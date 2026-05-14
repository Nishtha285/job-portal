<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Job Notification Email</title>
</head>
<body>
    <h1>Hello, {{ $mail_data['employer']->name }}</h1>
    <p>Job Title: {{ $mail_data['job']->title }}</p>

    <p>Employee Details:</p>
    <p>Name: {{ $mail_data['user']->name }}</p>
    <p>Email: {{ $mail_data['user']->email }}</p>
    <p>Mobile No: {{ $mail_data['user']->mobile }}</p>
</body>
</html>