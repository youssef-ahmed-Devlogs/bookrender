<x-layouts.dashboard.app>

    @push('styles')
        <style>
            body {
                overflow: hidden;
            }
        </style>
    @endpush

    <div class="dash-main position-relative">
        <img src="{{ asset('assets/dashboard/images/vector5.png') }}" class="position-absolute" alt="" />
        <img src="{{ asset('assets/dashboard/images/group1000008036.png') }}" class="position-absolute img-star"
            alt="star">

        <div class="main-dashbordh1">
            <h1 class="">Welcome</h1>
            <h1 class="main-dashbord">{{ auth()->user()->fname . ' ' . auth()->user()->lname }}</h1>
        </div>

        <a href="{{ route('dashboard.books.create') }}">
            {{-- <a href="{{ route('dashboard.book.create') }}"> --}}
                <button class="button_Adduser position-absolute z-1">
                    Start a New Book
                </button>
            </a>

            @if (session()->has('last_book'))
                <a href="{{ route('dashboard.books.show', session()->get('last_book')) }}">
                    <button class="button_Adduser2 position-absolute z-1">Resume Writing</button>
                </a>
            @endif

            <img src="{{ asset('assets/dashboard/images/vector21.png') }}" class="position-absolute img_line_dash"
                alt="" />

            <div class="pb-5 row z-2 position-absolute dash-row pb-md-0 ">
                <div class="mt-5 col-lg-4 dash-book ps-md-4 me-md-4 me-3 mt-md-0 ">
                    <h4>{{ $books->count() }}</h4>
                    <p>Active Books</p>
                </div>
            </div>

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

        <div style="overflow-y: auto;height: 350px;">
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

    @push('scripts')
        {{--
        <script src="/user/user/js/mian.js"></script> --}}
        {{--
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                fetch(`{{ route('dashboard.plans.check') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    credentials: 'same-origin'
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message);
                        } else if (data.status === 'info') {
                            console.log(data.message);
                        } else {
                            console.error('Unexpected response:', data);
                        }
                    })
                    .catch(error => {
                        console.error('There was an error with the request: ', error);
                    });

                // Sort functionality
                const sortDropdown = document.querySelector('.sort-dropdown');
                if (sortDropdown) {
                    sortDropdown.addEventListener('click', function (e) {
                        e.preventDefault();

                        if (e.target.classList.contains('dropdown-item')) {
                            const sortType = e.target.getAttribute('data-sort');
                            sortBooks(sortType);

                            // Update sort icon to show current sort
                            const sortIcon = document.querySelector('.sort-icon');
                            if (sortIcon) {
                                sortIcon.className = 'fas fa-sort-amount-down me-4 pt-1 sort-icon';
                                if (sortType.includes('desc')) {
                                    sortIcon.className = 'fas fa-sort-amount-up me-4 pt-1 sort-icon';
                                }
                            }
                        }
                    });
                }

                function sortBooks(sortType) {
                    // Target the specific row container that holds the books
                    const booksContainer = document.querySelector('.container .row');
                    if (!booksContainer) return;

                    const bookItems = Array.from(booksContainer.querySelectorAll('.book-item'));

                    bookItems.sort(function (a, b) {
                        let aValue, bValue;

                        switch (sortType) {
                            case 'title-asc':
                                aValue = a.getAttribute('data-title');
                                bValue = b.getAttribute('data-title');
                                return aValue.localeCompare(bValue);
                            case 'title-desc':
                                aValue = a.getAttribute('data-title');
                                bValue = b.getAttribute('data-title');
                                return bValue.localeCompare(aValue);
                            case 'date-asc':
                                aValue = parseInt(a.getAttribute('data-date'));
                                bValue = parseInt(b.getAttribute('data-date'));
                                return aValue - bValue;
                            case 'date-desc':
                                aValue = parseInt(a.getAttribute('data-date'));
                                bValue = parseInt(b.getAttribute('data-date'));
                                return bValue - aValue;
                            case 'updated-asc':
                                aValue = parseInt(a.getAttribute('data-updated'));
                                bValue = parseInt(b.getAttribute('data-updated'));
                                return aValue - bValue;
                            case 'updated-desc':
                                aValue = parseInt(a.getAttribute('data-updated'));
                                bValue = parseInt(b.getAttribute('data-updated'));
                                return bValue - aValue;
                            default:
                                return 0;
                        }
                    });

                    // Remove all book items from container
                    bookItems.forEach(item => {
                        item.remove();
                    });

                    // Re-append sorted items to the container
                    bookItems.forEach(item => {
                        booksContainer.appendChild(item);
                    });
                }

                // View toggle functionality
                const viewDropdown = document.querySelector('.view-dropdown');
                const booksContainer = document.querySelector('.row');
                let currentView = 'grid'; // Default view

                if (viewDropdown && booksContainer) {
                    viewDropdown.addEventListener('click', function (e) {
                        e.preventDefault();

                        if (e.target.classList.contains('dropdown-item')) {
                            const viewType = e.target.getAttribute('data-view');
                            toggleView(viewType);

                            // Update view icon
                            const viewIcon = document.querySelector('.view-toggle-icon');
                            if (viewIcon) {
                                if (viewType === 'list') {
                                    viewIcon.className = 'fas fa-th-large me-4 pt-1 view-toggle-icon';
                                } else {
                                    viewIcon.className = 'fas fa-list me-4 pt-1 view-toggle-icon';
                                }
                            }
                        }
                    });
                }

                function toggleView(viewType) {
                    const bookItems = document.querySelectorAll('.book-item');

                    bookItems.forEach(item => {
                        if (viewType === 'list') {
                            item.classList.remove('book-grid-item');
                            item.classList.add('book-list-item');
                            item.classList.remove('col-3');
                            item.classList.add('col-12');
                        } else {
                            item.classList.remove('book-list-item');
                            item.classList.add('book-grid-item');
                            item.classList.remove('col-12');
                            item.classList.add('col-3');
                        }
                    });

                    currentView = viewType;
                }

                // Filter functionality
                const applyFilterBtn = document.getElementById('applyFilter');
                const clearFilterBtn = document.getElementById('clearFilter');
                const filterModal = document.getElementById('filterModal');
                const titleFilterInput = document.getElementById('titleFilter');
                let originalBooks = [];

                // Store original books on page load
                originalBooks = Array.from(document.querySelectorAll('.book-item')).map(item => item.cloneNode(true));

                // Real-time title filtering
                if (titleFilterInput) {
                    titleFilterInput.addEventListener('input', function () {
                        applyFilters();
                    });
                }

                if (applyFilterBtn) {
                    applyFilterBtn.addEventListener('click', function () {
                        applyFilters();
                        const modal = bootstrap.Modal.getInstance(filterModal);
                        if (modal) {
                            modal.hide();
                        }
                    });
                }

                if (clearFilterBtn) {
                    clearFilterBtn.addEventListener('click', function () {
                        clearFilters();
                        const modal = bootstrap.Modal.getInstance(filterModal);
                        if (modal) {
                            modal.hide();
                        }
                    });
                }

                function applyFilters() {
                    const titleFilter = document.getElementById('titleFilter').value.toLowerCase().trim();
                    const yearFilter = document.getElementById('yearFilter').value;
                    const dateRangeFilter = document.getElementById('dateRangeFilter').value;

                    const bookItems = document.querySelectorAll('.book-item');
                    const booksContainer = document.querySelector('.row');

                    bookItems.forEach(item => {
                        let showItem = true;

                        // Title filter - search across multiple title fields
                        if (titleFilter) {
                            const mainTitle = item.getAttribute('data-title') || '';
                            const secondTitle = item.getAttribute('data-second-title') || '';
                            const fullTitle = item.getAttribute('data-full-title') || '';

                            // Check if any of the title fields contain the search term
                            const titleMatch = mainTitle.includes(titleFilter) ||
                                secondTitle.includes(titleFilter) ||
                                fullTitle.includes(titleFilter);

                            if (!titleMatch) {
                                showItem = false;
                            }
                        }

                        // Year filter
                        if (yearFilter && showItem) {
                            const year = item.getAttribute('data-year');
                            if (year !== yearFilter) {
                                showItem = false;
                            }
                        }

                        // Date range filter
                        if (dateRangeFilter && showItem) {
                            const itemDate = new Date(parseInt(item.getAttribute('data-date')) * 1000);
                            const now = new Date();

                            switch (dateRangeFilter) {
                                case 'today':
                                    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                                    if (itemDate < today) showItem = false;
                                    break;
                                case 'week':
                                    const weekAgo = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
                                    if (itemDate < weekAgo) showItem = false;
                                    break;
                                case 'month':
                                    const monthAgo = new Date(now.getFullYear(), now.getMonth(), 1);
                                    if (itemDate < monthAgo) showItem = false;
                                    break;
                                case 'year':
                                    const yearStart = new Date(now.getFullYear(), 0, 1);
                                    if (itemDate < yearStart) showItem = false;
                                    break;
                            }
                        }

                        if (showItem) {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    // Update filter icon to show active state
                    const filterIcon = document.querySelector('.filter-icon');
                    if (filterIcon && (titleFilter || yearFilter || dateRangeFilter)) {
                        filterIcon.style.color = '#007bff';
                    }
                }

                function clearFilters() {
                    document.getElementById('titleFilter').value = '';
                    document.getElementById('yearFilter').value = '';
                    document.getElementById('dateRangeFilter').value = '';

                    const bookItems = document.querySelectorAll('.book-item');
                    bookItems.forEach(item => {
                        item.style.display = '';
                    });

                    // Reset filter icon
                    const filterIcon = document.querySelector('.filter-icon');
                    if (filterIcon) {
                        filterIcon.style.color = '';
                    }
                }
            });
        </script>
        {{--
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
    @endpush
</x-layouts.dashboard.app>