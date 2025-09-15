<x-layouts.dashboard.app>

    @push('styles')
        <style>
            body {
                overflow: hidden;
            }
        </style>
    @endpush

    <div class="img_managment2 position-relative">
        <img src="{{ asset('assets/admin/images/vector5.png') }}" class="top-0 position-absolute" alt="" />

        <h1 class="text-center w-100">My Projects</h1>

        <a href="{{ route('dashboard.books.create') }}">
            <button class="button_Adduser position-absolute z-1">
                Start a New Book
            </button>
        </a>

        <img src="{{ asset('assets/dashboard/images/group1000008036.png') }}" class="position-absolute img-star2"
            alt="star">
        <img src="{{ asset('assets/dashboard/images/vector20.png') }}" class="position-absolute img_line" alt="" />
        <img src="{{ asset('assets/dashboard/images/group3.png') }}" class="position-absolute img_line2" alt="" />
        <img src="{{ asset('assets/dashboard/images/vector4.png') }}" class="position-absolute img_line2" alt="" />
        <img src="{{ asset('assets/dashboard/images/vector19.png') }}" class="position-absolute img_line3" alt="" />
    </div>



    <div class="container">
        <div class="mt-4 mb-3 d-flex justify-content-between">
            <h4 class="pt-1 font-usermodel">Recent</h4>

            <div class="d-flex">
                <div class="dropdown me-4">
                    <i class="pt-1 fas fa-sort-amount-down me-4 sort-icon" style="cursor: pointer;"
                        data-bs-toggle="dropdown" aria-expanded="false"></i>
                    <ul class="dropdown-menu sort-dropdown">
                        <li><a class="dropdown-item" href="#" data-sort="title-asc">Title A-Z</a></li>
                        <li><a class="dropdown-item" href="#" data-sort="title-desc">Title Z-A</a></li>
                        <li><a class="dropdown-item" href="#" data-sort="date-asc">Date Created (Oldest)</a></li>
                        <li><a class="dropdown-item" href="#" data-sort="date-desc">Date Created (Newest)</a></li>
                        <li><a class="dropdown-item" href="#" data-sort="updated-asc">Last Updated (Oldest)</a></li>
                        <li><a class="dropdown-item" href="#" data-sort="updated-desc">Last Updated (Newest)</a></li>
                    </ul>
                </div>

                <div class="dropdown me-4">
                    <i class="pt-1 fas fa-list me-4 view-toggle-icon" style="cursor: pointer;" data-bs-toggle="dropdown"
                        aria-expanded="false"></i>
                    <ul class="dropdown-menu view-dropdown">
                        <li><a class="dropdown-item" href="#" data-view="grid"><i class="fas fa-th-large me-2"></i>Grid
                                View</a></li>
                        <li><a class="dropdown-item" href="#" data-view="list"><i class="fas fa-list me-2"></i>List
                                View</a>
                        </li>
                    </ul>
                </div>

                <div class="dropdown me-4">
                    <i class="pt-1 fas fa-filter icon-recent me-4 pe-4 filter-icon" style="cursor: pointer;"
                        data-bs-toggle="modal" data-bs-target="#filterModal"></i>
                </div>

                <h4 class="pt-1 go-font">Go to Files</h4>

                <i class="pb-2 fa-solid fa-chevron-down fa-2xs align-self-center ms-1"></i>
            </div>

        </div>

        <div style="overflow-x: hidden;overflow-y: auto;height: 450px;">
            <div class="row">
                <x-books.list :books="$books" />
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Books</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="titleFilter" class="form-label">Title Contains</label>
                        <input type="text" class="form-control" id="titleFilter" placeholder="Enter title keywords...">
                    </div>
                    <div class="mb-3">
                        <label for="yearFilter" class="form-label">Year Created</label>
                        <select class="form-select" id="yearFilter">
                            <option value="">All Years</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                            <option value="2022">2022</option>
                            <option value="2021">2021</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="dateRangeFilter" class="form-label">Date Range</label>
                        <select class="form-select" id="dateRangeFilter">
                            <option value="">All Time</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="year">This Year</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="applyFilter">Apply Filter</button>
                    <button type="button" class="btn btn-outline-secondary" id="clearFilter">Clear All</button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard.app>