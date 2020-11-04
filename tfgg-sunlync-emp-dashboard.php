<?php

function tfgg_scp_display_emp_dashboard(){
    ob_start(); 
    if(tfgg_is_sunlync_emplopyee_logged_in()){
        tfgg_scp_display_emp_dashboard_panels();
    }else{
        tfgg_scp_display_emp_dashboard_login(); 
    }

    return ob_get_clean();
}

function tfgg_scp_display_emp_dashboard_login(){
    ?>
    <hr /><br />
    <?php
        tfgg_sunlync_cp_show_error_messages();
    ?>
    <h3 class="header"><?php _e('Employee Dashboard Login');?></h3>
    <form method="POST" id="tfgg_cp_api_employee_login" action="">
        <div class="login-container">
            <div class="account-overview-input-single">
                <label for="tfgg_cp_user_login"  class="account-overview-label"><?php _e('SunLync Username'); ?></label>
                <input name="tfgg_cp_user_login" id="tfgg_cp_user_login" class="required account-overview-input" type="text"/>
                <div style="display:none" id="login_alert_email" class="reg_alert"></div> 
            </div>
        </div>
            
        <div class="login-container">
                <div class="account-overview-input-single">
                    <label for="tfgg_cp_user_pass" class="account-overview-label"><?php _e('Password'); ?></label>
                    <input name="tfgg_cp_user_pass" id="tfgg_cp_user_pass" class="required account-overview-input" type="password"/>
                    <div style="display:none" id="login_alert_password" class="reg_alert"></div> 
            </div>
        </div>
        
        
            <div class="login-container">
                <div class="account-overview-input-double">
                    <input type="hidden" name="tfgg_cp_employee_login_nonce" id="tfgg_cp_employee_login_nonce" value="<?php echo wp_create_nonce('tfgg-cp-employee-login-nonce'); ?>"/>
                    <button id="tfgg_cp_login_submit" type="submit" class="account-overview-button account-overview-standard-button" onclick="portalLogin('tfgg_cp_api_employee_login');"><?php _e('LOGIN'); ?></button>
                </div>
            </div>
            
            <br />
        
        </div> 
    </form> 
    <?php
}

function tfgg_scp_display_emp_dashboard_panels(){
    ?>
    <hr /><br />
    <?php
        tfgg_sunlync_cp_show_error_messages();
    ?>
    <h3 class="header"><?php _e('Employee Dashboard');?></h3>
    <div class="container-fluid">

        <div id="tfgg_scp_emp_dash_stat_select" class="card">
            <div class="card-header">
                Select what stats you would like to view
            </div>
            <div class="card-body">
                <div>
                    <button id="btn_emp_dash_store_clockin" class="appts-button appts-standard-button" onclick="EmpDash_StoreClockIn()">Store Clock-In</button>
                </div>
            </div>
        </div>

        <div id="tfgg_scp_emp_dash_busy" style="display:none; border:none" class="card">
            <img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/loading.gif" class="loading-image"/>
        </div>

        <div id="tfgg_scp_emp_dash_store_clock_in_state" class="card" style="display:none">
            <div class="card-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-10">
                            Store Clock-In Times
                        </div>
                        <div class="col-md-2 float-right">
                            <button id="btn_emp_dash_store_clockin_Refresh" class="appts-button appts-standard-button" onclick="EmpDash_StoreClockIn()">Refresh</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="container-fluid" id="tfgg_scp_emp_dash_store_clock_in_store_card">
                </div>
            </div>   
        </div>
    </div>
    <br/><br/>
    <?php
}


function tfgg_scp_employee_login(){
    if(isset($_POST['tfgg_cp_employee_login_nonce']) && 
        wp_verify_nonce($_POST['tfgg_cp_employee_login_nonce'],'tfgg-cp-employee-login-nonce')){

        unset($_POST['tfgg_cp_employee_login_nonce']);

        if((!isset($_POST['tfgg_cp_user_login']))||($_POST['tfgg_cp_user_login']==='')){
            tfgg_cp_errors()->add('error_empty_username', __('Please enter a login'));	
        }
        
        if((!isset($_POST['tfgg_cp_user_pass']))||($_POST['tfgg_cp_user_pass']==='')){
            tfgg_cp_errors()->add('error_empty_password', __('Please enter a password'));	
        }
        
        $errors = tfgg_cp_errors()->get_error_messages();

        if(empty($errors)){
            $loginResponse = json_decode(tfgg_cp_api_employee_login($_POST['tfgg_cp_user_login'],trim($_POST['tfgg_cp_user_pass'])));
            
            if(strToUpper($loginResponse->results)!='SUCCESS'){
                tfgg_cp_errors()->add('error_no_matching_user', __('Username or password is incorrect, please try again'));
            }
            $errors = tfgg_cp_errors()->get_error_messages();
        }
        
    }
}
add_action('init','tfgg_scp_employee_login');
?>