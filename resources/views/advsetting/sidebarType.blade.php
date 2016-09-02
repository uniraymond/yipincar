<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li class="list-group-item"><a href="{{ url('admin/advsetting/list') }}"><i class="fa fa-fw fa-desktop"></i>All</a></li>
            <li >
                <a href="#"><i class="fa fa-fw fa-desktop"></i> 展开类型<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    @foreach($types as $type)
                        <li class="list-group-item {{ (Request::is('*type/'.$type->id) ? 'active' : '') }}">
                            <a href="{{ url('admin/advsetting/type/'.$type->id) }}"><i class="fa fa-files-o fa-fw"></i> {{ $type->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>

            <li >
                <a href="#"><i class="fa fa-fw fa-desktop"></i> 展开位置<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    @foreach($positions as $position)
                        <li class="list-group-item {{ (Request::is('*position/'.$position->id) ? 'active' : '') }}">
                            <a href="{{ url('admin/advsetting/position/'.$position->id) }}"><i class="fa fa-files-o fa-fw"></i> {{ $position->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>
        </ul>
    </div>
</div>