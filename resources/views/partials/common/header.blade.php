<!-- Header -->
<div class="py-1 overflow-hidden d-flex justify-content-between border-bottom nav-create">
    <div class="mb-5 d-md-flex d-none align-self-center create ms-2 position-absolute">
        <a href="{{ route('dashboard.index') }}" class="d-flex">
            <img src="{{ asset('assets/dashboard/images/group1000007853.png') }}" class="ms-md-3 mb-md-1" alt="logo" />
            <h4 class="text-center mt-md-1 me-5 me-md-5 ms-md-2">Book Render</h4>
        </a>

    </div>
    <div class="mt-3 info_nav d-flex align-self-center ms-lg-3 ms-md-5 ms-1">
        <a href="{{ route('about-us') }}">
            <p class="text-black ms-2 ms-md-5 ms-lg-5 font-weight">About Us</p>
        </a>
        <a href="{{ route('pricing-plans') }}">
            <p class="text-black ms-4 font-weight">Plan & Pricing</p>
        </a>
    </div>

    <div class="btn_nav d-flex justify-content-between">
        <form action="#" id="main-search-form"
            class="button-container d-flex justify-content-center align-items-center me-2 ms-sm-5">
            <a href="#" class="text-white upgrade-button me-3">
                Upgrade to<span class="p-md-1 ms-1">PRO</span>
            </a>

            <input type="text" placeholder="Search" name="search" id="main-search-input"
                value="{{ request()->get('search') }}"
                class="main-search form-control ps-md-4 position-relative align-self-center" />
        </form>


        <div class="ms-4 d-flex me-3">

            <a href="#" class="align-self-center">
                <i class="fa-regular img_nav fa-circle-question me-2 align-self-center ps-3"></i>
            </a>

            <div id="bell-icon" class="align-self-center">
                <i class="fa-regular fa-bell me-2 ms-md-1">
                </i>
                <div class="d-none" id="notific">
                    <div class="shadow notification rounded-4 position-absolute">
                        <div class="p-3 info-notification d-flex border-bottom justify-content-between">
                            <h4>Notifications</h4>
                            <h4>Mark all as read</h4>
                        </div>
                        <div class="p-3 pb-0 border-bottom">

                            <div class="personal-notification d-flex ">
                                <h5>You have no notifications yet</h5>
                            </div>
                            <div class="d-flex ms-5 ps-3 icon">
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <div id="person-icon" class="align-self-center d-flex">
                <img src="{{ asset('assets/dashboard/images/image10.png') }}" class="align-self-center ms-md-2"
                    alt="user" />

                <i class="fa-solid fa-chevron-down fa-2xs align-self-center text-muted ms-1"></i>

                <div class="d-none" id="notific-person">
                    <div class="shadow notification sign rounded-4 position-absolute">
                        <div class="text-center d-flex justify-content-center border-bottom">
                            <div class="p-4 pb-1 personal ">
                                <img src="{{ asset('assets/dashboard/images/image16.png') }}" alt="">
                                <h3>{{ auth()->user()->fname . ' ' . auth()->user()->lname }}</h3>

                                <p>{{ auth()->user()->email }}</p>

                                <a href="#" class="py-2 mb-2 text-white upgrade-button d-block">
                                    Upgrade
                                    to<span class="p-md-1 ms-1">PRO</span>
                                </a>
                            </div>
                        </div>

                        <div class="p-4 info-personl border-bottom">
                            <a href="{{ route('dashboard.profile') }}"
                                class="{{ request()->routeIs('dashboard.profile') ? 'active' : '' }}">
                                <h4>My Profile</h4>
                            </a>

                            <a href="{{ route('dashboard.books.index') }}"
                                class="{{ request()->routeIs('dashboard.books.*') ? 'active' : '' }} mb-3 d-block h4">
                                <h4>My Projects</h4>
                            </a>
                            <h4 class="mb-3">Favorites</h4>
                            <a href="#" class="mb-3 d-block h4">
                                <h4>Manage Membership</h4>
                            </a>
                            <a href="#" class="mb-3 d-block h4">
                                <h4>Help</h4>
                            </a>

                        </div>

                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button type="submit" onclick="preventDefault()" class="bg-transparent border-0 text-dark">
                                <h4 class="p-4 pb-3 ">Logout</h4>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const form = document.getElementById('main-search-form');
    const mainSearchInput = document.getElementById('main-search-input');

    mainSearchInput.addEventListener('change', (e) => {
        if (e.target.value === '') {
            form.submit();
        }
    })
</script>