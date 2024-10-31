function nirweb_smart_sms_countdown(enddate,elem){
    var x = setInterval(function () {
        var currentDate = new Date();
        var eventDate = new Date(enddate * 1000);
        var milliseconds = eventDate.getTime() - currentDate.getTime();
        var seconds = parseInt(milliseconds / 1000);
        var minutes = parseInt(seconds / 60);
        var hours = parseInt(minutes / 60);
        var days = parseInt(hours / 24);
        var months = parseInt(days / 30);
        seconds -= minutes * 60;
        minutes -= hours * 60;
        hours -= days * 24;
        days -= months * 30;
        jQuery(elem).find('.hours').text(hours);
        jQuery(elem).find('.days').text(days);
        jQuery(elem).find('.months').text(months);
        jQuery(elem).find('.minutes').text(minutes);
        jQuery(elem).find('.seconds').text(seconds);
        if (milliseconds < 0) {
            clearInterval(x);
            alert('مدت زمان شما به اتمام رسید');
            window.location.reload();
        }
    },1000);
}
jQuery(document).ready(function($) {
    $('#notity_me_when_in_stock').change(function() {
        if(this.checked) {
            $('.nirweb_smart_sms_in_stock .nirweb_smart_sms_phone_fields').addClass('show');
        }else{
            $('.nirweb_smart_sms_in_stock .nirweb_smart_sms_phone_fields').removeClass('show');
        }
    });

    $('.nirweb_smart_sms_in_stock .nirweb_smart_sms_phone_fields button').click(function (){
        $(this).addClass('loading');
        phone = $(this).closest('div').find('input').val();
        product_id = $(this).attr('data-product');
        $.ajax({
            url :  nirweb_smart_sms_js.ajax_url ,
            data : {
                action : 'nirweb_smart_sms_save_phone',
                phone,
                product_id,
                sec_token : nirweb_smart_sms_js.security_nonce
            },
            dataType: 'json',
            cache: false,
            type :  "POST",
            success: function (data) {
                if(data['0'] == 1){
                    $('.nirweb_smart_sms_in_stock .nirweb_smart_sms_response').addClass('success');
                }else{
                    $('.nirweb_smart_sms_in_stock .nirweb_smart_sms_response').addClass('fail');
                }
                $('.nirweb_smart_sms_in_stock .nirweb_smart_sms_phone_fields button').removeClass('loading');
                $('.nirweb_smart_sms_in_stock .nirweb_smart_sms_response').html(data[1]);
            }
        });
    });

    $('#notity_me_when_on_sale').change(function() {
        if(this.checked) {
            $('.nirweb_smart_sms_sale .nirweb_smart_sms_phone_fields').addClass('show');
        }else{
            $('.nirweb_smart_sms_sale .nirweb_smart_sms_phone_fields').removeClass('show');
        }
    });
    $('.nirweb_smart_sms_sale .nirweb_smart_sms_phone_fields button').click(function (){
        $(this).addClass('loading');
        phone = $(this).closest('div').find('input').val();
        product_id = $(this).attr('data-product');
        $.ajax({
            url :  nirweb_smart_sms_js.ajax_url ,
            data : {
                action : 'nirweb_smart_sms_save_phone_sale',
                phone,
                product_id,
                sec_token : nirweb_smart_sms_js.security_nonce
            },
            dataType: 'json',
            cache: false,
            type :  "POST",
            success: function (data) {
                if(data['0'] == 1){
                    $('.nirweb_smart_sms_sale .nirweb_smart_sms_response').addClass('success');
                }else{
                    $('.nirweb_smart_sms_sale .nirweb_smart_sms_response').addClass('fail');
                }
                $('.nirweb_smart_sms_sale .nirweb_smart_sms_phone_fields button').removeClass('loading');
                $('.nirweb_smart_sms_sale .nirweb_smart_sms_response').html(data[1]);
            }
        });
    });



});