<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>一品汽车</title>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset("/src/assets/stylesheets/styles.css") }}" />
    <link rel="stylesheet" href="{{ asset("/src/css/self.css") }}" />

    {{--<link rel="stylesheet" href="/src/css/main.css" >--}}
    @show
</head>
<body>
    @include('layouts.topNavigation')
    <!-- /.navbar-header -->

    @yield('content')
    @include('layouts.footer')
    <script src="{{ asset("/src/assets/scripts/frontend.js") }}" type="text/javascript"></script>

    {{--<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular.min.js"></script>--}}
</body>
</html>
