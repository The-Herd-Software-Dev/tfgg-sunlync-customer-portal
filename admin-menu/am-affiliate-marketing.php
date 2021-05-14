<?php

    function tfgg_scp_admin_affiliate_marketing_options(){
        add_settings_section("tfgg_awin_affiliate_marketing_section", '', null, "tfgg-awin-affiliate-marketing-options");

        add_settings_field("tfgg_scp_enable_awin_mrkting", "Enable AWIN:", "display_tfgg_enable_awin", "tfgg-awin-affiliate-marketing-options", "tfgg_awin_affiliate_marketing_section");
        register_setting("tfgg_awin_affiliate_marketing_section", "tfgg_scp_enable_awin_mrkting");

        add_settings_field("tfgg_scp_awin_merchant_id", "Merchant ID:", "display_tfgg_scp_awin_merchant_id", "tfgg-awin-affiliate-marketing-options", "tfgg_awin_affiliate_marketing_section");
        register_setting("tfgg_awin_affiliate_marketing_section", "tfgg_scp_awin_merchant_id");

        add_settings_field("tfgg_scp_awin_channel", "Channel:", "display_tfgg_scp_awin_channel", "tfgg-awin-affiliate-marketing-options", "tfgg_awin_affiliate_marketing_section");
        register_setting("tfgg_awin_affiliate_marketing_section", "tfgg_scp_awin_channel");

        add_settings_field("tfgg_scp_awin_reg_lead_desc", "Registration Lead Description:", "display_tfgg_scp_awin_reg_lead_desc", "tfgg-awin-affiliate-marketing-options", "tfgg_awin_affiliate_marketing_section");
        register_setting("tfgg_awin_affiliate_marketing_section", "tfgg_scp_awin_reg_lead_desc");

        add_settings_field("tfgg_scp_awin_reg_amt", "Reagistration Amt:", "display_tfgg_scp_awin_reg_amt", "tfgg-awin-affiliate-marketing-options", "tfgg_awin_affiliate_marketing_section");
        register_setting("tfgg_awin_affiliate_marketing_section", "tfgg_scp_awin_reg_amt");

        add_settings_field("tfgg_scp_awin_trans_lead_desc", "Transaction Lead Description:", "display_tfgg_scp_awin_trans_lead_desc", "tfgg-awin-affiliate-marketing-options", "tfgg_awin_affiliate_marketing_section");
        register_setting("tfgg_awin_affiliate_marketing_section", "tfgg_scp_awin_trans_lead_desc");

        add_settings_section("tfgg_google_affiliate_marketing_section", '', null, "tfgg-google-affiliate-marketing-options");

        add_settings_field("tfgg_scp_enable_google_mrkting", "Enable Google:", "display_tfgg_enable_google", "tfgg-google-affiliate-marketing-options", "tfgg_google_affiliate_marketing_section");
        register_setting("tfgg_google_affiliate_marketing_section", "tfgg_scp_enable_google_mrkting");

        //add_settings_field("tfgg_scp_google_mrkting_script", "Marketing Script:", "display_tfgg_scp_google_mrkting_script", "tfgg-google-affiliate-marketing-options", "tfgg_google_affiliate_marketing_section");
        //register_setting("tfgg_google_affiliate_marketing_section", "tfgg_scp_google_mrkting_script");

        add_settings_field("tfgg_scp_google_mrkting_event", "Event Description:", "display_tfgg_event_desc_google", "tfgg-google-affiliate-marketing-options", "tfgg_google_affiliate_marketing_section");
        register_setting("tfgg_google_affiliate_marketing_section", "tfgg_scp_google_mrkting_event");

        add_settings_field("tfgg_scp_google_mrkting_tracker", "Tracker:", "display_tfgg_tracker_google", "tfgg-google-affiliate-marketing-options", "tfgg_google_affiliate_marketing_section");
        register_setting("tfgg_google_affiliate_marketing_section", "tfgg_scp_google_mrkting_tracker");
    }

    function tfgg_scp_affiliate_marketing(){
        tfgg_scp_admin_menu_header();
        ?>
        <div class="container-fluid">
        <div class="row">
            <div class="col-sm-11 col-md-9 col-lg-8">
                <div class="card">
                    <h5 class="card-header">AWIN Affiliate Marketing</h5>
                    <div class="card-body">
                        <form method="post" action="options.php">
                        <?php
                        settings_fields('tfgg_awin_affiliate_marketing_section');
                        do_settings_sections('tfgg-awin-affiliate-marketing-options');
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

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-11 col-md-8">
                <div class="card">
                    <h5 class="card-header">Google Affiliate Marketing</h5>
                    <div class="card-body">
                        <form method="post" action="options.php">
                        <?php
                        settings_fields('tfgg_google_affiliate_marketing_section');
                        do_settings_sections('tfgg-google-affiliate-marketing-options');
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

    function display_tfgg_enable_awin(){
        ?>
        <input type="checkbox" name="tfgg_scp_enable_awin_mrkting" value="1" <?php if(get_option('tfgg_scp_enable_awin_mrkting',0)==1){echo 'checked';} ?> />
        <?php
    }

    function display_tfgg_scp_awin_merchant_id(){
        ?>
        <input type="text" name="tfgg_scp_awin_merchant_id" value="<?php echo get_option('tfgg_scp_awin_merchant_id'); ?>" style="width: 60%" />
        <?php
    }
    
    function display_tfgg_scp_awin_channel(){
        ?>
        <input type="text" name="tfgg_scp_awin_channel" value="<?php echo get_option('tfgg_scp_awin_channel'); ?>" style="width: 60%" />
        <?php
    }

    function display_tfgg_scp_awin_reg_lead_desc(){
        ?>
        <input type="text" name="tfgg_scp_awin_reg_lead_desc" value="<?php echo get_option('tfgg_scp_awin_reg_lead_desc'); ?>" style="width: 60%" />
        <?php
    }

    function display_tfgg_scp_awin_reg_amt(){
        ?>
        <input type="number" name="tfgg_scp_awin_reg_amt" value="<?php echo get_option('tfgg_scp_awin_reg_amt','1.00'); ?>" style="width: 60%" />
        <?php
    }

    function display_tfgg_scp_awin_trans_lead_desc(){
        ?>
        <input type="text" name="tfgg_scp_awin_trans_lead_desc" value="<?php echo get_option('tfgg_scp_awin_trans_lead_desc'); ?>" style="width: 60%" />
        <?php
    }

    function display_tfgg_enable_google(){
        ?>
        <input type="checkbox" name="tfgg_scp_enable_google_mrkting" value="1" <?php if(get_option('tfgg_scp_enable_google_mrkting',0)==1){echo 'checked';} ?> />
        <?php
    }

    function display_tfgg_event_desc_google(){
        ?>
        <input type="text" name="tfgg_scp_google_mrkting_event" value="<?php echo get_option('tfgg_scp_google_mrkting_event'); ?>" style="width: 60%" />
        <?php  
    }

    function display_tfgg_tracker_google(){
        ?>
        <input type="text" name="tfgg_scp_google_mrkting_tracker" value="<?php echo get_option('tfgg_scp_google_mrkting_tracker'); ?>" style="width: 60%" />
        <?php  
    }

?>