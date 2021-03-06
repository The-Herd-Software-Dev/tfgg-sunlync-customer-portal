<?php

/**
 * Plugin Name: TFGG Sunlync Customer Portal
 * Description: Customer portal for TFGG Sunlync customers to manage demographic information and appointments
 * Version:     1.6.1.3
 * Author:      The Herd llc.
 */
    //ob_start();

    //2019-09-25 CB V1.0.0.3 - should only be set on the dev environment
    if($_SERVER['HTTP_HOST']=='localhost:8888'){   
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }  

    //2019-10-12 CB V1.1.1.1 - if no session exists, start one
    if(session_status() !== PHP_SESSION_ACTIVE){ session_start(['read_and_close'=>true]);}

    $dir = plugin_dir_url(__FILE__);
    require_once('functions.php');
    require_once('api-class.php');
    /*require_once('api-comm.php');*/
    require_once('tfgg-sunlync-cart.php');
    require_once('demographics-account-overview.php');
    require_once('appt-control.php');
    require_once('admin-menu.php');
    require_once('inc-shortcodes.php');
    require_once('register-files.php');
    require_once('tfgg-sunlync-errors.php');
    require_once('tfgg-sunlync-emp-dashboard.php');
    require_once('freebie-mrkting.php');

    //2019-09-23 CB V1.0.0.2 - added
    if ( is_admin()  ) {        
        new tfgg_scp_updater( __FILE__, 'The-Herd-Software-Dev', "tfgg-sunlync-customer-portal" );
    }

    //2020-03-22 CB V1.2.6.3
    register_activation_hook(__FILE__,  'tfgg_scp_upgrade_portal_tables');

    register_activation_hook(__FILE__,  'tfgg_scp_freebie_marketing_create_tbl');
    
?>