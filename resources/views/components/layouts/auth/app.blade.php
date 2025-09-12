<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Book</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/auth/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/auth/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/auth/css/style.css') }}">

    <style>
        .paragh::after {
            content: "";
            width: 25%;
            height: 6%;
            background-color: #949494;
            display: block;
            position: absolute;
            left: 0;
            top: 50%;
        }

        .paragh::before {
            content: "";
            width: 25%;
            height: 6%;
            background-color: #949494;
            display: block;
            position: absolute;
            right: 0;
            top: 50%;
        }

        .checkbox-group label {
            font-family: "Inter", serif;
            font-weight: 500;
            font-size: 12px;
            line-height: 100%;
            letter-spacing: 0%;

        }
    </style>

    @stack('styles')
</head>


<body>
    {{ $slot }}

    <script src="{{ asset('assets/auth/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        document
            .querySelectorAll('.position-relative input[type="password"]')
            .forEach(function (passwordInput) {
                var eyeIcon = passwordInput.parentElement.querySelector(".fa-eye");
                if (eyeIcon) {
                    eyeIcon.addEventListener("click", function () {
                        if (passwordInput.type === "password") {
                            passwordInput.type = "text";
                            eyeIcon.classList.remove("fa-eye");
                            eyeIcon.classList.add("fa-eye-slash");
                        } else {
                            passwordInput.type = "password";
                            eyeIcon.classList.remove("fa-eye-slash");
                            eyeIcon.classList.add("fa-eye");
                        }
                    });
                }
            });
    </script>


    <script>
        const emailInput = document.getElementById("email");

        emailInput.addEventListener('blur', validateEmail);

        function validateEmail(e) {
            const emailInput = e.target;
            const errorSpan = document.getElementById("email-error");
            const email = emailInput.value;

            if (/\s/.test(email)) {
                errorSpan.textContent = "Email must not contain spaces.";
                e.preventDefault();
                return false;
            }

            if (/^@/.test(email)) {
                errorSpan.textContent = "Email must have text before @";
                e.preventDefault();
                return false;
            }

            const validEmailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!validEmailPattern.test(email)) {
                errorSpan.textContent = "Please enter a valid email address.";
                e.preventDefault();
                return false;
            }

            errorSpan.textContent = "";
            return true;
        }
    </script>

    @stack('scripts')
</body>

</html>