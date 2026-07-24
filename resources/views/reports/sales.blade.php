@extends('layouts.app')
@section('content')
<div class="content">
	<div class="page-header">
		<div class="add-item d-flex">
			<div class="page-title">
				<h4>Product Sales Report</h4>
				<h6>Manage your Product Sales report</h6>
			</div>
		</div>
		<ul class="table-top-head">
			<li class="me-2">
				<a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
			</li>
			<li class="me-2">
				<a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
			</li>
		</ul>
	</div>
	<div class="row d-none">
		<div class="col-xl-3 col-sm-6 col-12 d-flex">
			<div class="card border border-success sale-widget flex-fill">
				<div class="card-body d-flex align-items-center">
					<span class="sale-icon bg-success text-white">
						<i class="ti ti-align-box-bottom-left-filled fs-24"></i>
					</span>
					<div class="ms-2">
						<p class="fw-medium mb-1">Total Amount</p>
						<div>
							<h3>$4,56,000</h3>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-sm-6 col-12 d-flex">
			<div class="card border border-info sale-widget flex-fill">
				<div class="card-body d-flex align-items-center">
					<span class="sale-icon bg-info text-white">
						<i class="ti ti-align-box-bottom-left-filled fs-24"></i>
					</span>
					<div class="ms-2">
						<p class="fw-medium mb-1">Total Paid</p>
						<div>
							<h3>$2,56,42</h3>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-sm-6 col-12 d-flex">
			<div class="card border border-orange sale-widget flex-fill">
				<div class="card-body d-flex align-items-center">
					<span class="sale-icon bg-orange text-white">
						<i class="ti ti-moneybag fs-24"></i>
					</span>
					<div class="ms-2">
						<p class="fw-medium mb-1">Total Unpaid</p>
						<div>
							<h3>$1,52,45</h3>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-sm-6 col-12 d-flex">
			<div class="card border border-danger sale-widget flex-fill">
				<div class="card-body d-flex align-items-center">
					<span class="sale-icon bg-danger text-white">
						<i class="ti ti-alert-circle-filled fs-24"></i>
					</span>
					<div class="ms-2">
						<p class="fw-medium mb-1">Overdue</p>
						<div>
							<h3>$2,56,12</h3>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <form id="salesFilterForm">
        <div class="row g-3 align-items-end">
    
            {{-- Product Search --}}
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="mb-3">
                    <label class="form-label">Product Search</label>
                    <input type="text" class="form-control" placeholder="Search here" id="search">
                </div>
            </div>
    
            {{-- Date Range --}}
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="mb-3">
                    <label class="form-label">Choose Date</label>
                    <div class="input-icon-start position-relative">
                        <input type="text"
                               class="form-control date-range bookingrange"
                               placeholder="dd/mm/yyyy - dd/mm/yyyy">
                        <span class="input-icon-left">
                            <i class="ti ti-calendar"></i>
                        </span>
                    </div>
                </div>
            </div>
    
            {{-- Category --}}
            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select class="select" id="category_id">
                        <option value="">All</option>
                        @foreach($cats as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
    
            {{-- Brand --}}
            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                <div class="mb-3">
                    <label class="form-label">Brand</label>
                    <select class="select" id="brand_id">
                        <option value="">All</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
    
            {{-- Location --}}
            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                <div class="mb-3">
                    <label class="form-label">Location</label>
                    <select class="select" id="location_id">
                        <option value="">All</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
    
        </div>
    </form>


	
	<div class="card no-search">
		<div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
			<div>
				<h4>Sales Report</h4>
			</div>
		</div>
		<div class="card-body p-0 sell_data">
			
		</div>
	</div>
	<!-- /product list -->
</div>

@endsection

@push('js')
<script type="text/javascript">
  $(document).ready(function () {
    $(document).on('bookingRangeChanged', function (e, data) {
        
        
        getData(1);
    
    });
    
    $('#search').keyup(function(){
        getData();
    });
    

    $('#brand_id, #category_id, #location_id').change(function(){
        getData();
    });

    $(document).on('click', ".pagination a", function(e) {
        e.preventDefault();

        $('li').removeClass('active');
        $(this).parent('li').addClass('active');

        var page = $(this).attr('href').split('page=')[1];
        getData(page);
    });

    function getData(page = 1){
        let date_range = $('#date_range').val();
        let brand_id   = $('#brand_id').val();
        let category_id = $('#category_id').val();
        let location_id = $('#location_id').val();
        let search = $('#search').val();
        let date=$('.bookingrange').val();
        
        let url = '{{ route("reports.getSales") }}';

        $('.sell_data').html('');

        $.ajax({
            url: url,
            type: 'GET',
            data:{date,search,category_id,location_id, brand_id, page},
            dataType: 'html',
            success: function(data) {
                $('.sell_data').html(data);
            }
        });
    }
  });
</script>

@endpush