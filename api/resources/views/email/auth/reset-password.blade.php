<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="body">
            <h3 class="body__title">Hello!</h3>
            <p class="body__text">You are receiving this email because we received a password reset request for your
                account.</p>
            <br />
            <div class="body__button-container">
                <a href="{{ $url }}">Reset Password</a>
            </div>
            <br />
            <p class="body__text">This password reset link will expire in 60 minutes.</p>
            <p class="body__text">If you did not request a password reset, no further action is required.</p>
            <br />
            <p class="body__text">Regards,</p>
            <p class="body__text">LMS</p>
        </div>
    </div>
</body>

<style>
    body {
        font-family: 'Inter', sans-serif;
        background: #EDF2F7;
    }

    .container {
        height: 100vh;
        widows: 100vw;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 24px;
    }

    .body {
        max-width: 600px;
        background: #fff;
        padding: 24px;
    }

    .body__title {
        font-weight: bold;
        color: #2D3748;
        margin-bottom: 1.5rem;
    }

    .body__text {
        color: #718096;
    }

    .body__button-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    a {
        color: #fff;
        background: #2D3748;
        padding: 0.5rem 1rem;
        cursor: pointer;
        border-radius: 0.3rem;
        text-decoration: none;
    }
</style>

</html>
