<div class="collapse navbar-collapse navbar-toggleable-sm navbar-ex1-collapse">
    <ul class="nav navbar-nav side-nav list-group">
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