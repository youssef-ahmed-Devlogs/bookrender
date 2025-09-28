<x-layouts.dashboard.app>

    @push('styles')
        <style>
            .button_backup {
                background-color: #28a745;
                /* أخضر جذاب */
                color: white;
                padding: 12px 24px;
                font-size: 16px;
                font-weight: bold;
                border: none;
                border-radius: 12px;
                cursor: pointer;
                transition: background-color 0.3s ease, transform 0.2s ease;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .button_backup:hover {
                background-color: #218838;
                transform: scale(1.05);
            }

            .button_backup:active {
                transform: scale(0.98);
            }

            .plan-card {
                background: linear-gradient(180deg, #1ea2ff, #007bff);
                color: #fff;
                border-radius: 28px;
                padding: 40px 30px;
                position: relative;
                overflow: hidden;
                min-height: 480px;
            }

            .plan-card.border {
                background: #fff !important;
                color: #000;
            }

            .plan-card.border .plan-price {
                color: #0d6efd;
            }

            .plan-card ul {
                padding: 0;
                margin: 30px 0 40px;
                list-style: none;
            }

            .plan-card li {
                margin-bottom: 18px;
                font-size: 15px;
                font-weight: 500;
                display: flex;
                align-items: center;
            }

            .plan-card li i {
                font-size: 18px;
                margin-right: 10px;
            }

            .plan-card.border li i {
                color: #0d6efd;
            }

            .plan-btn {
                border-radius: 40px;
                font-weight: 600;
                padding: 14px 0;
                width: 100%;
            }

            .old-price {
                text-decoration: line-through;
                font-size: 14px;
                margin-left: 8px;
                opacity: .8;
            }

            .plan-divider {
                height: 1px;
                background: rgba(255, 255, 255, .4);
                margin: 20px 0;
            }

            .plan-card.border .plan-divider {
                background: #e0e0e0;
            }

            .plan-price {
                font-size: 28px;
                font-weight: 700;
                color: #ffffff;
            }
        </style>
    @endpush

    <div class="p-4 modal-content rounded-4" style="width:80%;margin: 0 auto">
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <h3 class="m-0 text-center w-100" style="font-weight:600;">Subscription Plan</h3>
            <a href="{{ route('dashboard.index') }}" class="btn-close"></a>
        </div>
        <div class="flex-wrap px-2 row g-4">
            @php
                $userSubscribedPlanIds = auth()->user()->subscriptions->pluck('plan_id')->toArray();
                $displayPlans = $plans->unique('id')->take(2)->values();
            @endphp
            @foreach ($displayPlans as $index => $plan)
                <div class="col-md-6">
                    <div class="plan-card {{ $index == 0 ? '' : 'border' }} shadow-sm h-100">
                        <div class="d-flex justify-content-between align-items-start plan-header">
                            <h5 class="m-0 fw-bold">{{ $plan->name }}</h5>
                            <div class="text-end">
                                <span class="plan-price">${{ number_format($plan->price, 2) }}<span
                                        class="text-white fs-6 fw-normal">/month</span></span><br>
                                @if ($plan->old_price)
                                    <span class="old-price">${{ number_format($plan->old_price, 2) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="plan-divider"></div>
                        <ul class="mb-4 list-unstyled">
                            <li><i
                                    class="fa fa-check-circle {{ $index == 0 ? '' : 'text-primary' }}"></i>{{ $plan->book_number }}
                                books allowed</li>
                            <li><i
                                    class="fa fa-check-circle {{ $index == 0 ? '' : 'text-primary' }}"></i>{{ $plan->word_number }}
                                words / month</li>
                            @foreach (preg_split('/\r?\n/', trim($plan->description)) as $feature)
                                @if ($feature)
                                    <li><i class="fa fa-check-circle {{ $index == 0 ? '' : 'text-primary' }}"></i>{{ $feature }}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                        @if (in_array($plan->id, $userSubscribedPlanIds))
                            <button
                                class="btn btn-outline-light plan-btn {{ $index == 0 ? '' : 'text-primary border-primary' }}"
                                disabled>Subscribed</button>
                        @else
                            <button
                                class="btn {{ $index == 0 ? 'btn-outline-light text-white' : 'btn-primary text-white' }} plan-btn"
                                data-price-id="{{ $plan->paddle_price_id }}" data-product-id="{{ $plan->paddle_product_id }}"
                                onclick="openCheckout(this)">
                                Subscribe Now
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <p class="mt-3 mb-0 text-center fst-italic" style="font-size:12px;">* <a href="{{ route('terms-conditions') }}"
                class="text-black">Terms &amp; Condition</a> applicable</p>
    </div>

    @push('scripts')
        <script src="https://cdn.paddle.com/paddle/v2/paddle.js"></script>

        <script type="text/javascript">
            Paddle.Environment.set("sandbox");
            Paddle.Initialize({
                token: "{{ config('paddle.client_token') }}"
            });

            // open checkout
            function openCheckout(button) {
                Paddle.Checkout.open({
                    items: [{
                        priceId: button.dataset.priceId,
                        quantity: 1,
                    }],
                    customer: {
                        email: "{{ auth()->user()->email }}"
                    },
                    customData: {
                        user_id: "{{ auth()->id() }}",
                        user_email: "{{ auth()->user()->email }}"
                    }
                });
            }
        </script>
    @endpush
</x-layouts.dashboard.app>