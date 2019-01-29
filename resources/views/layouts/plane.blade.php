<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
    <!--<![endif]-->
    <head>
        <meta charset="utf-8"/>
        <title>SB Admin 2 - Bootstrap Admin Theme</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <meta content="" name="description"/>
        <meta content="" name="author"/>
        <title>SB Admin 2 - Bootstrap Admin Theme</title>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">


        <link rel="stylesheet" href="{{ asset("css/styles.css") }}" />
    </head>
    <body>



        @if(Auth::check()) 
        
        <script>
            var userName = '{{Auth::user()->email}}';
            
        </script>
        
        
        @yield('body')
        @else
        <script>
            window.location.href = '/login'
        </script>
        @endif


        <script src="{{ asset("js/jquery-3.2.1.min.js") }}"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="{{ asset("js/bootstrap.min.js") }}" ></script>
        <script src="{{ asset("js/app.js") }}" type="text/javascript"></script>
    </body>
</html>