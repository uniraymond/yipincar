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
    <link rel="stylesheet" href="{{ asset("src/assets/stylesheets/styles.css") }}" />
    <link rel="stylesheet" href="{{ asset("src/css/self.css") }}" />

    {{--<link rel="stylesheet" href="/src/css/main.css" >--}}
    @show
</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-small-collapse">
            <span class="sr-only">点击显示</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{ url ('/admin/article') }}"><img src="{{ url('/src/images/toplogo.png') }}" /></a>
    </div>
    <div class="hidden-sm-up navbar-toggleable-sm navbar-small-collapse collapse" aria-expanded="false" style="height: 0px;">
        <ul>
            @if((null !== Auth::user()) && Auth::user()->hasAnyRole(['super_admin', 'admin', 'editor', 'main_editor', 'chef_editor', 'auth_editor']))
                <li class="list-group-item">{{link_to('admin/article', '文章')}}</li>
                <li class="list-group-item">{{link_to('admin/advsetting/list', '广告设置')}}</li>
                <li class="list-group-item">{{link_to('admin/tag', '标签')}}</li>
                <li class="list-group-item">{{link_to('admin/statistics', '统计')}}</li>
            @endif
                @if( (null !== Auth::user()) && Auth::user()->hasAnyRole(['super_admin', 'admin']))
                    <li class="list-group-item">{{link_to('admin/user', '用户')}}</li>
                @endif
        </ul>
    </div>
    <div class="collapse navbar-collapse navbar-toggleable-sm" id="app-navbar-collapse">
        <!-- Left Side Of Navbar -->
        <ul class="nav navbar-nav">
            @if((null !== Auth::user()) && Auth::user()->hasAnyRole(['super_admin', 'admin', 'editor', 'main_editor', 'chef_editor', 'auth_editor']))
                <li>{{link_to('admin/article', '文章')}}</li>
                <li>{{link_to('admin/advsetting/list', '广告设置')}}</li>
                <li>{{link_to('admin/tag', '标签')}}</li>
                <li>{{link_to('admin/statistics', '统计')}}</li>
            @endif
            @if( (null !== Auth::user()) && Auth::user()->hasAnyRole(['super_admin', 'admin']))
                <li>{{link_to('admin/user', '用户')}}</li>
            @endif
        </ul>
    </div>
    <!-- /.navbar-static-side -->

    <ul class="nav navbar-top-links navbar-right">
        <li class="dropdown">
        @if ((null == Auth::user()) && Auth::guest())
            <a class="dropdown-toggle" data-toggle="dropdown" href="{{ url('/login') }}">
                <i class="fa fa-user fa-fw"></i>登陆<i class="fa fa-caret-down"></i>
            </a>
        @else
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i> @if (null !== Auth::user()) {{ Auth::user()->name }} @endif <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li>
                    <a href="{{ url('admin/profile/'.Auth::user()->id.'/editprofile') }}"><i class="fa fa-user fa-fw"></i>编辑</a>
                </li>
                <li class="divider"></li>
                <li><a href="{{ url('/logout') }}"><i class="fa fa-sign-out fa-fw"></i>退出</a>
                </li>
            </ul>
        @endif
        </li>
    </ul>
    <!-- /.navbar-static-side -->
</nav>
    <!-- /.navbar-header -->

    @yield('content')

    <script src="{{ asset("src/js/jQuery.min.2.2.4.js") }}" type="text/javascript"></script>
    <script src="{{ asset("src/assets/scripts/frontend.js") }}" type="text/javascript"></script>
</body>
</html>
