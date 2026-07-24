<!DOCTYPE html>
<html lang="en">
	
<!-- Mirrored from dreamspos.dreamstechnologies.com/html/template/pos-5.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 09 May 2025 05:29:32 GMT -->
<head>

		<!-- Meta Tags -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Dreams POS is a powerful Bootstrap based Inventory Management Admin Template designed for businesses, offering seamless invoicing, project tracking, and estimates.">
		<meta name="keywords" content="inventory management, admin dashboard, bootstrap template, invoicing, estimates, business management, responsive admin, POS system">
		<meta name="author" content="Dreams Technologies">
		<meta name="robots" content="index, follow">
		<title> POS Panel</title>

		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png')}}">

		<!-- Apple Touch Icon -->
		<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/apple-touch-icon.png')}}">
		
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css')}}">

		<!-- Datetimepicker CSS -->
		<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css')}}">
		
		<!-- animation CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/animate.css')}}">

		<!-- Select2 CSS -->
		<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css')}}">

		<!-- Datatable CSS -->
		<link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap5.min.css')}}">
		
        <!-- Fontawesome CSS -->
		<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css')}}">
		<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css')}}">

		<!-- Daterangepikcer CSS -->
		<link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css')}}">

		<!-- Tabler Icon CSS -->
		<link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.css')}}">

		<!-- Datetimepicker CSS -->
		<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css')}}">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

		<!-- Owl Carousel CSS -->
		<link rel="stylesheet" href="{{ asset('assets/plugins/owlcarousel/owl.carousel.min.css')}}">
		<link rel="stylesheet" href="{{ asset('assets/plugins/owlcarousel/owl.theme.default.min.css')}}">
		
		<!-- Color Picker Css -->
	<link rel="stylesheet" href="{{ asset('assets/plugins/%40simonwep/pickr/themes/nano.min.css')}}">

	    <!-- Main CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/style.css')}}">
        <link rel="stylesheet" href="{{ asset('assets/css/pos_style.css')}}">
		
	</head>
	
	<body class="pos-page">
		<div id="global-loader" >
			<div class="whirly-loader"> </div>
		</div>
		<!-- Main Wrapper -->
		<div class="main-wrapper pos-three pos-four">

			<!-- Header -->
			<div class="header pos-header">
			
				<!-- Logo -->
				 <div class="header-left active">
					<a href="{{ route('home')}}" class="logo logo-normal">
						<img src="{{ getImage('settings',getInfo('logo'))}}"  alt="Img">
					</a>
					<a href="{{ route('home')}}" class="logo logo-white">
						<img src="{{ asset('assets/img/logo-white.svg')}}"  alt="Img">
					</a>
					<a href="{{ route('home')}}" class="logo-small">
						<img src="{{ asset('assets/img/logo-small.png')}}"  alt="Img">
					</a>
				</div>
				<!-- /Logo -->
				
				<a id="mobile_btn" class="mobile_btn d-none" href="#sidebar">
					<span class="bar-icon">
						<span></span>
						<span></span>
						<span></span>
					</span>
				</a>
				
				<!-- Header Menu -->
				<ul class="nav user-menu">

					
					
					<li class="nav-item pos-nav">
						<a href="{{ route('home')}}" class="btn btn-purple btn-md d-inline-flex align-items-center">
							<i class="ti ti-world me-1"></i>Dashboard
						</a>
					</li>

					
					
					<li class="nav-item nav-item-box">
						<a href="#" data-bs-toggle="modal" data-bs-target="#calculator" class="bg-orange border-orange text-white"><i class="ti ti-calculator"></i></a>
					</li>
					<li class="nav-item nav-item-box">
						<a href="javascript:void(0);" id="btnFullscreen" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Maximize" >
							<i class="ti ti-maximize"></i>
						</a>
					</li>
					<li class="nav-item nav-item-box" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Cash Register">
						<a href="#" data-bs-toggle="modal" data-bs-target="#cash-register"><i class="ti ti-cash"></i></a>
					</li>
					<li class="nav-item nav-item-box" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Print Last Reciept">
						<a href="#"><i class="ti ti-printer"></i></a>
					</li>
					<li class="nav-item nav-item-box" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Today’s Sale">
						<a href="#" data-bs-toggle="modal" data-bs-target="#today-sale"><i class="ti ti-progress"></i></a>
					</li>
					<li class="nav-item nav-item-box" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Today’s Profit">
						<a href="#" data-bs-toggle="modal" data-bs-target="#today-profit"><i class="ti ti-chart-infographic"></i></a>
					</li>
					<li class="nav-item nav-item-box" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="POS Settings">
						<a href="pos-settings.html"><i class="ti ti-settings"></i></a>
					</li>
					<li class="nav-item dropdown has-arrow main-drop profile-nav">
						<a href="javascript:void(0);" class="nav-link userset" data-bs-toggle="dropdown">
							<span class="user-info p-0">
								<span class="user-letter">
									<img src="{{ asset('assets/img/profiles/avator1.jpg')}}" alt="Img" class="img-fluid">
								</span>
							</span>
						</a>
						<div class="dropdown-menu menu-drop-user">
							<div class="profilename">
								<div class="profileset">
									<span class="user-img"><img src="{{ asset('assets/img/profiles/avator1.jpg')}}" alt="Img">
										<span class="status online"></span></span>
									<div class="profilesets">
										<h6> {{ auth()->user()->name}} </h6>
										<h5> {{ getrole()}} </h5>
									</div>
								</div>
								<hr class="m-0">
								<hr class="m-0">
								<a class="dropdown-item logout pb-0" onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
									<img src="{{ asset('assets/img/icons/log-out.svg')}}" class="me-2" alt="img">Logout
								</a>

								<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
				                    @csrf
				                </form>
							</div>
						</div>
					</li>
				</ul>
				<!-- /Header Menu -->
				
				<!-- Mobile Menu -->
				<div class="dropdown mobile-user-menu">
					<a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
					<div class="dropdown-menu dropdown-menu-right">
						<a class="dropdown-item" href="profile.html">My Profile</a>
						<a class="dropdown-item" href="general-settings.html">Settings</a>
						<a class="dropdown-item" href="signin.html">Logout</a>
					</div>
				</div>
				<!-- /Mobile Menu -->
			</div>
			<!-- Header -->

			<!-- Sidebar -->
			<div class="sidebar d-flex flex-column" id="sidebar">

                <!-- Logo -->
                <div class="sidebar-logo">
                    <a href="{{ route('home')}}" class="logo logo-normal">
                        <!--<img src="{{ asset('assets/img/logo.svg')}}" alt="Img">-->
                        <img src="{{ getImage('settings',getInfo('logo'))}}"  alt="Img">
                    </a>
                    <a href="{{ route('home')}}" class="logo logo-white">
                        <img src="{{ asset('assets/img/logo-white.svg')}}" alt="Img">
                    </a>
                    <a href="{{ route('home')}}" class="logo-small">
                        <img src="{{ asset('assets/img/logo-small.png')}}" alt="Img">
                    </a>
                    <!--<a id="toggle_btn" href="javascript:void(0);">-->
                    <!--    <i data-feather="chevrons-left" class="feather-16"></i>-->
                    <!--</a>-->
                </div>
                <!-- /Logo -->
            
                <!-- Main Menu -->
                <ul class="sidebar-menu flex-grow-1">
                    <li class="menu-item">
                        <a href="">
                            <i class="ti ti-layout-grid fs-16 me-2"> </i>
                            <span>POS</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="">
                            <i class="ti ti-moneybag fs-16 me-2"></i>
                            <span>Orders</span>
                            <span class="badge bg-danger">9+</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="">
                            <i class="ti ti-users-group fs-16 me-2"></i>
                            <span>Customers</span>
                        </a>
                    </li>
                    <li class="menu-item has-submenu">
                        <a href="javascript:void(0);">
                            <i data-feather="box" class="me-2"></i>
                            <span>Products</span>
                            <i data-feather="chevron-down" class="submenu-arrow"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="">All Products</a></li>
                            <li><a href="">Add Product</a></li>
                        </ul>
                    </li>
                </ul>
                <!-- /Main Menu -->
            
                <!-- Bottom Menu -->
                <ul class="sidebar-menu mt-auto">
                    <li class="menu-item">
                        <a href="">
                            <i class="ti ti-zoom-money fs-16 me-2"></i>
                            <span>Help Center</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="">
                            <i class="ti ti-settings fs-16 me-2"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
                <!-- /Bottom Menu -->
            
            
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
            </div>
    		<!-- /Sidebar -->
    		
    		<div class="page-wrapper">
    			@yield('content')
    		</div>

		</div>
		<!-- /Main Wrapper -->

		<!-- Payment Completed -->
		<div class="modal fade modal-default" id="payment-completed" aria-labelledby="payment-completed">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-body p-0">
						<div class="success-wrap text-center">
							<form action="https://dreamspos.dreamstechnologies.com/html/template/pos-5.html">
								<div class="icon-success bg-success text-white mb-2">
									<i class="ti ti-check"></i>
								</div>
								<h3 class="mb-2">Payment Completed</h3>
								<p class="mb-3">Do you want to Print Receipt for the Completed Order</p>
								<div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">
									<button type="button" class="btn btn-md btn-secondary" data-bs-toggle="modal" data-bs-target="#print-receipt">Print Receipt<i class="feather-arrow-right-circle icon-me-5"></i></button>
									<button type="submit" class="btn btn-md btn-primary">Next Order</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /Payment Completed -->

		<!-- Products -->
		<div class="modal fade modal-default pos-modal" id="products" aria-labelledby="products">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header d-flex align-items-center justify-content-between">
						<div class="d-flex align-items-center">
							<h5 class="me-4">Products</h5>
						</div>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card bg-light mb-3">
							<div class="card-body">
								<div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-3">
									<span class="badge bg-dark fs-12">Order ID : #45698</span>
									<p class="fs-16">Number of Products : 02</p>
								</div>								
								<div class="product-wrap h-auto">
									<div class="product-list bg-white align-items-center justify-content-between">
										<div class="d-flex align-items-center product-info" data-bs-toggle="modal" data-bs-target="#products">
											<a href="javascript:void(0);" class="img-bg">
												<img src="{{ asset('assets/img/products/pos-product-16.png')}}" alt="Products">
											</a>
											<div class="info">
												<h6><a href="javascript:void(0);">Red Nike Laser</a></h6>
												<p>Quantity : 04</p>
											</div>
										</div>
										<p class="text-teal fw-bold">$2000</p>
									</div>
									<div class="product-list bg-white align-items-center justify-content-between">
										<div class="d-flex align-items-center product-info" data-bs-toggle="modal" data-bs-target="#products">
											<a href="javascript:void(0);" class="img-bg">
												<img src="{{ asset('assets/img/products/pos-product-17.png')}}" alt="Products">
											</a>
											<div class="info">
												<h6><a href="javascript:void(0);">Iphone 11S</a></h6>
												<p>Quantity : 04</p>
											</div>
										</div>
										<p class="text-teal fw-bold">$3000</p>
									</div>
								</div>
							</div>	
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /Products -->


		


		<!-- Calculator -->
		<div class="modal fade pos-modal" id="calculator" tabindex="-1"  aria-hidden="true">
			<div class="modal-dialog modal-md modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-body p-0">
						<div class="calculator-wrap">
							<div class="p-3">
								<div class="d-flex align-items-center">
									<h3>Calculator</h3>
									<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">×</span>
									</button>
								</div>							
								<div>
									<input class="input" type="text" placeholder="0" readonly>
								</div>
							</div>
							<div class="calculator-body d-flex justify-content-between">
								<div class="text-center">
									<button class="btn btn-clear" onclick="if (!window.__cfRLUnblockHandlers) return false; clr()" data-cf-modified-198cbe834a0292f21b262338-="">C</button>
									<button class="btn btn-number" onclick="if (!window.__cfRLUnblockHandlers) return false; dis('7')" data-cf-modified-198cbe834a0292f21b262338-="">7</button>
									<button class="btn btn-number" onclick="if (!window.__cfRLUnblockHandlers) return false; dis('4')" data-cf-modified-198cbe834a0292f21b262338-="">4</button>
									<button class="btn btn-number" onclick="if (!window.__cfRLUnblockHandlers) return false; dis('1')" data-cf-modified-198cbe834a0292f21b262338-="">1</button>
									<button class="btn btn-number" onclick="if (!window.__cfRLUnblockHandlers) return false; dis(',')" data-cf-modified-198cbe834a0292f21b262338-="">,</button>
								</div>
								<div class="text-center">
									<button class="btn btn-expression" onclick="if (!window.__cfRLUnblockHandlers) return false; dis('https://dreamspos.dreamstechnologies.com/')" data-cf-modified-198cbe834a0292f21b262338-="">÷</button>
									<button class="btn btn-number" onclick="if (!window.__cfRLUnblockHandlers) return false; dis('8')" data-cf-modified-198cbe834a0292f21b262338-="">8</button>
									<button class="btn btn-number" onclick="if (!window.__cfRLUnblockHandlers) return false; dis('5')" data-cf-modified-198cbe834a0292f21b262338-="">5</button>
									<button class="btn btn-number" onclick="if (!window.__cfRLUnblockHandlers) return false; dis('2')" data-cf-modified-198cbe834a0292f21b262338-="">2</button>
									<button class="btn btn-number" onclick="if (!window.__cfRLUnblockHandlers) return false; dis('00')" data-cf-modified-198cbe834a0292f21b262338-="">00</button>									
								</div>
								<div class="text-center">
									<button class="btn btn-expression" onclick="if (!window.__cfRLUnblockHandlers) return false; dis('%')" data-cf-modified-198cbe834a0292f21b262338-="">%</button>
									<button class="btn btn-number" onclick="if (!window.__cfRLUnblockHandlers) return false; dis('9')" data-cf-modified-198cbe834a0292f21b262338-="">9</button>
									<button class="btn btn-number" onclick="if (!window.__cfRLUnblockHandlers) return false; dis('6')" data-cf-modified-198cbe834a0292f21b262338-="">6</button>
									<button class="btn btn-number" onclick="if (!window.__cfRLUnblockHandlers) return false; dis('3')" data-cf-modified-198cbe834a0292f21b262338-="">3</button>
									<button class="btn btn-number" onclick="if (!window.__cfRLUnblockHandlers) return false; dis('.')" data-cf-modified-198cbe834a0292f21b262338-="">.</button>									
								</div>
								<div class="text-center">
									<button class="btn btn-clear" onclick="if (!window.__cfRLUnblockHandlers) return false; back()" data-cf-modified-198cbe834a0292f21b262338-=""><i class="ti ti-backspace"></i></button>
									<button class="btn btn-expression" onclick="if (!window.__cfRLUnblockHandlers) return false; dis('*')" data-cf-modified-198cbe834a0292f21b262338-="">x</button>
									<button class="btn btn-expression" onclick="if (!window.__cfRLUnblockHandlers) return false; dis('-')" data-cf-modified-198cbe834a0292f21b262338-="">-</button>
									<button class="btn btn-expression" onclick="if (!window.__cfRLUnblockHandlers) return false; dis('+')" data-cf-modified-198cbe834a0292f21b262338-="">+</button>
									<button class="btn btn-clear" onclick="if (!window.__cfRLUnblockHandlers) return false; solve()" data-cf-modified-198cbe834a0292f21b262338-="">=</button>									
								</div>
							</div>							
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /Calculator -->

		<!-- Cash Register Details -->
		<div class="modal fade pos-modal" id="cash-register" tabindex="-1"  aria-hidden="true">
			<div class="modal-dialog modal-md modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						 <h5 class="modal-title">Cash Register Details</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="table-responsive">
							<table class="table table-striped border">
								<tr>
									<td>Cash in Hand</td>
									<td class="text-gray-9 fw-medium text-end">$45689</td>
								</tr>
								<tr>
									<td>Total Sale Amount</td>
									<td class="text-gray-9 fw-medium text-end">$565597.88</td>
								</tr>
								<tr>
									<td>Total Payment</td>
									<td class="text-gray-9 fw-medium text-end">$566867.97</td>
								</tr>
								<tr>
									<td>Cash Payment</td>
									<td class="text-gray-9 fw-medium text-end">$3355.84</td>
								</tr>
								<tr>
									<td>Total Sale Return</td>
									<td class="text-gray-9 fw-medium text-end">$1959</td>
								</tr>
								<tr>
									<td>Total Expense</td>
									<td class="text-gray-9 fw-medium text-end">$0</td>
								</tr>
								<tr>
									<td class="text-gray-9 fw-bold bg-secondary-transparent">Total Cash</td>
									<td class="text-gray-9 fw-bold text-end bg-secondary-transparent">$587130.97</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="modal-footer d-flex justify-content-end gap-2 flex-wrap">
						<button type="button" class="btn btn-md btn-primary" data-bs-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /Cash Register Details -->

		<!-- Today's Sale -->
		<div class="modal fade pos-modal" id="today-sale" tabindex="-1"  aria-hidden="true">
			<div class="modal-dialog modal-md modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						 <h5 class="modal-title">Today's Sale</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="table-responsive">
							<table class="table table-striped border">
								<tr>
									<td>Total Sale Amount</td>
									<td class="text-gray-9 fw-medium text-end">$565597.88</td>
								</tr>
								<tr>
									<td>Cash Payment</td>
									<td class="text-gray-9 fw-medium text-end">$3355.84</td>
								</tr>
								<tr>
									<td>Credit Card Payment</td>
									<td class="text-gray-9 fw-medium text-end">$1959</td>
								</tr>
								<tr>
									<td>Cheque Payment:</td>
									<td class="text-gray-9 fw-medium text-end">$0</td>
								</tr>
								<tr>
									<td>Deposit Payment</td>
									<td class="text-gray-9 fw-medium text-end">$565597.88</td>
								</tr>
								<tr>
									<td>Points Payment</td>
									<td class="text-gray-9 fw-medium text-end">$3355.84</td>
								</tr>
								<tr>
									<td>Gift Card Payment</td>
									<td class="text-gray-9 fw-medium text-end">$565597.88</td>
								</tr>
								<tr>
									<td>Scan & Pay</td>
									<td class="text-gray-9 fw-medium text-end">$3355.84</td>
								</tr>
								<tr>
									<td>Pay Later</td>
									<td class="text-gray-9 fw-medium text-end">$3355.84</td>
								</tr>
								<tr>
									<td>Total Payment</td>
									<td class="text-gray-9 fw-medium text-end">$565597.88</td>
								</tr>
								<tr>
									<td>Total Sale Return</td>
									<td class="text-gray-9 fw-medium text-end">$565597.88</td>
								</tr>
								<tr>
									<td>Total Expense:</td>
									<td class="text-gray-9 fw-medium text-end">$565597.88</td>
								</tr>
								<tr>
									<td class="text-gray-9 fw-bold bg-secondary-transparent">Total Cash</td>
									<td class="text-gray-9 fw-bold text-end bg-secondary-transparent">$587130.97</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="modal-footer d-flex justify-content-end gap-2 flex-wrap">
						<button type="button" class="btn btn-md btn-primary" data-bs-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /Today's Sale -->

		<!-- Today's Profit -->
		<div class="modal fade pos-modal" id="today-profit" tabindex="-1"  aria-hidden="true">
			<div class="modal-dialog modal-md modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						 <h5 class="modal-title">Today's Profit</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row justify-content-center g-3 mb-3">
							<div class="col-lg-4 col-md-6 d-flex">
								<div class="border border-success bg-success-transparent br-8 p-3 flex-fill">
									<p class="fs-16 text-gray-9 mb-1">Total Sale</p>
									<h3 class="text-success">$89954</h3>
								</div>
							</div>
							<div class="col-lg-4 col-md-6 d-flex">
								<div class="border border-danger bg-danger-transparent br-8 p-3 flex-fill">
									<p class="fs-16 text-gray-9 mb-1">Expense</p>
									<h3 class="text-danger">$89954</h3>
								</div>
							</div>
							<div class="col-lg-4 col-md-6 d-flex">
								<div class="border border-info bg-info-transparent br-8 p-3 flex-fill">
									<p class="fs-16 text-gray-9 mb-1">Total Profit	</p>
									<h3 class="text-info">$2145</h3>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table table-striped border">
								<tr>
									<td>Product Revenue</td>
									<td class="text-gray-9 fw-medium text-end">$565597.88</td>
								</tr>
								<tr>
									<td>Product Cost</td>
									<td class="text-gray-9 fw-medium text-end">$3355.84</td>
								</tr>
								<tr>
									<td>Expense</td>
									<td class="text-gray-9 fw-medium text-end">$1959</td>
								</tr>
								<tr>
									<td>Total Stock Adjustment</td>
									<td class="text-gray-9 fw-medium text-end">$0</td>
								</tr>
								<tr>
									<td>Deposit Payment</td>
									<td class="text-gray-9 fw-medium text-end">$565597.88</td>
								</tr>
								<tr>
									<td>Total Purchase Shipping Cost</td>
									<td class="text-gray-9 fw-medium text-end">$3355.84</td>
								</tr>
								<tr>
									<td>Total Sell Discount</td>
									<td class="text-gray-9 fw-medium text-end">$565597.88</td>
								</tr>
								<tr>
									<td>Total Sell Return</td>
									<td class="text-gray-9 fw-medium text-end">$3355.84</td>
								</tr>
								<tr>
									<td>Closing Stock</td>
									<td class="text-gray-9 fw-medium text-end">$3355.84</td>
								</tr>
								<tr>
									<td>Total Sales</td>
									<td class="text-gray-9 fw-medium text-end">$565597.88</td>
								</tr>
								<tr>
									<td>Total Sale Return</td>
									<td class="text-gray-9 fw-medium text-end">$565597.88</td>
								</tr>
								<tr>
									<td>Total Expense</td>
									<td class="text-gray-9 fw-medium text-end">$565597.88</td>
								</tr>
								<tr>
									<td class="text-gray-9 fw-bold bg-secondary-transparent">Total Cash</td>
									<td class="text-gray-9 fw-bold text-end bg-secondary-transparent">$587130.97</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="modal-footer d-flex justify-content-end gap-2 flex-wrap">
						<button type="button" class="btn btn-md btn-primary" data-bs-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /Today's Profit -->
		
		<!-- jQuery -->
		<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

		<!-- Feather Icon JS -->
		<script src="{{ asset('assets/js/feather.min.js')}}" type="198cbe834a0292f21b262338-text/javascript"></script>

		<!-- Slimscroll JS -->
		<script src="{{ asset('assets/js/jquery.slimscroll.min.js')}}" type="198cbe834a0292f21b262338-text/javascript"></script>
		
		<!-- Bootstrap Core JS -->
		<script src="{{ asset('assets/js/bootstrap.bundle.min.js')}}" type="198cbe834a0292f21b262338-text/javascript"></script>

		<!-- Chart JS -->
		<script src="{{ asset('assets/plugins/apexchart/apexcharts.min.js')}}" type="198cbe834a0292f21b262338-text/javascript"></script>
		<script src="{{ asset('assets/plugins/apexchart/chart-data.js')}}" type="198cbe834a0292f21b262338-text/javascript"></script>

		<!-- Datatable JS -->
		<script src="{{ asset('assets/js/jquery.dataTables.min.js')}}" type="198cbe834a0292f21b262338-text/javascript"></script>
		<script src="{{ asset('assets/js/dataTables.bootstrap5.min.js')}}" type="198cbe834a0292f21b262338-text/javascript"></script>

		<!-- Daterangepikcer JS -->
		<script src="{{ asset('assets/js/moment.min.js')}}" type="198cbe834a0292f21b262338-text/javascript"></script>
		<script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js')}}" type="198cbe834a0292f21b262338-text/javascript"></script>
		<script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js')}}" type="198cbe834a0292f21b262338-text/javascript"></script>

		<!-- Owl JS -->
		<script src="{{ asset('assets/plugins/owlcarousel/owl.carousel.min.js')}}" type="198cbe834a0292f21b262338-text/javascript"></script>

		<!-- Select2 JS -->
		<script src="{{ asset('assets/plugins/select2/js/select2.min.js')}}" type="198cbe834a0292f21b262338-text/javascript"></script>

		<!-- Sticky-sidebar -->
		<script src="{{ asset('assets/plugins/theia-sticky-sidebar/ResizeSensor.js')}}" type="198cbe834a0292f21b262338-text/javascript"></script>
		<script src="{{ asset('assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js')}}" type="198cbe834a0292f21b262338-text/javascript"></script>

		<!-- Color Picker JS -->
		<script src="{{ asset('assets/plugins/%40simonwep/pickr/pickr.es5.min.js')}}" type="198cbe834a0292f21b262338-text/javascript"></script>
		
		<!-- Custom JS -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
		<script src="{{ asset('assets/js/theme-colorpicker.js')}}" type="198cbe834a0292f21b262338-text/javascript"></script>
		<script src="{{ asset('assets/js/calculator.js')}}" type="198cbe834a0292f21b262338-text/javascript"></script>
		<script src="{{ asset('assets/js/script.js')}}" type="198cbe834a0292f21b262338-text/javascript"></script>
		<script src="{{ asset('assets/js/ajax.js')}}"></script>

		@stack('js')
	
	<script src="{{ asset('assets/cdn-cgi/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js')}}" data-cf-settings="198cbe834a0292f21b262338-|49" defer></script><script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"rayId":"93cec8d31bfb786e","version":"2025.4.0-1-g37f21b1","serverTiming":{"name":{"cfExtPri":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}},"token":"3ca157e612a14eccbb30cf6db6691c29","b":1}' crossorigin="anonymous"></script>
</body>

<!-- Mirrored from dreamspos.dreamstechnologies.com/html/template/pos-5.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 09 May 2025 05:29:33 GMT -->
</html>