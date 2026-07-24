@extends('layouts.app')
@push('css')
@endpush
@section('content')
<div class="content">
	<div class="page-header">
		<div class="add-item d-flex">
			<div class="page-title">
				<h4 class="fw-bold">FAQ Page</h4>
				<h6>Manage your FAQ Page</h6>
			</div>
		</div>
		<ul class="table-top-head">
			<!--<li>-->
			<!--	<a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img src="assets/img/icons/pdf.svg" alt="img"></a>-->
			<!--</li>-->
			<!--<li>-->
			<!--	<a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img src="assets/img/icons/excel.svg" alt="img"></a>-->
			<!--</li>-->
			<!--<li>-->
			<!--	<a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>-->
			<!--</li>-->
			<li>
				<a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
			</li>
		</ul>
		<div class="page-btn">
			<a href="{{ route('faq_pages.create')}}" class="btn btn-primary btn_modal"><i class="ti ti-circle-plus me-1"></i>Add FAQ Page</a>
		</div>
	</div>
	<!-- /product list -->
	<div class="card">
		<div class="card-header">
    		<div class="d-flex align-items-center flex-wrap gap-3">
                <!-- Search (70%) -->
                
                <div class="flex-grow-1 search-wrapper">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="ti ti-search"></i>
                        </span>
                        <input
                            type="text"
                            id="search"
                            class="form-control"
                            placeholder="Search..."
                        >
                    </div>
                </div>
            </div>
		</div>	
		<div class="card-body p-0" id="data">										
			
		</div>
	</div>
	<!-- /product list -->
</div>
@endsection

@push('js')
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>


<script type="text/javascript">
  $(document).ready(function () {
    
    getData();
    $('#search').keyup(function(){
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
    
        $('#member_data').html('');
        $.ajax({
            url: '{{ route("faq_pages.index")}}?page='+page,
            type: 'GET',
            data:{q},
            dataType: 'html',
            success: function(data) {
                $('#data').html(data);
            }
        });
    }

    $('#common_modal').on('shown.bs.modal', function () {



	    ClassicEditor

	        .create( document.querySelector( '#editor' ),{

	            ckfinder: {

	                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",

	            }

	        })

	        .catch( error => {

	              

	        } );
	    });
  });
</script>
@endpush