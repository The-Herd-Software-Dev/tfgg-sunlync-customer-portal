/*global $*/
/*global jQuery*/
/*global localAccess*/

jQuery(function(){
    /*if (typeof jQuery != 'undefined') {  
        // jQuery is loaded => print the version
        alert(jQuery.fn.jquery);
    }*/

    jQuery('.close').click(function(){
        var pnl = this.closest('.alert');
        if(jQuery(pnl).hasClass('alert-dismissible')){
            jQuery(pnl).hide();
        }            
    });
    
    jQuery('.equipPnl').click(function(){
        event.StopPropagation();
        event.StopImmediatePropagation(); 
    }); 

    //set the tandc dialog width to half the screen width/height
    var width = jQuery('#main-content').width()/2;
    var height = jQuery('#main-content').height()/2;

    //set dialog options
    jQuery("#tfgg_cp_reg_tandc_dialog").dialog({
        autoOpen: false,
        show: {
            effect: "blind",
            duration: 1000
        },
        width: width,
        height: height,
        modal:true,
        draggable: false,
        resizeable: false
    });

    jQuery('#tfgg_cp_skin_type').change(function(){
        jQuery('#new_reg_skin_type_alertpnl').css('display','none');
        jQuery('#new_reg_skin_type_alertpnl').html('');
        jQuery('#tfgg_cp_skin_type_confirm').prop("checked",false);

        //2019-09-26 CB V1.0.0.4 - updated to reflect tanning warning message
        //2019-09-30 CB V1.0.0.6 - changed to only show for 'SENSITIVE'
        var selected = jQuery(this).children('option:selected').text();
        if(selected.toUpperCase()=='SENSITIVE'){
            jQuery('#new_reg_skin_type_alertpnl').html('Skin Type: No Tanning - You are unsuitable for UV tanning but you can still use spray tanning services');
            jQuery('#new_reg_skin_type_alertpnl').css('display','block');
        }
    });

    jQuery('#tfgg_cp_store').change(function(){
        //2019-09-30 CB V1.0.0.6 - added
        jQuery('#chiswick_display_warning').css('display','none');
        var selected = jQuery(this).children('option:selected').text();
        if(selected.toUpperCase()=='CHISWICK'){
            jQuery('#chiswick_display_warning').css('display','block');    
        }
    });

    jQuery('#tfgg_store_filter').on('input',function(){
        jQuery('#tfgg_storeselect_warning').hide();
        var filter =  jQuery('#tfgg_store_filter').val().toLowerCase();//2019-06-11 - added toLowercase to remove case sensitivity
        jQuery('.appts-store-selector').each(function(){
            jQuery(this).show();
        });
        if(filter===''){
            
        }else{
            jQuery('.appts-store-selector').each(function(){
                var loc = jQuery(this).data('storelocation');
                if (loc.toLowerCase().indexOf(filter) < 0){//if loc doesnt contain filter, hide it
                    jQuery(this).hide();
                }
            });//each
        }
        
        if(jQuery('.appts-store-selector:visible').length==0){
            jQuery('#tfgg_storeselect_warning').show();        
        }
    });
});

var selectedStorePanel = "";

function FormatTimeToUK(time){
    //note the 2000 date - this is arbitrary,
    //we are just using this to generate a valid Date()
    //2019-10-12 CB V1.0.1.5 - changed code to manually split and recreate for Safari
    time = time.split(':');
    //var formattedTime = new Date('2000-01-01'+time);
    var formattedTime = new Date('2000','01','01', time[0], time[1], time[2]);
    //console.log(formattedTime);
    var options = {
        hour: 'numeric',
        minute: 'numeric',
        hour12: true
    }; 
    formattedTime = formattedTime.toLocaleString('en-GB', options);
    //console.log(formattedTime);
    return formattedTime;
}

function TestAPICredentials(){
    //console.log('TestAPICredentials');
    jQuery("#tfgg-api-options-test-api-response").css('display','none');
    jQuery("#tfgg-api-options-test-api-response").removeClass('notice-error');
    jQuery("#tfgg-api-options-test-api-response").removeClass('notice-success');
    jQuery("#tfgg-api-test-response").text('');

    var pathname = window.location.pathname;
   
    jQuery.get(localAccess.adminAjaxURL,{
        'action'    : 'tfgg_get_api_version',
        'dataType'  : 'json',
		'pathname'  : pathname
    },function(data){
        //console.log(data);
        var obj = jQuery.parseJSON(data);
        console.log(obj);

        if(obj["results"]=='success'){
            jQuery("#tfgg-api-options-test-api-response").addClass('notice-success');
            jQuery("#tfgg-api-test-response").text(obj['api_version']);
        }else{
            jQuery("#tfgg-api-options-test-api-response").addClass('notice-error');
            jQuery("#tfgg-api-test-response").text(obj['error_message']);
        }


        jQuery("#tfgg-api-options-test-api-response").css('display','block');
    });
}

function testAJAX(){
    
    jQuery.get(localAccess.adminAjaxURL,{
        'action'    : 'tfgg_get_api_version',
        'dataType'  : 'json',
		'pathname'  : window.location.pathname
    },function(data){
        console.log(data);
        
    });
}

function timer(time,update,complete) {
    var start = new Date().getTime();
    var interval = setInterval(function() {
        var now = time-(new Date().getTime()-start);
        if( now <= 0) {
            clearInterval(interval);
            update('');
            complete();
        }
        else update(Math.ceil(now/1000));
    },100); // the smaller this number, the more accurate the timer will be
}//function timer

function CancelAppt(apptID){
    event.preventDefault();

    myConfirm('Are you sure you wish to cancel this appointment?',
    function(){processApptCancelation(apptID);},
    function(){return false;},
    'Confirm Appointment Cancellation');   

}

