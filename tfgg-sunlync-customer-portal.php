<?php

/**
 * Plugin Name: TFGG Sunlync Customer Portal
 * Plugin URI: http://theherdsoftware.com
 * Description: Customer portal for TFGG Sunlync customers to manage demographic information and appointments
 * Version:     1.0.0.1b
 * Author:      The Herd llc.
 * Author URI: http://theherdsoftware.com
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


    require_once( 'TheHerdGitHubPluginUpdater.php' );
    if ( is_admin() ) {
        new TheHerdGitHubPluginUpdater( __FILE__, 'The-Herd-Software-Dev', "tfgg-scp-customer-portal" );
    }
    
?>