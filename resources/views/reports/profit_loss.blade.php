@extends('layouts.app')
@section('content')

<div class="content">
	<div class="page-header">
		<div class="add-item d-flex">
			<div class="page-title">
				<h4>Profit / Loss Report</h4>
				<h6>View Reports of Profit / Loss Report</h6>
			</div>
		</div>
	</div>
	<div class="d-flex align-items-center justify-content-end">
		<div class="mb-3 me-3">
			<div class="input-icon-start position-relative">
				<input type="text" class="form-control date-range bookingrange" placeholder="dd/mm/yyyy - dd/mm/yyyy">
				<span class="input-icon-left">
					<i class="ti ti-calendar"></i>
				</span>
			</div>
		</div>
		
	</div>
	
	<div class="container-fluid mt-3 profit_data">

        <!-- Summary Row -->
        
    </div>

</div>
				

@endsection

@push('js')


<script type="text/javascript">
  $(document).ready(function () {
    
     $(document).on('bookingRangeChanged', function (e, data) {
        
        
        getData(1);
    
    });
    
    $('#search').change(function(){
        getData();
    });

    $('#search_btn').click(function(){
        getData();
    });

    $('#type_id').change(function(){
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
        let q=$('#search').val();
        let date=$('.bookingrange').val();

        let type_id=$('#type_id').val();
    
        $('.profit_data').html('');
        $.ajax({
            url: '{{ route("reports.profitLoss")}}?page='+page,
            type: 'GET',
            data:{q,date},
            dataType: 'html',
            success: function(data) {
                $('.profit_data').html(data);
            }
        });
    }
  });
</script>
@endpush