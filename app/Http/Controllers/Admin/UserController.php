<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Setting;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Setting::first();

        $users = User::where('id', '!=', auth()->id())
            ->when(request()->get('search'), function ($query, $value) {
                $value = trim($value);
                $query->whereRaw("CONCAT(fname, ' ', lname) LIKE ?", ["%{$value}%"]);
            })
            ->paginate(20);

        return view('admin.users.index', [
            'users' => $users,
            'settings' => $settings
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $settings = Setting::first();
        $plans = Plan::all();
        $freePlan = $plans->where('is_free', 1)->first();

        return view('admin.users.create', [
            'plans' => $plans,
            'freePlan' => $freePlan,
            'settings' => $settings,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fname' => ['required', 'regex:/^[\pL\s]+$/u', 'max:10'],
            'lname' => ['required', 'regex:/^[\pL\s]+$/u', 'max:10'],
            'email' => 'required|email:rfc,dns|unique:users,email|max:25',
            'password' => 'required|min:8',
            'role' => ['required'],
            'subscription' => ['required'],
        ]);

        // إنشاء المستخدم
        $user = User::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        // إضافة الاشتراك للمستخدم
        $user->subscriptions()->create([
            'user_id' => $user->id,
            'plan_id' => $request->subscription,  // هنا إزالتها ووضعت الـ plan_id فقط
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'status' => 'active',
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $plans = Plan::all();
        $settings = Setting::first();

        $freePlan = $plans->where('is_free', 1)->first();
        $userSubscription = $user->subscriptions()->latest()->first();


        return view('admin.users.edit', [
            'user' => $user,
            'plans' => $plans,
            'settings' => $settings,
            'freePlan' => $freePlan,
            'userSubscription' => $userSubscription,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            // 'fname' => ['required', 'regex:/^[\pL\s]+$/u', 'max:10'],
            // 'lname' => ['required', 'regex:/^[\pL\s]+$/u', 'max:10'],
            'fname' => ['required', 'max:10'],
            'lname' => ['required', 'max:10'],
            'email' => 'required|email:rfc,dns|unique:users,email,' . $id,
            'max:25',
            'password' => 'nullable|min:8',
            'role' => 'required',
            'subscription' => 'required',
        ]);

        $user = User::find($id);

        if ($user) {
            // تحديث بيانات المستخدم
            $user->update([
                'fname' => $request->fname,
                'lname' => $request->lname,
                'email' => $request->email,
                'role' => $request->role,
                'password' => $request->password ? bcrypt($request->password) : $user->password, // إذا لم يتم إدخال كلمة مرور جديدة، احتفظ بكلمة المرور القديمة
            ]);

            // التأكد إذا كان للمستخدم اشتراك بالفعل
            $subscription = $user->subscriptions()->first(); // الحصول على الاشتراك المرتبط بالمستخدم

            if ($subscription) {
                // إذا كان لديه اشتراك، قم بتحديثه
                $subscription->update([
                    'plan_id' => $request->subscription,
                    'start_date' => now(),
                    'end_date' => now()->addMonth(),
                    'status' => 'active',
                ]);
            } else {
                // إذا لم يكن لديه اشتراك، قم بإنشاء اشتراك جديد
                $user->subscriptions()->updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'plan_id' => $request->subscription, // التأكد من أن الـ plan_id يتوافق مع الـ user_id
                    ],
                    [
                        'start_date' => now(),
                        'end_date' => now()->addMonth(),
                        'status' => 'active',
                    ]
                );
            }

            return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
        } else {
            return redirect()->back()->with('error', 'User not found');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return redirect()->back()->with('success', 'User deleted successfully');
        } else {
            return redirect()->back()->with('error', 'User not found');
        }
    }
}
