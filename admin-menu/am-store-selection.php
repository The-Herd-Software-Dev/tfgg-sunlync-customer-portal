<?php

function tfgg_scp_store_selction_description(){
    echo '<br/>';
    echo '<p>Please select the stores you wish users to be able to select throughout this portal</p>';
    echo '<p>If no stores are selected, all stores returned from the API will be used (barring stores containing "CLOSED" and "DELETED" in their description</p>';
}

function display_tfgg_store_cart_details_page(){
    ?>
    <input type="text" name="tfgg_scp_store_cart_details_page" value="<?php echo get_option('tfgg_scp_store_cart_details_page'); ?>" style="width: 60%" />
    <?php
}

function display_tfgg_store_selection(){
    //first thing we need to do is actually display all the stores the API returns

    //2020-02-24 CB V1.2.4.20 - this was reworked to allow for extra options per store

    $storeList = json_decode(tfgg_api_get_unfiltered_stores());
    if(StrToUpper($storeList->results)==='SUCCESS'){
        $storeList = $storeList->stores;	
        $selectedStores = (array)get_option('tfgg_scp_store_selection');
        /*$rowCounter = 1;
        echo '<div class="container border rounded" style="padding: 10px">';
        foreach($storeList as &$storeDetails){
            if((!strpos(StrToUpper($storeDetails->store_loc),'CLOSED'))&&
            (!strpos(StrToUpper($storeDetails->store_loc),'DELETED'))){
                if($rowCounter==1){echo "<div class=\"row\" style=\"padding: 5px;\">";}
                
                if(in_array($storeDetails->store_id, $selectedStores)){ $isChecked = 'checked="checked"'; }else{ $isChecked = ''; }
                echo '<div class="col-sm">'.
                '<input type="checkbox" value="'.$storeDetails->store_id.'" name="tfgg_scp_store_selection[]" '.$isChecked.' />'.
                $storeDetails->store_loc.'</div>';	
                
                $rowCounter++;
                if($rowCounter>3){
                    $rowCounter=1;
                    echo "</div>";//close the row
                }
            }
        }//foreach
        echo '</div>';//container*/

        //2020-07-15 CB V1.2.6.5 - new array
        $regStores = (array)get_option('tfgg_scp_store_registration_selection');

        $apptStores = (array)get_option('tfgg_scp_store_appts_selection');
        $storeCartDetailsID = (array)get_option('tfgg_scp_store_cart_details_id');
        
        $rowCounter=1;

        echo '<div class="container border rounded" style="padding: 10px; margin-left:unset"><div class="row">';
        foreach($storeList as &$storeDetails){

            //2020-07-15 CB V1.2.6.5
            if(in_array($storeDetails->store_id, $regStores)){ $useInReg = 'checked="checked"'; }else{ $useInReg = ''; }

            if(in_array($storeDetails->store_id, $selectedStores)){ $useInCart = 'checked="checked"'; }else{ $useInCart = ''; }
            if(in_array($storeDetails->store_id, $apptStores)){ $useInAppts = 'checked="checked"'; }else{ $useInAppts = ''; }

            if((array_key_exists($storeDetails->store_id, $storeCartDetailsID))&&
            ($storeCartDetailsID[$storeDetails->store_id]<>'')){ $freeText = $storeCartDetailsID[$storeDetails->store_id]; }else{ $freeText = ''; } 

            echo '<div class="col-lg-4 admin-service-item">'.

            '<div class="admin-service-description"><strong>'.$storeDetails->store_loc.'</strong></div>'.
            '<div class="admin-service-detail">'.
            '<div><input type="checkbox" value="'.$storeDetails->store_id.'" name="tfgg_scp_store_registration_selection[]" '.$useInReg.' /><Label> Registration</label></div>'.
            '<div><input type="checkbox" value="'.$storeDetails->store_id.'" name="tfgg_scp_store_appts_selection[]" '.$useInAppts.' /><label> Appointments</label></div>'.
            '<div><input type="checkbox" value="'.$storeDetails->store_id.'" name="tfgg_scp_store_selection[]" '.$useInCart.' /><Label> Cart</label></div>'.
            '<div><label class="admin-service-label">Cart Details ID: </label><input type="text" class="admin-service-value" name="tfgg_scp_store_cart_details_id['.$storeDetails->store_id.']" value="'.$freeText.'"/></div>'.
            '<br />'.
            '</div></div>';	

        }//foreach
        echo '</div></div>';


    }else{
        $alert = "<div class=\"notice notice-error\">Unable to retrieve your store list, please ensure your API credentials are setup</div>";
        echo $alert;
    }
}

?>