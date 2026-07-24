@extends('layouts.app')
@section('content')
@push('css')
<style>
    .image-upload .upload-label {
        cursor: pointer;
        display: block;
    }
    
    .image-upload .image-uploads,
    .image-upload .image-uploads * {
        cursor: pointer;
    }

</style>
@endpush

<div class="content">
	<div class="page-header">
		<div class="add-item d-flex">
			<div class="page-title">
				<h4 class="fw-bold">Setting</h4>
				<h6>Manage your Setting</h6>
			</div>
		</div>

	</div>
	<!-- /product list -->
	
	<div class="card flex-fill mb-0">
		<div class="card-header">
			<h4 class="fs-18 fw-bold">Company Settings</h4>
		</div>
		<div class="card-body">
			<form action="{{ route('settings.update',[$item->id])}}" method="post" id="ajax_form">
		      @method('PATCH')
		      @csrf
				<div class="border-bottom mb-3">
					<div class="card-title-head">
						<h6 class="fs-16 fw-bold mb-2">
							<span class="fs-16 me-2"><i class="ti ti-building"></i></span> 
							Company Information
						</h6>
					</div>
					<div class="row">
						<div class="col-xl-4 col-lg-6 col-md-4">
							<div class="mb-3">
								<label class="form-label">
									Company Name  <span class="text-danger">*</span>
								</label>
								<input type="text" class="form-control" name="title" value="{{ $item->title}}">
							</div>
						</div>
						<div class="col-xl-4 col-lg-6 col-md-4">
							<div class="mb-3">
								<label class="form-label">
									Company Email Address  <span class="text-danger">*</span>
								</label>
								<input type="email" class="form-control" name="email" value="{{ $item->email}}">
							</div>
						</div>
						<div class="col-md-4">
							<div class="mb-3">
								<label class="form-label">
									Phone Number <span class="text-danger">*</span>
								</label>
								<input type="text" class="form-control" name="phone" value="{{ $item->phone}}">
							</div>
						</div>

					</div>
				</div>
				<div class="border-bottom mb-3 pb-3">
					<div class="card-title-head">
						<h6 class="fs-16 fw-bold mb-2">
							<span class="fs-16 me-2"><i class="ti ti-photo"></i></span> 
							Company Images
						</h6>
					</div>
					<div class="row align-items-center gy-3">
						
						<div class="col-xl-9">
							<div class="row gy-3 align-items-center">
								<div class="col-lg-4">
									<div class="logo-info">
										<h6 class="fw-medium">Favicon</h6>
										<p>Upload Favicon of your Company</p>
									</div>
								</div>
								<div class="col-lg-8">
									<div class="profile-pic-upload mb-0 justify-content-lg-end">
										<div class="new-employee-field">
											<div class="mb-0">
												<div class="image-upload mb-0">
													<input type="file" name="favicon">
													<div class="image-uploads">
														<h4><i class="ti ti-upload me-1"></i>Upload Image</h4>
													</div>
												</div>
												<span class="mt-1">Recommended size is 450px x 450px. Max size 5mb.</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3">
							<div class="new-logo ms-xl-auto">
								<a href="#">
									<img src="{{ getImage('favicon', $item->favicon) }}" alt="Logo">
									<span><i class="ti ti-x"></i></span>
								</a>
							</div>
						</div>
						<div class="col-xl-9">
							<div class="row gy-3 align-items-center">
								<div class="col-lg-4">
									<div class="logo-info">
										<h6 class="fw-medium">Company Logo</h6>
										<p>Upload Logo of your Company</p>
									</div>
								</div>
								<div class="col-lg-8">
									<div class="profile-pic-upload mb-0 justify-content-lg-end">
										<div class="new-employee-field">
											<div class="mb-0">
												<div class="image-upload mb-0">
													<input type="file" name="logo">
													<div class="image-uploads">
														<h4><i class="ti ti-upload me-1"></i>Upload Image</h4>
													</div>
												</div>
												<span class="mt-1">Recommended size is 450px x 450px. Max size 5mb.</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							
						</div>
						<div class="col-xl-3">
							<div class="new-logo ms-xl-auto">
								<a href="#">
									<img src="{{ getImage('settings',$item->logo)}}" alt="Logo">
									<span><i class="ti ti-x"></i></span>
								</a>
							</div>
						</div>
						
					</div>
				</div>
				<div class="company-address">
					<div class="card-title-head">
						<h6 class="fs-16 fw-bold mb-2">
							<span class="fs-16 me-2"><i class="ti ti-map-pin"></i></span> 
							Address Information
						</h6>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="mb-3">
								<label class="form-label">
									Address <span class="text-danger">*</span>
								</label>
								<textarea class="form-control" name="address">{{ $item->address}}</textarea>
							</div>
						</div>
						
						
						<div class="col-md-4">
							<div class="mb-3">
								<label class="form-label"> Whats App Number	</label>
								<input type="text" value="{{  $item->whats_app_no }}" class="form-control" name="whats_app_no">
							</div>
						</div>
						
						
						<div class="col-md-4">
							<div class="mb-3">
								<label class="form-label"> Facebook Link	</label>
								<input type="text" value="{{  $item->facebook_link }}" class="form-control" name="facebook_link">
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="mb-3">
								<label class="form-label"> Youtube Link	</label>
								<input type="text" value="{{  $item->youtube_link }}" class="form-control" name="youtube_link">
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="mb-3">
								<label class="form-label"> Instagram Link	</label>
								<input type="text" value="{{  $item->instagram_link }}" class="form-control" name="instagram_link">
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="mb-3">
								<label class="form-label"> Linkedin Link	</label>
								<input type="text" value="{{  $item->linkedin_link }}" class="form-control" name="linkedin_link">
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="mb-3">
								<label class="form-label"> Pinterest Link	</label>
								<input type="text" value="{{  $item->pinterest_link }}" class="form-control" name="pinterest_link">
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="mb-3">
								<label class="form-label"> Tiktok Link	</label>
								<input type="text" value="{{  $item->tiktok_link }}" class="form-control" name="tiktok_link">
							</div>
						</div>
						
						
					</div>
				</div>
				<div class="text-end settings-bottom-btn mt-0">
					<button type="submit" class="btn btn-primary">Save Changes</button>
				</div>
			</form>
		</div>
	</div>

	<!-- /product list -->
</div>
@endsection

@push('js')

@endpush