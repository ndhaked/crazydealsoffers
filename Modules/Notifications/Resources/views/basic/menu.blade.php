<li class="{{ Request::is('admin/notifications') ? 'active' : '' }} {{ Request::is('admin/notifications/*') ? 'active' : '' }} {{ Request::is('admin/comment-notifications') ? 'active' : '' }} {{ Request::is('admin/product/comments/*') ? 'active' : '' }} treeview">
  <a href="javascript:;"><i class="fa fa-bell"></i> <span>{{trans('notifications::menu.sidebar.manage')}}</span>
   <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
   <li class="{{ Request::is('admin/notifications') ? 'active' : '' }}"><a href="{{route('notifications.index')}}"><i class="fa fa-list"></i> {{trans('notifications::menu.sidebar.slug')}}</a></li>
   @can('commentnotifications.index')
   <li class="{{ Request::is('admin/comment-notifications') ? 'active' : '' }} {{ Request::is('admin/product/comments/*') ? 'active' : '' }}"><a href="{{route('commentnotifications.index')}}"><i class="fa fa-list"></i> Comments Notifications</a></li>
   @endcan
  </ul>
</li>
