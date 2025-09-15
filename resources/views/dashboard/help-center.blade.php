<x-layouts.dashboard.app>
    <div class="pb-5 home">
        <div class="d-flex justify-content-center">
            <div class="text-center info-help">

                <h4>Help & Support</h4>

                <p class="mx-auto mt-1 mb-0 para-help">Find solutions, learn features, and get assistance with ease.</p>
                <p class="mx-auto para-help">
                    <a href="mailto:support@bookrender.com">support@bookrender.com</a>
                </p>

                <form action="{{ route('dashboard.help-center') }}" method="GET" class="d-flex justify-content-center">
                    <input type="text" placeholder="How can we help you?"
                        class="py-3 mx-auto mt-3 form-control rounded-3 w-100" name="faq_search"
                        value="{{ request()->get('faq_search') }}">
                </form>


                <h5 class="mt-5 freq">Frequently Asked Questions</h5>

                <div class="mx-auto mt-4 rounded-5">
                    <div class="accordion " id="accordionPanelsStayOpenExample" style="width: 500px;">
                        @foreach ($faqs as $faq)
                            <div class="mb-3 accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapse{{ $faq->id }}" aria-expanded="true"
                                        aria-controls="panelsStayOpen-collapse{{ $faq->id }}">
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapse{{ $faq->id }}" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        {{ $faq->answer }}
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layouts.dashboard.app>