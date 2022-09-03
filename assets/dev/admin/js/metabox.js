jQuery(document).ready(function ($) {

    $("#expire_date").datetimepicker();

    if ( $("#enable_news").is(':checked') ) {
        $(".form-group-custom_title").show(300);
        $(".form-group-enable_expire_time").show(300);
    } else {
        $(".form-group-custom_title").hide();
        $(".form-group-enable_expire_time").hide();
        $(".form-group-expire_date").hide();
    }

    $("#enable_news").on('click', function () {
        if ($("#enable_news").is(":checked") ) {
            $(".form-group-custom_title").show(300);
            $(".form-group-enable_expire_time").show(300);
            
        } else {
            $("#expire_date").val('');
            $("#custom_title").val('');
            $("#enable_expire_time").prop('checked', false);

            $(".form-group-custom_title").hide(300);
            $(".form-group-enable_expire_time").hide(300);
            $(".form-group-expire_date").hide(300);
        }
    });
    
    

    $("body").on('click', '#enable_expire_time', function () {
        if ($("#enable_expire_time").is(":checked")) {
            $(".form-group-expire_date").show(300);
        } else {
            $("#expire_date").val('');
            $(".form-group-expire_date").hide(300);
        }
    });
    
    
    
});