<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/bootstrap.min.css') }}" />
    <title>Chapters UI</title>
    <style>
        @php
            // Base paragraph font size from session (fallback to 14)
            $base = (int) (session('font_size', 14));
            // Scales based on best practices for long-form reading
            $sizes = [
                'p' => $base,                    // paragraph
                'h6' => (int) round($base + 2),  // minor heading
                'h5' => (int) round($base + 4),
                'h4' => (int) round($base + 6),
                'h3' => (int) round($base + 10), // section title
                'h2' => (int) round($base + 14), // chapter subtitle
                'h1' => (int) round($base + 20), // chapter/main title
            ];
            // Line-height ~1.6 for body text; slightly tighter for big headings
            $lhP = 1.7; $lhH = 1.25;
            // Vertical rhythm spacing multiples of base size
            $spaceSm = $base * 0.75;  // small gap
            $spaceMd = $base * 1.25;  // medium gap
            $spaceLg = $base * 2.00;  // large gap
        @endphp

        :root {
            --font-base: {{ $sizes['p'] }}px;
            --font-h1: {{ $sizes['h1'] }}px;
            --font-h2: {{ $sizes['h2'] }}px;
            --font-h3: {{ $sizes['h3'] }}px;
            --font-h4: {{ $sizes['h4'] }}px;
            --font-h5: {{ $sizes['h5'] }}px;
            --font-h6: {{ $sizes['h6'] }}px;
            --line-height-body: {{ $lhP }};
            --line-height-heading: {{ $lhH }};
            --space-sm: {{ (int) round($spaceSm) }}px;
            --space-md: {{ (int) round($spaceMd) }}px;
            --space-lg: {{ (int) round($spaceLg) }}px;
        }

        /* Apply dynamic typography to generated HTML within preview */
        .content-preview, .chapter-content {
            font-size: var(--font-base);
            line-height: var(--line-height-body);
        }

        .content-preview p, .chapter-content p {
            margin: 0 0 var(--space-sm) 0;
        }

        .content-preview h1, .chapter-content h1 { font-size: var(--font-h1); line-height: var(--line-height-heading); margin: var(--space-lg) 0 var(--space-md); }
        .content-preview h2, .chapter-content h2 { font-size: var(--font-h2); line-height: var(--line-height-heading); margin: var(--space-md) 0 var(--space-sm); }
        .content-preview h3, .chapter-content h3 { font-size: var(--font-h3); line-height: var(--line-height-heading); margin: var(--space-md) 0 var(--space-sm); }
        .content-preview h4, .chapter-content h4 { font-size: var(--font-h4); line-height: var(--line-height-heading); margin: var(--space-sm) 0 calc(var(--space-sm) * 0.75); }
        .content-preview h5, .chapter-content h5 { font-size: var(--font-h5); line-height: var(--line-height-heading); margin: calc(var(--space-sm) * 0.75) 0 calc(var(--space-sm) * 0.5); }
        .content-preview h6, .chapter-content h6 { font-size: var(--font-h6); line-height: var(--line-height-heading); margin: calc(var(--space-sm) * 0.5) 0 calc(var(--space-sm) * 0.5); }

        .content-preview ul, .content-preview ol,
        .chapter-content ul, .chapter-content ol {
            padding-left: 1.25em;
            margin: 0 0 var(--space-sm) 1em;
        }

        /* Ensure better spacing after headings before first paragraph */
        .content-preview h1 + p,
        .content-preview h2 + p,
        .content-preview h3 + p,
        .content-preview h4 + p,
        .content-preview h5 + p,
        .content-preview h6 + p { margin-top: calc(var(--space-sm) * 0.25); }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
        }

        h1 {
            color: #007bff;
            margin-bottom: 40px;
            font-size: 28px;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            max-width: 1200px;
            width: 100%;
        }

        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 350px;
            padding: 25px;
            display: flex;
            flex-direction: column;
        }

        .card h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 15px;
            font-size: 20px;
        }

        .card input[type="text"],
        .card textarea {
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
            font-size: 14px;
            width: 100%;
        }

        .button-wrapper {
            margin-top: 30px;
            text-align: center;
            width: 100%;
        }

        .confirm-btn {
            background-color: #00bfff;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .confirm-btn:hover {
            background-color: #0099cc;
        }

        .predict h4 {
            background: linear-gradient(90deg, #1876F1, #00BEF5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }

        .predict p {
            font-weight: 400;
            font-size: 12px;
            line-height: 185%;
            letter-spacing: 0%;

        }

        .predict h5 {
            font-weight: 500;
            font-size: 15px;
            line-height: 185%;
            letter-spacing: 0%;


        }

        .predict h1 {
            font-weight: 600;
            font-size: 32px;
            line-height: 100%;
            letter-spacing: 0%;
            text-align: center;
            vertical-align: middle;
            background: linear-gradient(90deg, #1876F1, #00BEF5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }

        .button_Adduser {
            background: linear-gradient(to right, #1876F1, #00BEF5);
            color: #fff;
            font-size: 15px;
            font-weight: bold;
            padding: 15px 30px;
            font-weight: 600;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            top: 30px;
            right: 20px;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }

        .button_Adduser:hover {
            color: #fff;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .section-type {
            display: inline-block;
            background: #e3f2fd;
            color: #1976d2;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .section-type.introduction {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .section-type.copyright {
            background: #fff3e0;
            color: #f57c00;
        }

        .section-type.table_of_contents {
            background: #e8f5e8;
            color: #388e3c;
        }

        .section-type.chapter {
            background: #e3f2fd;
            color: #1976d2;
        }

        .content-preview {
            max-height: 200px;
            overflow: hidden;
            position: relative;
        }

        .content-preview::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            background: linear-gradient(transparent, white);
        }

        .table-of-contents {
            font-family: 'Times New Roman', serif;
        }

        .table-of-contents h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
            color: #333;
        }

        .toc-content {
            margin: 20px 0;
        }

        .toc-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            padding: 5px 0;
        }

        .toc-title {
            font-weight: 500;
            color: #333;
        }

        .toc-dots {
            flex-grow: 1;
            border-bottom: 1px dotted #ccc;
            margin: 0 10px;
            height: 1px;
        }

        .toc-page {
            font-weight: 500;
            color: #666;
            min-width: 30px;
            text-align: right;
        }

        .copyright-page {
            text-align: center;
            font-family: 'Times New Roman', serif;
            line-height: 1.6;
        }

        .copyright-page h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
        }

        .copyright-page p {
            margin-bottom: 15px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>

<body>


    <!-- Form to save chapters (commented out for now) -->
    <!--
<div class="container">
    <form action="{{ route('dashboard.generateContentAI') }}" method="POST">
        @csrf
        <input type="hidden" name="project_id" value="{{ session('id') }}">

        @foreach (session('chapters', []) as $index => $chapter)
<div class="mb-4 card">
                <h2>Chapter {{ $index + 1 }}</h2>
                <input type="text" name="chapters[{{ $index }}][title]"
                    value="{{ \Illuminate\Support\Str::before($chapter, ':') }}"
                    placeholder="Chapter title"
                    class="mb-2 form-control" required>

                <textarea name="chapters[{{ $index }}][content]" rows="6" placeholder="Chapter content"
                    class="form-control" required>{{ \Illuminate\Support\Str::after($chapter, ':') }}</textarea>
            </div>
@endforeach

        <button type="submit" class="btn btn-success">Save Chapters</button>
    </form>
</div>
-->

    <!-- Chapter preview layout -->


    <div class="container mt-4 predict" style="display: block">
        <h1>Your Book is Complete!</h1>

        @php
            $generatedContent = session('generated_content', []);
        @endphp

        <div class="row justify-content-center">
            @foreach ($generatedContent as $index => $section)
                <div class="mb-5 col-lg-6">
                    <div class="p-4 bg-white rounded-4" style='height: 80%;'>
                        <div class="section-type {{ $section['type'] ?? 'chapter' }}">
                            {{ ucfirst(str_replace('_', ' ', $section['type'] ?? 'chapter')) }}
                        </div>

                        <h4 class="mb-3 text-center d-flex align-items-center justify-content-center">
                            @if(($section['type'] ?? 'chapter') === 'chapter')
                                Chapter {{ $section['chapter_number'] ?? ($index + 1) }}
                            @else
                                {{ $section['title'] }}
                            @endif
                            <i class="fa-solid fa-file-word ms-2" style="color: #2E73B8;"></i>
                        </h4>

                        <h5 style='text-align:center'>
                            <strong>{{ $section['title'] }}</strong>
                        </h5>

                        <div class="content-preview">
                            {!! $section['content'] !!}
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-center">
                        <a href="{{ route('dashboard.books.show', request()->get('project_id')) }}"
                            class="button_Adduser me-3">
                            Edit <i class="fa-solid fa-pen-to-square ms-2"></i>
                        </a>
                        <a href="{{ route('dashboard.books.show', ['book' => request()->get('project_id'), 'preview' => true]) }}"
                            class="button_Adduser">
                            Preview <i class="fa-solid fa-file-lines ms-2"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{--
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const projectId = @json(session('project_id'));
            const chaptersToSave = @json(session('generated_content', []));
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            if (projectId && chaptersToSave.length > 0) {
                fetch("{{ route('dashboard.generateContentAI') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        project_id: projectId,
                        chapters: chaptersToSave
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Chapters saved successfully in the background.');
                            // Now, clear the session to prevent re-saving on refresh
                            fetch("{{ route('dashboard.clear.predict.session') }}", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            }).then(() => console.log('Predict session cleared.'));
                        } else {
                            console.error('Failed to save chapters:', data);
                        }
                    })
                    .catch(error => {
                        console.error('Error saving chapters:', error);
                    });
            }
        });
    </script> --}}

</body>

</html>