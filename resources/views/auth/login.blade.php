<!DOCTYPE html>
<html lang="en">
<head>

        <!-- Meta Tags -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="">
        <meta name="robots" content="index, follow">
        <title>POS Login</title>
        
        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

        <!-- Apple Touch Icon -->
        <link rel="apple-touch-icon" sizes="180x180" href="assets/img/apple-touch-icon.png">
        
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        
        <!-- Fontawesome CSS -->
        <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
        <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

        <!-- Tabler Icon CSS -->
        <link rel="stylesheet" href="assets/plugins/tabler-icons/tabler-icons.css">

        <!-- Main CSS -->
        <link rel="stylesheet" href="assets/css/style.css">
        
    </head>
    <body class="account-page bg-white">
    
        <!-- Main Wrapper -->
        <div class="main-wrapper">
            <div class="account-content">
                <div class="row login-wrapper m-0">
                    <div class="col-lg-6 p-0">
                        
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="login-content">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="login-userset">
                                    <div class="login-logo logo-normal">
                                    <img src="{{ getImage('settings',getInfo('logo'))}}" alt="img">
                                </div>
                                <a href="index.html" class="login-logo logo-white">
                                    <img src="{{ getImage('settings',getInfo('logo'))}}"  alt="Img">
                                </a>
                                <div class="login-userheading">
                                    <h3>Sign In</h3>
                                    
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <input id="email" type="email" class="border-end-0 form-control" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        <span class="input-group-text border-start-0">
                                            <i class="ti ti-mail"></i>
                                        </span>
                                    </div>
                                    @error('email')
                                        <span class="alert alert-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <div class="pass-group">
                                        <input id="password" type="password" class="pass-input form-control" name="password" required autocomplete="current-password">
                                        <span class="ti toggle-password ti-eye-off text-gray-9"></span>

                                    </div>
                                    @error('password')
                                        <span class="alert alert-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-login authentication-check">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="custom-control custom-checkbox">
                                                <label class="checkboxs ps-4 mb-0 pb-0 line-height-1">
                                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                    <span class="checkmarks"></span>Remember me
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 text-end">
                                            <!-- <a class="forgot-link" href="forgot-password-2.html">Forgot Password?</a> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="form-login">
                                    <button type="submit" class="btn btn-login">Sign In</button>
                                </div>
                                
                            </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6 p-0">
                        <div class="login-img">
                            <img src="{{ getImage('assets/img','login2.jpeg')}}" alt="img">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Main Wrapper -->

        
</body>

<!-- Mirrored from dreamspos.dreamstechnologies.com/html/template/signin-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 09 May 2025 05:29:47 GMT -->
</html>