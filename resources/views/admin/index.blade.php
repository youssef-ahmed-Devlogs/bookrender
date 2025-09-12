<x-layouts.admin.app>
    <div class="img_main position-relative">
        <img src="{{ asset('assets/admin/images/vector5.png') }}" class="top-0 position-absolute" alt="">
        <h1 class="pt-5">Welcome</h1>
        <h1 class="color_bakup index-main">{{ auth()->user()->fname . ' ' . auth()->user()->lname }}</h1>
        <img src="{{ asset('assets/admin/images/vector7.png') }}" class="position-absolute img_line" alt="">
        <img src="{{ asset('assets/admin/images/group3.png') }}" class="position-absolute img_line2" alt="">
        <img src="{{ asset('assets/admin/images/vector4.png') }}" class="position-absolute img_line2" alt="">
        <img src="{{ asset('assets/admin/images/vector8.png') }}" class="position-absolute img_line3" alt="">
    </div>

    <!-- Analytics -->
    <section class="analytics">
        <div class="container mt-4">
            <div class="row">
                <div class="mt-4 col-lg-4 col-md-12 ">
                    <div class="mb-4 w-100 ms-2 ms-md-0 ">
                        <div class="p-4 shadow card-1 rounded-4">
                            <h6>all users</h6>
                            <div class="visitor d-flex justify-content-between ">
                                <div class="d-flex paper">
                                    <img src="{{ asset('assets/admin/images/group.png') }}" class="align-self-center"
                                        alt="">
                                    <h3 class="ms-4 align-self-center">{{ $users }}</h3>
                                </div>
                                <img src="{{ asset('assets/admin/images/group1000008082.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 ms-2 ms-md-0 w-100 rounded-4">
                        <div class="p-4 shadow card-1 rounded-4">
                            <h6>totol subscripers</h6>
                            <div class="visitor d-flex justify-content-between ">
                                <div class="d-flex paper">
                                    <img src="{{ asset('assets/admin/images/group1.png') }}" class="align-self-center"
                                        alt="">
                                    <h3 class="ms-4 align-self-center">{{ $totalsubscribers }}</h3>
                                </div>
                                <img src="{{ asset('assets/admin/images/group1000008082.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 ms-2 ms-md-0 rounded-4">
                        <div class="p-4 shadow card-1 rounded-4">
                            <h6>Today's Visitors/Traffic</h6>
                            <div class="visitor d-flex justify-content-between ">
                                <div class="d-flex paper">
                                    <img src="{{ asset('assets/admin/images/group2.png') }}" class="align-self-center"
                                        alt="">
                                    <h3 class="ms-4 align-self-center">895</h3>
                                </div>
                                <img src="{{ asset('assets/admin/images/group10000080822.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12">
                    <div class="p-4 shadow card ms-2 ms-md-0">
                        <div class="d-flex justify-content-between">
                            <div class="my-3">
                                <h6 class="mb-3">Monthly Revenue</h6>
                                <div class="d-flex">
                                    <h3>${{ $revenueData->sum() }} </h3><span class="text-muted ms-3"></span>
                                </div>
                            </div>
                            <div class="mt-4 text-center card_profit">
                                <span class="ms-5">3,5%</span>
                                <p class="text-muted">Overall profit</p>
                            </div>
                        </div>
                        <div class="chart bg-light position-relative">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>

                <script>
                    const ctx = document.getElementById('revenueChart').getContext('2d');
                    const revenueData = @json($revenueData); // تحويل البيانات من PHP إلى JavaScript

                    const days = Object.keys(revenueData);
                    const revenues = Object.values(revenueData);

                    const chart = new Chart(ctx, {
                        type: 'line', // أو يمكن أن تستخدم 'bar' أو 'pie' حسب ما تفضل
                        data: {
                            labels: days,
                            datasets: [{
                                label: 'Revenue',
                                data: revenues,
                                borderColor: 'rgba(75, 192, 192, 1)',
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                </script>

            </div>
        </div>

        <div class="container pb-4">
            <div class="row g-4" style="display: flex !important; flex-direction: row !important;">
                <div class="row g-4">
                    <!-- History Card -->
                    <div class="col-md-12 col-lg-4">
                        <div class="p-4 shadow card ms-2 ms-md-0">
                            <div class="d-flex justify-content-between info_security">
                                <h5 class="pb-1">Recent Users</h5>
                                <a href="#" class="">Show : <span class="text-muted">All
                                        History</span></a>
                            </div>
                            <ul class="mt-4 list-unstyled">
                                @foreach ($newusers as $user)
                                    <li class="mb-4"><span class="text-success">●</span>
                                        <strong>{{ $user->fname . ' ' . $user->lname }}
                                            <span class="text-muted float-end">
                                                {{ $user->created_at->diffForHumans() }}
                                            </span>
                                        </strong>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Recapitulation Card -->
                    <div class="col-md-4">
                        <div class="p-3 shadow card h-100 ms-2 ms-md-0">
                            <div class="d-flex rec justify-content-between">
                                <h5>Recapitulation</h5>
                                <select class="w-auto form-select">
                                    <option>Monthly</option>
                                </select>
                            </div>

                            <div class="mt-4 mb-5 chart-container mb-md-5">
                                <div class="">free: {{ $free ?? 0 }}%</div>
                                <div class="">basic: {{ $basic ?? 0 }}%</div>
                                <div class="">pro: {{ $Pro ?? 0 }}%</div>
                                <div class="">lifetime: {{ $Lifetime ?? 0 }}%</div>
                            </div>

                            <hr>
                            <div class="text-center d-flex justify-content-between">
                                <div class="ms-3">
                                    <p>{{ $totalsubscribers }}</p>
                                    <span class="text-muted">Subscribers</span>
                                </div>
                                <div class="px-4 new-user">
                                    <p>{{ $newusers->count() }}</p>
                                    <span class="text-muted">New users</span>
                                </div>
                                <div class="me-3">
                                    <p>{{ $unsupscribers }}</p>
                                    <span class="text-muted">Unsubscribe</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</x-layouts.admin.app>