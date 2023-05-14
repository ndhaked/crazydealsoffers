<li class="{{ Request::is('admin/staticpages') ? 'active' : '' }} {{ Request::is('admin/staticpages/*') ? 'active' : '' }} {{ Request::is('admin/advertiseaffiliated*') ? 'active' : '' }} treeview">
  <a href="javascript:;"><i class="fa fa-file-o"></i> <span>{{trans('staticpages::menu.sidebar.menu_title')}}</span>
   <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
   <li class="{{ Request::is('admin/staticpages') ? 'active' : '' }}"><a href="{{route('staticpages.index')}}"><i class="fa fa-list"></i> {{trans('staticpages::menu.sidebar.slug')}}</a></li>
   
   <!------------------------------->
   <li class="{{ Request::is('admin/advertiseaffiliated/advertise/edit') ? 'active' : '' }}"><a href="{{route('advertiseaffiliated.edit','advertise')}}"><i class="fa fa-list"></i> Advertise With Us</a></li>
   <li class="{{ Request::is('admin/advertiseaffiliated/affiliate/edit') ? 'active' : '' }}"><a href="{{route('advertiseaffiliated.edit','affiliate')}}"><i class="fa fa-list"></i> Affiliate Disclosure</a></li>
   <!------------------------------->
   

    {{--
       @if(Auth::user()->hasRole(['admin']) || Auth::user()->hasAnyPermission(['staticpages.create']))
    		<li class="{{ Request::is('admin/staticpages/create') ? 'active' : '' }}"><a href="{{route('staticpages.create')}}"><i class="fa fa-plus"></i> {{trans('staticpages::menu.sidebar.create')}}</a></li>
    --}}
  </ul>
</li>