<?php

function tfgg_scp_admin_services_options(){
    add_settings_section("tfgg_service_selection_section", '', null, "tfgg-service-selection");

    add_settings_field("tfgg_scp_services_sale_slug","Services Sale Slug:","display_tfgg_services_sale_slug", "tfgg-service-selection", "tfgg_service_selection_section");
    register_setting("tfgg_service_selection_section","tfgg_scp_services_sale_slug");

    add_settings_field("tfgg_scp_package_header_label","Package Display Header:","display_tfgg_package_header_label", "tfgg-service-selection", "tfgg_service_selection_section");
    register_setting("tfgg_service_selection_section","tfgg_scp_package_header_label");

    add_settings_field("tfgg_scp_package_allow_search","Allow Package Search:","display_tfgg_package_allow_search", "tfgg-service-selection", "tfgg_service_selection_section");
    register_setting("tfgg_service_selection_section","tfgg_scp_package_allow_search");

    add_settings_field("tfgg_scp_package_unit_minutes","Rename 'Minutes':","display_tfgg_package_units_minutes", "tfgg-service-selection", "tfgg_service_selection_section");
    register_setting("tfgg_service_selection_section","tfgg_scp_package_unit_minutes");

    add_settings_field("tfgg_scp_package_unit_sessions","Rename 'Sessions':","display_tfgg_package_units_sessions", "tfgg-service-selection", "tfgg_service_selection_section");
    register_setting("tfgg_service_selection_section","tfgg_scp_package_unit_sessions");

    add_settings_field("tfgg_scp_package_unit_credits","Rename 'Credits':","display_tfgg_package_units_credits", "tfgg-service-selection", "tfgg_service_selection_section");
    register_setting("tfgg_service_selection_section","tfgg_scp_package_unit_credits");

    add_settings_field("tfgg_scp_package_selection","Packages For Sale:","display_tfgg_package_selection", "tfgg-service-selection", "tfgg_service_selection_section");
    register_setting("tfgg_service_selection_section","tfgg_scp_package_selection");

    //add_settings_field("tfgg_scp_package_alias","Package Alias:","", "tfgg-service-selection", "tfgg_service_selection_section");
    register_setting("tfgg_service_selection_section","tfgg_scp_package_alias");
    register_setting("tfgg_service_selection_section","tfgg_scp_package_img");
    register_setting("tfgg_service_selection_section","tfgg_scp_package_free_text");

    add_settings_field("tfgg_scp_membership_header_label","Membership Display Header:","display_tfgg_membership_header_label", "tfgg-service-selection", "tfgg_service_selection_section");
    register_setting("tfgg_service_selection_section","tfgg_scp_membership_header_label");

    add_settings_field("tfgg_scp_membership_allow_search","Allow Membership Search:","display_tfgg_membership_allow_search", "tfgg-service-selection", "tfgg_service_selection_section");
    register_setting("tfgg_service_selection_section","tfgg_scp_membership_allow_search");

    add_settings_field("tfgg_scp_membership_selection","Memberships For Sale:","display_tfgg_membership_selection", "tfgg-service-selection", "tfgg_service_selection_section");
    register_setting("tfgg_service_selection_section","tfgg_scp_membership_selection");

    //add_settings_field("tfgg_scp_membership_alias","Membership Alias:","", "tfgg-service-selection", "tfgg_service_selection_section");
    register_setting("tfgg_service_selection_section","tfgg_scp_membership_alias");
    register_setting("tfgg_service_selection_section","tfgg_scp_membership_img");
    register_setting("tfgg_service_selection_section","tfgg_scp_membership_free_text");
}

