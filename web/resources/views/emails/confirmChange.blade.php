<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email change confirmation</title>
</head>
<body>
<h1>Your email has been changed...</h1>

<p>
    We just need you to <a href='{{ url("verander/bevestig/{$user->token}") }}'>confirm this email address</a> real quick!
</p>
</body>
</html>