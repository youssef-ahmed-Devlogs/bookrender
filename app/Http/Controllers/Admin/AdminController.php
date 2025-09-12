<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::count();
        $settings = Setting::first();
        $newusers = User::where('created_at', '>=', now()->subDays(7))->paginate(10);

        // المستخدمين اللي عندهم اشتراك غير مجاني
        $totalsubscribers = User::whereHas('subscriptions', function ($query) {
            $query->whereHas('plan', function ($query) {
                $query->where('is_free', 0);
            });
        })->count();

        // المستخدمين اللي عندهم اشتراك مجاني أو مافيش اشتراك أصلاً
        $unsupscribers = User::whereHas('subscriptions', function ($query) {
            $query->whereHas('plan', function ($query) {
                $query->where('is_free', 1);
            });
        })->orWhereDoesntHave('subscriptions')->count();

        // الاشتراكات حسب أنواع الخطط
        $free = Subscription::whereHas('plan', function ($query) {
            $query->where('is_free', 1);
        })->count();

        $basic = Subscription::whereHas('plan', function ($query) {
            $query->where('name', 'Basic Plan');
        })->count();

        $pro = Subscription::whereHas('plan', function ($query) {
            $query->where('name', 'Pro Plan');
        })->count();

        $lifetime = Subscription::whereHas('plan', function ($query) {
            $query->where('name', 'Lifetime Plan');
        })->count();

        // تجنب القسمة على صفر
        $totalsubscribers2 = $totalsubscribers == 0 ? 1 : $totalsubscribers;

        // حساب النسب
        $freepercent = ($free / $totalsubscribers2) * 100;
        $basicpercent = ($basic / $totalsubscribers2) * 100;
        $propercent = ($pro / $totalsubscribers2) * 100;
        $lifetimepercent = ($lifetime / $totalsubscribers2) * 100;


        $transactions = Transaction::whereMonth('created_at', now()->month)->get();
        // dd($transactions);
        // تجميع الإيرادات الشهرية (يمكنك تعديل ذلك حسب احتياجاتك)
        $revenueData = $transactions->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('d'); // تجميع حسب اليوم
        })->map(function ($day) {
            return $day->sum('price'); // جمع الإيرادات اليومية
        });

        return view('admin.index', [
            'users' => $users,
            'newusers' => $newusers,
            'totalsubscribers' => $totalsubscribers,
            'unsupscribers' => $unsupscribers,
            'free' => $freepercent,
            'basic' => $basicpercent,
            'Pro' => $propercent,
            'Lifetime' => $lifetimepercent,
            'revenueData' => $revenueData,
            'settings' => $settings
        ]);
    }
}
