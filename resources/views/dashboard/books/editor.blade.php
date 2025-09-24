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
            body {
                overflow: hidden;
            }

            /* Dynamically scale editor / preview based on chosen Treem size */
            .page-preview {
                width: {{ $dimensions['w'] }}px;
                height: {{ $dimensions['h'] }}px;
                border: 1px solid #e0e0e0;
                background: #ffffff;
                margin: 0 auto;
                /* overflow-y: auto; */
                box-shadow: 0 0 6px rgba(0, 0, 0, 0.1);
            }

            .page-preview .ql-editor {
                /* width: {{ $dimensions['w'] . 'px'}};
                min-height: {{ $dimensions['h'] . 'px'}};
                height: {{ $dimensions['h'] . 'px'}}; */
                overflow-y: auto;
                padding: 20px 40px;
                padding-bottom: 120px;
                /* inner margin for text */
                box-sizing: border-box;
                background-color: #fff;
            }

            .preview-area {
                width: {{ $dimensions['w'] . 'px'}};
                height: {{ $dimensions['h'] . 'px'}};
                background: #fff;
                padding: 40px 20px;
                box-shadow: 1px 1px 4px 0px #0000004a;
                margin: auto;
            }

            @media(max-width: 1565px) {
                .preview-area {
   
                    height: fit-content;
                }
            }

            @media(max-width: 600px) {
                .preview-area {
                    width: 100%;
                }
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
                /* max-height: 80vh; */
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
                /* max-height: min({{ $dimensions['h'] }}px, 70vh) !important; */
                overflow-y: auto !important;
            }

            /* AI Generated text area scrolling */
            #generated-text {
                max-height: 200px;
                overflow-y: auto;
                overflow-x: hidden;
            }

        </style>

        <style>
            .main-container {
                display: flex;
                gap: 10px;
                padding: 15px;


                .sidebar-editor {
                    padding: 10px;
                }

                .editor-container {
                    flex: 1;
                    /* height: {{ $dimensions['h'] . 'px'}}; */
                }

            .sidebar-ai {
                    .buttons {
                    display: flex;
                    gap: 5px;
                    align-items: center;
                    margin-bottom: 20px;

                    button, a {
                        padding: 20px 25px;
                        display: flex;
                        align-items: center;
                        height: fit-content;
                    }
                }
            }
        }

            @media(max-width: 1565px) {
                body {
                    overflow: auto !important;
                }
                .main-container {
                    flex-direction: column;

                }

                .editor-container {
                    margin-bottom: 75px;
                }
            }

            @media(max-width: 600px) {
                .page-preview {
                    width: 100%;
                    height: fit-content;
                }

                .sidebar-ai {
                    .buttons {
  
                justify-content: center;
                margin-bottom: 10px !important;

                    button, a {
                        padding: 15px 20px !important;
                        display: flex;
                        align-items: center !important;
                        height: 50px !important;
                        margin: 0 !important;
                    }
                }
            }
            }

         
        </style>
    @endpush

    <div class="col-12 main-container">
        <!-- Sidebar chapters -->
        <div class="shadow sidebar-editor h-100 rounded-4">

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
                    <li class=" {{ $key == 0 ? 'active' : '' }} mb-1" data-id="{{ $chapter->id }}"
                        data-content="{{ $chapter->content }}" title="{{ $chapter->title }}">

                        <div style="display: flex;align-items: center;gap:5px">
                            <div>

                                <a href="{{ route('dashboard.chapter.edit', $chapter->id) }}"
                                    style="text-decoration: none">
                                    <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                </a>

                                <form action="{{ route('dashboard.chapter.destroy', $chapter->id) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="text-danger" style="border:none"
                                        onclick="return confirm('Are you sure you want to delete this chapter?');">
                                        <i class="fa-solid fa-trash fa-lg"></i>
                                    </button>
                                </form>
                            </div>

                            <div class="chapter-item {{ $key == 0 ? 'active' : '' }}"
                                data-id="{{ $chapter->id }}" data-content="{{ $chapter->content }}">
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
        <div class="editor-container">
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

        <!-- Sidebar AI -->
        <div class="sidebar-ai" >
            <div class="buttons">
                <button id="save-changes-btn" class=" button_Adduser">Save Changes
                    <i class="fa-solid fa-floppy-disk fa-lg ms-2" style="color: #ffffff;"></i>
                </button>

                <button id="export-btn" href="{{ route('dashboard.export.book', $project->id) }}"
                    class=" button_Adduser_editor">
                    Export File
                    <i class="fa-solid fa-download fa-lg ms-2"></i>
                </button>

                @if ($isPreview)
                    <br>
                    <br>
                    <br>
                    <a href="{{ route('dashboard.books.show', $project->id) }}" class=" button_Adduser">
                        Edit Book
                        <i class="fa-solid fa-pen-to-square fa-lg ms-2"></i>
                    </a>
                @endif
            </div>

            @if (!$isPreview)
                <div class="p-3 shadow sidebar-editor rounded-4" id="generate-text-section">
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
                                <button class="btn btn-primary {{ $isLimitExceeded ? 'btn-disabled' : '' }}"
                                    id="generate-btn" onclick="improveWithAI()" {{ $isLimitExceeded ? 'disabled' : '' }} {{ $isLimitExceeded ? 'الباقة انتهت' : 'توليد النص' }}>Generate</button>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 mt-3 border rounded-4" id="tips-container">
                        <div class="mb-2 d-flex justify-content-between">
                            <h4>Tips</h4>
                            <i class="fa-solid fa-xmark fa-lg align-self-center" id="close-tips"
                                style="cursor: pointer"></i>
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
        {{--
        <script src="/user/user/js/mian.js"></script> --}}
        <script>
            const TOGETHER_API_KEY = "{{ config('services.togetherai.api_key') }}";
            let quill; // Will be undefined in preview mode
            let currentUsedWords = {{ auth()->user()->subscriptions->first()->used_words ?? 0 }};
            const wordLimit = {{ auth()->user()->subscriptions->first()->plan->word_number ?? 0 }};

            function parseStyleAttribute(styleAttr) {
                const result = {};
                if (!styleAttr) return result;
                styleAttr.split(';').forEach(pair => {
                    const [key, value] = pair.split(':').map(s => s && s.trim());
                    if (key && value) result[key.toLowerCase()] = value;
                });
                return result;
            }

            function registerQuillAttributors() {
                const Parchment = Quill.import('parchment');
                // Block-level font-size to avoid span wrapping
                const FontSizeBlockStyle = new Parchment.Attributor.Style('fontsize', 'font-size', { scope: Parchment.Scope.BLOCK });
                const LineHeightStyle = new Parchment.Attributor.Style('lineheight', 'line-height', { scope: Parchment.Scope.BLOCK });
                const MarginStyle = new Parchment.Attributor.Style('margin', 'margin', { scope: Parchment.Scope.BLOCK });
                Quill.register(FontSizeBlockStyle, true);
                Quill.register(LineHeightStyle, true);
                Quill.register(MarginStyle, true);
            }

            function addClipboardMatcherPreserveStyles(quillInstance) {
                quillInstance.clipboard.addMatcher(Node.ELEMENT_NODE, (node, delta) => {
                    const styles = parseStyleAttribute(node.getAttribute && node.getAttribute('style'));
                    if (!styles || delta.ops == null) return delta;

                    const fontSize = styles['font-size'];
                    const lineHeight = styles['line-height'];
                    const margin = styles['margin'];
                    const marginBottom = styles['margin-bottom'];

                    // Apply block-level styles on trailing newline
                    const last = delta.ops[delta.ops.length - 1];
                    if (last && typeof last.insert === 'string' && last.insert.endsWith('\n')) {
                        last.attributes = last.attributes || {};
                        if (fontSize) last.attributes.fontsize = fontSize;
                        if (lineHeight) last.attributes.lineheight = lineHeight;
                        if (margin) {
                            last.attributes.margin = margin;
                        } else if (marginBottom) {
                            last.attributes.margin = `0 0 ${marginBottom} 0`;
                        }
                    }

                    return delta;
                });
            }

            function initQuillEditor() {
                const toolbarOptions = [
                    [{ 'font': [] }, { 'size': [] }],
                    ['bold', 'italic', 'underline', 'strike', { 'script': 'sub' }, { 'script': 'super' }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'header': 1 }, { 'header': 2 }, 'blockquote', 'code-block'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'list': 'check' }],
                    [{ 'indent': '-1' }, { 'indent': '+1' }],
                    [{ 'direction': 'rtl' }],
                    [{ 'align': [] }],
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
                                'undo': function () { this.quill.history.undo(); },
                                'redo': function () { this.quill.history.redo(); }
                            }
                        },
                        history: { delay: 1000, maxStack: 500, userOnly: true }
                    }
                });

                registerQuillAttributors();
                addClipboardMatcherPreserveStyles(quill);

                // Add SVG icons for undo/redo
                const icons = Quill.import('ui/icons');
                if (!icons['undo']) {
                    icons['undo'] = '<svg viewBox="0 0 18 18"><polygon class="ql-fill" points="6 4 3 1 0 4 0 1 0 7 6 7 6 4"></polygon><path class="ql-fill" d="M12.82,3A6.18,6.18,0,0,1,19,9.18v0a6.18,6.18,0,0,1-6.18,6.18H10a.5.5,0,0,1,0-1h2.82A5.18,5.18,0,0,0,18,9.18v0A5.18,5.18,0,0,0,12.82,4H6.72l2,2H6V1h5Z" transform="translate(-1 -1)"></path></svg>';
                    icons['redo'] = '<svg viewBox="0 0 18 18"><polygon class="ql-fill" points="12 4 15 1 18 4 18 1 18 7 12 7 12 4"></polygon><path class="ql-fill" d="M7.18,3A6.18,6.18,0,0,0,1,9.18v0A6.18,6.18,0,0,0,7.18,15H10a.5.5,0,0,0,0-1H7.18A5.18,5.18,0,0,1,2,9.18v0A5.18,5.18,0,0,1,7.18,4H13.28l-2,2H13V1H8Z" transform="translate(-1 -1)"></path></svg>';
                }
            }

            function bindChapterListInteractions(isPreview, chapters, contentArea) {
                function setActiveChapterContent(content) {
                    if (isPreview) {
                        contentArea.innerHTML = content || '<p>No content available.</p>';
                    } else if (quill) {
                        quill.setContents([]);
                        quill.clipboard.dangerouslyPasteHTML(content || '<p></p>');
                    }
                }

                chapters.forEach(chapter => {
                    chapter.addEventListener('click', function () {
                        chapters.forEach(c => c.classList.remove('active'));
                        this.classList.add('active');
                        setActiveChapterContent(this.getAttribute('data-content'));
                    });
                });

                if (chapters.length > 0) {
                    isPreview ? contentArea && (contentArea.innerHTML = chapters[0].getAttribute('data-content') || '<p>No content available.</p>')
                        : setActiveChapterContent(chapters[0].getAttribute('data-content'));
                    chapters[0].classList.add('active');
                }

                return { setActiveChapterContent };
            }

            document.addEventListener('DOMContentLoaded', function () {
                const isPreview = {{ $isPreview ? 'true' : 'false' }};
                const chapters = document.querySelectorAll('.chapter-item');
                const contentArea = isPreview ? document.querySelector('.preview-area') : null;

                if (!isPreview) {
                    initQuillEditor();
                    document.getElementById('save-changes-btn').addEventListener('click', saveChanges);
                    document.getElementById('export-btn').addEventListener('click', function () {
                        saveChanges();
                        window.location.href = this.getAttribute('href');
                    });
                    document.getElementById('insert-ai-text-btn').addEventListener('click', insertAiText);
                } else {
                    document.getElementById('export-btn').addEventListener('click', function () {
                        window.location.href = this.getAttribute('href');
                    });
                }

                bindChapterListInteractions(isPreview, chapters, contentArea);
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


        <script>
            window.addEventListener('DOMContentLoaded', function () {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        </script>
    @endpush
</x-layouts.dashboard.app>