
<x-layouts.dashboard.app>

    @push('styles')
           <!-- Quill Snow theme -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

    @php
        $sizeMap = [
            '5" x 8"' => ['w' => 480, 'h' => 768], // 96 px per inch approximation
            '6" x 9"' => ['w' => 576, 'h' => 864],
            '7" x 10"' => ['w' => 672, 'h' => 960],
            '7" x 10.5"' => ['w' => 672, 'h' => 1008],
        ];
        $dimensions = $sizeMap[$project->treem_size] ?? ['w' => 576, 'h' => 864];
    @endphp


    <style>
        /* Dynamically scale editor / preview based on chosen Treem size */
        .page-preview {
            width: {{ $dimensions['w'] }}px;
            height: {{ $dimensions['h'] }}px;
            border: 1px solid #e0e0e0;
            background: #ffffff;
            margin: 0 auto;
            overflow-y: auto;
            box-shadow: 0 0 6px rgba(0, 0, 0, 0.1);
        }

        .page-preview .ql-editor {
            width: {{ $dimensions['w'] }}px;
            min-height: {{ $dimensions['h'] }}px;
            height: {{ $dimensions['h'] }}px;
            overflow-y: auto;
            padding: 20px 40px;
            /* inner margin for text */
            box-sizing: border-box;
        }

        .preview-area {
            background: #fff;
            padding: 40px 20px;
        }

        /* Icons for custom undo/redo buttons */
        .ql-toolbar .ql-undo::before {
            content: '\21B6';
            /* Unicode CCW arrow */
        }

        .ql-toolbar .ql-redo::before {
            content: '\21B7';
            /* Unicode CW arrow */
        }

        /* Editor container scrolling */
        .editor-container {
            max-height: 80vh;
            /* overflow-y: auto;
                                                overflow-x: hidden; */
        }

        /* AI Sidebar scrolling */
        .ai-sidebar {
            max-height: 80vh;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Ensure CKEditor respects the scroll container */
        .ck-editor {
            max-height: 80vh;
        }

        .ck-editor__editable {
            max-height: min({{ $dimensions['h'] }}px, 70vh) !important;
            overflow-y: auto !important;
        }

        /* AI Generated text area scrolling */
        #generated-text {
            max-height: 200px;
            overflow-y: auto;
            overflow-x: hidden;
        }
    </style>
    @endpush

    <div class="position-relative">
        <div class="upload-file d-none justify-content-center align-items-center" id="popapupload">
            <div class="d-flex flex-column justify-content-center align-items-center">
                <div class="p-4 upload">
                    <div class="p-2 d-flex justify-content-between">
                        <h3 class="fw-bold">Upload File</h3>
                        <i class="fa-solid fa-xmark fa-sm" id="close"></i>
                    </div>
                    <div class="p-3 text-center uplad-dot">
                        <img src="{{ asset('assets/dashboard/images/group7.png') }}" alt="upload">
                        <h4 class="mt-3 mb-3 fw-bold">Drag and Drop file here</h4>
                        <p>or</p>
                        <a href="">Choose file</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <main class="col-md-12" style="min-height: 100vh;">
                <div class="p-0 container-fluid editor" style="height: 100%;">
                    <div class="row g-0">
                        <!-- Sidebar chapters -->
                        <div class="p-3 mt-3 shadow col-md-12 col-lg-2 ms-lg-4 sidebar-editor h-100 rounded-4">

                            <div class="mb-4 d-flex align-items-center">
                                <i class="fa-solid fa-chevron-left me-2"></i>
                                {{-- <span>{{ $project->title }}</span> --}}
                                <label for="project-title" style="cursor: pointer; margin-right: 5px;padding-bottom:2px"
                                    id="edit-project-title">
                                    <i class="fa-solid fa-pen-to-square fa-sm ms-2"></i>
                                </label>

                                <input type="text" id="project-title" data-projectId="{{ $project->id }}"
                                    value="{{ $project->title }}" readonly="true" style="border: none;padding: 0 5px">
                            </div>

                            <h6 class="mt-5 mb-3">List of Chapter</h6>
                            <ul class="chapter-list">
                                @foreach ($project->chapters as $key => $chapter)
                                    <li class=" {{ $key == 0 ? 'active' : '' }} mb-1"
                                        data-id="{{ $chapter->id }}" data-content="{{ $chapter->content }}" title="{{ $chapter->title }}">

                                        <div style="display: flex;align-items: center;gap:5px">
                                            <div>

                                                <a href="{{ route('dashboard.chapter.edit', $chapter->id) }}" style="text-decoration: none">
                                                    <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                                </a>

                                                 <form action="{{ route('dashboard.chapter.destroy', $chapter->id) }}" method="POST" style="display:inline;">
                                                     @csrf
                                                     @method('DELETE')
                                                     
                                                     <button type="submit" class="text-danger" style="border:none" onclick="return confirm('Are you sure you want to delete this chapter?');">
                                                        <i class="fa-solid fa-trash fa-lg"></i>
                                                    </button>
                                                 </form>
                                        </div>

                                        <div  class="chapter-item {{ $key == 0 ? 'active' : '' }}" data-id="{{ $chapter->id }}" data-content="{{ $chapter->content }}">
                                            <i class="fa-solid fa-folder fa-lg me-2"></i>
                                            {{ Str::limit($chapter->title, 16) }}
                                        </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <div>
                                <a href="{{ route('dashboard.chapter.create.custom', $project->id) }}">
                                    <button class="px-3 button_Adduser ms-1">Create New Chapter</button>
                                </a>
                                <button class="px-3 mt-2 button_Adduser_editor"><a
                                        href="{{ route('dashboard.books.edit', $project->id) }}">Manage Book
                                        Details</a></button>
                            </div>
                        </div>

                        <!-- Editor Content -->
                        <div class="col-md-12 col-lg-6">
                            <div class="col">
                                <div class="mt-5 d-flex justify-content-center">

                                </div>

                                {{-- <div class="my-2 d-flex justify-content-center">
                                    <button class="complete-edit me-2">On Click</button>
                                    <button class="complete-edit me-2">Grammar correction</button>
                                    <button class="complete-edit me-2">Copyright check</button>
                                    <button class="complete-edit me-2">Humanizer book</button>
                                    <button class="complete-edit me-2">spelling correction</button>
                                </div> --}}

                                <div class="p-5 pt-1 editor-container">
                                    @if ($isPreview)
                                        <div class="preview-area">
                                            {{-- Content will be loaded by JS --}}
                                        </div>
                                    @else
                                        <div id="page-preview" class="page-preview">
                                            <div id="editor-container"></div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar AI -->

                        <div class="col-md-3 col-lg-2 ms-md-2" style='margin-top: 30px;'>
                            <div class="mb-3 ms-4">
                                <button id="save-changes-btn" class="px-4 button_Adduser">Save Changes
                                    <i class="fa-solid fa-floppy-disk fa-lg ms-2" style="color: #ffffff;"></i>
                                </button>

                                <button id="export-btn" href="{{ route('dashboard.export.book', $project->id) }}"
                                    class="px-4 mt-2 button_Adduser_editor">
                                    Export File
                                    <i class="fa-solid fa-download fa-lg ms-2"></i>
                                </button>

                                @if ($isPreview)
                                    <br>
                                    <br>
                                    <br>
                                    <a href="{{ route('dashboard.books.show', $project->id) }}"
                                        class="px-4 button_Adduser">
                                        Edit Book
                                        <i class="fa-solid fa-pen-to-square fa-lg ms-2"></i>
                                    </a>
                                @endif
                            </div>
                            @if (!$isPreview)
                                <div class="p-3 shadow me-md-5 sidebar-editor rounded-4" id="generate-text-section">
                                    <div class="border-bottom">
                                        <div class="mt-3 mb-1 d-flex justify-content-between align-items-center">
                                            <h4 class="mb-0">Generate Text</h4>
                                            <span id="close-generate-text-section">
                                                <i class="fa-solid fa-xmark fa-lg" style="cursor: pointer;"></i>
                                            </span>
                                        </div>
                                        <div class="p-3 mt-3 border generate-text rounded-4 bg-light">
                                            <h5 id="generated-title" class="mb-2">AI Result</h5>
                                            <p id="generated-text" class="mb-3 text-muted">
                                                Generated text will be shown here after generation...
                                            </p>
                                            <div class="d-flex justify-content-center">
                                                <button id="insert-ai-text-btn" class="mt-2 btn btn-success w-75">
                                                    <i class="fa-solid fa-angles-left fa-lg me-1"></i> Insert
                                                </button>
                                            </div>
                                        </div>
                                        @php
                                            $usedWords = auth()->user()->subscriptions->first()->used_words ?? 0;
                                            $wordLimit =
                                                auth()->user()->subscriptions->first()->plan->word_number ?? 0;
                                            $isLimitExceeded = $usedWords >= $wordLimit;
                                        @endphp
                                        <div class="mt-4 d-flex justify-content-center">
                                            <div class="input-container d-flex w-100" style="max-width: 600px;">
                                                <input id="user-input" type="text" class="form-control me-2"
                                                    placeholder="Enter text here...">
                                                <button
                                                    class="btn btn-primary {{ $isLimitExceeded ? 'btn-disabled' : '' }}"
                                                    id="generate-btn" onclick="improveWithAI()"
                                                    {{ $isLimitExceeded ? 'disabled' : '' }}
                                                    {{ $isLimitExceeded ? 'الباقة انتهت' : 'توليد النص' }}>Generate</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-3 mt-3 border rounded-4" id="tips-container">
                                        <div class="mb-2 d-flex justify-content-between">
                                            <h4>Tips</h4>
                                            <i class="fa-solid fa-xmark fa-lg align-self-center" id="close-tips" style="cursor: pointer"></i>
                                        </div>

                                        <p class="mt-3">
                                            <ol>
                                                <li>Specify the book type clearly (novel, educational, technical, etc.).</li>
                                                <li>Define the number of chapters or desired content length.</li>
                                                <li>Identify the target audience to match the tone and language.</li>
                                            </ol>
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </main>
        </div>
    </div>

    @push('scripts')
        
    <script>
        const closeTipsButton = document.getElementById('close-tips');
        const tipsContainer = document.getElementById('tips-container');
        closeTipsButton.addEventListener('click', () => {
            tipsContainer.style.display = 'none';
        })
    </script>

    <script>
        const generateTextTection = document.getElementById('generate-text-section');
        const closeGenerateTextTection = document.getElementById('close-generate-text-section');
        closeGenerateTextTection.addEventListener('click', () => {
            generateTextTection.style.display = 'none';
            generateTextTection.style.overflow = 'initial';
        })
    </script>

    {{-- Load Quill only if not in preview mode --}}
    @if (!$isPreview)
        <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    @endif
    {{-- <script src="/user/user/js/mian.js"></script> --}}
    <script>
        const TOGETHER_API_KEY = "{{ config('services.togetherai.api_key') }}";
        let quill; // Will be undefined in preview mode
        let currentUsedWords = {{ auth()->user()->subscriptions->first()->used_words ?? 0 }};
        const wordLimit = {{ auth()->user()->subscriptions->first()->plan->word_number ?? 0 }};

        document.addEventListener('DOMContentLoaded', function() {
            const isPreview = {{ $isPreview ? 'true' : 'false' }};
            const chapters = document.querySelectorAll('.chapter-item');
            const contentArea = isPreview ? document.querySelector('.preview-area') : null;

            function setActiveChapterContent(content) {
                if (isPreview) {
                    contentArea.innerHTML = content || '<p>No content available.</p>';
                } else if (quill) {
                    quill.setContents([]);
                    quill.clipboard.dangerouslyPasteHTML(content || '<p></p>');
                }
            }

            if (!isPreview) {
                const toolbarOptions = [
                    [{
                        'font': []
                    }, {
                        'size': []
                    }],
                    ['bold', 'italic', 'underline', 'strike', {
                        'script': 'sub'
                    }, {
                        'script': 'super'
                    }],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    [{
                        'header': 1
                    }, {
                        'header': 2
                    }, 'blockquote', 'code-block'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }, {
                        'list': 'check'
                    }],
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }],
                    [{
                        'direction': 'rtl'
                    }],
                    [{
                        'align': []
                    }],
                    ['link', 'image', 'video'],
                    ['undo', 'redo'],
                    ['clean']
                ];

                quill = new Quill('#editor-container', {
                    theme: 'snow',
                    modules: {
                        toolbar: {
                            container: toolbarOptions,
                            handlers: {
                                'undo': function() {
                                    this.quill.history.undo();
                                },
                                'redo': function() {
                                    this.quill.history.redo();
                                }
                            }
                        },
                        history: {
                            delay: 1000,
                            maxStack: 500,
                            userOnly: true
                        }
                    }
                });

                if (chapters.length > 0) {
                    const firstChapter = chapters[0];
                    setActiveChapterContent(firstChapter.getAttribute('data-content'));
                }

                document.getElementById('save-changes-btn').addEventListener('click', saveChanges);
                document.getElementById('export-btn').addEventListener('click', function() {
                    saveChanges();
                    window.location.href = this.getAttribute('href');
                });
                document.getElementById('insert-ai-text-btn').addEventListener('click', insertAiText);

                // Add SVG icons for undo/redo
                const icons = Quill.import('ui/icons');
                if (!icons['undo']) {
                    icons['undo'] =
                        '<svg viewBox="0 0 18 18"><polygon class="ql-fill" points="6 4 3 1 0 4 0 1 0 7 6 7 6 4"></polygon><path class="ql-fill" d="M12.82,3A6.18,6.18,0,0,1,19,9.18v0a6.18,6.18,0,0,1-6.18,6.18H10a.5.5,0,0,1,0-1h2.82A5.18,5.18,0,0,0,18,9.18v0A5.18,5.18,0,0,0,12.82,4H6.72l2,2H6V1h5Z" transform="translate(-1 -1)"></path></svg>';
                    icons['redo'] =
                        '<svg viewBox="0 0 18 18"><polygon class="ql-fill" points="12 4 15 1 18 4 18 1 18 7 12 7 12 4"></polygon><path class="ql-fill" d="M7.18,3A6.18,6.18,0,0,0,1,9.18v0A6.18,6.18,0,0,0,7.18,15H10a.5.5,0,0,0,0-1H7.18A5.18,5.18,0,0,1,2,9.18v0A5.18,5.18,0,0,1,7.18,4H13.28l-2,2H13V1H8Z" transform="translate(-1 -1)"></path></svg>';
                }
            } else {
                // Preview mode logic
                if (chapters.length > 0) {
                    setActiveChapterContent(chapters[0].getAttribute('data-content'));
                }

                document.getElementById('export-btn').addEventListener('click', function() {
                    window.location.href = this.getAttribute('href');
                });
            } 

            chapters.forEach(chapter => {
                chapter.addEventListener('click', function() {
                    chapters.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    setActiveChapterContent(this.getAttribute('data-content'));
                });
            });

            if (chapters.length > 0) {
                chapters[0].classList.add('active');
            }

        });

        function saveChanges() {
            const selectedChapter = document.querySelector('.chapter-item.active');
            if (!selectedChapter) {
                alert("Please select a chapter to save.");
                return;
            }

            const chapterId = selectedChapter.getAttribute('data-id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const updatedContent = quill.root.innerHTML;


            fetch(`/dashboard/chapter/${chapterId}`, {
                    method: 'PUT', // Use PUT for update
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        content: updatedContent
                    })
                })
                .then(res => res.json())
                .then(data => {

                    const saveButton = document.getElementById('save-changes-btn');
                    let saveButtonText = saveButton.textContent;
                    saveButton.textContent = "Saving...";


                    if (data.success) {
                        // alert("Chapter saved successfully!");
                        // Update the local data-content to prevent needing a page reload
                        selectedChapter.setAttribute('data-content', updatedContent);
                        setTimeout(() => {
                            saveButton.textContent = saveButtonText;
                        }, 500);
                    } else {
                        // alert("Failed to save chapter. " + (data.message || ''));
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("An error occurred while saving.");
                });
        }

        function insertAiText() {
            const aiText = document.getElementById('generated-text').innerHTML;
            if (quill) {
                const range = quill.getSelection();
                const cursorPosition = range ? range.index : quill.getLength();
                
                // Focus the editor first
                quill.focus();
                
                // Insert with 'user' source to enable undo/redo
                quill.clipboard.dangerouslyPasteHTML(cursorPosition, aiText, 'user');
            }
        }

        function improveWithAI() {
            const userInput = document.getElementById('user-input').value;
            const apiUrl = `https://api.together.xyz/v1/completions`;
            const generatedTextElement = document.getElementById('generated-text');
            const generatedTitleElement = document.getElementById('generated-title');

            if (!userInput) {
                generatedTextElement.textContent = "Please enter text to improve.";
                return;
            }

            if (currentUsedWords >= wordLimit) {
                alert("You have exceeded your word limit for this billing cycle.");
                return;
            }

            // --- Build context from current and previous chapters ---
            const chapters = Array.from(document.querySelectorAll('.chapter-item'));
            const activeIndex = chapters.findIndex(c => c.classList.contains('active'));
            let context = '';
            for (let i = 0; i <= activeIndex; i++) {
                const title = chapters[i].textContent.trim();
                const content = chapters[i].getAttribute('data-content') || '';
                context += `Title: ${title}\nContent: ${content}\n\n`;
            }

            // --- Build the full prompt ---
            const prompt =
                `You are an expert book writer. Here are the current and previous chapters for context:\n${context}\n\nNow, based on the above, generate ONLY the next part of the story. Do NOT repeat previous content. Do NOT include any titles or summaries. The following is a description or instruction for what should happen next: "${userInput}". Write only the new story text that follows.
                
                do not give the result as a code block
                
                `;

                

            console.log('Prompt sent to AI:', prompt);

            fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${TOGETHER_API_KEY}`
                    },
                    body: JSON.stringify({
                        model: 'mistralai/Mistral-7B-Instruct-v0.3',
                        prompt: prompt,
                        max_tokens: 1024
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);

                    if (data.choices && data.choices.length > 0) {
                        const aiGeneratedText = data.choices[0].text;

                        generatedTitleElement.textContent = "Generated AI Text";
                        generatedTextElement.textContent = aiGeneratedText || "لم يتم توليد نص بعد.";
                    } else {
                        generatedTextElement.textContent = "لم يتم توليد نص بعد.";
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    generatedTextElement.textContent = "حدث خطأ أثناء الاتصال بـ API.";
                });
        }
    </script>


    <script>
        const editProjectTitleButton = document.getElementById('edit-project-title');
        const projectTitleInput = document.getElementById('project-title');

        editProjectTitleButton.addEventListener('click', () => {
            projectTitleInput.removeAttribute('readonly');
        })

        projectTitleInput.addEventListener('blur', () => {
            if (projectTitleInput.getAttribute('readonly')) return;


            fetch("{{ route('dashboard.book.fetch.update-title', [], true) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        project_id: projectTitleInput.dataset.projectid,
                        title: projectTitleInput.value,
                    })
                })
                .then(res => res.json())
                .then(data => {
                    console.log(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                });

            projectTitleInput.setAttribute('readonly', true);
        })
    </script>
    @endpush
</x-layouts.dashboard.app>
