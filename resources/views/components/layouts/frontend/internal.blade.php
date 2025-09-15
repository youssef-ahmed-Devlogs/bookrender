<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Book Render</title>
    <meta name="description"
        content="Read our Privacy Policy to understand how Book Render collects, uses, and protects your data. Your privacy is our top priority." />
    <meta name="keywords"
        content="privacy policy, user data protection, AI book generator, Book Render data security" />
    <meta name="robots" content="index, follow" />
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #ffffff;
            margin: 0;
            padding: 0;
            color: #333;
            overflow-x: hidden;
            min-height: 100vh;
            position: relative;
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            background-color: #167BF1;
            color: white;
            padding: 10px 16px;
            border-radius: 10px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transition: background 0.3s, transform 0.3s;
        }

        .back-button:hover {
            background-color: #0e5fc7;
            transform: scale(1.05);
        }

        .container {
            max-width: 960px;
            margin: 120px auto 40px;
            background: #ffffff;
            padding: 50px;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            font-size: 42px;
            color: #2c3e50;
            margin-bottom: 10px;
            position: relative;
        }

        h1::after {
            content: '';
            width: 60px;
            height: 4px;
            background: #007BFF;
            display: block;
            margin: 10px auto 0;
            border-radius: 2px;
        }

        h2 {
            font-size: 26px;
            color: #007BFF;
            margin-top: 40px;
            margin-bottom: 10px;
        }

        p,
        li {
            font-size: 18px;
            line-height: 1.8;
            margin: 15px 0;
        }

        ul {
            margin-left: 20px;
            list-style-type: disc;
        }

        .highlight {
            color: #007BFF;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            font-style: italic;
            margin-top: 60px;
            color: #555;
            font-size: 16px;
        }

        .info-box {
            background: #167BF1;
            color: white;
            padding: 20px;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 500;
            margin: 30px 0;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .info-box:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        }

        .card {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 25px 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .btn {
            padding: 12px 24px;
            border: none;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s ease-in-out;
            display: inline-block
        }

        .btn:hover {
            opacity: 0.7
        }

        .btn-primary {
            color: white;
            background: #167BF1;
        }
    </style>

    @stack('styles')
</head>

<body>
    <a href="/" class="back-button">
        <span>&larr;</span> Back
    </a>

    <main>
        {{ $slot }}
    </main>

    @stack('scripts')
</body>

</html>