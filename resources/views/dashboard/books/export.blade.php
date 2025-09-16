<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>تصدير الكتاب</title>
    @php
        // Convert treem size like 6" x 9" to valid CSS size "6in 9in"
        $sizeString = $project->treem_size;
        $cssSize = $sizeString; // fallback
        if (preg_match('/([0-9\.]+)\"?\s*x\s*([0-9\.]+)\"?/i', $sizeString, $matches)) {
            $w = $matches[1];
            $h = $matches[2];
            $cssSize = $w . 'in ' . $h . 'in';
        }
    @endphp
    <style>
        img {
            width: 100%;
        }

        @page {
            size:
                {{ $cssSize }}
            ;
            margin: 1in;
            @if($project->bleed_file == 'Yes')
                bleed: 0.125in;
                marks: crop;
            @endif
        }

        /* Remove all margins on the very first (cover) page */
        @page: first {
            margin: 0;
        }

        body {
            /* font-family and font-size removed to respect editor styling */
            orphans: 0;
            widows: 0;
            margin: 0;
        }

        /* Prevent unexpected page breaks inside common block elements to reduce empty pages */
        h1,
        h2,
        h3,
        p,
        img,
        .chapter,
        .title-page,
        .book-header {
            page-break-inside: avoid;
        }

        .book-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .book-image {
            max-width: 180px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        h1 {
            font-size: 24px;
            margin: 10px 0;
        }

        .chapters {
            margin-top: 20px;
        }

        .chapter {
            padding: 0 0 15px 0;
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .chapter h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #000;
            text-align: center;
        }

        .chapter p {
            font-size: 15px;
            line-height: 1.8;
            text-align: justify;
            margin: 0;
        }

        .chapter-title {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 20px;
            page-break-before: auto;
        }

        .chapter-content {
            text-align: justify;
        }

        .footer {
            position: fixed;
            bottom: 0;
            right: 0;
            text-align: right;
        }

        .title-page {
            text-align: center;
            page-break-after: always;
            display: flex;
            flex-direction: column;
            justify-content: center;
            /* height removed to prevent overflow causing blank page */
        }

        .title-page h1 {
            font-size: 2.5em;
            margin-bottom: 0.5em;
        }

        .title-page h2 {
            font-size: 1.8em;
            margin-bottom: 1.5em;
            font-style: italic;
            color: #555;
        }

        .title-page h3 {
            font-size: 1.2em;
            margin-top: 3em;
        }

        .title-page p {
            margin-top: 1em;
            font-size: 1em;
            color: #666;
            text-align: justify;
            padding: 0 2em;
        }

        .title-page {
            text-align: center;
            padding-top: 20%;
        }
    </style>
</head>

<body>
    {{-- Cover Page --}}
    @if($project->image)
        <div style="page-break-after:always; width:100%; height:100%; margin:0; padding:0;">
            <img src="{{ public_path('books/' . $project->image) }}"
                style="width:100%;height:100%;object-fit:cover;display:block;" alt="Cover" />
        </div>
    @endif

    {{-- Title Page --}}
    <div class="title-page">
        <h1>{{ $project->title }}</h1>
        <h2>{{ $project->second_title }}</h2>
        <h3>By: {{ $project->author }}</h3>
        <p>{{ $project->description }}</p>
    </div>

    <div class="book-header">
        {{-- cover image removed from header --}}
        <h1>{{ $project->title }} {{ $project->second_title }}</h1>
    </div>

    @if($project->table_of_contents == 'Yes')
        <h1 class="chapter-title">Table of Contents</h1>
        <ul>
            @foreach($chapters as $chapter)
                <li>{{ $chapter->title }}</li>
            @endforeach
        </ul>
    @endif

    <div class="chapters">
        @foreach($chapters as $chapter)
            <div class="chapter">
                <h3>{{ $chapter->title }}</h3>
                <div class="chapter-content">
                    {!! nl2br($chapter->content) !!}
                </div>
            </div>
        @endforeach
    </div>

    {{-- Optional: Add Page Numbers --}}
    @if($project->add_page_num == 'Yes')
        <script type="text/php">
                                    if (isset($pdf)) {
                                        $pdf->page_script('
                                            $font = $fontMetrics->get_font("{{ $project->text_style }}", "normal");
                                            $pdf->text(500, 800, "Page $PAGE_NUM of $PAGE_COUNT", $font, 10);
                                        ');
                                    }
                                </script>
    @endif

</body>

</html>