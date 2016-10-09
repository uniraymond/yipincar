<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li class="list-group-item {{ (Request::is('*article/create') ? 'active' : '') }}"> <a href="{{ url('admin/user/create') }}"><i class="fa fa-files-o fa-fw"></i> 登记 </a></li>
            <li class="list-group-item {{ (Request::is('*user/authEditorList') ? 'active' : '') }}"><a href="{{ url('admin/user/authEditorList') }}"> <i class="fa fa-files-o fa-fw"></i> 会员列表</a></li>
        </ul>
    </div>
</div>

