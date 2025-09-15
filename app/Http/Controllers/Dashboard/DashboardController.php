<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Setting;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $books = auth()->user()->projects()
            ->where('created_at', '>=', Carbon::now()->subWeek())
            ->get();
        return view("dashboard.index", [
            // 'settings' => $settings,
            'books' => $books,
        ]);
    }

    public function profile()
    {
        $settings = Setting::first();
        $user = auth()->user();

        return view('dashboard.profile', [
            'settings' => $settings,
            'user' => $user
        ]);
    }

    public function helpCenter()
    {
        $settings = Setting::first();

        $faqs = Faq::when(request()->get('faq_search'), function ($builder) {
            $builder->where('question', 'LIKE', '%' . request()->get('faq_search') . '%');
            $builder->orWhere('answer', 'LIKE', '%' . request()->get('faq_search') . '%');
        })->paginate(3);

        return view('dashboard.help-center', [
            'settings' => $settings,
            'faqs' => $faqs,
        ]);
    }
}
