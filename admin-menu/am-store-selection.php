<?php

function tfgg_scp_store_selction_description(){
    echo '<br/>';
    echo '<p>Please select the stores you wish users to be able to select throughout this portal</p>';
    echo '<p>If no stores are selected, all stores returned from the API will be used (barring stores containing "CLOSED" and "DELETED" in their description</p>';
    echo '<p>If a store specific registration slug is entered, when that page is loaded, the registration store selection will be disabled</p>';
    echo '<p>If no store specifc registration package/promo is selected, the default package/promo will be used</p>';
}

function display_tfgg_store_cart_details_page(){
    ?>
    <input type="text" name="tfgg_scp_store_cart_details_page" value="<?php echo get_option('tfgg_scp_store_cart_details_page'); ?>" style="width: 60%" />
    <?php
}

function display_tfgg_store_selection(){
    //first thing we need to do is actually display all the stores the API returns

    //2020-02-24 CB V1.2.4.20 - this was reworked to allow for extra options per store
    //2021-02-14 CB V1.3.0.1 - reworked again for new options, better formatting

    $storeList = json_decode(tfgg_api_get_unfiltered_stores());
    
    if(StrToUpper($storeList->results)==='SUCCESS'){
        $storeList = $storeList->stores;
        
        //2021-02-14 CB - get pkgs and promos
        $promoList = json_decode(tfgg_api_get_promos());
        if((StrToupper($promoList->results)==='SUCCESS')&&
        (array_key_exists('promotions',$promoList))&&(count($promoList->promotions)>0)){
            $promoList = $promoList->promotions;
        }else{
            $promoList='';
        }
        
        $packageList = json_decode(tfgg_scp_get_packages_from_api(''));
        if(StrToUpper($packageList->results)==='SUCCESS'){
            $packageList = $packageList->packages;
        }else{
            $packageList='';
        }
        $selectedStores = (array)get_option('tfgg_scp_store_selection');

        //2020-07-15 CB V1.2.6.5 - new array
        $regStores = (array)get_option('tfgg_scp_store_registration_selection');

        $apptStores = (array)get_option('tfgg_scp_store_appts_selection');
        $storeCartDetailsID = (array)get_option('tfgg_scp_store_cart_details_id');

        //2020-02-14 CB V2.3.0.1 - new arrays
        $regStoreSlugs = (array)get_option('tfgg_scp_store_reg_slugs');
        $regStorePromos = (array)get_option('tfgg_scp_store_reg_promos');
        $regStorePkgs = (array)get_option('tfgg_scp_store_reg_pkgs');

        $regOnline = (array)get_option('tfgg_scp_online_registration_selection');
        $regOnlinePromos = (array)get_option('tfgg_scp_online_reg_promos');
        $regOnlinePkgs = (array)get_option('tfgg_scp_online_reg_pkgs');

        
        $rowCounter=1;

        echo '<div class="container border rounded" style="padding: 10px; margin-left:unset">';
        foreach($storeList as &$storeDetails){
            if($storeDetails->store_id!='0000000000'){
                if($rowCounter==1){
                    //reset the row container
                    echo '<div class="row">';
                }

                    $storeContainer= '<div class="col">';
                    
                    $storeContainer.= '<div class="container border rounded">';
                            $storeContainer.= '<div style="padding-top: 5px"><span><strong>'.$storeDetails->store_loc.'</strong></span></div>';
                            $storeContainer.= '<hr/>'; 

                            //online registration option
                            if(in_array($storeDetails->store_id, $regStores)){ $useInReg = 'checked="checked"'; }else{ $useInReg = ''; }
                            $storeContainer.= '<div class="row">';
                                $storeContainer.= '<div class="col-4"><label>Online Registration</lable></div>';
                                $storeContainer.= '<div class="col">';
                                $storeContainer.= '<input type="checkbox" value="'.$storeDetails->store_id.'" name="tfgg_scp_store_registration_selection[]" '.$useInReg.' />';
                                $storeContainer.=' </div>';
                            $storeContainer.= '</div>';

                            if((array_key_exists($storeDetails->store_id, $regStoreSlugs))&&
                            ($regStoreSlugs[$storeDetails->store_id]<>'')){ $storeSlug = $regStoreSlugs[$storeDetails->store_id]; }else{ $storeSlug = ''; } 
                            $storeContainer.= '<div class="row">';
                                $storeContainer.= '<div class="col-1">&nbsp;</div>';
                                $storeContainer.= '<div class="col-3"><label>Slug</lable></div>';
                                $storeContainer.= '<div class="col">';
                                $storeContainer.= '<input type="text" class="admin-service-value" name="tfgg_scp_store_reg_slugs['.$storeDetails->store_id.']" value="'.$storeSlug.'" style="width:100%"/>';
                                $storeContainer.=' </div>';
                            $storeContainer.= '</div>';

                            if((array_key_exists($storeDetails->store_id, $regStorePromos))&&
                            ($regStorePromos[$storeDetails->store_id]<>'')){ $storePromo = $regStorePromos[$storeDetails->store_id]; }else{ $storePromo = ''; } 
                            $storeContainer.= '<div class="row">';
                                $storeContainer.= '<div class="col-1">&nbsp;</div>';
                                $storeContainer.= '<div class="col-3"><label>Promo</lable></div>';
                                $storeContainer.= '<div class="col"><select name="tfgg_scp_store_reg_promos['.$storeDetails->store_id.']" style="width:100%">';
                                $storeContainer.= '<option value="">Please Select...</option>';
                                foreach($promoList as &$details){
                                    $storeContainer.='<option value="'.$details->PromoID.'" '.($details->PromoID === $storePromo ? "selected" : "").'>'.$details->Description.'</option>';
                                }
                                $storeContainer.='</select></div>';
                            $storeContainer.= '</div>';

                            if((array_key_exists($storeDetails->store_id, $regStorePkgs))&&
                            ($regStorePkgs[$storeDetails->store_id]<>'')){ $storePkg = $regStorePkgs[$storeDetails->store_id]; }else{ $storePkg = ''; } 
                            $storeContainer.= '<div class="row">';
                                $storeContainer.= '<div class="col-1">&nbsp;</div>';
                                $storeContainer.= '<div class="col-3"><label>Package</lable></div>';
                                $storeContainer.= '<div class="col"><select name="tfgg_scp_store_reg_pkgs['.$storeDetails->store_id.']" style="width:100%">';
                                $storeContainer.= '<option value="">Please Select...</option>';
                                foreach($packageList as &$details){
                                    $storeContainer.='<option value="'.$details->package_id.'" '.($details->package_id === $storePkg ? "selected" : "").'>'.$details->description.'</option>';
                                }
                                $storeContainer.='</select></div>';
                            $storeContainer.= '</div>';

                            $storeContainer.='<hr/>';

                            //instore registration option
                            if(in_array($storeDetails->store_id, $regOnline)){ $useInReg = 'checked="checked"'; }else{ $useInReg = ''; }
                            $storeContainer.= '<div class="row">';
                                $storeContainer.= '<div class="col-4"><label>Instore Registration</lable></div>';
                                $storeContainer.= '<div class="col">';
                                $storeContainer.= '<input type="checkbox" value="'.$storeDetails->store_id.'" name="tfgg_scp_online_registration_selection[]" '.$useInReg.' />';
                                $storeContainer.=' </div>';
                            $storeContainer.= '</div>';

                            /*if((array_key_exists($storeDetails->store_id, $regStoreSlugs))&&
                            ($regStoreSlugs[$storeDetails->store_id]<>'')){ $storeSlug = $regStoreSlugs[$storeDetails->store_id]; }else{ $storeSlug = ''; } 
                            $storeContainer.= '<div class="row">';
                                $storeContainer.= '<div class="col-1">&nbsp;</div>';
                                $storeContainer.= '<div class="col-3"><label>Slug</lable></div>';
                                $storeContainer.= '<div class="col">';
                                $storeContainer.= '<input type="text" class="admin-service-value" name="tfgg_scp_store_reg_slugs['.$storeDetails->store_id.']" value="'.$storeSlug.'" style="width:100%"/>';
                                $storeContainer.=' </div>';
                            $storeContainer.= '</div>';*/

                            if((array_key_exists($storeDetails->store_id, $regOnlinePromos))&&
                            ($regOnlinePromos[$storeDetails->store_id]<>'')){ $storePromo = $regOnlinePromos[$storeDetails->store_id]; }else{ $storePromo = ''; } 
                            $storeContainer.= '<div class="row">';
                                $storeContainer.= '<div class="col-1">&nbsp;</div>';
                                $storeContainer.= '<div class="col-3"><label>Promo</lable></div>';
                                $storeContainer.= '<div class="col"><select name="tfgg_scp_online_reg_promos['.$storeDetails->store_id.']" style="width:100%">';
                                $storeContainer.= '<option value="">Please Select...</option>';
                                foreach($promoList as &$details){
                                    $storeContainer.='<option value="'.$details->PromoID.'" '.($details->PromoID === $storePromo ? "selected" : "").'>'.$details->Description.'</option>';
                                }
                                $storeContainer.='</select></div>';
                            $storeContainer.= '</div>';

                            if((array_key_exists($storeDetails->store_id, $regOnlinePkgs))&&
                            ($regOnlinePkgs[$storeDetails->store_id]<>'')){ $storePkg = $regOnlinePkgs[$storeDetails->store_id]; }else{ $storePkg = ''; } 
                            $storeContainer.= '<div class="row">';
                                $storeContainer.= '<div class="col-1">&nbsp;</div>';
                                $storeContainer.= '<div class="col-3"><label>Package</lable></div>';
                                $storeContainer.= '<div class="col"><select name="tfgg_scp_online_reg_pkgs['.$storeDetails->store_id.']" style="width:100%">';
                                $storeContainer.= '<option value="">Please Select...</option>';
                                foreach($packageList as &$details){
                                    $storeContainer.='<option value="'.$details->package_id.'" '.($details->package_id === $storePkg ? "selected" : "").'>'.$details->description.'</option>';
                                }
                                $storeContainer.='</select></div>';
                            $storeContainer.= '</div>';

                            $storeContainer.='<hr/>';

                            //appointments
                            if(in_array($storeDetails->store_id, $apptStores)){ $useInAppts = 'checked="checked"'; }else{ $useInAppts = ''; }
                            $storeContainer.= '<div class="row">';
                                $storeContainer.= '<div class="col-4"><label>Appointments</lable></div>';
                                $storeContainer.= '<div class="col">';
                                $storeContainer.= '<input type="checkbox" value="'.$storeDetails->store_id.'" name="tfgg_scp_store_appts_selection[]" '.$useInAppts.' />';
                                $storeContainer.=' </div>';
                            $storeContainer.= '</div>';

                            $storeContainer.='<hr/>';

                            //cart
                            if(in_array($storeDetails->store_id, $selectedStores)){ $useInCart = 'checked="checked"'; }else{ $useInCart = ''; }
                            $storeContainer.= '<div class="row">';
                                $storeContainer.= '<div class="col-4"><label>Cart</lable></div>';
                                $storeContainer.= '<div class="col">';
                                $storeContainer.= '<input type="checkbox" value="'.$storeDetails->store_id.'" name="tfgg_scp_store_selection[]" '.$useInCart.' />';
                                $storeContainer.=' </div>';
                            $storeContainer.= '</div>';

                            if((array_key_exists($storeDetails->store_id, $storeCartDetailsID))&&
                            ($storeCartDetailsID[$storeDetails->store_id]<>'')){ $cartDetailsID = $storeCartDetailsID[$storeDetails->store_id]; }else{ $cartDetailsID = ''; } 
                            $storeContainer.= '<div class="row">';
                                $storeContainer.= '<div class="col-1">&nbsp;</div>';
                                $storeContainer.= '<div class="col-3"><label>Details ID</lable></div>';
                                $storeContainer.= '<div class="col">';
                                $storeContainer.= '<input type="text" class="admin-service-value" name="tfgg_scp_store_cart_details_id['.$storeDetails->store_id.']" value="'.$cartDetailsID.'"/>';
                                $storeContainer.=' </div>';
                            $storeContainer.= '</div>';
                    $storeContainer.= '<br/></div>';

                    $storeContainer.='</div>';
                    echo $storeContainer;

                if($rowCounter / 3==1){
                    //close the row container
                    echo '</div><br/>';
                    $rowCounter=1;
                }else{
                    $rowCounter++;
                }
            }
        }
        echo '</div>';


    }else{
        $alert = "<div class=\"notice notice-error\">Unable to retrieve your store list, please ensure your API credentials are setup</div>";
        echo $alert;
    }
}

?>