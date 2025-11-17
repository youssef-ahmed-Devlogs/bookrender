<!-- Sidebar -->
<nav class="pt-2 text-center shadow col-md-1 d-none d-md-block sidebar position-relative">
    <a href="{{ route('dashboard.index') }}" class="{{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
        <img src="{{ asset('assets/dashboard/images/group1000007883.png') }}" class="" alt="Dashboard" />
        <p class="mb-3">Dashboard</p>
    </a>

    <a href="{{ route('dashboard.books.index') }}"
        class="{{ request()->routeIs('dashboard.books.*') ? 'active' : '' }}">
        <img src="{{ asset('assets/dashboard/images/vector13.png') }}" alt="my project" />
        <p class="mb-3">My Projects</p>
    </a>

    @if (session()->has('last_book'))
        <a href="{{ route('dashboard.books.show', session()->get('last_book')) }}"
            class="{{ request()->is('editor*') ? 'active' : '' }}">
            <img src="{{ asset('assets/dashboard/images/vector21.svg') }}" alt="Book Editor Access" />
            <p class="mb-3">Book Editor Access</p>
        </a>
    @endif

    <a href="{{ route('dashboard.profile') }}" class="{{ request()->routeIs('dashboard.profile') ? 'active' : '' }}">
        <img src="{{ asset('assets/dashboard/images/group4.png') }}" alt="Profile Management" />
        <p class="mb-3">Profile Management</p>
    </a>

    <a href="{{ route('dashboard.plans.index') }}"
        class="{{ request()->routeIs('dashboard.plans.*') ? 'active' : '' }}">
        <img src="{{ asset('assets/dashboard/images/group5.png') }}" alt="Subscription Information" />
        <p class="mb-3">Subscription Information</p>
    </a>
    <!-- <a href="#" class="{{ request()->is('notifications*') ? 'active' : '' }}">
        <img src="{{ asset('assets/dashboard/images/group3.svg') }}" alt="Notifications" />
        <p class="mb-3">Notifications</p>
    </a> -->

    <a href="{{ route('dashboard.help-center') }}"
        class="{{ request()->routeIs('dashboard.help-center') ? 'active' : '' }}">
        <img src="{{ asset('assets/dashboard/images/group6.png') }}" alt="Help Center" />
        <p class="mb-3">Help Center</p>
    </a>
    <!-- <a href="#" class="{{ request()->is('settings*') ? 'active' : '' }}">
        <img src="{{ asset('assets/dashboard/images/vector23.svg') }}" alt="Settings" />
        <p class="mb-3">Settings</p>
    </a> -->
    <form action="{{ route('logout') }}" method="post">
        @csrf
        <button type="submit" onclick="preventDefault()" class="bg-transparent border-0 ">
            <img src="{{ asset('assets/dashboard/images/group1000007891.png') }}" alt="Logout" />


            <p class="mb-3">Logout</p>
        </button>
    </form>
</nav>