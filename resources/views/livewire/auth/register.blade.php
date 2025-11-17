<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Validation\Rules\Password;

new #[Layout('components.layouts.auth.app')] class extends Component {
    public string $fname = '';
    public string $lname = '';
    public string $email = '';
    public string $password = '';
    public int $agreement = 0;

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'fname' => ['required', 'string', 'max:10'],
            'lname' => ['required', 'string', 'max:10'],
            'email' => ['required', 'string', 'lowercase', 'email:rfc,dns', 'max:40', 'unique:' . User::class],
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
            ],
            'agreement' => ['required', 'boolean']
        ], [
            'fname.required' => 'First Name is required.',
            'lname.required' => 'Last Name is required.',
            'fname.max' => 'First Name must not be greater than 10 characters.',
            'lname.max' => 'Last Name must not be greater than 10 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email must not be greater than 40 characters.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.mixedCase' => 'The password must contain both uppercase and lowercase letters.',
            'password.numbers' => 'The password must include at least one number.',
            'password.symbols' => 'The password must include at least one symbol.',
        ]);

        $validated['password'] = Hash::make($validated['password']);


        $user = User::create($validated);

        event(new Registered($user));

        Auth::login($user);

        $this->redirectIntended(route('dashboard.index', absolute: false));
    }
}; ?>

<section class="my-5">
    <div class="container-fluid">
        <div class="row justify-content-center ms-2">
            <div class="col-md-6 d-none d-md-flex">
                <img src="{{ asset('assets/auth/images/image.png') }}" class="w-100 h-100" alt="user">
            </div>

            <div class="col-md-6 login d-flex justify-content-center align-items-center">
                <div class="">
                    <div class="logo d-flex mb-lg-4 mb-md-0 ms-5">
                        <img src="{{ asset('assets/auth/images/logo.png') }}" alt="logo">
                        <h4 class="pt-3 align-self-center ms-lg-2">Bookrender</h4>
                    </div>

                    <h1 class="mt-3 ms-5 mt-md-0">Create an account</h1>

                    <p class="my-4 ms-5">
                        Already have an account?
                        <a href="{{ route('login') }}" target="_self">
                            Log in
                        </a>
                    </p>

                    <form method="POST" wire:submit="register" class="ms-5 w-75">
                        @csrf

                        <div class="d-flex">
                            <div class="w-50 me-2">
                                <input type="text" wire:model="fname" placeholder="First Name"
                                    class="mt-2 form-control">
                                @error('fname')
                                    <div class="mt-1 text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="w-50">
                                <input type="text" wire:model="lname" placeholder="Last Name" class="mt-2 form-control">
                                @error('lname')
                                    <div class="mt-1 text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <input type="text" wire:model="email" id="email" placeholder="Enter your Email"
                            class="mt-2 mb-2 form-control w-100">
                        @error('email')
                            <div class="mt-1 text-danger">{{ $message }}</div>
                        @enderror
                        <div class="mt-1 text-danger" id="email-error"></div>

                        <div class="position-relative w-100">
                            <input type="password" wire:model="password" placeholder="Password"
                                class="form-control w-100">
                            <i class="fa-solid fa-eye position-absolute"
                                style="top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;"></i>
                        </div>
                        @error('password')
                            <div class="mt-1 text-danger">{{ $message }}</div>
                        @enderror

                        <div class="mt-3 mb-4 checkbox-group d-flex">
                            <input type="checkbox" wire:model="agreement" id="agree">
                            <label for="agree" class="ms-2">I agree to the <a {{--
                                    href="{{ route('terms-conditions') }}"> --}}
                                    href="#">
                                    Terms & Conditions
                                </a></label>
                        </div>

                        <button class="mt-3 main-btn">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>