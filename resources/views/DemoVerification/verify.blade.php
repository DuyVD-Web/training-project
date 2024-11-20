<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verify Email</title>
    @vite('resources/css/app.css')
</head>
<body>
<div class="mx-10 my-3">
    <h1 class="mb-4">Please verify email through the email we've sent you.</h1>
    <p class="mb-2">Didn't get the email?</p>
    <form action="{{route('demo.verification.send')}}" method="post">
        @csrf
        <button type="submit" class="bg-blue-600 rounded text-white px-2 py-1">Send again</button>
    </form>
</div>
</body>
</html>
