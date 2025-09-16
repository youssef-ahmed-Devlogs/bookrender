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
        <h1>Your Book is complete!</h1>

        @php
            $generatedContent = session('generated_content', []);
            $specialSections = ['Book Introduction', 'Copyright Page', 'Table of Contents'];
        @endphp

        <div class="row justify-content-center">
            @php $chapterNumber = 1; @endphp
            @foreach ($generatedContent as $index => $chapter)
                @if (!in_array($chapter['title'], $specialSections))
                    <div class="mb-5 col-lg-6">
                        <div class="p-4 bg-white rounded-4" style='height: 80%;'>
                            <h4 class="mb-3 text-center d-flex align-items-center justify-content-center">
                                Chapter {{ $chapterNumber }}
                                <i class="fa-solid fa-file-word ms-2" style="color: #2E73B8;"></i>
                            </h4>
                            <h5 style='text-align:center'> <strong>{{ $chapter['title'] }}</strong></h5>
                            <p>{{ $chapter['content'] }}</p>
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

                    @php $chapterNumber++; @endphp
                @endif
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