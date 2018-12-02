<!DOCTYPE html>
<html>
<head>
    <title>Password Reset Mail</title>
</head>

<body>
<h2>Hello {{$user['name']}}</h2>
<br/>
You or someone else requested to reset your password. If you have not requested it please ignore this email. Otherwise you can reset your password here:
<br/>
<a href="{{url('https://nknx.org/newpassword/'.$user->passwordReset->token)}}">reset your password</a>
</body>

</html>