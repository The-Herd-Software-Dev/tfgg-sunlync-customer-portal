<?php
/*
* used to control the admin menu provided
*/

    function tfgg_sunlync_cp_options(){
        //sections
        add_settings_section("tfgg_shortcodes", '', null, "tfgg-shortcodes");
        add_settings_section("tfgg_api_section", '', null, "tfgg-api-options");
        add_settings_section("tfgg_general_options_section", '', null, "tfgg-general-options");
        add_settings_section("tfgg_appointments_section", '', null, "tfgg-appointments-options");
        add_settings_section("tfgg_messages_section", '', null, "tfgg-messages-options");
        
        //tfgg_shortcodes
        add_settings_field("tfgg_scp_cplogin_page", "Login Page:", "display_cplogin_page", "tfgg-shortcodes", "tfgg_shortcodes");
        register_setting("tfgg_shortcodes", "tfgg_scp_cplogin_page");
        
        add_settings_field("tfgg_scp_cplogin_page_success", "Login Redirect:", "display_cplogin_page_success", "tfgg-shortcodes", "tfgg_shortcodes");
        register_setting("tfgg_shortcodes", "tfgg_scp_cplogin_page_success");
        
        add_settings_field("tfgg_scp_acct_overview", "Account Overview:", "display_acct_overview", "tfgg-shortcodes", "tfgg_shortcodes");
        register_setting("tfgg_shortcodes", "tfgg_scp_acct_overview");
        
        add_settings_field("tfgg_scp_cpnewuser_page", "Registration Page:", "display_cpnewuser_page", "tfgg-shortcodes", "tfgg_shortcodes");
        register_setting("tfgg_shortcodes", "tfgg_scp_cpnewuser_page");
        
        add_settings_field("tfgg_scp_cpappt_page", "Appt Booking Page:", "display_cpappt_page", "tfgg-shortcodes", "tfgg_shortcodes");
        register_setting("tfgg_shortcodes", "tfgg_scp_cpappt_page");
        
        //tfgg_api_section
        add_settings_field("tfgg_scp_api_protocol", "Protocol:", "display_tfgg_api_protocol", "tfgg-api-options", "tfgg_api_section");
        register_setting("tfgg_api_section", "tfgg_scp_api_protocol");
        
        add_settings_field("tfgg_scp_api_url", "URL:", "display_tfgg_api_url", "tfgg-api-options", "tfgg_api_section");
        register_setting("tfgg_api_section", "tfgg_scp_api_url");
        
        add_settings_field("tfgg_scp_api_port", "Port:", "display_tfgg_api_port", "tfgg-api-options", "tfgg_api_section");
        register_setting("tfgg_api_section", "tfgg_scp_api_port");
        
        add_settings_field("tfgg_scp_api_mrkt", "Market:", "display_tfgg_api_market", "tfgg-api-options", "tfgg_api_section");
        register_setting("tfgg_api_section", "tfgg_scp_api_mrkt");
        
        add_settings_field("tfgg_scp_api_user", "Username:", "display_tfgg_api_user", "tfgg-api-options", "tfgg_api_section");
        register_setting("tfgg_api_section", "tfgg_scp_api_user");
        
        add_settings_field("tfgg_scp_api_pass", "Password:", "display_tfgg_api_pass", "tfgg-api-options", "tfgg_api_section");
        register_setting("tfgg_api_section", "tfgg_scp_api_pass");
        
        //tfgg_general_options
        add_settings_field("tfgg_scp_customer_service_email", "Customer Service E-Mail:", "display_customer_service_email", "tfgg-general-options", "tfgg_general_options_section");
        register_setting("tfgg_general_options_section", "tfgg_scp_customer_service_email");
        
        add_settings_field("tfgg_scp_update_employee", "Log Updates As:", "display_update_employee", "tfgg-general-options", "tfgg_general_options_section");
        register_setting("tfgg_general_options_section", "tfgg_scp_update_employee");
        
        add_settings_field("tfgg_scp_reg_promo", "Promo For Registration:", "display_reg_promo", "tfgg-general-options", "tfgg_general_options_section");
        register_setting("tfgg_general_options_section", "tfgg_scp_reg_promo");
        
        add_settings_field("tfgg_scp_demogrphics_allow", "Enable Demographics Updates:", "display_demogrphics_allow", "tfgg-general-options", "tfgg_general_options_section");
        register_setting("tfgg_general_options_section", "tfgg_scp_demogrphics_allow");
        
        add_settings_field("tfgg_scp_comm_pref_allow", "Enable Marketing Updates:", "display_comm_pref_allow", "tfgg-general-options", "tfgg_general_options_section");
        register_setting("tfgg_general_options_section", "tfgg_scp_comm_pref_allow");

        /*2019-06-18 - removed
        add_settings_field("tfgg_scp_registration_tandc", "Registration Terms and Conditions:", "display_tandc_editor", "tfgg-general-options", "tfgg_general_options_section");
        register_setting("tfgg_general_options_section", "tfgg_scp_registration_tandc");
        */

        add_settings_field("tfgg_scp_tandc_label", "Terms & Conditions Label:", "display_tandc_label","tfgg-general-options","tfgg_general_options_section");
        register_setting("tfgg_general_options_section","tfgg_scp_tandc_label");

        add_settings_field("tfgg_scp_marketing_optin_label", "Marketing Opt In Label:", "display_marketing_optin_label","tfgg-general-options","tfgg_general_options_section");
        register_setting("tfgg_general_options_section","tfgg_scp_marketing_optin_label");

        //2019-10-22 CB V1.1.1.3 - new registration field        
        add_settings_field("tfgg_scp_registration_source_label", "Registration Source Label:", "display_registration_source_label","tfgg-general-options","tfgg_general_options_section");
        register_setting("tfgg_general_options_section","tfgg_scp_registration_source_label");
        
        
        //tfgg_appointments_section
        add_settings_field("tfgg_scp_appointments_allow", "Enable Appointments:", "display_appointments_allow", "tfgg-appointments-options", "tfgg_appointments_section");
        register_setting("tfgg_appointments_section", "tfgg_scp_appointments_allow");
        
        add_settings_field("tfgg_scp_appointments_allowed_hrs", "Appts Must Be Booked Hrs In Advance:", "display_appointments_allowed_hrs", "tfgg-appointments-options", "tfgg_appointments_section");
        register_setting("tfgg_appointments_section", "tfgg_scp_appointments_allowed_hrs");
        
        add_settings_field("tfgg_scp_appointments_allow_cancel", "Allow Cancellations:", "display_appointments_allow_cancel", "tfgg-appointments-options", "tfgg_appointments_section");
        register_setting("tfgg_appointments_section", "tfgg_scp_appointments_allow_cancel");
        
        add_settings_field("tfgg_scp_appointments_cancel_allowed_hrs", "Appts Must Be Cancelled Hrs In Advance:", "display_appointments_cancel_allowed_hrs", "tfgg-appointments-options", "tfgg_appointments_section");
        register_setting("tfgg_appointments_section", "tfgg_scp_appointments_cancel_allowed_hrs");
        
        add_settings_field("tfgg_scp_appt_update_employee", "Log Updates As:", "display_appt_update_employee", "tfgg-appointments-options", "tfgg_appointments_section");
        register_setting("tfgg_appointments_section", "tfgg_scp_appt_update_employee");
        
        add_settings_field("tfgg_scp_appt_equip_dir", "Equipment Images Dir:", "display_appt_images_dir", "tfgg-appointments-options", "tfgg_appointments_section");
        register_setting("tfgg_appointments_section", "tfgg_scp_appt_equip_dir");

        //onscreen and in email messages
        add_settings_field("tfgg_scp_appts_success", "Successful Appointment Booking:", "display_appts_success", "tfgg-messages-options", "tfgg_messages_section");
        register_setting("tfgg_messages_section", "tfgg_scp_appts_success");

        add_settings_field("tfgg_scp_appts_fail", "Failed Appointment Booking:", "display_appts_fail", "tfgg-messages-options", "tfgg_messages_section");
        register_setting("tfgg_messages_section", "tfgg_scp_appts_fail");

        /* 2019-10-13 CB V1.1.1.1 - deprecated
        add_settings_field("tfgg_scp_email_pass_reset", "Password Reset Email:", "display_email_pass_rest", "tfgg-messages-options", "tfgg_messages_section");
        register_setting("tfgg_messages_section", "tfgg_scp_email_pass_reset");*/
        
    }
    
    add_action("admin_init", "tfgg_sunlync_cp_options");
    
    include('admin-menu/am-api.php');
    include('admin-menu/am-general.php');
    include('admin-menu/am-appointments.php');
    include('admin-menu/am-shortcodes.php');
    include('admin-menu/am-messages.php');

    function tfgg_sunlync_cp_admin_menu_option(){
        add_menu_page('Sunlync Customer Portal','Sunlync CP','manage_options','tfgg-sunlync-cp-admin-menu','tfgg_sunlync_cp_page','',5);
    }
    
    add_action('admin_menu','tfgg_sunlync_cp_admin_menu_option');
    
    /*function tfgg_sunlync_cp_demo_menu_option(){
        $menuLabel=get_option('tfgg_scp_demogrphics_menu_label');
        
        if($menuLabel==''){
            $menuLabel='Instore Account';
        }
        add_users_page($menuLabel,$menuLabel,'read','tfgg-sunlync-cp-demographics-menu','tfgg_sunlync_cp_demographics_page','',4);    
    }
    
    function tfgg_sunlync_cp_appt_menu_option(){
        $menuLabel=get_option('tfgg_scp_appointments_menu_label');
        
        if($menuLabel==''){
            $menuLabel='Appointments';
        }
        add_users_page($menuLabel,$menuLabel,'read','tfgg-sunlync-cp-appointments-menu','tfgg_sunlync_cp_appointments_page','',4);  
        
    }*/
    
    
    /*if(get_option('tfgg_scp_demogrphics_allow')==1){
        add_action('admin_menu','tfgg_sunlync_cp_demo_menu_option');
    }
    
    if(get_option('tfgg_scp_appointments_allow')==1){
        add_action('admin_menu','tfgg_sunlync_cp_appt_menu_option');
    }*/
    
    function tfgg_sunlync_cp_demographics_page(){
        
    }
    
    function tfgg_sunlync_cp_appointments_page(){
        
    }
    
    function tfgg_sunlync_cp_page(){
        $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'tfgg-shortcodes';
        ?>
            <div class="wrap">
                <h2>TFGG Sunlync Customer Portal</h2>
                <?php if( isset($_GET['settings-updated']) ) { ?>
                    <div id="message" class="updated">
                        <p><strong><?php _e('Settings saved.') ?></strong></p>
                    </div>
                <?php }?> 
            </div>
            <div class="nav-tab-wrapper">
                <a href="?page=tfgg-sunlync-cp-admin-menu&tab=tfgg-shortcodes" class="nav-tab <?php echo $active_tab == 'tfgg-shortcodes' ? 'nav-tab-active' : ''; ?>">Shortcodes & Redirect</a>
                <a href="?page=tfgg-sunlync-cp-admin-menu&tab=tfgg-api-options" class="nav-tab <?php echo $active_tab == 'tfgg-api-options' ? 'nav-tab-active' : ''; ?>">API Options</a>
                <a href="?page=tfgg-sunlync-cp-admin-menu&tab=tfgg-general-options" class="nav-tab <?php echo $active_tab == 'tfgg-general-options' ? 'nav-tab-active' : ''; ?>">General Options</a>
                <a href="?page=tfgg-sunlync-cp-admin-menu&tab=tfgg-appointments-options" class="nav-tab <?php echo $active_tab == 'tfgg-appointments-options' ? 'nav-tab-active' : ''; ?>">Appointments Options</a>
                <a href="?page=tfgg-sunlync-cp-admin-menu&tab=tfgg-messages-options" class="nav-tab <?php echo $active_tab == 'tfgg-messages-options' ? 'nav-tab-active' : ''; ?>">Message Text Options</a>
            </div>
            <form method="POST" action="options.php">
            <?php
                switch($active_tab){
                    case 'tfgg-shortcodes':
                        list_out_shortcodes();
                        ?>
                        -------------
                        <?php
                        settings_fields('tfgg_shortcodes');
                        do_settings_sections('tfgg-shortcodes');
                    break;
                    case 'tfgg-api-options':
                        ?>
                        <div id="tfgg-api-options-test-api-response" class="notice is-dismissible" style="display:none">
                            <p>API Responded With: <strong><span id="tfgg-api-test-response"></span></strong></p>
                        </div>
                        <?php
                        settings_fields('tfgg_api_section');
                        do_settings_sections('tfgg-api-options');
                        break;
                    case 'tfgg-general-options':
                        settings_fields('tfgg_general_options_section');
                        do_settings_sections('tfgg-general-options');
                        break;
                    case 'tfgg-appointments-options':
                        settings_fields('tfgg_appointments_section');
                        do_settings_sections('tfgg-appointments-options');
                        break;
                    case 'tfgg-messages-options':
                        settings_fields('tfgg_messages_section');
                        do_settings_sections('tfgg-messages-options');
                        break;
                }//switch
            ?>
            <input type="submit" class="button button-primary" value="Save Changes"/>
            </form>
            <?php
            if(($active_tab=='tfgg-api-options')&&
            (get_option('tfgg_scp_api_protocol')!='')&&
            (get_option('tfgg_scp_api_url')!='')&&
            (get_option('tfgg_scp_api_port')!='')&&
            (get_option('tfgg_scp_api_mrkt')!='')){
                ?>
                <br/><br/>
                <button class="button button-primary" onclick="TestAPICredentials()" value="Test Credentials" />Test Credentials</button>
                <?php
            }
            
    }

?>