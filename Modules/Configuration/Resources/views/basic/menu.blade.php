<li class="{{ Request::is('admin/configuration') ? 'active' : '' }} {{ Request::is('admin/configuration/*') ? 'active' : '' }} treeview">
  <a href="javascript:;"><i class="fa fa-wrench"></i> <span>{{trans('configuration::menu.sidebar.menu_title')}}</span>
   <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
     <li class="{{ Request::is('admin/configuration') ? 'active' : '' }}"><a href="{{route('configuration.index')}}"><i class="fa fa-list"></i> {{trans('configuration::menu.sidebar.slug')}}</a></li>
     <!--  <li class="{{ Request::is('admin/configuration/create') ? 'active' : '' }}"><a href="{{route('configuration.create')}}"><i class="fa fa-plus"></i> {{trans('configuration::menu.sidebar.create')}}</a></li> -->
    </ul>
</li>