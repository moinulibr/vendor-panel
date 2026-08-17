window.myAppFunctions = window.myAppFunctions || {};

$(document).ready(function(){
    
    $('#common_modal').on('shown.bs.modal', function () {
        $(this).find('.select2').select2({
            dropdownParent: $('#common_modal'),
            width: '100%'
        });
        
        
        $('.transaction_pay_amount').on('input', function() {
      
            // Get the values and convert them to numbers
            var amountPaid = parseFloat($(this).val()) || 0;
            var totalDue = parseFloat($('.total_due_amount').text()) || 0;
    
            // Logic: If paid amount is less than total due (and more than 0), show the date field
            if (amountPaid > 0 && amountPaid < totalDue) {
      
                $('.next_payment_wrapper').fadeIn(); // Smoothly shows the div
            } else {
                $('.next_payment_wrapper').fadeOut(); // Hides it if fully paid or empty
                $('.next_payment_date').val(''); // Hides it if fully paid or empty
            }
        });
        

    
    });

	$(document).find('.amount').each(function() {
        let price=$(this).text();
        price=Number(price).toLocaleString('en');
        $(this).text(price);
    
    });


    $(document).on('click','.btn_modal', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        console.log(url);
        $.ajax({
           type:'GET',
           url:url,
           data:{},
           success:function(res){
              $('div#common_modal').html(res).modal('show');
           }
        });
    });


    $(document).on('click','.btn_print', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
           type:'GET',
           url:url,
           data:{},
           success:function(res){
              $('div#print-receipt').html(res);
                var printContents = $('#print-receipt').html();
                var printWindow = window.open('', '', 'height=600,width=800');
                printWindow.document.write('<html><head><title>Print Modal</title>');
                printWindow.document.write(`<link rel="stylesheet" href='{{ asset("assets/css/style.css")}}'>`); // optional
                printWindow.document.write('</head><body>');
                printWindow.document.write(printContents);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
                printWindow.close();
                
           }
        });
    });




    $(document).on('submit','form#ajax_form', function(e) {
        e.preventDefault(); 
        $('span.textdanger').text('');
        var url=$(this).attr('action');
        var method=$(this).attr('method');
        var formData = new FormData($(this)[0]);
        
        let button=$(this).find('[type="submit"]');
        button.attr("disabled", "disabled");
        $.ajax({
            type: method,
            url: url,
            data: formData,
            async: false,
            processData: false,
            contentType: false,
            success: function(res) {

                if(res.status==true){    
                    let funcName = res.function;
                    let pageNo = 1; 
                    if ($('.pagination li.active').length > 0) {
                        pageNo = $('.pagination li.active').text().trim();
                    } else if ($('.pagination .active').length > 0) {
                        pageNo = $('.pagination .active').text().trim();
                    }
                    //console.log("res.isNew - ",res.isNew);
                    if(res.isNew == 'yes'){
                        pageNo = 1;
                    }

                    swal(res.msg);
                    $('div#common_modal').modal('hide');
                    if(res.function && window.myAppFunctions[funcName]) {
                        window.myAppFunctions[funcName](pageNo); 
                    }
                    else if(res.function){    
                        jQuery("input#search").keyup();
                    }
                    else if(res.url){
                        setTimeout(function() { 
                            document.location.href = res.url;
                        }, 2000);
                        
                    }else{
                        setTimeout(function() { 
                            window.location.reload();
                        }, 1000);
                        
                    }
                }else if(res.status==false){
                    button.prop("disabled", false);
                    swal(res.msg);
                }
                
            },
            error:function (response){
                button.prop("disabled", false);
                $.each(response.responseJSON.errors,function(field_name,error){
                    $(document).find('[name='+field_name+']').after('<span style="color:red">' +error+ '</span>')
                  
                })
            }
        });
    });


    // ajax request for delete data
    $(document).on('click','a.delete', function(e) {
        var form=$(this);
        e.preventDefault(); 
        swal({
          title: "Are you sure?",
          text: "You want To Delete!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#006400",
          confirmButtonText: "Yes, do it!",
          cancelButtonText: "No, cancel plz!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function(isConfirm){
          if (isConfirm) {
    
            var url=$(form).attr('href');
    
            $.ajax({
                type: 'DELETE',
                url: url,
                headers: {
    		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    		    },
                success: function(res) {
                    
                    if(res.status==true){
                        swal(res.msg);
                        $('#common_modal').modal('hide');
                        if(res.url){
                            document.location.href = res.url;
                        }
                        //else{
                        //     window.location.reload();
                        // }
                        jQuery("input#search").keyup();
                    }else if(res.status==false){
                        swal(res.msg);
                    }
                    
                },
                error:function (response){
                    
                }
            });
          } else {
            swal("Cancelled", "Your imaginary file is safe :)", "error");
          }
        });
    });
    
    $(document).on('click', '#selldeleteSelectedBtn',function () {
        let ids = [];
        
        $('.select_item:checked').each(function () {
            ids.push($(this).val());
        });
        
        if(ids.length === 0){
            alert("No item selected");
            return;
        }
        
        if(!confirm("Are you sure want to delete selected items?")){
            return;
        }
        
        $.ajax({
            url: $(this).data('href'),
            type: "POST",
            headers: {
    		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    		    },
            data: {
                ids: ids
            },
            success: function(res){
                
                if(res.status==true){
                    swal(res.msg);
                    // if(res.url){
                    //     document.location.href = res.url;
                    // }else{
                    //     window.location.reload();
                    // }
                    jQuery("input#search").change();
                }else if(res.status==false){
                    swal(res.msg);
                }
                        
            }
        });
        });


});