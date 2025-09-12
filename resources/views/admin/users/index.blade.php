<x-layouts.admin.app>

    <x-page-header>
        <h1 class="text-center w-100">User Managment</h1>
        <a href="{{route('admin.users.create')}}" class="button_Adduser position-absolute z-1 ">Add User</a>
    </x-page-header>

    <div class="container-fluid container-lg">
        <form action="{{ URL::current() }}" method="GET">
            <div class="mt-4 d-flex managment-search justify-content-between ">
                <h2>All Users</h2>

                <input type="text" name="search" id="search" class="rounded form-control w-25 rounded-4"
                    placeholder="Search" value="{{ request()->get('search') }}" onblur="this.form.submit()">
            </div>
        </form>

        <div class="mt-4 overflow-hidden border shadow rounded-5">
            <div class="pagination-container">
                {{$users->links()}}
            </div>

            <div class="table-responsive">
                <table id="Table" class="table text-center align-middle border-0 table-hover ">
                    <thead>
                        <tr>
                            <th scope="col">User Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">ADDED Date</th>
                            <th scope="col">ASSIGNED Role</th>
                            <th scope="col">ACTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $user)
                            <tr class="pt-5">
                                <td class="font">
                                    {{$user->fname . ' ' . $user->lname}}
                                </td>

                                <td class="email-column font">{{$user->email}}</td>

                                <td class="font ">{{$user->created_at}}</td>

                                <td class="pe-md-5 pe-0 ">
                                    <button class="button_admin">{{$user->role}}</button>
                                </td>

                                <td class="ps-md-5 pe-md-5 ps-0 pe-0">
                                    <div class="d-flex justify-content-end">
                                        <form action="{{route('admin.users.destroy', $user)}}" method="post">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="mt-2 deleteBtn button_admin3 btn btn-danger mb-md-3 mb-lg-0 me-3 ">
                                                <i class="pt-1 fa-solid fa-trash-can me-2 pt-md-0"></i>
                                                Delete
                                            </button>
                                        </form>

                                        <a href="{{route('admin.users.edit', $user)}}"
                                            class="button_admin2 btn btn-secondary">
                                            <i class="fa-solid fa-pen-to-square me-2"></i>
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.admin.app>