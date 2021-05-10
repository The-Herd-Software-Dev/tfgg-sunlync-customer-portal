<?php

    function tfgg_scp_admin_misc_options(){
        add_settings_section("tfgg_misc_options_section", '', null, "tfgg-misc-options");

        add_settings_field("tfgg_scp_customer_service_email", "Customer Service E-Mail:", "display_customer_service_email", "tfgg-misc-options", "tfgg_misc_options_section");
        register_setting("tfgg_misc_options_section", "tfgg_scp_customer_service_email");
    }

    function tfgg_scp_admin_misc(){
        tfgg_scp_admin_menu_header();
        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-8">
                    <div class="card">
                        <h5 class="card-header">Misc.</h5>
                        <div class="card-body">
                            <form method="POST" action="options.php">
                            <?php
                            settings_fields('tfgg_misc_options_section');
                            do_settings_sections('tfgg-misc-options');
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
        <?php
    }
    
    function display_customer_service_email(){
        ?>
        <input type="email" name="tfgg_scp_customer_service_email" value="<?php echo get_option('tfgg_scp_customer_service_email');?>" style="width: 60%"/>
        <?php
    }
    
    function display_demographics_title_label(){
        ?>
        <input type="text" name="tfgg_scp_demogrphics_title_label" value="<?php echo get_option('tfgg_scp_demogrphics_title_label'); ?>" size="70" />
        <?php
    }

    function display_tandc_editor(){
        $settings = array(
            'textarea_rows' => 15,
            'tabindex' => 1,
            'media_buttons' => false,
            'wpautop' => false
        );
        wp_editor( get_option('tfgg_scp_registration_tandc'), 'tfgg_scp_registration_tandc', $settings);        
    }
    
?>