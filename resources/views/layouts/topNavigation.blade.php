<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="position: fixed; width:100%; margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-small-collapse">
            <span class="sr-only">点击显示</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{ url ('/') }}"><img src="{{ url('/src/images/toplogo.png') }}" /></a>
    </div>



    <div class="hidden-sm-up navbar-toggleable-sm navbar-small-collapse collapse" aria-expanded="false" style="height: 0px;">
        <ul>
            <li>
                {{--<a href="{{ url('admin/profile/'.Auth::user()->id) }}">--}}
                    <i class="fa fa-user fa-fw"></i>我的信息
                {{--</a>--}}
            </li>
            @if((null !== Auth::user()) && Auth::user()->hasAnyRole(['super_admin', 'admin', 'editor', 'main_editor', 'chef_editor', 'adv_editor']))
                <li class="list-group-item">{{link_to('admin/tag', '设置')}}</li>
                <li class="list-group-item">{{link_to('admin/article', '内容管理')}}</li>
                @if( (null !== Auth::user()) && Auth::user()->hasAnyRole(['super_admin', 'admin']))
                    <li class="list-group-item">{{link_to('admin/user/authEditorList', '会员管理')}}</li>
                @endif
                @if((null !== Auth::user()) && Auth::user()->hasAnyRole(['super_admin', 'admin', 'main_editor', 'chef_editor', 'adv_editor']))
                    <li class="list-group-item">{{link_to('admin/statistics', '数据统计')}}</li>
                @endif
            @endif
        </ul>
    </div>
    <div class="collapse navbar-collapse navbar-toggleable-sm" id="app-navbar-collapse">
        <!-- Left Side Of Navbar -->
        <ul class="nav navbar-nav">
            @if ((null == Auth::user()) && Auth::guest())
                {{--<li>--}}
                    {{--<a href="{{ url('admin/profile/' . Auth::user()->id) }}" >--}}
                        {{--<i class="fa fa-user fa-fw"></i>我的信息--}}
                    {{--</a>--}}
                {{--</li>--}}
            @elseif ((null != Auth::user()) && Auth::user()->hasAnyRole(['auth_editor']))
                <li>
                    @if (isset($profile) && $profile != null)
                        <a href="{{  url('authshow') }}" >
                    @else
                        <a href="{{  url('authprofile/create') }}" >
                    @endif
                        <i class="fa fa-user fa-fw"></i>我的信息
                    </a>
                </li>
            @endif
            @if((null != Auth::user()) && Auth::user()->hasAnyRole(['super_admin', 'admin', 'editor', 'main_editor', 'chef_editor', 'adv_editor']))
                <li>{{link_to('admin/tag', '设置')}}</li>
                <li>{{link_to('admin/article', '内容管理')}}</li>
                @if( (null != Auth::user()) && Auth::user()->hasAnyRole(['super_admin', 'admin']))
                    <li>{{link_to('admin/user/authEditorList', '会员管理')}}</li>
                @endif
                @if((null != Auth::user()) && Auth::user()->hasAnyRole(['super_admin', 'admin', 'main_editor', 'chef_editor', 'adv_editor']))
                    <li>{{link_to('admin/statistics', '数据统计')}}</li>
                @endif
            @elseif((null != Auth::user()) && Auth::user()->hasAnyRole(['auth_editor']) && Auth::user()->status_id == 3)
                <li>{{link_to('admin/article', '内容管理')}}</li>
            @endif
        </ul>
    </div>
    <!-- /.navbar-static-side -->

    <ul class="nav navbar-top-links navbar-right">
        <li class="dropdown">
            @if ((null == Auth::user()) && Auth::guest())
                {{--<a class="dropdown-toggle" data-toggle="dropdown" href="{{ url('/login') }}">--}}
                    {{--<i class="fa fa-user fa-fw"></i>登陆<i class="fa fa-caret-down"></i>--}}
                {{--</a>--}}
            @else
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i> @if (null !== Auth::user()) {{ isset(Auth::user()->profiles->name) ? Auth::user()->profiles->name : Auth::user()->name }} @endif <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    {{--<li>--}}
                        {{--<a href="{{ url('admin/profile/'.Auth::user()->id) }}">--}}
                            {{--<i class="fa fa-user fa-fw"></i>我的信息--}}
                        {{--</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="{{ url('admin/profile/'.Auth::user()->id.'/editprofile') }}"><i class="fa fa-user fa-fw"></i>编辑</a>--}}
                    {{--</li>--}}
                    {{--<li class="divider"></li>--}}
                    <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out fa-fw"></i>退出</a>
{{--                    <li><a href="{{ url('/logout') }}"><i class="fa fa-sign-out fa-fw"></i>退出</a>--}}
                    </li>
                </ul>
            @endif
        </li>
    </ul>
    <!-- /.navbar-static-side -->
</nav>