<li class="{{ Request::is('admin/faq') ? 'active' : '' }} {{ Request::is('admin/faq/*') ? 'active' : '' }} treeview">
  <a href="javascript:;"><i class="fa fa-question-circle"></i> <span>{{trans('faq::menu.sidebar.menu_title')}}</span>
   <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
   <li class="{{ Request::is('admin/faq') ? 'active' : '' }}"><a href="{{route('faq.index')}}"><i class="fa fa-list"></i> {{trans('faq::menu.sidebar.slug')}}</a></li>
    <li class="{{ Request::is('admin/faq/create') ? 'active' : '' }}"><a href="{{route('faq.create')}}"><i class="fa fa-plus"></i> {{trans('faq::menu.sidebar.create')}}</a></li>
  </ul>
</li>
