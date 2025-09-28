<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Support\Facades\Redirect;
use ProtoneMedia\LaravelPaddle\Paddle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Plan;
use App\Models\User;
use App\Models\Setting;
use App\Models\Transaction;
use Carbon\Carbon;

class PlanController extends Controller
{
    public function index()
    {
        $settings = Setting::first();
        $plans = Plan::where('status', 'active')->get();

        return view("dashboard.plans.index", [
            "plans" => $plans,
            "settings" => $settings,
        ]);
    }

    public function success()
    {
        return view('dashboard.plans.success');
    }

    public function failure()
    {
        return view('dashboard.plans.fail');
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->all();

        // تأكيد التوقيع القادم من Paddle
        if (! \ProtoneMedia\LaravelPaddle\Paddle::isValidWebhook($payload)) {
            return response('Invalid signature', 400);
        }

        // نتعامل فقط مع تنبيه نجاح دفع الاشتراك
        if ($payload['alert_name'] !== 'subscription_payment_succeeded') {
            return response('Ignored', 200);
        }

        // العثور على الخطة باستخدام subscription_plan_id القادم من Paddle
        $plan = Plan::where('paddle_plan_id', $payload['subscription_plan_id'])->first();
        if (! $plan) {
            return response('Plan not found', 404);
        }

        // نحاول أولاً استخراج user_id من passthrough (إذا أرسلناه وقت الـ checkout)
        $passthroughUserId = null;
        if (! empty($payload['passthrough'])) {
            $pt = json_decode($payload['passthrough'], true);
            $passthroughUserId = $pt['user_id'] ?? null;
        }

        $userId = $passthroughUserId ?: ($payload['user_id'] ?? null);
        $user = User::find($userId);
        if (! $user) {
            return response('User not found', 404);
        }

        // إنشاء أو تحديث الاشتراك
        $subscription = $user->subscriptions()->latest()->first();

        if (! $subscription || $subscription->plan_id !== $plan->id) {
            $subscription = $user->subscriptions()->create([
                'plan_id'    => $plan->id,
                'status'     => 'active',
                'start_date' => now(),
                'end_date'   => now()->addMonth(),
            ]);
        } else {
            $subscription->update([
                'plan_id'    => $plan->id,
                'status'     => 'active',
                'end_date'   => now()->addMonth(),
            ]);
        }

        // تسجيل المعاملة
        \App\Models\Transaction::create([
            'plan_id' => $plan->id,
            'user_id' => $user->id,
            'price'   => $payload['sale_gross'] ?? $payload['amount'] ?? 0,
        ]);

        return response('OK', 200);
    }

    public function checkAndUpdateSubscription(Request $request)
    {
        $user = Auth::user();
        $freePlan = Plan::where('is_free', '1')->first();
        if ($user) {

            $subscription = $user->subscriptions()->latest()->first();


            if ($subscription && Carbon::now()->gt($subscription->end_date)) {

                $subscription->update([
                    'plan_id' => $freePlan->id,
                    'status' => 'active',
                    'start_date' => Carbon::now(),
                    'end_date' => Carbon::now()->addMonth(),
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Your subscription has been updated to the free plan.'
                ]);
            }

            // إذا لم تنتهِ صلاحية الاشتراك
            return response()->json([
                'status' => 'info',
                'message' => 'Your subscription is still active.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated.'
            ], 401); // ترجع خطأ 401 إذا لم يكن المستخدم مسجل دخول
        }
    }
}
