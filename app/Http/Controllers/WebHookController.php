<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Plan;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Subscription;

class WebHookController extends Controller
{
    /**
     * Handle Paddle webhook callbacks for payment events
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function paddleCallback(Request $request)
    {
        $request->validate([
            'event_type' => ['required'],
            'data' => ['required'],
        ]);

        $eventType = $request->input('event_type');
        $transactionData = $request->input('data');

        // Log the webhook for debugging
        Log::info('Paddle Webhook Received', [
            'event_type' => $eventType,
            'transaction_id' => $transactionData['id'] ?? null,
        ]);

        try {
            switch ($eventType) {
                case 'transaction.paid':
                    return $this->handleSuccessfulPayment($transactionData);

                case 'transaction.payment_failed':
                    return $this->handleFailedPayment($transactionData);

                default:
                    Log::info('Unhandled webhook event type', ['event_type' => $eventType]);
                    return response()->json(['message' => 'Event type not handled'], 200);
            }
        } catch (\Exception $e) {
            Log::error('Webhook processing error', [
                'error' => $e->getMessage(),
                'event_type' => $eventType,
                'transaction_id' => $transactionData['id'] ?? null,
            ]);

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Handle successful payment transactions
     * 
     * @param array $transactionData
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleSuccessfulPayment($transactionData)
    {
        $transactionId = $transactionData['id'];
        $priceId = $transactionData['items'][0]['price_id'] ?? null;
        $customerId = $transactionData['customer_id'] ?? null;
        $amount = $transactionData['details']['totals']['total'] ?? 0;
        $currencyCode = $transactionData['details']['totals']['currency_code'] ?? 'USD';

        // Find the plan by paddle price ID
        $plan = Plan::where('paddle_price_id', $priceId)->first();
        if (!$plan) {
            Log::error('Plan not found for price ID', ['price_id' => $priceId]);
            return response()->json(['error' => 'Plan not found'], 404);
        }

        // Find user using multiple identification strategies (user_id, email, customer_id)
        $user = $this->findUserFromTransaction($customerId, $transactionData);
        if (!$user) {
            Log::error('User not found for customer', ['customer_id' => $customerId]);
            return response()->json(['error' => 'User not found'], 404);
        }

        // Create or update subscription
        $subscription = $user->subscriptions()->latest()->first();

        if (!$subscription || $subscription->plan_id !== $plan->id) {

            $user->subscriptions()
                ->whereNot('status', 'canceled')
                ->update([
                    'status' => 'canceled',
                ]);

            $subscription = $user->subscriptions()->create([
                'plan_id' => $plan->id,
                'status' => 'active',
                'start_date' => now(),
                'end_date' => $this->calculateEndDate($plan),
            ]);
        } else {
            $subscription->update([
                'status' => 'active',
                'end_date' => $this->calculateEndDate($plan),
            ]);
        }

        // Record the transaction
        Transaction::create([
            'plan_id' => $plan->id,
            'user_id' => $user->id,
            'price' => (int) number_format(floatval($amount / 100), 0, '', ''),
            'transaction_id' => $transactionId,
            'status' => 'paid',
            'payment_method' => $transactionData['payments'][0]['method_details']['type'] ?? 'card',
            'currency_code' => $currencyCode,
        ]);

        Log::info('Payment processed successfully', [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'transaction_id' => $transactionId,
        ]);

        return response()->json(['message' => 'Payment processed successfully'], 200);
    }

    /**
     * Handle failed payment transactions
     * 
     * @param array $transactionData
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleFailedPayment($transactionData)
    {
        $transactionId = $transactionData['id'];
        $priceId = $transactionData['items'][0]['price_id'] ?? null;
        $customerId = $transactionData['customer_id'] ?? null;
        $amount = $transactionData['details']['totals']['total'] ?? 0;
        $currencyCode = $transactionData['details']['totals']['currency_code'] ?? 'USD';
        $errorCode = $transactionData['payments'][0]['error_code'] ?? 'unknown_error';

        // Find the plan by paddle price ID
        $plan = Plan::where('paddle_price_id', $priceId)->first();
        if (!$plan) {
            Log::error('Plan not found for failed payment', ['price_id' => $priceId]);
            return response()->json(['error' => 'Plan not found'], 404);
        }

        // Find user
        $user = $this->findUserFromTransaction($customerId, $transactionData);
        if (!$user) {
            Log::error('User not found for failed payment', ['customer_id' => $customerId]);
            return response()->json(['error' => 'User not found'], 404);
        }

        // Record the failed transaction
        Transaction::create([
            'plan_id' => $plan->id,
            'user_id' => $user->id,
            'price' => $amount,
            'transaction_id' => $transactionId,
            'status' => 'failed',
            'payment_method' => $transactionData['payments'][0]['method_details']['type'] ?? 'card',
            'currency_code' => $currencyCode,
            'error_code' => $errorCode,
        ]);

        Log::info('Failed payment recorded', [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'transaction_id' => $transactionId,
            'error_code' => $errorCode,
        ]);

        return response()->json(['message' => 'Failed payment recorded'], 200);
    }

    /**
     * Find user from Paddle transaction data using multiple identification strategies.
     * Attempts to match user by ID first, then by email, with support for both
     * array and JSON string custom_data formats.
     * 
     * @param string $customerId Paddle customer ID
     * @param array $transactionData Full transaction data from webhook
     * @return User|null Found user or null if not found
     */
    private function findUserFromTransaction($customerId, $transactionData)
    {
        // Method 1: If you store paddle_customer_id in users table (future enhancement)
        // $user = User::where('paddle_customer_id', $customerId)->first();
        // if ($user) return $user;

        // Extract custom data (supports both array and JSON string formats)
        $customData = $this->extractCustomData($transactionData);

        if ($customData) {
            // Method 2: Find by user_id (most reliable)
            $user = $this->findUserById($customData);
            if ($user) return $user;

            // Method 3: Find by email (fallback)
            $user = $this->findUserByEmail($customData);
            if ($user) return $user;
        }

        // Method 4: Get customer details from Paddle API and match by email (future enhancement)
        // $user = $this->findUserByPaddleCustomerEmail($customerId);
        // if ($user) return $user;

        Log::warning('User not found for customer', [
            'customer_id' => $customerId,
            'custom_data' => $transactionData['custom_data'] ?? null
        ]);

        return null;
    }