function processApptCancelation(apptID){
    var pathname = window.location.pathname;
    jQuery('#appt-cancel-response').empty();
    jQuery('#appt-cancel-response').removeClass('alert alert-success alert-error');
    jQuery('#appt-cancel-response').css('display','none');

    jQuery.post(localAccess.adminAjaxURL,{
        'action'    : 'tfgg_set_api_cancel_appointment',
		'data'      : {appt_ID: apptID},
		'dataType'  : 'json',
		'pathname'  : pathname
    },function(data){
        //console.log(data);
        var obj = jQuery.parseJSON(data);
        //console.log(obj);

        if(obj["results"]=='success'){
            jQuery('#appt-cancel-response'+apptID).addClass('alert alert-success');
            jQuery('#appt-cancel-response'+apptID+'-message').html('<strong>Success: </strong>your appointment was successfully cancelled');
            jQuery('#apptContainer'+apptID).hide();
        }else{
            jQuery('#appt-cancel-response'+apptID).addClass('alert alert-error');
            jQuery('#appt-cancel-response'+apptID).html('<span><strong>Error: </strong>unable to cancel your appointment, please contact the store</span>');
        }
        jQuery('#appt-cancel-response'+apptID).css('display','block');
    });
}

function ResetRegValidation(){

    jQuery('.reg_alert').each(function(){
        jQuery(this).css('display','none');
        jQuery(this).html('');
    });
}

function ValidateNewReg(isOnline){
    
    ResetRegValidation();
    event.preventDefault();
    var bResult = true;
    
    if(!isEmail(jQuery('#tfgg_cp_user_email').val())){
        jQuery('#new_reg_email').html('<p>'+jQuery('label[for="tfgg_cp_user_email"]').text()+' is required</p>');
        jQuery('#new_reg_email').css('display','block');
        bResult = false;
    }

    jQuery('.required').each(function(){
        //console.log(this.id);
        if(jQuery(this).val()===''){
            var label = jQuery('label[for="'+this.id+'"]').text();
            var alrtpnl = '#'+jQuery(this).data('alertpnl');
            jQuery(alrtpnl).html('<p>'+label+' is required</p>');
            jQuery(alrtpnl).css('display','block');
            bResult = false;
        }
    });

    if(jQuery('#tfgg_cp_user_gender option:selected').val()==='please select'){
	    jQuery('#new_reg_gender').css('display','block');
	    jQuery('#new_reg_gender').html('Please select an option');
	    bResult = false;
    }

    //new postcode validation
    var postcode = jQuery('#tfgg_cp_post_code').val();
    if(!isValidPostcode(postcode)){
        jQuery('#new_reg_post_code_alertpnl').css('display','block');
        jQuery('#new_reg_post_code_alertpnl').html('This is not a valid post code');
	    bResult = false; 
    }
    
    //new mobile number validation
    var mob=jQuery('#tfgg_cp_mobile_phone').val();
    mob=mob.replace(/\s/g,'');
    mob=mob.replace(/\D/g,'');
    jQuery('#tfgg_cp_mobile_phone').val(mob);
    /*if((!(Math.floor(mob)==mob))&&(!jQuery.isNumeric(mob))){
        jQuery('#new_reg_mobile_phone').css('display','block');
	    jQuery('#new_reg_mobile_phone').html('This is not a valid number');
	    bResult = false; 
    }*/

    if (!isValidMobileNumber(jQuery('#tfgg_cp_mobile_phone').val())) {
        jQuery('#new_reg_mobile_phone').css('display','block');
	    jQuery('#new_reg_mobile_phone').html('This is not a valid number');
	    bResult = false;     
    }

    if(jQuery('#tfgg_cp_store option:selected').val()==='please select'){
	    jQuery('#new_reg_store_alertpnl').css('display','block');
	    jQuery('#new_reg_store_alertpnl').html('Please select an option');
	    bResult = false;
    }
    
    if(jQuery('#tfgg_cp_skin_type').val()==='please select'){
        jQuery('#new_reg_skin_type_alertpnl').css('display','block');
	    jQuery('#new_reg_skin_type_alertpnl').html('Please select an option');
	    bResult = false;
    }else if(jQuery('#tfgg_cp_skin_type_confirm').is(":checked")===false){
        jQuery('#new_reg_skin_type_confirm_alertpnl').css('display','block');
	    jQuery('#new_reg_skin_type_confirm_alertpnl').html('Please confirm your selection');
	    bResult = false;
    }

    if(jQuery('#tfgg_cp_eye_color').val()==='please select'){
        jQuery('#new_reg_eye_color_alertpnl').css('display','block');
	    jQuery('#new_reg_eye_color_alertpnl').html('Please select an option');
	    bResult = false;
    }

	if(jQuery('#tfgg_cp_how_hear option:selected').val()==='please select'){
	    jQuery('#new_reg_how_hear_alert').css('display','block');
	    jQuery('#new_reg_how_hear_alert').html('Please select an option');
	    bResult = false;
    }
    
    if(jQuery('#tfgg_cp_skin_type_confirm').is(":checked")===false){
        jQuery('#new_reg_skin_type_confirm_alertpnl').css('display','block');
	    jQuery('#new_reg_skin_type_confirm_alertpnl').html('Please confirm');
	    bResult = false;
    }

	if(jQuery('#tfgg_cp_user_tandc_agree').is(":checked")===false){
	    jQuery('#new_reg_tandc_confirm').css('display','block');
        jQuery('#new_reg_tandc_confirm').html('Terms and Conditions must be accepted');	    
	    bResult = false;
    }
    
    if(jQuery('#tfgg_cp_user_pass').val()===""){
        jQuery('#new_reg_pass').css('display','block');
	    jQuery('#new_reg_pass').html('A password must be set');
	    bResult = false;    
    }

    if(!isValidPass(jQuery('#tfgg_cp_user_pass').val())){
        jQuery('#new_reg_pass').css('display','block');
	    jQuery('#new_reg_pass').html('Password does not meet requirements');
	    bResult = false;    
    }

    if(jQuery('#tfgg_cp_user_pass_confirm').val()===""){
        jQuery('#new_reg_pass_confirm').css('display','block');
	    jQuery('#new_reg_pass_confirm').html('A confirmation of your password must be set');
	    bResult = false;    
    }

    if(jQuery('#tfgg_cp_user_pass').val()!=jQuery('#tfgg_cp_user_pass_confirm').val()){
        jQuery('#new_reg_pass_confirm').css('display','block');
	    jQuery('#new_reg_pass_confirm').html('Confirmation password does not match');
	    bResult = false;    
    }

    if(!bResult){
        event.preventDefault();
        grecaptcha.reset();//reset the reCaptcha
        jQuery('#registrationSubmitButton').attr('disabled','true');
        jQuery('#new_reg_overall_alertpnl').css('display','block');
        jQuery('#new_reg_overall_alertpnl').html('We encountered an error with your registration, please fix the highlighted fields');
        genModalDialog('instore_reg_validation_fail_warning');
        return false;
    }else{
        if(isOnline){
            jQuery('#sunlync_cp_registration_form').submit();
        }else{
            jQuery('#sunlync_cp_instore_registration_form').submit();
        }
    }
}

