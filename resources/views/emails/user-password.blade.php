<!DOCTYPE html>
<html>
<head>
    <title>Your Account Details</title>
</head>
<body>
    <h1>Hello, {{ $name }}!</h1>

    <p>Thank you for registering with us. Below are your account details:</p>

    <p><strong>Email:</strong> {{ $email }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>

    <p>Please make sure to change your password after logging in.</p>

    <p>Best regards,</p>
    <p>Your Company</p>
</body>
</html>
