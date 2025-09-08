<!-- Sidebar -->
<nav class="pt-2 text-center shadow col-md-1 d-none d-md-block sidebar position-relative">
    <a href="#" class="{{ request()->is('dashboard') ? 'active' : '' }}">
        <img src="{{ asset('assets/dashboard/images/group1000007883.png') }}" class="" alt="Dashboard" />
        <p class="mb-3">Dashboard</p>
    </a>

    <a href="#" class="{{ request()->is('book*') ? 'active' : '' }}">
        <img src="{{ asset('assets/dashboard/images/vector13.png') }}" alt="my project" />
        <p class="mb-3">My Projects</p>
    </a>
    <a href="#" class="{{ request()->is('editor*') ? 'active' : '' }}">
        <img src="{{ asset('assets/dashboard/images/vector21.svg') }}" alt="Book Editor Access" />
        <p class="mb-3">Book Editor Access</p>
    </a>

    <a href="#" class="{{ request()->is('profile') ? 'active' : '' }}">
        <img src="{{ asset('assets/dashboard/images/group4.png') }}" alt="Content Management" />
        <p class="mb-3">Profile Management</p>
    </a>

    <a href="#" class="{{ request()->is('plans*') ? 'active' : '' }}">
        <img src="{{ asset('assets/dashboard/images/group5.png') }}" alt="Subscription Information" />
        <p class="mb-3">Subscription Information</p>
    </a>
    <a href="#" class="{{ request()->is('notifications*') ? 'active' : '' }}">
        <img src="{{ asset('assets/dashboard/images/group3.svg') }}" alt="Notifications" />
        <p class="mb-3">Notifications</p>
    </a>

    <a href="#" class="{{ request()->is('help') ? 'active' : '' }}">
        <img src="{{ asset('assets/dashboard/images/group6.png') }}" alt="Help Center" />
        <p class="mb-3">Help Center</p>
    </a>
    <a href="#" class="{{ request()->is('settings*') ? 'active' : '' }}">
        <img src="{{ asset('assets/dashboard/images/vector23.svg') }}" alt="Settings" />
        <p class="mb-3">Settings</p>
    </a>
    <form action="{{ route('logout') }}" method="post">
        @csrf
        <button type="submit" onclick="preventDefault()" class="bg-transparent border-0 ">
            <img src="{{ asset('assets/dashboard/images/group1000007891.png') }}" alt="Logout" />


            <p class="mb-3">Logout</p>
        </button>
    </form>
</nav>