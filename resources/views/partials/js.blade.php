<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<!-- Feather Icon JS -->
<script src="{{ asset('assets/js/feather.min.js')}}" type="957bd134482ff367f9eb3c44-text/javascript"></script>


<!-- Slimscroll JS -->
<script src="{{ asset('assets/js/jquery.slimscroll.min.js')}}" type="957bd134482ff367f9eb3c44-text/javascript"></script>

<!-- Bootstrap Core JS -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js')}}" type="957bd134482ff367f9eb3c44-text/javascript"></script>

<!-- ApexChart JS -->
<script src="{{ asset('assets/plugins/apexchart/apexcharts.min.js')}}" type="957bd134482ff367f9eb3c44-text/javascript"></script>
<script src="{{ asset('assets/plugins/apexchart/chart-data.js')}}" type="957bd134482ff367f9eb3c44-text/javascript"></script>

<!-- Chart JS -->
<script src="{{ asset('assets/plugins/chartjs/chart.min.js')}}" type="957bd134482ff367f9eb3c44-text/javascript"></script>
<script src="{{ asset('assets/plugins/chartjs/chart-data.js')}}" type="957bd134482ff367f9eb3c44-text/javascript"></script>

<!-- Daterangepikcer JS -->
<script src="{{ asset('assets/js/moment.min.js')}}" type="957bd134482ff367f9eb3c44-text/javascript"></script>
<script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js')}}" type="957bd134482ff367f9eb3c44-text/javascript"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="{{ asset('assets/plugins/%40simonwep/pickr/pickr.es5.min.js')}}" type="957bd134482ff367f9eb3c44-text/javascript"></script>

@stack('js')

<script src="{{ asset('assets/js/script.js')}}" type="957bd134482ff367f9eb3c44-text/javascript"></script>
<script src="{{ asset('assets/js/theme-colorpicker.js')}}" type="957bd134482ff367f9eb3c44-text/javascript"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script src="{{ asset('assets/js/ajax.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.1/jquery-ui.min.js"></script>


<script src="{{ asset('assets/cdn-cgi/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js')}}" data-cf-settings="957bd134482ff367f9eb3c44-|49" defer></script>

<script>
    
    $(document).on('keyup blur', 'input.mobile_validation', function () {
        let phone = $(this).val().trim();
    
        // Bangladesh phone regex
        let bdPhoneRegex = /^(?:\+880|880|0)1[3-9]\d{8}$/;
    
        if (phone === '') {
            $('#phone_error').text('');
            return;
        }
    
        if (!bdPhoneRegex.test(phone)) {
            $('#phone_error').text('Enter a valid Bangladesh phone number');
        } else {
            $('#phone_error').text('');
        }
    });
</script>