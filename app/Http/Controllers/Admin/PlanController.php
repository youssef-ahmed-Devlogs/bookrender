<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Subscription;
use App\Services\PaddleService;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Setting::first();
        $plans = Plan::paginate();

        $subscribers = User::whereHas('subscriptions', function ($query) {
            $query->whereHas('plan', function ($query) {
                $query->where('is_free', '!=',  1);
            });
        })->paginate(20);

        $activesubscriptions =  User::whereHas('subscriptions', function ($query) {
            $query->whereHas('plan', function ($query) {
                $query->where('is_free', '!=',  1);
            });
        })->count();

        $expiredsubscriptions = Subscription::where('status', 'expired')->count();
        $canceledsubscriptions = Subscription::where('status', 'canceled')->count();

        return view('admin.plans.index', [
            'plans' => $plans,
            'subscribers' => $subscribers,
            'activesubscriptions' => $activesubscriptions,
            'expiredsubscriptions' => $expiredsubscriptions,
            'canceledsubscriptions' => $canceledsubscriptions,
            'settings' => $settings
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $settings = Setting::first();
        $hasFreePlan = Plan::where('is_free', 1)->exists();

        return view('admin.plans.create', [
            'hasFreePlan' => $hasFreePlan,
            'settings' => $settings
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric',
            'book' => 'required',
            'word' => 'required',
            'status' => 'required|in:active,disactive',
        ]);

        $paddleService = new PaddleService();

        $paddleProduct = $paddleService->createProduct([
            'name' => $request->name,
            'description' => $request->description ?? $request->name,
        ]);

        $paddleProductPrice = $paddleService->createProductPrice([
            "product_id" => $paddleProduct['id'],
            "unit_price" => [
                'amount' => (string) ($request->price * 100),
                'currency_code' => 'USD',
            ],
            'description' => $request->description ?? $request->name,
        ]);

        Plan::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'book_number' => $request->book,
            'word_number' => $request->word,
            'status' => $request->status,
            'paddle_product_id' => $paddleProduct['id'],
            'paddle_price_id' => $paddleProductPrice['id'],
            'is_free' => $request->is_free
        ]);

        return redirect()->route('admin.plans.index')->with('success', 'Plan created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plan $plan)
    {
        $settings = Setting::first();
        $plan = Plan::find($plan->id);

        $hasFreePlan = Plan::where('is_free', 1)->exists();

        if ($plan->is_free == 1) {
            $hasFreePlan = 0;
        }

        return view('admin.plans.edit', [
            'plan' => $plan,
            'settings' => $settings,
            'hasFreePlan' => $hasFreePlan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric',
            'book' => 'required',
            'word' => 'required',
            'status' => 'required|in:active,disactive',
        ]);

        $plan->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'book_number' => $request->book,
            'word_number' => $request->word,
            'status' => $request->status,
            'is_free' => $request->is_free
        ]);

        return redirect()->route('admin.plans.index')->with('success', 'Plan updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        $plan->delete();
        return redirect()->back()->with('success', 'Plan deleted successfully');
    }

    public function toggleStatus(Request $request)
    {
        $request->validate([
            'plan_id' => ['required']
        ]);

        $plan = Plan::findOrFail($request->plan_id);

        $plan->status = ($plan->status === 'active') ? 'disactive' : 'active';
        $plan->save();

        return response()->json([
            'success' => true,
            'new_status' => $plan->status,
            'message' => 'Plan status updated successfully',
        ]);
    }
}
