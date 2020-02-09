<?php
/*
* used to control the admin menu provided
*/

    function tfgg_sunlync_cp_options(){
        //sections
        add_settings_section("tfgg_shortcodes", '', null, "tfgg-shortcodes");
        add_settings_section("tfgg_api_section", '', null, "tfgg-api-options");
        add_settings_section("tfgg_misc_options_section", '', null, "tfgg-misc-options");
        add_settings_section("tfgg_registration_options_section", '', null, "tfgg-registration-options");
        add_settings_section("tfgg_registration_options_section_instore", '', null, "tfgg-registration-options-instore");
        add_settings_section("tfgg_customer_acct_section", '', null, "tfgg-customer-acct-options");
        add_settings_section("tfgg_appointments_section", '', null, "tfgg-appointments-options");
        add_settings_section("tfgg_messages_section", '', null, "tfgg-messages-options");
        add_settings_section("tfgg_store_selection_section", '', null, "tfgg-store-selection");
        add_settings_section("tfgg_service_selection_section", '', null, "tfgg-service-selection");
        add_settings_section("tfgg_cart_section", '', null, "tfgg-cart-options");
        
        //tfgg_shortcodes
        add_settings_field("tfgg_scp_cplogin_page", "Login Page:", "display_cplogin_page", "tfgg-shortcodes", "tfgg_shortcodes");
        register_setting("tfgg_shortcodes", "tfgg_scp_cplogin_page");
        
        add_settings_field("tfgg_scp_cplogin_page_success", "Login Redirect:", "display_cplogin_page_success", "tfgg-shortcodes", "tfgg_shortcodes");
        register_setting("tfgg_shortcodes", "tfgg_scp_cplogin_page_success");
        
        add_settings_field("tfgg_scp_acct_overview", "Account Overview:", "display_acct_overview", "tfgg-shortcodes", "tfgg_shortcodes");
        register_setting("tfgg_shortcodes", "tfgg_scp_acct_overview");
        
        add_settings_field("tfgg_scp_cpnewuser_page", "Registration Page (Online):", "display_cpnewuser_page", "tfgg-shortcodes", "tfgg_shortcodes");
        register_setting("tfgg_shortcodes", "tfgg_scp_cpnewuser_page");

        add_settings_field("tfgg_scp_cpnewuser_success_page", "Registration Page (Online) Success:", "display_cpnewuser_success_page", "tfgg-shortcodes", "tfgg_shortcodes");
        register_setting("tfgg_shortcodes", "tfgg_scp_cpnewuser_success_page");

        add_settings_field("tfgg_scp_cpnewuser_page_instore", "Registration Page (instore):", "display_cpnewuser_page_instore", "tfgg-shortcodes", "tfgg_shortcodes");
        register_setting("tfgg_shortcodes", "tfgg_scp_cpnewuser_page_instore");
        
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

        add_settings_field("tfgg_scp_instore_registration_success", "Successful Instore Registration:", "display_successful_reg_instore", "tfgg-messages-options", "tfgg_messages_section");
        register_setting("tfgg_messages_section", "tfgg_scp_instore_registration_success");

        add_settings_field("tfgg_scp_instore_registration_validation_fail", "Instore Validation Fail:", "display_fail_validation_reg_instore", "tfgg-messages-options", "tfgg_messages_section");
        register_setting("tfgg_messages_section", "tfgg_scp_instore_registration_validation_fail");

        /* 2019-10-13 CB V1.1.1.1 - deprecated
        add_settings_field("tfgg_scp_email_pass_reset", "Password Reset Email:", "display_email_pass_rest", "tfgg-messages-options", "tfgg_messages_section");
        register_setting("tfgg_messages_section", "tfgg_scp_email_pass_reset");*/

        //2019-10-23 CB V1.1.2.1 - new fields
        add_settings_field("tfgg_scp_store_selection","Stores For Use:","display_tfgg_store_selection", "tfgg-store-selection", "tfgg_store_selection_section");
        register_setting("tfgg_store_selection_section","tfgg_scp_store_selection");

        //misc section
        add_settings_field("tfgg_scp_customer_service_email", "Customer Service E-Mail:", "display_customer_service_email", "tfgg-misc-options", "tfgg_misc_options_section");
        register_setting("tfgg_misc_options_section", "tfgg_scp_customer_service_email");

        //customer acct section
        add_settings_field("tfgg_scp_update_employee", "Log Updates As:", "display_update_employee", "tfgg-customer-acct-options", "tfgg_customer_acct_section");
        register_setting("tfgg_customer_acct_section", "tfgg_scp_update_employee");

        add_settings_field("tfgg_scp_demogrphics_allow", "Enable Demographics Updates:", "display_demogrphics_allow", "tfgg-customer-acct-options", "tfgg_customer_acct_section");
        register_setting("tfgg_customer_acct_section", "tfgg_scp_demogrphics_allow");
        
        add_settings_field("tfgg_scp_comm_pref_allow", "Enable Marketing Updates:", "display_comm_pref_allow", "tfgg-customer-acct-options", "tfgg_customer_acct_section");
        register_setting("tfgg_customer_acct_section", "tfgg_scp_comm_pref_allow");

        //registration section
        //online
        add_settings_field("tfgg_scp_cust_info_reg_title", "Registration Title:", "display_reg_title_online","tfgg-registration-options","tfgg_registration_options_section");
        register_setting("tfgg_registration_options_section","tfgg_scp_cust_info_reg_title");

        add_settings_field("tfgg_scp_tandc_label", "Terms & Conditions Label:", "display_tandc_label_online","tfgg-registration-options","tfgg_registration_options_section");
        register_setting("tfgg_registration_options_section","tfgg_scp_tandc_label");

        add_settings_field("tfgg_scp_marketing_optin_label", "Marketing Opt In Label:", "display_marketing_optin_label_online","tfgg-registration-options","tfgg_registration_options_section");
        register_setting("tfgg_registration_options_section","tfgg_scp_marketing_optin_label");
              
        add_settings_field("tfgg_scp_registration_source_label", "Registration Source:", "display_registration_source_label_online","tfgg-registration-options","tfgg_registration_options_section");
        register_setting("tfgg_registration_options_section","tfgg_scp_registration_source_label");

        add_settings_field("tfgg_scp_reg_promo", "Promo For Registration:", "display_reg_promo_online", "tfgg-registration-options", "tfgg_registration_options_section");
        register_setting("tfgg_registration_options_section", "tfgg_scp_reg_promo");

        add_settings_field("tfgg_scp_online_reg_recaptcha_req", "Google Recaptcha Required:", "display_reg_online_recaptcha_req", "tfgg-registration-options", "tfgg_registration_options_section");
        register_setting("tfgg_registration_options_section", "tfgg_scp_online_reg_recaptcha_req");

        //instore
        add_settings_field("tfgg_scp_cust_info_reg_title_instore", "Registration Title:", "display_reg_title_instore","tfgg-registration-options-instore","tfgg_registration_options_section_instore");
        register_setting("tfgg_registration_options_section_instore","tfgg_scp_cust_info_reg_title_instore");

        add_settings_field("tfgg_scp_tandc_label_instore", "Terms & Conditions:", "display_tandc_label_instore","tfgg-registration-options-instore","tfgg_registration_options_section_instore");
        register_setting("tfgg_registration_options_section_instore","tfgg_scp_tandc_label_instore");

        add_settings_field("tfgg_scp_tandc_slug_instore", "Terms & Conditions Slug:", "display_tandc_slug_instore","tfgg-registration-options-instore","tfgg_registration_options_section_instore");
        register_setting("tfgg_registration_options_section_instore","tfgg_scp_tandc_slug_instore");

        add_settings_field("tfgg_scp_marketing_optin_label_instore", "Marketing Opt In:", "display_marketing_optin_label_instore","tfgg-registration-options-instore","tfgg_registration_options_section_instore");
        register_setting("tfgg_registration_options_section_instore","tfgg_scp_marketing_optin_label_instore");

        add_settings_field("tfgg_scp_marketing_slug_instore", "Marketing Slug:", "display_marketing_slug_instore","tfgg-registration-options-instore","tfgg_registration_options_section_instore");
        register_setting("tfgg_registration_options_section_instore","tfgg_scp_marketing_slug_instore");

        add_settings_field("tfgg_scp_skin_type_info_slug_instore", "Skintype Info Slug:", "display_skin_type_info_slug", "tfgg-registration-options-instore", "tfgg_registration_options_section_instore");
        register_setting("tfgg_registration_options_section_instore", "tfgg_scp_skin_type_info_slug_instore");
        
        add_settings_field("tfgg_scp_registration_source_label_instore", "Registration Source:", "display_registration_source_label_instore","tfgg-registration-options-instore","tfgg_registration_options_section_instore");
        register_setting("tfgg_registration_options_section_instore","tfgg_scp_registration_source_label_instore");

        add_settings_field("tfgg_scp_reg_promo_instore", "Promo For Registration:", "display_reg_promo_instore", "tfgg-registration-options-instore", "tfgg_registration_options_section_instore");
        register_setting("tfgg_registration_options_section_instore", "tfgg_scp_reg_promo_instore");

        add_settings_field("tfgg_scp_instore_reg_recaptcha_req", "Google Recaptcha Required:", "display_reg_instore_recaptcha_req", "tfgg-registration-options-instore", "tfgg_registration_options_section_instore");
        register_setting("tfgg_registration_options_section_instore", "tfgg_scp_instore_reg_recaptcha_req");
        //2019-11-19 CB V1.2.4.2 - added
        add_settings_field("tfgg_scp_instore_reg_password_hint", "Instore Password Hint:", "display_password_hint_reg_instore", "tfgg-registration-options-instore", "tfgg_registration_options_section_instore");
        register_setting("tfgg_registration_options_section_instore", "tfgg_scp_instore_reg_password_hint");

        //service selection
        add_settings_field("tfgg_scp_services_sale_slug","Services Sale Slug:","display_tfgg_services_sale_slug", "tfgg-service-selection", "tfgg_service_selection_section");
        register_setting("tfgg_service_selection_section","tfgg_scp_services_sale_slug");

        add_settings_field("tfgg_scp_package_header_label","Package Display Header:","display_tfgg_package_header_label", "tfgg-service-selection", "tfgg_service_selection_section");
        register_setting("tfgg_service_selection_section","tfgg_scp_package_header_label");

        add_settings_field("tfgg_scp_package_allow_search","Allow Package Search:","display_tfgg_package_allow_search", "tfgg-service-selection", "tfgg_service_selection_section");
        register_setting("tfgg_service_selection_section","tfgg_scp_package_allow_search");

        add_settings_field("tfgg_scp_package_unit_minutes","Rename 'Minutes':","display_tfgg_package_units_minutes", "tfgg-service-selection", "tfgg_service_selection_section");
        register_setting("tfgg_service_selection_section","tfgg_scp_package_unit_minutes");

        add_settings_field("tfgg_scp_package_unit_sessions","Rename 'Sessions':","display_tfgg_package_units_sessions", "tfgg-service-selection", "tfgg_service_selection_section");
        register_setting("tfgg_service_selection_section","tfgg_scp_package_unit_sessions");

        add_settings_field("tfgg_scp_package_unit_credits","Rename 'Credits':","display_tfgg_package_units_credits", "tfgg-service-selection", "tfgg_service_selection_section");
        register_setting("tfgg_service_selection_section","tfgg_scp_package_unit_credits");

        add_settings_field("tfgg_scp_package_selection","Packages For Sale:","display_tfgg_package_selection", "tfgg-service-selection", "tfgg_service_selection_section");
        register_setting("tfgg_service_selection_section","tfgg_scp_package_selection");

        //add_settings_field("tfgg_scp_package_alias","Package Alias:","", "tfgg-service-selection", "tfgg_service_selection_section");
        register_setting("tfgg_service_selection_section","tfgg_scp_package_alias");
        register_setting("tfgg_service_selection_section","tfgg_scp_package_img");
        register_setting("tfgg_service_selection_section","tfgg_scp_package_free_text");

        add_settings_field("tfgg_scp_membership_header_label","Membership Display Header:","display_tfgg_membership_header_label", "tfgg-service-selection", "tfgg_service_selection_section");
        register_setting("tfgg_service_selection_section","tfgg_scp_membership_header_label");

        add_settings_field("tfgg_scp_membership_allow_search","Allow Membership Search:","display_tfgg_membership_allow_search", "tfgg-service-selection", "tfgg_service_selection_section");
        register_setting("tfgg_service_selection_section","tfgg_scp_membership_allow_search");

        add_settings_field("tfgg_scp_membership_selection","Memberships For Sale:","display_tfgg_membership_selection", "tfgg-service-selection", "tfgg_service_selection_section");
        register_setting("tfgg_service_selection_section","tfgg_scp_membership_selection");

        //add_settings_field("tfgg_scp_membership_alias","Membership Alias:","", "tfgg-service-selection", "tfgg_service_selection_section");
        register_setting("tfgg_service_selection_section","tfgg_scp_membership_alias");
        register_setting("tfgg_service_selection_section","tfgg_scp_membership_img");
        register_setting("tfgg_service_selection_section","tfgg_scp_membership_free_text");

        //cart options
        add_settings_field("tfgg_scp_cart_slug","Cart Slug:","display_tfgg_cart_slug", "tfgg-cart-options", "tfgg_cart_section");
        register_setting("tfgg_cart_section","tfgg_scp_cart_slug");

        add_settings_field("tfgg_scp_cart_menu_link_text","Cart Menu Link Label:","display_tfgg_menu_link_label", "tfgg-cart-options", "tfgg_cart_section");
        register_setting("tfgg_cart_section","tfgg_scp_cart_menu_link_text");

        add_settings_field("tfgg_scp_cart_employee","Process Transactions As:","display_tfgg_cart_employee", "tfgg-cart-options", "tfgg_cart_section");
        register_setting("tfgg_cart_section","tfgg_scp_cart_employee");

        add_settings_field("tfgg_scp_cart_max_item_count","Max item count:","display_tfgg_cart_max_item_count", "tfgg-cart-options", "tfgg_cart_section");
        register_setting("tfgg_cart_section","tfgg_scp_cart_max_item_count");

        add_settings_field("tfgg_scp_cart_allow_paypal_payment","Allow PayPal Payments:","display_tfgg_allow_paypal_payment", "tfgg-cart-options", "tfgg_cart_section");
        register_setting("tfgg_cart_section","tfgg_scp_cart_allow_paypal_payment");

        add_settings_field("tfgg_scp_cart_paypal_tandc_label","PayPal T&C Label:","display_tfgg_paypal_tand_label", "tfgg-cart-options", "tfgg_cart_section");
        register_setting("tfgg_cart_section","tfgg_scp_cart_paypal_tandc_label");

        add_settings_field("tfgg_scp_cart_paypal_payment","Process Paypal Payments As:","display_tfgg_paypal_payment", "tfgg-cart-options", "tfgg_cart_section");
        register_setting("tfgg_cart_section","tfgg_scp_cart_paypal_payment");

        add_settings_field("tfgg_scp_cart_paypal_clientid","PayPal Client ID:","display_tfgg_paypal_clientid", "tfgg-cart-options", "tfgg_cart_section");
        register_setting("tfgg_cart_section","tfgg_scp_cart_paypal_clientid");

        add_settings_field("tfgg_scp_cart_save_demographics","Allow Save Demographics:","display_tfgg_save_cart_demographics", "tfgg-cart-options", "tfgg_cart_section");
        register_setting("tfgg_cart_section","tfgg_scp_cart_save_demographics");

        add_settings_field("tfgg_save_cart_demographics_label","Save Demographics Label:","display_tfgg_save_cart_demographics_label", "tfgg-cart-options", "tfgg_cart_section");
        register_setting("tfgg_cart_section","tfgg_save_cart_demographics_label");

        add_settings_field("tfgg_scp_cart_save_commpref","Allow Save Comm Pref:","display_tfgg_save_cart_commpref", "tfgg-cart-options", "tfgg_cart_section");
        register_setting("tfgg_cart_section","tfgg_scp_cart_save_commpref");

        add_settings_field("tfgg_scp_cart_save_commpref_label","Save Comm Pref Label:","display_tfgg_scp_cart_save_commpref_label", "tfgg-cart-options", "tfgg_cart_section");
        register_setting("tfgg_cart_section","tfgg_scp_cart_save_commpref_label");

        add_settings_field("tfgg_scp_cart_allow_sage_payment","Allow SagePay Payments:","display_tfgg_allow_sage_payment", "tfgg-cart-options", "tfgg_cart_section");
        register_setting("tfgg_cart_section","tfgg_scp_cart_allow_sage_payment");

        add_settings_field("tfgg_scp_cart_sage_payment","Process SagePay Payments As:","display_tfgg_sage_payment", "tfgg-cart-options", "tfgg_cart_section");
        register_setting("tfgg_cart_section","tfgg_scp_cart_sage_payment");

        add_settings_field("tfgg_scp_cart_sage_pay_sandbox","Use SagePay Sandbox:","display_tfgg_sage_pay_sandbox", "tfgg-cart-options", "tfgg_cart_section");
        register_setting("tfgg_cart_section","tfgg_scp_cart_sage_pay_sandbox");


        add_settings_field("tfgg_scp_cart_sage_vendor_name","SagePay Vendor Name:","display_tfgg_sage_vendor_name", "tfgg-cart-options", "tfgg_cart_section");
        register_setting("tfgg_cart_section","tfgg_scp_cart_sage_vendor_name");

        add_settings_field("tfgg_scp_cart_sage_vendor_name","SagePay Vendor Name:","display_tfgg_sage_vendor_name", "tfgg-cart-options", "tfgg_cart_section");
        register_setting("tfgg_cart_section","tfgg_scp_cart_sage_vendor_name");

        add_settings_field("tfgg_scp_cart_sage_key","SagePay Integration Key:","display_tfgg_sage_key", "tfgg-cart-options", "tfgg_cart_section");
        register_setting("tfgg_cart_section","tfgg_scp_cart_sage_key");

        add_settings_field("tfgg_scp_cart_sage_pass","SagePay Integration Password:","display_tfgg_sage_pass", "tfgg-cart-options", "tfgg_cart_section");
        register_setting("tfgg_cart_section","tfgg_scp_cart_sage_pass");

        add_settings_field("tfgg_scp_cart_success_message","Successful Cart Message:","display_tfgg_successful_cart_message", "tfgg-cart-options", "tfgg_cart_section");
        register_setting("tfgg_cart_section","tfgg_scp_cart_success_message");
        
    }
    
    add_action("admin_init", "tfgg_sunlync_cp_options");
    
    include('admin-menu/am-api.php');
    include('admin-menu/am-misc.php');
    include('admin-menu/am-registration.php');
    include('admin-menu/am-customer-acct.php');
    include('admin-menu/am-appointments.php');
    include('admin-menu/am-shortcodes.php');
    include('admin-menu/am-messages.php');
    include('admin-menu/am-store-selection.php');
    include('admin-menu/am-services.php');
    include('admin-menu/am-cart.php');

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
                <a href="?page=tfgg-sunlync-cp-admin-menu&tab=tfgg-api-options" class="nav-tab <?php echo $active_tab == 'tfgg-api-options' ? 'nav-tab-active' : ''; ?>">API Connection</a>
                <a href="?page=tfgg-sunlync-cp-admin-menu&tab=tfgg-store-selection" class="nav-tab <?php echo $active_tab == 'tfgg-store-selection' ? 'nav-tab-active' : ''; ?>">Stores To Use</a>
                <a href="?page=tfgg-sunlync-cp-admin-menu&tab=tfgg-online-registration" class="nav-tab <?php echo $active_tab == 'tfgg-online-registration' ? 'nav-tab-active' : ''; ?>">Online Registration</a>
                <a href="?page=tfgg-sunlync-cp-admin-menu&tab=tfgg-instore-registration" class="nav-tab <?php echo $active_tab == 'tfgg-instore-registration' ? 'nav-tab-active' : ''; ?>">Instore Registration</a>
                <a href="?page=tfgg-sunlync-cp-admin-menu&tab=tfgg-customer-acct-options" class="nav-tab <?php echo $active_tab == 'tfgg-customer-acct-options' ? 'nav-tab-active' : ''; ?>">Customer Acct</a>
                <a href="?page=tfgg-sunlync-cp-admin-menu&tab=tfgg-appointments-options" class="nav-tab <?php echo $active_tab == 'tfgg-appointments-options' ? 'nav-tab-active' : ''; ?>">Appointments</a>
                <a href="?page=tfgg-sunlync-cp-admin-menu&tab=tfgg-messages-options" class="nav-tab <?php echo $active_tab == 'tfgg-messages-options' ? 'nav-tab-active' : ''; ?>">Message Text</a>
                <a href="?page=tfgg-sunlync-cp-admin-menu&tab=tfgg-service-options" class="nav-tab <?php echo $active_tab == 'tfgg-service-options' ? 'nav-tab-active' : ''; ?>">Services For Sale</a>
                <a href="?page=tfgg-sunlync-cp-admin-menu&tab=tfgg-cart-options" class="nav-tab <?php echo $active_tab == 'tfgg-cart-options' ? 'nav-tab-active' : ''; ?>">Cart</a>
                <a href="?page=tfgg-sunlync-cp-admin-menu&tab=tfgg-misc-options" class="nav-tab <?php echo $active_tab == 'tfgg-misc-options' ? 'nav-tab-active' : ''; ?>">Misc.</a>
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
                    case 'tfgg-customer-acct-options':
                        settings_fields('tfgg_customer_acct_section');
                        do_settings_sections('tfgg-customer-acct-options');
                        break;
                    case 'tfgg-online-registration':
                        display_registration_settings_title_online();
                        settings_fields('tfgg_registration_options_section');
                        do_settings_sections('tfgg-registration-options');
                        break;
                    case 'tfgg-instore-registration':
                        display_registration_settings_title_instore();
                        settings_fields('tfgg_registration_options_section_instore');
                        do_settings_sections('tfgg-registration-options-instore'); 
                        break;
                    case 'tfgg-appointments-options':
                        settings_fields('tfgg_appointments_section');
                        do_settings_sections('tfgg-appointments-options');
                        break;
                    case 'tfgg-messages-options':
                        settings_fields('tfgg_messages_section');
                        do_settings_sections('tfgg-messages-options');
                        break;
                    case 'tfgg-misc-options':
                        settings_fields('tfgg_misc_options_section');
                        do_settings_sections('tfgg-misc-options');
                        break;
                    //2019-10-23 CB V1.1.2.1 - new setting section
                    case 'tfgg-store-selection':
                        tfgg_scp_store_selction_description();
                        settings_fields('tfgg_store_selection_section');
                        do_settings_sections('tfgg-store-selection');
                        break;
                    //2019-11-11 CB V2.0.0.1 - new setting section for cart integration
                    case 'tfgg-service-options':
                        tfgg_scp_service_selction_description();
                        settings_fields('tfgg_service_selection_section');
                        do_settings_sections('tfgg-service-selection');
                        break;
                    case 'tfgg-cart-options':
                        settings_fields('tfgg_cart_section');
                        do_settings_sections('tfgg-cart-options');
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