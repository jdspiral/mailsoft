<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Compressed CSS -->
    <link  rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/4.3.2/css/foundation.min.css" rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/foundation/6.1.2/foundation.min.css">


    <title>Mailsoft - @yield('title')</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    {{--<!-- Styles -->--}}
    {{--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">--}}

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <!-- Compressed JavaScript -->
    <script src="https://cdn.jsdelivr.net/foundation/6.1.2/foundation.min.js"></script>
    <link href="{{ asset('css/app.css') }}" media="all" rel="stylesheet" type="text/css" />
</head>
<body>
@section('nav')
    <div class="title-bar" data-responsive-toggle="main-menu" data-hide-for="medium">
        <button class="menu-icon" type="button" data-toggle></button>
        <div class="title-bar-title">Menu</div>
    </div>

    <div class="top-bar" id="main-menu">
        <div class="row">
        <div class="top-bar-left">
            <ul class="dropdown menu" data-dropdown-menu>
                <li class="menu-text">Site Title</li>
            </ul>
        </div>
        <div class="top-bar-right">
            <ul class="menu" data-responsive-menu="drilldown medium-dropdown">
                <li><a href="login">Login</a></li>
                <li><a href="#">Three</a></li>
            </ul>
        </div>
    </div></div>
    @show

<div class="row">
    @yield('content')
    <script>
        $(document).foundation();
    </script>
</div>
</body>
</html>