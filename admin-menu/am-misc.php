<?php
    
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