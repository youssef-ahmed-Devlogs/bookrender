<nav class="pt-2 text-center shadow col-md-1 d-none d-md-block sidebar position-relative">
    <style>
        .sidebar a {
            display: block;
            padding: 20px 0;
            color: #6c757d;
            text-decoration: none;
        }

        .sidebar a.active {
            background: #e8f2ff;
            border-right: 4px solid #0c9bf3;
            color: #0c9bf3;
        }

        .sidebar a.active p {
            color: #0c9bf3 !important;
        }
    </style>

    <a href="{{ route('admin.index') }}" class="{{ request()->is('admin') ? 'active' : '' }}">
        <img src="{{ asset('assets/admin/images/group1000007883.png') }}" alt="Dashboard">
        <p class="mb-4"> Dashboard</p>
    </a>

    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <img src="{{ asset('assets/admin/images/group1000008235.png') }}" alt="user managment">
        <p class="mb-4">User Management</p>
    </a>

    <a href="{{ route('admin.plans.index') }}" class="{{ request()->routeIs('admin.plans.*') ? 'active' : '' }}"
        target="_self">
        <img src="{{ asset('assets/admin/images/vector2.png') }}" alt="Subscriptions">
        <p class="mb-4">Subscriptions</p>
    </a>


    <a href="{{ route('admin.newsletters.index') }}"
        class="{{ request()->routeIs('admin.newsletters.*') ? 'active' : '' }}">
        <img src="{{ asset('assets/admin/images/group1000008236.png') }}" alt="user managment">
        <p class="mb-4">Newsletters </p>
    </a>

    <a href="{{ route('admin.settings.logo-site') }}"
        class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
        <img src="{{ asset('assets/admin/images/group1000008236.png') }}" alt=" Settings">
        <p class="mb-4"> Settings</p>
    </a>

    <form action="{{route('logout')}}" method="post">
        @csrf
        <button type="submit" class="mt-5 btn btn-danger">Logout</button>
    </form>
</nav>