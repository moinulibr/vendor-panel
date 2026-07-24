@extends('layouts.app')
@section('content')
<div class="content">
	<div>
		<div class="page-header">
			<div class="add-item d-flex">
				<div class="page-title">
					<h4>Inventory</h4>
					<h6>View Reports of Inventory</h6>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-body pb-1">
				
				<div class="row align-items-end">
					<div class="col-lg-10">
						<div class="row">
							<div class="col-md-3">
								<div class="mb-3">
									<label class="form-label"> Product Search</label>
										<input type="text" class="form-control" placeholder="Search here" id="search">
										
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
			</div>
		</div>
		
		<div class="card no-search">

			<div class="card-body p-0" id="data">
				
			</div>
		</div>
		<!-- /product list -->
	</div>
</div>
@endsection

@push('js')


<script type="text/javascript">
  $(document).ready(function () {
    
    getData();
    $('#search').keyup(function(){
        getData();
    });

    $('#search_btn').click(function(){
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
  
    function getData(page=null){
        let search=$('#search').val();

        let brand_id=$('#brand_id').val();
        let category_id=$('#category_id').val();
        let location_id=$('#location_id').val();
    
        $('#data').html('');
        $.ajax({
            url: '{{ route("reports.productSTock")}}?page='+page,
            type: 'GET',
            data:{search,brand_id, category_id,location_id},
            dataType: 'html',
            success: function(data) {
                $('#data').html(data);
            }
        });
    }
  });
</script>
@endpush