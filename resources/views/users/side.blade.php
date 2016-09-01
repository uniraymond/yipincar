<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li class="list-group-item">
                <a href="{{ url('admin/user') }}"><i class="fa fa-user"></i>All</a>
            </li>
            @foreach($usergroups as $usergroup)
                @if($usergroup->name != 'super_admin')
                    <li class="list-group-item {{ (Request::is('*role/'.$usergroup->id) ? 'active' : '') }}">
                        <a href="{{ url('admin/user/role/'.$usergroup->id) }}"><i class="fa fa-user"></i> {{ $usergroup->description }}</a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>