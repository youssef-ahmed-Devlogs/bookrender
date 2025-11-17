<x-layouts.frontend.app>
    @php
        $settings = \App\Models\Setting::first();
    @endphp

    <section>
        <div class="home">
            <div class="container-nav">
                <nav>
                    <div class="d-flex justify-content-between">
                        <div class="mt-3 icon-main d-flex align-items-center">
                            <img src="{{ $settings->logo ? asset($settings->logo) : asset('assets/common/images/logo.png') }}"
                                class="me-3" alt="">
                            <h4 class="text-center mt-md-1 me-5 me-md-5 ms-md-2">Bookrender</h4>
                        </div>
                        <div>
                            <a href="{{ route('dashboard.index') }}" class=" text-decoration-none">
                                <div class="d-flex align-items-center">
                                    <button class="mt-3 button-main">GET STARTED</button>
                                </div>
                            </a>

                        </div>
                    </div>
                </nav>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mt-5 home_info">
                            <h1>Create a book with our easy to use tool</h1>
                            <a href="{{ route('dashboard.index') }}" class=" text-decoration-none">
                                <button class="mt-3 button-main ">LEARN MORE<i
                                        class="fa-solid fa-arrow-right ms-2"></i></button>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 d-none d-md-flex">
                        <div class="home_img">
                            <img src="{{ asset('assets/frontend/images/image6.png') }}"
                                class="position-relative z-2 img " alt="">
                            <img src="{{ asset('assets/frontend/images/image7.png') }}" class="position-absolute img-1 "
                                alt="">
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>

    <section class="next-section">
        <div class="container-home">
            <div class="row g-1">
                <h3 class="pt-5 pb-5 text-center">Coming <span>Soon</span></h3>

                <div class="mb-2 col-lg-3 col-md-6 next-section-home mb-md-0 d-flex">
                    <img src="{{ asset('assets/frontend/images/group11.png') }}" alt="">
                    <h5 class="align-self-center ms-2">Easy drag-and-drop editor</h5>
                </div>
                <div class="mb-2 col-lg-3 col-md-6 next-section-home mb-md-0 d-flex">
                    <img src="{{ asset('assets/frontend/images/group11.png') }}" alt="">
                    <h5 class="align-self-center ms-2">3M+ free stock photos and graphics</h5>
                </div>
                <div class="mb-2 col-lg-3 col-md-6 next-section-home mb-md-0 d-flex">
                    <img src="{{ asset('assets/frontend/images/group11.png') }}" alt="">
                    <h5 class="align-self-center ms-2 ">Generate content and media with AI</h5>
                </div>
                <div class="mb-2 col-lg-3 col-md-6 next-section-home mb-md-0 d-flex">
                    <img src="{{ asset('assets/frontend/images/group11.png') }}" alt="">
                    <h5 class="align-self-center ms-2">Custom templates for your book layout</h5>
                </div>
            </div>

            <div class="text-center info-home">
                <h3>Make Your Book With <span>Book Render</span></h3>
                <p>Create your dream book for free with our AI book generator. Launch your eBook or paperback
                    effortlessly using
                    powerful AI tools. Design, write, and export with ease — perfect for both beginners and pros. No
                    writing
                    experience? No problem. Just bring your idea, and our AI book generator will do the rest</p>
                <img src="{{ asset('assets/frontend/images/group60.png') }}" class="w-100" alt="main home">
            </div>

            <div class="row align-items-center g-5 img-book">
                <div class="text-center col-lg-8 col-md-12">
                    <img src="{{ asset('assets/frontend/images/group1000008230.png') }}" alt="book"
                        class="img-fluid w-100">
                </div>

                <div class="col-lg-4 col-md-12">
                    <h2><span class="highlight">How to</span> Create A Book</h2>
                    <ul class="steps">
                        <li><span class="step-line"></span> Open Our Webapp</li>
                        <li><span class="step-line"></span> Select "Book" option</li>
                        <li><span class="step-line gray"></span> Click "Generate" button</li>
                        <li><span class="step-line gray"></span> Preview or edit the book</li>
                        <li><span class="step-line gray"></span> Download PDF or Word file</li>
                    </ul>
                    <a href="{{ route('register') }}" class=" text-decoration-none">
                        <button class="button-main ">START NEW BOOK<i class="fa-solid fa-arrow-right ms-2"></i></button>
                    </a>
                </div>
            </div>




            <div class="py-5 mx-auto slid">
                <div class="container">
                    <h3 class="pt-5 pb-5 text-center">What <span>Our Client</span> Say</h3>
                    <div class="pb-5 row justify-content-between align-items-center">

                        <div class="icon-home-2 left d-flex align-items-center justify-content-center" id="prev">
                            <i class="fa-solid fa-arrow-left fa-lg"></i>
                        </div>

                        <div class="overflow-hidden col-10">
                            <div class="transition slider-track d-flex">

                                @foreach (\App\Models\Rating::limit(10)->get() as $rating)
                                    <div class="px-2 col-md-6">
                                        <img src="{{ asset("storage/{$rating->image}") }}" class="w-100" alt="comment">
                                    </div>
                                @endforeach

                            </div>
                        </div>

                        <div class="icon-home right d-flex align-items-center justify-content-center" id="next">
                            <i class="fa-solid fa-arrow-right fa-lg"></i>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>


</x-layouts.frontend.app>