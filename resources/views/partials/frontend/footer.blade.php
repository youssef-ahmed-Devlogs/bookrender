@php
    $address = \App\Models\AppSetting::where('key', 'address')->first();
    $contactEmail = \App\Models\AppSetting::where('key', 'contact_email')->first();

    $facebook = \App\Models\AppSetting::where('key', 'facebook')->first();
    $twitter = \App\Models\AppSetting::where('key', 'twitter')->first();
    $youtube = \App\Models\AppSetting::where('key', 'youtube')->first();
@endphp


<footer class="footer position-relative">
    <div class="container">
        <div class="d-md-flex ">
            <div class="pb-5 w-50 footer1">
                <h4 class="pt-2 pb-2 footer-title">Book Render</h4>
                <p class="pb-4 text-white ">Create stunning books with our AI book generator. Design and publish
                    professional-quality books effortlessly — trusted by thousands of authors worldwide.</p>

                <h5>Subscribe Our Newsletter</h5>
                {{-- <form action="{{ route('newsletter.subscribe') }}" method="POST" class="mt-2 input-container"> --}}
                    <form action="#" method="POST" class="mt-2 input-container">
                        @csrf
                        <input type="text" placeholder="Your Email" name="email">
                        <button class="btn">Subscribe<i class="fa-solid fa-paper-plane fa-sm ms-2"></i></button>
                    </form>

                    <div id="newsletter-message">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                        @if (session('success'))
                            <div class="text-success">{{ session('success') }}</div>
                        @endif
                    </div>
            </div>
            <div class="mt-3 footer2 mt-md-0">
                <h5 class="pb-2">Company</h5>
                <ul class="footer-links">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Pricing Plan</a></li>
                    <li><a href="#">Affiliate Program</a></li>
                    <li><a href="#">Refund Policy</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms and Conditions</a></li>
                </ul>
            </div>
            <div class="mt-3 footer3 mt-md-0">
                <h5 class="pb-2">Get In Touch</h5>
                <ul class="footer-contact">
                    <div class="d-flex">
                        <div class="icon-footer d-flex justify-content-center align-items-center">
                            <i class="fa-solid fa-location-dot fa-sm align-items-center align-self-center ms-2 "></i>
                        </div>

                        <li class="pb-2 ms-2">
                            <span>Our Address</span>
                            <br>
                            {{ nl2br($address?->value) }}
                        </li>
                    </div>

                    <div class="d-flex">
                        <div class="icon-footer d-flex justify-content-center align-items-center"><i
                                class="fa-solid fa-envelope fa-sm align-items-center align-self-center ms-2"></i></div>
                        <li class="pb-2 ms-2"> <span>Mail Us</span>
                            <br>
                            {{ $contactEmail?->value }}
                        </li>
                    </div>

                </ul>
            </div>
        </div>
        <hr>
        <div class="pb-2 footer-bottom ">
            <p>© Copyright 2024 <span class="highlight">Book Render</span>. All Rights Reserved.</p>
            <div class="social-icons d-flex">


                <div class="social-icon-home me-3 d-flex justify-content-center align-items-center">
                    <a href="{{ $facebook?->value }}" class=" align-self-center">
                        <i class="fa-brands fa-facebook fa-sm me-3"></i>
                    </a>
                </div>

                <div class="social-icon-home me-3 d-flex justify-content-center align-items-center">
                    <a href="{{ $twitter?->value }}"><i class="fa-brands fa-x-twitter fa-sm me-3"></i></a>
                </div>

                <div class="social-icon-home me-3 d-flex justify-content-center align-items-center">
                    <a href="{{ $youtube?->value }}"><i class="fa-brands fa-youtube fa-sm me-3"></i></a>
                </div>



            </div>

        </div>

    </div>
    <div class="scroll-top d-flex justify-content-center align-items-center position-fixed" id="scrollTopBtn">
        <button class="bg-transparent border-0 test-white"><i
                class="text-white fa-solid fa-arrow-up fa-lg"></i></button>
    </div>

</footer>