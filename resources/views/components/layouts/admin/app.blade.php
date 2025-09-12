@php
    $settings = \App\Models\Setting::first();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CreateBook</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/style.css') }}">

    <script defer src="{{ asset('assets/admin/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{asset('assets/admin/js/chart.js')}}"></script>
    <style>
        h1 {
            font-size:
                {{ $settings->font_h1 }}
            ;
            color:
                {{ $settings->heading }}
            ;
        }

        h2 {
            font-size:
                {{ $settings->font_h2 }}
            ;
        }

        h3 {
            font-size:
                {{ $settings->font_h3 }}
            ;
        }

        h4 {
            font-size:
                {{ $settings->font_h4 }}
            ;
        }

        h5 {
            font-size:
                {{ $settings->font_h5 }}
            ;

        }

        p {
            font-size:
                {{ $settings->font_paragraph }}
            ;
            color:
                {{ $settings->para }}
            ;
        }

        button {
            color:
                {{ $settings->button }}
            ;
        }
    </style>

    @stack('styles')
</head>

<body style="font-family: {{ $settings->fontfamily }}; background-color: {{ $settings->body }}">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @include('partials.admin.sidebar')

            <!-- Main Content ms-sm-auto px-md-4 -->
            <main class="col-md-11">

                @include('partials.common.header')

                {{ $slot }}

            </main>
        </div>
    </div>

    @include('partials.admin.scripts')
</body>

</html>