    /**
     * Extract custom data from transaction, handling both array and JSON string formats
     * 
     * @param array $transactionData
     * @return array|null
     */
    private function extractCustomData($transactionData)
    {
        if (!isset($transactionData['custom_data'])) {
            return null;
        }

        $customData = $transactionData['custom_data'];

        // If it's already an array, return it
        if (is_array($customData)) {
            return $customData;
        }

        // If it's a JSON string, decode it
        if (is_string($customData)) {
            $decoded = json_decode($customData, true);
            return $decoded ?: null;
        }

        return null;
    }

    /**
     * Find user by ID from custom data
     * 
     * @param array $customData
     * @return User|null
     */
    private function findUserById($customData)
    {
        $userId = $customData['user_id'] ?? null;
        if (!$userId) return null;

        $user = User::find($userId);
        if ($user) {
            Log::info('User found by custom_data user_id', ['user_id' => $userId]);
            return $user;
        }

        return null;
    }

    /**
     * Find user by email from custom data
     * 
     * @param array $customData
     * @return User|null
     */
    private function findUserByEmail($customData)
    {
        $userEmail = $customData['user_email'] ?? null;
        if (!$userEmail) return null;

        $user = User::where('email', $userEmail)->first();
        if ($user) {
            Log::info('User found by custom_data email', ['email' => $userEmail]);
            return $user;
        }

        return null;
    }

    /**
     * Calculate subscription end date based on plan
     * 
     * @param Plan $plan
     * @return \Carbon\Carbon
     */
    private function calculateEndDate($plan)
    {
        // Assuming monthly billing - adjust based on your plan structure
        return now()->addMonth();
    }
}
