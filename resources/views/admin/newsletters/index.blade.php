<x-layouts.admin.app>

    <x-page-header>
        <h1 class="text-center w-100">Newsletters Managment</h1>
    </x-page-header>

    <div class="container-fluid container-lg">
        <form action="{{ URL::current() }}" method="GET">
            <div class="mt-4 d-flex managment-search justify-content-between ">
                <h2>All Newsletters</h2>
                {{--
                <input type="text" name="search" id="search" class="rounded form-control w-25 rounded-4"
                    placeholder="Search" value="{{ request()->get('search') }}" onblur="this.form.submit()"> --}}

                <a href="{{ route('admin.newsletters.export') }}" class="btn btn-success">
                    Export to Excel
                </a>
            </div>
        </form>

        <div class="mt-4 overflow-hidden border shadow rounded-5">
            <div class="pagination-container">
                {{$newsletters->links()}}
            </div>

            <div class="table-responsive">
                <table id="Table" class="table text-center align-middle border-0 table-hover ">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Email</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($newsletters as $newsletter)
                            <tr class="pt-5">
                                <td class="font">
                                    {{ $newsletter->id }}
                                </td>

                                <td class="email-column font">{{ $newsletter->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.admin.app>