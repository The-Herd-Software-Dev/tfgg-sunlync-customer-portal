<?php

/**
 * Plugin Name: TFGG Sunlync Customer Portal
 * Description: Customer portal for TFGG Sunlync customers to manage demographic information and appointments
 * Version:     1.0.0.2
 * Author:      The Herd llc.
 */
    //ob_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    $dir = plugin_dir_url(__FILE__);
    require_once('functions.php');
    require_once('api-class.php');
    /*require_once('api-comm.php');*/
    require_once('demographics-account-overview.php');
    require_once('appt-control.php');
    require_once('admin-menu.php');
    require_once('inc-shortcodes.php');
    require_once('register-files.php');
    require_once('tfgg-sunlync-errors.php');

    //2019-09-23 CB V1.0.0.2b - added
    if ( is_admin() ) {
        new tfgg_scp_updater( __FILE__, 'The-Herd-Software-Dev', "tfgg-scp-customer-portal" );
    }
    
?>