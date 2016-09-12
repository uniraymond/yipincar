<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">--}}
    {{--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">--}}

    @section('style')
            <!-- Styles -->
    <link rel="stylesheet" href="/src/css/bootstrap.min.css">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

            <!-- Custom CSS -->
    <link href="/src/css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="/src/css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="/src/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="/src/css/main.css" >
    @show
</head>
<body id="app-layout">
<div id="wrapper-full">
    <nav class="navbar navbar-dark bg-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <!-- Collapsed Hamburger -->

                <!-- Collapsed Hamburger -->
                <button class="navbar-toggler hidden-sm-up pull-sm-right" type="button" data-toggle="collapse" data-target=".navbar-small-collapse">
                    ☰
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{--<img src="/src/images/yipin.jpeg" />--}}
                    一品汽车
                </a>
            </div>

            <div class="navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    @if( (null !== Auth::user()) && Auth::user()->hasAnyRole(['super_admin', 'admin']))
                        <li>{{link_to('admin/user', '用户')}}</li>
                        {{--                        <li>{{link_to('admin/category', '栏目')}}</li>--}}
                        {{--                        <li>{{link_to('admin/articletypes', '类型')}}</li>--}}
                    @endif
                    @if((null !== Auth::user()) && Auth::user()->hasAnyRole(['super_admin', 'admin', 'editor', 'main_editor', 'chef_editor', 'auth_editor', 'adv_editor']))
                        <li>{{link_to('admin/tag', '标签')}}</li>
                        <li>{{link_to('admin/article', '文章')}}</li>
                        <li>{{link_to('admin/advsetting/list', '广告设置')}}</li>
                    @endif
                </ul><div class="collapse navbar-collapse navbar-toggleable-sm" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        @if( (null !== Auth::user()) && Auth::user()->hasAnyRole(['super_admin', 'admin']))
                            <li>{{link_to('admin/user', '用户')}}</li>
                        @endif
                        @if((null !== Auth::user()) && Auth::user()->hasAnyRole(['super_admin', 'admin', 'editor', 'main_editor', 'chef_editor', 'auth_editor', 'adv_editor']))
                            <li>{{link_to('admin/tag', '标签')}}</li>
                            <li>{{link_to('admin/article', '文章')}}</li>
                            <li>{{link_to('admin/advsetting/list', '广告设置')}}</li>
                        @endif
                    </ul>

                </div>

                <!-- Right Side Of Navbar -->


                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if ((null == Auth::user()) && Auth::guest())
                        <li><a href="{{ url('/login') }}">登陆</a></li>
                        {{--                        <li><a href="{{ url('/register') }}">注册</a></li>--}}
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                @if (null !== Auth::user()) {{ Auth::user()->name }} @endif <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>退出</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div><!-- Right Side Of Navbar -->
            <div id="top_user_login_link">
                <ul class="nav navbar-nav navbar-right pull-xs-right user_button">
                    <!-- Authentication Links -->
                    @if ((null == Auth::user()) && Auth::guest())
                        <li><a href="{{ url('/login') }}">登陆</a></li>
                        {{--                        <li><a href="{{ url('/register') }}">注册</a></li>--}}
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                @if (null !== Auth::user()) {{ Auth::user()->name }} @endif <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('admin/profile/'.Auth::user()->id.'/editprofile') }}"><i class="fa fa-btn fa-sign-out"></i>编辑</a></li>
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>退出</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')
</div>
@section('script')

    <script src="/src/js/jQuery.min.2.2.4.js"></script>
    <script src="/src/js/bootstrap.min.js"></script>
    <script src="/src/js/angular.min.js"></script>

    <script src="/src/js/plugins/morris/raphael.min.js"></script>
    <script src="/src/js/plugins/morris/morris.min.js"></script>
    <script src="/src/js/plugins/morris/morris-data.js"></script>
    {{--<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.29/angular.min.js"></script>--}}
@show
{{--<script src="/src/js/resourceApp.js"></script>--}}
</body>
</html>