function isValidMobileNumber(mob) {
    mob=mob.replace(/\s/g,'');
    mob=mob.replace(/\D/g,'');
    if((!(Math.floor(mob)==mob))&&(!jQuery.isNumeric(mob))){
        return false;
    }
    /*2019-11-13 CB V1.2.2.4 - no longer validating uk cell phones
    if ( mob.trim().length != 11 || mob.trim().substring(0,2) !== "07"  )
        return false;
    else return true;*/

    //15 characters is the max length the API allows for
    if( mob.trim().length>15){return false;}
    
    return true;
}

function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

function isValidPass(pass){
    
    var passRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,})");
    /*
    (?=.*[a-z]) -> at least 1 lowercase
    (?=.*[A-Z]) -> at least 1 uppercase
    (?=.*[0-9]) -> at least 1 number
    (?=.[!@#\$%\^&]) -> at least one special character //removed 2019-06-02
    (?=.{8,}) -> at least 8 characters
    */
    return passRegex.test(pass);
}

function isValidPostcode(postCode){
    var postRegex = new RegExp("^([A-Za-z][A-Ha-hK-Yk-y]?[0-9][A-Za-z0-9]? ?[0-9][A-Za-z]{2}|[Gg][Ii][Rr] ?0[Aa]{2})$");
    return postRegex.test(postCode);
}

function ValidNewPass(){
    if(jQuery('#tfgg_cp_demo_password').val()===''){
        return true;//only validate passwords if a new one has been entered
    }
    
    if(jQuery('#tfgg_cp_demo_password').val()!=jQuery('#tfgg_cp_demo_password_confirm').val()){
        jQuery('#alertpnl_password_confirm').html('Passwords do not match');
        jQuery('#alertpnl_password_confirm').css('display','inline');
        return false;
    }
    
    if(!isValidPass(jQuery('#tfgg_cp_demo_password').val())){
        jQuery('#alertpnl_password').html('Password does not meet requirements');
        jQuery('#alertpnl_password').css('display','inline');
        return false;    
    }
    
    return true;
    
}

function ResetDemoValidation(){
    jQuery('span[id^="alertpnl"]').html('');
    jQuery('span[id^="alertpnl"]').hide();
}

function ValidDemoInfo() {
    event.preventDefault();

    ResetDemoValidation();
	var result = true;//default

    jQuery(".required").removeClass( "emptyvalue" );
    
    if(!isEmail(jQuery('#tfgg_cp_demo_email').val())){
        jQuery('#alertpnl_email').html('This is not a valid E-Mail');
        jQuery('#alertpnl_email').css('display','block');
        result = false;
    }

	jQuery(".required").each(function( index ) {
		if (jQuery(this).val().trim() === "") {
			jQuery(this).addClass('emptyvalue');
			jQuery(this).focus();
			var alrtpnl = '#'+jQuery(this).data('alertpnl');
			jQuery(alrtpnl).html(' - Required');
            jQuery(alrtpnl).css('display','inline');
			result = false;
			//return false;
		}
	});
    
    var mob = jQuery('#tfgg_cp_demo_cellphone').val();
    mob=mob.replace(/\s/g,'');
    mob=mob.replace(/\D/g,'');
    jQuery('#tfgg_cp_demo_cellphone').val(mob);
    if(mob!=''){
        if (!isValidMobileNumber(mob)) {
            jQuery('#alertpnl_mobile').css('display','inline');
            jQuery('#alertpnl_mobile').html('This is not a valid number');
            result=false;
        }
    }

    if(jQuery('#tfgg_cp_demo_password').val()===''){
        //only validate passwords if a new one has been entered
    }else    
    if(jQuery('#tfgg_cp_demo_password').val()!=jQuery('#tfgg_cp_demo_password_confirm').val()){
        jQuery('#alertpnl_password_confirm').html('Passwords do not match');
        jQuery('#alertpnl_password_confirm').css('display','inline');
        result=false;
    }else    
    if(!isValidPass(jQuery('#tfgg_cp_demo_password').val())){
        jQuery('#alertpnl_password').html('Password does not meet requirements');
        jQuery('#alertpnl_password').css('display','inline');
        result=false;    
    }

    var postcode = jQuery('#tfgg_cp_demo_postcode').val();
    if(postcode!=''){
        if(!isValidPostcode(postcode)){
            jQuery('#alertpnl_postcode').css('display','inline');
            jQuery('#alertpnl_postcode').html('This is not a valid post code');
            result=false;
        }
    }
    console.log(result);
    if(!result){
        event.preventDefault();
        return false;
    }else{
        jQuery('#tfgg_sunlync_cp_demo').submit();
    }
}

