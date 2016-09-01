<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li class="list-group-item"><a href="{{ url('admin/article') }}"><i class="fa fa-fw fa-desktop"></i>All</a></li>
            <li >
                <a href="#"><i class="fa fa-sitemap fa-fw"></i> 展开栏目<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    @foreach($categories as $category)
                        <li class="list-group-item {{ (Request::is('*category/'.$category->id) ? 'active' : '') }}">
                            <a href="{{ url('admin/article/category/'.$category->id) }}"><i class="fa fa-fw fa-desktop"></i> {{ $category->description }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>

            <li >
                <a href="#"><i class="fa fa-sitemap fa-fw"></i> 展开类型<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    @foreach($types as $type)
                        <li class="list-group-item {{ (Request::is('*type/'.$type->id) ? 'active' : '') }}">
                            <a href="{{ url('admin/article/type/'.$type->id) }}"><i class="fa fa-fw fa-desktop"></i> {{ $type->description }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>

            <li >
                <a href="#"><i class="fa fa-sitemap fa-fw"></i> 展开标签<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    @foreach($tags as $tag)
                        <li class="list-group-item {{ (Request::is('*tag/'.$tag->id) ? 'active' : '') }}">
                            <a href="{{ url('admin/article/tag/'.$tag->id) }}"><i class="fa fa-fw fa-desktop"></i> {{ $tag->description }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>

        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>