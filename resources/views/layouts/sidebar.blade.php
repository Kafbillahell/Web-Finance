<aside class="left-sidebar" data-sidebarbg="skin6">
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="sidebar-item"> 
                    <a class="sidebar-link sidebar-link" href="{{ route('dashboard') }}" aria-expanded="false">
                        <i data-feather="home" class="feather-icon"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="list-divider"></li>
                
                <!-- User Management Section -->
                <li class="nav-small-cap"><span class="hide-menu">User Management</span></li>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <i data-feather="users" class="feather-icon"></i>
                        <span class="hide-menu">Users</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level base-level-line">
                        <li class="sidebar-item">
                            <a href="{{ route('users.index') }}" class="sidebar-link">
                                <span class="hide-menu">All Users</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('users.create') }}" class="sidebar-link">
                                <span class="hide-menu">Add User</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="list-divider"></li>
            </ul>
        </nav>
    </div>
</aside>yy