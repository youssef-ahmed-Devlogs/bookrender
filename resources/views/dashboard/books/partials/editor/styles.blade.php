<style>
    body {
        overflow: hidden;
    }

    /* Dynamically scale editor / preview based on chosen Treem size */
    .page-preview {
        width:
            {{ $dimensions['w'] }}
            px;
        height:
            {{ $dimensions['h'] }}
            px;
        border: 1px solid #e0e0e0;
        background: #ffffff;
        margin: 0 auto;
        /* overflow-y: auto; */
        box-shadow: 0 0 6px rgba(0, 0, 0, 0.1);
    }

    .page-preview .ql-editor {
        width:
            {{ $dimensions['w'] }}
            px;
        min-height:
            {{ $dimensions['h'] }}
            px;
        height:
            {{ $dimensions['h'] }}
            px;
        overflow-y: auto;
        padding: 20px 40px;
        padding-bottom: 120px;
        /* inner margin for text */
        box-sizing: border-box;
        background-color: #fff;
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