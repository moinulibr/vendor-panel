@extends('layouts.app')
@section('content')
<div class="content">
	<div class="page-header">
		<div class="add-item d-flex">
			<div class="page-title">
				<h4>Online Orders</h4>
				<h6>Manage Your Online orders</h6>
			</div>
		</div>
	</div>
	
	<!-- /product list -->
	
	<div class="card">
        <div class="card-body pb-2">
    
            <div class="row g-3 align-items-end">
    
                <!-- Delete Selected -->
                <!--<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12">-->
                <!--    <a class="btn btn-sm btn-danger w-100 selected_delete">-->
                <!--        Delete Selected-->
                <!--    </a>-->
                <!--</div>-->
    
                <!-- Search -->
                <div class="col-xl-5 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label class="form-label">Search</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="ti ti-search"></i>
                        </span>
                        <input type="text" id="search" class="form-control" placeholder="Search...">
                    </div>
                </div>
    
                <!-- Date -->
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label class="form-label">Choose Date</label>
                    <div class="position-relative">
                        <input
                            type="text"
                            class="form-control ps-5 bookingrange"
                            placeholder="dd/mm/yyyy - dd/mm/yyyy"
                        >
                        <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                            <i class="ti ti-calendar"></i>
                        </span>
                    </div>
                </div>
    
                <!-- Shipping Status -->
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label class="form-label">Shipping Status</label>
                    <select class="form-select" id="shipping_status">
                        <option value="">All</option>
                        @foreach(getStatusList() as $i => $status)
                            <option value="{{ $i }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
    
                <!-- Payment Status -->
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12">
                    <label class="form-label">Payment Status</label>
                    <select class="form-select" id="payment_status">
                        <option value="">All</option>
                        <option value="paid">Paid</option>
                        <option value="due">Due</option>
                        <option value="partial">Partial</option>
                    </select>
                </div>
    
            </div>
    
        </div>
    </div>

	<div class="card">

		<div class="card-body p-0 sell_data">
			
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

    $('#type_id, #shipping_status, #payment_status').change(function(){
        getData();
    });
    
    

    $(document).on('bookingRangeChanged', function (e, data) {
        
        
        getData(1);
    
    });

    $(document).on('click', 'a.selected_delete', function(e){
        e.preventDefault();
        var url = '{{ route("sells.bulkDelete")}}';
    
        var product = $('input.select_item:checked').map(function(){
          return $(this).val();
        });
        var ids=product.get();
        
        if(ids.length ==0){
            toastr.error('Please Select A Invoice First !');
            return ;
        }
        
        $.ajax({
           type:'POST',
           url:url,
           headers: {
    		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    		    },
           data:{ids},
           success:function(res){
               if(res.status==true){
                toastr.success(res.msg);
                getData();
                
            }else if(res.status==false){
                toastr.error(res.msg);
            }
           }
        });
    
    });

    
    
    $(document).on('click', ".pagination a", function(e) {
        e.preventDefault();

        $('li').removeClass('active');
        $(this).parent('li').addClass('active');

        var page = $(this).attr('href').split('page=')[1];
        getData(page);
    });
  
    function getData(page=null){
        
        let date=$('.bookingrange').val();
        
        let q=$('#search').val();
    
        let type_id=$('#type_id').val();
        let shipping_status=$('#shipping_status').val();
        let payment_status=$('#payment_status').val();
        let online=1;
    
        $('.sell_data').html('');
        $.ajax({
            url: '{{ route("sells.index")}}?page='+page,
            type: 'GET',
            data:{q,date,type_id,online,shipping_status, payment_status},
            dataType: 'html',
            success: function(data) {
                $('.sell_data').html(data);
            }
        });
    }
  });
</script>

@endpush