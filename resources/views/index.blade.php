<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name = "csrf-token" content = "{{ csrf_token() }}">

        <title> Appointment </title>
        @include('templates.layouts.css')
    </head>

    <body>
        <div id="app">
            
            <main-app 
                datakey = {{config('services.google.recaptcha.site_key')}}>
            </main-app>

        </div>
        @include('templates.layouts.js')
    </body>
</html>

