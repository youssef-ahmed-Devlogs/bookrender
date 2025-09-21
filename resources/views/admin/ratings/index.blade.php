<x-layouts.admin.app>

    <x-page-header>
        <h1 class="text-center w-100">Ratings Managment</h1>
        <a href="{{route('admin.ratings.create')}}" class="button_Adduser position-absolute z-1 ">Add Rating</a>
    </x-page-header>

    <div class="container-fluid container-lg">
        <form action="{{ URL::current() }}" method="GET">
            <div class="mt-4 d-flex managment-search justify-content-between ">
                <h2>All Ratings</h2>
            </div>
        </form>

        <div class="mt-4 overflow-hidden border shadow rounded-5">
            <div class="pagination-container">
                {{$ratings->links()}}
            </div>

            <div class="table-responsive">
                <table id="Table" class="table text-center align-middle border-0 table-hover ">
                    <thead>
                        <tr>
                            <th scope="col">Image</th>
                            <th scope="col">ADDED Date</th>
                            <th scope="col">ACTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($ratings as $rating)
                            <tr class="pt-5">
                                <td class="email-column font">
                                    <a href="{{ asset("storage/{$rating->image}") }}" target="_blank">
                                        <img src="{{ asset("storage/{$rating->image}") }}"
                                            style="width: 250px;object-fit: cover;">
                                    </a>
                                </td>

                                <td class="font">{{$rating->created_at}}</td>

                                <td class="ps-md-5 pe-md-5 ps-0 pe-0">
                                    <div class="d-flex justify-content-end">
                                        <form action="{{route('admin.ratings.destroy', $rating)}}" method="post">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="mt-2 deleteBtn button_admin3 btn btn-danger mb-md-3 mb-lg-0 me-3 ">
                                                <i class="pt-1 fa-solid fa-trash-can me-2 pt-md-0"></i>
                                                Delete
                                            </button>
                                        </form>
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