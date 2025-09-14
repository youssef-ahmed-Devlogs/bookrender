<x-layouts.admin.app>

    <x-page-header>
        <h1 class="text-center w-100">Subscription Managment</h1>
        <a href="{{route('admin.plans.create')}}" class="button_Adduser position-absolute z-1 ">Create New Plan</a>
    </x-page-header>

    <div class="container-fluid container-lg">
        <form action="{{ URL::current() }}" method="GET">
            <div class="mt-4 d-flex managment-search justify-content-between ">
                <h2>All Plans</h2>

                {{-- <input type="text" name="search" id="search" class="rounded form-control w-25 rounded-4"
                    placeholder="Search" value="{{ request()->get('search') }}" onblur="this.form.submit()"> --}}
            </div>
        </form>

        <div class="mt-4 overflow-hidden border shadow rounded-5">
            <div class="pagination-container">
                {{$plans->links()}}
            </div>

            <div class="table-responsive">
                <table id="Table" class="table text-center align-middle border-0 table-hover ">
                    <thead>
                        <tr>
                            <th scope="col">Plan Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Status</th>
                            <th scope="col">Pricing</th>
                            <th scope="col">ACTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($plans as $plan)
                            <tr class="pt-5">
                                <td class="pb-4 basic">
                                    <p class="mt-3 basic">{{ $plan->name }}</p>
                                </td>
                                <td class="description">{{ $plan->description }}</td>
                                <td class="pe-md-5 pe-0 ">

                                    <button class="button_admin_sub ms-3" data-id="{{ $plan->id }}">
                                        {{ $plan->status }}
                                    </button>

                                </td>
                                <td class="sub-padding ">${{ $plan->price }} / Month</td>

                                <td class="ps-md-5 pe-md-5 ps-0 pe-0">
                                    <div class="d-flex justify-content-end">
                                        <form action="{{route('admin.plans.destroy', $plan)}}" method="post">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="mt-2 deleteBtn button_admin3 btn btn-danger mb-md-3 mb-lg-0 me-3 ">
                                                <i class="pt-1 fa-solid fa-trash-can me-2 pt-md-0"></i>
                                                Delete
                                            </button>
                                        </form>

                                        <a href="{{route('admin.plans.edit', $plan)}}"
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

    <div class="container-fluid container-lg">
        <div class="mt-4 d-flex managment-search justify-content-between ">
            <h2>All Subscribers</h2>
        </div>

        <div class="mt-4 overflow-hidden border shadow rounded-5">
            <div class="ms-5 mt-5 mb-2 d-flex sub-info">
                <h6 class="mx-4">All</h6>
                <h6 class="ms-5">Active {{$activesubscriptions}}</h6>
                <h6 class="ms-5">Expired {{$expiredsubscriptions}}</h6>
                {{-- <h6 class="ms-5">Completed (5)</h6> --}}
                {{-- <h6 class="ms-5">Trialling (0)</h6> --}}
                <h6 class="ms-5">Cancelled {{$canceledsubscriptions}}</h6>
            </div>

            <div class="table-responsive">
                <table id="Table" class="table text-center align-middle border-0 table-hover ">
                    <thead>
                        <tr>
                            <th scope="col">User Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">ADDED Date</th>
                            <th scope="col">ASSIGNED Role</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($subscribers as $subscriber)
                            <tr class="pt-5">
                                <td class="pb-4 basic">
                                    <p class="mt-3 basic"> {{$subscriber->fname . ' ' . $subscriber->lname}}</p>
                                </td>

                                <td>{{ $subscriber->email }}</td>

                                <td class="pe-md-5 pe-0 ">
                                    {{ $subscriber->subscriptions->first()?->start_date }}
                                </td>

                                <td class="sub-padding ">{{ $subscriber->subscriptions->first()?->plan?->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('.button_admin_sub').forEach(button => {
                button.addEventListener('click', function () {
                    console.log(this);

                    let planId = this.getAttribute('data-id');

                    fetch(`{{route('admin.plans.toggleStatus')}}?plan_id=${planId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.textContent = data.new_status;
                            } else {
                                alert('حدث خطأ ما');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        </script>
    @endpush
</x-layouts.admin.app>