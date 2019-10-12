<?php
/*
* Used to house low level short codes used through out the plugin and other pages
*
* dont forget to add any publicly facing shortcodes to admin-menu/am-shortcodes.php
*/

require_once('reg-page.php');
//require_once('login-page.php');
require_once('api-login-page.php');

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
    return reg_form_display_new(); 
}

add_shortcode('cp_sunlync_registrationform_new','tfgg_cp_sunlync_register_new');

function tfgg_cp_sunlync_register_existing(){
    //return reg_form_display();
}

add_shortcode('cp_sunlync_registrationform_existing','tfgg_cp_sunlync_register_existing');

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

?>