function ToggleCommPref(allow){

    if(allow=='1'){
        jQuery('input[name="allow_text_marketing"]').attr('disabled',false);
        jQuery('input[name="allow_email_marketing"]').attr('disabled',false);
        jQuery('input[name="allow_mail_marketing"]').attr('disabled',false);
        jQuery('input[name="allow_phone_marketing"]').attr('disabled',false);
    }else{
        jQuery('input[name="allow_text_marketing"]').attr('checked',false);
        jQuery('input[name="allow_text_marketing"]').attr('disabled',true);

        jQuery('input[name="allow_email_marketing"]').attr('checked',false);
        jQuery('input[name="allow_email_marketing"]').attr('disabled',true);

        jQuery('input[name="allow_mail_marketing"]').attr('checked',false);
        jQuery('input[name="allow_mail_marketing"]').attr('disabled',true);

        jQuery('input[name="allow_phone_marketing"]').attr('checked',false);
        jQuery('input[name="allow_phone_marketing"]').attr('disabled',true);
    }
}

function CalculateSkinType(){

    ResetRegValidation();

    jQuery('#new_reg_skintype_text').empty();
    jQuery('#tfgg_new_reg_skintype').val('');
    jQuery('#tfgg_new_reg_eyecolour').val('');

    for(var n=1; n<=8; n++){
        jQuery('#question'+n+'-alertpnl').hide();
    }

    var skinType;
    var skinTypeDesc;
    var eyeColor;
    var question;
    var score=0;
    var scoreSelected;

    for(var n=1; n<=8; n++){
        question='question'+n;
        scoreSelected=false;
        var questionOptions = document.getElementsByName(question);
        for (var p = 0; p < questionOptions.length; p++) {
            if(questionOptions[p].checked){
                score+=p;
                scoreSelected=true;
                if(n==1){
                    switch(p){
                        case 0: eyeColor='Light blue, grey, green';break;
                        case 1: eyeColor='Blue, grey or dark green';break;
                        case 2: eyeColor='Light brown';break;
                        case 3: eyeColor='Brown';break;
                        case 4: eyeColor='Dark brown';break;
                    }
                }
            }
        }

        if(!scoreSelected){
            jQuery('#question'+n+'-alertpnl').text('Please select an option');
            jQuery('#question'+n+'-alertpnl').show();
            return false;
        }
    }

    switch(true){
        case score<8:
            skinType=4;
            skinTypeDesc='No Tanning - You are unsuitable for UV tanning but you can still use spray tanning services';
            break;
        case score<17:
            skinType=5;
            skinTypeDesc='Fair';
            break;
        case score<26:
            skinType=2;
            skinTypeDesc='Medium';
            break;
        default:
            skinType=3;
            skinTypeDesc='Dark';
    }//switch

    jQuery('#tfgg_new_reg_skintype').val('000000000'+skinType);
    jQuery('#tfgg_new_reg_eyecolour').val(eyeColor);
    jQuery('#new_reg_skintype_text').text(skinTypeDesc);
}

function ApptStoreSelect(){
    jQuery('#tfgg_appt_store_text').empty();
    jQuery('#tfgg_appt_storecode').val('');
    jQuery('#tfgg_appt_storeloc').val('');
    
    jQuery('#tfgg_appt_date_text').empty();
    jQuery('#tfgg_appt_date').val('');
    
    jQuery('#alertpnl_tfgg_appt_set_store_date_message').empty();
    jQuery('#alertpnl_tfgg_appt_set_store_date').hide();
    
    jQuery('#tfgg_appt_len').val();
    jQuery('#tfgg_appt_len_text').empty();
    
    if(!selectedStorePanel){
        jQuery('#alertpnl_tfgg_appt_set_store_date_message').text('Please select a store');
        jQuery('#alertpnl_tfgg_appt_set_store_date').show();
        return false;
    }
    
    jQuery('#tfgg_appt_store_text').text(jQuery('#' + selectedStorePanel).data('storelocation'));
    jQuery('#tfgg_appt_success_store').text(jQuery('#' + selectedStorePanel).data('storelocation'));
    jQuery('#tfgg_appt_storeloc').val(jQuery('#' + selectedStorePanel).data('storelocation'));
    jQuery('#tfgg_appt_storecode').val(jQuery('#' + selectedStorePanel).data('storecode'));
    jQuery('#tfgg_appt_len').val(jQuery('#' + selectedStorePanel).data('apptlength'));
    
    var dateselected=jQuery('#tfgg_appt_set_date').val();
    
    if(dateselected==''){
        jQuery('#alertpnl_tfgg_appt_set_store_date_message').text('Please select an appointment date');
        jQuery('#alertpnl_tfgg_appt_set_store_date').show(); 
        return false;    
    }

    //var year=dateselected.substring(0,4);
    //var mth=dateselected.substring(5,7);
    //var day=dateselected.substring(8,10);
    //dateselected=new Date(year,mth-1,day);
    
    dateselected = jQuery("#appt_date_calendar").datepicker('getDate');

    //console.log(dateselected.toLocaleDateString());

    if(!Date.parse(dateselected)){
        jQuery('#alertpnl_tfgg_appt_set_store_date_message').text('Please select an appointment date');
        jQuery('#alertpnl_tfgg_appt_set_store_date').show(); 
        return false;
    }
    //console.log(dateselected.toLocaleDateString('en-GB'));
    jQuery('#tfgg_appt_date_text').text(dateselected.toLocaleDateString('en-GB'));
    jQuery('#tfgg_appt_success_date').text(dateselected.toLocaleDateString('en-GB'));
    //jQuery('#tfgg_appt_date').val(jQuery('#tfgg_appt_set_date').val());
    jQuery('#tfgg_appt_date').val(jQuery.datepicker.formatDate('yy-mm-dd',dateselected));



    changeActiveContentPanel('appts_loading');
    
    LoadStoreEquipment(jQuery('#' + selectedStorePanel).data('storecode'));
    
    
}

