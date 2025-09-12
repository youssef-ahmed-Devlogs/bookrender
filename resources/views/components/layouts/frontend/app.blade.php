@php
    $settings = \App\Models\Setting::first();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Book</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/style.css') }}">

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

        .icon-main h4 {
            font-family: "Poppins", sans-serif;
            font-weight: 500;
            font-size: 24px;
            line-height: 100%;
            letter-spacing: 0%;
            vertical-align: middle;
            color: #17253F;
            width: 100%;
        }

        .home_info h1 {
            font-family: "Poppins", sans-serif;
            font-weight: 500;
            font-size: 60px;
            line-height: 68px;
            letter-spacing: 0%;
            width: 90%;
            color: #000000;
        }

        .slider-track {
            transition: transform 0.5s ease;
        }

        .scroll-top {
            background-color: #008cff;
            color: white;
            border: none;
            padding: 10px;
            width: 3%;
            border-radius: 50%;
            cursor: pointer;
            position: absolute;
            right: 20px;
            bottom: 10px;
        }

        @media (max-width: 768px) {
            .button-main {
                background: linear-gradient(to right, #1876f1, #00bef5);
                color: #fff;
                font-size: 10px;
                font-weight: bold;
                padding: 10px 20px;
                border: none;
                border-radius: 30px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                cursor: pointer;
            }

            .icon-main h4 {
                font-family: "Poppins", sans-serif;
                font-weight: 500;
                font-size: 14px;
                line-height: 100%;
                letter-spacing: 0%;
                vertical-align: middle;
                color: #17253F;
            }

            .home_info h1 {
                font-family: "Poppins", sans-serif;
                font-weight: 500;
                font-size: 40px;
                line-height: 50px;
                letter-spacing: 0%;
                width: 100%;
                color: #000000;
            }

            .home {
                height: 60vh;
            }

            .img-book h2,
            .slid h3,
            .info-home h3 {
                font-size: 20px;
                font-weight: 600;
            }

            .slider-track .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .icon-home {
                position: absolute;
                right: 6%;
            }

            .scroll-top {
                background-color: #008cff;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 50%;
                cursor: pointer;
                position: absolute;
                right: 30px;
                bottom: 100px;

            }

            .scroll-top i {
                transform: rotate(-30deg);
            }

        }
    </style>
</head>


<body style="font-family: {{ $settings->fontfamily }}; background-color: {{ $settings->body }}">

    {{ $slot }}

    @include('partials.frontend.footer')

    <script src="{{ asset('assets/frontend/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/mian.js') }}"></script>

    <script>
        $(document).ready(function () {
            let currentIndex = 0;

            function getCardWidth() {
                return $('.slider-track .col-md-6').outerWidth(true);
            }

            function getVisibleCount() {
                return window.innerWidth <= 768 ? 1 : 2;
            }

            function getMaxIndex() {
                const total = $('.slider-track .col-md-6').length;
                return total - getVisibleCount();
            }

            function updateSlider() {
                const cardWidth = getCardWidth();
                $('.slider-track').css('transform', 'translateX(' + (-cardWidth * currentIndex) + 'px)');
            }

            $('#next').click(function () {
                if (currentIndex < getMaxIndex()) {
                    currentIndex++;
                    updateSlider();
                }
            });

            $('#prev').click(function () {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateSlider();
                }
            });

            $(window).resize(function () {
                updateSlider();
            });
        });

        document.getElementById("scrollTopBtn").addEventListener("click", function () {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    </script>
</body>

</html>