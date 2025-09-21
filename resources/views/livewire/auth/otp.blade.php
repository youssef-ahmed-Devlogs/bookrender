<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Illuminate\Validation\ValidationException;

new #[Layout('components.layouts.auth.app')] class extends Component {
    #[Validate('required|numeric|digits:6')]
    public string $otp = '';

    public function mount()
    {
        $user = auth()?->user();

        if (is_null($user?->otp) || is_null($user?->otp_expired_at < now())) {
            Auth::guard('web')->logout();

            Session::invalidate();
            Session::regenerateToken();

            $this->redirect(route('login', absolute: false));
        }
    }

    /**
     * Handle an incoming otp request.
     */
    public function send(): void
    {
        $this->validate();

        $user = auth()->user();

        // if ($user->otp !== $this->otp || $user->otp_expired_at < now()) {
        //     throw ValidationException::withMessages([
        //         'otp' => 'This otp is invalid !',
        //     ]);
        // }

        $user->otp = NULL;
        $user->otp_verified_at = now();
        $user->otp_expired_at = NULL;
        $user->save();

        $routeName = 'dashboard.index';

        if (auth()->user()->role === 'admin') {
            $routeName = 'admin.index';
        }

        $this->redirectIntended(default: route($routeName, absolute: false));
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
                        <h4 class="pt-3 align-self-center ms-lg-2">CreateBook</h4>
                    </div>

                    <h1 class="mt-3 mt-md-0">Enter OTP</h1>

                    <form method="POST" wire:submit="send">
                        @csrf

                        <div class="position-relative w-100">
                            <input type="text" wire:model="otp" id="otp" placeholder="otp code"
                                class="form-control w-100">

                        </div>

                        @error('otp')
                            <div class="mt-1 text-danger">{{ $message }}</div>
                        @enderror

                        <button class="mt-3 main-btn">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>