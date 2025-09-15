<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\Newsletter;

class FrontendController extends Controller
{
    public function index()
    {
        return view('frontend.index');
    }

    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect()->to(url()->previous() . '#newsletter-message')
                ->withErrors($validator)
                ->withInput();
        }

        $message = "You have been subscribed";

        $exists = Newsletter::where('email', $request->email)->exists();

        if ($exists) {
            $message = 'You are already subscribed';
        } else {
            Newsletter::create([
                'email' => $request->email,
            ]);
        }

        return redirect()->to(url()->previous() . '#newsletter-message')->with('success',  $message);
    }

    public function aboutUs()
    {
        return view('frontend.about-us');
    }

    public function pricingPlans()
    {
        $plans = Plan::where('status', 'active')->get();
        return view('frontend.pricing-plans', compact('plans'));
    }

    public function affiliateProgram()
    {
        return view('frontend.affiliate-program');
    }

    public function refundPolicy()
    {
        return view('frontend.refund-policy');
    }

    public function privacyPolicy()
    {
        return view('frontend.privacy-policy');
    }

    public function termsConditions()
    {
        return view('frontend.terms-conditions');
    }
}
