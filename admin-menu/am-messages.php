<?php

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