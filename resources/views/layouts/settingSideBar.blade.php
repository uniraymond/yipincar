<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li class="list-group-item {{ (Request::is('*taboo') ? 'active' : '') }}"> <a href="{{ url('admin/taboo') }}"><i class="fa fa-files-o fa-fw"></i> 敏感词 </a></li>
            <li class="list-group-item {{ (Request::is('*user') || Request::is('*profile') ? 'active' : '') }}"><a href="{{ url('admin/user') }}"> <i class="fa fa-files-o fa-fw"></i> 用户管理</a></li>
            <li class="list-group-item {{ (Request::is('*tag') ? 'active' : '') }}" ><a href="{{ url('admin/tag') }}"> <i class="fa fa-files-o fa-fw"></i> 标签管理</a></li>
            <li class="list-group-item {{ (Request::is('*comment') ? 'active' : '') }}"><a href="{{ url('admin/comment') }}"> <i class="fa fa-files-o fa-fw"></i> 评论管理</a></li>
        </ul>
    </div>
</div>