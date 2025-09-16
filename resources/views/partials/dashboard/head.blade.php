<meta name="csrf-token" content="{{ csrf_token() }}">

<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">

<title>{{ $settings->title }}</title>

<link rel="stylesheet" href="{{ asset('assets/dashboard/css/all.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/bootstrap.min.css') }}" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/style.css') }}" />

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

    .sidebar {
        min-height: 100vh;
    }

    .sidebar a.active p {
        color: #0c9bf3 !important;
    }

    .sidebar a.active img {
        filter: brightness(0) saturate(100%) invert(48%) sepia(79%) saturate(2476%) hue-rotate(176deg) brightness(98%) contrast(119%);
    }

    .sidebar a img {
        filter: brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%);
    }
</style>

<style>
    .main-search {
        width: 400px !important;
        height: 80% !important;
    }
</style>

@stack('styles')