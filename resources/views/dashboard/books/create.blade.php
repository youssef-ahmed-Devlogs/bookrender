<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Book</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/style.css') }}" />
    <style>
        .image-upload-container {
            border: 2px dashed #ccc;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .image-upload-container:hover {
            background-color: #f8f9fa;
        }

        .image-preview {
            max-width: 200px;
            margin-top: 15px;
            display: none;
        }

        .form-control {
            margin-top: 15px;
        }

        .container-box select {
            border-radius: 12px;
            padding: 10px;
            border: 2px solid #1e90ff;
            font-weight: bold;
            color: white;
            background-color: #0C9BF3;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .container-box select:hover {
            background-color: #0a8ad9;
        }

        .container-box select:focus {
            outline: none;
            border-color: #1e90ff;
            box-shadow: 0 0 0 2px rgba(30, 144, 255, 0.2);
        }

        .container-box select option {
            background-color: white;
            color: #6c757d;
            padding: 14px 10px;
            font-size: 16px;
        }

        .container-box select option:hover {
            background-color: #d0e9ff;
            color: #0c9bf3;
        }

        .container-box select option:checked {
            background-color: #d0e9ff;
            color: #0c9bf3;
        }

        .image-upload-container {
            padding: 80px 120px;
        }

        .form-select i {
            color: white;
        }

        .generale {
            background-color: transparent;
            font-weight: 600;
            font-size: 16px;
            color: white;
            border: none;
        }

        .generat {
            border-left: 0.5px solid white;
            height: 80px;
        }

        .navbar-create {
            background: linear-gradient(to right, #1e90ff, #00bfff);
            padding: 0 !important;
        }

        .custom-dropdown {
            background-color: #0C9BF3;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 15px;
            font-weight: 500;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            margin-top: 20px;
        }

        .create-bg {
            background-color: #f6f6f6;
        }

        .custom-select-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .custom-select-wrapper::after {
            content: "▼";
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            pointer-events: none;
            color: white;
            font-size: 12px;
            font-weight: 900;
        }

        .dropdown-menu {
            border-radius: 12px;
            border: 2px solid #1e90ff;
            padding: 0;
            margin-top: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item {
            padding: 10px 20px;
            color: #333;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: #0C9BF3;
            color: white;
        }

        .dropdown-item.active {
            background-color: #0C9BF3;
            color: white;
        }

        @media (max-width: 768px) {
            .btn-nav {
                font-size: 12px !important;
            }

            .create-font h5 {
                font-size: 12px;
            }

            .generale {
                font-size: 12px;
            }

            .generat {
                border-left: none;
            }
        }

        /* --- Custom Dropdown Styles --- */
        .custom-dropdown-container {
            position: relative;
            width: 100%;
            margin-top: 15px;
        }

        .custom-dropdown-trigger {
            background-color: #0C9BF3;
            color: white;
            font-weight: 600;
            font-size: 16px;
            padding: 10px 15px;
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 2px solid #1e90ff;
            height: 50px;
            /* Consistent height */
        }

        .custom-dropdown-trigger .fa-chevron-down {
            transition: transform 0.2s ease-in-out;
        }

        .custom-dropdown-container.open .custom-dropdown-trigger .fa-chevron-down {
            transform: rotate(180deg);
        }

        .custom-dropdown-menu {
            position: absolute;
            top: calc(100% + 5px);
            left: 0;
            width: 100%;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
            padding: 10px;
            border: 1px solid #eee;
        }

        .custom-dropdown-container.open .custom-dropdown-menu {
            display: block;
        }

        .custom-dropdown-options ul {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 220px;
            overflow-y: auto;
        }

        .custom-dropdown-options li {
            padding: 14px 15px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #6c757d;
            font-size: 16px;
            border-radius: 8px;
            margin-bottom: 2px;
        }

        .custom-dropdown-options li:hover,
        .custom-dropdown-options li.selected {
            background-color: #d0e9ff;
            color: #0c9bf3;
        }

        .custom-dropdown-options li .remove-option {
            color: #adb5bd;
            font-size: 14px;
            visibility: hidden;
            transition: color 0.2s;
        }

        .custom-dropdown-options li .remove-option:hover {
            color: #dc3545;
        }

        .custom-dropdown-options li:hover .remove-option {
            visibility: visible;
        }

        .custom-dropdown-options .text-style-option-italic {
            font-style: italic;
            font-family: serif;
            font-weight: bold;
            font-size: 1.2em;
        }

        .custom-dropdown-options .text-style-option-b {
            font-weight: bold;
        }

        .custom-dropdown-add-new {
            display: flex;
            padding: 15px 5px 10px;
            border-top: 1px solid #f0f0f0;
            margin-top: 5px;
        }

        .custom-dropdown-add-new input {
            width: 100%;
            border: 1px solid #ced4da;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 14px;
            margin-right: 10px;
            transition: border-color 0.2s;
        }

        .custom-dropdown-add-new input:focus {
            outline: none;
            border-color: #0C9BF3;
        }

        .custom-dropdown-add-new button {
            background-color: #0C9BF3;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0 20px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .custom-dropdown-add-new button:hover {
            background-color: #0a8ad9;
        }

        .add-new-link {
            display: block;
            text-align: center;
            color: #0C9BF3;
            font-weight: 500;
            padding-top: 15px;
            text-decoration: none;
            border-top: 1px solid #f0f0f0;
            margin-top: 10px;
        }

        .add-new-link:hover {
            text-decoration: underline;
        }

        /* --- Number Input Counter --- */
        .number-input-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #0C9BF3;
            color: white;
            font-weight: 600;
            font-size: 16px;
            padding: 10px 15px;
            border-radius: 12px;
            border: 2px solid #1e90ff;
            height: 50px;
            margin-top: 15px;
        }

        .number-input-container span {
            flex-grow: 1;
            text-align: left;
        }

        .number-input-controls {
            display: flex;
            align-items: center;
            border-left: 1px solid #1e90ff;
            padding-left: 15px;
        }

        .number-input-controls button {
            background: transparent;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            padding: 0 5px;
        }

        .number-input-controls input {
            width: 50px;
            text-align: center;
            border: 1px solid #1e90ff;
            background: white;
            color: #333;
            border-radius: 6px;
            margin: 0 5px;
            font-size: 16px;
            -moz-appearance: textfield;
        }

        .number-input-controls input::-webkit-outer-spin-button,
        .number-input-controls input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* --- Custom Checkbox Button --- */
        .custom-checkbox-button {
            background-color: #0C9BF3;
            color: white;
            border: 2px solid #1e90ff;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            margin-top: 15px;
            height: 50px;
            cursor: pointer;
        }

        .custom-checkbox-button .checkbox-visual {
            width: 24px;
            height: 24px;
            background-color: transparent;
            border: 2px solid white;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
        }

        .custom-checkbox-button .checkbox-visual .fa-check {
            color: white;
            font-size: 14px;
            visibility: hidden;
        }

        .custom-checkbox-button.checked .checkbox-visual {
            background-color: white;
        }

        .custom-checkbox-button.checked .checkbox-visual .fa-check {
            color: #0C9BF3;
            visibility: visible;
        }

        /* --- Font Size "Add New" Stepper --- */
        .font-size-add {
            display: flex;
            align-items: center;
            padding: 10px 5px;
        }

        .font-size-add .stepper-btn {
            border: 1px solid #ced4da;
            background-color: #f8f9fa;
            font-weight: bold;
            width: 15px;
            height: 15px;
            border-radius: 100%;
            cursor: pointer;
            padding: 15px;
            color: black;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .font-size-add .font-size-input {
            width: 40px;
            text-align: center;
            margin: 0 10px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            padding: 5px;
        }

        .font-size-add .add-font-btn {
            margin-left: auto;
            background-color: #0C9BF3;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 5px 15px;
            cursor: pointer;
        }

        .custom-dropdown-trigger span {
            color: white;
        }

        button.custom-checkbox-button span {
            color: white;
        }
    </style>
</head>

<body class="create-bg">
    <nav class="navbar-create d-flex justify-content-between">
        <div class="mx-3 d-flex align-items-center">
            <a href="{{ route('dashboard.index') }}">
                <i class="mt-1 fa-solid fa-arrow-left fa-lg pe-md-3 mt-md-0" style="color: #ffffff;"></i>
            </a>
            <div class="save">
                <button type="button" class="text-white btn btn-nav save-btn" onclick="submitAndRedirect()">
                    <i class="fa-solid fa-floppy-disk fa-lg me-2" style="color: #ffffff;"></i>Save & Close
                </button>

            </div>
        </div>

        <div class=" d-flex align-items-center create-font">
            <img src="{{ asset('assets/dashboard/images/group10000078533.png') }}" alt="logo" />
            <h5 class="mt-1 text-white align-self-center pe-4"> Bookrender</h5>

        </div>



        <div class="generat d-flex align-items-center">
            <div class="d-flex ">
                <button class="ps-2 align-self-center generale"
                    onclick="document.querySelector('form').submit()">GENERATE</button>
                <i class="mx-3 fa-solid fa-arrow-right fa-lg align-self-center " style="color: #ffffff;"></i>
            </div>
        </div>
    </nav>

    <form action="{{ route('dashboard.books.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="container mt-5">
            <div class="p-4 container-box">
                <div class="row">
                    <div class="col-md-3">
                        <div class="custom-dropdown-container" data-input-name="treem_size">
                            <div class="custom-dropdown-trigger">
                                <span>Treem size</span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="custom-dropdown-menu">
                                <div class="custom-dropdown-options">
                                    <ul>
                                        <li><span>5" x 8"</span></li>
                                        <li><span>6" x 9"</span></li>
                                        <li><span>7" x 10"</span></li>
                                        <li><span>7" x 10.5"</span></li>
                                    </ul>
                                </div>
                            </div>
                            <input type="hidden" name="treem_size" value="{{ old('treem_size') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="custom-dropdown-container" data-input-name="page_count" data-autosave="true">
                            <div class="custom-dropdown-trigger">
                                <span>Page Count</span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="custom-dropdown-menu">
                                <div class="custom-dropdown-add-new">
                                    <input type="number" placeholder="e.g., 250" value="{{ old('page_count') }}">
                                </div>
                            </div>
                            <input type="hidden" name="page_count" value="{{ old('page_count') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="custom-dropdown-container" data-input-name="format">
                            <div class="custom-dropdown-trigger">
                                <span>Format</span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="custom-dropdown-menu">
                                <div class="custom-dropdown-options">
                                    <ul>
                                        <li><span>PDF</span></li>
                                        <li><span>PDF Print</span></li>
                                        <li><span>Word</span></li>
                                    </ul>
                                </div>
                            </div>
                            <input type="hidden" name="format" value="{{ old('format') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="custom-dropdown-container" data-input-name="bleed_file">
                            <div class="custom-dropdown-trigger">
                                <span>Bleed file</span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="custom-dropdown-menu">
                                <div class="custom-dropdown-options">
                                    <ul>
                                        <li><span>Yes</span></li>
                                        <li><span>No</span></li>
                                    </ul>
                                </div>
                            </div>
                            <input type="hidden" name="bleed_file" value="{{ old('bleed_file') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="custom-dropdown-container" data-input-name="category">
                            <div class="custom-dropdown-trigger">
                                <span>Category</span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="custom-dropdown-menu">
                                <div class="custom-dropdown-options">
                                    <ul>
                                        <li><span>Fiction</span></li>
                                        <li><span>Mystery & Thriller</span></li>
                                        <li><span>Fantasy</span></li>
                                        <li><span>Science Fiction</span></li>
                                        <li><span>Romance</span></li>
                                        <li><span>Biography & Memoir</span></li>
                                        <li><span>Self-Help & Personal Development</span></li>
                                    </ul>
                                </div>
                            </div>
                            <input type="hidden" name="category" value="{{ old('category') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="custom-dropdown-container" data-input-name="author" data-autosave="true">
                            <div class="custom-dropdown-trigger">
                                <span>Author name</span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="custom-dropdown-menu">
                                <div class="custom-dropdown-add-new">
                                    <input type="text" placeholder="Type..." value="{{ old('author') }}">
                                </div>
                            </div>
                            <input type="hidden" name="author" value="{{ old('author') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="custom-dropdown-container" data-input-name="chapters" data-multiselect="true">
                            <div class="custom-dropdown-trigger">
                                <span>Chapter</span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="custom-dropdown-menu">
                                <div class="custom-dropdown-options">
                                    <ul>
                                        {{-- <li><span>Chapter 1</span> <i class="fa-solid fa-times remove-option"></i>
                                        </li>
                                        <li><span>Chapter 2</span> <i class="fa-solid fa-times remove-option"></i></li>
                                        <li><span>Chapter 3</span> <i class="fa-solid fa-times remove-option"></i></li>
                                        --}}
                                    </ul>
                                </div>
                                <div class="custom-dropdown-add-new">
                                    <input type="text" placeholder="Type here" value="{{ old('chapters') }}">
                                    <button type="button">ADD</button>
                                </div>
                            </div>
                            <input type="hidden" name="chapters" value="{{ old('chapters') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="custom-dropdown-container" data-input-name="text_style">
                            <div class="custom-dropdown-trigger">
                                <span>Text Style</span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="custom-dropdown-menu">
                                <div class="custom-dropdown-options">
                                    <ul>
                                        <li><span>A</span></li>
                                        <li class="text-style-option-italic"><span style="font-style: italic;">I</span>
                                        </li>
                                        <li class="text-style-option-b"><span style="font-weight: bold;">B</span></li>
                                        <li><span style="text-decoration: underline;">U</span></li>

                                    </ul>
                                </div>
                            </div>
                            <input type="hidden" name="text_style" value="{{ old('text_style', 'A') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="custom-dropdown-container" data-input-name="font_size" data-dynamic-options="true">
                            <div class="custom-dropdown-trigger">
                                <span>Font Size</span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="custom-dropdown-menu">
                                <div class="custom-dropdown-options">
                                    <ul>
                                        <li><span>10</span></li>
                                        <li class="selected"><span>12</span></li>
                                        <li><span>14</span></li>
                                        <li><span>16</span></li>
                                        <li><span>18</span></li>
                                    </ul>
                                </div>
                                <div class="custom-dropdown-add-new font-size-add">
                                    <button type="button" class="stepper-btn stepper-decrement">-</button>
                                    <input type="number" class="font-size-input" value="{{ old('font_size', 12) }}"
                                        min="1">
                                    <button type="button" class="stepper-btn stepper-increment">+</button>
                                    <button type="button" class="add-font-btn">Add</button>
                                </div>
                            </div>
                            <input type="hidden" name="font_size" value="{{ old('font_size', 12) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="custom-dropdown-container" data-input-name="add_page_num">
                            <div class="custom-dropdown-trigger">
                                <span>Add Page Number</span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="custom-dropdown-menu">
                                <div class="custom-dropdown-options">
                                    <ul>
                                        <li><span>Yes</span></li>
                                        <li><span>No</span></li>
                                    </ul>
                                </div>
                            </div>
                            <input type="hidden" name="add_page_num" value="{{ old('add_page_num') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="custom-checkbox-button" data-name="book_intro">
                            <span>Book Intro</span>
                            <span class="checkbox-visual"><i class="fa-solid fa-check"></i></span>
                        </button>
                        <input type="hidden" name="book_intro" value="{{ old('book_intro', 'No') }}">
                    </div>

                    <div class="col-md-4">
                        <button type="button" class="custom-checkbox-button" data-name="copyright_page">
                            <span>Copyright Page</span>
                            <span class="checkbox-visual"><i class="fa-solid fa-check"></i></span>
                        </button>
                        <input type="hidden" name="copyright_page" value="{{ old('copyright_page', 'No') }}">
                    </div>

                    <div class="col-md-4">
                        <button type="button" class="custom-checkbox-button" data-name="table_of_contents">
                            <span>Table of Contents</span>
                            <span class="checkbox-visual"><i class="fa-solid fa-check"></i></span>
                        </button>
                        <input type="hidden" name="table_of_contents" value="{{ old('table_of_contents', 'No') }}">
                    </div>
                </div>
                <!-- <div class="d-flex justify-content-center">
        
                    <i class="mb-2 fa-solid fa-image fa-2x"></i><br />
                    Click to upload cover image
                    <input name="image" type="file" id="coverImage" accept="image/*" class="d-none" />
                    <img id="preview" class="image-preview" />
                </label>
                </div> -->


                <input name="title" type="text" class="form-control @error('title') is-invalid @enderror"
                    placeholder="Book Title" value="{{ old('title') }}" />
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <input name="second_title" type="text" class="form-control @error('second_title') is-invalid @enderror"
                    placeholder="Book Subtitle" value="{{ old('second_title') }}" />
                @error('second_title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3"
                    placeholder="Book description">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror


                <div class="my-5 text-center generate-btn d-flex justify-content-center">
                    <button class="button_Adduser">
                        GENERATE <i class="fa-solid fa-arrow-right fa-sm ms-2" style="color: #ffffff;"></i>
                    </button>
                </div>
            </div>








    </form>

    <script src="{{ asset('assets/dashboard/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Custom Dropdown Logic ---
            document.querySelectorAll('.custom-dropdown-container').forEach(setupDropdown);
            // --- Custom Checkbox Logic ---
            document.querySelectorAll('.custom-checkbox-button').forEach(setupCheckbox);

            // --- Restore dropdowns and checkboxes from old values ---
            document.querySelectorAll('.custom-dropdown-container').forEach(function (container) {
                const hiddenInput = container.querySelector('input[type="hidden"]');
                const optionsList = container.querySelector('.custom-dropdown-options ul');
                const triggerText = container.querySelector('.custom-dropdown-trigger span');
                const isMultiSelect = container.dataset.multiselect === 'true';
                const defaultTriggerText = triggerText ? triggerText.textContent : '';
                if (hiddenInput && optionsList && triggerText) {
                    const value = hiddenInput.value;
                    if (value) {
                        if (isMultiSelect) {
                            // Multi-select: select all matching options
                            const values = value.split(',');
                            let selectedCount = 0;
                            optionsList.querySelectorAll('li').forEach(function (li) {
                                const text = li.querySelector('span').textContent;
                                if (values.includes(text)) {
                                    li.classList.add('selected');
                                    selectedCount++;
                                } else {
                                    li.classList.remove('selected');
                                }
                            });
                            if (selectedCount === 0) {
                                triggerText.textContent = defaultTriggerText;
                            } else if (selectedCount === 1) {
                                triggerText.textContent = values[0];
                            } else {
                                triggerText.textContent = `${selectedCount} chapters selected`;
                            }
                        } else {
                            // Single select: select the matching option
                            let found = false;
                            optionsList.querySelectorAll('li').forEach(function (li) {
                                const text = li.querySelector('span').textContent;
                                if (text === value) {
                                    li.classList.add('selected');
                                    triggerText.textContent = text;
                                    found = true;
                                } else {
                                    li.classList.remove('selected');
                                }
                            });
                            if (!found) {
                                triggerText.textContent = value; // fallback for custom value
                            }
                        }
                    } else {
                        // No value, reset to default
                        optionsList.querySelectorAll('li').forEach(function (li) {
                            li.classList.remove('selected');
                        });
                        triggerText.textContent = defaultTriggerText;
                    }
                }
            });

            // --- Restore custom checkboxes from old values ---
            document.querySelectorAll('.custom-checkbox-button').forEach(function (button) {
                const inputName = button.dataset.name;
                const hiddenInput = document.querySelector(`input[name="${inputName}"]`);
                if (hiddenInput && hiddenInput.value === 'Yes') {
                    button.classList.add('checked');
                } else {
                    button.classList.remove('checked');
                }
            });

            function setupCheckbox(button) {
                const inputName = button.dataset.name;
                const hiddenInput = document.querySelector(`input[name="${inputName}"]`);

                button.addEventListener('click', () => {
                    const isChecked = button.classList.toggle('checked');
                    hiddenInput.value = isChecked ? 'Yes' : 'No';
                });
            }

            function setupDropdown(container) {
                const trigger = container.querySelector('.custom-dropdown-trigger');
                const optionsList = container.querySelector('.custom-dropdown-options ul');
                const triggerText = trigger.querySelector('span');
                const hiddenInput = container.querySelector('input[type="hidden"]');
                const addNewInput = container.querySelector('.custom-dropdown-add-new input');
                const addNewButton = container.querySelector('.custom-dropdown-add-new button');
                const defaultTriggerText = triggerText.textContent;

                const isMultiSelect = container.dataset.multiselect === 'true';
                const shouldAutosave = container.dataset.autosave === 'true';
                const hasRemoveIcon = isMultiSelect || container.dataset.dynamicOptions === 'true';

                // Set initial value from selected items
                if (isMultiSelect) {
                    updateMultiSelectDisplay();
                } else if (optionsList) {
                    const initialSelected = optionsList.querySelector('li.selected');
                    if (initialSelected) {
                        updateSelection(initialSelected.querySelector('span').textContent);
                    }
                }

                // Toggle dropdown
                trigger.addEventListener('click', (e) => {
                    e.stopPropagation();
                    closeAllDropdowns(container);
                    container.classList.toggle('open');
                });

                // Handle standard item selection
                if (optionsList) {
                    optionsList.addEventListener('click', (e) => {
                        const li = e.target.closest('li');
                        if (!li) return;

                        // Handle remove click
                        if (hasRemoveIcon && e.target.classList.contains('remove-option')) {
                            e.stopPropagation();
                            li.remove();
                            if (isMultiSelect) {
                                updateMultiSelectDisplay();
                            } else {
                                updateSelection(defaultTriggerText, true);
                            }
                            return;
                        }

                        // Handle selection
                        if (isMultiSelect) {
                            li.classList.toggle('selected');
                            updateMultiSelectDisplay();
                        } else {
                            optionsList.querySelectorAll('li').forEach(item => item.classList.remove(
                                'selected'));
                            li.classList.add('selected');
                            updateSelection(li.querySelector('span').textContent);
                            if (!isMultiSelect) {
                                container.classList.remove('open'); // Close dropdown after selection
                            }
                        }
                    });
                }

                // Handle "add new" functionality
                if (addNewInput) {
                    // Autosave fields (Author Name, Page Count)
                    if (shouldAutosave) {
                        const saveValue = () => {
                            const text = addNewInput.value.trim();
                            if (text) {
                                updateSelection(text);
                                container.classList.remove('open');
                            }
                        };
                        addNewInput.addEventListener('blur', saveValue);
                        addNewInput.addEventListener('keypress', function (e) {
                            if (e.key === 'Enter') {
                                e.preventDefault();
                                saveValue();
                            }
                        });
                    }

                    // Fields with an ADD button (Chapter)
                    if (addNewButton) {
                        // Specific logic for Font Size stepper
                        if (container.querySelector('.font-size-add')) {
                            const stepperInput = container.querySelector('.font-size-input');
                            const decrementBtn = container.querySelector('.stepper-decrement');
                            const incrementBtn = container.querySelector('.stepper-increment');
                            const addBtn = container.querySelector('.add-font-btn');

                            decrementBtn.addEventListener('click', () => stepperInput.stepDown());
                            incrementBtn.addEventListener('click', () => stepperInput.stepUp());

                            const addFontSize = () => {
                                const text = stepperInput.value;
                                if (text) addOption(text);
                            };

                            addBtn.addEventListener('click', addFontSize);

                        } else {
                            // Generic logic for other 'add new' fields
                            const addGenericOption = () => {
                                const text = addNewInput.value.trim();
                                if (text) addOption(text);
                                addNewInput.value = '';
                            };
                            addNewButton.addEventListener('click', addGenericOption);
                            addNewInput.addEventListener('keypress', function (e) {
                                if (e.key === 'Enter') {
                                    e.preventDefault();
                                    addGenericOption();
                                }
                            });
                        }
                    }
                }

                function updateSelection(text, isDefault = false) {
                    triggerText.textContent = text;
                    if (hiddenInput) {
                        hiddenInput.value = isDefault ? '' : text;
                    }
                }

                function updateMultiSelectDisplay() {
                    if (!optionsList) return;
                    const selectedItems = optionsList.querySelectorAll('li.selected');
                    const selectedValues = Array.from(selectedItems).map(li => li.querySelector('span')
                        .textContent);

                    if (selectedItems.length === 0) {
                        triggerText.textContent = defaultTriggerText;
                    } else if (selectedItems.length === 1) {
                        triggerText.textContent = selectedValues[0];
                    } else {
                        triggerText.textContent = `${selectedItems.length} chapters selected`;
                    }

                    if (hiddenInput) {
                        hiddenInput.value = selectedValues.join(',');
                    }
                }

                function addOption(text) {
                    if (!text) return;
                    // Prevent duplicates
                    const existingOptions = Array.from(optionsList.querySelectorAll('span')).map(s => s
                        .textContent);
                    if (existingOptions.includes(text)) {
                        return;
                    }

                    const newLi = document.createElement('li');
                    const content = hasRemoveIcon ?
                        `<span>${text}</span> <i class="fa-solid fa-times remove-option"></i>` :
                        `<span>${text}</span>`;
                    newLi.innerHTML = content;
                    optionsList.appendChild(newLi);

                    if (isMultiSelect) {
                        newLi.classList.add('selected');
                        updateMultiSelectDisplay();
                    } else {
                        // Select the newly added option
                        optionsList.querySelectorAll('li').forEach(item => item.classList.remove('selected'));
                        newLi.classList.add('selected');
                        updateSelection(text);
                    }
                }
            }

            function closeAllDropdowns(exceptThisOne = null) {
                document.querySelectorAll('.custom-dropdown-container').forEach(container => {
                    if (container !== exceptThisOne) {
                        container.classList.remove('open');
                    }
                });
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.custom-dropdown-container')) {
                    closeAllDropdowns();
                }
            });
        });
    </script>

    <script>
        function submitAndRedirect() {
            const form = document.querySelector('form');

            if (form) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'submit_type';
                hiddenInput.value = 'save_close';
                form.appendChild(hiddenInput);

                setTimeout(function () {
                    form.submit();
                }, 300);
            }
        }
    </script>
</body>

</html>