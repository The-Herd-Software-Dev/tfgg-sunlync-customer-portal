<?php

    function reg_form_display_instore(){
        ob_start(); 
        
        $storeList = json_decode(tfgg_api_get_stores());
        if(StrToUpper($storeList->results)==='SUCCESS'){
        	$storeList = $storeList->stores;	
		}
		
		$skintypes = json_decode(tfgg_api_get_skintypes());
		if(strtoupper($skintypes->results)==='SUCCESS'){
			$skintypes = $skintypes->skintypes;
		}
        
        $minBirthDate = new DateTime();
        date_sub($minBirthDate, date_interval_create_from_date_string('18 years'));
        
        $repopulate=false;

        /*if((array_key_exists('tfgg_cp_register_instore_nonce',$_POST))&&
		(wp_verify_nonce($_POST['tfgg_cp_register_instore_nonce'],'tfgg_cp_register_instore_nonce'))){
			$repopulateStpre = true;
		}else{
			$repopulateStore = false;
		}*/
        ?>
        <hr />
        <?php
            tfgg_sunlync_cp_show_error_messages(); 
        ?>
        <form id="sunlync_cp_instore_registration_form" action="" method="POST" autocomplete="OFF">
				
			<div class="registration-container-main">
				
				<div class="account-overview-input-single-left">
				
					<h4><?php echo get_option('tfgg_scp_cust_info_reg_title_instore');?></h4>
				
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
                            /*if(array_key_exists('tfgg_cp_store',$_POST)){
                                $selectedStore = $_POST['tfgg_cp_store'];
                            }else{
                                $selectedStore = '';
							}*/
							$selectedStore = $_COOKIE['instore_reg_store'];
							?>
							<select data-alertpnl="new_reg_store_alertpnl" name="tfgg_cp_store" id="tfgg_cp_store" class="js-example-basic-single required account-overview-input"
                            <?php if($selectedStore<>''){echo 'disabled';}?>>
								<option value="please select">Please Select...</option>
								<?php
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
						<?php
						$infoSlug=get_option('tfgg_scp_marketing_slug_instore');
						if(trim($infoSlug)!=''){
						?>
						<div class="registration-container">
							<div class="account-overview-input-single" style="padding-top:10px">
							<span onclick="instoreSKinTypeInfoDialog();" style="cursor: pointer; color:#F16631; text-decoration:underline;"><strong>Click here for more information on skin types</strong></span>
							</div>						
						</div>
						<?php
						}
						?>

				</div>
	
				</div>
				
				<hr />
				<div class="registration-container-main">
					<div class="account-overview-input-single-left">
						<h4 onclick="secretClick();">Set Password</h4>
						<div class="registration-container">
							<div class="account-overview-input-double">
							<?php
							if(get_option('tfgg_scp_instore_reg_password_hint')!=''){
							?>
								<div class="password-hints">
									<?php
									echo get_option('tfgg_scp_instore_reg_password_hint');
									?>
								</div>
							<?php
							}
							?>
							</div>
							<div class="account-overview-input-single">
							&nbsp;
							</div>
						</div>

						<div class="registration-container">
							<div class="account-overview-input-double">
								<label for="tfgg_cp_user_pass" class="account-overview-label"><?php _e('Password'); ?></label>
								<input data-alertpnl="new_reg_pass" id="tfgg_cp_user_pass" name="tfgg_cp_user_pass" class="required account-overview-input" type="password"/>
								<button type="button" class="account-overview-button account-overview-standard-button account-overview-appt-cancel-button" style="float:left;" onclick="tfggSCPTogglePassword();"><?php _e('Show'); ?></button>
								<div style="display:none" id="new_reg_pass" class="reg_alert"></div> 
							</div>
							<div class="account-overview-input-single">
							&nbsp;
							</div>
						</div>
					</div>

					<div class="account-overview-input-single demo2-continer">
						<h4>Please Read Carefully</h4>
						<br style="line-height:0.9"/>
						<div class="account-overview-input-single" style="padding-left:10px">
							<div class='reg-checkbox-container'>
								<input data-alertpnl="new_reg_tandc_confirm" name="tfgg_cp_user_tandc_agree" id="tfgg_cp_user_tandc_agree" class="required account-overview-survey-input scaled-checkbox" type="checkbox"/>
								<label onclick="instoreTandCDialog();" style="color:#F16631; font-weight:700px; padding-left: 5px;"><?php echo get_option('tfgg_scp_tandc_label_instore'); ?></label>	
								<div style="display:none" id="new_reg_tandc_confirm" class="reg_alert"></div>
							</div>
						</div>
						<br style="line-height:0.9"/>
						<div class="account-overview-input-single" style="padding-left:10px">		
							<div class='reg-checkbox-container'>
								<input data-alertpnl="new_reg_marketing" id="tfgg_cp_marketing" name="tfgg_cp_marketing" class="account-overview-survey-input scaled-checkbox" type="checkbox" value="1"/>
								<label onclick="instoreMarketingDialog();" style="color:#F16631; font-weight:700px; padding-left: 5px;"><?php echo get_option('tfgg_scp_marketing_optin_label_instore') ?></label>	
								<div style="display:none" id="new_reg_marketing" class="reg_alert"></div>	
							</div>
						</div>
						<br style="line-height:0.9"/>
						<div class="account-overview-input-single" style="padding-left:10px">
							<div class='reg-checkbox-container'>
								<input data-alertpnl="new_reg_skin_type_confirm_alertpnl" id="tfgg_cp_skin_type_confirm" name="tfgg_cp_skin_type_confirm" class="required account-overview-survey-input scaled-checkbox" type="checkbox" value="1"/>
								<label style="color:#F16631; font-weight:700px; padding-left: 5px;"><?php _e('I hereby certify that the skin type selected is accurate'); ?></label>
								<div style="display:none" id="new_reg_skin_type_confirm_alertpnl" class="reg_alert"></div>
							</div>
						</div>
					</div>
				</div><!--main-->
				<hr />
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
				<?php
					//check to see if reCaptcha is active and if so, display it
					if(get_option('tfgg_scp_instore_reg_recaptcha_req','1')=='1'){
						include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
						if(is_plugin_active('google-captcha/google-captcha.php')){
						?>
							<div id="tfgg_scp_reg_recaptcha">
						<?php
							echo  do_shortcode("[bws_google_captcha]");
						?>
							</div>
						<?php
						}
					}
				?>
		
				<input type="hidden" name="tfgg_cp_register_instore_nonce" id="tfgg_cp_register_instore_nonce" value="<?php echo wp_create_nonce('tfgg-cp-register-instore-nonce'); ?>"/>
				<button type="submit" id="registrationSubmitButton" class="account-overview-button account-overview-standard-button" onclick="ValidateNewReg(false)" 
				<?php if(get_option('tfgg_scp_instore_reg_recaptcha_req','1')=='1'){echo 'disabled';}?>> <?php _e('REGISTER YOUR ACCOUNT'); ?></button>
				<div class="account-overview-input-single">
					<div id="new_reg_overall_alertpnl" style="display:none;" class="reg_alert"></div>
				</div>
			</form>
		
			<div id="instore_tandc_dialog" name="instore_tandc_dialog" style="display:none">
				<?php
                    echo get_post_field('post_content', url_to_postid( site_url(get_option('tfgg_scp_tandc_slug_instore')) ));
                ?>
			</div>

            <div id="instore_marketing_dialog" name="instore_marketing_dialog" style="display:none">
				<?php
                    echo get_post_field('post_content', url_to_postid( site_url(get_option('tfgg_scp_marketing_slug_instore')) ));
                ?>
			</div>
			<div id="instore_skin_type_info_dialog" name="instore_skin_type_info_dialog" style="display:none">
				<?php
                    echo get_post_field('post_content', url_to_postid( site_url(get_option('tfgg_scp_skin_type_info_slug_instore')) ));
                ?>
			</div>
			<div id="instore_reg_validation_fail_warning" name="instore_reg_validation_fail_warning" style="display:none">
				<?php
				echo get_option('tfgg_scp_instore_registration_validation_fail');
				?>
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
						showMonthAfterYear:true,
						maxDate: maxDateAllowed,
						dateFormat: 'dd-mm-yy',
						onSelect: function(dateText) {
							jQuery('#new_reg_dob').hide();
							jQuery('#new_reg_dob').html('');
							//tfgg_cp_user_dob
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
    }//reg_form_display_instore

	function set_storecode_display(){
		ob_start(); 
        
        $storeList = json_decode(tfgg_api_get_stores());
        if(StrToUpper($storeList->results)==='SUCCESS'){
        	$storeList = $storeList->stores;	
		}

		?>
		<hr />
        <?php
            tfgg_sunlync_cp_show_error_messages(); 
        ?>
		<form id="sunlync_cp_instore_registration_setstore" action="" method="POST" autocomplete="OFF">
			<div class="login-container">
				<div class="account-overview-input-double">
					<p>Please set the store that this device will be registering customers under</p>
				</div>
			</div>
			<div class="login-container">
				<div class="account-overview-input-single">
					<label for="tfgg_cp_user_login"  class="account-overview-label"><?php _e('Store Location'); ?></label>
					<select name="tfgg_cp_instorereg_store" id="tfgg_cp_instorereg_store" class="js-example-basic-single required account-overview-input">
						<option value="please select">Please Select...</option>
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
			<div class="login-container">
				<div class="account-overview-input-double">
				<input type="hidden" name="tfgg_cp_instore_set_store" id="tfgg_cp_instore_set_store" value="<?php echo wp_create_nonce('tfgg-cp-instore-set-store'); ?>"/>
					<button id="tfgg_cp_instore_reg_store_submit" type="submit" class="account-overview-button account-overview-standard-button">SET STORE</button>
				</div>
			</div>
		</form>
		<?php
		
		return ob_get_clean();
	}

	function tfgg_sunlync_client_instore_reg_set_store(){
		if((isset($_POST['tfgg_cp_instorereg_store'])) && ((array_key_exists('tfgg_cp_instore_set_store',$_POST))&&
        (wp_verify_nonce($_POST['tfgg_cp_instore_set_store'],'tfgg-cp-instore-set-store')))){	
			//setcookie('instore_reg_store',$_POST['tfgg_cp_instorereg_store'],time()+31556926);
			setcookie('instore_reg_store',$_POST['tfgg_cp_instorereg_store'],2147483647);
			wp_redirect($_SERVER['REQUEST_URI']);
			exit;
		}
	}
	add_action('init','tfgg_sunlync_client_instore_reg_set_store');

    function tfgg_sunlync_client_instore_api_registration(){
		/*2019-12-09 CB V1.2.4.8 - removed nonce verification
		if((isset($_POST['tfgg_cp_user_email'])) && ((array_key_exists('tfgg_cp_register_instore_nonce',$_POST))&&
		(wp_verify_nonce($_POST['tfgg_cp_register_instore_nonce'],'tfgg-cp-register-instore-nonce')))){*/
		if((isset($_POST['tfgg_cp_user_email'])) && ((array_key_exists('tfgg_cp_register_instore_nonce',$_POST)))){
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

			//2019-11-11 CB V1.2.2.3 - if the posted storecode is blank, pull from the cookie
			if((array_key_exists('tfgg_cp_store',$_POST))&&($_POST['tfgg_cp_store']!='')){
				$storecode=$_POST['tfgg_cp_store'];				
			}else{
				$storecode=$_COOKIE['instore_reg_store'];
			}
			
			$demographics = array(
			'firstname'	=> $_POST['tfgg_cp_user_first'],
			'lastname'	=> $_POST['tfgg_cp_user_last'],
			'midinit'	=> '',
			'email'		=> $_POST['tfgg_cp_user_email'],
			'dob'		=> $_POST['tfgg_cp_user_dob'],
			'address'	=> $address,
			'numbers'	=> $numbers,
			'storecode'	=> $storecode,//2019-11-11 CB
			'howhear'	=> $_POST['tfgg_cp_how_hear'],
			'eyecolor'	=> '',
			'gender'	=> $_POST['tfgg_cp_user_gender'],
			'skintype'	=> $_POST['tfgg_cp_skin_type'],
			'userdefined1' => get_option('tfgg_scp_registration_source_label_instore'),
			'userdefined2' => ''
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

			//check to determine if the user exists in sunlync, if not, register them
			$alreadyRegistered=json_decode(tfgg_api_check_user_exists($demographics['firstname'],
			$demographics['lastname'],$demographics['dob'],$demographics['email']));
			
			if(StrToUpper($alreadyRegistered->results)==='SUCCESS'){
				//user exists in SunLync
				tfgg_cp_errors()->add('warning_existing_user', __('An account with these details already exists<br/>Try resetting your password from the login page or contact the support department for assistance: <a href="mailto:'.get_option('tfgg_scp_customer_service_email').'?subject=Registration Issues" target="_blank">'.get_option('tfgg_scp_customer_service_email').'</a>'));	
			}else{
				//no user in SunLync, insert as a new user

				$reg_result=json_decode(tfgg_api_insert_user_proprietary($demographics, $commPref,get_option('tfgg_scp_reg_promo_instore'), ''));
				if(strtoupper($reg_result->results)=='SUCCESS'){

					//now set the password

					tfgg_api_set_password($reg_result->clientnumber,$_POST['tfgg_cp_user_pass']);

					//2019-11-14 CB V1.2.3.1 - added palceholders to replace data
					$successMessage=get_option('tfgg_scp_instore_registration_success');
					$successMessage=str_replace('!@#firstname#@!',$_POST['tfgg_cp_user_first'],$successMessage);
					$successMessage=str_replace('!@#lastname#@!',$_POST['tfgg_cp_user_last'],$successMessage);
					$successMessage=str_replace('!@#clientnumber#@!',$reg_result->clientnumber,$successMessage);
                    tfgg_cp_errors()->add('success_reg_complete', __($successMessage));					
				}else{
					tfgg_cp_errors()->add('error_cannot_reg', __('There was an error registering your account: '.$reg_result->response.
					'<br/>Please contact the support department for assistance: <a href="mailto:'.get_option('tfgg_scp_customer_service_email').'?subject=Registration Issues" target="_blank">'.get_option('tfgg_scp_customer_service_email').'</a>'));
				}
			}


		}
	}
    add_action('init','tfgg_sunlync_client_instore_api_registration');

?>