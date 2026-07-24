<!DOCTYPE html>
<html lang="en" data-layout-mode="light_mode">

@include('partials.head')

<body>

    <div class="main-wrapper">

        <!-- Header -->
        @include('partials.header')
        <!-- /Header -->

        <!-- Sidebar -->
        <div class="sidebar no-print" id="sidebar">
            <!-- Logo -->
            <div class="sidebar-logo active">
                <a href="{{ route('home')}}" class="logo logo-normal">
                    <img src="{{ getImage('settings',getInfo('logo'))}}" alt="Img">
                </a>
                <a href="{{ route('home')}}" class="logo logo-white">
                    <img src="{{ asset('assets/img/logo-white.svg')}}" alt="Img">
                </a>
                <a href="#" class="logo-small">
                    <img src="{{ getImage('settings',getInfo('logo'))}}" alt="Img">
                </a>
                <a id="toggle_btn" href="javascript:void(0);">
                    <i data-feather="chevrons-left" class="feather-16"></i>
                </a>
            </div>
            <!-- /Logo -->
            <div class="modern-profile p-3 pb-0">
                <div class="text-center rounded bg-light p-3 mb-4 user-profile">
                    <div class="avatar avatar-lg online mb-3">
                        <img src="{{ asset('assets/img/customer/customer15.jpg')}}" alt="Img" class="img-fluid rounded-circle">
                    </div>
                    <h6 class="fs-14 fw-bold mb-1">Adrian Herman</h6>
                    <p class="fs-12 mb-0">System Admin</p>
                </div>
                <div class="sidebar-nav mb-3">
                    <ul class="nav nav-tabs nav-tabs-solid nav-tabs-rounded nav-justified bg-transparent" role="tablist">
                        <li class="nav-item"><a class="nav-link active border-0" href="#">Menu</a></li>
                        <li class="nav-item"><a class="nav-link border-0" href="chat.html">Chats</a></li>
                        <li class="nav-item"><a class="nav-link border-0" href="email.html">Inbox</a></li>
                    </ul>
                </div>
            </div>
            <div class="sidebar-header p-3 pb-0 pt-2">
                <div class="text-center rounded bg-light p-2 mb-4 sidebar-profile d-flex align-items-center">
                    <div class="avatar avatar-md onlin">
                        <img src="{{ asset('assets/img/customer/customer15.jpg')}}" alt="Img" class="img-fluid rounded-circle">
                    </div>
                    <div class="text-start sidebar-profile-info ms-2">
                        <h6 class="fs-14 fw-bold mb-1">Adrian Herman</h6>
                        <p class="fs-12">System Admin</p>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between menu-item mb-3">
                    <div>
                        <a href="{{ route('home')}}" class="btn btn-sm btn-icon bg-light">
                            <i class="ti ti-layout-grid-remove"></i>
                        </a>
                    </div>
                    <div>
                        <a href="chat.html" class="btn btn-sm btn-icon bg-light">
                            <i class="ti ti-brand-hipchat"></i>
                        </a>
                    </div>
                    <div>
                        <a href="email.html" class="btn btn-sm btn-icon bg-light position-relative">
                            <i class="ti ti-message"></i>
                        </a>
                    </div>
                    <div class="notification-item">
                        <a href="activities.html" class="btn btn-sm btn-icon bg-light position-relative">
                            <i class="ti ti-bell"></i>
                            <span class="notification-status-dot"></span>
                        </a>
                    </div>
                    <div class="me-0">
                        <a href="general-settings.html" class="btn btn-sm btn-icon bg-light">
                            <i class="ti ti-settings"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    @include('partials.sidebar')
                </div>
            </div>
        </div>
        <!-- /Sidebar -->



        <div class="page-wrapper">
            @yield('content')
            <div class="copyright-footer d-flex align-items-center justify-content-between border-top bg-white gap-3 flex-wrap no-print">
                <p class="fs-13 text-gray-9 mb-0">2025 &copy; {{getInfo('title')}}. All Right Reserved</p>
                <p>Designed & Developed By <a href="javascript:void(0);" class="link-primary">AR</a></p>
            </div>

            <div class="modal fade" id="common_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            </div>

            <div class="modal fade modal-default" id="print-receipt" aria-labelledby="print-receipt">
                
            </div>

        </div>

    </div>
    <!-- /Main Wrapper -->

    @include('partials.js')

</body>

</html>