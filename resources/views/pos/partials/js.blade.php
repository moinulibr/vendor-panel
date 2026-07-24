<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">
    
    $('#same_shipping').on('change', function () {
        $('.shipping_form').toggle(!this.checked);
    });
    
    $('.add_new_shipping').on('click', function () {
        
        let contact_id=$(document).find('#contact_id').val();
        
        if (contact_id) {
            $('.shipping_new_form').slideToggle(300);
        }else{
            swal('Select A Customer First');
        }
        
        
    });

    $(document).ready(function () {

        $('.option-box').on('click', function () {
            $(this).find('input[type="radio"]').prop('checked', true);
    
            $('.option-box').removeClass('active');
            $(this).addClass('active');
        });
    
    });
    
    

    getData();
    getCustomer();
    calculateSum();
    
    let products=[];
    
    $('#customer_search').keyup(function(){
        getCustomer();
    });
    
    $('.concat_add_from').change(function(){
        getCustomer();
    });
    

    $('#psearch').keyup(function(){
        getData();
    });


    $('#category_id, #location_id, #brand_id').change(function(){
        getData();
    });
    
    function getCustomer(){
        
        let search=$('#customer_search').val(); 
        let add_from=$('.concat_add_from').val(); 
    
        $('div#customer-list').html('');
        $.ajax({
            url: '{{ route("getCustomer")}}',
            type: 'GET',
            data:{search,add_from},
            dataType: 'html',
            success: function(data) {
                $('div.customer-list').html(data);
            }
        });
        
        
    }
    
    $(document).on('click', '.add_shipping_form', function(){
        
        let contact_id=$('#contact_id').val();
        
        let name=$('#name').val();
        let phone=$('#customer_phone').val();
        let district_id=$('#district_id').val();
        let upazila_id=$('#upazila_id').val();
        let landmark=$('#landmark').val();
        let address=$('#address').val();
        let button=$(this);
        button.attr("disabled", "disabled");
        
        $('.serror').remove();
        $.ajax({
            url: '{{ route("storeCustomerAddress")}}',
            type: 'POST',
            data:{contact_id,name,phone,address,landmark,upazila_id,district_id},
            dataType: 'json',
            
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                button.removeAttr("disabled");
                if(data.status==true){
                    toastr.success(data.msg);
                    customerAddress(contact_id);
                    $('.shipping_new_form').slideToggle(300);
                    
                    $('#name').val('');
                    $('#customer_phone').val('');
                    $('#district_id').val('');
                    $('#upazila_id').val('');
                    $('#landmark').val('');
                    $('#address').val('');
        
                }
            },
            
            error:function (response){
                button.removeAttr("disabled");
                if (response.status === 422) {
                    $.each(response.responseJSON.errors,function(field_name,error){
                        $(document).find('#'+field_name).after('<span class="serror" style="color:red">' +error+ '</span>')
                        // toastr.error(error);
                    })
                }
            }
            
        });
        
        
        
    });
    
    
    $(document).on('click', "#product_paginate .pagination a", function(e) {
        e.preventDefault();

        $('li').removeClass('active');
        $(this).parent('li').addClass('active');

        var page = $(this).attr('href').split('page=')[1];
        getData(page);
    });
    

    function getData(page=1){
        let search=$('#psearch').val();

        let category_id=$('#category_id').val(); 
        let location_id=$('#location_id').val(); 
        let brand_id=$('#brand_id').val(); 
    
        $('div#product-list').html('');
        $.ajax({
            url: '{{ route("getPosProduct")}}',
            type: 'GET',
            data:{search,category_id, location_id, brand_id,page},
            dataType: 'html',
            success: function(data) {
                $('div#product-list').html(data);
            }
        });
    }

    //
    
    var product_url = "{{ route('getSellProduct') }}";
    $("#purchases_product" ).autocomplete({
        selectFirst: true, //here
        minLength: 2,
        source: function( request, response ) {
          $.ajax({
            url: product_url,
            type: 'GET',
            dataType: "json",
            data: {search: request.term, location_id:$("#location_id").val()},
            success: function( data ) {
                
                if (data.length ==0) {
                    toastr.error('Product Not Found');
                }
                else if (data.length ==1) {

                    if(products.indexOf(data[0].id) ==-1){
                        productEntry(data[0].id);
                        products.push(data[0].id);
                    }
                    
                    $('#purchases_product').val('');


                    
                }else if (data.length >1) {
                    response(data);
                }
            }
          });
        },
        select: function (event, ui) {
           
            if(products.indexOf(ui.item.id) ==-1){
                productEntry(ui.item.id);
                products.push(ui.item.id);
            }

           $('#purchases_product').val('');
           return false;
        }
    });

    function productEntry(variation_id){
        
        if(products.indexOf(variation_id) ==-1){
            products.push(variation_id);
            let location_id=$("#location_id").val();
            $.ajax({
                url: '{{ route("sellProductEntry")}}',
                type: 'GET',
                dataType: "json",
                data: {variation_id,location_id},
                success: function( res ) {
                        
                    if (res.html) {
                        $('div#pos_cart_items').append(res.html);
                        toastr.success('Product Added To Cart ');
                        calculateSum();
                    }else{
                        swal(res.msg);
                    }
                    
                }
            });
        }
    }


    $(document).on('click',".remove_cart_row",function(e) {
        
        let variation_id=$(this).data('variation_id');
        
        products = products.filter(id => id !== variation_id);
        
        var whichtr = $(this).closest("div.product-item");
        whichtr.remove(); 
        toastr.success('Product Remove From Cart ');
        calculateSum();    
    });

    $(document).on('click',".decrease, .increase",function(e) {

        let inputqty=$(this).closest('div.quantity-controls').find('input.quantity');
        let span_quantity=$(this).closest('div.quantity-controls').find('span.tquantity');
        let qty=Number(inputqty.val());
        let max_qty=Number(inputqty.attr('max'));

        let type=$(this).data('type');

        if (type=='plus') {
            if (max_qty <qty+1) {
                swal('Stock Is Over');
            }
            if (max_qty >qty) {
                inputqty.val(qty+1);
                span_quantity.text(qty+1);
            }
        }else if (type=='minus') {

            if (qty >1) {

                inputqty.val(qty-1);
                span_quantity.text(qty-1);
            }
        }
        calculateSum();    
    });


    $(document).on('blur',".unit_price",function(e) {

        calculateSum();    
    });

    $(document).on('click',".savediscount ,.saveCharge",function(e) {

        calculateSum();    
    });


    function calculateSum() {


        let sub_total=0;
        let p_discount=0;

        let tblrows = $("#pos_cart_items div.product-item");
        tblrows.each(function (index) {
            let tblrow = $(this);

            let product_qty=Number(tblrow.find('input.quantity').val());
            let product_amount=Number(tblrow.find('input.unit_price').val());
            let row_discount=Number(tblrow.find('input.discount').val());
             
            let product_row_total=(product_qty *product_amount);
            tblrow.find('.row_total').text(product_row_total.toFixed(2));
            sub_total+=product_row_total;  
            p_discount+=(product_qty*row_discount);  
            
        });
        $('span.sub_total').text(sub_total.toFixed(2));

        let discount_type=$('select.discount_type').val();
        let charge=Number($('input.service_charge').val() || 0);
        
        
        let discount=Number($('input.discount_amount').val() || 0);
        
        if(discount_type=='Percentage'){
            discount=(sub_total*discount)/100;
        }
        
        $('input.cal_discount').val((discount+p_discount).toFixed(2));
        
        sub_total=(sub_total+charge - discount);
        $('span.charge').text(charge.toFixed(2));
        $('span.discount').text((discount+p_discount).toFixed(2));

        $('input.final_amount').val(sub_total.toFixed(2));
        $('input.pay_amount').val(sub_total.toFixed(2));
        $('span.final_amount').text(sub_total.toFixed(2));
        $('span.total_item').text(tblrows.length);
    }


    $(document).on('click','#place_order', function(e) {
        if($("#pos_cart_items div.product-item").length ==0){
            alert('Product Select first');
            return false;
        }
        $('#payment-card').modal('show');
    });
    
    $(document).on('click','#quotation', function(e) {
        if($("#pos_cart_items div.product-item").length ==0){
            alert('Product Select first');
            return false;
        }
        $('#quotation-modal').modal('show');
    });
    

    $(document).on('click','.final_sale', function(e) {
        if($("#pos_cart_items div.product-item").length ==0){
            alert('Product Select first');
            return false;
        }
        
        let quotation=0;
        
        if ($(this).hasClass("quotation")) {
            quotation=1;
        }
    
        let dmethod=$(this).data('method');

        if (dmethod=='cash' || dmethod=='card') {
            $('.select-payment').val(dmethod).change();
        }else if (dmethod=='due'){
            $('input.pay_amount').val(0);
        }

        $('span.textdanger').text('');
        var url=$("#pos_form").attr('action');
        var method=$("#pos_form").attr('method');
        
        let button=$(this);
        
        let data = $("#pos_form").serialize();
        data += '&location_id='+$('#location_id').val();
        data += '&quotation='+quotation;

        button.attr("disabled", "disabled");
        $.ajax({
            type: method,
            url: url,
            data: data,
            success: function(res) {
                
                if(res.status==true){
                    toastr.success(res.msg);
                    $('div#payment-card').modal('hide');
                    $('div#quotation-modal').modal('hide');
                    
                    products=[];
                    $("#pos_cart_items").html('');
                    
                    $('#pos_form')[0].reset();
                    calculateSum();

                    if(res.print_html){
                        $('div#print-receipt').html(res.print_html); 
                        
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

                    if(res.url){
                        setTimeout(function() {
                            window.location.href = res.url;
                        }, 3000);
                    }
                    
                }else if(res.status==false){
                    swal(res.msg);
                }
                
            },
            error:function (response){
                button.removeAttr("disabled");
                $.each(response.responseJSON.errors,function(field_name,error){
                    // $(document).find('[name='+field_name+']').after('<span style="color:red">' +error+ '</span>')
                    toastr.error(error);
                })
            }
        });
    });
    
    
    //  for print
    
    $(document).on('click','#print_sell', function(e) {
        if($("#pos_cart_items div.product-item").length ==0){
            alert('Product Select first');
            return false;
        }


        $('span.textdanger').text('');
        var url='{{ route("storePosPrint",[7])}}';
        var method='PATCH';
        
        let button=$(this);
        
        let data = $("#pos_form").serialize();
        data += '&location_id='+$('#location_id').val();

    
        $.ajax({
            type: method,
            url: url,
            data: data,
            success: function(res) {
                
                if(res.status==true){
                    toastr.success(res.msg);

                    if(res.print_html){
                        $('div#print-receipt').html(res.print_html); 
                        
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
                    
                }else if(res.status==false){
                    swal(res.msg);
                }
                
            },
            error:function (response){
                button.removeAttr("disabled");
                $.each(response.responseJSON.errors,function(field_name,error){
                    // $(document).find('[name='+field_name+']').after('<span style="color:red">' +error+ '</span>')
                    toastr.error(error);
                })
            }
        });
    });
    
    
    function customerEntry(customer){
        
        $('#contact_id').val(customer.id);
        customerDetails(customer.id);
        customerAddress(customer.id);
    }
    
    
    $(document).ready(function(){
        
        let contact_id=$('#contact_id').val();
        
        if (contact_id) {
            customerDetails(contact_id);
        }
        
        
        $('.customer-box .ti-x, .customer-box .ti-chevron-right').hide();
    });
    
    function customerEntryNew(customer){
        
        $('#contact_id').val(customer.id);
        customerDetailsNew(customer.id);
        
    }

    
    function customerDetails(customer_id){
        let url='{{ route("getCustomerdetails")}}';
        $.ajax({
            type: 'GET',
            url: url,
            data: {customer_id},
            dataType: "json",
            success: function(res) {
                
                if(res.html){
             
                    $('div#customerDetailsAccordion').html(res.html);
                    $('span.customer_name').html(res.customer_name);
                    
                    if(res.contact){
                        
                        $('.ncustomer_name').html(res.contact.name);
                        $('.ncustomer_phone').html(res.contact.mobile);
                        $('.ncustomer_email').html(res.contact.email);
                    }
                   
                    
                    // Show or hide icons based on customer_name
                    if(res.customer_name && res.customer_name.trim() !== ''){
                        $('.customer-box .ti-x, .customer-box .ti-chevron-right').show();
                    } else {
                        $('.customer-box .ti-x, .customer-box .ti-chevron-right').hide();
                    }
                    
                    $('#customerOffcanvas').offcanvas('hide');
                    $('#addCustomerOffcanvas').offcanvas('hide');
                    // $('#customerDetailsOffcanvas').offcanvas('show');
                    $(document).find('.due_balance').text($(document).find('.exist_due_balance').text());
    
                }else if(res){
                    swal('Not Found Customer');
                    $('.customer-box .ti-x, .customer-box .ti-chevron-right').hide();
                }
                
            }
        });
        
        
    }
    
    function customerAddress(customer_id){
        let url='{{ route("getCustomerAddress")}}';
        $.ajax({
            type: 'GET',
            url: url,
            data: {customer_id},
            dataType: "json",
            success: function(res) {
                
                if(res.contact_address_html){
             
                    $('div.customer_address').html(res.contact_address_html);
    
                }
                
            }
        });
        
        
    }
    
    
    function customerDetailsNew(customer_id){
        let url='{{ route("getCustomerdetails")}}';
        $.ajax({
            type: 'GET',
            url: url,
            data: {customer_id},
            dataType: "json",
            success: function(res) {
                
                if(res.html){
             
                    $('div#customerDetailsAccordion').html(res.html);
                    $('span.customer_name').html(res.customer_name);
                    $('#customerOffcanvas').offcanvas('hide');
                    $('#addCustomerOffcanvas').offcanvas('hide');
                    $('#customerDetailsOffcanvas').offcanvas('show');
                    $(document).find('.due_balance').text($(document).find('.exist_due_balance').text());
    
                }else if(res){
                    swal('Not Found Customer');
                }
                
            }
        });
        
        
    }
    
    
    
    $(document).on('submit','#add_customer', function(e) {
        e.preventDefault();
        
        var method=$(this).attr('method');
        let url=$(this).attr('action');
        let button=$(this);
        
        let data = $(this).serialize();

        button.attr("disabled", "disabled");
        $.ajax({
            type: method,
            url: url,
            data: data,
            success: function(res) {
                
                if(res.status==true){
                    toastr.success(res.msg);
                    $('div#payment-card').modal('hide');
                    customerEntry(res.contact);
                    
                    
                    
                }else if(res.status==false){
                    swal(res.msg);
                }
                
            },
            error:function (response){
                button.removeAttr("disabled");
                $.each(response.responseJSON.errors,function(field_name,error){
                    // $(document).find('[name='+field_name+']').after('<span style="color:red">' +error+ '</span>')
                    toastr.error(error);
                })
            }
        });
    });
    
    
    $(document).on('change', '.district_id',function () {
        var district_id = $(this).val();
    
        $('.upazila_id').empty().append('<option value="">Loading...</option>');
    
        $.ajax({
            url: '{{ route("getUpazila")}}',
            type: 'GET',
            data:{district_id},
            success: function (data) {
                $('.upazila_id').empty().append('<option value="">Select Thana</option>');
                $.each(data, function (key, value) {
                    $('.upazila_id').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                });
            }
        });
    });
    
    $(document).on('change', '#s_district',function () {
        var district_id = $(this).val();
    
        $('#s_upazila').empty().append('<option value="">Loading...</option>');
    
        $.ajax({
            url: '{{ route("getUpazila")}}',
            type: 'GET',
            data:{district_id},
            success: function (data) {
                $('#s_upazila').empty().append('<option value="">Select Thana</option>');
                $.each(data, function (key, value) {
                    $('#s_upazila').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                });
            }
        });
    });
    

    
    $(document).on('keydown', '.input_number', function (e) {
        // Prevent: e, E, +, -, Enter
        if (
            e.key === 'e' || 
            e.key === 'E' || 
            e.key === '+' || 
            e.key === '-' || 
            e.key === 'Enter'
        ) {
            e.preventDefault();
        }
    });


  
</script>