function moreEquipInfo(equimentType){
    
}

function ResetEquipmentPnlSelection(){
    jQuery('#tfgg_appt_equip_text').empty();
    jQuery('#tfgg_appt_equip_type').val();
    jQuery('#tfgg_appt_equip_type_multiplier').val();
    jQuery('.equipPnl').each(function(){
        jQuery(this).css('background-color','white');        
    });    
}

function highlightEquipment(equipmentPnl){
    ResetEquipmentPnlSelection();
    jQuery('#tfgg_appt_len_text').text((jQuery('#tfgg_appt_len').val()*jQuery('#'+equipmentPnl).data('apptblocks'))+' min(s)');

    jQuery('#tfgg_appt_equip_text').text(jQuery('#'+equipmentPnl).data('description'));
    jQuery('#tfgg_appt_equip_type').val(jQuery('#'+equipmentPnl).data('equipmenttype'));
    jQuery('#tfgg_appt_equip_type_multiplier').val(jQuery('#'+equipmentPnl).data('apptblocks'));
    
    jQuery('.appts-euip-selector-selected').removeClass('appts-euip-selector-selected');
    jQuery('#' + equipmentPnl).addClass('appts-euip-selector-selected');
    ApptEquipSelect();
}

function LoadStoreEquipment(storecode){
    jQuery('#tfgg_appt_set_equip').empty();
    //console.log('LoadstoreEquipment: '+storecode);
    var pathname = window.location.pathname;
    //console.log('pathname: '+localAccess.adminAjaxURL);
    
    //console.log(localAccess);
    jQuery.get(localAccess.adminAjaxURL,{
        'action'    : 'tfgg_api_get_store_equipment',
        'dataType'  : 'json',
        'data'      : {store_code: storecode},
		'pathname'  : pathname
    },function(data){
        //console.log(data);  
        var returnData = jQuery.parseJSON(data);
        
        if(returnData["results"]=='success'){
            //console.log(returnData["equipment"]);
            jQuery.each(returnData["equipment"], function(key,details){
                var pnlName="'equipPnl_"+details['equipmenttype']+"'";
                
                //2019-10-03 CB V1.0.0.9 - added replace(/ \([\s\S]*?\)/g, '') to description to strip data between ()

                var pnl='<div id="equipPnl_'+details["equipmenttype"]+'" class="appts-equip-selector equipPnl" onclick="highlightEquipment('+pnlName+')" style="cursor: pointer;" '+ 
                'data-equipmenttype="'+details["equipmenttype"]+'"'+
                'data-description="'+details["description"].replace(/ \([\s\S]*?\)/g, '')+'" data-apptblocks="'+details["minappointmentblocks"]+'">' +
               
                '<div class="bed-image" style="float:right;"> '+
                    '<img src="../'+returnData["picDir"]+'/'+details["image"]+'" style="display:block; max-height: 50px;max-width:70px; width: auto; height: auto;"> '+
                '</div>'+
               
                '<div class="row-body" style="">'+
                    '<span class="room-name">'+details["description"].replace(/ \([\s\S]*?\)/g, '')+'</span>'+
                    '<br />'+
                        //'<a class="details-link" href="javascript:void(0)" onclick="moreEquipInfo('+details["equipmenttype"]+')">More Info ></a>'+
                        '<span style="font-size:smallest;">Appointment Length: '+details["minappointmentblocks"]*jQuery('#tfgg_appt_len').val()+' minutes</span>'+
                    '<br />'+
                '</div>'+
                    '</div>';
                    
                jQuery('#tfgg_appt_set_equip').append(pnl);
            });
            changeActiveContentPanel('appts_equipment');
        }else{
            var pnl='<div id="alertpnl_tfgg_appt_no_equipment" style="display:block;" class="alert alert-warning">'+
                        '<span id="alertpnl_tfgg_appt_no_equipment_message">No equipment available for appointments, please try a different selection</span>'+
                    '</div>';
            jQuery('#tfgg_appt_set_equip').append(pnl);
            changeActiveContentPanel('appts_equipment');
        }
    });
    
}

function selectStore(storepanel) {
    selectedStorePanel = storepanel;
    jQuery('.appts-store-selector-selected').removeClass('appts-store-selector-selected');
    jQuery('#' + selectedStorePanel).addClass('appts-store-selector-selected');
    ApptStoreSelect();
}

function changeActiveContentPanel(contentId){
    jQuery('#appt-booking-success').hide();
    jQuery('#appt-booking-fail').hide();
    jQuery('.appts-container-active').addClass('appts-container-inactive').removeClass('appts-container-active');
    jQuery('#' + contentId).addClass('appts-container-active').removeClass('appts-container-inactive');
}

