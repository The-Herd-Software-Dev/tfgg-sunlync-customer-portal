<?php

    function tfgg_scp_admin_shortcodes_options(){
        add_settings_section("tfgg_shortcodes", '', null, "tfgg-shortcodes");
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
        
        add_settings_field("tfgg_scp_cpappt_success", "Appt Booking Success Redirect:", "display_cpappt_redirect", "tfgg-shortcodes", "tfgg_shortcodes");
        register_setting("tfgg_shortcodes", "tfgg_scp_cpappt_success"); 
    }

    function tfgg_scp_admin_shortcodes(){
        tfgg_scp_admin_menu_header();
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-11">
                <div class="card">
                    <h5 class="card-header">Shortcodes</h5>
                    <div class="card-body">
                        <p class="card-text">These are the shortcodes to use throughout your site to output the necessary forms/pages onscreen</p>
                        <p class="card-text">Please feel free to place any custom content before on after the shortcode on your page, it will not affect the output</p>
                        <hr/>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="row shortcode">
                                    <div class="col-sm-12 col-md-4">
                                        <strong>Login Form:</strong>
                                    </div>
                                    <div class="col-sm-12 col-md-8">
                                        [cp_sunlync_loginform]
                                    </div>
                                </div>
                                <div class="row shortcode">
                                    <div class="col-sm-12 col-md-4">
                                        <strong>Online Registration:</strong>
                                    </div>
                                    <div class="col-sm-12 col-md-8">
                                        [cp_sunlync_registrationform_new]
                                    </div>
                                </div>
                                <div class="row shortcode">
                                    <div class="col-sm-12 col-md-4">
                                        <strong>Instore Registration:</strong>
                                    </div>
                                    <div class="col-sm-12 col-md-8">
                                        [cp_sunlync_registrationform_instore]
                                    </div>
                                </div>
                                <div class="row shortcode">
                                    <div class="col-sm-12 col-md-4">
                                        <strong>Set 'Instore' Store:</strong>
                                    </div>
                                    <div class="col-sm-12 col-md-8">
                                        [cp_sunlync_registrationform_instore_setstore]
                                    </div>
                                </div>
                                <div class="row shortcode">
                                    <div class="col-sm-12 col-md-4">
                                        <strong>Customer Demographics:</strong>
                                    </div>
                                    <div class="col-sm-12 col-md-8">
                                        [cp_sunlync_demographics]
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="row shortcode">
                                    <div class="col-sm-12 col-md-4">
                                        <strong>Appointments:</strong>
                                    </div>
                                    <div class="col-sm-12 col-md-8">
                                        [cp_sunlync_appts]
                                    </div>
                                </div>
                                <div class="row shortcode">
                                    <div class="col-sm-12 col-md-4">
                                        <strong>Purchase Services:</strong>
                                    </div>
                                    <div class="col-sm-12 col-md-8">
                                        [cp_sunlync_cart_services]
                                    </div>
                                </div>
                                <div class="row shortcode">
                                    <div class="col-sm-12 col-md-4">
                                        <strong>Shopping Cart:</strong>
                                    </div>
                                    <div class="col-sm-12 col-md-8">
                                        [cp_sunlync_cart]
                                    </div>
                                </div>
                                <div class="row shortcode">
                                    <div class="col-sm-12 col-md-4">
                                        <strong>Shopping Cart Success:</strong>
                                    </div>
                                    <div class="col-sm-12 col-md-8">
                                        [cp_sunlync_success_cart]
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-11">
                <div class="card">
                    <h5 class="card-header">Redirects</h5>
                    <div class="card-body">
                        <p class="card-text">Please enter the corresponding page slugs so we may redirect users appropriately</p>
                        <form method="POST" action="options.php">
                        <?php
                        settings_fields('tfgg_shortcodes');
                        do_settings_sections('tfgg-shortcodes');
                        ?>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="form-group col-12">
                                    <button type="submit" class="btn btn-primary"><?php echo __('Save Settings');?></button>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    }
    
    function list_out_shortcodes(){
        ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">Login Form:</th>
                    <td>[cp_sunlync_loginform]</td>
                </tr>
                <tr>
                    <th scope="row">Registration Form (online):</th>
                    <td>[cp_sunlync_registrationform_new]</td>
                </tr>
                <tr>
                    <th scope="row">Registration Form (instore):</th>
                    <td>[cp_sunlync_registrationform_instore]</td>
                </tr>
                <tr>
                    <th scope="row">Set Enforced Store (instore):</th>
                    <td>[cp_sunlync_registrationform_instore_setstore]</td>
                </tr>
                <tr>
                    <th scope="row">Demographics:</th>
                    <td>[cp_sunlync_demographics]</td>
                </tr>
                <tr>
                    <th scope="row">Appointments:</th>
                    <td>[cp_sunlync_appts]</td>
                </tr>
                <tr>
                    <th scope="row">Purchase Services: </th>
                    <td>[cp_sunlync_cart_services]</td>
                </tr>
                <tr>
                    <th scope="row">Shopping Cart: </th>
                    <td>[cp_sunlync_cart]</td>
                </tr>
                <tr>
                    <th scope="row">Processed Shopping Cart: </th>
                    <td>[cp_sunlync_success_cart]</td>
                </tr>
            </tbody>
        </table>
        <?php
    }
    
    function display_cplogin_page(){
        ?>
        <input type="text" name="tfgg_scp_cplogin_page" value="<?php echo get_option('tfgg_scp_cplogin_page'); ?>" style="width: 60%" />
        <?php
    }
    function display_cplogin_page_success(){
        ?>
        <input type="text" name="tfgg_scp_cplogin_page_success" value="<?php echo get_option('tfgg_scp_cplogin_page_success'); ?>" style="width: 60%" />
        <?php
    }
    
    function display_cpnewuser_page(){
        ?>
        <input type="text" name="tfgg_scp_cpnewuser_page" value="<?php echo get_option('tfgg_scp_cpnewuser_page'); ?>" style="width: 60%" />
        <?php
    }

    function display_cpnewuser_success_page(){
        ?>
        <input type="text" name="tfgg_scp_cpnewuser_success_page" value="<?php echo get_option('tfgg_scp_cpnewuser_success_page'); ?>" style="width: 60%" />
        <?php    
    }

    function display_cpnewuser_page_instore(){
        ?>
        <input type="text" name="tfgg_scp_cpnewuser_page_instore" value="<?php echo get_option('tfgg_scp_cpnewuser_page_instore'); ?>" style="width: 60%" />
        <?php
    }
    
    function display_cpexistinguser_page(){
        ?>
        <input type="text" name="tfgg_scp_cpexistinguser_page" value="<?php echo get_option('tfgg_scp_cpexistinguser_page'); ?>" style="width: 60%" />
        <?php
    }
    
    function display_acct_overview(){
        ?>
        <input type="text" name="tfgg_scp_acct_overview" value="<?php echo get_option('tfgg_scp_acct_overview'); ?>" style="width: 60%" />
        <?php   
    }
    
    function display_cpappt_page(){
        ?>
        <input type="text" name="tfgg_scp_cpappt_page" value="<?php echo get_option('tfgg_scp_cpappt_page'); ?>" style="width: 60%" />
        <?php   
    }

    function display_cpappt_redirect(){
        ?>
        <input type="text" name="tfgg_scp_cpappt_success" value="<?php echo get_option('tfgg_scp_cpappt_success'); ?>" style="width: 60%" />
        <?php
    }
    
?>
