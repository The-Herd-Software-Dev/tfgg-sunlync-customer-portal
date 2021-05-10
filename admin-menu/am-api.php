<?php

    function tfgg_scp_admin_api_options(){
        add_settings_section("tfgg_api_section", '', null, "tfgg-api-options");

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
    }

    function tfgg_sunlync_cp_page(){
        tfgg_scp_admin_menu_header();
        ?>
        <div class="container-fluid">
        <div class="row">
            <div class="col-sm-11 col-md-8">
                <div class="card">
                    <h5 class="card-header">SunLync API Connection</h5>
                    <div class="card-body">
                        <p class="card-text">Please enter the URL and Credentials for the SunLync API connection</p>
                        <form method="post" action="options.php">
                        <?php
                        settings_fields('tfgg_scp_api_section');
                        do_settings_sections('tfgg-api-options');
                        ?>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="form-group col-12">
                                    <button type="submit" class="btn btn-primary"><?php echo __('Save Settings');?></button>
                                </div>
                            </div>
                        </div>
                        </form>
                        <?php
                        if((get_option('tfgg_scp_api_url','')!='')&&(get_option('tfgg_scp_api_port','')!='')
                        &&(get_option('tfgg_scp_api_user','')!='')&&(get_option('tfgg_scp_api_pass','')!='')){
                        ?>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="form-group col-12">
                                    <button type="submit" onclick="TestAPICredentials()" class="btn btn-primary"><?php echo __('Test Credentials');?></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-rom">
                            <div class="form-group col-md-6">
                                <div id="tfgg-api-options-test-api-response" class="notice is-dismissible" style="display:none">
                                    <p>API Responded With: <strong><span id="tfgg-api-test-response"></span></strong></p>
                                </div>  
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    }

    function tfgg_scp_api_description(){
        echo '<p>API Settings</p>';
    }
    
    function display_tfgg_api_protocol(){
        ?>
        <input type="text" name="tfgg_scp_api_protocol" value="<?php echo get_option('tfgg_scp_api_protocol'); ?>" style="width: 60%" />
        <?php
    }
    
    function display_tfgg_api_url(){
        ?>
        <input type="text" name="tfgg_scp_api_url" value="<?php echo get_option('tfgg_scp_api_url'); ?>" style="width: 60%" />
        <?php
    }
    
    function display_tfgg_api_port(){
        ?>
        <input type="number" name="tfgg_scp_api_port" value="<?php echo get_option('tfgg_scp_api_port'); ?>" style="width: 30%" />
        <?php
    }
    
    function display_tfgg_api_market(){
        ?>
        <input type="text" name="tfgg_scp_api_mrkt" value="<?php echo get_option('tfgg_scp_api_mrkt'); ?>" style="width: 60%" />
        <?php
    }
    
    function display_tfgg_api_user(){
        ?>
        <input type="text" name="tfgg_scp_api_user" value="<?php echo get_option('tfgg_scp_api_user'); ?>" style="width: 60%" />
        <?php
    }
    
    function display_tfgg_api_pass(){
        ?>
        <input type="password" name="tfgg_scp_api_pass" value="<?php echo get_option('tfgg_scp_api_pass'); ?>" style="width: 60%" />
        <?php
    }

?>