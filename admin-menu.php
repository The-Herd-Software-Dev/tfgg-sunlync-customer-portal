<?php
/*
* used to control the admin menu provided
*/

    function tfgg_sunlync_cp_options(){
        tfgg_scp_admin_api_options();
        tfgg_scp_admin_appointments_options();
        tfgg_scp_admin_cart_options();
        tfgg_scp_admin_cust_acct_options();
        tfgg_scp_admin_messages_options();
        tfgg_scp_admin_misc_options();
        tfgg_scp_admin_recaptcha_options();
        tfgg_scp_admin_registration_options();
        tfgg_scp_admin_registration_options_instore();
        tfgg_scp_admin_services_options();
        tfgg_scp_admin_shortcodes_options();
        tfgg_scp_admin_store_selection_options(); 
        tfgg_scp_admin_affiliate_marketing_options();       
    }
    
    add_action("admin_init", "tfgg_sunlync_cp_options");
    
    require_once('admin-menu/am-api.php');
    require_once('admin-menu/am-misc.php');
    require_once('admin-menu/am-registration.php');
    require_once('admin-menu/am-customer-acct.php');
    require_once('admin-menu/am-appointments.php');
    require_once('admin-menu/am-shortcodes.php');
    require_once('admin-menu/am-messages.php');
    require_once('admin-menu/am-store-selection.php');
    require_once('admin-menu/am-services.php');
    require_once('admin-menu/am-cart.php');
    require_once('admin-menu/am-card-tran-log.php');//2020-03-22 CB V1.2.6.3 - added
    require_once('admin-menu/am-recaptcha.php');//2021-04-13 CB V1.4.1.1 - added
    require_once('admin-menu/am-affiliate-marketing.php');//2021-04-13 CB V1.4.1.1 - added

    function tfgg_scp_enqueue_admin_styles_scripts(){
        wp_enqueue_style( 'tfgg-admin-styles', plugins_url( 'admin-menu/css/layout.css', __FILE__ ) );
    }
    add_action( 'admin_enqueue_scripts', 'tfgg_scp_enqueue_admin_styles_scripts' );


    add_action('admin_menu','tfgg_sunlync_cp_admin_menu_option');
    //change the order here to affect the order in the menu
    add_action('admin_menu', 'tfgg_scp_admin_submenu_shortcodes');
    add_action('admin_menu', 'tfgg_scp_admin_submenu_recaptcha');
    add_action('admin_menu', 'tfgg_scp_admin_submenu_stores_to_use');
    add_action('admin_menu', 'tfgg_scp_admin_submenu_registration');
    add_action('admin_menu', 'tfgg_scp_admin_submenu_appointments');
    add_action('admin_menu', 'tfgg_scp_admin_submenu_cart');
    add_action('admin_menu', 'tfgg_scp_admin_submenu_cart_services_for_sale');
    add_action('admin_menu', 'tfgg_scp_admin_submenu_customer_accts');
    add_action('admin_menu', 'tfgg_scp_admin_submenu_message_text');
    add_action('admin_menu', 'tfgg_scp_admin_submenu_affiliate_marketing');
    add_action('admin_menu', 'tfgg_scp_admin_submenu_misc');


    function tfgg_sunlync_cp_admin_menu_option(){
        add_menu_page('Sunlync Customer Portal',
        'Sunlync CP',
        'manage_options',
        'tfgg-sunlync-cp-admin-menu',
        'tfgg_sunlync_cp_page',
        '',
        5);
    }

    function tfgg_scp_admin_submenu_shortcodes(){
        add_submenu_page(
            'tfgg-sunlync-cp-admin-menu',
            'SCP Shortcodes',
            'Shortcodes &nbsp; Redirects',
            'manage_options',
            'tfgg-scp-admin-shortcodes',
            'tfgg_scp_admin_shortcodes'
        );
    }

    function tfgg_scp_admin_submenu_recaptcha(){
        add_submenu_page(
            'tfgg-sunlync-cp-admin-menu',
            'SCP reCaptcha',
            'Google reCaptcha',
            'manage_options',
            'tfgg-scp-admin-recaptcha',
            'tfgg_scp_admin_recaptcha'
        );
    }

    function tfgg_scp_admin_submenu_stores_to_use(){
        add_submenu_page(
            'tfgg-sunlync-cp-admin-menu',
            'SCP Stores to Use',
            'Stores To Use',
            'manage_options',
            'tfgg-scp-admin-stores-to-use',
            'tfgg_scp_admin_stores_to_use'
        );
    }

    function tfgg_scp_admin_submenu_registration(){
        add_submenu_page(
            'tfgg-sunlync-cp-admin-menu',
            'SCP Registration',
            'Registration',
            'manage_options',
            'tfgg-scp-admin-registration',
            'tfgg_scp_admin_registration'
        );
    }

    function tfgg_scp_admin_submenu_appointments(){
        add_submenu_page(
            'tfgg-sunlync-cp-admin-menu',
            'SCP Appointments',
            'Appointments',
            'manage_options',
            'tfgg-scp-admin-appointments',
            'tfgg_scp_admin_appointments'
        );
    }

    function tfgg_scp_admin_submenu_cart(){
        add_submenu_page(
            'tfgg-sunlync-cp-admin-menu',
            'SCP Cart',
            'Cart',
            'manage_options',
            'tfgg-scp-admin-cart',
            'tfgg_scp_admin_cart'
        );
    }

    function tfgg_scp_admin_submenu_cart_services_for_sale(){
        add_submenu_page(
            'tfgg-sunlync-cp-admin-menu',
            'SCP Services For Sale',
            'Services For Sale',
            'manage_options',
            'tfgg-scp-admin-cart-services-for-sale',
            'tfgg_scp_admin_cart_services_for_sale'
        );
    }

    function tfgg_scp_admin_submenu_customer_accts(){
        add_submenu_page(
            'tfgg-sunlync-cp-admin-menu',
            'SCP Customer Accts',
            'Customer Accts',
            'manage_options',
            'tfgg-scp-admin-customer-accts',
            'tfgg_scp_admin_customer_accts'
        );
    }

    function tfgg_scp_admin_submenu_message_text(){
        add_submenu_page(
            'tfgg-sunlync-cp-admin-menu',
            'SCP Messages',
            'Message Text',
            'manage_options',
            'tfgg-scp-admin-message-text',
            'tfgg_scp_admin_message_text'
        );
    }
    
    function tfgg_scp_admin_submenu_misc(){
        add_submenu_page(
            'tfgg-sunlync-cp-admin-menu',
            'SCP Misc.',
            'Misc.',
            'manage_options',
            'tfgg-scp-admin-misc',
            'tfgg_scp_admin_misc'
        );
    }

    function tfgg_scp_admin_submenu_affiliate_marketing(){
        add_submenu_page(
            'tfgg-sunlync-cp-admin-menu',
            'SCP Affil Mrkting.',
            'Affiliate Marketing',
            'manage_options',
            'tfgg-scp-affiliate-marketing',
            'tfgg_scp_affiliate_marketing'
        );
    }

    //this header should be added to all pages
    function tfgg_scp_admin_menu_header(){
        ?>
        <div class="container-fluid">
            <h2>TFGG Sunlync Customer Portal</h2>
            <?php if( isset($_GET['settings-updated']) ) { ?>
                <div id="message" class="updated">
                    <p><strong><?php _e('Settings saved.') ?></strong></p>
                </div>
            <?php }?> 
        </div>
        <?php
    }

    

?>