function tfgg_scp_admin_cart_services_for_sale(){
    tfgg_scp_admin_menu_header();
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-11">
                <div class="card">
                    <h5 class="card-header">Services for Sale</h5>
                    <div class="card-body">
                        <p class="card-text">Please select the services you wish users to be able to purchase through this portal, if no services are selected, all services returned from the API will be used</p>
                        <p class="card-text">Prices displayed to the user will be those pulled from the API</p>
                        <form method="POST" action="options.php">
                        <?php
                        settings_fields('tfgg_service_selection_section');
                        do_settings_sections('tfgg-service-selection');
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
function display_tfgg_services_sale_slug(){
    ?>
    <input type="text" name="tfgg_scp_services_sale_slug" value="<?php echo get_option('tfgg_scp_services_sale_slug'); ?>" style="width: 60%" />
    <?php
}

function display_tfgg_cart_max_item_count(){
    ?>
    <input type="number" name="tfgg_scp_cart_max_item_count" value="<?php echo get_option('tfgg_scp_cart_max_item_count',10); ?>" style="width: 60%" />
    <?php   
}

function display_tfgg_package_selection(){
    //first thing we need to do is actually display all the packages the API returns
    $packageList = json_decode(tfgg_scp_get_packages_from_api(''));
    if(StrToUpper($packageList->results)==='SUCCESS'){
        $packageList = $packageList->packages;	
        //var_dump($packageList);
        $selectedPackages = (array)get_option('tfgg_scp_package_selection');
        $packageAlias = (array)get_option('tfgg_scp_package_alias');
        $packageImages = (array)get_option('tfgg_scp_package_img');
        $packageText = (array)get_option('tfgg_scp_package_free_text');
        //var_dump($packageAlias);
        $rowCounter = 1;

        echo '<div class="container border rounded" style="padding: 10px; margin-left:unset"><div class="row">';
        
        foreach($packageList as &$packageDetails){
            
                
            if(in_array($packageDetails->package_id, $selectedPackages)){ $isChecked = 'checked="checked"'; }else{ $isChecked = ''; }
            if((array_key_exists($packageDetails->package_id, $packageAlias))&&
            ($packageAlias[$packageDetails->package_id]<>'')){ $alias = $packageAlias[$packageDetails->package_id]; }else{ $alias = ''; }
            if((array_key_exists($packageDetails->package_id, $packageImages))&&
            ($packageImages[$packageDetails->package_id]<>'')){ $img = $packageImages[$packageDetails->package_id]; }else{ $img = ''; }
            if((array_key_exists($packageDetails->package_id, $packageText))&&
            ($packageText[$packageDetails->package_id]<>'')){ $freeText = $packageText[$packageDetails->package_id]; }else{ $freeText = ''; }
            
            echo '<div class="col-lg-4 admin-service-item">'.

            '<div class="admin-service-description"><input type="checkbox" value="'.$packageDetails->package_id.'" name="tfgg_scp_package_selection[]" '.$isChecked.' />'.$packageDetails->description.'</div>'.
            '<div class="admin-service-detail">'.
            '<div><span class="admin-service-label">Price:&nbsp; </span><span>&#163;'.$packageDetails->price.'</span></div>'.
            '<div><label class="admin-service-label">Alias: </label><input type="text" class="admin-service-value" name="tfgg_scp_package_alias['.$packageDetails->package_id.']" value="'.$alias.'"/></div>'.
            '<div><label class="admin-service-label">Img: </label><input type="text" class="admin-service-value" name="tfgg_scp_package_img['.$packageDetails->package_id.']" value="'.$img.'"/></div>'.
            '<div><label class="admin-service-label">Text: </label><input type="text" class="admin-service-value" name="tfgg_scp_package_free_text['.$packageDetails->package_id.']" value="'.$freeText.'"/></div>'.
            '<br />'.
            '</div></div>';	
        
        }//foreach
        echo '</div></div>';//container
    }else{
        $alert = "<div class=\"notice notice-error\">Unable to retrieve your package list, please ensure your API credentials are setup</div>";
        echo $alert;
    }
}

function display_tfgg_membership_selection(){
    //first thing we need to do is actually display all the packages the API returns
    $membershipList = json_decode(tfgg_scp_get_memberships_from_api(''));
    if(StrToUpper($membershipList->results)==='SUCCESS'){
        $membershipList = $membershipList->memberships;	
        $selectedMemberships = (array)get_option('tfgg_scp_membership_selection');
        $membershipAlias = (array)get_option('tfgg_scp_membership_alias');
        $membershipImages = (array)get_option('tfgg_scp_membership_img');
        $membershipText = (array)get_option('tfgg_scp_membership_free_text');
        $rowCounter = 1;
        echo '<div class="container border rounded" style="padding: 10px; margin-left:unset"> <div class="row">';
        
        

        foreach($membershipList as &$membershipDetails){
            
                
            if(in_array($membershipDetails->membership_id, $selectedMemberships)){ $isChecked = 'checked="checked"'; }else{ $isChecked = ''; }
            if((array_key_exists($membershipDetails->membership_id, $membershipAlias))&&
            ($membershipAlias[$membershipDetails->membership_id]<>'')){ $alias = $membershipAlias[$membershipDetails->membership_id]; }else{ $alias = ''; }
            if((array_key_exists($membershipDetails->membership_id, $membershipImages))&&
            ($membershipImages[$membershipDetails->membership_id]<>'')){ $img = $membershipImages[$membershipDetails->membership_id]; }else{ $img = ''; }
            if((array_key_exists($membershipDetails->membership_id, $membershipText))&&
            ($membershipText[$membershipDetails->membership_id]<>'')){ $freeText = $membershipText[$membershipDetails->membership_id]; }else{ $freeText = ''; }            
            


            echo '<div class="col-lg-4 admin-service-item">'.
                '<div class="admin-service-description"><input type="checkbox" value="'.$membershipDetails->membership_id.'" name="tfgg_scp_membership_selection[]" '.$isChecked.' />'.$membershipDetails->description.'</div>'.
                '<div class="admin-service-detail">'.
                    '<div><span class="admin-service-label">Price:&nbsp; </span><span>&#163;'.$membershipDetails->price.'</span></div>'.
                    '<div><label class="admin-service-label">Alias: </label><input type="text" class="admin-service-value" name="tfgg_scp_membership_alias['.$membershipDetails->membership_id.']" value="'.$alias.'"/></div>'.
                    '<div><label class="admin-service-label">Img: </label><input type="text" class="admin-service-value" name="tfgg_scp_membership_img['.$membershipDetails->membership_id.']" value="'.$img.'"/></div>'.
                    '<div><label class="admin-service-label">Text: </label><input type="text" class="admin-service-value" name="tfgg_scp_membership_free_text['.$membershipDetails->membership_id.']" value="'.$freeText.'"/></div>'.
                    '<br />'.
                '</div></div>';	
            
            
        }//foreach
        echo '</div></div>';//container
    }else{
        $alert = "<div class=\"notice notice-error\">Unable to retrieve your membership list, please ensure your API credentials are setup</div>";
        echo $alert;
    }
}

function display_tfgg_membership_header_label(){
    ?>
        <input type="text" name="tfgg_scp_membership_header_label" value="<?php echo get_option('tfgg_scp_membership_header_label'); ?>" style="width: 60%" />
    <?php  
}

function display_tfgg_package_header_label(){
    ?>
        <input type="text" name="tfgg_scp_package_header_label" value="<?php echo get_option('tfgg_scp_package_header_label'); ?>" style="width: 60%" />
    <?php    
}

function display_tfgg_package_allow_search(){
    ?>
        <input type="checkbox" name="tfgg_scp_package_allow_search" value="1" <?php if(get_option('tfgg_scp_package_allow_search')==1){echo 'checked';} ?>/>
    <?php
}

function display_tfgg_membership_allow_search(){
    ?>
        <input type="checkbox" name="tfgg_scp_membership_allow_search" value="1" <?php if(get_option('tfgg_scp_membership_allow_search')==1){echo 'checked';} ?>/>
    <?php
}


function display_tfgg_package_units_minutes(){ 
    ?>
    <input type="text" name="tfgg_scp_package_unit_minutes" value="<?php echo get_option('tfgg_scp_package_unit_minutes');?>" style="width: 60%"/>
    <?php
}

function display_tfgg_package_units_sessions(){
    ?>
    <input type="text" name="tfgg_scp_package_unit_sessions" value="<?php echo get_option('tfgg_scp_package_unit_sessions');?>" style="width: 60%"/>
    <?php   
}

function display_tfgg_package_units_credits(){
    ?>
    <input type="text" name="tfgg_scp_package_unit_credits" value="<?php echo get_option('tfgg_scp_package_unit_credits');?>" style="width: 60%"/>
    <?php    
}

?>