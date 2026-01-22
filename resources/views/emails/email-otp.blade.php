<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Email Verification</title>
</head>

<body style="font-family: Arial, sans-serif; background:#f6f8fb; padding:20px">

    <div style="max-width:520px;margin:auto;background:#ffffff;border-radius:8px;padding:24px">

        <h2 style="color:#0b1c3d;margin-bottom:10px">
            SYMCARD 2026
        </h2>

        <p>Hello,</p>

        <p>
            You requested an email verification code for
            <strong>{{ $email }}</strong>.
        </p>

        <p style="margin-top:20px">Your verification code is:</p>

        <div style="
            font-size:32px;
            font-weight:bold;
            letter-spacing:6px;
            text-align:center;
            color:#e53935;
            margin:20px 0
        ">
            {{ $otp }}
        </div>

        <p style="color:#555">
            This code will expire in <strong>5 minutes</strong>.
            Please do not share this code with anyone.
        </p>

        <hr style="margin:30px 0">

        <p style="font-size:12px;color:#999">
            Department of Cardiology & Vascular Medicine<br>
            Faculty of Medicine, Universitas Andalas<br>
            SYMCARD UNAND 2026
        </p>

    </div>

</body>

</html>
