<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li class="list-group-item {{ (Request::is('*article/myarticle') ? 'active' : '') }}"> <a href="{{ url('admin/article/myarticle') }}"><i class="fa fa-files-o fa-fw"></i> 我的文章 </a></li>
            @if((null !== Auth::user()) && Auth::user()->hasAnyRole(['super_admin', 'admin', 'chef_editor', 'main_editor', 'adv_editor']))
            <li class="list-group-item {{ (Request::is('*article/articlereview') ? 'active' : '') }}"> <a href="{{ url('admin/article/articlereview') }}"><i class="fa fa-files-o fa-fw"></i> 文章审核 </a></li>
            @endif
            @if((null !== Auth::user()) && Auth::user()->hasAnyRole(['super_admin', 'admin', 'chef_editor', 'adv_editor']))
            <li class="list-group-item {{ (Request::is('*advsetting*') ? 'active' : '') }}"><a href="{{ url('admin/advsetting/list') }}"> <i class="fa fa-files-o fa-fw"></i> 广告管理</a></li>
            @endif
            <li class="list-group-item {{ (Request::is('*articles/actived') ? 'active' : '') }}" ><a href="{{ url('admin/articles/actived') }}"> <i class="fa fa-files-o fa-fw"></i> 发布列表</a></li>
            @if((null !== Auth::user()) && Auth::user()->hasAnyRole(['super_admin', 'admin', 'chef_editor', 'adv_editor']))
            <li class="list-group-item {{ (Request::is('*history') ? 'active' : '') }}"><a href="{{ url('admin/history') }}"> <i class="fa fa-files-o fa-fw"></i> 历史记录</a></li>
            @endif

            @if((null !== Auth::user()) && Auth::user()->hasAnyRole(['super_admin']))
                <li class="list-group-item {{ (Request::is('*article/videoTest') ? 'active' : '') }}"> <a href="{{ url('admin/article/videoTest') }}"><i class="fa fa-files-o fa-fw"></i> 视频测试 </a></li>
            @endif
        </ul>
    </div>
</div>