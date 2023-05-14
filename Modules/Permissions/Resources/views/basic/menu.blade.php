  <li class="{{ Request::is('admin/permissions') ? 'active' : '' }} {{ Request::is('admin/permissions/*') ? 'active' : '' }} treeview">
    <a href="javascript:;"><i class="fa fa-unlock"></i> <span>{{trans('menu.sidebar.permission.manage')}}</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Request::is('admin/permissions') ? 'active' : '' }}"><a href="{{route('permissions.index')}}"><i class="fa fa-list"></i> {{trans('menu.sidebar.permission.slug')}}</a></li>
    </ul>
</li>