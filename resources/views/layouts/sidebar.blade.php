@php
    $role = auth()->user()->role;
@endphp

<aside class="left-sidebar" data-sidebarbg="skin6">
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                @if($role === 'user')
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="{{ route('dashboard') }}" aria-expanded="false">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                @endif
                @if($role === 'admin')
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="{{ route('admin.dashboard') }}" aria-expanded="false">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                @endif
                <li class="list-divider"></li>
                @if($role === 'user')
                <li class="nav-small-cap"><span class="hide-menu">Money Management</span></li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('tabungan.index') }}" aria-expanded="false">
                        <i class="fas fa-piggy-bank"></i>
                        <span class="hide-menu">Tabungan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('kategori.index') }}" aria-expanded="false">
                        <i class="fas fa-tag"></i>
                        <span class="hide-menu">Kategori</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('dompet.index') }}" aria-expanded="false">
                        <i class="fas fa-credit-card"></i>
                        <span class="hide-menu">Dompet</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('transaksi.index') }}" aria-expanded="false">
                        <i class="fas fa-exchange-alt"></i>
                        <span class="hide-menu">Transaksi</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('transaksi.tabungan') }}" aria-expanded="false">
                        <i class="fas fa-history"></i>
                        <span class="hide-menu">Transaksi Tabungan</span>
                    </a>
                </li>
                <li class="list-divider"></li>
                @endif


                @if($role === 'admin')
                <!-- User Management Section -->
                <li class="nav-small-cap"><span class="hide-menu">User Management</span></li>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-users"></i>
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
                @endif
            </ul>
        </nav>
    </div>
</aside>
