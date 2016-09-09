<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li class="list-group-item {{ (Request::is('*article') ? 'active' : '') }}"> <a href="{{ url('admin/article') }}"><i class="fa fa-files-o fa-fw"></i> 文章管理 </a></li>
            <li class="list-group-item {{ (Request::is('*advsetting') ? 'active' : '') }}"><a href="{{ url('admin/advsetting/list') }}"> <i class="fa fa-files-o fa-fw"></i> 广告管理</a></li>
            <li class="list-group-item {{ (Request::is('*activeArticle') ? 'active' : '') }}" ><a href="{{ url('admin/articles/actived') }}"> <i class="fa fa-files-o fa-fw"></i> 发布列表</a></li>
            <li class="list-group-item {{ (Request::is('*history') ? 'active' : '') }}"><a href="{{ url('admin/history') }}"> <i class="fa fa-files-o fa-fw"></i> 历史记录</a></li>
        </ul>
    </div>
</div>