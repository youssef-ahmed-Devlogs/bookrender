@foreach ($books as $book)
    <div class="col-lg-3 rounded-3 book-item book-grid-item mb-3" style="position: relative;"
        data-title="{{ strtolower($book->title) }}" data-second-title="{{ strtolower($book->second_title ?? '') }}"
        data-full-title="{{ strtolower($book->title . ' ' . ($book->second_title ?? '')) }}"
        data-date="{{ $book->created_at->timestamp }}" data-updated="{{ $book->updated_at->timestamp }}"
        data-category="{{ strtolower($book->category ?? 'uncategorized') }}"
        data-status="{{ strtolower($book->status ?? 'active') }}" data-year="{{ $book->created_at->year }}">


        <img src="{{ $book->cover() }}" class="w-100 rounded-3 border-1 img-book" alt="book"
            style="width:302.25px;height:302.25px;object-fit:cover">


        <div class="mt-2 d-flex justify-content-between">


            <div class="btn-usermodel">
                {{-- <button class="complete">14% Complete</button> --}}
                <a href="{{ route('dashboard.books.edit', $book->id) }}">
                    <button class="Editing">Editing</button>
                </a>
            </div>
            <div class="icon-usermodel d-flex justify-content-end align-items-center">
                <form action="{{ route('dashboard.books.destroy', $book->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="deleteBtn Editing bg-danger">delete</button>
                </form>

                <button class="mx-2 mt-1 Editing bg-success">
                    <a href="{{ route('dashboard.chapter.create.custom', $book->id) }}"
                        class="text-white text-decoration-none">
                        chapter
                    </a>
                </button>

                <button class="mt-1 Editing bg-primary">
                    <a href="{{ route('dashboard.books.show', $book->id) }}" class="text-white text-decoration-none">


                        view
                    </a>

                </button>
                {{-- <i class="fa-solid fa-ellipsis-vertical fa-sm"></i> --}}
            </div>
        </div>
        <div class="book-info">
            <h3 class="mt-2 main-usermodel">
                {{ Str::words($book->title . ': ' . $book->second_title, 18, '...') }}
            </h3>
            <p class="para-usermodel">Last Edit {{ $book->updated_at->diffForhumans() }} </p>
        </div>
    </div>
@endforeach