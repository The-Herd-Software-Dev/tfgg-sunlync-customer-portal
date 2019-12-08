<?php

function tfgg_scp_service_selction_description(){
    echo '<br/>';
    echo '<p>Please select the services you wish users to be able to purchase through this portal, if no services are selected, all services returned from the API will be used</p>';
    echo '<p>Prices displayed to the user will be those pulled from the API</p>';
}

function display_tfgg_package_selection(){
    //first thing we need to do is actually display all the packages the API returns
    $packageList = json_decode(tfgg_scp_get_packages_from_api(''));
    if(StrToUpper($packageList->results)==='SUCCESS'){
        $packageList = $packageList->packages;	
        $selectedPackages = (array)get_option('tfgg_scp_package_selection');
        $packageAlias = (array)get_option('tfgg_scp_package_alias');
        //var_dump($packageAlias);
        $rowCounter = 1;
        echo '<div class="container border rounded" style="padding: 10px">';
        foreach($packageList as &$packageDetails){
            if($rowCounter==1){echo "<div class=\"row\" style=\"padding: 5px;\">";}
                
            if(in_array($packageDetails->package_id, $selectedPackages)){ $isChecked = 'checked="checked"'; }else{ $isChecked = ''; }
            if((array_key_exists($packageDetails->package_id, $packageAlias))&&
            ($packageAlias[$packageDetails->package_id]<>'')){ $alias = $packageAlias[$packageDetails->package_id]; }else{ $alias = ''; }
            echo '<div class="col-sm">'.
            '<input type="checkbox" value="'.$packageDetails->package_id.'" name="tfgg_scp_package_selection[]" '.$isChecked.' />'.$packageDetails->description.'<br/>'.
            '<div><label>Alias: </label><input type="text" name="tfgg_scp_package_alias['.$packageDetails->package_id.']" value="'.$alias.'"/></div>'.
            '</div>';	
            
            $rowCounter++;
            if($rowCounter>3){
                $rowCounter=1;
                echo "</div>";//close the row
            }
        }//foreach
        echo '</div>';//container
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
        $rowCounter = 1;
        echo '<div class="container border rounded" style="padding: 10px">';
        foreach($membershipList as &$membershipDetails){
            if($rowCounter==1){echo "<div class=\"row\" style=\"padding: 5px;\">";}
                
            if(in_array($membershipDetails->membership_id, $selectedMemberships)){ $isChecked = 'checked="checked"'; }else{ $isChecked = ''; }
            if((array_key_exists($membershipDetails->membership_id, $membershipAlias))&&
            ($membershipAlias[$membershipDetails->membership_id]<>'')){ $alias = $membershipAlias[$membershipDetails->membership_id]; }else{ $alias = ''; }
            echo '<div class="col-sm">'.
            '<input type="checkbox" value="'.$membershipDetails->membership_id.'" name="tfgg_scp_membership_selection[]" '.$isChecked.' />'.$membershipDetails->description.'<br/>'.
            '<div><label>Alias: </label><input type="text" name="tfgg_scp_membership_alias['.$membershipDetails->membership_id.']" value="'.$alias.'"/></div>'.
            '</div>';	
            
            $rowCounter++;
            if($rowCounter>3){
                $rowCounter=1;
                echo "</div>";//close the row
            }
        }//foreach
        echo '</div>';//container
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