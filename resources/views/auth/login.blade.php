<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets') }}/images/favicon.png">
    <title>Finance Web - Login</title>
    <link href="{{ asset('assets') }}/css/style.min.css" rel="stylesheet">
</head>

<body>
    <div class="main-wrapper">
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative"
            style="background:url('{{ asset('assets/images/big/auth-bg.jpg') }}') no-repeat center center;">
            <div class="auth-box row">
                <div class="col-lg-7 col-md-5 modal-bg-img" style="background-image: url('{{ asset('assets/images/big/3.jpg') }}');">
                </div>
                <div class="col-lg-5 col-md-7 bg-white">
                    <div class="p-3">
                        <div class="text-center mb-3">
                            <img src="{{ asset('assets/images/big/icon.png') }}" alt="Logo" style="max-width: 100px;">
                        </div>
                        <h2 class="mt-2 text-center">Sign In</h2>
                        <p class="text-center">Enter your email address and password to access your account.</p>

                        <form class="mt-4" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <label class="text-dark" for="email">Email</label>
                                <input class="form-control" id="email" name="email" type="email" placeholder="Enter your email" required autofocus value="{{ old('email') }}">
                                @error('email')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-dark" for="password">Password</label>
                                <input class="form-control" id="password" type="password" name="password" placeholder="Enter your password" required>
                                @error('password')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-dark btn-block">Sign In</button>
                            </div>

                            <div class="text-center mt-3">
                                Don't have an account? <a href="{{ route('register') }}" class="text-danger">Sign Up</a>
                            </div>
                        </form>

                        @if(session('message'))
                        <div class="alert alert-warning mt-3 text-center">{{ session('message') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets') }}/libs/jquery/dist/jquery.min.js "></script>
    <script src="{{ asset('assets') }}/libs/popper.js/dist/umd/popper.min.js "></script>
    <script src="{{ asset('assets') }}/libs/bootstrap/dist/js/bootstrap.min.js "></script>
    <script>
        $(".preloader ").fadeOut();
        </script>
</body>
</html>