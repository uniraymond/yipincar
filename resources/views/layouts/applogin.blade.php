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


</head>
<body id="app-layout">
<div wrapper>
    <nav class="navbar navbar-dark bg-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggler hidden-sm-up pull-sm-right collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    @if( (null !== Auth::user()) && Auth::user()->hasAnyRole(['super_admin', 'admin']))
                        <span class="icon-bar">{{link_to('admin/user', 'Users')}}</span>
                        {{--<span class="icon-bar">{{link_to('admin/category', 'Categories')}}</span>--}}
                        {{--                        <span class="icon-bar">{{link_to('admin/articletypes', 'Article Types')}}</span>--}}
                        <span class="icon-bar">{{link_to('admin/tag', 'Article Tags')}}</span>
                    @endif
                    @if((null !== Auth::user()) && Auth::user()->hasAnyRole(['super_admin', 'admin', 'editor', 'main_editor', 'chefeditor', 'auth_editor', 'adv_editor']))
                        <span class="icon-bar">{{link_to('admin/article', 'Articles')}}</span>
                    @endif
                </button>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
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
                    @endif
                </ul>

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
            </div>
        </div>
    </nav>

    <div id="logo page-wrapper" class="home_logo">
        <!-- Branding Image -->
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="/src/images/yipinlogo.png" id="logoimage"/>
        </a>
    </div>
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
