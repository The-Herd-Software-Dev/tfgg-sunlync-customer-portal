<?php
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