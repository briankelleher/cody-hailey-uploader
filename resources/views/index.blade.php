<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hailey & Cody</title>
    @vite('resources/scss/main.scss')
</head>
<body>
    <div id="app">
        <App></App>
        <div class="container py-5">
            <small class="text-center">If you have any issues, you can email your photos to <a href="mailto:{{ $backup_email }}?subject=Wedding+Photos">{{ $backup_email }}</a></small>
        </div>
        
    </div>
    @vite('resources/js/app.js')
</body>
</html>
