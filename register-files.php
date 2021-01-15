<?php

    function tfgg_cp_register_css() {
    	wp_register_style('tfgg-cp-form-css', plugin_dir_url( __FILE__ ) . '/css/forms.css');
    }
    add_action('init', 'tfgg_cp_register_css');
    
    //load the css if it if requested
    function tfgg_cp_print_css() {
    	global $tfgg_cp_load_css;
     
    	// this variable is set to TRUE if the short code is used on a page/post
    	if ( ! $tfgg_cp_load_css )
    		return; // this means that neither short code is present, so we get out of here
     
    	wp_print_styles('tfgg-cp-form-css');
    }
    add_action('wp_footer', 'tfgg_cp_print_css');
    
    add_action( 'wp_enqueue_scripts', 'load_api_scripts' );
    
    add_action( 'admin_enqueue_scripts', 'load_api_scripts' );
    function load_api_scripts(){   
        
        //2019-10-11 CB V1.0.1.4 - versioning added to css and js
        $apiJsV = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'js/api.js' ));
        $includeJsV = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'js/include.js' ));
        $wpStuleV = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'css/wp_style.css' ));

        wp_enqueue_script( 'tfgg-api-scripts', plugin_dir_url(__FILE__).'js/api.js', array( 'jquery' ), $apiJsV );
        wp_localize_script('tfgg-api-scripts', 'localAccess', array('pluginsUrl' => plugin_dir_url(__FILE__),
        'adminAjaxURL' => admin_url( 'admin-ajax.php' ),
        'acctOverview' => get_option('tfgg_scp_acct_overview'),
        'apptRedirect' => get_option('tfgg_scp_cpappt_success',get_option('tfgg_scp_acct_overview'))));
        
        wp_enqueue_script( 'tfgg-wp-scripts', plugin_dir_url(__FILE__).'js/include.js', array( 'jquery' ), $includeJsV );
        wp_localize_script('tfgg-wp-scripts', 'localAccess', array('pluginsUrl' => plugin_dir_url(__FILE__),
        'adminAjaxURL' => admin_url( 'admin-ajax.php' ),
        'acctOverview' => get_option('tfgg_scp_acct_overview'),
        'apptRedirect' => get_option('tfgg_scp_cpappt_success',get_option('tfgg_scp_acct_overview'))));
        
        wp_enqueue_style( 'tfgg-wp-styles', plugins_url( 'css/wp_style.css', __FILE__ ) );
        //wp_enqueue_script( 'boot1','https://code.jquery.com/jquery-3.3.1.slim.min.js', array( 'jquery' ),'' );
        wp_enqueue_script( 'boot3','https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js', array( 'jQuery'),'' );
        wp_enqueue_style('bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css');
        wp_enqueue_script( 'boot2','https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js', array( 'jQuery' ),'' );
        wp_enqueue_style( 'boot5','https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', '','' );
        

        wp_register_script( 'boot-script', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array( 'jquery' ) );
        wp_enqueue_script( 'boot-script' );

        wp_register_style( 'jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css', true);
        wp_enqueue_style( 'jquery-style' );
        //2021-01-14 CB V1.2.7.12 - changed to use the api script handle to prevent double load
        wp_register_script( 'jquery-ui-datepicker', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js', array( 'jquery' ) );
        wp_enqueue_script( 'jquery-ui-datepicker' );


        wp_register_style( 'select2-style', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css', true);
        wp_enqueue_style( 'select2-style' );
        wp_register_script( 'select2-script', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js', array( 'jquery' ) );
        wp_enqueue_script( 'select2-script' );
        
        //2019-11-24 CB
        if((get_option('tfgg_scp_cart_paypal_clientid')!='')&&(get_option('tfgg_scp_cart_allow_paypal_payment','0')==1)){
            wp_register_script('paypal-script','https://www.paypal.com/sdk/js?currency=GBP&client-id='.get_option('tfgg_scp_cart_paypal_clientid'),'',null);
            wp_enqueue_script('paypal-script');
        }

        /*if((get_option('tfgg_scp_cart_sage_key')!='')&&(get_option('tfgg_scp_cart_sage_pass')!='')){
            wp_register_script('sagepay-script','https://pi-test.sagepay.com/api/v1/js/sagepay.js','',null);
            wp_enqueue_script('sagepay-script');
        }*/
        
             
    }
    



?>