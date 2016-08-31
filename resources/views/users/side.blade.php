<div class="collapse navbar-collapse navbar-toggleable-sm navbar-ex1-collapse">
    <ul class="nav navbar-nav side-nav list-group">
        <li class="list-group-item">
            <a href="{{ url('admin/user') }}"><i class="fa fa-user"></i>All</a>
        </li>
        @foreach($usergroups as $usergroup)
            @if($usergroup->name != 'super_admin')
                <li class="list-group-item">
                    <a href="{{ url('admin/user/role/'.$usergroup->id) }}"><i class="fa fa-user"></i> {{ $usergroup->description }}</a>
                </li>
            @endif
        @endforeach
    </ul>
</div>