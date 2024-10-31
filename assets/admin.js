jQuery(document).ready(function () {
    jQuery('.nirweb_smart_sms_admin_container >div').first().show();
    jQuery('.tab_in').first().show();
    jQuery('.nirweb_smart_sms_tabs li').on('click',function (){
        jQuery('.nirweb_smart_sms_tabs li').removeClass('active');
        jQuery(this).addClass('active');
        jQuery('.nirweb_smart_sms_admin_container >div').hide();
        jQuery('#'+ jQuery(this).attr('data-target')).show();
    });
    jQuery('.nirweb_inner_tabs li').on('click',function (){
        jQuery('.nirweb_inner_tabs li').removeClass('active');
        jQuery(this).addClass('active');
        jQuery('.tab_in').hide();
        jQuery('#'+ jQuery(this).attr('data-target')).show();
    });
});