<style>
    .logo-text {
        text-decoration: none !important;
    }
</style>
<header class="topbar" data-navbarbg="skin6">
    <nav class="navbar top-navbar navbar-expand-md">
        <div class="navbar-header" data-logobg="skin6">
            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"
                aria-label="Toggle sidebar">
                <i class="ti-menu ti-close"></i>
            </a>

            <div class="navbar-brand">
                <a href="{{ url('/') }}" aria-label="FinDash Home">
                    <div class="flex items-center gap-4 mb-10">
                        <svg class="h-12 w-12" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" x2="12" y1="2" y2="22"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                        <span class="text-2xl font-bold text-primary logo-text">FinDash</span>
                    </div>
                </a>
            </div>

            <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation content">
                <i class="ti-more"></i>
            </a>
        </div>

        <div class="navbar-collapse collapse" id="navbarSupportedContent">
            <ul class="navbar-nav float-left mr-auto ml-3 pl-1">
                <li class="nav-item d-none d-md-block">
                    <a class="nav-link" href="javascript:void(0)"><i class="ti-search"></i></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti-bell"></i>
                        <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-left animated bounceInDown">
                        @php
                            $success = session('success');
                            $successMessages = is_array($success) ? $success : [$success];
                        @endphp
                        
                        @if (!empty($successMessages))
                            @foreach ($successMessages as $message)
                                <a class="dropdown-item text-success" href="#"><strong>{{ $message }}</strong></a>
                            @endforeach
                        @endif
                        @if (session('failed'))
                            @foreach (session('failed') as $failed)
                                <a class="dropdown-item text-danger" href="#"><strong>{{ $failed }}</strong></a>
                            @endforeach
                        @else
                            <a class="dropdown-item text-primary" href="#"><i class="ti-info"></i> No notifications</a>
                        @endif
                        
                    </div>
                </li>
            </ul>

            <ul class="navbar-nav float-right">
                @php
                $user = Auth::user();
                @endphp

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false" aria-label="User profile dropdown">
                        <img src="{{ asset('assets/avatars/' . ($user->avatar ?? 'avatar1.png')) }}" 
                             alt="{{ $user->name }}'s avatar" 
                             class="rounded-circle" 
                             style="width: 40px; height: 40px; object-fit: cover;">
                        <span class="ml-2 d-none d-lg-inline-block">
                            <span>Hello,</span>
                            <span class="text-dark">{{ $user->name }}</span>
                            <i data-feather="chevron-down" class="svg-icon"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                        <a class="dropdown-item" href="{{ route('profile') }}"><i data-feather="user" class="svg-icon mr-2 ml-1"></i> My Profile</a>

                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i data-feather="power" class="svg-icon mr-2 ml-1"></i> Logout
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>