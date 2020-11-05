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
    
    /*function tfgg_cp_set_sunlync_client($user_id, $clientNumber){
        return add_user_meta($user_id, 'sunlync_client', $clientNumber, true);
    }*/

    function tfgg_cp_hash_password($password){
        return StrToUpper(MD5(StrToUpper($password)));
    }

    function tfgg_cp_set_sunlync_client($clientnumber){
        $_SESSION['sunlync_client'] = $clientnumber;
    }

    function tfgg_cp_unset_sunlync_client(){
        unset($_SESSION['sunlync_client']);
        unset($_SESSION['sunlync_firstname']);
        unset($_SESSION['sunlync_lastname']);
    }

    function tfgg_cp_portal_logout(){
        tfgg_cp_unset_sunlync_client();

        if(isset($_SESSION['tfgg_scp_cartid'])){
            unset($_SESSION['tfgg_scp_cartid']);
        }
        if(isset($_SESSION['tfgg_scp_cart_qty'])){
            unset($_SESSION['tfgg_scp_cart_qty']);
        }
        if(isset($_SESSION['sageMerchantSession'])){
            unset($_SESSION['sageMerchantSession']);
        }
        if(isset($_SESSION['sageMerchantSessionExp'])){
            unset($_SESSION['sageMerchantSessionExp']);
        }
        if(isset($_SESSION['clientHomeStore'])){
            unset($_SESSION['clientHomeStore']);
        }
        if(isset($_SESSION['tfgg_scp_cart_store'])){
            unset($_SESSION['tfgg_scp_cart_store']);
        }
        if(isset($_SESSION['tfgg_cp_cart_warning'])){
            unset($_SESSION['tfgg_cp_cart_warning']);
        }

        $result["logout"]=site_url();//possible configurable option
        exit(json_encode($result));
    }
    add_action( 'wp_ajax_tfgg_cp_portal_logout', 'tfgg_cp_portal_logout' );
    add_action( 'wp_ajax_nopriv_tfgg_cp_portal_logout', 'tfgg_cp_portal_logout' );

    function tfgg_cp_get_sunlync_client(){
        /* 2019-10-12 CB V1.1.1.1 - deprecated code
        if ( ! function_exists( 'wp_get_current_user' ) ) {
            return false;
        }
        
        $user = wp_get_current_user();
        
        if(!$user||$user->ID==0){
            return false;    
        }
        
        return get_user_meta($user->ID, 'sunlync_client',true);*/
        if(array_key_exists('sunlync_client',$_SESSION)){
            return $_SESSION['sunlync_client'];
        }else{
            return false;
        }

    }

    function tfgg_is_sunlync_user_logged_in(){
        if(!tfgg_cp_get_sunlync_client()){
            return false;
        }else{
            return true;
        }    
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

    function tfgg_cp_redirect_after_login($existingUser=false, $redirectToCart=false){
        if(get_option('tfgg_scp_cplogin_page_success')==''){
			wp_redirect(home_url());exit;
		}else{
		    if($existingUser){
		        wp_redirect(get_option('tfgg_scp_cplogin_page_success').'?existingUser='.$existingUser);exit;    
		    }else{
                if($redirectToCart){
                    wp_redirect(get_option('tfgg_scp_cart_slug'));exit;
                }else{
                    wp_redirect(get_option('tfgg_scp_cplogin_page_success'));exit;
                }
		    }
		}
    }

    function tfgg_cp_redirect_after_registration(){
        if(get_option('tfgg_scp_cpnewuser_success_page')==''){
            wp_redirect(get_option('tfgg_scp_cplogin_page_success'));exit;
        }else{
            wp_redirect(get_option('tfgg_scp_cpnewuser_success_page'));exit;
        }
    }

    function tfgg_cp_get_redirect_after_login(){
        return get_option('tfgg_scp_cplogin_page_success');
    }
    add_action( 'wp_ajax_tfgg_cp_get_redirect_after_login', 'tfgg_cp_get_redirect_after_login' );
    add_action( 'wp_ajax_nopriv_tfgg_cp_get_redirect_after_login', 'tfgg_cp_get_redirect_after_login' );

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

    function tfgg_scp_remove_slashes($str){
        $str = str_replace('/','',$str);
        $str = stripslashes($str);
        return $str;
    }

    function tfgg_redirect_from_user_req_pages(){
        global $wp;        
        log_me('requested page: '.$wp->request);
        $sunlyncUser = tfgg_cp_get_sunlync_client();
        if($sunlyncUser<>false){$sunlyncUser=true;}
        
        $siteurl=get_site_url().'/';

        $acctOverview = tfgg_scp_remove_slashes(get_option('tfgg_scp_acct_overview'));
        $apptBooking = tfgg_scp_remove_slashes(get_option('tfgg_scp_cpappt_page'));
        $login = tfgg_scp_remove_slashes(get_option('tfgg_scp_cplogin_page'));
        $registration = tfgg_scp_remove_slashes(get_option('tfgg_scp_cpnewuser_page'));
        $cart = tfgg_scp_remove_slashes(get_option('tfgg_scp_cart_slug'));
        $servicesSale = tfgg_scp_remove_slashes(get_option('tfgg_scp_services_sale_slug'));

        //2020-03-05 CB V1.2.5.7 - appended full site url

        //I am purposely leaving this broken out to make management easier
        if($sunlyncUser){
            log_me('is a sunlync user');
            if(is_page(array($login, $registration))){
                log_me('page exists in array - redirecting');

                //2020-03-02 CB V1.2.5.4 - redirect back to cart if customer came from cart to login page
                if((array_key_exists('sendBackToCart',$_SESSION))&&($_SESSION['sendBackToCart']===true)){
                    unset($_SESSION['sendBackToCart']);
                    wp_redirect( $siteurl.$servicesSale.'/' ); 
                }else{
                    wp_redirect( $siteurl.$acctOverview.'/' ); 
                }
                exit;
            }
        }else{
            log_me('not a sunlync user');
            if(is_page(array($acctOverview,$apptBooking,$cart,$servicesSale))){
                log_me('page exists in array - redirecting to login');       
                unset($_SESSION['sendBackToCart']);//just in case
                if($wp->request==$servicesSale){
                    $_SESSION['sendBackToCart']=true;
                }

                wp_redirect( $siteurl.$login.'/'); 
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
    add_action( 'wp_ajax_nopriv_tfgg_get_api_version', 'tfgg_get_api_version' );
    
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
    add_action( 'wp_ajax_nopriv_tfgg_set_api_cancel_appointment', 'tfgg_set_api_cancel_appointment' );
    
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
    add_action('wp_ajax_nopriv_tfgg_api_get_store_equipment','tfgg_api_get_store_equipment');
    
    function tfgg_cp_api_client_login($login, $pass){
        //2019-10-12 CB V1.1.1.1 - new method for logging in via api
        //CIPLogin(sLoginID, sLoginPass:String; mrktCode:String
       
        $url = tfgg_get_api_url();
        $url.='TSunLyncAPI/CIPLogin/sLoginID/sLoginPass';

        $url=str_replace('sLoginID',$login,$url);
        $url=str_replace('sLoginPass',tfgg_cp_hash_password($pass),$url);       

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
            $result["data"]=array_slice($data,1,-1);

            return json_encode($result);
		}
    }
    add_action('wp_ajax_tfgg_cp_api_client_login','tfgg_cp_api_client_login');
    add_action('wp_ajax_nopriv_tfgg_cp_api_client_login','tfgg_cp_api_client_login');

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

            $demo = $result['demographics'][0];
            //var_dump($demo);
            //default purchasing store if the cart is empty
            $_SESSION['clientHomeStore'] = $demo->homeStore;
            $_SESSION['sunlync_firstname'] = $demo->first_name;
            $_SESSION['sunlync_lastname'] = $demo->last_name;

            if(!isset($_SESSION['tfgg_scp_cartid'])){
                unset($_SESSION['tfgg_scp_cartid']);
            }
            
            tfgg_scp_get_cart_contents();//after login, verify cart contents

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
            //$result["clientPackages"]=array_slice($data,1,-1);
            $packages = array_slice($data,1,-1);
            $packageAlias = get_option('tfgg_scp_package_alias',array());
            $packageImg = get_option('tfgg_scp_package_img',array());
            $packageText = get_option('tfgg_scp_package_free_text',array());
            foreach($packages as &$packageDetails){
                if((is_array($packageAlias))&&(array_key_exists($packageDetails->package_number, $packageAlias))&&
                ($packageAlias[$packageDetails->package_number]<>'')){ 
                    $packageDetails->alias = $packageAlias[$packageDetails->package_number]; 
                }else{ 
                    $packageDetails->alias = $packageDetails->description;
                }

                if((is_array($packageImg))&&(array_key_exists($packageDetails->package_number, $packageImg))&&
                ($packageImg[$packageDetails->package_number]<>'')){ 
                    $packageDetails->img = $packageImg[$packageDetails->package_number]; 
                }else{ 
                    $packageDetails->img = '';
                }

                if((is_array($packageText))&&(array_key_exists($packageDetails->package_number, $packageText))&&
                ($packageText[$packageDetails->package_number]<>'')){ 
                    $packageDetails->freeText = $packageText[$packageDetails->package_number]; 
                }else{ 
                    $packageDetails->freeText = '';
                }
            }

            $result["clientPackages"]=$packages;
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
            //$result["clientMemberships"]=array_slice($data,1,-1);

            $memberships = array_slice($data,1,-1);
            $membershipAlias = get_option('tfgg_scp_membership_alias',array());
            $membershipImg = get_option('tfgg_scp_membership_img',array());
            $membershipText = get_option('tfgg_scp_membership_free_text',array());
            foreach($memberships as &$membershipDetails){
                if((is_array($membershipAlias))&&(array_key_exists($membershipDetails->membership_number, $membershipAlias))&&
                ($membershipAlias[$membershipDetails->membership_number]<>'')){ 
                    $membershipDetails->alias = $membershipAlias[$membershipDetails->membership_number]; 
                }else{ 
                    $membershipDetails->alias = $membershipDetails->description;
                }

                if((is_array($membershipImg))&&(array_key_exists($membershipDetails->membership_number, $membershipImg))&&
                ($membershipImg[$membershipDetails->membership_number]<>'')){ 
                    $membershipDetails->img = $membershipImg[$membershipDetails->membership_number]; 
                }else{ 
                    $membershipDetails->img = '';
                }

                if((is_array($membershipText))&&(array_key_exists($membershipDetails->membership_number, $membershipText))&&
                ($membershipText[$membershipDetails->membership_number]<>'')){ 
                    $membershipDetails->freeText = $membershipText[$membershipDetails->membership_number]; 
                }else{ 
                    $membershipDetails->freeText = '';
                }
            }

            $result["clientMemberships"]=$memberships;

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

    function tfgg_sunlync_cart_execute_url($url){
        // WE DO NOT STRIP ANYTHING FROM THIS RETURN DATA!!!!!
        $url.='/'.get_option('tfgg_scp_api_mrkt'); 
        
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
        
        $result=json_decode($result);
		return $result;
    }

    function tfgg_execute_api_request($method, $url, $data){
        if(!strpos($url,'GenericGetAPIVersion')){
            $url.='/'.get_option('tfgg_scp_api_mrkt'); 
        }
        
        $ch = curl_init($url);
        //set all common options first
        curl_setopt($ch, CURLOPT_USERPWD,get_option('tfgg_scp_api_user').":".get_option('tfgg_scp_api_pass'));                                                                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        switch (StrToUpper($method)){
            case "GET":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json'
                ));
                break;
            case "POST":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                    
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                    'Content-Type: application/json',                                                                                
                    'Content-Length: ' . strlen($data))                                                                       
                ); 
                break; 
            case "DELETE":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE"); 
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json'
                ));
                break;
            case "PUT":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); 
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json'
                ));
                break;                
        }//switch
        
        $result = curl_exec($ch);
        curl_close($ch); 
		
		if(($result===FALSE)||($result=='')){
			throw new Exception("ERROR: Invalid URL");
			exit;
        }

        //strip data from non-cart items
        if(strpos($url,'TSunLyncAPI')){
            $result=str_replace('{"result":[','',$result);
            $result=str_replace(']}','',$result);   
        }

        return json_decode($result);
    }

    function tfgg_sunlync_post_to_url($postData, $url){
        /*
        {
        "result": [
            [
            {
                "success": "item successfully added to cart"
            },
            {
                "cartID": "{848EF55B-714C-4751-AF62-0766BA722B5E}"
            },
            {
                "request_id": "95000701C9C5DCD83C4272752A10F1A263B86"
            }
            ]
        ]
        }

        */

        $ch = curl_init($url);                                                                      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($postData))                                                                       
        ); 
        curl_setopt($ch, CURLOPT_USERPWD,get_option('tfgg_scp_api_user').":".get_option('tfgg_scp_api_pass'));                                                                                                                  
                                                                                                                            
        $result = curl_exec($ch); 
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
        //2019-09-30 CB V1.0.0.6 - changed to use store demo appt info
        //$url=tfgg_get_api_url().'TSunLyncAPI/ApptGetStoreSettings/sStoreCode';
        $url= tfgg_get_api_url().'TSunLyncAPI/CIPGetStoreDemoApptInfo/sStoreCode/nInAppts';
        
        $url=str_replace('sStoreCode',tfgg_scp_get_stores_selected_for_api(),$url);
        $url=str_replace('nInAppts','',$url);
        
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
            usort($result["stores"],"tfgg_store_store_by_name");//2019-09-30 CB V1.0.0.6 - the new api call is not sorted alphabetically
            return json_encode($result);
		}
        
    }
    add_action( 'wp_ajax_tfgg_api_get_stores', 'tfgg_api_get_stores' );
    add_action( 'wp_ajax_nopriv_tfgg_api_get_stores', 'tfgg_api_get_stores' );

    function tfgg_api_get_reg_stores(){
        //2020-07-15 CB V1.2.6.5 - new
        $url= tfgg_get_api_url().'TSunLyncAPI/CIPGetStoreDemoApptInfo/sStoreCode/nInAppts';
        
        //$url=str_replace('sStoreCode',tfgg_scp_get_stores_selected_for_api(),$url);
        $url=str_replace('sStoreCode',tfgg_scp_get_stores_selected_for_reg(),$url);
        $url=str_replace('nInAppts','',$url);
        
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
            usort($result["stores"],"tfgg_store_store_by_name");//2019-09-30 CB V1.0.0.6 - the new api call is not sorted alphabetically
            return json_encode($result);
		}
        
    }
    add_action( 'wp_ajax_tfgg_api_get_reg_stores', 'tfgg_api_get_reg_stores' );
    add_action( 'wp_ajax_nopriv_tfgg_api_get_reg_stores', 'tfgg_api_get_reg_stores' );

    function tfgg_api_get_unfiltered_stores(){
        //2019-09-30 CB V1.0.0.6 - changed to use store demo appt info
        //$url=tfgg_get_api_url().'TSunLyncAPI/ApptGetStoreSettings/sStoreCode';
        $url= tfgg_get_api_url().'TSunLyncAPI/CIPGetStoreDemoApptInfo/sStoreCode/nInAppts';
        
        $url=str_replace('sStoreCode','',$url);
        $url=str_replace('nInAppts','',$url);
        
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
            usort($result["stores"],"tfgg_store_store_by_name");//2019-09-30 CB V1.0.0.6 - the new api call is not sorted alphabetically
            return json_encode($result);
		}    
    }

    function tfgg_scp_get_appt_stores_selected_for_api(){
        //2020-02-24 CB V1.2.4.20
        $stores = get_option('tfgg_scp_store_appts_selection');
        if($stores<>''){
            $storesSelected = join('","',$stores);   
            return '"'.$storesSelected.'"';    
        }else{
            return '';
        }
    }

    function tfgg_api_get_stores_for_appts(){
        //2019-10-01 CB V1.0.0.8 - new method added to retrieve stores
        //based on day selected from appt-control
        
        $apptDay = $_GET['data']['apptDay'];
        $url= tfgg_get_api_url().'TSunLyncAPI/TFGG_GetStoresAndHours/sStoreCode/nInAppts/nApptDay';

        $url=str_replace('sStoreCode',tfgg_scp_get_appt_stores_selected_for_api(),$url);
        $url=str_replace('nInAppts','1',$url);
        $url=str_replace('nApptDay',$apptDay,$url);

        try{
            $data = tfgg_sunlync_execute_url($url);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
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
			
			exit(json_encode($result));
		}else{
		       
            $result["results"]="success";
            $result["stores"]=array_slice($data,1,-1);
            usort($result["stores"],"tfgg_store_store_by_name");//2019-09-30 CB V1.0.0.6 - the new api call is not sorted alphabetically
            exit(json_encode($result));
		}

    }
    add_action( 'wp_ajax_tfgg_api_get_stores_for_appts', 'tfgg_api_get_stores_for_appts' );
    add_action( 'wp_ajax_nopriv_tfgg_api_get_stores_for_appts', 'tfgg_api_get_stores_for_appts' );

    function tfgg_store_store_by_name($a,$b){
        return strcmp($a->store_loc, $b->store_loc);
    }

    function tfgg_order_service_by_name($a,$b){
        return strcmp($a->description, $b->description);
    }

    function tfgg_order_service_by_price($a,$b){
        return $b->price > $a->price ? 1 : -1;
    }
    
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
        $currentDateBuffer->setTimezone(new DateTimeZone('Europe/London'));//2020-07-20 CB V1.2.6.7 - setting the timezone
        $currentDateBuffer->add(new DateInterval('PT'.get_option('tfgg_scp_appointments_allowed_hrs').'H'));
        $result["availableSlots"]=array_slice($data,1,-1);
        //2019-10-12 CB V1.0.1.5 - changed date formatting for increased Safari support
        $result["earlistAppt"]=$currentDateBuffer->format('Y-m-d H:i:s');
        $result["earlistApptDate"]=$currentDateBuffer->format('Y-m-d');
        $result["earlistApptTime"]=$currentDateBuffer->format('H:i:s');
        exit(json_encode($result));
        
    }
    add_action('wp_ajax_tfgg_api_get_equip_type_appt_slots', 'tfgg_api_get_equip_type_appt_slots');
    add_action('wp_ajax_nopriv_tfgg_api_get_equip_type_appt_slots', 'tfgg_api_get_equip_type_appt_slots');
    
    function tfgg_api_sync_password($clientnumber, $password){
        //TFGG_SyncPassword(sclientNumber, sEmpNo, sNewPass
        $employeenumber = get_option('tfgg_scp_update_employee');
        
        //$password = wp_hash_password($password);
        $password = tfgg_cp_hash_password($password);

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
    //add_action( 'password_reset', 'tfgg_api_sync_password', 10, 2 );

    function tfgg_scp_api_pass_reset_request($identifier){
        //CIPPassResetRequest/sClientIdentifier/sUseHash

        $url=tfgg_get_api_url().'TSunLyncAPI/CIPPassResetRequest/sClientIdentifier/sUseHash';
        
        $url=str_replace('sClientIdentifier',$identifier,$url);
        $url=str_replace('sUseHash','1',$url);//force use of SunLync hash

        try{
            $data = tfgg_sunlync_execute_url($url);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage();
            $result["cust_support"]=get_option('tfgg_scp_customer_service_email');
            return(json_encode($result));
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
		return(json_encode($result));
    }
    /*add_action('wp_ajax_tfgg_scp_api_pass_reset_request', 'tfgg_scp_api_pass_reset_request');
    add_action('wp_ajax_nopriv_tfgg_scp_api_pass_reset_request', 'tfgg_scp_api_pass_reset_request');*/

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
            //2019-09-30 CB V1.0.0.6 - forcing a specific order
            //$result["skintypes"]=array_slice($data,0,-1);//2019-07-19 CB - fixed offset as no 'result' is returned

            $skintypes = array_slice($data,0,-1);
            $returnTypes=array();
            array_push($returnTypes,$skintypes[0]);
            array_push($returnTypes,$skintypes[2]);
            array_push($returnTypes,$skintypes[1]);
            array_push($returnTypes,$skintypes[3]);

            $result["skintypes"]=$returnTypes;
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
    
    function tfgg_api_insert_user_proprietary($demographics,$commPref, $promo, $pkg){
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

        //2019-10-31 CB V1.2.1.1 - these are passed in now
        //$promo = get_option('tfgg_scp_reg_promo');
        //$pkg = '';

        if($pkg==''){$pkg='0000000000';} 

        if($promo==''){$promo='0000000000';} 
            
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
        //2019-10-22 CB V1.1.1.3 - new fields added
        $url=str_replace('sUserDefined1',$demographics['userdefined1'],$url);
        $url=str_replace('sUserDefined2',$demographics['userdefined2'],$url);
        
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
        $url=str_replace('sPackageNumber',$pkg,$url);
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
            
            $result['url']=$url;
			
			return json_encode($result);
		}else{
		       
            $result["results"]="success";
            $result["clientnumber"]=$data[0]->client_number;
            return json_encode($result);
		} 
        
    }

    function tfgg_api_set_password($clientNumber, $password){
        //TFGG_SyncPassword(sclientNumber, sEmpNo, sNewPass
        $url=tfgg_get_api_url().'TSunLyncAPI/TFGG_SyncPassword/sclientNumber/sEmpNo/sNewPass';
        $emp = get_option('tfgg_scp_update_employee');

        $url=str_replace('sclientNumber',$clientNumber,$url);
        $url=str_replace('sEmpNo',$emp,$url);
        $url=str_replace('sNewPass',tfgg_cp_hash_password($password), $url);

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
            
            $result['url']=$url;
			
			return json_encode($result);
		}else{
		       
            $result["results"]="success";
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
    add_action('wp_ajax_nopriv_tfgg_api_schedule_appt', 'tfgg_api_schedule_appt');
    
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
            
            /*2019-10-12 CB V1.1.1.1 - deprecated
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
            }*/
            
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
        //2019-10-08 CB V1.0.1.2 - 
        $buffer = get_option('tfgg_scp_appointments_cancel_allowed_hrs');
        if(($buffer=='0')||($buffer=='')){
            return true;//no buffer was set
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

        /*switch (substr($number,0,2)){
            case '02':
                $number=substr($number,0,3)." ".substr($number,3,4)." ".substr($number,8,4);
                break;
            case '07':
                $number=substr($number,0,5)." ".substr($number,5,6);
                break;
            default:
                $number=substr($number,0,4)." ".substr($number,4,12);
                break;
        }*/
    
        return $number;
    }

    function tfgg_format_date_for_display($date){
        //$date='07/27/2019';
        $date = new DateTime(tfgg_format_date_to_ymd($date));        
        return $date->format('j/n/Y');
    }

    function tfgg_format_time_for_display($time){
        $time = new DateTime($time);
        return $time->format('g:i A');
    }

    function tfgg_format_date_to_ymd($date){
        //USE THIS FUNCTION CONVERT D/M/Y TO Y/M/D
        if((($_SERVER['HTTP_HOST']!='localhost:8888')&&
        ($_SERVER['HTTP_HOST']!='tfgg-portal.theherdsoftware.com'))||
        (get_option('tfgg_scp_api_url')=='188.95.206.202')){
            $date = str_replace('/', '-', $date); //this line is to ensure UK dates parse correctly
        }
        $date = new DateTime($date);
        return $date->format('Y-m-d');   
    }

    //2019-09-27 CB V1.0.0.5 - clean up the names
    function tfgg_delete_all_between($beginning, $end, $string) {
        $beginningPos = strpos($string, $beginning);
        $endPos = strpos($string, $end);
        if ($beginningPos === false || $endPos === false) {
            return $string;
        }

        $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

        return tfgg_delete_all_between($beginning, $end, str_replace($textToDelete, '', $string)); // recursion to ensure all occurrences are replaced
    }

    //2019-09-27 CB V1.0.0.5 - function to not show services if they were purchased too long ago
    //hardcoded to 18 months at present
    function tfgg_purchased_within_acceptable_period($purchase_date){
        //return true;

        //echo $purchase_date;
        if((($_SERVER['HTTP_HOST']!='localhost:8888')&&
        ($_SERVER['HTTP_HOST']!='tfgg-portal.theherdsoftware.com'))||
        (get_option('tfgg_scp_api_url')=='188.95.206.202')){  
            $purchase_date = str_replace('/', '-', $purchase_date); //this line is to ensure UK dates parse correctly
        }
        
        $purchase_date = new DateTime($purchase_date);
        //echo $purchase_date->format('Y-m-d');
        $now = new DateTime();
        //echo ' '.$now->format('Y-m-d');
        $diff = $purchase_date->diff($now); // Returns DateInterval
        //echo $diff->format('%R%a days');
        //echo (12*$diff->y+$diff->m);

        //2020-07-11 CB - this wasn't working, changed to monthsSincePurchase
        //$lessThanMonths = $diff->y === 0 && $diff->m < 18;//18 is hardcoded
        $monthsSincePurchase = (12*$diff->y+$diff->m);
        return $monthsSincePurchase<=18 ? true : false;
    }

    //2019-10-23 CB V1.1.2.1 - new function to return the stores selected as a 'useable' string for the API
    function tfgg_scp_get_stores_selected_for_api(){
        $stores = get_option('tfgg_scp_store_selection');
        if($stores<>''){
            $storesSelected = join('","',$stores);   
            return '"'.$storesSelected.'"';    
        }else{
            return '';
        }
    }

    //2019-10-23 CB V1.2.6.5 - new function to return the stores selected for registration pages as a 'useable' string for the API
    function tfgg_scp_get_stores_selected_for_reg(){
        $stores = get_option('tfgg_scp_store_registration_selection');
        if($stores<>''){
            $storesSelected = join('","',$stores);   
            return '"'.$storesSelected.'"';    
        }else{
            return '';
        }
    }

    //2019-10-23 CB V1.2.6.5
    function tfgg_display_currency_symbol(){
        switch (get_option('tfgg_scp_cart_currency_symbol','1')){
            case 2: return '&euro;';
            break;
            default: return '&#163;';            
        }
    }

    //2020-02-09 CB V1.2.4.15
    function tfgg_scp_validate_service_dates($fromDate, $toDate){
        if(($fromDate=='1899-12-30')&&($toDate=='1899-12-30')){
            //dates are blank, so we are all set
            return true;
        }

        if($fromDate=='1899-12-30'){$fromDate = new DateTime();}
        if($toDate=='1899-12-30'){$toDatenew = new DateTime();}

        //2020-03-04 CB V1.2.5.5 - fixed date time comparison 
        //2020-03-06 CB V1.2.5.10 - changed comaprisons to be inclusive
        /*if((new DateTime($fromDate)<new DateTime())||(new DateTime($toDate)>new DateTime())){        
            //from date is in the future or
            //to date is in the past
            return false;
        }else{
            return true;
        }
        */

        //2020-03-06 CB V1.2.5.11 - new DateTime was adding a 'time' to the date, added 'today'
        if(new DateTime($fromDate)>new DateTime('today')){
			return false;//doesn't start until a future date
		}elseif(new DateTime('today')>new DateTime($toDate)){
			return false;//it ended in the past
		}else{
			return true;
		}
                
    }

    function tfgg_scp_get_packages_selected_for_api(){
        $packages = get_option('tfgg_scp_package_selection');
        if($packages<>''){
            $packagesSelected = join('","',$packages);   
            return '"'.$packagesSelected.'"';    
        }else{
            return '';
        }    
    }

    function tfgg_scp_get_memberships_selected_for_api(){
        $memberships = get_option('tfgg_scp_membership_selection');
        if($memberships<>''){
            $membershipsSelected = join('","',$memberships);   
            return '"'.$membershipsSelected.'"';    
        }else{
            return '';
        }    
    }

    function tfgg_scp_get_packages_from_api($allowedPackageList='', $affiliatedStore='', $sortByName=true){
        /*CIPGetPackages(sPackageList, sStoreCode:String; mrktCode*/
        $url=tfgg_get_api_url().'TSunLyncAPI/CIPGetPackages/sPackageList/sStoreCode';
        
        $url=str_replace('sPackageList',$allowedPackageList,$url);
        $url=str_replace('sStoreCode',$affiliatedStore,$url);

        try{
            $data = tfgg_execute_api_request('GET',$url,'');
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
            /*$result["packages"]=array_slice($data,1,-1);
            usort($result["packages"],'tfgg_order_service_by_name');*/

            $packages = array_slice($data,1,-1);
            //2020-02-27 CB V1.2.4.24 - changed to order by price
            if($sortByName){
                usort($packages,'tfgg_order_service_by_name');
            }else{
                usort($packages, 'tfgg_order_service_by_price');
            }

            $packageAlias = (array)get_option('tfgg_scp_package_alias',array());
            $packageImg = get_option('tfgg_scp_package_img',array());
            $packageText = get_option('tfgg_scp_package_free_text',array());
            foreach($packages as &$packageDetails){
                if((is_array($packageAlias))&&(array_key_exists($packageDetails->package_id, $packageAlias))&&
                ($packageAlias[$packageDetails->package_id]<>'')){
                    $packageDetails->alias = $packageAlias[$packageDetails->package_id];
                }else{
                    $packageDetails->alias = $packageDetails->description;
                }

                if((is_array($packageImg))&&(array_key_exists($packageDetails->package_id, $packageImg))&&
                ($packageImg[$packageDetails->package_id]<>'')){ 
                    $packageDetails->img = $packageImg[$packageDetails->package_id]; 
                }else{ 
                    $packageDetails->img = '';
                }

                if(is_array($packageText)&&(array_key_exists($packageDetails->package_id, $packageText))&&
                ($packageText[$packageDetails->package_id]<>'')){ 
                    $packageDetails->freeText = $packageText[$packageDetails->package_id]; 
                }else{ 
                    $packageDetails->freeText = '';
                }
            }
            $result["packages"]=$packages;
            return json_encode($result);
		}  
    }

    function tfgg_scp_get_memberships_from_api($allowedMembershipList='', $affiliatedStore='', $sortByName=true){
        $url=tfgg_get_api_url().'TSunLyncAPI/CIPGetMemberships/sMembershipList/sStoreCode';
        
        $url=str_replace('sMembershipList',$allowedMembershipList,$url);
        $url=str_replace('sStoreCode',$affiliatedStore,$url);

        try{
            $data = tfgg_execute_api_request('GET',$url,'');
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

            $memberships=array_slice($data,1,-1);
            //2020-02-27 CB V1.2.4.24 - changed to order by price
            if($sortByName){
                usort($memberships,'tfgg_order_service_by_name'); 
            }else{
                usort($memberships, 'tfgg_order_service_by_price');
            }

            $membershipAlias = (array)get_option('tfgg_scp_membership_alias',array());
            $membershipImg = get_option('tfgg_scp_membership_img',array());
            $membershipText = get_option('tfgg_scp_membership_free_text',array());
            foreach($memberships as &$membershipDetails){
                if((is_array($membershipAlias))&&(array_key_exists($membershipDetails->membership_id, $membershipAlias))&&
                ($membershipAlias[$membershipDetails->membership_id]<>'')){
                    $membershipDetails->alias = $membershipAlias[$membershipDetails->membership_id];
                }else{
                    $membershipDetails->alias = $membershipDetails->description;
                }

                if((is_array($membershipImg))&&(array_key_exists($membershipDetails->membership_id, $membershipImg))&&
                ($membershipImg[$membershipDetails->membership_id]<>'')){ 
                    $membershipDetails->img = $membershipImg[$membershipDetails->membership_id]; 
                }else{ 
                    $membershipDetails->img = '';
                }

                if((is_array($membershipText))&&(array_key_exists($membershipDetails->membership_id, $membershipText))&&
                ($membershipText[$membershipDetails->membership_id]<>'')){ 
                    $membershipDetails->freeText = $membershipText[$membershipDetails->membership_id]; 
                }else{ 
                    $membershipDetails->freeText = '';
                }
            }

            $result["memberships"]=$memberships;
            //usort($result["memberships"],'tfgg_order_service_by_name');
            return json_encode($result);
		}  
    }

    function tfgg_scp_can_service_be_purchased($serviceType, $serviceNumber, $allowedServiceList){
        if(get_option('tfgg_scp_enable_cart',0)==0){return false;}//2020-02-17 CB V1.2.4.17
        if($allowedServiceList==''){return false;}

        foreach($allowedServiceList as &$allowedServiceDetails){
            switch ($serviceType){
                case 'P':
                    if(($allowedServiceDetails->package_id==$serviceNumber)&&
                    (tfgg_scp_validate_service_dates($allowedServiceDetails->available_from, $allowedServiceDetails->available_to))){
                        return true;
                    }
                break;
                case 'M':
                    if(($allowedServiceDetails->membership_id==$serviceNumber)&&
                    (tfgg_scp_validate_service_dates($allowedServiceDetails->available_from, $allowedServiceDetails->available_to))){
                        return true;
                    }
                break;
                default: return false;
            }
        }

        return false;//default

    }

    function tfgg_scp_get_cart_contents(){
        if(isset($_SESSION['tfgg_scp_cartid'])){
            $cartid=$_SESSION['tfgg_scp_cartid'];
        }else{
            $cartid='';
        }

        $url=tfgg_get_api_url().'TAPICart/CartDetails/sCartID/sclientnumber';
        
        $url=str_replace('sCartID',$cartid,$url);
        $url=str_replace('sclientnumber',tfgg_cp_get_sunlync_client(),$url);

        try{
            $data = tfgg_execute_api_request('GET',$url,'');
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            return json_encode($result);
        }

        $returned=$data->result[0][0]; 
        
        if((array_key_exists('WARNING',$returned))||(array_key_exists('ERROR',$returned))){
            if(array_key_exists('ERROR',$returned)){
				$result=array("results"=>"FAIL",
					"response"=>$returned->ERROR);
			}else{
				$result=array("results"=>"FAIL",
                    "response"=>$returned->WARNING);

                if(StrToupper($returned->WARNING)=='NO CART ITEMS AVAILABLE'){
                    if(isset($_SESSION['tfgg_scp_cartid'])){
                        unset($_SESSION['tfgg_scp_cartid']);
                    }
                    if(isset($_SESSION['tfgg_scp_cart_qty'])){
                        unset($_SESSION['tfgg_scp_cart_qty']);
                    }
                }
            }                

            return json_encode($result);
        }

        $cartHeader = $data->result[0][0][0];
        $cartHeader = $cartHeader->header[0];//there is only ever 1 header record

        $_SESSION['tfgg_scp_cart_store'] = $cartHeader->processingStore;
        $_SESSION['tfgg_scp_cart_qty'] = $cartHeader->qty;

        $cartItems= $data->result[0][0][1];
        $cartItems = $cartItems->lineitems;//this could be an array of items

        $paymentItems= $data->result[0][0][2];
        $paymentItems = $paymentItems->paymentitems;//this could be an array of items

        $result["results"]="success";
        $result["header"]=$cartHeader;
        $result["lineItems"]=$cartItems;

        $packageAlias = get_option('tfgg_scp_package_alias',array());
        $packageImg = get_option('tfgg_scp_package_img',array());
        $packageText = get_option('tfgg_scp_package_free_text',array());

        $membershipAlias = get_option('tfgg_scp_membership_alias',array());
        $membershipImg = get_option('tfgg_scp_membership_img',array());
        $membershipText = get_option('tfgg_scp_membership_free_text',array());

        foreach($result["lineItems"] as &$itemDetails){
            if($itemDetails->ItemType=='P'){
                if((is_array($packageAlias))&&(array_key_exists($itemDetails->KeyValue,$packageAlias))&&
                ($packageAlias[$itemDetails->KeyValue]<>'')){
                    $itemDetails->alias = $packageAlias[$itemDetails->KeyValue];
                }else{
                    $itemDetails->alias = $itemDetails->Description;
                }

                if((is_array($packageImg))&&(array_key_exists($itemDetails->KeyValue, $packageImg))&&
                ($packageImg[$itemDetails->KeyValue]<>'')){ 
                    $itemDetails->img = $packageImg[$itemDetails->KeyValue]; 
                }else{ 
                    $itemDetails->img = '';
                }

                if((is_array($packageText))&&(array_key_exists($itemDetails->KeyValue, $packageText))&&
                ($packageText[$itemDetails->KeyValue]<>'')){ 
                    $itemDetails->freeText = $packageText[$itemDetails->KeyValue]; 
                }else{ 
                    $itemDetails->freeText = '';
                }
            }else{
                if((is_array($membershipAlias))&&(array_key_exists($itemDetails->KeyValue,$membershipAlias))&&
                ($membershipAlias[$itemDetails->KeyValue]<>'')){
                    $itemDetails->alias = $membershipAlias[$itemDetails->KeyValue];
                }else{
                    $itemDetails->alias = $itemDetails->Description;
                }

                if((is_array($membershipImg))&&(array_key_exists($itemDetails->KeyValue,$membershipImg))&&
                ($membershipImg[$itemDetails->KeyValue]<>'')){
                    $itemDetails->img = $membershipImg[$itemDetails->KeyValue];
                }else{
                    $itemDetails->img = '';
                }

                if((is_array($membershipText))&&(array_key_exists($itemDetails->KeyValue,$membershipText))&&
                ($membershipText[$itemDetails->KeyValue]<>'')){
                    $itemDetails->freeText = $membershipText[$itemDetails->KeyValue];
                }else{
                    $itemDetails->freeText = '';
                }
                
            }
        }

        $result["paymentItems"]=$paymentItems;
        
        if(!isset($_SESSION['tfgg_scp_cartid'])){
            $_SESSION['tfgg_scp_cartid']=$result["header"]->cartID;
        }

        return json_encode($result);
    }

    function tfgg_scp_get_processed_cart_contents($receiptNo){

        $url=tfgg_get_api_url().'TAPICart/ProcessedCartDetails/sReceiptNo';
        
        $url=str_replace('sReceiptNo',$receiptNo,$url);

        try{
            $data = tfgg_execute_api_request('GET',$url,'');
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            return json_encode($result);
        }

        $returned=$data->result[0][0]; 
        
        if((array_key_exists('WARNING',$returned))||(array_key_exists('ERROR',$returned))){
            if(array_key_exists('ERROR',$returned)){
				$result=array("results"=>"FAIL",
					"response"=>$returned->ERROR);
			}else{
				$result=array("results"=>"FAIL",
                    "response"=>$returned->WARNING);
            }                

            return json_encode($result);
        }

        $cartHeader = $data->result[0][0][0];
        $cartHeader = $cartHeader->header[0];//there is only ever 1 header record

        $cartItems= $data->result[0][0][1];
        $cartItems = $cartItems->lineitems;//this could be an array of items

        $paymentItems= $data->result[0][0][2];
        $paymentItems = $paymentItems->paymentitems;//this could be an array of items

        $result["results"]="success";
        $result["header"]=$cartHeader;
        $result["lineItems"]=$cartItems;

        $packageAlias = get_option('tfgg_scp_package_alias',array());
        $packageImg = get_option('tfgg_scp_package_img',array());
        $packageText = get_option('tfgg_scp_package_free_text',array());

        $membershipAlias = get_option('tfgg_scp_membership_alias',array());
        $membershipImg = get_option('tfgg_scp_membership_img',array());
        $membershipText = get_option('tfgg_scp_membership_free_text',array());

        foreach($result["lineItems"] as &$itemDetails){
            if($itemDetails->ItemType=='P'){
                if((is_array($packageAlias))&&(array_key_exists($itemDetails->KeyValue,$packageAlias))&&
                ($packageAlias[$itemDetails->KeyValue]<>'')){
                    $itemDetails->alias = $packageAlias[$itemDetails->KeyValue];
                }else{
                    $itemDetails->alias = $itemDetails->Description;
                }

                if((is_array($packageImg))&&(array_key_exists($itemDetails->KeyValue, $packageImg))&&
                ($packageImg[$itemDetails->KeyValue]<>'')){ 
                    $itemDetails->img = $packageImg[$itemDetails->KeyValue]; 
                }else{ 
                    $itemDetails->img = '';
                }

                if((is_array($packageText))&&(array_key_exists($itemDetails->KeyValue, $packageText))&&
                ($packageText[$itemDetails->KeyValue]<>'')){ 
                    $itemDetails->freeText = $packageText[$itemDetails->KeyValue]; 
                }else{ 
                    $itemDetails->freeText = '';
                }
            }else{
                if((is_array($membershipAlias))&&(array_key_exists($itemDetails->KeyValue,$membershipAlias))&&
                ($membershipAlias[$itemDetails->KeyValue]<>'')){
                    $itemDetails->alias = $membershipAlias[$itemDetails->KeyValue];
                }else{
                    $itemDetails->alias = $itemDetails->Description;
                }

                if((is_array($membershipImg))&&(array_key_exists($itemDetails->KeyValue,$membershipImg))&&
                ($membershipImg[$itemDetails->KeyValue]<>'')){
                    $itemDetails->img = $membershipImg[$itemDetails->KeyValue];
                }else{
                    $itemDetails->img = '';
                }

                if((is_array($membershipText))&&(array_key_exists($itemDetails->KeyValue,$membershipText))&&
                ($membershipText[$itemDetails->KeyValue]<>'')){
                    $itemDetails->freeText = $membershipText[$itemDetails->KeyValue];
                }else{
                    $itemDetails->freeText = '';
                }
                
            }
        }

        $result["paymentItems"]=$paymentItems;
        
        if(!isset($_SESSION['tfgg_scp_cartid'])){
            $_SESSION['tfgg_scp_cartid']=$result["header"]->cartID;
        }

        return json_encode($result);    
    }

    function tfgg_scp_post_cart_item(){
        /*
        the API is expecting the following JSON format
        {
        "clientNumber": "",
        "storeCode": "",
        "cartID": "",
        "itemID": "",
        "itemType": "",
        "keyValue": "",
        "qty": "",
        "mrkt": ""
        "processingEmployee":""
        }
        */
        $url=tfgg_get_api_url().'TAPICart/CartItems';

        if(isset($_SESSION['tfgg_scp_cartid'])){
            $cartid=$_SESSION['tfgg_scp_cartid'];
        }else{
            $cartid='';
        }

        if(isset($_SESSION['tfgg_scp_cart_store'])){$storecode = $_SESSION['tfgg_scp_cart_store'];}else{$storecode=$_SESSION['clientHomeStore'];}

        $postBody = array();
        $postBody["clientNumber"] = tfgg_cp_get_sunlync_client();
        $postBody["storeCode"] = $storecode;
        $postBody["cartID"] = $cartid;
        $postBody["itemType"] = $_POST['data']['itemType'];

        if(array_key_exists('itemid',$_POST['data'])){
            $postBody["itemID"] = $_POST['data']['itemid'];
        }else{
            $postbody["itemID"] = '';
        }

        $postBody["keyValue"] = $_POST['data']['keyValue'];
        $postBody["qty"] = $_POST['data']['qty'];
        $postBody["mrkt"] = get_option('tfgg_scp_api_mrkt');
        $postBody["processingEmployee"] = get_option('tfgg_scp_cart_employee');

        $postBody = json_encode($postBody);
        
        try{
            $data = tfgg_execute_api_request('POST', $url, $postBody);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            exit(json_encode($result));
        }
        $result=$data->result[0][0];

        if((array_key_exists('ERROR',$result))||(array_key_exists('WARNING',$result))){
			if(array_key_exists('ERROR',$result)){
				$result=array("results"=>"FAIL",
					"response"=>$result->ERROR);
			}else{
				$result=array("results"=>"FAIL",
					"response"=>$result->WARNING);
			}			
			exit(json_encode($result));
		}else{
            $return["results"]="success";
            $cartID=$data->result[0][1];
            $return["cartID"]=$cartID->cartID;
            $_SESSION['tfgg_scp_cartid']=$return["cartID"];

            if(isset($_SESSION['tfgg_scp_cart_qty'])){
                $_SESSION['tfgg_scp_cart_qty']=$_SESSION['tfgg_scp_cart_qty']+$_POST['data']['qty'];
            }else{
                $_SESSION['tfgg_scp_cart_qty']=$_POST['data']['qty'];
            }
            
            $return['cartQty']=$_SESSION['tfgg_scp_cart_qty'];

            exit(json_encode($return));
		} 

    }
    add_action('wp_ajax_tfgg_scp_post_cart_item','tfgg_scp_post_cart_item');
    add_action('wp_ajax_nopriv_tfgg_scp_post_cart_item','tfgg_scp_post_cart_item');
    
    function tfgg_scp_delete_cart_item(){
        $url=tfgg_get_api_url().'TAPICart/CartItems/sCartItemID';
        
        $url=str_replace('sCartItemID',$_POST['data']['itemID'],$url);
        
        try{
            $data = tfgg_execute_api_request("DELETE",$url,'');
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            exit(json_encode($result));
        }
        
        $returned=$data->result[0][0];

        if((array_key_exists('ERROR',$returned))||(array_key_exists('WARNING',$returned))){
			if(array_key_exists('ERROR',$returned)){
				$result=array("results"=>"FAIL",
					"response"=>$returned->ERROR);
			}else{
				$result=array("results"=>"FAIL",
					"response"=>$returned->WARNING);
            }
            
            if(StrToUpper($returned->WARNING)=='ITEM ID DOES NOT EXIST'){
                $result=array("results"=>"SUCCESS");//item does not exist, allow it to be removed    
            }
			
			exit(json_encode($result));
		}else{		       
            $result["results"]="success";
            exit(json_encode($result));
		} 

    }
    add_action('wp_ajax_tfgg_scp_delete_cart_item','tfgg_scp_delete_cart_item');
    add_action('wp_ajax_nopriv_tfgg_scp_delete_cart_item','tfgg_scp_delete_cart_item'); 
    
    function tfgg_api_get_payment_types(){
        //CIPGetPaymentID(sPaymentNumber, sDesc, sDeleted, sStoreCode, mrktCode

        $url=tfgg_get_api_url().'TSunLyncAPI/CIPGetPaymentID/sPaymentNumber/sDesc/sDeleted/sStoreCode';

        $url=str_replace('sPaymentNumber','',$url);
        $url=str_replace('sDesc','',$url);
        $url=str_replace('sDeleted','',$url);
        $url=str_replace('sStoreCode','',$url);

        try{
            $data = tfgg_execute_api_request("GET",$url,'');
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
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
			
			return json_encode($result);
		}else{
		       
            $result["results"]="success";
            $result["payment_types"]=array_slice($data,1,-1);
            return json_encode($result);
		}
        
    }

    function tfgg_scp_post_payment_item(){
        $url=tfgg_get_api_url().'TAPICart/CartPayments';

        $postBody = array();
        $postBody["cartID"] = $_SESSION['tfgg_scp_cartid'];
        //$postBody["keyValue"] = $_POST['data']['keyValue'];
        $postBody["amt"] = $_POST['data']['amt'];
        $postBody["mrkt"] = get_option('tfgg_scp_api_mrkt');
        $postBody["externalID"] = $_POST['data']['externalID'];
        $postBody["externalDesc"] = $_POST['data']['externalDesc'];

        switch(StrToUpper($postBody["externalDesc"])){
            case 'PAYPAL':$postBody['keyValue'] = get_option('tfgg_scp_cart_paypal_payment','0000000001');
            case 'SAGE':$postBody['keyVaue'] = get_option('tfgg_scp_cart_sage_payment','0000000001');
            case 'SUNLYNC_PROMO':$postBody['keyValue']='0000000008';
        }

        $postBody = json_encode($postBody);
       
        try{
            $data = tfgg_execute_api_request('POST', $url, $postBody);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            exit(json_encode($result));
        }

        $result=$data->result[0][0];

        if((array_key_exists('ERROR',$result))||(array_key_exists('WARNING',$result))){
			if(array_key_exists('ERROR',$result)){
				$result=array("results"=>"FAIL",
					"response"=>$result->ERROR);
			}else{
				$result=array("results"=>"FAIL",
					"response"=>$result->WARNING);
			}			
			exit(json_encode($result));
		}else{
            $return["results"]="success";

            exit(json_encode($return));
		} 
    }
    add_action('wp_ajax_tfgg_scp_post_payment_item','tfgg_scp_post_payment_item');
    add_action('wp_ajax_nopriv_tfgg_scp_post_payment_item','tfgg_scp_post_payment_item');

    function tfgg_scp_post_payment_item_manual($cartid, $amt, $externalID, $externalDesc){
        $url=tfgg_get_api_url().'TAPICart/CartPayments';

        $postBody = array();
        $postBody["cartID"] = $cartid;
        //$postBody["keyValue"] = $_POST['data']['keyValue'];
        $postBody["amt"] = $amt;
        $postBody["mrkt"] = get_option('tfgg_scp_api_mrkt');
        $postBody["externalID"] = $externalID;
        $postBody["externalDesc"] = $externalDesc;

        switch(StrToUpper($postBody["externalDesc"])){
            case 'PAYPAL':
                $postBody['keyValue'] = get_option('tfgg_scp_cart_paypal_payment','0000000001');
            break;
            case 'SAGE':
                $postBody['keyValue'] = get_option('tfgg_scp_cart_sage_payment','0000000001');
            break;
        }

        $postBody = json_encode($postBody);
       
        try{
            $data = tfgg_execute_api_request('POST', $url, $postBody);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            return json_encode($result);
        }

        $result=$data->result[0][0];

        if((array_key_exists('ERROR',$result))||(array_key_exists('WARNING',$result))){
			if(array_key_exists('ERROR',$result)){
				$result=array("results"=>"FAIL",
					"response"=>$result->ERROR);
			}else{
				$result=array("results"=>"FAIL",
					"response"=>$result->WARNING);
			}			
			return json_encode($result);
		}else{
            $return["results"]="success";

            return json_encode($return);
		} 
    }

    function tfgg_scp_post_cart_storecode(){
        $_SESSION['tfgg_cp_cart_warning']='1';

        if((!isset($_SESSION['tfgg_scp_cartid']))||
        (isset($_SESSION['tfgg_scp_cart_store'])&&($_SESSION['tfgg_scp_cart_store']==$_POST['data']['storecode']))
        ){
            //no need to hit the API, just change the cart store and retrun
            $_SESSION['tfgg_scp_cart_store']=$_POST['data']['storecode'];
            
            $return["results"]="success";
            exit(json_encode($return));
        }

        $url=tfgg_get_api_url().'TAPICart/CartStore/sCartID/sStoreCode';

        $url=str_replace('sCartID',$_SESSION['tfgg_scp_cartid'],$url);
        $url=str_replace('sStoreCode',$_POST['data']['storecode'],$url);

        try{
            $data = tfgg_execute_api_request('POST', $url, '');
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            exit(json_encode($result));
        }

        $result=$data->result[0][0];

        if((array_key_exists('ERROR',$result))||(array_key_exists('WARNING',$result))){
			if(array_key_exists('ERROR',$result)){
				$result=array("results"=>"FAIL",
					"response"=>$result->ERROR);
			}else{
				$result=array("results"=>"FAIL",
					"response"=>$result->WARNING);
			}			
			exit(json_encode($result));
		}else{
            $return["results"]="success";

            tfgg_scp_get_cart_contents();//after update, refresh cart

            exit(json_encode($return));
		} 

    }
    add_action('wp_ajax_tfgg_scp_post_cart_storecode','tfgg_scp_post_cart_storecode');
    add_action('wp_ajax_nopriv_tfgg_scp_post_cart_storecode','tfgg_scp_post_cart_storecode');


    function tfgg_api_finalize_cart(){
        $url=tfgg_get_api_url().'TAPICart/Cart/sCartID';
        $url=str_replace('sCartID',$_SESSION['tfgg_scp_cartid'],$url);

        try{
            $data = tfgg_execute_api_request('PUT', $url, '');
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            exit(json_encode($result));
        }
        $result=$data->result[0][0];
        $receipt=$data->result[0][1];

        if((array_key_exists('ERROR',$result))||(array_key_exists('WARNING',$result))){
			if(array_key_exists('ERROR',$result)){
				$result=array("results"=>"FAIL",
					"response"=>$result->ERROR);
			}else{
				$result=array("results"=>"FAIL",
					"response"=>$result->WARNING);
			}			
			exit(json_encode($result));
		}else{
            $return["results"]="success";

            unset($_SESSION['tfgg_scp_cartid']);
            unset($_SESSION['tfgg_scp_cart_qty']);

            $return["receipt"]=$receipt->recipt;


            exit(json_encode($return));
		} 
        
    }
    add_action('wp_ajax_tfgg_api_finalize_cart','tfgg_api_finalize_cart');
    add_action('wp_ajax_nopriv_tfgg_api_finalize_cart','tfgg_api_finalize_cart');

    function tfgg_api_finalize_cart_manual($cartid){
        $url=tfgg_get_api_url().'TAPICart/Cart/sCartID';
        $url=str_replace('sCartID',$cartid,$url);

        try{
            $data = tfgg_execute_api_request('PUT', $url, '');
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            return json_encode($result);
        }
        $result=$data->result[0][0];
        $receipt=$data->result[0][1];

        if((array_key_exists('ERROR',$result))||(array_key_exists('WARNING',$result))){
			if(array_key_exists('ERROR',$result)){
				$result=array("results"=>"FAIL",
					"response"=>$result->ERROR);
			}else{
				$result=array("results"=>"FAIL",
					"response"=>$result->WARNING);
			}			
			return json_encode($result);
		}else{
            $return["results"]="success";

            unset($_SESSION['tfgg_scp_cartid']);
            unset($_SESSION['tfgg_scp_cart_qty']);

            $return["receipt"]=$receipt->recipt;

            return json_encode($return);
		}     
    }

    function tfgg_scp_sagepay_generate_merchant_session_key(){

        //$auth = base64_encode(get_option('tfgg_scp_cart_sage_key').':'.get_option('tfgg_scp_cart_sage_pass'));
        $auth = get_option('tfgg_scp_cart_sage_key').':'.get_option('tfgg_scp_cart_sage_pass');
        //echo $auth;

        $curl = curl_init();

        //2020-02-20 CB V1.2.4.18 - switch the url
        if(get_option('tfgg_scp_cart_sage_pay_sandbox','1')=='1'){
            $isSandbox=1;
            $sageURL="https://pi-test.sagepay.com/api/v1/merchant-session-keys";
        }else{
            $isSandbox=0;
            $sageURL="https://pi-live.sagepay.com/api/v1/merchant-session-keys";
        }

        curl_setopt_array($curl, array(
            //CURLOPT_URL => "https://pi-test.sagepay.com/api/v1/merchant-session-keys",
            CURLOPT_URL => $sageURL, 
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{ "vendorName": "'.get_option('tfgg_scp_cart_sage_vendor_name').'" }',
            CURLOPT_USERPWD => $auth,
            CURLOPT_HTTPHEADER => array(
              "Cache-Control: no-cache",
              "Content-Type: application/json"
            ),
          ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        switch($httpcode){
            case 401:
                log_me('sage merchant session fetch error: '.$response);
                $result = array('results'=>'error',
                'errorNice'=>'There was an error staging your credit card transaction',
                'error'=>$response);
            break;
            case 201:
                $response = json_decode($response);
                $result = array('results'=>'success',
                'sageMerchantSession'=>$response->merchantSessionKey,
                'sageMerchantSessionExp'=>$response->expiry);
            break;
            default:
                log_me('sage merchant session fetch error '.$httpcode.': '.$response);
                $response = json_decode($response); 
                $result = array('results'=>'error',
                'response'=>$response,
                'auth'=>$auth);  
            break;
        }

        //tfgg_scp_write_card_transaction_log($_SESSION["tfgg_scp_cartid"], 'SagePay', 'Generate Session Key', $isSandbox, $result);

        exit(json_encode($result));

    }
    add_action('wp_ajax_tfgg_scp_sagepay_generate_merchant_session_key','tfgg_scp_sagepay_generate_merchant_session_key');
    add_action('wp_ajax_nopriv_tfgg_scp_sagepay_generate_merchant_session_key','tfgg_scp_sagepay_generate_merchant_session_key');

    function tfgg_scp_process_sage_pay_cart(){
        if(isset($_POST['tfgg_cp_cart_sage_nonce']) && wp_verify_nonce($_POST['tfgg_cp_cart_sage_nonce'],'tfgg-cp-cart-sage-nonce')){
            log_me('processing cart ('.$_POST['cartid'].') payment via sage identifier '.$_POST['card-identifier']);
            unset($_POST['tfgg_cp_cart_sage_nonce']);
            /*$_POST array(10) { ["tfgg_cp_user_email"]=> 
                ["tfgg_cp_user_first"]=> 
                ["tfgg_cp_user_last"]=>
                ["tfgg_cp_street_address"]=> 
                ["tfgg_cp_street_address_2"]=>
                ["tfgg_cp_city"]=> 
                ["tfgg_cp_post_code"]=>
                ["cartid"]=> 
                ["tfgg_cp_cart_sage_nonce"]=>
                ["card-identifier"]=>*/

            //using the card-identifier, we POST a transaction to SagePayPI and send the results to the SunLync API and finalize the cart

            //sage api reference https://test.sagepay.com/documentation/#transactions


            $cartContents = json_decode(tfgg_scp_get_cart_contents());
            $cartHeader = $cartContents->header;
            $auth = get_option('tfgg_scp_cart_sage_key').':'.get_option('tfgg_scp_cart_sage_pass');

            $tranDate = new DateTime();

            //$vendorCode = $cartHeader->processingStoreName.time();
            //$vendorCode = $_POST['cartid'];
            $vendorCode = uniqid($_SESSION['sunlync_client'],true);

            log_me('POSTING to SagePay as '.$vendorCode.' for '.$_POST['cartid']);

            //2020-02-20 CB V1.2.4.18 - switch the url
            if(get_option('tfgg_scp_cart_sage_pay_sandbox','1')=='1'){
                $isSandbox=1;
                $sageURL="https://pi-test.sagepay.com/api/v1/transactions";
            }else{
                $isSandbox=0;
                $sageURL="https://pi-live.sagepay.com/api/v1/transactions";
            }

            $curl = curl_init();
            curl_setopt_array($curl, array(
            //CURLOPT_URL => "https://pi-test.sagepay.com/api/v1/transactions",
            CURLOPT_URL => $sageURL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{' .
                                    '"transactionType": "Payment",' .
                                    '"paymentMethod": {' .
                                    '    "card": {' .
                                    '        "merchantSessionKey": "' . $_POST['sageMerchantSession'] . '",' .
                                    '        "cardIdentifier": "' . $_POST['card-identifier'] . '",' .
                                    '        "save": "false"' .
                                    '    }' .
                                    '},' .
                                    '"vendorTxCode": "'.$vendorCode.'",' .
                                    '"amount": '. (($cartHeader->total)*100)  .',' .
                                    '"currency": "GBP",' .
                                    '"description": "'.$cartHeader->processingStoreName.' Services",' .
                                    '"apply3DSecure": "Disable",' .
                                    '"applyAvsCvcCheck": "Disable", '.
                                    '"customerFirstName": "'.$_POST['tfgg_cp_user_first'].'",' .
                                    '"customerLastName": "'.$_POST['tfgg_cp_user_last'].'",' .
                                    '"billingAddress": {' .
                                    '    "address1": "'.$_POST['tfgg_cp_street_address'].'",' .
                                    '    "city": "'.$_POST['tfgg_cp_city'].'",' .
                                    '    "postalCode": "'.$_POST['tfgg_cp_post_code'].'",' .
                                    '    "country": "GB"' .
                                    '},' .
                                    '"entryMethod": "Ecommerce", ' .
                                    '"customerEmail": "'.$_POST['tfgg_cp_user_email'].'" '.
                                    '}',
            CURLOPT_USERPWD => $auth,
            CURLOPT_HTTPHEADER => array("Cache-Control: no-cache",
                "Content-Type: application/json"
            ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            log_me($response);            
            curl_close($curl);

            tfgg_scp_write_card_transaction_log($_POST['cartid'], 'SagePay', 'Process Transaction', $isSandbox, json_decode($response,true));

            $response = json_decode($response);
            
            //var_dump($response);
            if($response->statusCode=='0000'){
                //the transaction processed with no errors

                $postPayment = json_decode(tfgg_scp_post_payment_item_manual($_POST['cartid'],
                $cartHeader->total,
                $response->retrievalReference,
                'SAGE'));

                tfgg_scp_write_card_transaction_log($_POST['cartid'], 'SagePay', 'Post API Payment', $isSandbox, $postPayment);

                if($postPayment->results=='success'){
                    $cartFinal = json_decode(tfgg_api_finalize_cart_manual($_POST['cartid']));
                    
                    $msg = get_option('tfgg_scp_cart_success_message');
                    $msg = str_replace('!@#receiptnumber#@!',$cartFinal->receipt, $msg);

                    tfgg_cp_errors()->add('success', __($msg));
                    
                    //2020-03-01 CB V1.2.5.1 - need this to output gsat tags
                    $_SESSION['processedCartReceipt']=$cartFinal->receipt;

                }else{
                    tfgg_scp_process_sage_pay_refund($vendorCode, (($cartHeader->total)*100), 
                    $response->transactionId, $cartHeader->processingStoreName.' Services');
                    tfgg_cp_errors()->add('error_processing_card', __('There was an error processing your card<br/><br/>'.$postPayment->response.'<br/><br/>You have not been charged for this transaction'));    
                }

                if(array_key_exists('tfgg_cp_update_demographics',$_POST)){
                    //update the client demographics                    
                    $updateResult = json_decode(tfgg_scp_update_demographics(tfgg_cp_get_sunlync_client(),
                    $_POST['tfgg_cp_user_first'], $_POST['tfgg_cp_user_last'],
                    $_POST['tfgg_cp_street_address'], $_POST['tfgg_cp_street_address_2'], 
                    $_POST['tfgg_cp_city'], $_POST['tfgg_cp_post_code'],
                    $_POST['tfgg_cp_user_email'],'',''));
                }

                if(array_key_exists('tfgg_cp_update_comm_pref',$_POST)){
                    if($_POST['tfgg_cp_update_comm_pref']==='1'){
                        $reg_result = json_decode(tfgg_scp_update_comm_pref(tfgg_cp_get_sunlync_client(),
					    '0','0','1','0','0'));
                    }else{
                        $reg_result = json_decode(tfgg_scp_update_comm_pref(tfgg_cp_get_sunlync_client(),
						'1','0','0','0','0'));
                    }
                }

            }else{
                //handle errors
                tfgg_cp_errors()->add('error_processing_card', __('There was an error processing your card<br/><br/>'.$response->statusDetail));
            }


        }
    }
    add_action('init','tfgg_scp_process_sage_pay_cart');

    function tfgg_scp_process_sage_pay_refund($vendorCode, $amt, $transactionId, $description){

        $newVendorCode = uniqid($_SESSION['sunlync_client'],true);
        log_me('refunding '.$vendorCode.' as '.$newVendorCode);

        $description='rfnd '.$description;

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://pi-test.sagepay.com/api/v1/transactions",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => '{' .
                                '"transactionType": "Refund",' .
                                '"referenceTransactionId": "'.$transactionId.'",' .
                                '"vendorTxCode": "'.$newVendorCode.'",' .
                                '"amount": '.$amt.',' .
                                '"currency": "GBP",' .
                                '"description": "'.$description.'"' .
                                '}',
        CURLOPT_USERPWD => get_option('tfgg_scp_cart_sage_key').':'.get_option('tfgg_scp_cart_sage_pass'),
        CURLOPT_HTTPHEADER => array(
            "Cache-Control: no-cache",
            "Content-Type: application/json"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        log_me($response);
        curl_close($curl);

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
    
    //2019-09-27 CB V1.0.0.5 - deprecated to show only for admin
    //add_filter('show_admin_bar', '__return_false');//prevents the admin bar from showing after tfgg_sunlync user logs in

    /*2019-10-12 CB V1.1.1.1 - deprecated
    add_action('after_setup_theme', 'remove_admin_bar');
    function remove_admin_bar(){
        if (!current_user_can('administrator') && !is_admin()) {
            show_admin_bar(false);
        }
    }*/

    /*add_action('admin_init', 'blockusers_init');
    function blockusers_init() {
        //if the user is logged in and is not an admin, do not give them access to wp-admin
        $file = basename($_SERVER['PHP_SELF']);
        if (is_user_logged_in() && is_admin() && !current_user_can( 'administrator' ) &&
         ($file != 'admin-ajax.php')){
            wp_redirect(get_option('tfgg_scp_cplogin_page_success'));
            exit;
        }
    }*/
    
    add_filter('wp_nav_menu_items', 'tfgg_add_loginout_link', 10, 2 );
    function tfgg_add_loginout_link($items, $args){
        //add a logout link to the small menu bar (secondary-menu) at the top of the screen
        if(tfgg_is_sunlync_user_logged_in() && $args->theme_location=='secondary-menu'){
          $items .='<li><a href="" onclick="endPortalSession();">Log Out</a></li>';  
        }
        return $items;
    }
    
    add_filter('wp_nav_menu_items', 'tfgg_add_acct_overview_link', 9, 2 );
    function tfgg_add_acct_overview_link($items, $args){
        //add the account overview link to the nav bar
        $sunlyncuser = tfgg_cp_get_sunlync_client();
        //if(is_user_logged_in() && $sunlyncuser && $args->theme_location=='secondary-menu'){
        if($sunlyncuser && $args->theme_location=='secondary-menu'){
        //2019-10-09 CB V1.0.1.3 - added full site URL
          $items .='<li><a href="'. get_site_url().'/'.get_option('tfgg_scp_acct_overview') .'/">Account Overview</a></li>';  
        }
        return $items;
    }

    add_filter('wp_nav_menu_items', 'tfgg_add_cart_link', 8, 2 );
    function tfgg_add_cart_link($items, $args){
        //add the account overview link to the nav bar
        if(get_option('tfgg_scp_enable_cart',0)==0){return $items;}//2020-02-17 CB V1.2.4.17
        $sunlyncuser = tfgg_cp_get_sunlync_client();
        //$link=get_option('tfgg_scp_cart_menu_link_text','Pay Now')." <span id=\"tfgg_scp_cart_qty\">";
        $link = '<i class="fa fa-shopping-cart"></i> <span id="tfgg_scp_cart_qty">';
        if((isset($_SESSION['tfgg_scp_cart_qty']))&&($_SESSION['tfgg_scp_cart_qty']>0)){
            $link.=' ('.$_SESSION['tfgg_scp_cart_qty'].')';
        }

        $link.="</span>";

        $link2 = '<i class="fa fa-shopping-cart"></i> <span id="tfgg_scp_cart_qty_primary">';
        if((isset($_SESSION['tfgg_scp_cart_qty']))&&($_SESSION['tfgg_scp_cart_qty']>0)){
            $link2.=' ('.$_SESSION['tfgg_scp_cart_qty'].')';
        }

        $link2.="</span>";

        if($sunlyncuser && $args->theme_location=='secondary-menu'){
            //$items .='<li><a href="'. esc_url(add_query_arg('viewcart','cart',site_url(get_option('tfgg_scp_cart_slug')))) .'" id="tfgg_scp_cart_link">'.$link.'</a></li>'; 
            $items.='<li><a href="'. esc_url(site_url(get_option('tfgg_scp_cart_slug'))).'/" id="tfgg_scp_cart_link">'.$link.'</a></li>';
        }
        if($sunlyncuser && $args->theme_location=='primary-menu'){
            //$items .='<li><a href="'. esc_url(add_query_arg('viewcart','cart',site_url(get_option('tfgg_scp_cart_slug')))) .'" id="tfgg_scp_cart_link">'.$link.'</a></li>'; 
            $items.='<li><a href="'. esc_url(site_url(get_option('tfgg_scp_cart_slug'))) .'/" id="tfgg_scp_cart_link_primary">'.$link2.'</a></li>';
        }
        return $items;
    }

    //2019-09-30 CB V1.0.0.6 - new menu item
    //add_filter('wp_nav_menu_items', 'tfgg_add_mobile_appt_link', 9, 2 );
    function tfgg_add_mobile_appt_link($items, $args){
        //add the appt link to the nav bar
        if(wp_is_mobile()){//only show the item if it's a mobile device
            $sunlyncuser = tfgg_cp_get_sunlync_client();
            if(is_user_logged_in() && $sunlyncuser && $args->theme_location=='primary-menu'){
                //2019-10-09 CB V1.0.1.3 - added full site URL
                $items .='<li><a href="'. get_site_url().'/'.get_option('tfgg_scp_cpappt_page') .'/">Book Appointment</a></li>';  
            }            
        }
        return $items;
    }

    function tfgg_add_custom_query_var( $vars ){
        $vars[] = "viewcart";
        return $vars;
    }
    add_filter( 'query_vars', 'tfgg_add_custom_query_var' );

    //2019-11-28 CB V1.2.4.7
    function tfgg_scp_set_meta_equiv(){
        global $post;
        $post_slug = $post->post_name;
        if(($post_slug==get_option('tfgg_scp_cpnewuser_page_instore'))||
        (strpos(get_option('tfgg_scp_cpnewuser_page_instore'),$post_slug)>0)){
            echo '<meta http-equiv="refresh" content="36000"/>';
        }
    }
    add_action('wp_head','tfgg_scp_set_meta_equiv');
    
    /*2019-10-12 CB V1.1.1.1 - deprecated
    add_action('wp_logout','tfgg_auto_redirect_after_logout');
    function tfgg_auto_redirect_after_logout(){
        //self-explanatory - redirect to the home page after logout
        wp_redirect( home_url() );    
        exit();
    }*/

    remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );

    //2019-09-23 CB V1.0.0.2b - added new class definition
    class tfgg_scp_updater {

        private $slug;
    
        private $pluginData;
    
        private $username;
    
        private $repo;
    
        private $pluginFile;
    
        private $githubAPIResult;
    
        private $accessToken;
    
        private $pluginActivated;
    
        /**
         * Class constructor.
         *
         * @param  string $pluginFile
         * @param  string $gitHubUsername
         * @param  string $gitHubProjectName
         * @param  string $accessToken
         * @return null
         */
        function __construct( $pluginFile, $gitHubUsername, $gitHubProjectName, $accessToken = '' )
        {
            add_filter( "pre_set_site_transient_update_plugins", array( $this, "setTransitent" ) );
            add_filter( "plugins_api", array( $this, "setPluginInfo" ), 10, 3 );
            add_filter( "upgrader_pre_install", array( $this, "preInstall" ), 10, 3 );
            add_filter( "upgrader_post_install", array( $this, "postInstall" ), 10, 3 );
    
            $this->pluginFile 	= $pluginFile;
            $this->username 	= $gitHubUsername;
            $this->repo 		= $gitHubProjectName;
            $this->accessToken 	= $accessToken;
            
        }
    
        /**
         * Get information regarding our plugin from WordPress
         *
         * @return null
         */
        private function initPluginData()
        {
            $this->slug = plugin_basename( $this->pluginFile );
            
            if( !function_exists('get_plugin_data') ){
                require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            }
            
            $this->pluginData = get_plugin_data( $this->pluginFile );
        }
    
        /**
         * Get information regarding our plugin from GitHub
         *
         * @return null
         */
        private function getRepoReleaseInfo()
        {
            
            if ( ! empty( $this->githubAPIResult ) )
            {
                return;
            }
    
            // Query the GitHub API
            $url = "https://api.github.com/repos/{$this->username}/{$this->repo}/releases";
    
            if ( ! empty( $this->accessToken ) )
            {
                $url = add_query_arg( array( "access_token" => $this->accessToken ), $url );
            }
    
            // Get the results
            $this->githubAPIResult = wp_remote_retrieve_body( wp_remote_get( $url ) );
    
            if ( ! empty( $this->githubAPIResult ) )
            {
                $this->githubAPIResult = @json_decode( $this->githubAPIResult );
            }
    
            // Use only the latest release
            if ( is_array( $this->githubAPIResult ) )
            {
                $this->githubAPIResult = $this->githubAPIResult[0];
            }
        }
     
        /**
         * Push in plugin version information to get the update notification
         *
         * @param  object $transient
         * @return object
         */
        public function setTransitent( $transient )
        {
            if ( empty( $transient->checked ) )
            {
                return $transient;
            }
    
            // Get plugin & GitHub release information
            $this->initPluginData();
            $this->getRepoReleaseInfo();
    
            $doUpdate = version_compare( $this->githubAPIResult->tag_name, $transient->checked[$this->slug] );
    
            if ( $doUpdate )
            {
                $package = $this->githubAPIResult->zipball_url;
    
                /*if ( ! empty( $this->accessToken ) )
                {
                    $package = add_query_arg( array( "access_token" => $this->accessToken ), $package );
                }*/
    
                // Plugin object
                $obj = new stdClass();
                $obj->slug = $this->slug;
                $obj->new_version = $this->githubAPIResult->tag_name;
                $obj->url = $this->pluginData["PluginURI"];
                $obj->package = $package;
    
                $transient->response[$this->slug] = $obj;
            }
    
            return $transient;
        }
    
        /**
         * Push in plugin version information to display in the details lightbox
         *
         * @param  boolean $false
         * @param  string $action
         * @param  object $response
         * @return object
         */
        public function setPluginInfo( $false, $action, $response )
        {
            $this->initPluginData();
            $this->getRepoReleaseInfo();
    
            if ( empty( $response->slug ) || $response->slug != $this->slug )
            {
                return $false;
            }
    
            // Add our plugin information
            $response->last_updated = $this->githubAPIResult->published_at;
            $response->slug = $this->slug;
            $response->plugin_name  = $this->pluginData["Name"];
            $response->version = $this->githubAPIResult->tag_name;
            $response->author = $this->pluginData["AuthorName"];
            $response->homepage = $this->pluginData["PluginURI"];
    
            // This is our release download zip file
            $downloadLink = $this->githubAPIResult->zipball_url;
    
            if ( !empty( $this->accessToken ) )
            {
                $downloadLink = add_query_arg(
                    array( "access_token" => $this->accessToken ),
                    $downloadLink
                );
            }
    
            $response->download_link = $downloadLink;
    
            // Load Parsedown
            require_once __DIR__ . DIRECTORY_SEPARATOR . 'parsedown.php';
    
            // Create tabs in the lightbox
            $response->sections = array(
                'Description' 	=> $this->pluginData["Description"],
                'changelog' 	=> class_exists( "Parsedown" )
                    ? Parsedown::instance()->parse( $this->githubAPIResult->body )
                    : $this->githubAPIResult->body
            );
            $response->name = $this->pluginData["Name"];
    
            // Gets the required version of WP if available
            $matches = null;
            preg_match( "/requires:\s([\d\.]+)/i", $this->githubAPIResult->body, $matches );
            if ( ! empty( $matches ) ) {
                if ( is_array( $matches ) ) {
                    if ( count( $matches ) > 1 ) {
                        $response->requires = $matches[1];
                    }
                }
            }
    
            // Gets the tested version of WP if available
            $matches = null;
            preg_match( "/tested:\s([\d\.]+)/i", $this->githubAPIResult->body, $matches );
            if ( ! empty( $matches ) ) {
                if ( is_array( $matches ) ) {
                    if ( count( $matches ) > 1 ) {
                        $response->tested = $matches[1];
                    }
                }
            }
    
            return $response;
        }
    
        /**
         * Perform check before installation starts.
         *
         * @param  boolean $true
         * @param  array   $args
         * @return null
         */
        public function preInstall( $true, $args )
        {
            // Get plugin information
            $this->initPluginData();
    
            // Check if the plugin was installed before...
            $this->pluginActivated = is_plugin_active( $this->slug );
        }
    
        /**
         * Perform additional actions to successfully install our plugin
         *
         * @param  boolean $true
         * @param  string $hook_extra
         * @param  object $result
         * @return object
         */
        public function postInstall( $true, $hook_extra, $result )
        {
            global $wp_filesystem;
    
            // Since we are hosted in GitHub, our plugin folder would have a dirname of
            // reponame-tagname change it to our original one:
            $pluginFolder = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . dirname( $this->slug );
            $wp_filesystem->move( $result['destination'], $pluginFolder );
            $result['destination'] = $pluginFolder;
    
            // Re-activate plugin if needed
            if ( $this->pluginActivated )
            {
                $activate = activate_plugin( $this->slug );
            }
    
            return $result;
        }
    }

//2020-03-22 CB V1.2.6.3 - added new code too display a log of SagePay / PayPal transaction staging and results

    function tfgg_scp_logging_check(){

        if( !function_exists('get_plugin_data') ){
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
        
        $pluginData = get_plugin_data(plugin_dir_path(__FILE__).'tfgg-sunlync-customer-portal.php');        

        if($pluginData['Version']=='1.2.6.3'){
            //only run this is the user is activating v1.2.6.3
            tfgg_scp_upgrade_portal_tables();       
        };

    }  


    function tfgg_scp_upgrade_portal_tables(){

        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->base_prefix}tfgg_card_tran_log`(
            id int(11) auto_increment primary key,
            entrylogged timestamp default current_timestamp,
            cart_id varchar(191) not null,
            clientnumber varchar(10) not null,
            firstname varchar(50) not null,
            lastname varchar(50) not null,
            card_processor varchar(20) not null,
            process varchar(100),
            sandbox tinyint(1) not null default 0,
            result text)$charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        $success = empty($wpdb->last_error);

        if(!$success){
           add_action('admin_notices','tfgg_scp_card_log_error'); 
        }

    }

    function tfgg_scp_card_log_error(){
        ?>
        <div class="update-nag notice">
            <p><?php _e('There was an error generating the card transaction log data for the SunLync portal'); ?></p>
        </div>    
        <?php
    }

    function tfgg_scp_write_card_transaction_log($cart_id, $processor, $process, $isSandbox, $result){
        global $wpdb;
        
        $postResult = http_build_query($result,'',', ');
        
        $wpdb->insert("{$wpdb->base_prefix}tfgg_card_tran_log",array(
            'cart_id' => $cart_id,
            'clientnumber' => $_SESSION['sunlync_client'],
            'firstname' => $_SESSION['sunlync_firstname'],
            'lastname' => $_SESSION['sunlync_lastname'],
            'card_processor' => $processor,
            'process' => $process,
            'sandbox' => $isSandbox,
            'result' => $postResult
        ));
    }

    function tfgg_scp_read_card_transaction_log($clientNumber, $rangeStart, $rangeEnd){
        
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->base_prefix}tfgg_card_tran_log";
        $conditions=array();

        if($clientNumber<>''){
            $sql.=" WHERE clientnumber = %s";
            array_push($conditions,$clientNumber);
        }

        $sql.=" ORDER BY entrylogged ASC LIMIT %d, %d";
        array_push($conditions,$rangeStart,$rangeEnd);

        $results = $wpdb->get_results(
            $wpdb->prepare($sql,$conditions)
        );
        //$results = $wpdb->get_results($sql);

        if($wpdb->num_rows==0){
            return false;
        }else{
            return $results;    
        }
    }
    
    function tfgg_cp_api_employee_login(){
        $url = tfgg_get_api_url();
        $url.='TSunLyncAPI/CIPValidateSecurity//sLoginID/sLoginPass//';

        $url=str_replace('sLoginID',tfgg_cp_hash_password($_POST['data']['user']),$url);
        $url=str_replace('sLoginPass',tfgg_cp_hash_password($_POST['data']['pass']),$url);       

        try{
            $data = tfgg_sunlync_execute_url($url);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
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
            
            exit(json_encode($result));
        }else{
                
            $result["results"]="success";
            $result["response"]="Login successful!";
            $result["data"]=array_slice($data,1,-1);

            $_SESSION['sunlync_employee']['employee_number'] = $data[0]->emp_no;
            $_SESSION['sunlync_employee']['first_name'] = $data[0]->firstname;
            $_SESSION['sunlync_employee']['last_name'] = $data[0]->lastname;

            tfgg_scp_get_emp_stores($_SESSION['sunlync_employee']['employee_number']);

            exit(json_encode($result));
        }
    }
    add_action('wp_ajax_tfgg_cp_api_employee_login','tfgg_cp_api_employee_login');
    add_action('wp_ajax_nopriv_tfgg_cp_api_employee_login','tfgg_cp_api_employee_login');

    function tfgg_scp_api_store_clock_ins($date){
        $url = tfgg_get_api_url();
        $url.='TSunLyncAPI/TFGG_GetStoresClockIns/sStoreList/sDate';

        $url=str_replace('sStoreList',tfgg_scp_get_formatted_employee_storecodes(),$url);
        $url=str_replace('sDate',$_GET['data']['date'],$url);

        /*$url = tfgg_get_api_url();
        $url.='TSunLyncAPI/AWEBEmpStores/sEmpNo';

        $url=str_replace('sEmpNo',$_SESSION['sunlync_employee']['employee_number'],$url);*/

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
		$data=curl_exec($ch);
		curl_close($ch);
		if(($data===FALSE)||($data=='')){
			throw new Exception("ERROR: Invalid URL");
			exit;
        }
        
        $data=str_replace('{"result":[','',$data);
        $data=str_replace(']]}',']',$data);
        $data=json_decode($data);

        $result["results"]="success";
        $result["data"]=array_slice($data,1,-1);

        exit(json_encode($result));

    }
    add_action('wp_ajax_tfgg_scp_api_store_clock_ins','tfgg_scp_api_store_clock_ins');
    add_action('wp_ajax_nopriv_tfgg_scp_api_store_clock_ins','tfgg_scp_api_store_clock_ins');

    function tfgg_scp_get_emp_stores($employeenumber){
        //AWEBEmpStores
        $url = tfgg_get_api_url();
        $url.='TSunLyncAPI/CIPAdminEmployeeStores/sEmpNo';

        $url=str_replace('sEmpNo',$employeenumber,$url);

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
            array_pop($data);//remove the request id

            $_SESSION['sunlync_employee']['storelist'] = array();//initialize the storelist array
            foreach($data as $store){
    
                $storeInfo = array("storecode"=>$store->storecode,
                "location"=>$store->storeloc);
                
                array_push($_SESSION['sunlync_employee']['storelist'],$storeInfo);
            }        
        }
    }

    function tfgg_cp_set_sunlync_employee($employeenumber){
        $_SESSION['sunlync_employee'] = $employeenumber;
    }

    function tfgg_scp_get_formatted_employee_storecodes(){
        $storelist="";
        foreach($_SESSION['sunlync_employee']['storelist'] as $store){
            $storelist.='"'.$store["storecode"].'",';
        }
        return substr($storelist,0,-1);//remove the last comma
    }

    function tfgg_cp_employee_dashboard_logout(){
        tfgg_cp_unset_sunlync_employee();
        
        $result["logout"]=site_url();//possible configurable option
        exit(json_encode($result));
        
    }
    add_action( 'wp_ajax_tfgg_cp_employee_dashboard_logout', 'tfgg_cp_employee_dashboard_logout' );
    add_action( 'wp_ajax_nopriv_tfgg_cp_employee_dashboard_logout', 'tfgg_cp_employee_dashboard_logout' );

    function tfgg_cp_unset_sunlync_employee(){
        unset($_SESSION['sunlync_employee']);
    }
    add_filter('wp_nav_menu_items', 'tfgg_add_logout_employee_link', 10, 2 );

    function tfgg_add_logout_employee_link($items, $args){
        //add a logout link to the small menu bar (secondary-menu) at the top of the screen
        if(tfgg_is_sunlync_emplopyee_logged_in() && $args->theme_location=='secondary-menu'){
          $items .='<li><a href="" onclick="endEmplopyeeDashboardSession();">Log Out</a></li>';  
        }
        return $items;
    }

    function tfgg_cp_get_sunlync_employee(){
        if(array_key_exists('sunlync_employee',$_SESSION)){
            return $_SESSION['sunlync_employee']['employee_number'];
        }else{
            return false;
        }

    }

    function tfgg_is_sunlync_emplopyee_logged_in(){
        if(!tfgg_cp_get_sunlync_employee()){
            return false;
        }else{
            return true;
        }    
    }

?>