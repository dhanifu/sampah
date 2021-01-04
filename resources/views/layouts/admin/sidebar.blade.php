<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('admin/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Sampah</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('admin/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard')?'active':'' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                @role('admin')
                <li class="nav-item">
                    <a href="{{ route('admin.type.index') }}"
                        class="nav-link {{ request()->routeIs('admin.type.index')?'active':'' }}">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            Type
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.village.index') }}"
                        class="nav-link {{ request()->routeIs('admin.village.index')?'active':'' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Village
                        </p>
                    </a>
                </li>
                @elserole('operator')
                <li class="nav-item">
                    <a href="{{ route('operator.member.data.index') }}"
                        class="nav-link {{ request()->routeIs('operator.member.data.*')?'active':'' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Member
                        </p>
                    </a>
                </li>
                @endrole

                <li class="nav-header">Recycle Bin</li>
                @role('admin')
                <li class="nav-item">
                    <a href="{{ route('admin.type.trash.index') }}" class="nav-link {{request()->routeIs('admin.type.trash.*')?'active':''}}">
                        <i class="nav-icon fas fa-trash-alt"></i>
                        <p>
                            Type Trash
                            <span class="badge badge-danger right">{{ typeTrashCount() }}</span>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.village.trash.index') }}"
                        class="nav-link {{request()->routeIs('admin.village.trash.*')?'active':''}}">
                        <i class="nav-icon fas fa-trash-alt"></i>
                        <p>
                            Village Trash
                            <span class="badge badge-danger right">{{ villageTrashCount() }}</span>
                        </p>
                    </a>
                </li>
                @elserole('operator')
                <li class="nav-item">
                    <a href="{{ route('operator.member.trash.index') }}"
                        class="nav-link {{request()->routeIs('operator.member.trash.*')?'active':''}}">
                        <i class="nav-icon fas fa-trash-alt"></i>
                        <p>
                            Member Trash
                            <span class="badge badge-danger right">{{ memberTrashCount() }}</span>
                        </p>
                    </a>
                </li>
                
                @endrole
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>