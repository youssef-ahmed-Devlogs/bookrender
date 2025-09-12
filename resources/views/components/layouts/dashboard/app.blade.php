@php
    $settings = \App\Models\Setting::first();
@endphp

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    @include('partials.dashboard.head')
</head>

<body style="font-family: {{ $settings->fontfamily }}; background-color: {{ $settings->body }}">
    <div class="container-fluid">
        <div class="row">
            @include('partials.dashboard.sidebar')

            <main class="col-md-11">
                @include('partials.common.header')

                {{ $slot }}
            </main>
        </div>
    </div>

    @include('partials.dashboard.scripts')
</body>

</html>