<div class="header no-print">
    <div class="main-header">
        <!-- Logo -->
        <div class="header-left active">
            <a href="{{ route('home')}}" class="logo logo-normal">
                <img src="{{ getImage('settings', getInfo('logo')) }}" alt="Img">
            </a>
            <!--<a href="{{ route('home')}}" class="logo logo-white">-->
            <!--    <img src="{{ asset('assets/img/logo-white.svg')}}" alt="Img">-->
            <!--</a>-->
            <!--<a href="{{ route('home')}}" class="logo-small">-->
            <!--    <img src="{{ asset('assets/img/logo-small.png')}}" alt="Img">-->
            <!--</a>-->
            @can('pos.create')
                <div class="nav-item pos-nav" style="padding-right: 20px;">
                    <a href="{{ route('pos.create')}}" class="btn btn-dark btn-md d-inline-flex align-items-center">
                        <i class="ti ti-device-laptop me-1"></i>POS
                    </a>
                </div>
            @endcan
        </div>
        <!-- /Logo -->
        <a id="mobile_btn" class="mobile_btn" href="javascript:void(0);">
            <span class="bar-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </a>

        <!-- Header Menu -->
        <ul class="nav user-menu">

            <!-- Search -->
            <li class="nav-item nav-searchinputs">
                <!--<div class="top-nav-search">-->
                <!--    <a href="javascript:void(0);" class="responsive-search">-->
                <!--        <i class="fa fa-search"></i>-->
                <!--    </a>-->
                <!--    <form action="#" class="dropdown">-->
                <!--        <div class="searchinputs input-group dropdown-toggle" id="dropdownMenuClickable" data-bs-toggle="dropdown" data-bs-auto-close="outside">-->
                <!--            <input type="text" placeholder="Search">-->
                <!--            <div class="search-addon">-->
                <!--                <span><i class="ti ti-search"></i></span>-->
                <!--            </div>-->
                <!--            <span class="input-group-text">-->
                <!--                <kbd class="d-flex align-items-center"><img src="{{ asset('assets/img/icons/command.svg')}}" alt="img" class="me-1">K</kbd>-->
                <!--            </span>-->
                <!--        </div>-->
                <!--        <div class="dropdown-menu search-dropdown" aria-labelledby="dropdownMenuClickable">-->
                <!--            <div class="search-info">-->
                <!--                <h6><span><i data-feather="search" class="feather-16"></i></span>Recent Searches-->
                <!--                </h6>-->
                <!--                <ul class="search-tags">-->
                <!--                    <li><a href="javascript:void(0);">Products</a></li>-->
                <!--                    <li><a href="javascript:void(0);">Sales</a></li>-->
                <!--                    <li><a href="javascript:void(0);">Applications</a></li>-->
                <!--                </ul>-->
                <!--            </div>-->
                <!--            <div class="search-info">-->
                <!--                <h6><span><i data-feather="help-circle" class="feather-16"></i></span>Help</h6>-->
                <!--                <p>How to Change Product Volume from 0 to 200 on Inventory management</p>-->
                <!--                <p>Change Product Name</p>-->
                <!--            </div>-->
                <!--            <div class="search-info">-->
                <!--                <h6><span><i data-feather="user" class="feather-16"></i></span>Customers</h6>-->
                <!--                <ul class="customers">-->
                <!--                    <li><a href="javascript:void(0);">Aron Varu<img src="{{ asset('assets/img/profiles/avator1.jpg')}}" alt="Img" class="img-fluid"></a></li>-->
                <!--                    <li><a href="javascript:void(0);">Jonita<img src="{{ asset('assets/img/profiles/avatar-01.jpg')}}" alt="Img" class="img-fluid"></a></li>-->
                <!--                    <li><a href="javascript:void(0);">Aaron<img src="{{ asset('assets/img/profiles/avatar-10.jpg')}}" alt="Img" class="img-fluid"></a></li>-->
                <!--                </ul>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </form>-->
                <!--</div>-->
            </li>
            <!-- /Search -->

            

       
            @can('pos.create')
                <li class="nav-item pos-nav">
                    <a href="{{ route('pos.create')}}" class="btn btn-dark btn-md d-inline-flex align-items-center">
                        <i class="ti ti-device-laptop me-1"></i>POS
                    </a>
                </li>
            @endcan



            <li class="nav-item nav-item-box">
                <a href="javascript:void(0);" id="btnFullscreen">
                    <i class="ti ti-maximize"></i>
                </a>
            </li>


            @can('settings.access')
            <li class="nav-item nav-item-box">
                <a href="{{ route('settings.index')}}"><i class="ti ti-settings"></i></a>
            </li>
            @endcan
            <li class="nav-item dropdown has-arrow main-drop profile-nav">
                <a href="javascript:void(0);" class="nav-link userset" data-bs-toggle="dropdown">
                    <span class="user-info p-0">
                        <span class="user-letter">
                            <img src="{{ getImage('users',auth()->user()->image)}}" alt="Img" class="img-fluid">
                        </span>
                    </span>
                </a>
                <div class="dropdown-menu menu-drop-user">
                    <div class="profileset d-flex align-items-center">
                        <span class="user-img me-2">
                            <img src="{{ getImage('users',auth()->user()->image)}}" alt="Img">
                        </span>
                        <div>
                            <h6 class="fw-medium"> {{ auth()->user()->name}} </h6>
                            <p>{{ getrole()}}</p>
                        </div>
                    </div>
                    <a class="dropdown-item" href="{{ route('users.show',[9999])}}"><i class="ti ti-user-circle me-2"></i>MyProfile</a>
                    
                    <hr class="my-2">

                    <a class="dropdown-item logout pb-0" onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                             <i class="ti ti-logout me-2"></i>Logout</a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>

                </div>
            </li>
        </ul>
        <!-- /Header Menu -->

        <!-- Mobile Menu -->
        <div class="dropdown mobile-user-menu">
            <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{ route('users.show',[9999])}}">My Profile</a>
                <a class="dropdown-item" href="{{ route('settings.index')}}">Settings</a>
                <a class="dropdown-item" onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">Logout</a>
            </div>
        </div>
        <!-- /Mobile Menu -->
    </div>
</div>