<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            @if(null !== Auth::user())
                @if (Auth::user()->hasAnyRole(['auth_editor']))
                    <li class="list-group-item {{ (Request::is('*profile') ? 'active' : '') }}">
                        <a href="{{ url('/authprofile/'.Auth::user()->id.'/edit') }}">
                            <i class="fa fa-user fa-fw"></i>修改个人信息
                        </a>
                    </li>

                    <li class="list-group-item {{ (Request::is('*user') ? 'active' : '') }}">
                        <a href="{{ url('admin/user/'.Auth::user()->id.'/editpw') }}">
                            <i class="fa fa-user fa-fw"></i>修改密码
                        </a>
                    </li>
                @else
                    <li class="list-group-item {{ (Request::is('*profile') ? 'active' : '') }}">
                        <a href="{{ url('admin/profile/'.Auth::user()->id.'/editprofile') }}">
                            <i class="fa fa-user fa-fw"></i>修改个人信息
                        </a>
                    </li>

                    <li class="list-group-item {{ (Request::is('*user') ? 'active' : '') }}">
                        <a href="{{ url('admin/user/'.Auth::user()->id.'/editpw') }}">
                            <i class="fa fa-user fa-fw"></i>修改密码
                        </a>
                    </li>
                @endif
            @endif
        </ul>
    </div>
</div>