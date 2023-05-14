<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{Auth::guard('admin')->user()->PicturePath}}" class="img-circle" alt="User Image" style="height: 35px;width: 35px;" />
            </div>
            <div class="pull-left info">
                <p>{{ucfirst(Auth::guard('admin')->user()->FullName)}} <a href="javascript:;"><i class="fa fa-circle text-success"></i></a></p>
                 <span class="hidden-xs">{{now()->subHours(4)->format(\Config::get('custom.default_date_time_formate')) }}</span>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li class="header"></li>
            @can('backend.dashboard')
            <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}"><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            @endcan
           
            @can('statistics.index')
            <li class="{{ Request::is('admin/reports') ? 'active' : '' }}"><a href="{{route('statistics.index')}}"><i class="fa fa-dashboard"></i> <span>Manage Reports</span></a></li>
            @endcan
           
            @if(auth('admin')->user()->hasAnyPermissionCustom(['users.index','subadmin.index'],'admin'))
            <li class="{{ Request::is('admin/borrowers*') ? 'active' : '' }}  {{ Request::is('admin/subadmin*') ? 'active' : '' }} {{ Request::is('admin/customers*') ? 'active' : '' }} treeview">
                <a href="javascript:;">
                    <i class="fa fa-users"></i> <span>{{trans('menu.sidebar.users.main')}}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @can('subadmin.index')
                    <li class="{{ Request::is('admin/subadmin*') ? 'active' : '' }}"><a href="{{route('subadmin.index')}}" alt="{{ trans('menu.role.subadmin') }}"><i class="fa fa-circle-o"></i>{{trans('menu.role.subadmin')}}</a></li>
                    @endcan
                    <li class="{{ Request::is('admin/customers*') ? 'active' : '' }}"><a href="{{route('users.index')}}" alt="{{ trans('menu.sidebar.role.customer') }}"><i class="fa fa-circle-o"></i>{{trans('menu.role.customers')}}</a></li>
                </ul>
            </li>
            @endcan
            
            @if(Auth::user()->hasAnyPermission(['configuration.index','email-templates.index','roles.index','permissions.index','staticpages.index','advertiseaffiliated.edit'],'admin'))
            <li class="{{ Request::is('admin/configuration*') ? 'active' : '' }} {{ Request::is('admin/staticpages*') ? 'active' : '' }} {{ Request::is('admin/aboutus*') ? 'active' : '' }} {{ Request::is('admin/contactus*') ? 'active' : '' }}  {{ Request::is('admin/roles*') ? 'active' : '' }} {{ Request::is('admin/permission*') ? 'active' : '' }} {{ Request::is('admin/email-templates*') ? 'active' : '' }} {{ Request::is('admin/faq*') ? 'active' : '' }} {{ Request::is('admin/advertiseaffiliated*') ? 'active' : '' }} treeview">
                <a href="javascript:;">
                    <i class="fa fa-sort-amount-desc"></i> <span>Site Managment</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                @can('configuration.index')
                    @if(\Module::collections()->has('Configuration'))
                        @include('configuration::basic.menu')
                    @endif
                @endcan
                @can('email-templates.index')
                    @if(\Module::collections()->has('EmailTemplates'))
                        @include('emailtemplates::basic.menu')
                    @endif 
                @endcan
                 <?php /*
                @can('roles.index')
                    @if(\Module::collections()->has('Roles'))
                        @include('roles::basic.menu')
                    @endif 
                @endcan
               
                @can('permissions.index')
                    @if(\Module::collections()->has('Permissions'))
                        @include('permissions::basic.menu')
                    @endif
                @endcan
                */ ?>
                @can('staticpages.index')
                    @if(\Module::collections()->has('StaticPages'))
                        @include('staticpages::basic.menu')
                    @endif                   
                @endcan
               
                @can('faq.index')
                    @if(\Module::collections()->has('Faq'))
                        @include('faq::basic.menu')
                    @endif
                @endcan
                </ul>
            </li>
            @endif
            @can('categories.index')
                @if(\Module::collections()->has('Categories'))
                    @include('categories::basic.menu')
                @endif
            @endcan

            
            @if(\Module::collections()->has('Notifications'))
                 @can('notifications.index')
                    @include('notifications::basic.menu')
                 @endcan
            @endif

            @can('product.index')
            <li class="{{ Request::is('admin/products') ? 'active' : '' }} {{ Request::is('admin/products/*') ? 'active' : '' }} treeview">
                <a href="javascript:;">
                    <i class="fa fa-cube"></i> <span>Product Management</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('admin/products') ? 'active' : '' }}"><a href="{{route('product.index')}}" alt="{{ trans('menu.sidebar.role.customer') }}"><i class="fa fa-list"></i>All Products</a></li>
                    @can('product.create')
                        <li class="{{ Request::is('admin/products/create') ? 'active' : '' }}"><a href="{{route('product.create')}}" alt="{{ trans('menu.sidebar.role.customer') }}"><i class="fa fa-plus"></i>Add Product</a></li>
                    @endcan
                    <li class="{{ Request::is('admin/products/social-login') ? 'active' : '' }}"><a href="{{route('product.socialLogin')}}" alt="{{ trans('menu.sidebar.role.customer') }}"><i class="fa fa-key"></i>Social Login</a></li>
                </ul>
            </li>
            @endcan
            
            @if(\Module::collections()->has('Blogs'))
                @can('blog.index')
                <li class="{{ Request::is('admin/blogs') ? 'active' : '' }} {{ Request::is('admin/blogs/*') ? 'active' : '' }} treeview">
                    <a href="javascript:;">
                        <i class="fa fa-film"></i> <span>Blog Management</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Request::is('admin/blogs') ? 'active' : '' }}"><a href="{{route('blog.index')}}" alt="{{ trans('menu.sidebar.role.customer') }}"><i class="fa fa-list"></i>All Blogs</a></li>
                        <li class="{{ Request::is('admin/blogs/create') ? 'active' : '' }}"><a href="{{route('blog.create')}}" alt="{{ trans('menu.sidebar.role.customer') }}"><i class="fa fa-plus"></i>Add Blog</a></li>
                    </ul>
                </li>
                @endcan
            @endif

            @if(\Module::collections()->has('Advertisements'))
                @can('advertisement.index')
                <li class="{{ Request::is('admin/advertisement') ? 'active' : '' }} {{ Request::is('admin/advertisement/*') ? 'active' : '' }} treeview">
                    <a href="javascript:;">
                        <i class="fa fa-newspaper-o"></i> <span>Advertisement Manage</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Request::is('admin/advertisement') ? 'active' : '' }}"><a href="{{route('advertisement.index')}}" alt="{{ trans('menu.sidebar.role.customer') }}"><i class="fa fa-list"></i>All Advertisements</a></li>
                        {{--<li class="{{ Request::is('admin/advertisement/create') ? 'active' : '' }}"><a href="{{route('advertisement.create')}}" alt="{{ trans('menu.sidebar.role.customer') }}"><i class="fa fa-plus"></i>Add Advertisement</a></li>--}}
                    </ul>
                </li>
                @endcan
            @endif
            
            <!-- @if(\Module::collections()->has('AdvertiseAffiliated'))
                <li class="{{ Request::is('admin/advertiseaffiliated') ? 'active' : '' }} {{ Request::is('admin/advertiseaffiliated/*') ? 'active' : '' }} treeview">
                    <a href="javascript:;">
                        <i class="fa fa-tasks"></i> <span>Advertise Affiliated Manage</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Request::is('admin/advertiseaffiliated') ? 'active' : '' }}"><a href="{{route('advertiseaffiliated.index')}}" alt="{{ trans('menu.sidebar.role.customer') }}"><i class="fa fa-list"></i>All Advertise Affiliated</a></li>
                    </ul>
                </li>
            @endif -->

            @if(\Module::collections()->has('Slider'))
                <li class="{{ Request::is('admin/slider') ? 'active' : '' }} {{ Request::is('admin/slider/*') ? 'active' : '' }} treeview">
                    <a href="javascript:;">
                        <i class="fa fa-image"></i> <span>Banner Advertisements</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Request::is('admin/slider') ? 'active' : '' }}"><a href="{{route('slider.index')}}" alt="{{ trans('menu.sidebar.role.customer') }}"><i class="fa fa-list"></i>All Banners</a></li>
                    </ul>
                </li>
            @endif
            
        </ul>
    </section>
</aside>
<!-- Global model for ajax -->
<div class="modal fade" id="globalModel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="modelContent">
            <!-- dynamic content goes here  -->
        </div>
    </div>
</div>
