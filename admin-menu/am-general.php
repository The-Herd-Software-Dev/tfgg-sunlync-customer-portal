<?php

    function tfgg_scp_demogrphics_description(){
        echo '<p>Demographics Settings</p>';
    }
    
    function display_demogrphics_allow(){
        ?>
        <input type="checkbox" name="tfgg_scp_demogrphics_allow" value="1" <?php if(get_option('tfgg_scp_demogrphics_allow')==1){echo 'checked';} ?>/>
        <?php
    }
    
    function display_customer_service_email(){
        ?>
        <input type="email" name="tfgg_scp_customer_service_email" value="<?php echo get_option('tfgg_scp_customer_service_email');?>" style="width: 60%"/>
        <?php
    }
    
    function display_comm_pref_allow(){
        ?>
        <input type="checkbox" name="tfgg_scp_comm_pref_allow" value="1" <?php if(get_option('tfgg_scp_comm_pref_allow')==1){echo 'checked';} ?> />
        <?php
    }
    
    function display_update_employee(){
        $updateEmp = get_option('tfgg_scp_update_employee');
        $employeeList = json_decode(tfgg_api_get_employees());
        $employeeList = $employeeList->employees;
        ?>
        <select name="tfgg_scp_update_employee" style="width: 60%">
            <option value="">Please Select...</option>
        <?php
            foreach($employeeList as &$details){
                $output='<option value="'.$details->emp_no.'" '.($details->emp_no === $updateEmp ? "selected" : "").'>'.$details->lname.', '.$details->fname.'</option>';
                echo $output; 
            }
        ?>
        </select>
        <?php
    }
    
    function display_reg_promo(){
        $promo = get_option('tfgg_scp_reg_promo');
        $promoList = json_decode(tfgg_api_get_promos());
        
        if((StrToupper($promoList->results)==='SUCCESS')&&
        (array_key_exists('promotions',$promoList))&&(count($promoList->promotions)>0)){
        
            $promoList = $promoList->promotions;
            ?>
            <select name="tfgg_scp_reg_promo" style="width: 60%">
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
    
    function display_demogrphics_title_label(){
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

    function display_tandc_label(){
        ?>
        <input type="text" name="tfgg_scp_tandc_label" value='<?php echo get_option('tfgg_scp_tandc_label'); ?>' size="70" />
        <?php
    }

    function display_marketing_optin_label(){
        ?>
        <input type="text" name="tfgg_scp_marketing_optin_label" value='<?php echo get_option('tfgg_scp_marketing_optin_label'); ?>' size="70" />
        <?php
    }
    
?>