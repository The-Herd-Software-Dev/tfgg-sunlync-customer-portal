<?php

    function tfgg_scp_add_registration_dialogs_to_footer(){
        echo'<div class="modal fade bd-modal-lg" id="modal-tfgg-scp-validation-results" tabindex="-1" role="dialog" 
                    aria-labelledby="model-tfgg-scp-results-center-title" aria-hidden="true" data-backdrop="false" 
                    style="background-color: rgba(0, 0, 0, 0.5);">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header tfgg_bg_color">
                                <h5 class="modal-title" id="modal-tfgg-scp-validation-results-header">Registration Errors</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="modal-tfgg-scp-validation-results-body">
                                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade bd-modal-lg" id="modal-tfgg-scp-tandc" tabindex="-1" role="dialog" 
                    aria-labelledby="model-tfgg-scp-results-center-title" aria-hidden="true" 
                    data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header tfgg_bg_color">
                                <h5 class="modal-title" id="modal-tfgg-scp-tandc-header">Terms & Conditions</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="modal-tfgg-scp-tandc-body">'.
                            get_post_field('post_content', url_to_postid( site_url(get_option('tfgg_scp_tandc_slug_instore')) )).
                            '  
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" data-dismiss="modal" onclick="setCheckBoxState(\'tfgg_scp_registration_tandc\',true);">Agree</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal"  onclick="setCheckBoxState(\'tfgg_scp_registration_tandc\',false);">Disagree</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade bd-modal-lg" id="modal-tfgg-scp-marketing" tabindex="-1" role="dialog" 
                    aria-labelledby="model-tfgg-scp-results-center-title" aria-hidden="true" 
                    data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header tfgg_bg_color">
                                <h5 class="modal-title" id="modal-tfgg-scp-marketing-header">Marketing Information</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="modal-tfgg-scp-marketing-body">'.
                            get_post_field('post_content', url_to_postid( site_url(get_option('tfgg_scp_marketing_slug_instore')) )).
                            '   
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" data-dismiss="modal" onclick="setCheckBoxState(\'tfgg_scp_registration_marketing\',true);">I would like to receive marketing information</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="setCheckBoxState(\'tfgg_scp_registration_marketing\',false);">No thank-you</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade bd-modal-lg" id="modal-tfgg-scp-skintypes" tabindex="-1" role="dialog" 
                aria-labelledby="model-tfgg-scp-results-center-title" aria-hidden="true" 
                data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header tfgg_bg_color">
                            <h5 class="modal-title" id="modal-tfgg-scp-skintypes-header">Skin Type Information</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="modal-tfgg-scp-skintypes-body">'.
                            get_post_field('post_content', url_to_postid( site_url(get_option('tfgg_scp_skin_type_info_slug_instore')) )).
                    '  
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
            <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>';
        
    }
    

    function tfgg_scp_registration_instore_set_cookie_display(){
        ob_start();

        $storeList = json_decode(tfgg_api_get_reg_stores(false));
        if(StrToUpper($storeList->results)==='SUCCESS'){
        	$storeList = $storeList->stores;	
		}
    ?>
        <style>
        .btn{
            white-space:normal;
        }
        <?php
        if(!$instoreReg){
        ?>
        .form-control{
            line-height:2;
        }
        <?php
        }else{
            //instore reg
        ?>
        button.tfgg_bg_color{
            line-height:2;
        }

        .checkbox-xl .custom-control-label::before, 
        .checkbox-xl .custom-control-label::after {
        top: 1.2rem;
        width: 3rem;
        height: 3rem;
        }

        .checkbox-xl .custom-control-label {
        padding-top: 2rem;
        padding-left: 2.9rem;
        }
        <?php
        }
        ?>
        .row{
            padding-bottom: 0.5em;
        }
        select{
            border: 1px solid #bbb !important;
        }
        .reg_error, .reg_alert{
            color:red;
        }
        .reg_alert{
            display:none;
        }
        input.reg_error{
            border-color:red;
        }
        div.reg_alert{
            margin-top:0.5em;
        }
        .tfgg_bg_color{
            background-color: #fcb040;
        }

        .modal.fade{
            z-index: 100000000 !important;
        }
        </style>

    <?php
        tfgg_sunlync_cp_show_error_messages();
    ?>
        <form id="tfgg_scp_instore_registration_store" method="POST">
            <div id="tfgg_scp_reg_main_content" class="container-fluid">
                <div class="form-row">
                    <h4>Default Registration Store</h4>
                </div>
                <div class="form-row">
                    <div class="form-group <?php echo $col; ?>">
                        <div class="form-row">
                            <div class="form-group col-12 <?php echo $inputGrp;?>">
                                <label for="tfgg_scp_registration_cookie_store" id="tfgg_scp_registration_cookie_store_label">Please set the store that this device will be registering customers under</label>
                                <select class="form-control <?php echo $frmControlSize;?>" id="tfgg_scp_registration_cookie_store" name="tfgg_scp_registration_cookie_store" onchange="checkForDisplayWarning();" required>
                                    <option></option>
                                <?php
                                    foreach($storeList as &$details){
										//2019-07-19 CB - added strpos check to remove 'CLOSED'/'DELETED' stores
										if((!strpos(StrToUpper($details->store_loc),'CLOSED'))&&
										(!strpos(StrToUpper($details->store_loc),'DELETED'))){
											echo '<option value="'.$details->store_id.'">'.$details->store_loc.'</option>';
										}
									}
                                ?>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="tfgg_scp_registration_cookie_nonce" id="tfgg_scp_registration_cookie_nonce" value="<?php echo wp_create_nonce('tfgg-scp-registration-cookie-nonce'); ?>"/>
                        <div class="form-row">
                            <div class="form-group col-12 reg_alert" id="reg_alert_for_tfgg_scp_registration_cookie_store"></div>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col">
                        <center>
                        <button type="submit" class="btn tfgg_bg_color" style="width: 75%; font-weight: 700">Set Device Store</button>
                        </center>
                    </div>
                </div>
            </div>
        </form>
    <?php
        return ob_get_clean();
    }

    function tfgg_scp_registration_instore_set_cookie(){
        if((array_key_exists('tfgg_scp_registration_cookie_nonce',$_POST))&&
        (wp_verify_nonce($_POST['tfgg_scp_registration_cookie_nonce'],'tfgg-scp-registration-cookie-nonce'))){
            setcookie('instore_reg_store',$_POST['tfgg_scp_registration_cookie_store'],2147483647);
			wp_redirect($_SERVER['REQUEST_URI']);
			exit;
        }
    }
    add_action('init','tfgg_scp_registration_instore_set_cookie');

    function tfgg_scp_registration($instoreReg = true){

        if((array_key_exists('tfgg_reg_resp',$_SESSION))&&(isset($_SESSION['tfgg_reg_resp']))){
			switch($_SESSION['tfgg_reg_resp']['fail_code']){
				case 'me':
					//multi-email
					$reg_email = $_SESSION['tfgg_reg_resp']['attempted_email'];
					unset($_SESSION['tfgg_reg_resp']);
					return reg_form_multi_email_display($reg_email);
				break;
				case 'ee':
					//existing email
					$reg_email = $_SESSION['tfgg_reg_resp']['attempted_email'];
					unset($_SESSION['tfgg_reg_resp']);
					return reg_form_existing_email_display($reg_email);
				break;
				case 'md':
					//multiple demographics
					unset($_SESSION['tfgg_reg_resp']);
					return reg_form_multi_demo_display();
				break;
				case 'ed':
					$reg_email = $_SESSION['tfgg_reg_resp']['rtnd_demo']->email;
					unset($_SESSION['tfgg_reg_resp']);
					return reg_form_single_demo_diff_email_display($reg_email);
					break;
				default:
					unset($_SESSION['tfgg_reg_resp']);
					return tfgg_scp_display_registration($instoreReg);	
					break;
			}
		}else{
			return tfgg_scp_display_registration($instoreReg);	
		}
    }

    function reg_form_multi_email_display($reg_email){
		ob_start(); 

		$cs_email = get_option('tfgg_scp_customer_service_email');
	?>
		<div class="card alert-warning">
			<div class="card-header">
				Multiple Accounts Found
			</div>
			<div class="card-body">
				<h5 class="card-title">There appres to be an issue with your registration</h5>
				<p class="card-text">The email address you are attempting to use, <?php echo $reg_email; ?>, is associated with multiple accounts</p>
				<p class="card-text">Please contact our customer service representatives at <a href="mailto:<?php echo $cs_email; ?>?subject=Registration Issues (Non-Unique EMail)"><?php echo $cs_email; ?></a> for assistance with identifying your account</p>
				<p class="card-text">Please include the following information to assist with locating the correct account: <br/>
				<ul>
					<li>Full first name</li>
					<li>Full last name</li>
					<li>Your date of birth</li>
					<li>Your registration email</li>
				</ul>
				</p>
			</div>
		</div>
		<br/>
	<?php
		return ob_get_clean();
	}

	function reg_form_existing_email_display($reg_email){
		ob_start(); 

		$forgot = get_site_url().'/'.tfgg_scp_remove_slashes(get_option('tfgg_scp_cplogin_page'))."?login=reset";
	?>
		<div class="card">
			<div class="card-header alert-info">
				Registration E-Mail Exists
			</div>
			<div class="card-body">
				<p class="card-text">The email address you are attempting to register with, <?php echo $reg_email; ?>, is already associated with an account</p>
				<p class="card-text">Please use the <a href="<?php echo $forgot;?>">Forgot Password Form</a> to request a new password for that account</p>
			</div>
		</div>
		<br/>
	<?php
		return ob_get_clean();
	}

	function reg_form_multi_demo_display(){
		ob_start(); 
		$cs_email = get_option('tfgg_scp_customer_service_email');
	?>
		<div class="card alert-warning">
			<div class="card-header">
				Multiple Accounts Found
			</div>
			<div class="card-body">
				<h5 class="card-title">There appears to be an issue with your registration</h5>
				<p class="card-text">The information you are attempting to register with is associated with multiple accounts</p>
				<p class="card-text">Please contact our customer service representatives at <a href="mailto:<?php echo $cs_email; ?>?subject=Registration Issues (Multiple Accounts)"><?php echo $cs_email; ?></a> for assistance with your registration</p>
				<p class="card-text">Please include the following information to assist with locating the correct account: <br/>
				<ul>
					<li>Full first name</li>
					<li>Full last name</li>
					<li>Your date of birth</li>
					<li>Your registration email</li>
				</ul>
				</p>
			</div>
		</div>
		<br/>
	<?php
		return ob_get_clean();
	}

	function obfuscate_email($email){
		$em = explode("@",$email);
		$len = strlen($em[0]);
		$name = substr($em[0],0,1).str_repeat('*',$len)."@".$em[1];
		return $name;
	}

	function reg_form_single_demo_diff_email_display($reg_email){
		ob_start(); 
		$cs_email = get_option('tfgg_scp_customer_service_email');
		$forgot = get_site_url().'/'.tfgg_scp_remove_slashes(get_option('tfgg_scp_cplogin_page'))."?login=reset";


	?>
		<div class="card alert-warning">
			<div class="card-header">
				EMail Conflict
			</div>
			<div class="card-body">
				<h5 class="card-title">There appears to be an issue with your registration</h5>
				<p class="card-text">The information you are attempting to register with is associated with an account that has a different email: <?php echo obfuscate_email($reg_email);?></p>
				<p class="card-text">If this email looks familiar, please use the <a href="<?php echo $forgot;?>">Forgot Password Form</a> to request a new password for that account</p>
				<p class="card-text">Otherwise, contact our customer service representatives at <a href="mailto:<?php echo $cs_email; ?>?subject=Registration Issues (EMail Conflict)"><?php echo $cs_email; ?></a> for assistance with your registration</p>
				<p class="card-text">Please include the following information to assist with locating the correct account: <br/>
				<ul>
					<li>Full first name</li>
					<li>Full last name</li>
					<li>Your date of birth</li>
					<li>Your registration email</li>
				</ul>
				</p>
			</div>
		</div>
		<br/>
	<?php
		return ob_get_clean();
	}

    function tfgg_scp_display_registration($instoreReg = true){
        ob_start(); 

        global $post;
		$post_slug = $post->post_name;

        $howHearOptions = array('Website',
        'Flyer',
        'Advert',
        'Email',
        'Seen Store',
        'Social Media',
        'Word of Mouth',
        'Ad Banner');

        //set up some specific css classes
        if($instoreReg){
            $col = 'col-xl-12';
            $inputGrp = 'input-group-lg';
            $frmControlSize = 'form-control-lg';
        }else{
            $col = 'col-md-6';
            $inputGrp='';
            $frmControlSize = '';
        }

        //get the stores
        $storeList = json_decode(tfgg_api_get_reg_stores(!$instoreReg));
        if(StrToUpper($storeList->results)==='SUCCESS'){
			$storeList = $storeList->stores;
			$selectedStoreCode='';
            $selectedStore='';
        }

        if($instoreReg){
            $selectedStoreCode = $_COOKIE['instore_reg_store'];
            foreach($storeList as &$details){
                if($selectedStoreCode == $details->store_id){
                    $selectedStore=$details->store_loc;
                }
            }
        }else{
            //check to see if we need to validate a store specific 
            if(str_replace('/','',get_option('tfgg_scp_cpnewuser_page'))!=$post_slug){
                //we are on a store specific page, maybe

				$regStoreSlugs = (array)get_option('tfgg_scp_store_reg_slugs');
				$storeSlugKey = array_search($post_slug,$regStoreSlugs);

                if(($storeSlugKey!='')&&($storeSlugKey!=FALSE)){
					foreach($storeList as &$details){
						if((!strpos(StrToUpper($details->store_loc),'CLOSED'))&&
						(!strpos(StrToUpper($details->store_loc),'DELETED'))){
							if($storeSlugKey==$details->store_id){
								$selectedStore=$details->store_loc;
                                $selectedStoreCode = $details->store_id;
							}
						}
					}
				}
            }
        }

        //get the skin types
        $skintypes = json_decode(tfgg_api_get_skintypes());
		if(strtoupper($skintypes->results)==='SUCCESS'){
			$skintypes = $skintypes->skintypes;
		}
    ?>
        <style>
        .btn{
            white-space:normal;
        }
        <?php
        if(!$instoreReg){
        ?>
        select, .form-control {
            height:2.2em !important;
        }
        <?php
        }else{
            //instore reg
        ?>
        button.tfgg_bg_color{
            line-height:2;
        }

        .checkbox-xl .custom-control-label::before, 
        .checkbox-xl .custom-control-label::after {
        top: 1.2rem;
        width: 3rem;
        height: 3rem;
        }

        .checkbox-xl .custom-control-label {
        padding-top: 2rem;
        padding-left: 2.9rem;
        }
        <?php
        }
        ?>
        .row{
            padding-bottom: 0.5em;
        }
        select{
            border: 1px solid #bbb !important;
        }
        .reg_error, .reg_alert{
            color:red;
        }
        .reg_alert{
            display:none;
        }
        input.reg_error, select.reg_error{
            border-color:red;
        }
        div.reg_alert{
            /*margin-top:0.5em;*/
        }
        .tfgg_bg_color{
            background-color: #fcb040;
        }

        .modal.fade{
            z-index: 100000000 !important;
        }
        </style>
        <?php
            tfgg_sunlync_cp_show_error_messages(); 
        ?>
        <form method="POST" autocomplete="off" id="tfgg_scp_registration">
            <div id="tfgg_scp_reg_main_content" class="container-fluid">
                <div class="form-row">
                <h4>Personal Information</h4>
                </div>
                <div class="form-row">
                    <div class="form-group <?php echo $col; ?>">
                        <div class="form-row">
                            <div class="form-group col-12 <?php echo $inputGrp;?>">
                                <label for="tfgg_scp_registration_store" id="tfgg_scp_registration_store_label">
                                <?php echo($instoreReg?'Registration Store':'Which store will you use most frequently?'); ?>
                                </label>
                                <?php
                                if(($selectedStore!='')&&($selectedStoreCode!='')){
                                    //registration for a specific page, do not allow user to change selection
                                ?>
                                <input class="form-control <?php echo $frmControlSize;?>" type="text" value="<?php echo $selectedStore;?>" disabled/>
                                <input type="hidden" value="<?php echo $selectedStoreCode;?>" id="tfgg_scp_registration_store" name="tfgg_scp_registration_store"/>
                                <?php
                                }else{
                                    //user has the ability to select which store they are registering with
                                ?>
                                <select class="form-control <?php echo $frmControlSize;?>" id="tfgg_scp_registration_store" 
                                name="tfgg_scp_registration_store" onchange="checkForDisplayWarning();" required>
                                    <option value="">Please Select...</option>
                                <?php
                                    foreach($storeList as &$details){
										//2019-07-19 CB - added strpos check to remove 'CLOSED'/'DELETED' stores
										if((!strpos(StrToUpper($details->store_loc),'CLOSED'))&&
										(!strpos(StrToUpper($details->store_loc),'DELETED'))){
                                            echo '<option value="'.$details->store_id.'">'.$details->store_loc.'</option>';
										}
									}
                                ?>
                                </select>
                                <?php
                                }//if..else
                                ?>
                                
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 reg_alert" id="reg_alert_for_tfgg_scp_registration_store"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 <?php echo $inputGrp;?>">
                                <label for="tfgg_scp_registration_firstname" id="tfgg_scp_registration_firstname_label">Firstname</label>
                                <input class="form-control <?php echo $frmControlSize;?>" type="text" name="tfgg_scp_registration_firstname" id="tfgg_scp_registration_firstname" required/>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 reg_alert" id="reg_alert_for_tfgg_scp_registration_firstname"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 <?php echo $inputGrp;?>">
                                <label for="tfgg_scp_registration_lastname" id="tfgg_scp_registration_lastname_label">Lastname</label>
                                <input class="form-control <?php echo $frmControlSize;?>" type="text" name="tfgg_scp_registration_lastname" id="tfgg_scp_registration_lastname" required />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 reg_alert" id="reg_alert_for_tfgg_scp_registration_lastname"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 <?php echo $inputGrp;?>">
                                <label for="tfgg_scp_registration_email" id="tfgg_scp_registration_email_label">E-mail</label>
                                <input class="form-control <?php echo $frmControlSize;?>" type="email" name="tfgg_scp_registration_email" id="tfgg_scp_registration_email" required />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 reg_alert" id="reg_alert_for_tfgg_scp_registration_email"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 <?php echo $inputGrp;?>">
                                <label for="tfgg_scp_registration_email_confirm" id="tfgg_scp_registration_email_confirm_label">Confirm E-mail</label>
                                <input class="form-control <?php echo $frmControlSize;?>" type="email" name="tfgg_scp_registration_email_confirm" id="tfgg_scp_registration_email_confirm" required />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 reg_alert" id="reg_alert_for_tfgg_scp_registration_email_confirm"></div>
                        </div>
                    </div>
                    <div class="form-group <?php echo $col; ?>">
                        <div class="form-row">
                            <div class="form-group col-md-8 col-xs-12 <?php echo $inputGrp;?>">
                                <label for="tfgg_scp_registration_street" id="tfgg_scp_registration_street_label">House Number</label>
                                <input class="form-control <?php echo $frmControlSize;?>" type="text" name="tfgg_scp_registration_street" id="tfgg_scp_registration_street" required />
                            </div>
                            <div class="form-group col-md-4 col-xs-12 <?php echo $inputGrp;?>">
                                <label for="tfgg_scp_registration_postcode" id="tfgg_scp_registration_postcode_label">Post Code</label>
                                <input class="form-control <?php echo $frmControlSize;?>" type="text" name="tfgg_scp_registration_postcode" id="tfgg_scp_registration_postcode" required />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-8 col-xs-12 <?php echo $inputGrp;?> reg_alert" id="reg_alert_for_tfgg_scp_registration_street"></div>
                            <div class="form-group col-md-4 col-xs-12 <?php echo $inputGrp;?> reg_alert" id="reg_alert_for_tfgg_scp_registration_postcode"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 <?php echo $inputGrp;?>">
                                <label for="tfgg_scp_registration_mobile_number" id="tfgg_scp_registration_mobile_number_label">Mobile Number</label>
                                <input class="form-control <?php echo $frmControlSize;?>" type="text" name="tfgg_scp_registration_mobile_number" id="tfgg_scp_registration_mobile_number" required />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 reg_alert" id="reg_alert_for_tfgg_scp_registration_mobile_number"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6  col-xs-12 <?php echo $inputGrp;?>">
                                <label for="tfgg_scp_registration_dob_mth" id="tfgg_scp_registration_dob_mth_label">Date of Birth</label>
                                <select class="form-control <?php echo $frmControlSize;?>" name="tfgg_scp_registration_dob_mth" id="tfgg_scp_registration_dob_mth" onchange="populateMonthDays();" required>
                                    <option value=""></option>
                                    <option value="01">January</option>
                                    <option value="02">February</option>
                                    <option value="03">March</option>
                                    <option value="04">April</option>
                                    <option value="05">May</option>
                                    <option value="06">June</option>
                                    <option value="07">July</option>
                                    <option value="08">August</option>
                                    <option value="09">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                            <div class="form-group col-xs-5 col-md-3 <?php echo $inputGrp;?>">
                                <label for="tfgg_scp_registration_dob_day">&nbsp;</label>
                                <select class="form-control <?php echo $frmControlSize;?>" name="tfgg_scp_registration_dob_day" id="tfgg_scp_registration_dob_day" onchange="validateDOBAge()" disabled required>
                                    
                                </select>
                            </div>
                            <div class="form-group col-xs-5 col-md-3 <?php echo $inputGrp;?>">
                                <label for="tfgg_scp_registration_dob_year">&nbsp;</label>
                                <select class="form-control <?php echo $frmControlSize;?>" name="tfgg_scp_registration_dob_year" id="tfgg_scp_registration_dob_year" onchange="validateDOBAge()"required>
                                    <option value=""></option>
                                    <?php
                                        $year = date("Y")-14;
                                        for($i=date("Y")-14; $i>=date("Y")-114; $i--){
                                            echo '<option value="'.$i.'">'.$i.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 reg_alert" id="reg_alert_for_tfgg_scp_registration_dob_mth"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12  <?php echo $inputGrp;?>">
                                <label for="tfgg_scp_registration_gender" id="tfgg_scp_registration_gender_label">Gender</label>
                                <select class="form-control <?php echo $frmControlSize;?>" name="tfgg_scp_registration_gender" id="tfgg_scp_registration_gender" required >
                                    <option value="">Please Select...</option>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 reg_alert" id="reg_alert_for_tfgg_scp_registration_gender"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12  <?php echo $inputGrp;?>">
                                <label for="tfgg_scp_registration_skin_type" id="tfgg_scp_registration_skin_type_label">Skin Type</label>
                                <select class="form-control <?php echo $frmControlSize;?>" name="tfgg_scp_registration_skin_type" id="tfgg_scp_registration_skin_type" onchange="evaluateSkinTypeWarning(this);" required>
                                    <option value="">Please Select...</option>
                                    <?php
                                    foreach($skintypes as &$details){
										echo '<option value="'.$details->type.'">'.$details->description.'</option>';	
									}
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 reg_alert" id="reg_alert_for_tfgg_scp_registration_skin_type"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 col align-self-end">
                                <center>
                                <button type="button" class="btn tfgg_bg_color" id="tfgg_scp_skin_type_display_info" style="width:75%; font-weight: 700" onclick="showSkinTypingInfo()">Click here for more information on skin types</button>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-row">
                    <div class="form-group <?php echo $col; ?>">
                        <div class="form-row">
                            <h4>Password</h4>
                        </div> 
                        <div class="form-row">
                            <div class="form-group col-md-8 col-sm-12">
                                <?php echo get_option('tfgg_scp_instore_reg_password_hint'); ?>
                            </div>
                        </div>                        
                        <div class="form-row">
                            <div class="form-group col-12  <?php echo $inputGrp;?>">
                                <label for="tfgg_scp_registration_password" id="tfgg_scp_registration_password_label">Password</label>
                                <input onclick="scrollInputIntoView(this);" class="form-control <?php echo $frmControlSize;?>" type="password" name="tfgg_scp_registration_password" id="tfgg_scp_registration_password" required autocomplete="new-password" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 reg_alert" id="reg_alert_for_tfgg_scp_registration_password"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12  <?php echo $inputGrp;?>">
                                <label for="tfgg_scp_registration_password_confirm" id="tfgg_scp_registration_password_confirm_label">Confirm Password</label>
                                <input onclick="scrollInputIntoView(this);" class="form-control <?php echo $frmControlSize;?>" type="password" name="tfgg_scp_registration_password_confirm" id="tfgg_scp_registration_password_confirm" required autocomplete="new-password" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 reg_alert" id="reg_alert_for_tfgg_scp_registration_password_confirm"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 col align-self-end">
                                <center>
                                <button type="button" class="btn tfgg_bg_color" id="tfgg_scp_registration_togglePassVis" style="width:75%; font-weight: 700" onclick="togglePassVis()">Show</button>
                                </center>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="tfgg_scp_registration_nonce" id="tfgg_scp_registration_nonce" value="<?php echo wp_create_nonce('tfgg-scp-registration-nonce'); ?>"/>
                    <input type="hidden" name="tfgg_scp_registration_user_defined_2" id="tfgg_scp_registration_user_defined_2" value=""/>
                    <input type="text" name="tfgg_scp_registration_user_password_reenter" id="tfgg_scp_registration_user_password_reenter" style="display:none !important" tabindex="-1" autocomplete="off"/>
                    <input type="hidden" name="tfgg_scp_registration_source_slug" id="tfgg_scp_registration_source_slug" value="<?php echo $post_slug;?>"/>
                    <input type="hidden" name="tfgg_scp_recaptcha_site_key" value="<?php echo get_option('tfgg_scp_recaptcha_site_key'); ?>" style="display:none !important" />
                    <input type="hidden" name="recaptcha_response" id="recaptchaResponse" autocomplete="off"/>
                    <input type="hidden" name="tfgg_scp_registration_instore" id="tfgg_scp_registration_instore" value="<?php echo ($instoreReg?'1':'0');?>" autocomplete="off"/>
                    <div class="form-group  <?php echo $col; ?>">
                        <div class="form-row">
                            <h4>Please Read Carefully</h4>
                        </div>
                        <div class="form-row">
                            <div class="custom-control custom-checkbox checkbox-xl">
                                <input type="checkbox" class="custom-control-input" name="tfgg_scp_registration_tandc" id="tfgg_scp_registration_tandc" required >
                                <label class="custom-control-label" onclick="showTandC();"><?php echo ($instoreReg ? get_option('tfgg_scp_tandc_label_instore'):get_option('tfgg_scp_tandc_label')); ?></label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 reg_alert" id="reg_alert_for_tfgg_scp_registration_tandc"></div>
                        </div>
                        <div class="form-row">
                            <div class="custom-control custom-checkbox checkbox-xl">
                                <input type="checkbox" class="custom-control-input" name="tfgg_scp_registration_marketing" id="tfgg_scp_registration_marketing" >
                                <label class="custom-control-label" onclick="showMarketing();"><?php echo ($instoreReg ? get_option('tfgg_scp_marketing_optin_label_instore'):get_option('tfgg_scp_marketing_optin_label')); ?></label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 reg_alert" id="reg_alert_for_tfgg_scp_registration_marketing"></div>
                        </div>
                        <div class="form-row">
                            <div class="custom-control custom-checkbox checkbox-xl">
                                <input type="checkbox" class="custom-control-input" name="tfgg_scp_registration_skin_type_confirm" id="tfgg_scp_registration_skin_type_confirm" required >
                                <label class="custom-control-label" for="tfgg_scp_registration_skin_type_confirm"><?php _e('I hereby certify that the skin type selected is accurate'); ?></label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 reg_alert" id="reg_alert_for_tfgg_scp_registration_skin_type_confirm"></div>
                        </div>
                        <div class="form-row" style="display:none" id="tfgg_scp_chiswick_warning">
                            <div class="form-group col-12">
                                <br/>
                                <strong>DO NOT USE UV TANNING EQUIPMENT IF YOU...</strong>
                                <ul>
                                <li>Are under 18</li>
                                <li>Suffer from: ill effects from normal sunbathing, epilepsy, giddiness or fainting, headaches or migraine, heart condition, blood pressure, hypertension, prickly heat, cold sores, allergies, skin ulcers or raised/ multiple moles?</li>
                                <li>Are: under medical supervision, prescribed or taking any form of drug, ointment/lotion, antibiotic or tranquilliser, diabetic, pregnant or hypersensitive to light?</li>
                                <li>Have had: hot waxing, tattooing, bleaching, laser hair removal or electrolysis in the past 24 hours?</li>
                                <li>Currently have, or recently have been treated for, skin cancer</li>
                                <li>Have a large number of moles, freckles or have red hair</li>
                                <li>Are taking any photosensitising medication</li>
                                </ul>
                                <strong>DO NOT USE SPRAY TANNING EQUIPMENT IF YOU...</strong>
                                <p>You are in your first trimester of pregnancy. If you are pregnant and not in your first trimester please take care when using our booths as the floor may be slippery.</p>
                                <br/>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 reg_alert" id="reg_alert_for_tfgg_scp_chiswick_warning"></div>
                        </div>
                        <div class="form-row" <?php echo ($instoreReg?'style="margin-top: 30px;"':'');?> >
                            <div class="form-group col-12  <?php echo $inputGrp;?>">
                                <label for="tfgg_scp_registration_how_hear" id="tfgg_scp_registration_how_hear_label">How did you hear about us?</label>
                                <select class="form-control <?php echo $frmControlSize;?>" name="tfgg_scp_registration_how_hear" id="tfgg_scp_registration_how_hear" required>
                                    <option value="">Please Select...</option>
                                    <?php
                                    foreach($howHearOptions as &$option){
										echo '<option value="'.$option.'">'.$option.'</option>';	
									}
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 reg_alert" id="reg_alert_for_tfgg_scp_registration_how_hear"></div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-row">
                    <div class="form-group col">
                        <center>
                            <img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/loading.gif" id="tfgg_scp_registration_busy" style="width: 40px; display:none"/>
                        </center>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col">
                        <center>
                        <button type="submit" class="btn tfgg_bg_color" id="tfgg_scp_registration_submit_button" style="width: 75%; font-weight: 700" onclick="validateRegistrationData(<?php echo ($instoreReg?'true':'false');?>)">Register Your Account</button>
                        </center>
                    </div>
                </div>
            </div>
        </form>
        <script>
            <?php
            if(!$instoreReg){
            ?>
            jQuery(document).ready(function () {
                jQuery("#tfgg_scp_registration_store").select2();
            });

            jQuery('#tfgg_scp_registration_store').on('select2:select', function (e) {
                clearElementAlert(this);
            });
            <?php
            }
            ?>
            var inputs = document.querySelectorAll('input[type=text], input[type=email], input[type=password]');
            for(var i=0; i<inputs.length; i++){
                inputs[i].addEventListener('keyup',function(){
                    clearElementAlert(this);
                },false);
            }
            
            var selects = document.querySelectorAll('select');
            for(var i=0; i<selects.length; i++){
                if(selects[i].id!='tfgg_scp_registration_skin_type'){
                    selects[i].addEventListener('change',function(){
                        clearElementAlert(this);
                    },false);
                }
            }

            function clearElementAlert(el){
                el.classList.remove('reg_error');
                var elAlert=document.getElementById('reg_alert_for_'+el.id);
                if(elAlert!=null){
                    elAlert.innerHTML='';
                }
            }

            function scrollInputIntoView(el){
                const label = document.getElementById(el.id+'_label');
                label.scrollIntoView();
            }

            function resetValidation(){
                document.getElementById('modal-tfgg-scp-validation-results-body').innerHTML='';
                var alerts = document.getElementsByClassName('reg_alert');
                for(var i=0; i<alerts.length; i++){
                    alerts[i].innerHTML='';
                }
                document.getElementById('tfgg_scp_registration_busy').style.display='block';
            }

            function validateRegistrationData(inStore){
                event.preventDefault();
                document.getElementById('tfgg_scp_registration_submit_button').disabled=true;
                document.getElementById('tfgg_scp_registration_submit_button').innerHTML='Validating';
                resetValidation();
                var resultMsg='Please correct these errors to continue:<ul>';
                var allowSubmit = true;

                //check all the required fields have been filled in
                var requiredElements = document.getElementById('tfgg_scp_registration').querySelectorAll('[required]');
                for(var i=0; i<requiredElements.length; i++){
                    requiredElements[i].value = requiredElements[i].value.trim();
                    if(requiredElements[i].value==''){
                        allowSubmit = false;
                        var label='';
                        switch(requiredElements[i].id){
                            case 'tfgg_scp_registration_tandc':
                                label = 'Terms and conditions';
                            break;
                            case 'tfgg_scp_registration_skin_type_confirm':
                                label = 'Skin Type Confirmation';
                            break;
                            default:
                                label = document.getElementById(requiredElements[i].id+'_label');
                                //console.log(label.textContent);
                                if((label!='undefined')&&(label!=null)&&(label!='null')){
                                    label=label.textContent.trim();
                                }
                            break;
                        }
                        if((label!='')&&(label!='undefined')&&(label!=null)&&(label!='null')){
                            resultMsg+='<li> - "'+label+'" is required';
                            var thisAlert = document.getElementById('reg_alert_for_'+requiredElements[i].id);
                            thisAlert.innerHTML+=label+' is required';
                            thisAlert.style.display='block';
                        }
                    }
                }
                
                if(!isEmail(document.getElementById('tfgg_scp_registration_email').value)){
                    allowSubmit = false;
                    resultMsg+='<li> - Invalid Email</li>';
                    var thisAlert = document.getElementById('reg_alert_for_tfgg_scp_registration_email');
                    if(thisAlert.innerHTML!=''){ thisAlert.innerHTML+='<br/>';}
                    thisAlert.innerHTML+='Invalid Email';
                    thisAlert.style.display='block';
                }

                if(document.getElementById('tfgg_scp_registration_email').value.toLowerCase() != 
                document.getElementById('tfgg_scp_registration_email_confirm').value.toLowerCase()){
                    allowSubmit = false;
                    resultMsg+='<li> - Confirmation Email does not match</li>';
                    var thisAlert = document.getElementById('reg_alert_for_tfgg_scp_registration_email_confirm');
                    if(thisAlert.innerHTML!=''){ thisAlert.innerHTML+='<br/>';}
                    thisAlert.innerHTML+='Confirmation Email does not match';
                    thisAlert.style.display='block';
                }

                //strip all the whitespace from mobile number
                var mob=document.getElementById('tfgg_scp_registration_mobile_number').value;
                mob=mob.replace(/\s/g,'');
                mob=mob.replace(/\D/g,'');
                document.getElementById('tfgg_scp_registration_mobile_number').value=mob;
                if(!isValidMobileNumber(mob)){
                    allowSubmit = false;
                    resultMsg+='<li> - Mobile nnumber is invalid</li>';
                    var thisAlert = document.getElementById('reg_alert_for_tfgg_scp_registration_mobile_number');
                    if(thisAlert.innerHTML!=''){ thisAlert.innerHTML+='<br/>';}
                    thisAlert.innerHTML+='Mobile nnumber is invalid';
                    thisAlert.style.display='block';
                }

                if(!isValidPass(document.getElementById('tfgg_scp_registration_password').value)){
                    allowSubmit = false;
                    resultMsg+='<li> - Password does not meet minimum requirements</li>';
                    var thisAlert = document.getElementById('reg_alert_for_tfgg_scp_registration_password');
                    if(thisAlert.innerHTML!=''){ thisAlert.innerHTML+='<br/>';}
                    thisAlert.innerHTML+='Password does not meet minimum requirements';
                    thisAlert.style.display='block';
                }

                if(document.getElementById('tfgg_scp_registration_password').value!=
                document.getElementById('tfgg_scp_registration_password_confirm').value){
                    allowSubmit = false;
                    resultMsg+='<li> - Confirmation password does not match</li>';
                    var thisAlert = document.getElementById('reg_alert_for_tfgg_scp_registration_password_confirm');
                    if(thisAlert.innerHTML!=''){ thisAlert.innerHTML+='<br/>';}
                    thisAlert.innerHTML+='Confirmation password does not match';
                    thisAlert.style.display='block';
                }

                resultMsg+='</ul>';

                if(!allowSubmit){
                    document.getElementById('modal-tfgg-scp-validation-results-body').innerHTML=resultMsg;
                    jQuery('#modal-tfgg-scp-validation-results').modal();
                    document.getElementById('tfgg_scp_registration_submit_button').disabled=false;
                document.getElementById('tfgg_scp_registration_submit_button').innerHTML='Register Your Account';
                    document.getElementById('tfgg_scp_registration_busy').style.display='none';
                }else{

                    var actionTag='online_registration';
                    if(inStore){
                        actionTag='instore_registration';
                    }

                    grecaptcha.ready(function () {
                        grecaptcha.execute(document.getElementsByName('tfgg_scp_recaptcha_site_key')[0].value, 
                        { action: actionTag }).then(function (token) {
                            var recaptchaResponse = document.getElementById('recaptchaResponse');
                            recaptchaResponse.value = token;
                            setTimeout( function(){
                                if( document.getElementById('recaptchaResponse').value.length > 0 ) {
                                    document.getElementById('tfgg_scp_registration').submit();
                                }
                            }, 1000 );
                            document.getElementById('tfgg_scp_registration_submit_button').innerHTML='Submitting';
                        });
                    });  
                }

            }

            function populateMonthDays(){
                var mthSelected = document.getElementById('tfgg_scp_registration_dob_mth').value;
                var yearSelected = document.getElementById('tfgg_scp_registration_dob_year').value;
                var daySelected = document.getElementById('tfgg_scp_registration_dob_day');
                if(yearSelected==''){ yearSelected = new Date().getFullYear()-14;}
                
                if(mthSelected!=''){
                    var daysInMth = new Date(yearSelected, mthSelected,0).getDate();

                    if(daysInMth!=daySelected.length){
                        removeOptions(daySelected);
                        for(var i=0; i<=daysInMth; i++){
                            var opt=document.createElement('option');
                            if(i>0){
                                var txt = i;
                                opt.appendChild(document.createTextNode(txt));
                                opt.value=txt.toString().padStart(2,'0');
                            }
                            daySelected.appendChild(opt);
                        }
                        daySelected.disabled = false;
                    }
                }else{
                    removeOptions(daySelected);
                    daySelected.disabled = true;
                }

                validateDOBAge();
            }

            function validateDOBAge(){
                var alert = document.getElementById('reg_alert_for_tfgg_scp_registration_dob_mth');
                alert.innerHTML='';
                alert.style.display='none';
                var mthSelected = document.getElementById('tfgg_scp_registration_dob_mth').value;
                var yearSelected = document.getElementById('tfgg_scp_registration_dob_year').value;
                var daySelected = document.getElementById('tfgg_scp_registration_dob_day').value;

                if((mthSelected!='')&&(yearSelected!='')&&(daySelected!='')){
                    var dob = new Date(yearSelected, (mthSelected-1), daySelected);
                    if(getAgeYears(dob)<18){
                        alert.innerHTML='Under 18s may only use Spray Tanning services';
                        alert.style.display='block';
                    }
                }
            }

            function removeOptions(selectElement) {
                var i, L = selectElement.options.length - 1;
                for(i = L; i >= 0; i--) {
                    selectElement.remove(i);
                }
            }

            function togglePassVis(){
                var x = document.getElementById('tfgg_scp_registration_password');
                var y = document.getElementById('tfgg_scp_registration_password_confirm');
                var b = document.getElementById('tfgg_scp_registration_togglePassVis');
                if(x.type === 'password'){
                    x.type="text";
                    y.type="text";
                    b.innerHTML="Hide";
                }else{
                    x.type="password";
                    y.type="password";
                    b.innerHTML="Show";
                }
            }

            function validateBeforeSubmit(){

                jQuery('#modal-tfgg-scp-validation-results').modal();

            }

            function showTandC(){
                jQuery('#modal-tfgg-scp-tandc').modal();
            }

            function showMarketing(){
                jQuery('#modal-tfgg-scp-marketing').modal();   
            }

            function showSkinTypingInfo(){
                jQuery('#modal-tfgg-scp-skintypes').modal();   
            }

            function checkForDisplayWarning(){
                var storeSelected = document.getElementById('tfgg_scp_registration_store').value;
                if(storeSelected=='0000000036'){
                    document.getElementById('tfgg_scp_chiswick_warning').style.display='block';
                }else{
                    document.getElementById('tfgg_scp_chiswick_warning').style.display='none';
                }
            }

            function setCheckBoxState(element, state){
                jQuery('#'+element).attr('checked',state);
            }

            function evaluateSkinTypeWarning(el){
                //console.log(el.options[el.selectedIndex].text.toUpperCase());
                var regAlert = document.getElementById('reg_alert_for_'+el.id);
                regAlert.innerHTML='';
                regAlert.style.display='none';

                if(el.options[el.selectedIndex].text.toUpperCase()=='SENSITIVE'){
                    regAlert.textContent='Skin Type: No Tanning - You are unsuitable for UV tanning but you can still use spray tanning services';
                    regAlert.style.display='block';
                }
            }

            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }
        </script>
    <?php
        return ob_get_clean();
    }

    function tfgg_scp_process_registration(){

        //tfgg_scp_registration_user_password_reenter -> this is the honeypot field

        if((array_key_exists('tfgg_scp_registration_nonce',$_POST))&&
        (wp_verify_nonce($_POST['tfgg_scp_registration_nonce'],'tfgg-scp-registration-nonce'))&&
        (empty($_POST['tfgg_scp_registration_user_password_reenter']))&&
        (array_key_exists('recaptcha_response',$_POST))
        ){

            unset($_POST['tfgg_scp_registration_nonce']);

            $instoreRegistration = ($_POST['tfgg_scp_registration_instore']==1);

            //at this point, we know a 'good' registration has been submitted, process the data
            
            $check_captcha = tfgg_scp_get_registration_recaptcha_response($_POST['recaptcha_response']);
            unset($_POST['recaptcha_response']);
            
            if(!$instoreRegistration){
                if(true === $check_captcha){
                    //do nothing, the captcha was a success
                }else{
                    tfgg_cp_errors()->add('error_cannot_reg', __('There was an error registering your account: Failed reCaptcha'.
                        '<br/>Please contact the support department for assistance: <a href="mailto:'.get_option('tfgg_scp_customer_service_email').'?subject=Registration Issues (reCaptcha)" target="_blank">'.get_option('tfgg_scp_customer_service_email').'</a>'));	
                    return false;
                }
            }

            //organize the data
			$address = array(
            'street'	=> $_POST['tfgg_scp_registration_street'],
            'street_2'	=> '',
            'city'		=> '',
            'state'		=> '',
            'postcode'	=> $_POST['tfgg_scp_registration_postcode']
            );
            
            $numbers = array(
            'home'		=> '',
            'work'		=> '',
            'work_ext'	=> '',
            'cell'		=> $_POST['tfgg_scp_registration_mobile_number']
            );

            if($instoreRegistration){
                if((array_key_exists('tfgg_scp_registration_store',$_POST))&&($_POST['tfgg_scp_registration_store']!='')){
                    $storecode=$_POST['tfgg_scp_registration_store'];				
                }else{
                    $storecode=$_COOKIE['instore_reg_store'];
                }
                $regSource = get_option('tfgg_scp_registration_source_label_instore');
            }else{
                $storecode=$_POST['tfgg_scp_registration_store'];
                $regSource = get_option('tfgg_scp_registration_source_label');
            }

            $dob = $_POST['tfgg_scp_registration_dob_year'].'-'.$_POST['tfgg_scp_registration_dob_mth'].'-'.$_POST['tfgg_scp_registration_dob_day'];
            
            $demographics = array(
            'firstname'	=> $_POST['tfgg_scp_registration_firstname'],
            'lastname'	=> $_POST['tfgg_scp_registration_lastname'],
            'midinit'	=> '',
            'email'		=> $_POST['tfgg_scp_registration_email'],
            'dob'		=> $dob,
            'address'	=> $address,
            'numbers'	=> $numbers,
            'storecode'	=> $storecode,//2019-11-11 CB
            'howhear'	=> $_POST['tfgg_scp_registration_how_hear'],
            'eyecolor'	=> '',
            'gender'	=> $_POST['tfgg_scp_registration_gender'],
            'skintype'	=> $_POST['tfgg_scp_registration_skin_type'],
            'userdefined1' => $regSource,
            'userdefined2' => $_POST['tfgg_scp_registration_user_defined_2']
            );

            if((array_key_exists('tfgg_scp_registration_marketing',$_POST))&&($_POST['tfgg_scp_registration_marketing']=='1')){
                $commPref = array(
                    'doNotSolicit'	=> '0',
                    'email'			=> '1',
                    'sms'			=> '1',
                );
            }else{
                $commPref = array(
                    'doNotSolicit'	=> '1',
                    'email'			=> '0',
                    'sms'			=> '0',
                );	
            }

            //data is all set, check for duplicate before process
            //2021-03-25 CB - new validation before registration
            $reg_validation = json_decode(tfgg_api_multi_step_existing_user_check($demographics['firstname'],
            $demographics['lastname'],$demographics['dob'],$demographics['email']));

            if(StrToUpper($reg_validation->results)==='FAIL'){
				//couldn't validate unique account
				//session values will take care of the display
			}else{
                //able to register somehow
                if(isset($reg_validation->process)){
                    switch($reg_validation->process){
                        case 'insert':
                            //process the insert like normal
                            $storePkg = tfgg_cp_reg_pkg($storecode,!$instoreRegistration);
							$storePromo = tfgg_cp_reg_promo($storecode,!$instoreRegistration);
							$reg_result=json_decode(tfgg_api_insert_user_proprietary($demographics, $commPref,
							$storePromo, $storePkg));

                            if(strtoupper($reg_result->results)=='SUCCESS'){
								//now set the password
								tfgg_api_set_password($reg_result->clientnumber,$_POST['tfgg_scp_registration_password']);
								
                                if($instoreRegistration){
                                    //2019-11-14 CB V1.2.3.1 - added palceholders to replace data
                                    $successMessage=get_option('tfgg_scp_instore_registration_success');
                                    $successMessage=str_replace('!@#firstname#@!',$demographics['firstname'],$successMessage);
                                    $successMessage=str_replace('!@#lastname#@!',$demographics['lastname'],$successMessage);
                                    $successMessage=str_replace('!@#clientnumber#@!',$reg_result->clientnumber,$successMessage);
                                    tfgg_cp_errors()->add('success_reg_complete', __($successMessage));
                                }else{
                                    $clientNumber=$reg_result->clientnumber;
                                }
								
							}else{
								tfgg_cp_errors()->add('error_cannot_reg', __('There was an error registering your account: '.$reg_result->response.
								'<br/>Please contact the support department for assistance: <a href="mailto:'.get_option('tfgg_scp_customer_service_email').'?subject=Registration Issues" target="_blank">'.get_option('tfgg_scp_customer_service_email').'</a>'));
							}

                            break;
                        case 'set':
                            //set the password and the email on the account
							$clientNumber=$reg_validation->client->client_id;
							tfgg_api_set_password($clientNumber,$_POST['tfgg_scp_registration_password']);
							tfgg_api_update_single_demo($clientNumber,'email',$demographics['email']);
                            
                            if($instoreRegistration){
                                $successMessage=get_option('tfgg_scp_instore_registration_success');
                                $successMessage=str_replace('!@#firstname#@!',$demographics['firstname'],$successMessage);
                                $successMessage=str_replace('!@#lastname#@!',$demographics['lastname'],$successMessage);
                                $successMessage=str_replace('!@#clientnumber#@!',$clientNumber,$successMessage);
                                tfgg_cp_errors()->add('success_reg_complete', __($successMessage));
                            }else{
                                $_SESSION['linked_reg']=$clientNumber;
                            }
                            break;
                    }//switch
                    if(!$instoreRegistration){
                        tfgg_cp_set_sunlync_client($clientNumber);
                        tfgg_cp_redirect_after_registration();
                    }
                }//isset 
            }//if StrToUpper(FAIL) .. else

        }//nonce validate

    }
    add_action('init','tfgg_scp_process_registration');
?>