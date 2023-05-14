   <li class="{{ Request::is('admin/roles') ? 'active' : '' }} {{ Request::is('admin/roles/*') ? 'active' : '' }} treeview">
    <a href="javascript:;"><i class="fa fa-lock"></i> <span>{{trans('menu.sidebar.role.manage')}}</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Request::is('admin/roles') ? 'active' : '' }}"><a href="{{route('roles.index')}}"><i class="fa fa-list"></i> {{trans('menu.sidebar.role.slug')}}</a></li>
        <li class="{{ Request::is('admin/roles/create') ? 'active' : '' }}"><a href="{{route('roles.create')}}"><i class="fa fa-plus"></i> {{trans('menu.sidebar.role.create')}}</a></li>
    </ul>
</li> 