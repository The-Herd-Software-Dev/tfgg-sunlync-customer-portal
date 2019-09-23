<?php

    /*Backing these functions out
    Basically, trying to get two hashing algorithms to work side-by-side in WP is
    not going to happen - too many 'core' functions need to be rewritten to accomdate.
    Any kind of WP update would overwrite these changes
    
    function tfgg_wp_hash_password($password){
        return StrToUpper(MD5(StrToUpper($password)));
    }
    
    function tfgg_wp_check_password($password, $hash){
        if (tfgg_wp_hash_password($password)==$hash){
            return true;
        }else{
            return false;
        }
    }
    
    function tfgg_wp_signon($creds, $secure_cookie=''){
        //attempting to create custom signon for tfgg-sunlync customers
        //that will NOT invalidate the backend/admin login (wp_signon())
        
        //we do not need wp_authenticate since we already authenticed the user BEFORE this method
        
        if ( '' === $secure_cookie ) {
            $secure_cookie = is_ssl();
        }//'' === $secure_cookie
        
        $secure_cookie = apply_filters( 'secure_signon_cookie', $secure_cookie, $creds );
 
        global $auth_secure_cookie; // XXX ugly hack to pass this to wp_authenticate_cookie
        $auth_secure_cookie = $secure_cookie;
        
        global $auth_secure_cookie; // XXX ugly hack to pass this to wp_authenticate_cookie
        $auth_secure_cookie = $secure_cookie;
     
        add_filter( 'authenticate', 'wp_authenticate_cookie', 30, 3 );
        
        $user = tfgg_wp_authenticate( $creds['user_login'], $creds['user_password'] );
        var_dump($user);
        //$user = get_user_by('user_login',$creds['user_login']);
        
        wp_set_auth_cookie( $user->ID, $creds['remember'], $secure_cookie );
        
        do_action( 'wp_login', $user->user_login, $user );
        return $user;
        
    }//tfgg_wp_signon
    
    function tfgg_wp_authenticate( $username, $password ) {
        $username = sanitize_user( $username );
        $password = trim( $password );
        
        $user = apply_filters( 'authenticate', null, $username, $password );
        
        return $user;
        
    }*/
    
    function tfgg_cp_set_sunlync_client($user_id, $clientNumber){
        return add_user_meta($user_id, 'sunlync_client', $clientNumber, true);
    }
    
    function tfgg_cp_get_sunlync_client(){
       // return 'here';exit;
        if ( ! function_exists( 'wp_get_current_user' ) ) {
            return false;
        }
        
        $user = wp_get_current_user();
        
        if(!$user||$user->ID==0){
            return false;    
        }
        
        return get_user_meta($user->ID, 'sunlync_client',true);
    }
    
    function tfgg_cp_check_sunlync_meta($clientNumber){
        if(metadata_exists( 'user', $clientNumber, 'sunlync_client' )){
            return true;   
        }else{
            return false;
        }
    }
    
    function tfgg_cp_get_user_loginid(){
        if ( ! function_exists( 'wp_get_current_user' ) ) {
            return false;
        }
        
        $user = wp_get_current_user();
        
        if(!$user||$user->ID==0){
            return false;    
        }
        
        return $user->user_login;
    }
    
    add_action('init','tfgg_cp_get_sunlync_client');

    function tfgg_cp_redirect_after_login($existingUser=false){
        if(get_option('tfgg_scp_cplogin_page_success')==''){
			wp_redirect(home_url());exit;
		}else{
		    if($existingUser){
		        wp_redirect(get_option('tfgg_scp_cplogin_page_success').'?existingUser='.$existingUser);exit;    
		    }else{
			    wp_redirect(get_option('tfgg_scp_cplogin_page_success'));exit;
		    }
		}
    }

    function log_me($message) {
        if ( WP_DEBUG === true ) {
            ini_set( 'error_log', WP_CONTENT_DIR . '/debug.log' );
            if ( is_array($message) || is_object($message) ) {
                error_log( print_r($message, true) );
            } else {
                error_log( $message );
            }
        }
    }

    function tfgg_redirect_from_user_req_pages(){
        global $wp;
        log_me('requested page: '.$wp->request);
        $sunlyncUser = tfgg_cp_get_sunlync_client();
        if($sunlyncUser<>false){$sunlyncUser=true;}
        
        $acctOverview = get_option('tfgg_scp_acct_overview');
        $apptBooking = get_option('tfgg_scp_cpappt_page');
        $login = get_option('tfgg_scp_cplogin_page');
        $registration=get_option('tfgg_scp_cpnewuser_page');

        //I am purposely leaving this broken out to make management easier
        if($sunlyncUser){
            log_me('is a sunlync user');
            if(is_page(array($login, $registration))){
                log_me('page exists in array - redirecting to acctOverview');
                wp_redirect( $acctOverview ); 
                exit;
            }
        }else{
            log_me('not a sunlync user');
            if(is_page(array($acctOverview,$apptBooking))){
                log_me('page exists in array - redirecting to login');
                wp_redirect( $login ); 
                exit;
            }    
        }
        log_me('carrying on');
    }
    add_action( 'template_redirect', 'tfgg_redirect_from_user_req_pages' );
    
    function tfgg_get_api_url(){
        //we are not adding the class in this call since there are now multiple
        return get_option('tfgg_scp_api_protocol').'://'.get_option('tfgg_scp_api_url').':'.get_option('tfgg_scp_api_port').'/datasnap/rest/';    
    }
    
    function tfgg_get_api_version(){
        $url = tfgg_get_api_url();
        $url.='TSunLyncAPI/GenericGetAPIVersion';
        //echo $url;
        
        try{
            $data = tfgg_sunlync_execute_url($url);
            
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            exit(json_encode($result));
        }
        $result=array();
        $result["results"]="success";
        $result["api_version"]=$data[0]->result;
        exit(json_encode($result));
        
    }
    add_action( 'wp_ajax_tfgg_get_api_version', 'tfgg_get_api_version' );
    //add_action( 'wp_ajax_nopriv_tfgg_get_api_version', 'tfgg_get_api_version' );
    
    function tfgg_set_api_cancel_appointment(){
        $employeenumber = get_option('tfgg_scp_appt_update_employee');
        $apptID = $_POST['data']['appt_ID'];
        //exit(json_encode($apptID.':'.$employeenumber));
        
        $url = tfgg_get_api_url();
        $url.='TSunLyncAPI/CIPCancelAppt/sApptID/sUpdateEmp/sNotes/1';
        
        $url=str_replace('sApptID',$apptID,$url);
        $url=str_replace('sUpdateEmp',$employeenumber,$url);
        $url=str_replace('sNotes','',$url);
        
        try{
            $data = tfgg_sunlync_execute_url($url);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            exit(json_encode($result));
        }
        $result["results"]="success";
        exit(json_encode($result));
    }
    add_action( 'wp_ajax_tfgg_set_api_cancel_appointment', 'tfgg_set_api_cancel_appointment' );
    
    function tfgg_api_get_store_equipment(){
        $url = tfgg_get_api_url();
        $url.='TSunLyncAPI/CIPGetStoreEquipmentTypes/storecode/inAppts/1';
        
        $url=str_replace('storecode',$_GET['data']['store_code'],$url);
        $url=str_replace('inAppts','1',$url);
        
        try{
            $data = tfgg_sunlync_execute_url($url);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            exit(json_encode($result));
        }
        $result["results"]="success";
        $result["equipment"]=array_slice($data,1,-1);
        $result["picDir"]=get_option('tfgg_scp_appt_equip_dir');
        exit(json_encode($result));            
    }
    add_action('wp_ajax_tfgg_api_get_store_equipment','tfgg_api_get_store_equipment');
    
    function tfgg_api_get_client_demographics($clientNumber){
        //CIPClientDemographics/clientnumber/market
        if($clientNumber===''){
            return 0;
        }
        
        $url=tfgg_get_api_url().'TSunLyncAPI/CIPClientDemographics/'.$clientNumber;
        try{
            $data = tfgg_sunlync_execute_url($url);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            return json_encode($result);
        }
        
        if((array_key_exists('ERROR',$data[0]))||(array_key_exists('WARNING',$data[0]))){
			if(array_key_exists('ERROR',$data[0])){
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->ERROR);
			}else{
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->WARNING);
			}
			
			return json_encode($result);
		}else{
		       
            $result["results"]="success";
            $result["demographics"]=array_slice($data,1,-1);
            return json_encode($result);
		}
        
    }
    
    function tfgg_api_get_client_comm_pref($clientNumber){
        if($clientNumber===''){
            return 0;
        } 
        
        $url=tfgg_get_api_url().'TSunLyncAPI/CIPCommPref/'.$clientNumber;
        try{
            $data = tfgg_sunlync_execute_url($url);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            return json_encode($result);
        }
        
        if((array_key_exists('ERROR',$data[0]))||(array_key_exists('WARNING',$data[0]))){
			if(array_key_exists('ERROR',$data[0])){
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->ERROR);
			}else{
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->WARNING);
			}
			
			return json_encode($result);
		}else{
		       
            $result["results"]="success";
            $result["commPref"]=array_slice($data,1,-1);
            return json_encode($result);
		}
    }
    
    function tfgg_api_get_client_pkgs($clientNumber){
        if($clientNumber===''){
            return 0;
        } 
        
        $url=tfgg_get_api_url().'TSunLyncAPI/CIPClientPackageInfo/'.$clientNumber;
        try{
            $data = tfgg_sunlync_execute_url($url);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            return json_encode($result);
        }
        
        if((array_key_exists('ERROR',$data[0]))||(array_key_exists('WARNING',$data[0]))){
			if(array_key_exists('ERROR',$data[0])){
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->ERROR);
			}else{
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->WARNING);
			}
			
			return json_encode($result);
		}else{
		       
            $result["results"]="success";
            $result["clientPackages"]=array_slice($data,1,-1);
            return json_encode($result);
		}    
    }
    
    function tfgg_api_get_client_mems($clientNumber){
        if($clientNumber===''){
            return 0;
        } 
        
        $url=tfgg_get_api_url().'TSunLyncAPI/CIPClientMembershipInfo/'.$clientNumber;
        try{
            $data = tfgg_sunlync_execute_url($url);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            return json_encode($result);
        }
        
        if((array_key_exists('ERROR',$data[0]))||(array_key_exists('WARNING',$data[0]))){
			if(array_key_exists('ERROR',$data[0])){
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->ERROR);
			}else{
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->WARNING);
			}
			
			return json_encode($result);
		}else{
		       
            $result["results"]="success";
            $result["clientMemberships"]=array_slice($data,1,-1);
            return json_encode($result);
		}    
    }
    
    function tfgg_api_get_client_appointments($clientNumber){
        if($clientNumber===''){
            return 0;
        }
        
        $url=tfgg_get_api_url().'TSunLyncAPI/CIPGetClientAppts/sClientNumber/sDateStart/sDateEnd/';
        $url.='sEmpNo/sEquipType/sApptType/sStoreCode';
        
        $dateStart = date('Y-m-d');
        
        $url=str_replace('sClientNumber',$clientNumber,$url);
        $url=str_replace('sDateStart',$dateStart,$url);
        $url=str_replace('sDateEnd','',$url);
        $url=str_replace('sEmpNo','',$url);
        $url=str_replace('sEquipType','',$url);
        $url=str_replace('sApptType','',$url);
        $url=str_replace('sStoreCode','',$url);  
        
        try{
            $data = tfgg_sunlync_execute_url($url);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            return json_encode($result);
        }
        
        if((array_key_exists('ERROR',$data[0]))||(array_key_exists('WARNING',$data[0]))){
			if(array_key_exists('ERROR',$data[0])){
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->ERROR);
			}else{
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->WARNING);
			}
			
			return json_encode($result);
		}else{
		       
            $result["results"]="success";
            $result["appointments"]=array_slice($data,1,-1);
            return json_encode($result);
		}
        
    }
    
    function tfgg_sunlync_execute_url($url){
        //the version check does not get a market appended
        if(!strpos($url,'GenericGetAPIVersion')){
            $url.='/'.get_option('tfgg_scp_api_mrkt'); 
        }
        $ch = curl_init($url);
		$ch_options=array(
			CURLOPT_RETURNTRANSFER=> true,
			CURLOPT_USERPWD=>get_option('tfgg_scp_api_user').":".get_option('tfgg_scp_api_pass'),
			CURLOPT_HTTPHEADER=>array('Content-type: application/json')
		);

		curl_setopt_array($ch,$ch_options);
		$result=curl_exec($ch);
		curl_close($ch);
		
		if(($result===FALSE)||($result=='')){
			throw new Exception("ERROR: Invalid URL");
			exit;
		}
		
		$result=str_replace('{"result":[','',$result);
		$result=str_replace(']}','',$result);
        $result=json_decode($result);
		
		return $result;
    }
    
    function tfgg_api_get_stores(){
        $url=tfgg_get_api_url().'TSunLyncAPI/ApptGetStoreSettings/sStoreCode';
        
        $url=str_replace('sStoreCode','',$url);
        
        try{
            $data = tfgg_sunlync_execute_url($url);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            return json_encode($result);
        }
        
        if((array_key_exists('ERROR',$data[0]))||(array_key_exists('WARNING',$data[0]))){
			if(array_key_exists('ERROR',$data[0])){
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->ERROR);
			}else{
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->WARNING);
			}
			
			return json_encode($result);
		}else{
		       
            $result["results"]="success";
            $result["stores"]=array_slice($data,1,-1);
            return json_encode($result);
		}
        
    }
    add_action( 'wp_ajax_tfgg_api_get_stores', 'tfgg_api_get_stores' );
    
    function tfgg_api_get_equip_type_appt_slots(){
        $url=tfgg_get_api_url().'TSunLyncAPI/CIPGetApptSlotForEquipType/sStoreCode/sEquipType/sDate/nApptLen';
        
        $url=str_replace('sStoreCode',$_GET['data']['store_code'],$url);
        $url=str_replace('sEquipType',$_GET['data']['equip_type'],$url);
        $url=str_replace('sDate',$_GET['data']['appt_date'],$url);
        $url=str_replace('nApptLen',$_GET['data']['appt_len'],$url);
        
        try{
            $data = tfgg_sunlync_execute_url($url);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            exit(json_encode($result));
        }
        $result["results"]="success";
        
        $currentDateBuffer=new DateTime();
        $currentDateBuffer->add(new DateInterval('PT'.get_option('tfgg_scp_appointments_allowed_hrs').'H'));
        $result["availableSlots"]=array_slice($data,1,-1);
        $result["earlistAppt"]=$currentDateBuffer->format('Y-M-d H:i:s');
        exit(json_encode($result));
        
    }
    add_action('wp_ajax_tfgg_api_get_equip_type_appt_slots', 'tfgg_api_get_equip_type_appt_slots');
    
    function tfgg_api_sync_password($clientnumber, $password){
        //TFGG_SyncPassword(sclientNumber, sEmpNo, sNewPass
        $employeenumber = get_option('tfgg_scp_update_employee');
        
        $password = wp_hash_password($password);

        $url=tfgg_get_api_url().'TSunLyncAPI/TFGG_SyncPassword/sclientNumber/sEmpNo/sNewPass';
        
        $url=str_replace('sclientNumber',$clientnumber,$url);
        $url=str_replace('sEmpNo',$employeenumber,$url);
        $url=str_replace('sNewPass',$password,$url);

        try{
            $data = tfgg_sunlync_execute_url($url);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            return json_encode($result);
        }
        if((array_key_exists('ERROR',$data[0]))||(array_key_exists('WARNING',$data[0]))){
			if(array_key_exists('ERROR',$data[0])){
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->ERROR);
			}else{
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->WARNING);
			}
			
			return json_encode($result);
		}else{
		       
            $result["results"]="success";
            return json_encode($result);
		}

    }

    function tfgg_api_get_employees($onlyAppts=0){
        $url=tfgg_get_api_url().'TSunLyncAPI/CIPGetEmpList/sStatus/sEmpNo/sAvailableForAppts/'.
        'sStoreCode';
        
        $url=str_replace('sStatus','Active',$url);
        $url=str_replace('sEmpNo','',$url);
        $url=str_replace('sAvailableForAppts',$onlyAppts,$url);
        $url=str_replace('sStoreCode','',$url);
        
        try{
            $data = tfgg_sunlync_execute_url($url);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            return json_encode($result);
        }
        
        if((array_key_exists('ERROR',$data[0]))||(array_key_exists('WARNING',$data[0]))){
			if(array_key_exists('ERROR',$data[0])){
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->ERROR);
			}else{
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->WARNING);
			}
			
			return json_encode($result);
		}else{
		       
            $result["results"]="success";
            $result["employees"]=array_slice($data,1,-1);
            return json_encode($result);
		}    
    }

    function tfgg_api_get_skintypes(){
        $url=tfgg_get_api_url().'TSunLyncAPI/GenericGetSkinTypeList';
        
        try{
            $data = tfgg_sunlync_execute_url($url);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            return json_encode($result);
        }
        
        if((array_key_exists('ERROR',$data[0]))||(array_key_exists('WARNING',$data[0]))){
			if(array_key_exists('ERROR',$data[0])){
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->ERROR);
			}else{
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->WARNING);
			}
			
			return json_encode($result);
		}else{
		    
            $result["results"]="success";
            $result["skintypes"]=array_slice($data,0,-1);//2019-07-19 CB - fixed offset as no 'result' is returned
            return json_encode($result);
		}    
    }
    
    function tfgg_api_get_promos($custSpecific=1){
        $url=tfgg_get_api_url().'TSunLyncAPI/GetCustomerPromotions';
        
        try{
            $data = tfgg_sunlync_execute_url($url);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            return json_encode($result);
        }

        //var_dump($data);

        if(array_key_exists('error',$data)){
            $result=array("results"=>"FAIL",
            "response"=>$data->error);
            return json_encode($result);
        }

        if((array_key_exists('ERROR',$data[0]))||(array_key_exists('WARNING',$data[0]))){
			if(array_key_exists('ERROR',$data[0])){
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->ERROR);
			}else{
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->WARNING);
			}
			
			return json_encode($result);
		}else{
		       
            $result["results"]="success";
            $result["promotions"]=array_slice($data,1,-1);
            return json_encode($result);
		}    
    }
    
    function tfgg_api_check_user_exists($fname, $lname, $dob, $email){
        /*CIPClientSearch(sFirstName, sLastName, sClientNumber, sAddress, sDOB, sPhone,
        sEmail, sRange, mrktcode*/
        $url=tfgg_get_api_url().'TSunLyncAPI/CIPClientSearch/sFirstName/';
        $url.='sLastName/sClientNumber/sAddress/sDOB/sPhone/';
        $url.='sEmail/sRange';
        
        $url=str_replace('sFirstName',$fname,$url);
        $url=str_replace('sLastName',$lname,$url);
        $url=str_replace('sClientNumber','',$url);
        $url=str_replace('sAddress','',$url);
        $url=str_replace('sDOB',$dob,$url);
        $url=str_replace('sPhone','',$url);
        $url=str_replace('sEmail',$email,$url);
        $url=str_replace('sRange','',$url);
        
        try{
            $data = tfgg_sunlync_execute_url($url);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            return json_encode($result);
        }
        
        if((array_key_exists('ERROR',$data[0]))||(array_key_exists('WARNING',$data[0]))){
			if(array_key_exists('ERROR',$data[0])){
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->ERROR);
			}else{
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->WARNING);
			}
			
			return json_encode($result);
		}else{
		       
            $result["results"]="success";
            $result["clientnumber"]=$data[1]->client_id;
            return json_encode($result);
		}
    }
    
    function tfgg_api_insert_user($fname, $lname, $dob, $email){
        /*GenericInsertClient(sFirstName, sLastName, sMidInit,
        sAddress1, sAddress2, sCity, sState, sZip, sHomePhone, sWorkPhone, sWorkExt,
        sCellPhone, sScanNo, sLicenseNo, sEmail, sDob, sStoreCode,sHowHear, sEyeColor,
        sGender, sSkinType, sUserDefined1, sUserDefined2,
        sDoNotSolicit, sEmailCommPref, sCellVoiceCommPref, sCellTextCommPref, sHomePhoneCommPref,
        sWorkPhoneCommPref, sMailCommPref, sCustomCommPref,*/
        $url=tfgg_get_api_url().'TSunLyncAPI/GenericInsertClient/sFirstName/sLastName/sMidInit/';
        $url.='sAddress1/sAddress2/sCity/sState/sZip/sHomePhone/sWorkPhone/sWorkExt/';
        $url.='sCellPhone/sScanNo/sLicenseNo/sEmail/sDob/sStoreCode/sHowHear/sEyeColor/';
        $url.='sGender/sSkinType/sUserDefined1/sUserDefined2/';
        $url.='sDoNotSolicit/sEmailCommPref/sCellVoiceCommPref/sCellTextCommPref/sHomePhoneCommPref/';
        $url.='sWorkPhoneCommPref/sMailCommPref/sCustomCommPref';
        
        $url=str_replace('sEmailCommPref','0',$url);
        $url=str_replace('sCellVoiceCommPref','0',$url);
        $url=str_replace('sCellTextCommPref','0',$url);
        $url=str_replace('sHomePhoneCommPref','0',$url);
        $url=str_replace('sWorkPhoneCommPref','0',$url);
        $url=str_replace('sMailCommPref','0',$url);
        $url=str_replace('sCustomCommPref','0',$url);
        
        $url=str_replace('sFirstName',$fname,$url);
        $url=str_replace('sLastName',$lname,$url);
        $url=str_replace('sMidInit','',$url);
        $url=str_replace('sAddress1','',$url);
        $url=str_replace('sAddress2','',$url);
        $url=str_replace('sCity','',$url);
        $url=str_replace('sState','',$url);
        $url=str_replace('sZip','',$url);
        $url=str_replace('sHomePhone','',$url);
        $url=str_replace('sWorkPhone','',$url);
        $url=str_replace('sWorkExt','',$url);
        $url=str_replace('sCellPhone','',$url);
        $url=str_replace('sScanNo','',$url);
        $url=str_replace('sLicenseNo','',$url);
        $url=str_replace('sEmail',$email,$url);
        $url=str_replace('sDob',$dob,$url);
        $url=str_replace('sStoreCode','0000000000',$url);//we need an option for this
        $url=str_replace('sHowHear','Site Registration',$url);//we may need an option for this
        $url=str_replace('sEyeColor','',$url);
        $url=str_replace('sGender','',$url);
        $url=str_replace('sSkinType','',$url);
        $url=str_replace('sUserDefined1','',$url);
        $url=str_replace('sUserDefined2','',$url);
        $url=str_replace('sDoNotSolicit','0',$url);//defaulting to allow, but not specifying any
        
        try{
            $data = tfgg_sunlync_execute_url($url);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            return json_encode($result);
        }
        
        if((array_key_exists('ERROR',$data[0]))||(array_key_exists('WARNING',$data[0]))){
			if(array_key_exists('ERROR',$data[0])){
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->ERROR);
			}else{
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->WARNING);
			}
			
			return json_encode($result);
		}else{
		       
            $result["results"]="success";
            $result["clientnumber"]=$data[0]->client_number;
            return json_encode($result);
		}    
    }
    
    function tfgg_api_insert_user_proprietary($demographics,$commPref){
        /*TFGG_ClientPortalRegistration(sFirstName, sLastName, sMidInit, sAddress1, sAddress2,
            sCity, sSTate, sZip, sHomePhone, sWorkPhone, sWorkExt, sCellPhone, sScanNo, sLicenseNo, sEmail,
            sDob, sStoreCode, sHowHear, sEyeColor, sGender, sSkinType, sUserDefined1, sUserDefined2:String;
            sDoNotSolicit, sEmailCommPref, sCellVoiceCommPref, sCellTextCommPref, sHomePhoneCommPref,
            sWorkPhoneCommPref, sMailCommPref, sCustomCommPref:String; sPromoNumber, sPackageNumber,
            sPkgExpDate, sPkgUnits, sEmpNo*/
        
        $url=tfgg_get_api_url().'TSunLyncAPI/TFGG_ClientPortalRegistration/sFirstName/sLastName/sMidInit/sAddress1/sAddress2/'.
            'sCity/sState/sZip/sHomePhoneNumber/sWorkPhoneNumber/sWorkExt/sCellPhoneNumber/sScanNo/sLicenseNo/sEmailAddress/'.
            'sDob/sStoreCode/sHowHear/sEyeColor/sGender/sSkinType/sUserDefined1/sUserDefined2/'.
            'sDoNotSolicit/sEmailCommPref/sCellVoiceCommPref/sCellTextCommPref/sHomePhoneCommPref/'.
            'sWorkPhoneCommPref/sMailCommPref/sCustomCommPref/sPromoNumber/sPackageNumber/'.
            'sPkgExpDate/sPkgUnits/sEmpNo';
            
        $emp = get_option('tfgg_scp_update_employee');
        $promo = get_option('tfgg_scp_reg_promo');
        $pkg = '';
            
        $url=str_replace('sFirstName',$demographics['firstname'],$url);
        $url=str_replace('sLastName',$demographics['lastname'],$url);
        $url=str_replace('sMidInit',$demographics['midinit'],$url);
        $url=str_replace('sAddress1',$demographics['address']['street'],$url);
        $url=str_replace('sAddress2',$demographics['address']['street_2'],$url);
        $url=str_replace('sCity',$demographics['address']['city'],$url);
        $url=str_replace('sState',$demographics['address']['state'],$url);
        $url=str_replace('sZip',$demographics['address']['postcode'],$url);
        $url=str_replace('sEmailAddress',$demographics['email'],$url);
        $url=str_replace('sDob',$demographics['dob'],$url);
        
        $url=str_replace('sHomePhoneNumber',$demographics['numbers']['home'],$url);
        $url=str_replace('sWorkPhoneNumber',$demographics['numbers']['work'],$url);
        $url=str_replace('sWorkExt',$demographics['numbers']['work_ext'],$url);
        $url=str_replace('sCellPhoneNumber',$demographics['numbers']['cell'],$url);
        
        $url=str_replace('sScanNo','',$url);
        $url=str_replace('sLicenseNo','',$url);
        
        $url=str_replace('sStoreCode',$demographics['storecode'],$url);
        $url=str_replace('sHowHear',$demographics['howhear'],$url);
        $url=str_replace('sEyeColor',$demographics['eyecolor'],$url);
        $url=str_replace('sGender',$demographics['gender'],$url);
        $url=str_replace('sSkinType',$demographics['skintype'],$url);
        $url=str_replace('sUserDefined1','',$url);
        $url=str_replace('sUserDefined2','',$url);
        
        if($commPref['doNotSolicit']==='1'){
            $url=str_replace('sDoNotSolicit','1',$url);
            $url=str_replace('sEmailCommPref','0',$url);
            $url=str_replace('sCellVoiceCommPref','0',$url);
            $url=str_replace('sCellTextCommPref','0',$url);
            $url=str_replace('sHomePhoneCommPref','0',$url);
            $url=str_replace('sWorkPhoneCommPref','0',$url);
            $url=str_replace('sMailCommPref','0',$url);  
            $url=str_replace('sCustomCommPref','0',$url);     
        }else{
            $url=str_replace('sDoNotSolicit','0',$url);
            $url=str_replace('sEmailCommPref',$commPref['email'],$url);
            $url=str_replace('sCellVoiceCommPref','0',$url);
            $url=str_replace('sCellTextCommPref',$commPref['sms'],$url);
            $url=str_replace('sHomePhoneCommPref','0',$url);
            $url=str_replace('sWorkPhoneCommPref','0',$url);
            $url=str_replace('sMailCommPref','0',$url);  
            $url=str_replace('sCustomCommPref','0',$url);     
        }
        
        
        $url=str_replace('sPromoNumber',$promo,$url);
        $url=str_replace('sPackageNumber','0000000000',$url);
        $url=str_replace('sPkgExpDate','0000-00-00',$url);
        $url=str_replace('sPkgUnits','0',$url);
        $url=str_replace('sEmpNo',$emp,$url);
        
        try{
            $data = tfgg_sunlync_execute_url($url);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            return json_encode($result);
        }
        
        if((array_key_exists('ERROR',$data[0]))||(array_key_exists('WARNING',$data[0]))){
			if(array_key_exists('ERROR',$data[0])){
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->ERROR);
			}else{
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->WARNING);
			}
			
			return json_encode($result);
		}else{
		       
            $result["results"]="success";
            $result["clientnumber"]=$data[0]->client_number;
            return json_encode($result);
		} 
        
    }
    
    function tfgg_api_schedule_appt(){
        /*
        CIPInsertClientAppt(sClientNumber, sStoreCode, sDate, sTime, sEmpNo, sEquipType,
        sNotes, sRoomNo, sApptType, sApptLength, sServiceType, sServiceID, sWithEmp, sSendEmail:String; mrktCode:String
        */
        
        $url=tfgg_get_api_url().'TSunLyncAPI/CIPInsertClientAppt/sClientNumber/sStoreCode/sDate/sTime/sEmpNo/sEquipType/'.
        'sNotes/sRoomNo/sApptType/sApptLength/sServiceType/sServiceID/sWithEmp/sSendEmail';
        
        $url=str_replace('sClientNumber',$_POST['data']['client_number'],$url);
        $url=str_replace('sStoreCode',$_POST['data']['store_code'],$url);
        $url=str_replace('sDate',$_POST['data']['appt_date'],$url);
        $url=str_replace('sTime',$_POST['data']['appt_time'],$url);
        $url=str_replace('sEmpNo',get_option('tfgg_scp_appt_update_employee'),$url);
        $url=str_replace('sEquipType',$_POST['data']['appt_equip'],$url);
        $url=str_replace('sNotes',$_POST['data']['appt_notes'],$url);
        $url=str_replace('sRoomNo',$_POST['data']['appt_room'],$url);
        $url=str_replace('sApptType',$_POST['data']['appt_type'],$url);
        $url=str_replace('sApptLength',$_POST['data']['appt_mins'],$url);
        $url=str_replace('sServiceType',$_POST['data']['appt_service'],$url);
        $url=str_replace('sServiceID',$_POST['data']['appt_serviceID'],$url);
        $url=str_replace('sWithEmp',$_POST['data']['appt_with_emp'],$url);
        $url=str_replace('sSendEmail','1',$url);
        
        try{
            $data = tfgg_sunlync_execute_url($url);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage();
            $result["cust_support"]=get_option('tfgg_scp_customer_service_email');
            exit(json_encode($result));
        }
        if((array_key_exists('ERROR',$data[0]))||(array_key_exists('WARNING',$data[0]))){
			if(array_key_exists('ERROR',$data[0])){
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->ERROR);
			}else{
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->WARNING);
			}
			$result["cust_support"]=get_option('tfgg_scp_customer_service_email');
			
		}else{
            $result["results"]="success";
		}
		exit(json_encode($result));
    }
    add_action('wp_ajax_tfgg_api_schedule_appt', 'tfgg_api_schedule_appt');
    
    function tfgg_scp_register_user($demogrphics, $commPref){
        //$dob=date_create($dob);
        //$dob=date_format($dob,'Y-m-d');
        
        //check if the user exists
        //$userExists=tfgg_api_check_user_exists($fname,$lname,$dob,$email);
        $userExists=tfgg_api_check_user_exists($demogrphics['firstname'],
        $demogrphics['lastname'],$demogrphics['dob'],$demogrphics['email']);
        $userExistsDecoded = json_decode($userExists);
        
        if(strtoupper($userExistsDecoded->results)==='SUCCESS'){
            return $userExists;//user exists, return the clientnumber    
        }else{
            //register the user via the API
            
            $userReg=tfgg_api_insert_user_proprietary($demoghraphics, $commPref);
            return $userReg;//return whatever response is coming from the insert
        }
        
    }
    
    function tfgg_scp_update_demographics($clientnumber,$fname, $lname, $address, $address2,$city,$postcode,$email,$cellphone,$workphone){

        $employeenumber = get_option('tfgg_scp_update_employee');
        
        $url=tfgg_get_api_url().'TSunLyncAPI/CIPSetDemographics/sClientNumber/sUpdateEmp/sFirstName/';
        $url.='sLastName/sMidInit/sAddress1/sAddress2/sCity/sState/sZip/sCellPhone/';
        $url.='sHomePhone/sWorkPhone/sWorkExt/sEmail/sDOB/sGender';
        
        $url=str_replace('sClientNumber',$clientnumber,$url);
        $url=str_replace('sUpdateEmp',$employeenumber,$url);
        $url=str_replace('sFirstName',$fname,$url);
        $url=str_replace('sLastName',$lname,$url);
        $url=str_replace('sMidInit','',$url);
        $url=str_replace('sAddress1',$address,$url);
        $url=str_replace('sAddress2',$address2,$url);
        $url=str_replace('sCity',$city,$url);
        $url=str_replace('sState','',$url);
        $url=str_replace('sZip',$postcode,$url);
        $url=str_replace('sCellPhone',$cellphone,$url);
        $url=str_replace('sHomePhone','',$url);
        $url=str_replace('sWorkPhone',$workphone,$url);
        $url=str_replace('sWorkExt','',$url);
        $url=str_replace('sEmail',$email,$url);
        $url=str_replace('sDOB','',$url);
        $url=str_replace('sGender','',$url);
        
        try{
            $data = tfgg_sunlync_execute_url($url);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            return json_encode($result);
        }
        if((array_key_exists('ERROR',$data[0]))||(array_key_exists('WARNING',$data[0]))){
			if(array_key_exists('ERROR',$data[0])){
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->ERROR);
			}else{
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->WARNING);
			}
			
			return json_encode($result);
		}else{
		       
            $result["results"]="success";
            
            $user = wp_get_current_user();
            $userData=array();
            if($fname<>''){
                if($fname<>$user->user_firstname){
                    $userData["user_firstname"]=$fname;
                }
            }
            if($lname<>''){
                if($lname<>$user->user_lastname){
                    $userData["user_lastname"]=$lname;
                }    
            }
            if($email<>''){//this is the most important one
                if($email<>$user->user_email){
                    $userData["user_email"]=$email;
                }       
            }
            
            if(sizeof($userData)>0){
                $userData["ID"]=$user->id;
                wp_update_user($userData);
            }
            
            return json_encode($result);
		}
		
    }
    
    function tfgg_scp_update_comm_pref($clientnumber, $doNotSolicit,$sms, $email, $mail, $voice){
        /*CIPSetCommPref(sClientNumber, sUpdateEmp, sDoNotSolicit, sEmail, 
        sCellT, sCellV, sHomeV, sWorkV, sMail*/
        
        $employeenumber = get_option('tfgg_scp_update_employee');
        
        $url=tfgg_get_api_url().'TSunLyncAPI/CIPSetCommPref/sClientNumber/sUpdateEmp/';
        $url.='sDoNotSolicit/sEmail/sCellT/sCellV/sHomeV/sWorkV/sMail';
        
        $url=str_replace('sClientNumber',$clientnumber,$url);
        $url=str_replace('sUpdateEmp',$employeenumber,$url);
        $url=str_replace('sDoNotSolicit',$doNotSolicit,$url);
        $url=str_replace('sEmail',$email,$url);
        $url=str_replace('sCellT',$sms,$url);
        $url=str_replace('sCellV',$voice,$url);
        $url=str_replace('sHomeV',$voice,$url);
        $url=str_replace('sWorkV','0',$url);
        
        try{
            $data = tfgg_sunlync_execute_url($url);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            return json_encode($result);
        }
        if((array_key_exists('ERROR',$data[0]))||(array_key_exists('WARNING',$data[0]))){
			if(array_key_exists('ERROR',$data[0])){
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->ERROR);
			}else{
				$result=array("results"=>"FAIL",
					"response"=>$data[0]->WARNING);
			}
			
			return json_encode($result);
		}else{
		       
            $result["results"]="success";
            return json_encode($result);
		}
    } 
    
    function tfgg_scp_can_appt_be_cancelled($apptDate, $apptTime){
        if (get_option('tfgg_scp_appointments_allow_cancel')!=1){
            return false;
        }
        
        //$apptDateTime = DateTime::createFromFormat('m/d/Y H:i:s',$apptDate.' '.$apptTime);
        $apptDate = tfgg_format_date_to_ymd($apptDate);
        $apptDateTime = new DateTime($apptDate.' '.$apptTime);
        $apptDateTime->sub(new DateInterval('PT'.get_option('tfgg_scp_appointments_cancel_allowed_hrs').'H'));
        $currentTime=new DateTime();
        
        if($apptDateTime<=$currentTime){
            return false;
        }
        
        return true;
        
    }
    
    function tfgg_format_number_for_display($number){

        switch (substr($number,0,2)){
            case '02':
                $number=substr($number,0,3)." ".substr($number,3,4)." ".substr($number,8,4);
                break;
            case '07':
                $number=substr($number,0,5)." ".substr($number,5,6);
                break;
            default:
                $number=substr($number,0,4)." ".substr($number,4,12);
                break;
        }
    
        return $number;
    }

    function tfgg_format_date_for_display($date){
        //$date='07/27/2019';
        $date = new DateTime(tfgg_format_date_to_ymd($date));        
        return $date->format('j-n-Y');
    }

    function tfgg_format_time_for_display($time){
        $time = new DateTime($time);
        return $time->format('g:i A');
    }

    function tfgg_format_date_to_ymd($date){
        //USE THIS FUNCTION CONVERT D/M/Y TO Y/M/D
        $date = str_replace('/', '-', $date); //this line is to ensure UK dates parse correctly
        $date = new DateTime($date);
        return $date->format('Y-m-d');   
    }
    
    /*function tfgg_user_menu(){
        $user = wp_get_current_user();
        $allowed_roles = array('editor', 'administrator', 'author');
        if(!array_intersect($allowed_roles, $user->roles ) ) { 
            
            
                
        }
        
    }
    
    function tfgg_modify_non_admin_menu(){
        
        add_filter( 'wp_nav_menu_items', 'tfgg_user_menu', 10, 2 );
        
        
    }
    
    if ( in_array( 'tfgg-sunlync-customer-portal/tfgg-sunlync-customer-portal.php', get_option( 'active_plugins' ) ) ) {
    	add_action('wp_before_admin_bar_render', 'tfgg_modify_non_admin_menu', 0);
    }
    
    
    add_action('wp_logout','tfgg_logout_redirect');

    function tfgg_logout_redirect(){
      wp_redirect( site_url() );//possible optional setting
      exit();
    }*/
    
    /*if ( ! current_user_can( 'manage_options' ) ) {
        show_admin_bar( false );
    }*/
    
    add_filter('show_admin_bar', '__return_false');//prevents the admin bar from showing after tfgg_sunlync user logs in
    add_action('admin_init', 'blockusers_init');
    function blockusers_init() {
        //if the user is logged in and is not an admin, do not give them access to wp-admin
        $file = basename($_SERVER['PHP_SELF']);
        if (is_user_logged_in() && is_admin() && !current_user_can( 'administrator' ) &&
         ($file != 'admin-ajax.php')){
            wp_redirect(get_option('tfgg_scp_cplogin_page_success'));
            exit;
        }
    }
    
    add_filter('wp_nav_menu_items', 'tfgg_add_loginout_link', 10, 2 );
    function tfgg_add_loginout_link($items, $args){
        //add a logout link to the small menu bar (secondary-menu) at the top of the screen
        if(is_user_logged_in() && $args->theme_location=='secondary-menu'){
          $items .='<li><a href="'. wp_logout_url() .'">Log Out</a></li>';  
        }
        return $items;
    }
    
    add_filter('wp_nav_menu_items', 'tfgg_add_acct_overview_link', 9, 2 );
    function tfgg_add_acct_overview_link($items, $args){
        //add the account overview link to the nav bar
        $sunlyncuser = tfgg_cp_get_sunlync_client();
        if(is_user_logged_in() && $sunlyncuser && $args->theme_location=='secondary-menu'){
          $items .='<li><a href="'. get_option('tfgg_scp_acct_overview') .'">Account Overview</a></li>';  
        }
        return $items;
    }
    
    add_action('wp_logout','tfgg_auto_redirect_after_logout');
    function tfgg_auto_redirect_after_logout(){
        //self-explanatory - redirect to the home page after logout
        wp_redirect( home_url() );    
        exit();
    }

    remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );
    
?>