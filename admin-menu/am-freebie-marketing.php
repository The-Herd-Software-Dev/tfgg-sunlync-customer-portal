<?php

function tfgg_scp_freebie_marketing(){
    
    if((array_key_exists('action',$_GET))&&
    ($_GET['action']=='delete')&&
    (array_key_exists('tfgg_freebie_id',$_GET))){
        tfgg_scp_freebie_marketing_delete($_GET['tfgg_freebie_id']);
    }

    tfgg_scp_admin_menu_header();
    tfgg_sunlync_cp_show_error_messages(); 
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-11">
                <div class="card">
                    <h5 class="card-header">Freebie-Marketing</h5>
                    <div class="card-body">
    <?php
        if(array_key_exists('action',$_GET)){
            switch($_GET['action']){
                case 'edit':
                    if(array_key_exists('tfgg_freebie_id',$_GET)){
                        tfgg_scp_freebie_marketing_edit($_GET['tfgg_freebie_id']);
                    }else{ 
                        tfgg_scp_freebie_marketing_edit(-1);
                    }
                    break;
                case 'create':
                    tfgg_scp_freebie_marketing_create();
                    break;
                default:
                    tfgg_scp_freebie_marketing_index();
                    break;
            }
        }else{
            tfgg_scp_freebie_marketing_index();
        }
    ?>
                        
                    </div>
                <?php
                    tfgg_scp_get_freebie_marketing_footer();
                ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function tfgg_scp_freebie_marketing_validation_script(){
?>
    <script>

        function resetMarketingFreebieValidation(){
            var alerts = document.getElementsByClassName('mrkting_alert');
            for(var i=0; i<alerts.length; i++){
                alerts[i].innerHTML='';
            }
        }

        function validateMarketingFreebie_slug(slug){
            var slugRegex=/^[a-z0-9]+(?:-[a-z0-9]+)*$/;
            return slugRegex.test(slug);
        }

        function validateMarketingFreebie_dates(activeFrom, activeTo){
            if((activeFrom!='')&&(activeTo!='')){
                var dFrom = new Date(activeFrom)
                var dTo = new Date(activeTo);
            }else{
                var dFrom = new Date();
                var dTo = new Date();
            }
            if(dTo<dFrom){
                var thisAlert = document.getElementById('freebie_alert_for_tfgg_scp_freebie_active_from_to');
                if(thisAlert.innerHTML!=''){ thisAlert.innerHTML+='<br/>';}
                thisAlert.innerHTML+='Invalid Active Dates';
                thisAlert.style.display='block';
                return false;
            }else{
                return true;
            }
        }

        function validateMarketingFreebie_tan(){

            var value = jQuery('#tfgg_scp_freebie_equipment').children("option:selected").val();
            var thisAlert = document.getElementById('freebie_alert_for_tfgg_scp_freebie_type');
            if(value==''){
                if(thisAlert.innerHTML!=''){ thisAlert.innerHTML+='<br/>';}
                thisAlert.innerHTML+='Please select equipment';
                thisAlert.style.display='block';
            }

            if(jQuery('#tfgg_scp_freebie_equipment_time').val()<1){
                if(thisAlert.innerHTML!=''){ thisAlert.innerHTML+='<br/>';}
                thisAlert.innerHTML+='Please set a courtesy time';
                thisAlert.style.display='block';
            }

            return thisAlert.innerHTML==='';

        }

        function validateMarketingFreebie_pkg(){
            var value = jQuery('#tfgg_scp_freebie_package').children("option:selected").val();
            var thisAlert = document.getElementById('freebie_alert_for_tfgg_scp_freebie_type');
            if(value==''){
                if(thisAlert.innerHTML!=''){ thisAlert.innerHTML+='<br/>';}
                thisAlert.innerHTML+='Please select a package';
                thisAlert.style.display='block';
            }
            
            if(jQuery('#tfgg_scp_freebie_package_units').val()<1){
                if(thisAlert.innerHTML!=''){ thisAlert.innerHTML+='<br/>';}
                thisAlert.innerHTML+='Please set a number of units to give';
                thisAlert.style.display='block';
            }

            if(jQuery('#tfgg_scp_freebie_package_exp').val()==''){
                if(thisAlert.innerHTML!=''){ thisAlert.innerHTML+='<br/>';}
                thisAlert.innerHTML+='Please set an expiration date';
                thisAlert.style.display='block';
            }

            return thisAlert.innerHTML==='';    
            
        }

        function validateMarketingFreebie_mem(){

            var value = jQuery('#tfgg_scp_freebie_membership').children("option:selected").val();
            var thisAlert = document.getElementById('freebie_alert_for_tfgg_scp_freebie_type');
            if(value==''){
                if(thisAlert.innerHTML!=''){ thisAlert.innerHTML+='<br/>';}
                thisAlert.innerHTML+='Please select a membership';
                thisAlert.style.display='block';
            }

            if(jQuery('#tfgg_scp_freebie_mem_exp').val()==''){
                if(thisAlert.innerHTML!=''){ thisAlert.innerHTML+='<br/>';}
                thisAlert.innerHTML+='Please set an expiration date';
                thisAlert.style.display='block';
            }

            return thisAlert.innerHTML==='';
            
        }

        function validateMarketingFreebie_promo(){

            var value = jQuery('#tfgg_scp_freebie_promo').children("option:selected").val();
            var thisAlert = document.getElementById('freebie_alert_for_tfgg_scp_freebie_type');
            if(value==''){
                if(thisAlert.innerHTML!=''){ thisAlert.innerHTML+='<br/>';}
                thisAlert.innerHTML+='Please select a promotion';
                thisAlert.style.display='block';
            }
            return thisAlert.innerHTML==='';
            
        }

        function validateMarketingFreebie_redeemPeriod(){
            var value = jQuery("input[type=radio][name=tfgg_scp_freebie_redeem]:checked").val();
            var thisAlert = document.getElementById('freebie_alert_for_tfgg_scp_freebie_redeem');
            if((value==1)&&(jQuery('#tfgg_scp_freebie_redeem_once_every_days').val()<1)){
                if(thisAlert.innerHTML!=''){ thisAlert.innerHTML+='<br/>';}
                thisAlert.innerHTML+='Please set a redemption period';
                thisAlert.style.display='block';
            }
            return thisAlert.innerHTML==='';
        }

        function validateMarketingFreebie(){
            allowSubmit = true;
            event.preventDefault();
            jQuery('#tfgg_scp_freebie_submit').prop('disabled',true);
            jQuery('#tfgg_scp_freebie_submit').html('Validating');
            resetMarketingFreebieValidation();

            if(!validateMarketingFreebie_slug(document.getElementById('tfgg_scp_freebie_slug').value)){
                allowSubmit = false;
                var thisAlert = document.getElementById('freebie_alert_for_tfgg_scp_freebie_slug');
                if(thisAlert.innerHTML!=''){ thisAlert.innerHTML+='<br/>';}
                thisAlert.innerHTML+='Invalid Post Slug';
                thisAlert.style.display='block';
            }

            if(!validateMarketingFreebie_dates(document.getElementById('tfgg_scp_freebie_active_from').value,
            document.getElementById('tfgg_scp_freebie_active_to').value)){
                allowSubmit = false;
            }

            var freebieType = jQuery("input[type=radio][name=tfgg_scp_freebie_type]:checked").val();
            switch(freebieType){
                case 'T':
                    if(!validateMarketingFreebie_tan()){allowSubmt = false;}
                    break;
                case 'P':
                    if(!validateMarketingFreebie_pkg()){allowSubmt = false;}
                    break;
                case 'M':
                    if(!validateMarketingFreebie_mem()){allowSubmt = false;}
                    break;
                case 'C':
                    if(!validateMarketingFreebie_promo()){allowSubmt = false;}
                    break;
            }

            if(!validateMarketingFreebie_redeemPeriod()){allowSubmt = false;}

            if(allowSubmit){
                //if all validation passes
                jQuery('#tfgg_scp_freebie_submit').html('Submitting');
                document.getElementById('tfgg_scp_freebie_marketing_form').submit();
            }else{
                jQuery('#tfgg_scp_freebie_submit').html('Save Freebie');
                jQuery('#tfgg_scp_freebie_submit').prop('disabled',false);  
            }
        }

    </script>
<?php
}

function tfgg_scp_freebie_marketing_create(){

    //create a new freebie
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

    $membershipList = json_decode(tfgg_scp_get_memberships_from_api(''));
    if(StrToUpper($membershipList->results)==='SUCCESS'){
        $membershipList = $membershipList->memberships;
        
    }else{
        $membershipList='';
    }

    $equipmentList = json_decode(tfgg_get_equipment_types(''));
    if(StrToUpper($equipmentList->results)==='SUCCESS'){
        $equipmentList = $equipmentList->equipment;
        
    }else{
        $equipmentList='';
    }

    tfgg_scp_freebie_marketing_validation_script();
?>
    <script>
        function switchFreebiePnl(id){
            //hide all the panels!
            document.getElementById('tfgg_scp_freebie_type_courtesy_tan_pnl').style.display='none';
            document.getElementById('tfgg_scp_freebie_type_package_pnl').style.display='none';
            document.getElementById('tfgg_scp_freebie_type_membership_pnl').style.display='none';
            document.getElementById('tfgg_scp_freebie_type_promotion_pnl').style.display='none';

            switch(id){
                case 'tfgg_scp_freebie_type_T':
                    document.getElementById('tfgg_scp_freebie_type_courtesy_tan_pnl').style.display='';
                    break;
                case 'tfgg_scp_freebie_type_P':
                    document.getElementById('tfgg_scp_freebie_type_package_pnl').style.display='';
                    break;
                case 'tfgg_scp_freebie_type_M':
                    document.getElementById('tfgg_scp_freebie_type_membership_pnl').style.display='';
                    break;
                case 'tfgg_scp_freebie_type_C':
                    document.getElementById('tfgg_scp_freebie_type_promotion_pnl').style.display='';
                    break;
            }
        }
    </script>
    <div class="container-fluid">
    <form method="post" id="tfgg_scp_freebie_marketing_form">
        <div class="form-row">
            <div class="form-group col-8 text-muted" style="font-size: .8em; margin-bottom:.5em">
                The page the user visits that uniquely identifies this freebie
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-4">
                <label for="tfgg_scp_freebie_slug">Post Slug</label>
                <input class="form-control" type="text" name="tfgg_scp_freebie_slug" id="tfgg_scp_freebie_slug" required/>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-12 text-danger mrkting_alert" id="freebie_alert_for_tfgg_scp_freebie_slug"></div>
        </div>
        <div class="form-row">
            <div class="form-group col-9 text-muted" style="font-size: .8em; margin-bottom:.5em">
                The dates between which this freebie can be applied
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-4">
                <label for="tfgg_scp_freebie_active_from">Active From</label>
                <input class="form-control" type="date" name="tfgg_scp_freebie_active_from" id="tfgg_scp_freebie_active_from"/>
            </div>
            <div class="form-group col-4">
                <label for="tfgg_scp_freebie_active_to">Active To</label>
                <input class="form-control" type="date" name="tfgg_scp_freebie_active_to" id="tfgg_scp_freebie_active_to"/>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-12 text-danger mrkting_alert" id="freebie_alert_for_tfgg_scp_freebie_active_from_to"></div>
        </div>
        <div class="form-row">
            <div class="form-group col-9 text-muted"  style="font-size: .8em; margin-bottom:.5em">
                The freebie type the user it to receive
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-12">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tfgg_scp_freebie_type" id="tfgg_scp_freebie_type_T" value="T" onclick="switchFreebiePnl(id)" checked>
                    <label class="form-check-label" for="tfgg_scp_freebie_type_T">Courtesy Tan</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tfgg_scp_freebie_type" id="tfgg_scp_freebie_type_P" value="P" onclick="switchFreebiePnl(id)">
                    <label class="form-check-label" for="tfgg_scp_freebie_type_P">Package</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tfgg_scp_freebie_type" id="tfgg_scp_freebie_type_M" value="M" onclick="switchFreebiePnl(id)">
                    <label class="form-check-label" for="tfgg_scp_freebie_type_M">Membership</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tfgg_scp_freebie_type" id="tfgg_scp_freebie_type_C" value="C" onclick="switchFreebiePnl(id)">
                    <label class="form-check-label" for="tfgg_scp_freebie_type_C">Customer Promotion</label>
                </div>
            </div>
        </div>
        <div class="form-row" id="tfgg_scp_freebie_type_courtesy_tan_pnl">
            <div class="form-group col-4">
                <label for="tfgg_scp_freebie_equipment">Courtesy Tan Equipment</label>
                <select class="form-control" name="tfgg_scp_freebie_equipment" id="tfgg_scp_freebie_equipment">
                    <option value="">Please Select...</option>
                    <?php
                        foreach($equipmentList as &$details){
                            echo '<option value="'.$details->type_desc.':'.$details->type_number.'" data-mintime="'.$details->min_tan_time.'" data-maxtime="'.$details->max_tan_time.'">'.$details->type_desc.'</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="form-group col-4">
                <label for="tfgg_scp_freebie_equipment_time">Tan Length</label>
                <input class="form-control" type="text" name="tfgg_scp_freebie_equipment_time" id="tfgg_scp_freebie_equipment_time"/>
            </div>
        </div>
        <div class="form-row" id="tfgg_scp_freebie_type_package_pnl" style="display:none">
            <div class="form-group col-3">
                <label for="tfgg_scp_freebie_package">Package</label>
                <select class="form-control" name="tfgg_scp_freebie_package" id="tfgg_scp_freebie_package">
                    <option value="">Please Select...</option>
                    <?php
                        foreach($packageList as &$details){
                            echo '<option value="'.$details->description.':'.$details->package_id.'">'.$details->description.'</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="form-group col-2">
                <label for="tfgg_scp_freebie_package_units">Units</label>
                <input class="form-control" type="text" name="tfgg_scp_freebie_package_units" id="tfgg_scp_freebie_package_units"/>
            </div>
            <div class="form-group col-3">
                <label for="tfgg_scp_freebie_package_exp">Expiration Date</label>
                <input class="form-control" type="date" name="tfgg_scp_freebie_package_exp" id="tfgg_scp_freebie_package_exp"/>
            </div>
        </div>
        <div class="form-row" id="tfgg_scp_freebie_type_membership_pnl" style="display:none">
            <div class="form-group col-4">
                <label for="tfgg_scp_freebie_membership">Membership</label>
                <select class="form-control" name="tfgg_scp_freebie_membership" id="tfgg_scp_freebie_membership">
                    <option value="">Please Select...</option>
                    <?php
                        foreach($membershipList as &$details){
                            echo '<option value="'.$details->description.':'.$details->membership_id.'">'.$details->description.'</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="form-group col-4">
                <label for="tfgg_scp_freebie_membership_exp">Expiration Date</label>
                <input class="form-control" type="date" name="tfgg_scp_freebie_membership_exp" id="tfgg_scp_freebie_membership_exp"/>
            </div>
        </div>
        <div class="form-row" id="tfgg_scp_freebie_type_promotion_pnl" style="display:none">
            <div class="form-group col-4">
                <label for="tfgg_scp_freebie_promo">Customer Promotion</label>
                <select  class="form-control" name="tfgg_scp_freebie_promo" id="tfgg_scp_freebie_promo">
                    <option value="">Please Select...</option>
                    <?php
                        foreach($promoList as &$details){
                            echo '<option value="'.$details->Description.':'.$details->PromoID.'">'.$details->Description.'</option>';
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-12 text-danger mrkting_alert" id="freebie_alert_for_tfgg_scp_freebie_type"></div>
        </div>
        
        <div class="form-row">
            <div class="form-group col-9 text-muted"  style="font-size: .8em; margin-bottom:.5em">
                How often the user may receive this particular freebie
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-12">
                <div class="form-check" style="padding-left:0">
                    <input class="form-check-input" type="radio" name="tfgg_scp_freebie_redeem" 
                    id="tfgg_scp_freebie_redeem_onetime" value="0" style="position: relative"
                    checked>
                    <label class="form-check-label" for="tfgg_scp_freebie_redeem_onetime">One Time Only</label>
                </div>
                <div class="form-check" style="padding-left:0">
                    <input class="form-check-input" type="radio" name="tfgg_scp_freebie_redeem" 
                    id="tfgg_scp_freebie_redeem_once_every" value="1" style="position: relative">
                    <label class="form-check-label" for="tfgg_scp_freebie_redeem_once_every">Once Every
                     <input type="text" name="tfgg_scp_freebie_redeem_once_every_days" id="tfgg_scp_freebie_redeem_once_every_days"/> days</label>
                </div>  
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-12 text-danger mrkting_alert" id="freebie_alert_for_tfgg_scp_freebie_redeem"></div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <div class="form-group col-12">
                    <button type="submit" id="tfgg_scp_freebie_submit" class="btn btn-primary" onclick="validateMarketingFreebie();"><?php echo __('Save Freebie');?></button>
                </div>
            </div>
        </div>
        <input type="hidden" name="tfgg_scp_freebie_nonce" id="tfgg_scp_freebie_nonce" value="<?php echo wp_create_nonce('tfgg-scp-freebie-nonce'); ?>"/>
    </form>
    </div>
<?php
}

function tfgg_scp_freebie_marketing_edit($freebieID){
    //edit an existing freebie

    $freebie = tfgg_scp_get_freebie_marketing_records($freebieID,0,1,'');
    if(!$freebie){
    ?>
    <div class="alert alert-warning">The freebie you are trying to edit does not exist</div>
    <?php
    }else{
        $freebie = $freebie[0];

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

        $membershipList = json_decode(tfgg_scp_get_memberships_from_api(''));
        if(StrToUpper($membershipList->results)==='SUCCESS'){
            $membershipList = $membershipList->memberships;
            
        }else{
            $membershipList='';
        }

        $equipmentList = json_decode(tfgg_get_equipment_types(''));
        if(StrToUpper($equipmentList->results)==='SUCCESS'){
            $equipmentList = $equipmentList->equipment;
            
        }else{
            $equipmentList='';
        }

        tfgg_scp_freebie_marketing_validation_script();
    ?>
<script>
    function switchFreebiePnl(id){
        //hide all the panels!
        document.getElementById('tfgg_scp_freebie_type_courtesy_tan_pnl').style.display='none';
        document.getElementById('tfgg_scp_freebie_type_package_pnl').style.display='none';
        document.getElementById('tfgg_scp_freebie_type_membership_pnl').style.display='none';
        document.getElementById('tfgg_scp_freebie_type_promotion_pnl').style.display='none';

        switch(id){
            case 'tfgg_scp_freebie_type_T':
                document.getElementById('tfgg_scp_freebie_type_courtesy_tan_pnl').style.display='';
                break;
            case 'tfgg_scp_freebie_type_P':
                document.getElementById('tfgg_scp_freebie_type_package_pnl').style.display='';
                break;
            case 'tfgg_scp_freebie_type_M':
                document.getElementById('tfgg_scp_freebie_type_membership_pnl').style.display='';
                break;
            case 'tfgg_scp_freebie_type_C':
                document.getElementById('tfgg_scp_freebie_type_promotion_pnl').style.display='';
                break;
        }
    }
    </script>
    <div class="container-fluid">
    <form method="post" id="tfgg_scp_freebie_marketing_form">
        <div class="form-row">
            <div class="form-group col-8 text-muted"  style="font-size: .8em; margin-bottom:.5em">
                The page the user visits that uniquely identifies this freebie
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-4">
                <label for="tfgg_scp_freebie_slug">Post Slug</label>
                <input class="form-control" type="text" name="tfgg_scp_freebie_slug" id="tfgg_scp_freebie_slug" required
                value="<?php echo $freebie->post_slug;?>"/>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-12 text-danger mrkting_alert" id="freebie_alert_for_tfgg_scp_freebie_slug" ></div>
        </div>
        <div class="form-row">
            <div class="form-group col-9 text-muted"  style="font-size: .8em; margin-bottom:.5em">
                The dates between which this freebie can be applied
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-4">
                <label for="tfgg_scp_freebie_active_from">Active From</label>
                <input class="form-control" type="date" name="tfgg_scp_freebie_active_from" id="tfgg_scp_freebie_active_from"
                value="<?php echo $freebie->active_from;?>"/>
            </div>
            <div class="form-group col-4">
                <label for="tfgg_scp_freebie_active_to">Active To</label>
                <input class="form-control" type="date" name="tfgg_scp_freebie_active_to" id="tfgg_scp_freebie_active_to"
                value="<?php echo $freebie->active_to;?>"/>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-12 text-danger mrkting_alert" id="freebie_alert_for_tfgg_scp_freebie_active_from_to"></div>
        </div>
        <div class="form-row">
            <div class="form-group col-9 text-muted"  style="font-size: .8em; margin-bottom:.5em">
                The freebie type the user it to receive
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-12">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tfgg_scp_freebie_type" id="tfgg_scp_freebie_type_T" value="T" onclick="switchFreebiePnl(id)"
                    <?php echo ($freebie->freebie_type=='T'?'checked':'');?>>
                    <label class="form-check-label" for="tfgg_scp_freebie_type_T">Courtesy Tan</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tfgg_scp_freebie_type" id="tfgg_scp_freebie_type_P" value="P" onclick="switchFreebiePnl(id)"
                    <?php echo ($freebie->freebie_type=='P'?'checked':'');?>>
                    <label class="form-check-label" for="tfgg_scp_freebie_type_P">Package</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tfgg_scp_freebie_type" id="tfgg_scp_freebie_type_M" value="M" onclick="switchFreebiePnl(id)"
                    <?php echo ($freebie->freebie_type=='M'?'checked':'');?>>
                    <label class="form-check-label" for="tfgg_scp_freebie_type_M">Membership</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tfgg_scp_freebie_type" id="tfgg_scp_freebie_type_C" value="C" onclick="switchFreebiePnl(id)"
                    <?php echo ($freebie->freebie_type=='C'?'checked':'');?>>
                    <label class="form-check-label" for="tfgg_scp_freebie_type_C">Customer Promotion</label>
                </div>
            </div>
        </div>
        <div class="form-row" id="tfgg_scp_freebie_type_courtesy_tan_pnl" <?php echo ($freebie->freebie_type=='T'?'':'style="display:none"');?>>
            <div class="form-group col-4">
                <label for="tfgg_scp_freebie_equipment">Courtesy Tan Equipment</label>
                <select class="form-control" name="tfgg_scp_freebie_equipment" id="tfgg_scp_freebie_equipment">
                    <option value="">Please Select...</option>
                    <?php
                        foreach($equipmentList as &$details){
                            echo '<option value="'.$details->type_desc.':'.$details->type_number.'" data-mintime="'.$details->min_tan_time.'" 
                            ata-maxtime="'.$details->max_tan_time.'"
                            '.(($freebie->type_number==$details->type_number && $freebie->freebie_type=='T')?'selected':'').'>'.$details->type_desc.'</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="form-group col-4">
                <label for="tfgg_scp_freebie_equipment_time">Tan Length</label>
                <input class="form-control" type="text" name="tfgg_scp_freebie_equipment_time" id="tfgg_scp_freebie_equipment_time"
                value="<?php echo $freebie->courtesy_tan_time;?>"/>
            </div>
        </div>
        <div class="form-row" id="tfgg_scp_freebie_type_package_pnl" <?php echo ($freebie->freebie_type=='P'?'':'style="display:none"');?>>
            <div class="form-group col-3">
                <label for="tfgg_scp_freebie_package">Package</label>
                <select class="form-control" name="tfgg_scp_freebie_package" id="tfgg_scp_freebie_package">
                    <option value="">Please Select...</option>
                    <?php
                        foreach($packageList as &$details){
                            echo '<option value="'.$details->description.':'.$details->package_id.'"
                            '.(($freebie->type_number==$details->package_id && $freebie->freebie_type=='P') ?'selected':'').'>'.$details->description.'</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="form-group col-2">
                <label for="tfgg_scp_freebie_package_units">Units</label>
                <input class="form-control" type="text" name="tfgg_scp_freebie_package_units" id="tfgg_scp_freebie_package_units"
                value="<?php echo $freebie->pkg_units;?>"/>
            </div>
            <div class="form-group col-3">
                <label for="tfgg_scp_freebie_package_exp">Expiration Date</label>
                <input class="form-control" type="date" name="tfgg_scp_freebie_package_exp" id="tfgg_scp_freebie_package_exp"
                value="<?php echo $freebie->exp_date;?>"/>
            </div>
        </div>
        <div class="form-row" id="tfgg_scp_freebie_type_membership_pnl" <?php echo ($freebie->freebie_type=='M'?'':'style="display:none"');?>>
            <div class="form-group col-4">
                <label for="tfgg_scp_freebie_membership">Membership</label>
                <select class="form-control" name="tfgg_scp_freebie_membership" id="tfgg_scp_freebie_membership">
                    <option value="">Please Select...</option>
                    <?php
                        foreach($membershipList as &$details){
                            echo '<option value="'.$details->description.':'.$details->membership_id.'"
                            '.(($freebie->type_number==$details->membership_id && $freebie->freebie_type=='M')?'selected':'').'>'.$details->description.'</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="form-group col-4">
                <label for="tfgg_scp_freebie_membership_exp">Expiration Date</label>
                <input class="form-control" type="date" name="tfgg_scp_freebie_membership_exp" id="tfgg_scp_freebie_membership_exp"
                value="<?php echo $freebie->exp_date;?>"/>
            </div>
        </div>
        <div class="form-row" id="tfgg_scp_freebie_type_promotion_pnl" <?php echo ($freebie->freebie_type=='C'?'':'style="display:none"');?>>
            <div class="form-group col-4">
                <label for="tfgg_scp_freebie_promo">Customer Promotion</label>
                <select  class="form-control" name="tfgg_scp_freebie_promo" id="tfgg_scp_freebie_promo">
                    <option value="">Please Select...</option>
                    <?php
                        foreach($promoList as &$details){
                            echo '<option value="'.$details->Description.':'.$details->PromoID.'"
                            '.(($freebie->type_number==$details->PromoID && $freebie->freebie_type=='C') ?'selected':'').'>'.$details->Description.'</option>';
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-12 text-danger mrkting_alert" id="freebie_alert_for_tfgg_scp_freebie_type"></div>
        </div>
        
        <div class="form-row">
            <div class="form-group col-9 text-muted"  style="font-size: .8em; margin-bottom:.5em">
                How often the user may receive this particular freebie
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-12">
                <div class="form-check" style="padding-left:0">
                    <input class="form-check-input" type="radio" name="tfgg_scp_freebie_redeem" 
                    id="tfgg_scp_freebie_redeem_onetime" value="0" style="position: relative"
                    <?php echo ($freebie->one_time==1?'checked':'');?>>
                    <label class="form-check-label" for="tfgg_scp_freebie_redeem_onetime">One Time Only</label>
                </div>
                <div class="form-check" style="padding-left:0">
                    <input class="form-check-input" type="radio" name="tfgg_scp_freebie_redeem" 
                    id="tfgg_scp_freebie_redeem_once_every" value="1" style="position: relative"
                    <?php echo ($freebie->one_time==0?'checked':'');?>>
                    <label class="form-check-label" for="tfgg_scp_freebie_redeem_once_every">Once Every
                    <input type="text" name="tfgg_scp_freebie_redeem_once_every_days" id="tfgg_scp_freebie_redeem_once_every_days"
                    value="<?php echo $freebie->once_every_x;?>"/> days</label>
                </div>  
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-12 text-danger mrkting_alert" id="freebie_alert_for_tfgg_scp_freebie_redeem"></div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <div class="form-group col-12">
                    <button type="submit" id="tfgg_scp_freebie_submit" class="btn btn-primary"><?php echo __('Save Freebie');?></button>
                </div>
            </div>
        </div>
        <input type="hidden" name="tfgg_scp_freebie_nonce" id="tfgg_scp_freebie_nonce" value="<?php echo wp_create_nonce('tfgg-scp-freebie-nonce'); ?>"/>
        <input type="hidden" name="tfgg_scp_freebie_id" id="tfgg_scp_freebie_id" value="<?php echo $freebie->id; ?>"/>
    </form>
    </div>
    <?php
    }
}

function tfgg_scp_freebie_marketing_delete($freebieID){
    global $wpdb;
    $wpdb->delete("{$wpdb->base_prefix}scp_marketing_freebies",
            array('id'=>$freebieID));

    $success = empty($wpdb->last_error);

    if(!$success){
        tfgg_cp_errors()->add('error_deleting_freebie',__('There was an error deleting the freebie database record: '.$wpdb->last_error));
    }else{
        tfgg_cp_errors()->add('success',__('Freebie successfully deleted')); 
    }
}

function tfgg_scp_freebie_marketing_index(){
    //show all the currently configured freebies

    $freebies = tfgg_scp_get_freebie_marketing_records('',0,100,'');

    if(!$freebies){
    ?>
    <p class="card-text">There are currently no freebies configured</p>
    <?php
    }else{
    ?>
    <style>
        .dashicons-edit-large{
            color:#009933;
        }
        .dashicons-edit-large:hover{
            color:#33cc33;
        }
        .dashicons-database-remove{
            color:#e60000;
        }
        .dashicons-database-remove:hover{
            color:#ff1a1a;
        }
        a.dashicons:hover{
            text-decoration:none;
        }
    </style>
    <table class="table table-sm table-bordered table-striped table-hover" style="width:95%">
        <thead class="thead-light">
            <tr>
                <th>Post Slug</th>
                <th>Active Dates</th>
                <th>Freebie Type</th>
                <th>Freebie Desc</th>
                <th>One Time Only</th>
                <th>Once Every X</th>
                <th>&nbsp</th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach($freebies as &$details){
                switch($details->freebie_type){
                    case 'T': $freebie_desc = 'Tan on';
                        break;
                    case 'P': $freebie_desc = 'Package:';
                        break;
                    case 'M': $freebie_desc = 'Membership:';
                        break;
                    case 'C': $freebie_desc = 'Promotion:';
                }
                $freebie_desc .= ' '.$details->freebie_desc;
                echo'<tr>
                    <td>'.$details->post_slug.'</td>
                    <td>'.$details->active_from.' - '.$details->active_to.'</td>
                    <td>'.$details->freebie_type.'</td>
                    <td>'.$freebie_desc.'</td>
                    <td>'.($details->one_time=='1'?'Yes':'No').'</td>
                    <td>'.$details->once_every_x.'</td>
                    <td><a href="#" class="dashicons dashicons-edit-large" onclick="window.location.search+=\'&action=edit&tfgg_freebie_id='.$details->id.'\';"></a>
                    <a href="#" class="dashicons dashicons-database-remove" onclick="window.location.search+=\'&action=delete&tfgg_freebie_id='.$details->id.'\';"></a></td>
                </tr>';
            }
        ?>
        </tbody>
    </table>
    <?php
    }
    
}
    
function tfgg_scp_get_freebie_marketing_footer(){
    ?>
    <div class="card-footer">
        <div class="float-right">
    <?php
    if((array_key_exists('action',$_GET))&&
    ($_GET['action']!='delete')){
        tfgg_scp_get_freebie_marketing_footer_cancel_btn();
    }else{
        tfgg_scp_get_freebie_marketing_footer_add_new_btn();
    }
    ?>
    
    </div>
    </div>
    <?php
}

function tfgg_scp_get_freebie_marketing_footer_add_new_btn(){
    ?>
        <button type="button" class="btn btn-success" onclick="window.location.search+='&action=create';"><?php echo __('Add New');?></a> 
    <?php
}

function tfgg_scp_get_freebie_marketing_footer_cancel_btn(){
    ?>
        <button type="button" class="btn btn-secondary" onclick="window.location.search='?page=tfgg-scp-freebie-marketing';"><?php echo __('Cancel');?></a> 
    <?php
}

function tfgg_scp_process_freebie_save(){

    if ((array_key_exists('tfgg_scp_freebie_nonce',$_POST))&&
    (wp_verify_nonce($_POST['tfgg_scp_freebie_nonce'],'tfgg-scp-freebie-nonce'))){

        $postSlug = $_POST['tfgg_scp_freebie_slug'];
        $activeFrom = $_POST['tfgg_scp_freebie_active_from'];
        $activeTo = $_POST['tfgg_scp_freebie_active_to'];
        $type = $_POST['tfgg_scp_freebie_type'];
        
        switch($type){
            case 'T':
                $arr = explode(':',$_POST['tfgg_scp_freebie_equipment']);
                $type_number = $arr[1];
                $type_desc = $arr[0];
                $tan_time = $_POST['tfgg_scp_freebie_equipment_time'];
                $pkg_units = 0;
                $exp_date = '';
                break;
            case 'P':
                $arr = explode(':',$_POST['tfgg_scp_freebie_package']);
                $type_number = $arr[1];
                $type_desc = $arr[0];
                $tan_time = 0;
                $pkg_units = $_POST['tfgg_scp_freebie_package_units'];
                $exp_date=$_POST['tfgg_scp_freebie_package_exp'];
                break;
            case 'M':
                $arr = explode(':',$_POST['tfgg_scp_freebie_membership']);
                $type_number = $arr[1];
                $type_desc = $arr[0];
                $tan_time = 0;
                $pkg_units = 0;
                $exp_date= $_POST['tfgg_scp_freebie_membership_exp'];
                break;
            case 'C':
                $arr = explode(':',$_POST['tfgg_scp_freebie_promo']);
                $type_number = $arr[1];
                $type_desc = $arr[0];
                $tan_time = 0;
                $pkg_units = 1;
                $exp_date= '';
                break;
        }

        if($_POST['tfgg_scp_freebie_redeem']==1){
            $one_time=0;
            $once_every_x = $_POST['tfgg_scp_freebie_redeem_once_every_days'];
        }else{
            $one_time=1;
            $once_every_x = 0;
        }
        global $wpdb;

        //check that the slug doesn't already exist
        $existCheck = tfgg_scp_get_freebie_marketing_records('',0,1,$postSlug);

        if(array_key_exists('tfgg_scp_freebie_id',$_POST)){

            if(!$existCheck){
                $update = true;
            }else{
                //need to check if the returned id matches
                if($existCheck[0]->id == $_POST['tfgg_scp_freebie_id']){
                    $update = true;
                }else{
                    $update = false;
                    tfgg_cp_errors()->add('error_creating_freebie',__('The post slug you are trying to use, '.$postSlug.', already exists, please use another')); 
                }
            }

            if($update){
                $wpdb->update("{$wpdb->base_prefix}scp_marketing_freebies",
                array('post_slug' =>$postSlug,
                    'active_from' =>$activeFrom,
                    'active_to' =>$activeTo,
                    'freebie_type' =>$type,
                    'freebie_desc' =>$type_desc,
                    'type_number'=>$type_number,
                    'one_time'=>$one_time,
                    'once_every_x'=>$once_every_x,
                    'courtesy_tan_time'=>$tan_time,
                    'pkg_units'=>$pkg_units,
                    'exp_date'=>$exp_date),
                array('id'=>$_POST['tfgg_scp_freebie_id']));

                $success = empty($wpdb->last_error);

                if(!$success){
                    tfgg_cp_errors()->add('error_updating_freebie',__('There was an error updating the freebie database record: '.$wpdb->last_error));
                }else{
                    tfgg_cp_errors()->add('success',__('Freebie successfully updated')); 
                }
            }
        }else{

            if(!$existCheck){
                $wpdb->insert("{$wpdb->base_prefix}scp_marketing_freebies",
                array(
                    'post_slug' =>$postSlug,
                    'active_from' =>$activeFrom,
                    'active_to' =>$activeTo,
                    'freebie_type' =>$type,
                    'freebie_desc' =>$type_desc,
                    'type_number'=>$type_number,
                    'one_time'=>$one_time,
                    'once_every_x'=>$once_every_x,
                    'courtesy_tan_time'=>$tan_time,
                    'pkg_units'=>$pkg_units,
                    'exp_date'=>$exp_date,
                ));

                $success = empty($wpdb->last_error);

                if(!$success){
                    tfgg_cp_errors()->add('error_creating_freebie',__('There was an error creating the freebie database record: '.$wpdb->last_error));
                }else{
                    tfgg_cp_errors()->add('success',__('Freebie successfully saved')); 
                }
            }else{
                    tfgg_cp_errors()->add('error_creating_freebie',__('The post slug you are trying to use, '.$postSlug.', already exists, please use another')); 
            }
        }
    }

}
add_action('init','tfgg_scp_process_freebie_save');

?>