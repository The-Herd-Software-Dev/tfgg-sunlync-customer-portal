<?php
/*
* Used to house low level short codes used through out the plugin and other pages
*
* dont forget to add any publicly facing shortcodes to admin-menu/am-shortcodes.php
*/

//require_once('reg-page.php');
//require_once('instore-reg-page.php');
//require_once('login-page.php');
require_once('api-login-page.php');
require_once('registration.php');

/*function tfgg_cp_sunlync_clientnumber(){
    return 'sunlync client number';
}

add_shortcode('cp_sunlync_clientnumber','tfgg_cp_sunlync_clientnumber');*/

function tfgg_cp_sunlync_login(){
    
    /*if(!is_user_logged_in()){
        return login_form_display();       
    }else{
        $client=tfgg_cp_get_sunlync_client();
        if(!$client){
            return login_form_display();     
        }else{
            //header('Location: ' . get_option('tfgg_scp_cplogin_page_success')); 
            echo '<script>document.location.href = "'.get_option('tfgg_scp_cplogin_page_success').'";</script>';
        }
    }*/
    return login_form_display(); 
    
}
add_shortcode('cp_sunlync_loginform','tfgg_cp_sunlync_login');

function tfgg_cp_sunlync_register_new(){
    
    /*if(!is_user_logged_in()){
        return reg_form_display_new();       
    }else{
        $client=tfgg_cp_get_sunlync_client();
        if(!$client){
            return reg_form_display_new();     
        }else{
            //header('Location: ' . get_option('tfgg_scp_cplogin_page_success')); 
            echo '<script>document.location.href = "'.get_option('tfgg_scp_cplogin_page_success').'";</script>';
        }
    }*/
    //return reg_form_display_new(); 
    if((get_option('tfgg_scp_recaptcha_site_key','')!=='')&&
    (get_option('tfgg_scp_recaptcha_secret_key','')!=='')){
        wp_enqueue_script('tfgg-user-recaptcha','https://www.google.com/recaptcha/api.js?render='.get_option('tfgg_scp_recaptcha_site_key'),'','');
    }
    add_action('wp_footer','tfgg_scp_add_registration_dialogs_to_footer');
    return tfgg_scp_registration(false);
}
add_shortcode('cp_sunlync_registrationform_new','tfgg_cp_sunlync_register_new');

/*function tfgg_cp_sunlync_register_existing(){
    //return reg_form_display();
}
add_shortcode('cp_sunlync_registrationform_existing','tfgg_cp_sunlync_register_existing');*/

function tfgg_cp_sunlync_appointments(){
    return appt_booking();
}
add_shortcode('cp_sunlync_appts','tfgg_cp_sunlync_appointments');

function tfgg_cp_sunlync_account(){
    /*if(!tfgg_cp_get_sunlync_client()){
        //wp_redirect(get_option('tfgg_scp_cplogin_page'));exit;
        echo '<script>document.location.href = "'.get_option('tfgg_scp_cplogin_page').'";</script>';
    }else{
        return account_overview();
    }*/
    
    /*if(!is_user_logged_in()){
        echo '<script>document.location.href = "'.get_option('tfgg_scp_cplogin_page').'";</script>';    
    }else{
        return account_overview();
    }*/
    return account_overview();
}
add_shortcode('cp_sunlync_demographics','tfgg_cp_sunlync_account');

function tfgg_cp_sunlync_register_instore(){
    if((get_option('tfgg_scp_recaptcha_site_key','')!=='')&&
    (get_option('tfgg_scp_recaptcha_secret_key','')!=='')){
        wp_enqueue_script('tfgg-user-recaptcha','https://www.google.com/recaptcha/api.js?render='.get_option('tfgg_scp_recaptcha_site_key'),'','');
    }
    add_action('wp_footer','tfgg_scp_add_registration_dialogs_to_footer');
    if(!isset($_COOKIE['instore_reg_store'])){
        return tfgg_scp_registration_instore_set_cookie_display();
    }else{
        return tfgg_scp_registration(true);
    }

}
add_shortcode('cp_sunlync_registrationform_instore','tfgg_cp_sunlync_register_instore');

function tfgg_cp_sunlync_register_instore_setstore(){
    return set_storecode_display();    
}
add_shortcode('cp_sunlync_registrationform_instore_setstore','tfgg_cp_sunlync_register_instore_setstore');

function tfgg_cp_sunlync_cart(){
    /*$viewCart= get_query_var('viewcart');
    
    if($viewCart=='cart'){
        return tfgg_scp_display_cart();
    }elseif($viewCart=='success-paypal'){
        
    }else{
        return tfgg_scp_display_services_for_sale();
    }*/
    if(get_option('tfgg_scp_enable_cart',0)==0){return false;}//2020-02-17 CB V1.2.4.17
    return tfgg_scp_display_cart();
}
add_shortcode('cp_sunlync_cart','tfgg_cp_sunlync_cart');

function tfgg_cp_sunlync_cart_services_sale(){
    if(get_option('tfgg_scp_enable_cart',0)==0){return false;}//2020-02-17 CB V1.2.4.17
    return tfgg_scp_display_services_for_sale();
}
add_shortcode('cp_sunlync_cart_services','tfgg_cp_sunlync_cart_services_sale');


function tfgg_cp_sunlync_emp_dashboard(){
    //CB V1.2.7.2 - new shortcode
    if ( !is_user_logged_in() ) {
    	auth_redirect();
	} 
    return tfgg_scp_display_emp_dashboard();
}
add_shortcode('cp_sunlync_emp_dashboard','tfgg_cp_sunlync_emp_dashboard');

function tfgg_cp_sunlync_cart_success(){
    if(get_option('tfgg_scp_enable_cart',0)==0){return false;}

    return tfgg_scp_cart_success_display();
}
add_shortcode('cp_sunlync_success_cart','tfgg_cp_sunlync_cart_success');

function tfgg_cp_sunlync_freebie_marketing_apply(){
    return tfgg_scp_output_freebie_marketing_processing();
}
add_shortcode('cp_sunlync_freebie_marketing_apply','tfgg_cp_sunlync_freebie_marketing_apply');

/*function cp_registration_instore(){
    return tfgg_scp_registration(true);
}
add_shortcode('tfgg_scp_registration_instore','cp_registration_instore');

function cp_registration_online(){
    return tfgg_scp_registration(false);
}
add_shortcode('tfgg_scp_registration_online','cp_registration_online');*/
?>