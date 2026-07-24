@extends('layouts.app')
@section('content')
<div class="content">
	<div class="page-header">
		<div class="add-item d-flex">
			<div class="page-title">
				<h4>Products Wise Sales Report</h4>
				<h6>View Reports of Products</h6>
			</div>
		</div>
		
	</div>

	<form id="salesFilterForm">
        <div class="row align-items-end">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-3">
						<div class="mb-3">
							<label class="form-label"> Product Search</label>
								<input type="text" class="form-control" placeholder="Search here" id="search">
								
						</div>
					</div>
					
                    <!-- Date Range -->
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Choose Date</label>
                            <div class="input-icon-start position-relative">
                                <input type="text" class="form-control date-range bookingrange"  placeholder="dd/mm/yyyy - dd/mm/yyyy">
                                <span class="input-icon-left">
                                    <i class="ti ti-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
    
                    
					<div class="col-md-3">
						<div class="mb-3">
							<label class="form-label">Category</label>
							<select class="select" id="category_id">
								<option value="">All</option>
								@foreach($cats as $cat)
								<option value="{{ $cat->id }}"> {{ $cat->name }} </option>
								@endforeach
							</select>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="mb-3">
							<label class="form-label">Brand</label>
							<select class="select" id="brand_id">
								<option value="">All</option>
								@foreach($brands as $brand)
								<option value="{{ $brand->id }}"> {{ $brand->name }} </option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="col-md-3">
						<div class="mb-3">
							<label class="form-label">Locations</label>
							<select class="select" id="location_id">
								<option value="">All</option>
								@foreach($locations as $location)
								<option value="{{ $location->id }}"> {{ $location->name }} </option>
								@endforeach
							</select>
						</div>
					</div>
    
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
        
        let url = '{{ route("reports.productReport") }}';

        $('.sell_data').html('');

        $.ajax({
            url: url,
            type: 'GET',
            data:{date,search,category_id,location_id, brand_id,page},
            dataType: 'html',
            success: function(data) {
                $('.sell_data').html(data);
            }
        });
    }
  });
</script>

@endpush