function LoadEquipTimeSlots(){
    jQuery('#appts-timeslot-container').empty();
    var equipmenttype = jQuery('#tfgg_appt_equip_type').val();
    var storecode = jQuery('#tfgg_appt_storecode').val();
    var apptdate = jQuery('#tfgg_appt_date').val();
    var aDate = apptdate.split('-');//2019-10-08 CB V1.0.1.1
    
    var apptlength = jQuery('#tfgg_appt_len').val()*jQuery('#tfgg_appt_equip_type_multiplier').val();

    var emptypnl='<div id="alertpnl_tfgg_appt_no_time_slot" style="display:block;" class="alert alert-warning">'+
                        '<span id="alertpnl_tfgg_appt_no_time_slot_message">No appointments are available, please try a different selection</span>'+
                    '</div><br/>'+
                    '<span><button class="appts-button appts-standard-button" onclick="ApptGoToApptDate()">Date selection</button></span><br/><br/>'+
                    '<span><button class="appts-button appts-standard-button" onclick="ApptGoToApptStore()">Store selection</button></span><br/><br/>'+
                    '<span><button class="appts-button appts-standard-button" onclick="ApptGoToEquipment()">Equipment Selection</button></span>';
    
    var pathname = window.location.pathname;
    jQuery.get(localAccess.adminAjaxURL,{
        'action'    : 'tfgg_api_get_equip_type_appt_slots',
        'dataType'  : 'json',
        'data'      : {store_code: storecode, equip_type: equipmenttype, appt_date: apptdate, appt_len: apptlength},
		'pathname'  : pathname
    },function(data){
        var returnData = jQuery.parseJSON(data);
        
        if(returnData["results"]==='success'){
           var i=0; 
           var earliestDate = returnData["earlistApptDate"].split('-');
           var earliestTime = returnData["earlistApptTime"].split(':');

           var earliestAppt = (new Date(earliestDate[0], earliestDate[1]-1, earliestDate[2], earliestTime[0], earliestTime[1], earliestTime[2]));
           jQuery.each(returnData["availableSlots"], function(key,details){
                i++;
                var aTime = details['start_time'].split(':');//2019-10-08 CB V1.0.1.1
                //if(new Date(details['start_time'],apptdate) < new Date(returnData["earlistAppt"])){
                    
                if(new Date(aDate[0], aDate[1]-1, aDate[2], aTime[0], aTime[1], aTime[2]) < earliestAppt){
                        //month is adate[1]-1 because months are 0 index for js                        
                    return;//continue to next iteration
                }
                if (ApptDoesNotConflict(details['start_time'],apptdate)){
                    var pnlName="'timePnl"+i+"'";
                
                    var pnl='<div class="appts-timeslot-selector timeSlotPnl" name="timePnl'+i+'" id="timePnl'+i+'" onclick="highlightTimeSlot('+pnlName+')" style="cursor: pointer;" '+
                    'data-roomnumber="'+details['roomnumber']+'" data-starttime="'+details['start_time']+'"> '+ //DO NOT FORMAT THIS TIME!!!
                                '<span class="appts-timeslot">Start Time: '+FormatTimeToUK(details['start_time'])+'</span><br/> '+
                                '<span class="appts-timeslot">End Time: '+FormatTimeToUK(details['end_time'])+'</span> '+
                            '</div>';
                    
                    jQuery('#appts-timeslot-container').append(pnl);
                }
                
           });//jquery.each
            
        }

        if(jQuery('#appts-timeslot-container').is(':empty') ) {
            jQuery('#appts-timeslot-container').append(emptypnl);
        }

        changeActiveContentPanel('appts_timeslots');
    });
}

function ApptDoesNotConflict(start_time, appt_date){
    var bResult = true;
    var validate = new Date(appt_date+' '+start_time);
    //loop through each of the existing appts and verify if
    //it does not fall within +/- 24hs
    jQuery('.excludeAptsDateTime').each(function(){
        //var existing = new Date(jQuery(this).data('apptdate')+' '+jQuery(this).data('appttime'));
        var existingDate = jQuery(this).data('apptdate').split('-');
        var existingTime = jQuery(this).data('appttime').split(':');
        var existing = new Date(existingDate[0], existingDate[1]+1, existingDate[2], existingTime[0], existingTime[1], existingTime[2]);
        var lowerBound = new Date(existing.setDate(existing.getDate()-1));//this decrements the existing by 1 day
        var upperBound = new Date(existing.setDate(existing.getDate()+2));//so we need to increment by 2 here
        if((validate>=lowerBound)&&(validate<=upperBound)){
            //an existing appt falls withing this time slot period
            bResult=false;
        }
    });

    return bResult;
}

function ApptGoToApptDate(){
    changeActiveContentPanel('appts_date_select');    
}

function ApptGoToApptStore(){
    changeActiveContentPanel('appts_store_select');    
}

function ApptGoToStoreAndDate(){
    changeActiveContentPanel('appts_date_store');
}

function ApptGoToEquipment(){
    ApptStoreSelect();
}

function highlightTimeSlot(pnl){
    jQuery('.appts-timeslot-selector-selected').removeClass('appts-timeslot-selector-selected');
    jQuery('#' + pnl).addClass('appts-timeslot-selector-selected');
    
    jQuery('#tfgg_appt_time_text').text(FormatTimeToUK(jQuery('#'+pnl).data('starttime')));
    jQuery('#tfgg_appt_success_time').text(FormatTimeToUK(jQuery('#'+pnl).data('starttime')));
    jQuery('#tfgg_appt_start_time').val(jQuery('#'+pnl).data('starttime'));
    jQuery('#tfgg_appt_equip_room').val(jQuery('#'+pnl).data('roomnumber'));
    TimeSlotSelect();
}

function ApptEquipSelect() {
    changeActiveContentPanel('appts_loading');
    LoadEquipTimeSlots();
}

function TimeSlotSelect() {
    changeActiveContentPanel('appts_confirm');
}

