<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->title }} - Export</title>

    <style>
        /* Page setup to match editor dimensions exactly */
        @page {
            size: {{ $dimensions['css'] }};
            margin: 0.5in; /* Minimal margins to let content container control layout */
            @if($project->bleed_file == 'Yes')
                bleed: 0.125in;
                marks: crop;
            @endif
        }

        /* Cover page with no margins */
        @page :first {
            margin: 0;
        }

        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: {{ $project->font_size }}px;
            line-height: 1.6;
            color: #333;
            orphans: 2;
            widows: 2;
            margin: 0;
            padding: 0;
        }

        /* Content container to match editor exactly */
        .page-content {
            max-width: 90%; /* Use percentage to ensure proper width */
            width: auto;
            margin: 0 auto;
            padding: 40px 20px; /* Exact editor padding */
            box-sizing: border-box;
            background: #fff;
        }

        /* Page break controls */
        .page-break {
            page-break-after: always;
        }

        .page-break-before {
            page-break-before: always;
        }

        .no-break {
            page-break-inside: avoid;
        }

        /* Cover page styling */
        .cover-page {
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            page-break-after: always;
        }

        .cover-page img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Title page - matching editor exactly */
        .title-page {
            page-break-after: always;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .title-page .page-content {
            text-align: center;
            padding: 0;
        }

        .title-page h1 {
            font-size: 32px;
            line-height: 1.25;
            margin: 24px 0 15px;
            font-weight: bold;
            color: #000;
        }

        .title-page h2 {
            font-size: 24px;
            font-style: italic;
            color: #555;
            margin-bottom: 30px;
        }

        .title-page .author {
            font-size: 16px;
            color: #333;
            margin-bottom: 30px;
        }

        .title-page .description {
            font-size: {{ $project->font_size }}px;
            color: #666;
            text-align: justify;
            max-width: 80%;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* Content styling - matching editor exactly */
        .content-section {
            page-break-before: always;
        }

        /* Remove any default margins that might interfere */
        .content-section .page-content {
            padding-top: 0;
        }

        /* Preserve all editor styling */
        h1 {
            font-size: 32px;
            line-height: 1.25;
            margin: 24px 0 15px;
            text-align: center;
            font-weight: bold;
            color: #000;
        }

        h2 {
            font-size: 24px;
            line-height: 1.3;
            margin: 20px 0 12px;
            font-weight: bold;
            color: #000;
        }

        h3 {
            font-size: 18px;
            line-height: 1.4;
            margin: 16px 0 10px;
            font-weight: bold;
            color: #000;
        }

        p {
            font-size: {{ $project->font_size }}px;
            line-height: 1.6;
            margin-bottom: 12px;
            @if($project->text_style === 'justify')
                text-align: justify;
            @else 
                text-align: left;
            @endif
        }

        /* Table of Contents styling - PDF compatible */
        .table-of-contents {
            max-width: 100%;
            margin: 0 auto;
        }

        .toc-content {
            margin-top: 32px;
        }

        /* Simple TOC item for DomPDF compatibility */
        .toc-item {
            margin-bottom: 12px;
            line-height: 1.6;
            position: relative;
            width: 100%;
            text-align: justify;
        }

        .toc-item:after {
            content: "";
            display: inline-block;
            width: 100%;
        }

        .toc-title {
            font-size: {{ $project->font_size }}px;
            background: white;
            padding-right: 4px;
        }

        .toc-page {
            font-size: {{ $project->font_size }}px;
            background: white;
            padding-left: 4px;
            font-weight: bold;
            float: right;
        }

        /* Create dotted leader effect */
        .toc-item .toc-title:after {
            content: " ............................................................................................................................................................................................................................";
            color: #333;
            font-weight: normal;
            overflow: hidden;
        }


        /* Preserve inline styles from editor */
        [style] {
            /* Allow inline styles to override */
        }

        /* Ensure all inline styles are preserved */
        * {
            font-family: inherit !important;
        }

        /* Default text alignment from project settings - can be overridden by inline styles */
        .content-section p:not([style*="text-align"]) {
            @if($project->text_style === 'justify')
                text-align: justify;
            @else 
                text-align: left;
            @endif
        }

        /* Ensure inline styles always take precedence */
        [style*="text-align"] {
            /* Inline text-align styles will override defaults */
        }

        [style*="margin"] {
            /* Inline margin styles will override defaults */
        }

        [style*="font-size"] {
            /* Inline font-size styles will override defaults */
        }

        /* Bold text */
        strong,
        b {
            font-weight: bold;
        }

        /* Italic text */
        em,
        i {
            font-style: italic;
        }

        /* Underlined text */
        u {
            text-decoration: underline;
        }

        /* Lists */
        ul,
        ol {
            margin: 12px 0;
            padding-left: 30px;
        }

        li {
            margin-bottom: 6px;
            font-size: {{ $project->font_size }}px;
            line-height: 1.6;
        }

        /* Images */
        img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 12px auto;
        }

        /* Blockquotes */
        blockquote {
            margin: 20px 0;
            padding: 15px 20px;
            border-left: 4px solid #ddd;
            background-color: #f9f9f9;
            font-style: italic;
        }

        /* Code blocks */
        pre,
        code {
            font-family: 'Courier New', monospace;
            background-color: #f5f5f5;
            padding: 2px 4px;
            border-radius: 3px;
        }

        pre {
            padding: 10px;
            margin: 12px 0;
            overflow-x: auto;
        }

        /* Page numbers */
        @if($project->add_page_num == 'Yes')
            @page {
                @bottom-center {
                    content: "Page " counter(page) " of " counter(pages);
                    font-family: Arial, sans-serif;
                    font-size: 10px;
                    color: #666;
                }
            }

        @endif
        /* Print-specific adjustments */
        @if($isPrintVersion)
            @media print {
                body {
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }

                .no-print {
                    display: none !important;
                }
            }

        @endif

        /* Ensure content doesn't overflow page boundaries */
        .content-wrapper {
            max-width: 100%;
            overflow-wrap: normal;
            word-wrap: normal;
            word-break: normal;
        }

        /* Fix text wrapping issues */
        .page-content {
            word-break: normal !important;
            overflow-wrap: normal !important;
            word-wrap: normal !important;
            white-space: normal !important;
        }

        .page-content * {
            word-break: normal !important;
            overflow-wrap: normal !important;
            word-wrap: normal !important;
        }

    </style>
</head>

<body>
    <!-- Cover Page -->
    @if($project->image && file_exists(public_path('books/' . $project->image)))
        <div class="cover-page">
            <img src="{{ public_path('books/' . $project->image) }}" alt="Book Cover">
        </div>
    @endif

    <!-- Title Page -->
    <div class="title-page">
        <div class="page-content">
            <h1>{{ $project->title }}</h1>
            @if($project->second_title)
                <h2>{{ $project->second_title }}</h2>
            @endif
            <div class="author">By: {{ $project->author }}</div>
            @if($project->description)
                <div class="description">{{ $project->description }}</div>
            @endif
        </div>
    </div>

    <!-- Book Content -->
    <div class="content-wrapper">
        @foreach($chapters as $index => $chapter)
            <div class="content-section {{ $index > 0 ? 'page-break-before' : '' }}">
                <div class="page-content">
                    {!! $chapter->content !!}
                </div>
            </div>
        @endforeach
    </div>

    <!-- Page numbering script for PDF -->
    @if($project->add_page_num == 'Yes')
        <script type="text/php">
            if (isset($pdf)) {
                $pdf->page_script('
                    $font = $fontMetrics->get_font("Arial", "normal");
                    $size = 10;
                    $pageText = "Page " . $PAGE_NUM . " of " . $PAGE_COUNT;
                    $y = $pdf->get_height() - 50;
                    $x = ($pdf->get_width() - $fontMetrics->get_text_width($pageText, $font, $size)) / 2;
                    $pdf->text($x, $y, $pageText, $font, $size, array(0.5, 0.5, 0.5));
                ');
            }
        </script>
    @endif
</body>

</html>