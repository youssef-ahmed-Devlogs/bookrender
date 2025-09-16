<x-layouts.dashboard.app>

    <div class="pb-5 general">
        <div class="container-fluid container-lg">
            <div class="d-flex justify-content-between">
                <div class="pb-2 mt-5 mb-1 chapter-a">
                    <a href="javascript:history.back()" class="me-3">
                        <i class="fa-solid fa-chevron-left me-3"></i>Edit Book Information
                    </a>

                </div>
            </div>

            <div class="mt-3 overflow-hidden bg-white border shadow general-book rounded-5">
                <h4 class="p-4 pb-3">General Book Information</h4>

                <form class="p-5 pt-3 chapter-label" method="POST"
                    action="{{ route('dashboard.books.update', $book->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="p-3 upload">
                        <div class="d-flex align-items-start">
                            <h3 class="me-4">Book Cover</h3>
                            <div class="text-center uplad-dot" id="drop-area" style="position: relative;">
                                <label for="image" style="cursor: pointer;">
                                    <img id="preview" src="{{ asset('storage/' . $book->image) }}"
                                        style="max-width: 100% ; width: 100px; height: auto;" class="mt-4" alt="upload">
                                    <h5 class="mt-3 mb-3">Drag your image here</h5>
                                    <p>or</p>
                                    <a class="" onclick="document.getElementById('image').click(); return false;">Choose
                                        file</a>
                                </label>
                                <input type="file" name="image" id="image" class="d-none" accept="image/*">
                            </div>

                            <style>
                                #drop-area.highlight {
                                    border: 2px dashed #0C9BF3;
                                    background-color: #f0f8ff;
                                }
                            </style>
                        </div>
                    </div>

                    <p class="pt-4 mt-5 font-para pragraph">Dimensions: 2,560 pixels in height x 1,600
                        pixels in width</p>

                    <label for="title">Title</label>
                    <input type="text" name="title" value="{{ old('title', $book->title) }}"
                        placeholder="Tangerine Text Guide - Pressbook"
                        class="mt-2 mb-4 form-control input-padding rounded-4">

                    <label for="second_title">Short Title</label>
                    <input type="text" name="second_title" value="{{ old('second_title', $book->second_title) }}"
                        class="form-control input-padding rounded-4">
                    <p class="mt-2 mb-4 font-para">In case of long titles that might be truncated in
                        running heads in the
                        PDF export.</p>

                    <label for="subtitle">description</label>
                    <input type="description" name="description"
                        value="{{ old('description', $book->description ?? '') }}"
                        class="mt-2 mb-4 form-control input-padding rounded-4">

                    <label for="author">Author Name</label>
                    <input type="text" name="author" value="{{ old('author', $book->author) }}"
                        class="mt-2 mb-4 form-control input-padding rounded-4">

                    <button class="mx-auto mt-3 button_Adduser">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Drag and drop functionality
            const dropArea = document.getElementById('drop-area');
            const imageInput = document.getElementById('image');
            const preview = document.getElementById('preview');

            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                }, false);
            });

            // Highlight drop area on dragenter/dragover
            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, () => {
                    dropArea.classList.add('highlight');
                }, false);
            });

            // Remove highlight on dragleave/drop
            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, () => {
                    dropArea.classList.remove('highlight');
                }, false);
            });

            // Handle dropped files
            dropArea.addEventListener('drop', (e) => {
                if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                    imageInput.files = e.dataTransfer.files;
                    const file = e.dataTransfer.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function (event) {
                            preview.src = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });

            imageInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        preview.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        </script>
    @endpush

</x-layouts.dashboard.app>