function bookAppt(){
    //jQuery('#appts-booking-results-container').empty();
    changeActiveContentPanel('appts_loading');//2019-07-30 - showing the loader as per request
    jQuery('#appts-booking-results-container-bottom').empty();
    
    var clientnumber=jQuery('#tfgg_appt_client').val();
    var storecode=jQuery('#tfgg_appt_storecode').val();
    var storelocation=jQuery('#tfgg_appt_storeloc').val();

    var apptdate=jQuery('#tfgg_appt_date').val();


    var appttime=jQuery('#tfgg_appt_start_time').val();
    var apptequip=jQuery('#tfgg_appt_equip_type').val();
    var apptroom=jQuery('#tfgg_appt_equip_room').val();
    var apptmins=jQuery('#tfgg_appt_equip_type_multiplier').val()*jQuery('#tfgg_appt_len').val();
    
    //TFGG only uses walk-in tanning appts at present
    var apptservice='W';
    var appttype='1';
    
    //these are not presently in use by TFGG
    var apptserviceID='000000000';//this is for a walk-in
    var apptwithemp='';
    var apptnotes='';
    
    var pathname = window.location.pathname;
    
    jQuery.post(localAccess.adminAjaxURL,{
        'action'    : 'tfgg_api_schedule_appt',
        'dataType'  : 'json',
        'data'      : {client_number:clientnumber, 
            store_code:storecode,
            appt_date:apptdate,
            appt_time:appttime,
            appt_equip:apptequip,
            appt_notes:apptnotes,
            appt_room:apptroom,
            appt_type:appttype,
            appt_mins:apptmins,
            appt_service:apptservice,
            appt_serviceID:apptserviceID,
            appt_with_emp:apptwithemp},
		'pathname'  : pathname    
    },function(data){
        var returnData = jQuery.parseJSON(data);
        //console.log(returnData);
        /*var pnl='';
        
        if(returnData["results"]==='success'){
            pnl='<div id="alertpnl_tfgg_appt_successful_booking" style="display:block;" class="alert alert-success">'+
                '<span id="alertpnl_tfgg_appt_successful_booking_message">Your appointment has been successfully booked at the '+
                storelocation+' location for '+appttime+' on '+apptdate+'.<br/>'+
                'Please arrive at least 15 minutes before your appointment time to ensure prompt service.<br/><br/>'+
                'We look forward to servicing you</span>'+
            '</div>'        
        }else{
            pnl='<div id="alertpnl_tfgg_appt_error_booking" style="display:block;" class="alert alert-warning">'+
                '<span id="alertpnl_tfgg_appt_error_booking_message">There was an error booking your appointment.<br/>'+
                'Please contact customer support at <a href="mailto:'+returnData["cust_support"]+'">'+returnData["cust_support"]+'</a> to book your selected appointment or try again</span>'+
            '</div>'    
        }
        
        jQuery('#appts-booking-results-container').append(pnl);*/

        changeActiveContentPanel('appts_booking_results');//this has to be here on successful responses will not show

        if(returnData["results"]==='success'){
            //console.log('success');
            var countdown = '<span style="font-size:x-small;">Redirecting in: <span id="redirectcountdown">31</span> seconds</span>';
            jQuery('#appt-booking-success').append(countdown);
            jQuery('#appt-booking-success').show(); 

            timer(15000,
            function(timeleft){
                jQuery('#redirectcountdown').html(timeleft);
            },
            function(){
                window.location.href=localAccess.acctOverview;
            }); 
        }else{            
            //console.log('fail');
            jQuery('#appt-booking-fail').show();
        }
    });
}

function showRegTAndC(){
    jQuery('#tfgg_cp_reg_tandc_dialog').dialog('open');
}

function closeRegTAndC(){
    jQuery('#tfgg_cp_reg_tandc_dialog').dialog('close');
}

