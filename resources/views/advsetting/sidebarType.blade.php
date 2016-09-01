<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li class="list-group-item">
                <a href="{{ url('admin/advsetting/list') }}"><i class="fa fa-files-o fa-fw"></i>All</a>
            </li>
            @foreach($types as $type)
                <li class="list-group-item">
                    <a href="{{ url('admin/advsetting/type/'.$type->id) }}"><i class="fa fa-files-o fa-fw"></i> {{ $type->name }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</div>