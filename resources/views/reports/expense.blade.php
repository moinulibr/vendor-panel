@extends('layouts.app')
@section('content')

<div class="content">
	<div class="page-header">
		<div class="add-item d-flex">
			<div class="page-title">
				<h4>{{ ucfirst($type)}} Report</h4>
				<h6>View Reports of {{ ucfirst($type)}}</h6>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body pb-1">
			<div class="row align-items-end">
				<div class="col-12">
					<div class="row">
					    
					    <div class="col-12 col-md-6 col-lg-4">
					        <div class="mb-3">
								<label class="form-label">Search</label>
                                <div class="search-set w-100">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">
                                            <i class="ti ti-search"></i>
                                        </span>
                                        <input type="text" id="search" class="form-control" placeholder="Search Reference...">
                                    </div>
                                </div>
                            </div>
                        </div>
            
						<div class="col-12 col-md-6 col-lg-4">
							<div class="mb-3">
								<label class="form-label">Choose Date</label>
								<div class="input-icon-start position-relative">
									<input type="text" class="form-control bookingrange" placeholder="dd/mm/yyyy - dd/mm/yyyy">
									<span class="input-icon-left">
										<i class="ti ti-calendar"></i>
									</span>
								</div>
							</div>
						</div>
						
						<div class="col-12 col-md-6 col-lg-2">
							<div class="mb-3">
								<label class="form-label"> Category </label>
								<select class="select" id="category_id">
									<option value="">All</option>
									
									@foreach($cats as $cat)
									<option value="{{ $cat->id}}">{{ $cat->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						
						<div class="col-12 col-md-6 col-lg-2">
							<div class="mb-3">
								<label class="form-label"> Payment Status</label>
								<select class="select" id="payment_status">
									<option value="">All</option>
								
									<option value="paid"> Paid</option>
									<option value="due"> Due</option>
									<option value="partial"> Partial</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /product list -->
	<div class="card no-search">
		<div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
			<div>
				<h4>Expense Report</h4>
			</div>
		</div>
		<div class="card-body p-0" id="data">
			
		</div>
	</div>
	<!-- /product list -->
</div>
@endsection

@push('js')
<script type="text/javascript">
  $(document).ready(function () {
    

    $('#search').keyup(function(){
        getData();
    });

    $('#search_btn').click(function(){
        getData();
    });

    $('#category_id, #payment_status').change(function(){
        getData();
    });
    
    
    $(document).on('bookingRangeChanged', function (e, data) {
        getData(1);
    });
    
    
    $(document).on('click', ".pagination a", function(e) {
        e.preventDefault();

        $('li').removeClass('active');
        $(this).parent('li').addClass('active');

        var page = $(this).attr('href').split('page=')[1];
        getData(page);
    });
  
    function getData(page=null){
        let q=$('#search').val();
        let date=$('.bookingrange').val();
        let category_id=$('#category_id').val();
        let payment_status=$('#payment_status').val();
        let type='{{$type}}';
        $('#data').html('');
        $.ajax({
            url: '{{ route("reports.getExpense")}}?page='+page,
            type: 'GET',
            data:{q,date,category_id, payment_status,type},
            dataType: 'html',
            success: function(data) {
                $('#data').html(data);
            }
        });
    }
  });
</script>
@endpush