function myConfirm(dialogText, okFunc, cancelFunc, dialogTitle) {
    jQuery('<div style="padding: 10px; max-width: 500px; word-wrap: break-word;">' + dialogText + '</div>').dialog({
      draggable: false,
      modal: true,
      resizable: false,
      width: 'auto',
      title: dialogTitle || 'Confirm',
      minHeight: 75,
      closeOnEscape: false,
      open: function(event, ui) {
        jQuery(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
      },
      buttons: {
        OK: function () {
          if (typeof (okFunc) == 'function') {
            setTimeout(okFunc, 50);
          }
          jQuery(this).dialog('destroy');
        },
        Cancel: function () {
          if (typeof (cancelFunc) == 'function') {
            setTimeout(cancelFunc, 50);
          }
          jQuery(this).dialog('destroy');
        }
      }
    });
  }

function recaptchaCallback(){
    jQuery('#registrationSubmitButton').removeAttr('disabled');
}

function getAgeYears(dateVal) {
    var
        birthday = new Date(dateVal),
        today = new Date(),
        ageInMilliseconds = new Date(today - birthday),
        years = ageInMilliseconds / (24 * 60 * 60 * 1000 * 365.25 ),
        months = 12 * (years % 1),
        days = Math.floor(30 * (months % 1));
    return Math.floor(years);

}

function markTandCchecked(){
    jQuery('#tfgg_cp_user_tandc_agree').prop('checked')=true;
}

function markMarketingChecked(){
    jQuery('#tfgg_cp_marketing').prop('checked')=true;
}

function getApptStores(dateSelected){
    changeActiveContentPanel('appts_loading');
    //clear these elements
    jQuery('#tfgg_store_filter').val('');
    jQuery('#tfgg_appt_store_panels').html('');
    jQuery('#tfgg_storeselect_warning').css('display','none');

    //get the 'day' selected
    dateSelected = new Date(dateSelected);
    dateSelected = dateSelected.getUTCDay();
    //console.log(dateSelected);
    var pathname = window.location.pathname;
    
    jQuery.get(localAccess.adminAjaxURL,{
        'action'    : 'tfgg_api_get_stores_for_appts',
        'dataType'  : 'json',
        'data'      : {apptDay: dateSelected, store_code:"0000000001"},
		'pathname'  : pathname
    },function(data){
        
        var returnData = jQuery.parseJSON(data);
        
        if(returnData["results"]=='success'){
            //console.log(returnData["equipment"]);
            jQuery.each(returnData["stores"], function(key,details){
                var pnlName="'appt_store_panel_"+details['store_id']+"'";
                
                var pnl='<div class="appts-selector appts-store-selector" '+
                'id="appt_store_panel_' + details['store_id'] + '" '+
                'data-storelocation="' +details['store_loc'].replace(/ \([\s\S]*?\)/g, '') + '" '+
                'data-storecode="'+details['store_id']+'" '+
                'data-apptlength="'+details['apptlength']+'" ';

                //pull the start and end time based on the day selected
                switch(dateSelected){
                    case 0:
                        pnl=pnl+'data-apptstarttime="' +details['SunStart']+'" '+
                        'data-apptendtime="'+details['SunEnd']+'" ';
                        break;
                    case 1:
                        pnl=pnl+'data-apptstarttime="' +details['MonStart']+'" '+
                        'data-apptendtime="'+details['MonEnd']+'" ';
                        break;
                    case 2:
                        pnl=pnl+'data-apptstarttime="' +details['TuesStart']+'" '+
                        'data-apptendtime="'+details['TuesEnd']+'" ';
                        break;
                    case 3:
                        pnl=pnl+'data-apptstarttime="' +details['WedStart']+'" '+
                        'data-apptendtime="'+details['WedEnd']+'" ';
                        break;
                    case 4:
                        pnl=pnl+'data-apptstarttime="' +details['ThursStart']+'" '+
                        'data-apptendtime="'+details['ThursEnd']+'" ';
                        break;
                    case 5:
                        pnl=pnl+'data-apptstarttime="' +details['FriStart']+'" '+
                        'data-apptendtime="'+details['FriEnd']+'" ';
                        break;
                    case 6:
                        pnl=pnl+'data-apptstarttime="' +details['SatStart']+'" '+
                        'data-apptendtime="'+details['SatEnd']+'" ';
                        break;
                }
                pnl=pnl+'onclick="selectStore('+pnlName+');" > ';

                //fill in the rest of the data!
                pnl=pnl+'<span class="appts-store-name"><strong>'+details['store_loc'].replace(/ \([\s\S]*?\)/g, '')+'</strong></span>';
                pnl=pnl+'<br/>';
                //address
                pnl=pnl+'<span class="appts-store-address">';
                pnl=pnl+details['address1'].substring(0,35)+'<br/>';
                pnl=pnl+details['address2'].substring(0,35)+'<br/>';
                pnl=pnl+details['city'];
                if(details['zip']!=''){ pnl=pnl+', '}
                pnl=pnl+details['zip']+'<br/>';//this br needs to be here in case the zip is empty and the line isn't rendered
                pnl=pnl+'</span>';//end span for appts-store-address
                pnl=pnl+'</div>';
                   
                jQuery('#tfgg_appt_store_panels').append(pnl);
            });
            
        }else{
            jQuery('#tfgg_storeselect_warning').css('display','block');
        }

    changeActiveContentPanel('appts_store_select');
    });
}

function ValidateLoginData(){
    var bResult=true;
    
    if(jQuery('#tfgg_cp_user_login').val()===''){
        jQuery('#login_alert_email').css('display','block');
	    jQuery('#login_alert_email').html('Please enter your login ID');
	    bResult = false;
    }

    if(jQuery('#tfgg_cp_user_pass').val()===''){
        jQuery('#login_alert_password').css('display','block');
	    jQuery('#login_alert_password').html('Please enter your password');
	    bResult = false;   
    }
    
    return bResult;
}

function ValidateLoginResetData(){
    var bResult=true;
    
    if(jQuery('#tfgg_cp_user_login').val()===''){
        jQuery('#login_alert_email').css('display','block');
	    jQuery('#login_alert_email').html('Please enter your login ID');
	    bResult = false;
    }
    return bResult;
}

function portalLogin(){
    ResetRegValidation();//we can use this as we are using the same class
    event.preventDefault();

    if(ValidateLoginData()){
        jQuery('#tfgg_cp_api_login').submit();
    }
}

function portalLoginReset(){
    ResetRegValidation();//we can use this as we are using the same class
    event.preventDefault();

    if(ValidateLoginResetData()){
        jQuery('#tfgg_cp_api_login_reset').submit();
    }    
}

function endPortalSession(){
    event.preventDefault();
    jQuery.get(localAccess.adminAjaxURL,{
        'action'    : 'tfgg_cp_portal_logout',
        'dataType'  : 'json',
		'pathname'  : window.location.pathname
    },function(data){
        var obj = jQuery.parseJSON(data);
        window.location.replace(obj["logout"]);
    });
    
}

function instoreTandCDialog(){
    genModalDialog('instore_tandc_dialog');
}

function instoreMarketingDialog(){
    genModalDialog('instore_marketing_dialog');
}

function secretClick(){
    jQuery('#tfgg_cp_store').prop('disabled',false);
}

function instoreSKinTypeInfoDialog(){
    genModalDialog('instore_skin_type_info_dialog');
}

function genModalDialog($modalID){
    var dialog=jQuery('#'+$modalID).dialog({ autoOpen: false,
        height: (jQuery(window).height()*0.75),
        width: (jQuery(window).width()*0.8),
        modal: true,
        open:function(){
            jQuery('#main-header').css('z-index',0);   
            jQuery('#'+$modalID).css('z-index','100000 !important');
        }
      });    

    dialog.dialog('open');
}