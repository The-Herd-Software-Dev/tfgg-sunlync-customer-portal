<?php

function tfgg_scp_service_selction_description(){
    echo '<br/>';
    echo '<p>Please select the services you wish users to be able to purchase through this portal</p>';
    echo '<p>If no services are selected, all services returned from the API will be used</p>';
    echo '<p>Prices displayed to the user will be those pulled from the API</p>';
}

function display_tfgg_package_selection(){
    //first thing we need to do is actually display all the packages the API returns
    $packageList = json_decode(tfgg_scp_get_packages_from_api(''));
    if(StrToUpper($packageList->results)==='SUCCESS'){
        $packageList = $packageList->packages;	
        $selectedPackages = (array)get_option('tfgg_scp_package_selection');
        $rowCounter = 1;
        echo '<div class="container border rounded" style="padding: 10px">';
        foreach($packageList as &$packageDetails){
            if($rowCounter==1){echo "<div class=\"row\" style=\"padding: 5px;\">";}
                
            if(in_array($packageDetails->package_id, $selectedPackages)){ $isChecked = 'checked="checked"'; }else{ $isChecked = ''; }
            echo '<div class="col-sm">'.
            '<input type="checkbox" value="'.$packageDetails->package_id.'" name="tfgg_scp_package_selection[]" '.$isChecked.' />'.
            $packageDetails->description.'</div>';	
            
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
        $rowCounter = 1;
        echo '<div class="container border rounded" style="padding: 10px">';
        foreach($membershipList as &$membershipDetails){
            if($rowCounter==1){echo "<div class=\"row\" style=\"padding: 5px;\">";}
                
            if(in_array($membershipDetails->membership_id, $selectedMemberships)){ $isChecked = 'checked="checked"'; }else{ $isChecked = ''; }
            echo '<div class="col-sm">'.
            '<input type="checkbox" value="'.$membershipDetails->membership_id.'" name="tfgg_scp_membership_selection[]" '.$isChecked.' />'.
            $membershipDetails->description.'</div>';	
            
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

?>