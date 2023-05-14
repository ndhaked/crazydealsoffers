<li class="{{ Request::is('admin/categories') ? 'active' : '' }} {{ Request::is('admin/categories/*') ? 'active' : '' }} treeview">
  <a href="javascript:;"><i class="{{trans('categories::menu.font_icon')}}"></i> <span>{{trans('categories::menu.sidebar.menu_title')}}</span>
   <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
    @can('categories.index')
      <li class="{{ Request::is('admin/categories') ? 'active' : '' }}"><a href="{{route('categories.index')}}"><i class="fa fa-list"></i> {{trans('categories::menu.sidebar.slug')}}</a></li>
    @endcan
    @can('categories.create')
      <li class="{{ Request::is('admin/categories/create') ? 'active' : '' }}"><a href="{{route('categories.create')}}"><i class="fa fa-plus"></i> {{trans('categories::menu.sidebar.create')}}</a></li>
    @endcan
  </ul>
</li>