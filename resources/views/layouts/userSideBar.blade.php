<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li class="list-group-item {{ (Request::is('*article') ? 'active' : '') }}"> <a href="{{ url('admin/registerUser') }}"><i class="fa fa-files-o fa-fw"></i> 登记 </a></li>
            <li class="list-group-item {{ (Request::is('*advsetting') ? 'active' : '') }}"><a href="{{ url('admin/users') }}"> <i class="fa fa-files-o fa-fw"></i> 会员列表</a></li>
        </ul>
    </div>
</div>
