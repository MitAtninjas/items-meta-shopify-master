<nav id="sidebar" aria-label="Main Navigation">
    <!-- Side Header -->
    <div class="bg-header-dark">
        <div class="content-header bg-white-10">
            <!-- Logo -->
            <a class="font-w600 text-white tracking-wide" href="/">
                <span class="smini-visible">
                    A<span class="opacity-75">A</span>
                </span>
                <span class="smini-hidden">
                    Active<span class="opacity-75">Ants</span>
                </span>
            </a>
            <!-- END Logo -->

            <!-- Options -->
            <div>
                <!-- Close Sidebar, Visible only on mobile screens -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <a class="d-lg-none text-white ml-2" data-toggle="layout" data-action="sidebar_close" href="javascript:void(0)">
                    <i class="fa fa-times-circle"></i>
                </a>
                <!-- END Close Sidebar -->
            </div>
            <!-- END Options -->
        </div>
    </div>
    <!-- END Side Header -->

    <!-- Sidebar Scrolling -->
    <div class="js-sidebar-scroll">
    <!-- Side Navigation -->
        <div class="content-side content-side-full">
            <ul class="nav-main">
                <li class="nav-main-item mt-4">
                    <a class="nav-main-link" href="{{ route('admin.dashboard') }}">
                        <i class="nav-main-link-icon fa fa-location-arrow"></i>
                        <span class="nav-main-link-name">Dashboard</span>
                    </a>
                </li>
                <li class="nav-main-heading">Sections</li>

                @if (auth()->user()->hasRole('admin'))
                    <li class="nav-main-item">
                        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="true" href="#">
                            <i class="nav-main-link-icon fa fa-users"></i>
                            <span class="nav-main-link-name">Users</span>
                        </a>
                        <ul class="nav-main-submenu">
                            <li class="nav-main-item">
                                <a class="nav-main-link" href="{{ route('admin.users.index') }}">
                                    <span class="nav-main-link-name">User List</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link" href="{{ route('admin.users.create') }}">
                                    <span class="nav-main-link-name">Add User</span>
                                </a>
                            </li>
                        </ul>
                    </li>
            
                    <li class="nav-main-item">
                        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="true" href="#">
                            <i class="nav-main-link-icon fa fa-store"></i>
                            <span class="nav-main-link-name">Stores</span>
                        </a>
                        <ul class="nav-main-submenu">
                            <li class="nav-main-item">
                                <a class="nav-main-link" href="{{ route('admin.stores.index') }}">
                                    <span class="nav-main-link-name">Store List</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link" href="{{ route('admin.stores.create') }}">
                                    <span class="nav-main-link-name">Add Store</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @else 
                    <li class="nav-main-item">
                        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="true" href="#">
                            <i class="nav-main-link-icon fa fa-store"></i>
                            <span class="nav-main-link-name">Stores</span>
                        </a>
                        <ul class="nav-main-submenu">
                            <li class="nav-main-item">
                                <a class="nav-main-link" href="{{ route('customer.stores.index') }}">
                                    <span class="nav-main-link-name">Store List</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link" href="{{ route('customer.stores.create') }}">
                                    <span class="nav-main-link-name">Add Store</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                
            </ul>
        </div>
        <!-- END Side Navigation -->
    </div>
    <!-- END Sidebar Scrolling -->
</nav>
