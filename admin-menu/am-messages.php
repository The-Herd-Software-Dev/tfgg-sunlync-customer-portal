<?php

    function tfgg_scp_admin_messages_options(){
        add_settings_section("tfgg_messages_section", '', null, "tfgg-messages-options");
        
        add_settings_field("tfgg_scp_appts_success", "Successful Appointment Booking:", "display_appts_success", "tfgg-messages-options", "tfgg_messages_section");
        register_setting("tfgg_messages_section", "tfgg_scp_appts_success");

        add_settings_field("tfgg_scp_appts_fail", "Failed Appointment Booking:", "display_appts_fail", "tfgg-messages-options", "tfgg_messages_section");
        register_setting("tfgg_messages_section", "tfgg_scp_appts_fail");

        add_settings_field("tfgg_scp_instore_registration_success", "Successful Instore Registration:", "display_successful_reg_instore", "tfgg-messages-options", "tfgg_messages_section");
        register_setting("tfgg_messages_section", "tfgg_scp_instore_registration_success");

        add_settings_field("tfgg_scp_instore_registration_validation_fail", "Instore Validation Fail:", "display_fail_validation_reg_instore", "tfgg-messages-options", "tfgg_messages_section");
        register_setting("tfgg_messages_section", "tfgg_scp_instore_registration_validation_fail");
    }

    function tfgg_scp_admin_message_text(){
        tfgg_scp_admin_menu_header();
        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-11">
                    <div class="card">
                        <h5 class="card-header">Messages</h5>
                        <div class="card-body">
                            <form method="POST" action="options.php">
                            <?php
                            settings_fields('tfgg_messages_section');
                            do_settings_sections('tfgg-messages-options');
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

function display_appts_success(){
    $settings = array(
        'textarea_rows' => 15,
        'tabindex' => 1,
        'media_buttons' => false,
        'wpautop' => false
    );
    wp_editor( get_option('tfgg_scp_appts_success'), 'tfgg_scp_appts_success', $settings); 

    if(wp_is_mobile()){
    ?>
    <div>
    <?php }else{ ?>
    <div style="font-size: small">
    <?php } ?>
        <p>Placeholders: <ul>
        <li>!@#store#@! -> The appointment store location</li>
        <li>!@#apptdate#@! -> The date of the appointment</li>
        <li>!@#appttime#@! -> The time of the appointment</li>
        </ul></p>
    </div>
    <?php
}

function display_successful_reg_instore(){
    $settings = array(
        'textarea_rows' => 15,
        'tabindex' => 1,
        'media_buttons' => false,
        'wpautop' => false
    );
    wp_editor( get_option('tfgg_scp_instore_registration_success'), 'tfgg_scp_instore_registration_success', $settings); 
    if(wp_is_mobile()){
    ?>
    <div>
    <?php }else{ ?>
    <div style="font-size: small">
    <?php } ?>
        <p>Placeholders: <ul>
        <li>!@#firstname#@! -> First Name</li>
        <li>!@#lastname#@! -> Last Name</li>
        <li>!@#clientnumber#@! -> Client Number</li>
        </ul></p>
    </div>
    <?php
}

function display_fail_validation_reg_instore(){
    $settings = array(
        'textarea_rows' => 15,
        'tabindex' => 1,
        'media_buttons' => false,
        'wpautop' => false
    );
    wp_editor( get_option('tfgg_scp_instore_registration_validation_fail'), 'tfgg_scp_instore_registration_validation_fail', $settings);    
}

function display_password_hint_reg_instore(){
    $settings = array(
        'textarea_rows' => 15,
        'tabindex' => 1,
        'media_buttons' => false,
        'wpautop' => false
    );
    wp_editor( get_option('tfgg_scp_instore_reg_password_hint'), 'tfgg_scp_instore_reg_password_hint', $settings);    
}

function display_appts_fail(){
    $settings = array(
        'textarea_rows' => 15,
        'tabindex' => 1,
        'media_buttons' => false,
        'wpautop' => false
    );
    wp_editor( get_option('tfgg_scp_appts_fail'), 'tfgg_scp_appts_fail', $settings); 
}

function display_email_pass_rest(){
    $settings = array(
        'textarea_rows' => 15,
        'tabindex' => 1,
        'media_buttons' => false,
        'wpautop' => false
    );
    wp_editor( get_option('tfgg_scp_email_pass_reset'), 'tfgg_scp_email_pass_reset', $settings); 
    if(wp_is_mobile()){
    ?>
    <div>
    <?php }else{ ?>
    <div style="font-size: small">
    <?php } ?>
        <p>Placeholders: <ul>
        <li>!@#url#@! -> Reset password link</li>
        <li>!@#sitename#@! -> Site Name</li>
        <li>!@#username#@! -> For the account being reset</li>
        </ul></p>
    </div>
    <?php
}
?>