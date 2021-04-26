<?php

    function reg_form_display_new(){

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
					return display_tfgg_scp_registration_form();
					break;
			}
		}else{
			return display_tfgg_scp_registration_form();	
		}
		
    }

	function display_tfgg_scp_registration_form(){
		ob_start(); 
        
        $storeList = json_decode(tfgg_api_get_reg_stores(true));
        if(StrToUpper($storeList->results)==='SUCCESS'){
			$storeList = $storeList->stores;
			$selectedStore='';
			global $post;
			$post_slug = $post->post_name;
			//check to see if we need to validate a store specific 
			if($post_slug<>get_option('tfgg_scp_cpnewuser_page')){
				//we are on a store specific page, maybe

				$regStoreSlugs = (array)get_option('tfgg_scp_store_reg_slugs');
				$storeSlugKey = array_search($post_slug,$regStoreSlugs);

				if(($storeSlugKey!='')&&($storeSlugKey!=FALSE)){
					foreach($storeList as &$details){
						if((!strpos(StrToUpper($details->store_loc),'CLOSED'))&&
						(!strpos(StrToUpper($details->store_loc),'DELETED'))){
							if($storeSlugKey==$details->store_id){
								$selectedStore=$details->store_loc;
							}
						}
					}
				}
			}
		}
		
		$skintypes = json_decode(tfgg_api_get_skintypes());
		if(strtoupper($skintypes->results)==='SUCCESS'){
			$skintypes = $skintypes->skintypes;
		}
        
        $minBirthDate = new DateTime();
        date_sub($minBirthDate, date_interval_create_from_date_string('18 years'));
		
		if((array_key_exists('tfgg_cp_register_nonce',$_POST))&&
		(wp_verify_nonce($_POST['tfgg_cp_register_nonce'],'tfgg-cp-register-nonce'))){
			$repopulate = true;
		}else{
			$repopulate = false;
		}

        ?>
        
        <hr />
	        <?php
	            tfgg_sunlync_cp_show_error_messages(); 
	        ?>
			
			<form id="sunlync_cp_registration_form" action="" method="POST" autocomplete="OFF">
				
			<div class="registration-container-main">
				
				<div class="account-overview-input-single-left">
				
					<h4><?php echo get_option('tfgg_scp_cust_info_reg_title');?></h4>
				
					<div class="registration-container">
						<div class="account-overview-input-single">
							<label for="tfgg_cp_user_email" class="account-overview-label"><?php _e('Email'); ?></label>
							<input data-alertpnl="new_reg_email" name="tfgg_cp_user_email" id="tfgg_cp_user_email" class="required account-overview-input" type="email"
							value="<?php if(($repopulate)&&(array_key_exists('tfgg_cp_user_email',$_POST))){echo $_POST['tfgg_cp_user_email'];} ?>"
							/>
							<div style="display:none" id="new_reg_email" class="reg_alert"></div> 
						</div>
					</div>

					<div class="registration-container">
						<div class="account-overview-input-single">
							<label for="tfgg_cp_user_email_confirm" class="account-overview-label"><?php _e('Confirm Email'); ?></label>
							<input data-alertpnl="new_reg_email_confirm" name="tfgg_cp_user_email_confirm" id="tfgg_cp_user_email_confirm" class="required account-overview-input" type="email"/>
							<div style="display:none" id="new_reg_email_confirm" class="reg_alert"></div> 
						</div>
					</div>
						
					<div class="registration-container">
						<div class="account-overview-input-double">
							<label for="tfgg_cp_user_first" class="account-overview-label"><?php _e('First Name'); ?></label>
							<input data-alertpnl="new_reg_fname" name="tfgg_cp_user_first" id="tfgg_cp_user_first" class="required account-overview-input" type="text"
							value="<?php if(($repopulate)&&(array_key_exists('tfgg_cp_user_first',$_POST))){echo $_POST['tfgg_cp_user_first'];} ?>"
							/>
							<div style="display:none" id="new_reg_fname" class="reg_alert"></div>
						</div>
						<div class="account-overview-input-single">
							<label for="tfgg_cp_user_last" class="account-overview-label"><?php _e('Last Name'); ?></label>
							<input data-alertpnl="new_reg_lname" name="tfgg_cp_user_last" id="tfgg_cp_user_last" class="required account-overview-input" type="text"
							value="<?php if(($repopulate)&&(array_key_exists('tfgg_cp_user_last',$_POST))){echo $_POST['tfgg_cp_user_last'];} ?>"
							/>
							<div style="display:none" id="new_reg_lname" class="reg_alert"></div>
						</div>
					</div>
					
					<div class="registration-container">
						<div class="account-overview-input-double">
							<label for="tfgg_cp_street_address" class="account-overview-label"><?php _e('Street Address'); ?></label>
							<input data-alertpnl="new_reg_street_address" id="tfgg_cp_street_address" name="tfgg_cp_street_address" class="required account-overview-input" type="text"
							value="<?php if(($repopulate)&&(array_key_exists('tfgg_cp_street_address',$_POST))){echo $_POST['tfgg_cp_street_address'];} ?>"
							/>
							<div style="display:none" id="new_reg_street_address" class="reg_alert"></div>
						</div>
						<div class="account-overview-input-single">
							<label for="tfgg_cp_post_code" class="account-overview-label"><?php _e('Post Code'); ?></label>
							<input data-alertpnl="new_reg_post_code_alertpnl" name="tfgg_cp_post_code" id="tfgg_cp_post_code" class="required account-overview-input" type="text"
							value="<?php if(($repopulate)&&(array_key_exists('tfgg_cp_post_code',$_POST))){echo $_POST['tfgg_cp_post_code'];} ?>"
							/>
							<div style="display:none" id="new_reg_post_code_alertpnl" class="reg_alert"></div>
						</div>
					</div>
						
				</div>	

				<div class="account-overview-input-single demo2-container">

					<div class="registration-container">
						<div class="account-overview-input-double">
							<label for="tfgg_cp_mobile_phone" class="account-overview-label"><?php _e('Mobile Phone'); ?></label>
							<input data-alertpnl="new_reg_mobile_phone" id="tfgg_cp_mobile_phone" name="tfgg_cp_mobile_phone" class="required account-overview-input" type="text"
							value="<?php if(($repopulate)&&(array_key_exists('tfgg_cp_mobile_phone',$_POST))){echo $_POST['tfgg_cp_mobile_phone'];} ?>"
							/>
							<div style="display:none" id="new_reg_mobile_phone" class="reg_alert"></div>
						</div>


						<div class="account-overview-input-single">
							<label for="tfgg_cp_store" class="account-overview-label"><?php _e('Store'); ?></label>
							<!--<input data-alertpnl="new_reg_post_code" name="tfgg_cp_store" id="tfgg_cp_store" class="required account-overview-input" type="text"/>-->
							
							<div class="select-container">
							<?php
							if((isset($selectedStore))&&($selectedStore=='')){
							?>
							<select data-alertpnl="new_reg_store_alertpnl" name="tfgg_cp_store" id="tfgg_cp_store" class="js-example-basic-single required account-overview-input">
								<option value="please select">Please Select...</option>
								<?php

									if(($repopulate)&&(array_key_exists('tfgg_cp_store',$_POST))){
										$selectedStore = $_POST['tfgg_cp_store'];
									}else{
										$selectedStore = '';
									}

									foreach($storeList as &$details){
										//2019-07-19 CB - added strpos check to remove 'CLOSED'/'DELETED' stores
										if((!strpos(StrToUpper($details->store_loc),'CLOSED'))&&
										(!strpos(StrToUpper($details->store_loc),'DELETED'))){
											if($selectedStore==$details->store_id){
												echo '<option value="'.$details->store_id.'" selected>'.$details->store_loc.'</option>';
											}else{
												echo '<option value="'.$details->store_id.'">'.$details->store_loc.'</option>';
											}	
										}
									}
								?>
							</select>
							<?php
	}else{
?>
							<input data-alertpnl="v" id="tfgg_cp_store_show" name="tfgg_cp_store_show" class="account-overview-input" type="text"
							value="<?php echo ($selectedStore); ?>" readonly/>
							<input type="hidden" name="tfgg_cp_store" value="<?php echo $storeSlugKey;?>"/>
<?php
	}
							?>
							</div>
					
							<div style="display:none" id="new_reg_store_alertpnl" class="reg_alert"></div>
						</div>



					</div>

					<div class="registration-container">
						<div class="account-overview-input-double">
							<label for="tfgg_cp_user_dob" class="account-overview-label"><?php _e('Date Of Birth'); ?></label>
							<input readonly="true" id="tfgg_cp_user_dob_value" name="tfgg_cp_user_dob_value" class="account-overview-input" type="text"
							value="<?php if(($repopulate)&&(array_key_exists('tfgg_cp_user_dob_value',$_POST))){echo $_POST['tfgg_cp_user_dob_value'];} ?>"
							/>
							<input data-alertpnl="new_reg_dob" name="tfgg_cp_user_dob" id="tfgg_cp_user_dob" class="required account-overview-input" type="hidden"/>
							<div style="display:none" id="new_reg_dob" class="reg_alert"></div>
						</div>
						<div class="account-overview-input-single">
							<label for="tfgg_cp_user_gender" class="account-overview-label"><?php _e('Gender'); ?></label>
							<select data-alertpnl="new_reg_gender" name="tfgg_cp_user_gender" id="tfgg_cp_user_gender" class="required account-overview-input">
								<option value="please select">Please Select...</option>
								<option value="Male" <?php if(($repopulate)&&(array_key_exists('tfgg_cp_user_gender',$_POST))&&($_POST['tfgg_cp_user_gender']=='Male')){echo 'selected';} ?>>Male</option>
								<option value="Female" <?php if(($repopulate)&&(array_key_exists('tfgg_cp_user_gender',$_POST))&&($_POST['tfgg_cp_user_gender']=='Female')){echo 'selected';} ?>>Female</option>
							</select>
							<div style="display:none" id="new_reg_gender" class="reg_alert"></div>
						</div>		
					</div>

					<div class="registration-container">
						<div class="account-overview-input-single">
							<label for="tfgg_cp_skin_type" class="account-overview-label"><?php _e('Skin Type'); ?></label>
							<!--<input data-alertpnl="new_reg_post_code" name="tfgg_cp_store" id="tfgg_cp_store" class="required account-overview-input" type="text"/>-->
							<select data-alertpnl="new_reg_skin_type_alertpnl" name="tfgg_cp_skin_type" id="tfgg_cp_skin_type" class="required account-overview-input">
								<option value="please select">Please Select...</option>
								<?php
									if(($repopulate)&&(array_key_exists('tfgg_cp_skin_type',$_POST))){
										$selectedSkin = $_POST['tfgg_cp_skin_type'];
									}else{
										$selectedSkin = '';
									}

									foreach($skintypes as &$details){
										if($selectedSkin == $details->type){
											echo '<option value="'.$details->type.'" selected>'.$details->description.'</option>';
										}else{
											echo '<option value="'.$details->type.'">'.$details->description.'</option>';
										}	
									}
								?>
							</select>
							<div style="display:none" id="new_reg_skin_type_alertpnl" class="reg_alert"></div>
						</div>
					</div>
					<input type="text" name="tfgg_scp_recaptcha_site_key" value="<?php echo get_option('tfgg_scp_recaptcha_site_key'); ?>" style="display:none" />
				</div>
	
				</div>
				
				<hr />
				<div class="account-overview-input-single-left">
				<h4>Password</h4>
					<div class="registration-container">
						<div class="account-overview-input-single">
							<div class="password-hints">
								<span>Passwords must meet the following requirements:</span><br/>
								<ul style="line-height:0.9;">
								<li>Contain at least 1 lower and 1 upper case letter</li>
								<li>Contain at least 1 number</li>
								<li>Be at least 8 characters long</li></ul>
							</div>
						</div>
					</div>
					<div class="registration-container">
						<div class="account-overview-input-single">
							<label for="tfgg_cp_user_pass" class="account-overview-label"><?php _e('Password'); ?></label>
							<input data-alertpnl="new_reg_pass" id="tfgg_cp_user_pass" name="tfgg_cp_user_pass" class="required account-overview-input" type="password"/>
							<button type="button" class="account-overview-button account-overview-standard-button account-overview-appt-cancel-button" style="float:right;" onclick="tfggSCPTogglePassword();"><?php _e('Show'); ?></button>
							<div style="display:none" id="new_reg_pass" class="reg_alert"></div> 
						</div>
					</div>
					<div class="registration-container">
						<div class="account-overview-input-single">
							<label for="tfgg_cp_user_pass_confirm" class="account-overview-label"><?php _e('Password Confirm'); ?></label>
							<input data-alertpnl="new_reg_pass_confirm" name="tfgg_cp_user_pass_confirm" id="tfgg_cp_user_pass_confirm" class="required account-overview-input" type="password"/>
							<div style="display:none" id="new_reg_pass_confirm" class="reg_alert"></div>
						</div>
					</div>

				</div>
				
		
				<hr />
				
				<h4>Please read carefully</h4>

				<div class='reg-checkbox-container'>
					<input data-alertpnl="new_reg_tandc_confirm" name="tfgg_cp_user_tandc_agree" id="tfgg_cp_user_tandc_agree" class="required account-overview-survey-input scaled-checkbox" type="checkbox"/>
					<label <?php /*onclick="showRegTAndC();"*/ ?> for="tfgg_cp_user_tandc_agree" style="color:#F16631; font-weight:700px; padding-left: 5px;"><?php echo get_option('tfgg_scp_tandc_label'); ?></label>	
					<div style="display:none" id="new_reg_tandc_confirm" class="reg_alert"></div>
				</div>
				<br style="line-height:0.9"/>		
				<div class='reg-checkbox-container'>
					<input data-alertpnl="new_reg_marketing" id="tfgg_cp_marketing" name="tfgg_cp_marketing" class="account-overview-survey-input scaled-checkbox" type="checkbox" value="1"/>
					<label for="tfgg_cp_marketing" style="color:#F16631; font-weight:700px; padding-left: 5px;"><?php echo get_option('tfgg_scp_marketing_optin_label') ?></label>	
					<div style="display:none" id="new_reg_marketing" class="reg_alert"></div>	
				</div>
				<br style="line-height:0.9"/>
				<div class='reg-checkbox-container'>
					<input data-alertpnl="new_reg_skin_type_confirm_alertpnl" id="tfgg_cp_skin_type_confirm" name="tfgg_cp_skin_type_confirm" class="required account-overview-survey-input scaled-checkbox" type="checkbox" value="1"/>
					<label for="tfgg_cp_skin_type_confirm"  style="color:#F16631; font-weight:700px; padding-left: 5px;"><?php _e('I hereby certify that the skin type selected is accurate'); ?></label>
					<div style="display:none" id="new_reg_skin_type_confirm_alertpnl" class="reg_alert"></div>
				</div>

				<br />

				<div class="registration-container">
							<div class="account-overview-input-double">
								<label for="tfgg_cp_how_hear" class="account-overview-label"><?php _e('How did you hear about us?'); ?></label>
								<select data-alertpnl="new_reg_how_hear" name="tfgg_cp_how_hear" id="tfgg_cp_how_hear" class="required account-overview-input">
									<option value="please select">Please select&#8230;</option>
									<option value="Website" <?php if(($repopulate)&&(array_key_exists('tfgg_cp_how_hear',$_POST))&&($_POST['tfgg_cp_how_hear']=='Website')){echo 'selected';} ?>>Website</option>
									<option value="Flyer" <?php if(($repopulate)&&(array_key_exists('tfgg_cp_how_hear',$_POST))&&($_POST['tfgg_cp_how_hear']=='Flyer')){echo 'selected';} ?>>Flyer</option>
									<option value="Advert" <?php if(($repopulate)&&(array_key_exists('tfgg_cp_how_hear',$_POST))&&($_POST['tfgg_cp_how_hear']=='Advert')){echo 'selected';} ?>>Advert</option>
									<option value="Email" <?php if(($repopulate)&&(array_key_exists('tfgg_cp_how_hear',$_POST))&&($_POST['tfgg_cp_how_hear']=='Email')){echo 'selected';} ?>>Email</option>
									<option value="Seen Store" <?php if(($repopulate)&&(array_key_exists('tfgg_cp_how_hear',$_POST))&&($_POST['tfgg_cp_how_hear']=='Seen Store')){echo 'selected';} ?>>Seen Store</option>
									<option value="Social Media" <?php if(($repopulate)&&(array_key_exists('tfgg_cp_how_hear',$_POST))&&($_POST['tfgg_cp_how_hear']=='Social Media')){echo 'selected';} ?>>Social Media</option>
									<option value="Word of Mouth"<?php if(($repopulate)&&(array_key_exists('tfgg_cp_how_hear',$_POST))&&($_POST['tfgg_cp_how_hear']=='Word of Mouth')){echo 'selected';} ?>>Word of Mouth</option>
									<option value="Ad Banner" <?php if(($repopulate)&&(array_key_exists('tfgg_cp_how_hear',$_POST))&&($_POST['tfgg_cp_how_hear']=='Ad Banner')){echo 'selected';} ?>>Ad Banner</option>
								</select>
								<div style="display:none" id="new_reg_how_hear_alert" class="reg_alert"></div>
							</div>
							<div class="account-overview-input-single">
								<input class="account-overview-input" type="hidden"/>
							</div>
					</div>
			
				<div id="chiswick_display_warning" style="display:none">	
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
		
				<input type="hidden" name="tfgg_cp_register_nonce" id="tfgg_cp_register_nonce" value="<?php echo wp_create_nonce('tfgg-cp-register-nonce'); ?>"/>
				<input type="hidden" name="tfgg_cp_user_defined_2" id="tfgg_cp_user_defined_2" value=""/>
				<input type="hidden" name="tfgg_cp_online_reg_source_slug" id="tfgg_cp_online_reg_source_slug" value="<?php echo $post_slug;?>"/>
				<input type="text" name="tfgg_cp_user_password_reenter" id="tfgg_cp_user_password_reenter" style="display:none !important" tabindex="-1" autocomplete="off"/>
				<input type="hidden" name="recaptcha_response" id="recaptchaResponse" autocomplete="off"/>
				<button type="submit" id="registrationSubmitButton" class="account-overview-button account-overview-standard-button" onclick="ValidateNewReg(true)">  <?php _e('REGISTER YOUR ACCOUNT'); ?></button>
				<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/loading.gif" class="loading-image" style="width: 40px; display:none"
            	id="tfgg_scp_recaptcha_busy"/>
				<div class="account-overview-input-single">
					<div id="new_reg_overall_alertpnl" style="display:none;" class="reg_alert"></div>
				</div>
			</form>
		
			<div id="tfgg_cp_reg_tandc_dialog" name="tfgg_cp_reg_tandc_dialog" title="Terms & Conditions" style="display:none">
				<div>
					<?php echo get_option('tfgg_scp_registration_tandc'); ?>
				
					<div class="dialog-buttons" style="display:none">
						<hr />
						<button type="button" class="account-overview-button account-overview-standard-button dialog-close-button" onclick="closeRegTAndC()">Close</button>
					</div>
				</div>
			</div>
			<br/><br/>
			<script>
				jQuery( function() {
					var now = new Date();					
					var maxDateAllowed = new Date();
					maxDateAllowed.setFullYear(maxDateAllowed.getFullYear() - 14);
    				jQuery( "#tfgg_cp_user_dob_value" ).datepicker({
      					changeMonth: true,
						changeYear: true,
						yearRange: "-100:+0",
						maxDate: maxDateAllowed,
						showMonthAfterYear:true,
						dateFormat: 'dd-mm-yy',
						onSelect: function(dateText) {
							jQuery('#new_reg_dob').hide();
							jQuery('#new_reg_dob').html('');
							tfgg_cp_user_dob
							dateselected = jQuery("#tfgg_cp_user_dob_value").datepicker('getDate');
							jQuery('#tfgg_cp_user_dob').val(jQuery.datepicker.formatDate('yy-mm-dd',dateselected));
							jQuery('#new_reg_dob').html('');
							var age = getAgeYears(jQuery.datepicker.formatDate('yy-mm-dd',dateselected));
							if(age<18){
								jQuery('#new_reg_dob').show();
								jQuery('#new_reg_dob').html('Under 18s may only use Spray Tanning services');
							}//if
						  },//onselect
						  onChangeMonthYear:function(yearText, monthText){
							//get the currently selected date
							jQuery('#new_reg_dob').hide();
							jQuery('#new_reg_dob').html('');
							dateselected = jQuery("#tfgg_cp_user_dob_value").datepicker('getDate');	
							if(dateselected!=null){
								//console.log('here');
								if(dateselected.getFullYear()!=yearText){
									dateselected.setFullYear(yearText)
									jQuery("#tfgg_cp_user_dob_value").datepicker("setDate",dateselected);
								}
								dateselected = jQuery("#tfgg_cp_user_dob_value").datepicker('getDate');
								jQuery('#tfgg_cp_user_dob').val(jQuery.datepicker.formatDate('yy-mm-dd',dateselected));
								jQuery('#new_reg_dob').html('');
								var age = getAgeYears(jQuery.datepicker.formatDate('yy-mm-dd',dateselected));
								if(age<18){
									jQuery('#new_reg_dob').show();
									jQuery('#new_reg_dob').html('Under 18s may only use Spray Tanning services');
								}//if
							}
						  }
    				});
				  } );

				
    			jQuery('.js-example-basic-single').select2();
			</script>

        <?php
		return ob_get_clean();
        //return ob_get_contents();
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
	
	
	//THIS IS DEPRECATED!!!!!
    function tfgg_sunlync_client_registration(){
		//2019-10-12 CB V1.1.1.1 - DEPRECATED!!
		return true;//force exit in case we somehow ended up here
    	if(isset($_POST['tfgg_cp_user_email']) && wp_verify_nonce($_POST['tfgg_cp_register_nonce'],'tfgg-cp-register-nonce')){
    		
    		//check for an existing user in wp
			$user = get_user_by('email', $_POST['tfgg_cp_user_email'] );
			if(!$user){
			
				$address = array(
				'street'	=> $_POST['tfgg_cp_street_address'],
				'street_2'	=> '',
				'city'		=> '',
				'state'		=> '',
				'postcode'	=> $_POST['tfgg_cp_post_code']
				);
				
				$numbers = array(
				'home'		=> '',
				'work'		=> '',
				'work_ext'	=> '',
				'cell'		=> $_POST['tfgg_cp_mobile_phone']
				);
				
				$demographics = array(
				'firstname'	=> $_POST['tfgg_cp_user_first'],
				'lastname'	=> $_POST['tfgg_cp_user_last'],
				'midinit'	=> '',
				'email'		=> $_POST['tfgg_cp_user_email'],
				'dob'		=> $_POST['tfgg_cp_user_dob'],
				'address'	=> $address,
				'numbers'	=> $numbers,
				'storecode'	=> $_POST['tfgg_cp_store'],
				'howhear'	=> $_POST['tfgg_cp_how_hear'],
				'eyecolor'	=> '',
				'gender'	=> $_POST['tfgg_cp_user_gender'],
				'skintype'	=> $_POST['tfgg_cp_skin_type']
				);
				
				/*$commPref = array(
				'doNotSolicit'	=> '0',
				'email'			=> (array_key_exists('tfgg_cp_marketing_email',$_POST)?$_POST['tfgg_cp_marketing_email']:'0'),
				'sms'			=> (array_key_exists('tfgg_cp_user_marketing_sms',$_POST)?$_POST['tfgg_cp_user_marketing_sms']:'0')
				);
				
				$commPref['doNotSolicit']=(($commPref['email']=='1'||$commPref['sms']=='1')?'1':'0');*/
				if((array_key_exists('tfgg_cp_marketing',$_POST))&&($_POST['tfgg_cp_marketing']=='1')){
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
				
				//check if the user exists in SunLync already
				$alreadyRegistered=json_decode(tfgg_api_check_user_exists($demographics['firstname'],
        		$demographics['lastname'],$demographics['dob'],$demographics['email']));
        	
        		if(StrToUpper($alreadyRegistered->results)==='SUCCESS'){
        			//double check if the sunlync client number exists
        			
        			if(tfgg_cp_check_sunlync_meta($alreadyRegistered->clientnumber)){
        				tfgg_cp_errors()->add('warning_existing_user', __('An account with these details already exists<br/>Try resetting your password from the login page or contact the support department for assistance: <a href="mailto:'.get_option('tfgg_scp_customer_service_email').'?subject=Registration Issues" target="_blank">'.get_option('tfgg_scp_customer_service_email').'</a>'));		
        			}else{
        			
        				//exists in Sunlync but not on WP
        				$userdata = array(
						'user_login'  =>  $_POST['tfgg_cp_user_email'],
						'user_email'  =>  $_POST['tfgg_cp_user_email'],
						'user_pass'   =>  $_POST['tfgg_cp_user_pass'],
						'first_name'  =>  $_POST['tfgg_cp_user_first'],
						'last_name'   =>  $_POST['tfgg_cp_user_last'],
						'role'		  =>  'subscriber'//this may need to be an optional setting
						);
						
						$user_id = wp_insert_user( $userdata );
        				tfgg_cp_set_sunlync_client($user_id, $alreadyRegistered->clientnumber);
        				wp_signon(array('user_login'=>$_POST['tfgg_cp_user_email'],'user_password'=>$_POST['tfgg_cp_user_pass']));
						tfgg_cp_redirect_after_login(true);
        			}
        			
        		}else{
        			//brand new user, whoop whoop!
        			
        			$userdata = array(
					'user_login'  =>  $_POST['tfgg_cp_user_email'],
					'user_email'  =>  $_POST['tfgg_cp_user_email'],
					'user_pass'   =>  $_POST['tfgg_cp_user_pass'],
					'first_name'  =>  $_POST['tfgg_cp_user_first'],
					'last_name'   =>  $_POST['tfgg_cp_user_last'],
					'role'		  =>  'subscriber'//this may need to be an optional setting
					);
					
					$user_id = wp_insert_user( $userdata );
					
					//2020-02-25 CB V1.2.4.21 - updated to include reg promo and pkg
					$reg_result=json_decode(tfgg_api_insert_user_proprietary($demographics, $commPref,
					get_option('tfgg_scp_reg_promo','0000000000'),
					get_option('tfgg_scp_reg_package','0000000000')));
					
					if(strtoupper($reg_result->results)=='SUCCESS'){
				
						$clientNumber=$reg_result->clientnumber;
						tfgg_cp_set_sunlync_client($user_id, $clientNumber);
						wp_signon(array('user_login'=>$_POST['tfgg_cp_user_email'],'user_password'=>$_POST['tfgg_cp_user_pass']));
						tfgg_cp_redirect_after_login();
						
					}else{
						//error registering, so roll back
						//include(ABSPATH.'wp-admin/includes/user.php');//so we have access to wp_delete_user
						wp_delete_user($user_id);
						tfgg_cp_errors()->add('error_cannot_reg', __('There was an error registering your account: '.$reg_result->response.
						'<br/>Please contact the support department for assistance: <a href="mailto:'.get_option('tfgg_scp_customer_service_email').'?subject=Registration Issues" target="_blank">'.get_option('tfgg_scp_customer_service_email').'</a>'));
					}
        		}
				
			}elseif($user->ID<>0){
				tfgg_cp_errors()->add('warning_existing_user', __('This email is already linked to an account<br/>Try resetting your password from the login page'));	
			}
			$errors = tfgg_cp_errors()->get_error_messages();

    	}
    }
	//add_action('init','tfgg_sunlync_client_registration');
	
	function tfgg_sunlync_client_api_registration(){
		if((isset($_POST['tfgg_cp_user_email'])) && ((array_key_exists('tfgg_cp_register_nonce',$_POST))&&
		(wp_verify_nonce($_POST['tfgg_cp_register_nonce'],'tfgg-cp-register-nonce')))&&
		(empty($_POST['tfgg_cp_user_password_reenter']))){

			$check_captcha = tfgg_scp_get_registration_recaptcha_response($_POST['recaptcha_response']);
			
			if(true === $check_captcha){
				//do nothing, the captcha was a success
			}else{
				tfgg_cp_errors()->add('error_cannot_reg', __('There was an error registering your account: Failed reCaptcha'.
					'<br/>Please contact the support department for assistance: <a href="mailto:'.get_option('tfgg_scp_customer_service_email').'?subject=Registration Issues (reCaptcha)" target="_blank">'.get_option('tfgg_scp_customer_service_email').'</a>'));	
				return false;
			}
			//organize the data
			$address = array(
			'street'	=> $_POST['tfgg_cp_street_address'],
			'street_2'	=> '',
			'city'		=> '',
			'state'		=> '',
			'postcode'	=> $_POST['tfgg_cp_post_code']
			);
			
			$numbers = array(
			'home'		=> '',
			'work'		=> '',
			'work_ext'	=> '',
			'cell'		=> $_POST['tfgg_cp_mobile_phone']
			);
			
			$demographics = array(
			'firstname'	=> $_POST['tfgg_cp_user_first'],
			'lastname'	=> $_POST['tfgg_cp_user_last'],
			'midinit'	=> '',
			'email'		=> $_POST['tfgg_cp_user_email'],
			'dob'		=> $_POST['tfgg_cp_user_dob'],
			'address'	=> $address,
			'numbers'	=> $numbers,
			'storecode'	=> $_POST['tfgg_cp_store'],
			'howhear'	=> $_POST['tfgg_cp_how_hear'],
			'eyecolor'	=> '',
			'gender'	=> $_POST['tfgg_cp_user_gender'],
			'skintype'	=> $_POST['tfgg_cp_skin_type'],
			'userdefined1' => get_option('tfgg_scp_registration_source_label'),
			'userdefined2' => $_POST['tfgg_cp_user_defined_2']
			);

			if((array_key_exists('tfgg_cp_marketing',$_POST))&&($_POST['tfgg_cp_marketing']=='1')){
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
							$storePkg = tfgg_cp_reg_pkg($_POST['tfgg_cp_store'],true);
							$storePromo = tfgg_cp_reg_promo($_POST['tfgg_cp_store'],true);
							$reg_result=json_decode(tfgg_api_insert_user_proprietary($demographics, $commPref,
							$storePromo, $storePkg));

							if(strtoupper($reg_result->results)=='SUCCESS'){
								//now set the password
								tfgg_api_set_password($reg_result->clientnumber,$_POST['tfgg_cp_user_pass']);
								
								$clientNumber=$reg_result->clientnumber;
								
							}else{
								tfgg_cp_errors()->add('error_cannot_reg', __('There was an error registering your account: '.$reg_result->response.
								'<br/>Please contact the support department for assistance: <a href="mailto:'.get_option('tfgg_scp_customer_service_email').'?subject=Registration Issues" target="_blank">'.get_option('tfgg_scp_customer_service_email').'</a>'));
							}

						break;
						case 'set':
							//set the password and the email on the account
							$clientNumber=$reg_validation->client->client_id;
							tfgg_api_set_password($clientNumber,$_POST['tfgg_cp_user_pass']);
							tfgg_api_update_single_demo($clientNumber,'email',$demographics['email']);
							$_SESSION['linked_reg']=$clientNumber;
						break;
					}
					tfgg_cp_set_sunlync_client($clientNumber);
					//2020-01-12 CB V1.2.4.13 - tfgg_cp_redirect_after_login();
					tfgg_cp_redirect_after_registration();
				}
				
			}

		}
	}
    //add_action('init','tfgg_sunlync_client_api_registration');
?>