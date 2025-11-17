<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use App\Mail\OTPMail;

new #[Layout('components.layouts.auth.app')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $user = auth()->user();

        $user->otp = rand(111111, 999999);
        $user->otp_verified_at = NULL;
        $user->otp_expired_at = now()->addMinutes(10);

        // Send otp in the email

        Mail::to($user->email)->send(new OTPMail($user, $user->otp));

        $user->save();

        $this->redirectIntended(default: route('otp', absolute: false));

    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
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
                    <div class="logo d-flex mb-lg-4 mb-md-0">
                        <img src="{{ asset('assets/auth/images/logo.png') }}" alt="logo">
                        <h4 class="pt-3 align-self-center ms-lg-2">Bookrender</h4>
                    </div>

                    <h1 class="mt-3 mt-md-0">Login to your account</h1>

                    <p class="my-4">
                        Don't have an account?
                        <a href="{{ route('register') }}" target="_self">
                            Sign Up
                        </a>
                    </p>

                    <form method="POST" wire:submit="login">
                        @csrf

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
                            <input type="checkbox" wire:model="remember" id="remember">
                            <label for="remember" class="ms-2">
                                Keep me logged in
                            </label>
                        </div>

                        <button class="mt-3 main-btn">Log in</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>