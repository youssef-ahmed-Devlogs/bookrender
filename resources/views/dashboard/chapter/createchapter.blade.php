<x-layouts.dashboard.app>

    <div class="pb-5 chapter pe-4">
        <div class="container-fluid container-lg ">
            <div class="d-flex justify-content-between ">
                <div class="pb-2 mt-5 mb-1 chapter-a">
                    <a href="javascript:history.back()" class="me-3"><i class="fa-solid fa-chevron-left me-3"></i>Create
                        Chapter</a>
                </div>
            </div>

            <form
                action="{{ isset($chapter) ? route('dashboard.chapter.updateChapter', $chapter->id) : route('dashboard.chapter.store') }}"
                method="post">
                @csrf

                @if (isset($chapter))
                    @method('PUT')
                @endif

                <input type="text" name="book_id" hidden value="{{ $book->id }}">

                <div class="mt-3 overflow-hidden bg-white border shadow rounded-5">
                    <!-- <h4 class="p-4 pb-3 chapter-font"> book name {{ $book->title }} </h4> -->
                    <h4 class="p-4 pb-3 chapter-font "> Chapter Metadata </h4>

                    <div class="p-3">
                        <label for="">Chapter Short Title</label>
                        <input name="title" type="text" placeholder="Type here"
                            class="mt-2 form-control input-padding rounded-4"
                            value="{{ old('title', isset($chapter) ? $chapter->title : '') }}">

                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                        <label for="" class="mt-4">Chapter Subtitle</label>
                        <input name="subtitle" type="text" placeholder="Type here"
                            class="mt-2 form-control input-padding rounded-4"
                            value="{{ old('subtitle', isset($chapter) ? $chapter->subtitle : '') }}">

                        <label for="" class="mt-4">Chapter Copyright License</label>
                        <p class="m-0">Specify the copyright or license terms for this chapter (e.g. All
                            rights reserved, CC BY-NC, etc.). </p>
                        <input name="license" type="text" placeholder="CC BY SA SS"
                            class="mt-2 form-control input-padding rounded-4"
                            value="{{ old('license', isset($chapter) ? $chapter->license : '') }}">

                        <label for="" class="mt-4">Chapter Digital Object Identifier (DOI)</label>
                        <p class="m-0">Enter the unique DOI (Digital Object Identifier) for this chapter,
                            if
                            available.</p>
                        <input name="doi" type="text" placeholder="11.1234/nshyp1160"
                            class="mt-2 form-control input-padding rounded-4"
                            value="{{ old('doi', isset($chapter) ? $chapter->doi : '') }}">

                        <button type="submit" class="mx-auto mt-3 button_Adduser">Save Changes</button>
                    </div>

                </div>
            </form>


        </div>

    </div>

    @push('scripts')

        <script>
            document.querySelector('form').addEventListener('submit', function (event) {
                event.preventDefault(); // منع الإرسال الافتراضي
                console.log('Form is being submitted'); // تأكد من أنه يتم إرساله
                this.submit(); // إرسال النموذج يدويًا بعد الطباعة
            });
        </script>
    @endpush

</x-layouts.dashboard.app>