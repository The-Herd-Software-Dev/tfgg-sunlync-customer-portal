<?php


    function tfgg_scp_admin_registration_options(){
        add_settings_section("tfgg_registration_options_section", '', null, "tfgg-registration-options");

        add_settings_field("tfgg_scp_cust_info_reg_title", "Registration Title:", "display_reg_title_online","tfgg-registration-options","tfgg_registration_options_section");
        register_setting("tfgg_registration_options_section","tfgg_scp_cust_info_reg_title");

        add_settings_field("tfgg_scp_tandc_label", "Terms & Conditions Label:", "display_tandc_label_online","tfgg-registration-options","tfgg_registration_options_section");
        register_setting("tfgg_registration_options_section","tfgg_scp_tandc_label");

        add_settings_field("tfgg_scp_marketing_optin_label", "Marketing Opt In Label:", "display_marketing_optin_label_online","tfgg-registration-options","tfgg_registration_options_section");
        register_setting("tfgg_registration_options_section","tfgg_scp_marketing_optin_label");
              
        add_settings_field("tfgg_scp_registration_source_label", "Registration Source:", "display_registration_source_label_online","tfgg-registration-options","tfgg_registration_options_section");
        register_setting("tfgg_registration_options_section","tfgg_scp_registration_source_label");

        add_settings_field("tfgg_scp_reg_promo", "Default Registration Promo:", "display_reg_promo_online", "tfgg-registration-options", "tfgg_registration_options_section");
        register_setting("tfgg_registration_options_section", "tfgg_scp_reg_promo");

        add_settings_field("tfgg_scp_reg_package", "Default Registration Package:", "display_reg_package_online", "tfgg-registration-options", "tfgg_registration_options_section");
        register_setting("tfgg_registration_options_section", "tfgg_scp_reg_package");

        add_settings_field("tfgg_scp_online_reg_recaptcha_req", "Google Recaptcha Required:", "display_reg_online_recaptcha_req", "tfgg-registration-options", "tfgg_registration_options_section");
        register_setting("tfgg_registration_options_section", "tfgg_scp_online_reg_recaptcha_req");
    }

    function tfgg_scp_admin_registration_options_instore(){
        add_settings_section("tfgg_registration_options_section_instore", '', null, "tfgg-registration-options-instore");

        add_settings_field("tfgg_scp_cust_info_reg_title_instore", "Registration Title:", "display_reg_title_instore","tfgg-registration-options-instore","tfgg_registration_options_section_instore");
        register_setting("tfgg_registration_options_section_instore","tfgg_scp_cust_info_reg_title_instore");

        add_settings_field("tfgg_scp_tandc_label_instore", "Terms & Conditions:", "display_tandc_label_instore","tfgg-registration-options-instore","tfgg_registration_options_section_instore");
        register_setting("tfgg_registration_options_section_instore","tfgg_scp_tandc_label_instore");

        add_settings_field("tfgg_scp_tandc_slug_instore", "Terms & Conditions Slug:", "display_tandc_slug_instore","tfgg-registration-options-instore","tfgg_registration_options_section_instore");
        register_setting("tfgg_registration_options_section_instore","tfgg_scp_tandc_slug_instore");

        add_settings_field("tfgg_scp_marketing_optin_label_instore", "Marketing Opt In:", "display_marketing_optin_label_instore","tfgg-registration-options-instore","tfgg_registration_options_section_instore");
        register_setting("tfgg_registration_options_section_instore","tfgg_scp_marketing_optin_label_instore");

        add_settings_field("tfgg_scp_marketing_slug_instore", "Marketing Slug:", "display_marketing_slug_instore","tfgg-registration-options-instore","tfgg_registration_options_section_instore");
        register_setting("tfgg_registration_options_section_instore","tfgg_scp_marketing_slug_instore");

        add_settings_field("tfgg_scp_skin_type_info_slug_instore", "Skintype Info Slug:", "display_skin_type_info_slug", "tfgg-registration-options-instore", "tfgg_registration_options_section_instore");
        register_setting("tfgg_registration_options_section_instore", "tfgg_scp_skin_type_info_slug_instore");
        
        add_settings_field("tfgg_scp_registration_source_label_instore", "Registration Source:", "display_registration_source_label_instore","tfgg-registration-options-instore","tfgg_registration_options_section_instore");
        register_setting("tfgg_registration_options_section_instore","tfgg_scp_registration_source_label_instore");

        add_settings_field("tfgg_scp_reg_promo_instore", "Promo For Registration:", "display_reg_promo_instore", "tfgg-registration-options-instore", "tfgg_registration_options_section_instore");
        register_setting("tfgg_registration_options_section_instore", "tfgg_scp_reg_promo_instore");

        add_settings_field("tfgg_scp_reg_package_instore", "Package For Registration:", "display_reg_package_instore", "tfgg-registration-options-instore", "tfgg_registration_options_section_instore");
        register_setting("tfgg_registration_options_section_instore", "tfgg_scp_reg_package_instore");

        add_settings_field("tfgg_scp_instore_reg_recaptcha_req", "Google Recaptcha Required:", "display_reg_instore_recaptcha_req", "tfgg-registration-options-instore", "tfgg_registration_options_section_instore");
        register_setting("tfgg_registration_options_section_instore", "tfgg_scp_instore_reg_recaptcha_req");
        //2019-11-19 CB V1.2.4.2 - added
        add_settings_field("tfgg_scp_instore_reg_password_hint", "Instore Password Hint:", "display_password_hint_reg_instore", "tfgg-registration-options-instore", "tfgg_registration_options_section_instore");
        register_setting("tfgg_registration_options_section_instore", "tfgg_scp_instore_reg_password_hint");

    } 

    function tfgg_scp_admin_registration(){
        tfgg_scp_admin_menu_header();
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <h5 class="card-header">Online Registration</h5>
                    <div class="card-body">
                        <form method="POST" action="options.php">
                            <?php
                            settings_fields('tfgg_registration_options_section');
                            do_settings_sections('tfgg-registration-options');
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
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <h5 class="card-header">Instore Registration</h5>
                    <div class="card-body">
                        <form method="POST" action="options.php">
                            <?php
                            settings_fields('tfgg_registration_options_section_instore');
                            do_settings_sections('tfgg-registration-options-instore');
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


    function display_registration_settings_title_online(){
        echo "<br/><h2>Online Registration Options</h2>";
    }

    function display_registration_settings_title_instore(){
        echo "<br/><h2>Instore Registration Options</h2>";
    }

    function display_tandc_label($isOnline){
        if($isOnline){
        ?>
        <input type="text" name="tfgg_scp_tandc_label" value='<?php echo get_option('tfgg_scp_tandc_label'); ?>' size="70" />
        <?php
        }else{
        ?>
        <input type="text" name="tfgg_scp_tandc_label_instore" value='<?php echo get_option('tfgg_scp_tandc_label_instore'); ?>' size="70" />
        <?php
        }
    }

    function display_marketing_optin_label($isOnline){
        if($isOnline){
        ?>
        <input type="text" name="tfgg_scp_marketing_optin_label" value='<?php echo get_option('tfgg_scp_marketing_optin_label'); ?>' size="70" />
        <?php
        }else{
        ?>
        <input type="text" name="tfgg_scp_marketing_optin_label_instore" value='<?php echo get_option('tfgg_scp_marketing_optin_label_instore'); ?>' size="70" />
        <?php
        }
    }

    function display_registration_source_label($isOnline){
        if($isOnline){
        ?>
        <input type="text" name="tfgg_scp_registration_source_label" value='<?php echo get_option('tfgg_scp_registration_source_label'); ?>' size="70" />
        <?php
        }else{
        ?>
        <input type="text" name="tfgg_scp_registration_source_label_instore" value='<?php echo get_option('tfgg_scp_registration_source_label_instore'); ?>' size="70" />
        <?php
        }
    }

    function display_reg_title($isOnline){
        if($isOnline){
        ?>
        <input type="text" name="tfgg_scp_cust_info_reg_title" value='<?php echo get_option('tfgg_scp_cust_info_reg_title'); ?>' size="70" />
        <?php
        }else{
        ?>
        <input type="text" name="tfgg_scp_cust_info_reg_title_instore" value='<?php echo get_option('tfgg_scp_cust_info_reg_title_instore'); ?>' size="70" />
        <?php
        }
    }

    function display_reg_title_online(){
        display_reg_title(true);
    }

    function display_reg_title_instore(){
        display_reg_title(false);
    }    

    function display_tandc_label_online(){
        display_tandc_label(true);    
    }

    function display_marketing_optin_label_online(){
        display_marketing_optin_label(true);
    }

    function display_registration_source_label_online(){
        display_registration_source_label(true);
    }

    function display_tandc_label_instore(){
        display_tandc_label(false);    
    }

    function display_tandc_slug_instore(){
        ?>
        <input type="text" name="tfgg_scp_tandc_slug_instore" value='<?php echo get_option('tfgg_scp_tandc_slug_instore'); ?>' size="70" />
        <?php  
    }

    function display_marketing_optin_label_instore(){
        display_marketing_optin_label(false);
    }  
    
    function display_marketing_slug_instore(){
        ?>
        <input type="text" name="tfgg_scp_marketing_slug_instore" value='<?php echo get_option('tfgg_scp_marketing_slug_instore'); ?>' size="70" />
        <?php  
    }

    //2019-11-14 CB V1.2.3.1 - new field
    function display_skin_type_info_slug(){
        ?>
        <input type="text" name="tfgg_scp_skin_type_info_slug_instore" value='<?php echo get_option('tfgg_scp_skin_type_info_slug_instore'); ?>' size="70" />
        <?php  
    }

    function display_registration_source_label_instore(){
        display_registration_source_label(false);
    }

    function display_reg_promo_instore(){
        display_reg_promo(false);
    }

    function display_reg_promo_online(){
        display_reg_promo(true);
    }

    function display_reg_promo($isOnline){
        if($isOnline){
            $promo = get_option('tfgg_scp_reg_promo');
            $name = 'tfgg_scp_reg_promo';
        }else{
            $promo = get_option('tfgg_scp_reg_promo_instore');
            $name = 'tfgg_scp_reg_promo_instore';
        }
        $promoList = json_decode(tfgg_api_get_promos());
        
        if((StrToupper($promoList->results)==='SUCCESS')&&
        (array_key_exists('promotions',$promoList))&&(count($promoList->promotions)>0)){
        
            $promoList = $promoList->promotions;
            ?>
            <select name="<?php echo $name; ?>" style="width: 60%">
                <option value="">Please Select...</option>
            <?php
                foreach($promoList as &$details){
                    $output='<option value="'.$details->PromoID.'" '.($details->PromoID === $promo ? "selected" : "").'>'.$details->Description.'</option>';
                    echo $output; 
                }
            ?>
            </select>
        <?php
        }else if((StrToupper($promoList->results)==='SUCCESS')&&
        (array_key_exists('promotions',$promoList))&&(count($promoList->promotions)==0)){
            ?>
            <strong>THERE ARE PRESENTLY NO CUSTOMER SPECIFIC PROMOTIONS CONFIGURED IN YOUR SYSTEM</strong>
            <?php
        }else{
            ?>
            <strong>An error was returned: <?php echo $promoList->response; ?></strong>
            <?php
        }
    }

    function display_reg_package_online(){
        display_reg_package(true);    
    }

    function display_reg_package_instore(){
        display_reg_package(false);    
    }

    function display_reg_package($isOnline){
        //2020-02-25 CB V1.2.4.21 - enabling packages for registration
        $packageList = json_decode(tfgg_scp_get_packages_from_api(''));

        if($isOnline){
            $pkg = get_option('tfgg_scp_reg_package');
            $name = 'tfgg_scp_reg_package';
        }else{
            $pkg = get_option('tfgg_scp_reg_package_instore');
            $name = 'tfgg_scp_reg_package_instore';
        }

        if(StrToUpper($packageList->results)==='SUCCESS'){
            $packageList = $packageList->packages;
            ?>
            <select name="<?php echo $name; ?>" style="width: 60%">
                <option value="">Please Select...</option>
            <?php
                foreach($packageList as &$details){
                    $output='<option value="'.$details->package_id.'" '.($details->package_id === $pkg ? "selected" : "").'>'.$details->description.'</option>';
                    echo $output; 
                }
            ?>
            </select>
            <?php            

        }else{
            $alert = "<div class=\"notice notice-error\">Unable to retrieve your package list, please ensure your API credentials are setup</div>";
            echo $alert;
        }
    }

    function display_reg_online_recaptcha_req(){
        ?>
        <input type="checkbox" name="tfgg_scp_online_reg_recaptcha_req" value="1" <?php if(get_option('tfgg_scp_online_reg_recaptcha_req','1')==1){echo 'checked';} ?>/>
        <?php
    }

    function display_reg_instore_recaptcha_req(){
        ?>
        <input type="checkbox" name="tfgg_scp_instore_reg_recaptcha_req" value="1" <?php if(get_option('tfgg_scp_instore_reg_recaptcha_req','1')==1){echo 'checked';} ?>/>
        <?php
    }
?>