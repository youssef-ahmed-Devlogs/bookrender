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

        // Separate special sections from regular chapters
        $specialSections = ['Book Introduction', 'Copyright Page', 'Table of Contents'];
        $specialChapters = $chapters->whereIn('title', $specialSections);
        $regularChapters = $chapters->whereNotIn('title', $specialSections);

        // Get specific special sections
        $bookIntroChapter = $specialChapters->where('title', 'Book Introduction')->first();
        $copyrightChapter = $specialChapters->where('title', 'Copyright Page')->first();
        $tableOfContentsChapter = $specialChapters->where('title', 'Table of Contents')->first();
    @endphp
    <style>
        img {
            width: 100%;
        }

        @page {
            size: {{ $cssSize }};
            margin: 0.5in;
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
            text-align: left;
            margin: 0;
        }

        .chapter-title {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 20px;
            page-break-before: auto;
        }

        /* .chapter-content {
            text-align: left;
        } */

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
            text-align: center;
            padding: 0 2em;
        }

        .title-page {
            text-align: center;
            padding-top: 20%;
        }

        /* Page numbering fallback using fixed positioning */
        @if($project->add_page_num == 'Yes')
        .page-number {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            font-family: Arial, sans-serif;
            font-size: 10pt;
            color: #333;
            z-index: 1000;
        }
        @endif
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
        @if($project->second_title)
            <h2>{{ $project->second_title }}</h2>
        @endif
        <h3>By: {{ $project->author }}</h3>
        @if($project->description)
            <p>{{ $project->description }}</p>
        @endif
    </div>

    {{-- Special Sections (using PDF-compatible content from database) --}}
    @if($bookIntroChapter)
        <div style="page-break-after: always;">
            {!! $bookIntroChapter->content !!}
        </div>
    @endif

    @if($copyrightChapter)
        <div style="page-break-after: always;">
            {!! $copyrightChapter->content !!}
        </div>
    @endif

    @if($tableOfContentsChapter)
        <div style="page-break-after: always;">
            {!! $tableOfContentsChapter->content !!}
        </div>
    @endif

    {{-- Regular Chapters (using PDF-compatible content from database) --}}
    @foreach($regularChapters as $chapter)
        <div style="page-break-inside: avoid; margin-bottom: 40px;">
            {!! $chapter->content !!}
        </div>
    @endforeach

    {{-- Add Page Numbers using DomPDF script --}}
    @if($project->add_page_num == 'Yes')
        <script type="text/php">
            if (isset($pdf)) {
                $pdf->page_script('
                    $font = $fontMetrics->get_font("Arial");
                    $size = 10;
                    $text = "Page $PAGE_NUM of $PAGE_COUNT";
                    $width = $fontMetrics->get_text_width($text, $font, $size);
                    $x = ($pdf->get_width() - $width) / 2;
                    $y = $pdf->get_height() - 30;
                    $pdf->text($x, $y, $text, $font, $size);
                ');
            }
        </script>
    @endif

</body>

</html>