<?php
    function login_form_display(){
		
        if(isset($_GET['login'])){
			switch($_GET['login']){
				case 'reset':
					return tfgg_scp_reset_password();
					break;
				case 'acct_check':
					if((array_key_exists('tfgg_reg_resp',$_SESSION))&&(isset($_SESSION['tfgg_reg_resp']))){
						switch($_SESSION['tfgg_reg_resp']['fail_code']){
							case 'me':
								//multi-email
								$reg_email = $_SESSION['tfgg_reg_resp']['attempted_email'];
								unset($_SESSION['tfgg_reg_resp']);
								return tfgg_acct_check_multi_email_display($reg_email);
							break;
							case 'ee':
								//existing email
								$reg_email = $_SESSION['tfgg_reg_resp']['attempted_email'];
								unset($_SESSION['tfgg_reg_resp']);
								return tfgg_acct_check_existing_email_display($reg_email);
							break;
							case 'md':
								//multiple demographics
								unset($_SESSION['tfgg_reg_resp']);
								return tfgg_acct_check_multi_demo_display();
							break;
							case 'ed':
								$reg_email = $_SESSION['tfgg_reg_resp']['rtnd_demo']->email;
								unset($_SESSION['tfgg_reg_resp']);
								return tfgg_acct_check_single_demo_diff_email_display($reg_email);
								break;
							case 'insert':
								unset($_SESSION['tfgg_reg_resp']);
								return tfgg_acct_check_advise_registration();
								break;
						}
					}else{
						return tfgg_scp_login_acct_check();
					}
					break;
				default:
					return tfgg_scp_login_form();
					break;
			}
        }else{
			return tfgg_scp_login_form();
		}
    }

	function tfgg_acct_check_multi_email_display($email){
		ob_start(); 

		$cs_email = get_option('tfgg_scp_customer_service_email');
	?>
		<div class="card alert-warning">
			<div class="card-header">
				Multiple Accounts Found
			</div>
			<div class="card-body">
				<h5 class="card-title">Unable to locate your account</h5>
				<p class="card-text">The email address you are attempting to use, <?php echo $email; ?>, is associated with multiple accounts</p>
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

	function tfgg_acct_check_existing_email_display($reg_email){
		ob_start(); 

		$forgot = get_site_url().'/'.tfgg_scp_remove_slashes(get_option('tfgg_scp_cplogin_page'))."?login=reset";
	?>
		<div class="card">
			<div class="card-header alert-info">
				Account E-Mail Exists
			</div>
			<div class="card-body">
				<p class="card-text">The email address you are using, <?php echo $reg_email; ?>, is already associated with an account</p>
				<p class="card-text">Please use the <a href="<?php echo $forgot;?>">Forgot Password Form</a> to request a new password for your account</p>
			</div>
		</div>
		<br/>
	<?php
		return ob_get_clean();
	}

	function tfgg_acct_check_multi_demo_display(){
		ob_start(); 
		$cs_email = get_option('tfgg_scp_customer_service_email');
	?>
		<div class="card alert-warning">
			<div class="card-header">
				Multiple Accounts Found
			</div>
			<div class="card-body">
				<h5 class="card-title">Unable to locate your account</h5>
				<p class="card-text">The information you are attempting to locate your account with matches multiple accounts</p>
				<p class="card-text">Please contact our customer service representatives at <a href="mailto:<?php echo $cs_email; ?>?subject=Registration Issues (Multiple Accounts)"><?php echo $cs_email; ?></a> for assistance</p>
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

	function tfgg_acct_check_single_demo_diff_email_display($reg_email){
		ob_start(); 
		$cs_email = get_option('tfgg_scp_customer_service_email');
		$forgot = get_site_url().'/'.tfgg_scp_remove_slashes(get_option('tfgg_scp_cpnewuser_page'))."?login=reset";
	?>
		<div class="card alert-warning">
			<div class="card-header">
				EMail Conflict
			</div>
			<div class="card-body">
				<h5 class="card-title">There appears to be an issue with your registration</h5>
				<p class="card-text">The information you are using to locate your account is associated with an account that has a different email: <?php echo obfuscate_email($reg_email);?></p>
				<p class="card-text">If this email looks familiar, please use the <a href="<?php echo $forgot;?>">Forgot Password Form</a> to request a new password for that account</p>
				<p class="card-text">Otherwise, contact our customer service representatives at <a href="mailto:<?php echo $cs_email; ?>?subject=Registration Issues (EMail Conflict)"><?php echo $cs_email; ?></a> for assistance</p>
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

	function tfgg_acct_check_advise_registration(){
		ob_start();
		$cs_email = get_option('tfgg_scp_customer_service_email');
		$registration = get_site_url().'/'.tfgg_scp_remove_slashes(get_option('tfgg_scp_cpnewuser_page'));
		?>
		<div class="card alert-warning">
			<div class="card-header">
				No Account Located
			</div>
			<div class="card-body">
				<h5 class="card-title">We are unable to locate a matching account</h5>
				<p class="card-text">The information you are using to locate your account is not associated with an account in our system.</p>
				<p class="card-text">If you've never used our services, we invite you to <a href="<?php echo $registration;?>">Register Now</a></p>
				<p class="card-text">Otherwise, contact our customer service representatives at <a href="mailto:<?php echo $cs_email; ?>?subject=Registration Issues (Cannot Find Account)"><?php echo $cs_email; ?></a> for assistance</p>
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

	function tfgg_scp_login_acct_check(){
		ob_start();
		?>
		<hr/><br/>
			<h3 class="header"><?php _e('Account Check');?></h3>
			<?php
				tfgg_sunlync_cp_show_error_messages();
			?>
			<form method="POST" id="tfgg_scp_login_account_check" action="" autocomplete="FALSE">
			<p class="reset-password-message">
				Please enter your information below and we'll try to locate your account.
			</p>
				<div class="login-container">
					<div class="account-overview-input-single">
						<label for="tfgg_scp_acct_check_email"  class="account-overview-label"><?php _e('Email'); ?></label>
						<input name="tfgg_scp_acct_check_email" id="tfgg_scp_acct_check_email" class="required account-overview-input" type="text"/>
						<div style="display:none" id="tfgg_scp_acct_check_email_alert" class="reg_alert"></div> 
					</div>
				</div>
				<div class="login-container">
					<div class="account-overview-input-single">
						<label for="tfgg_scp_acct_check_firstname"  class="account-overview-label"><?php _e('First Name'); ?></label>
						<input name="tfgg_scp_acct_check_firstname" id="tfgg_scp_acct_check_firstname" class="required account-overview-input" type="text"/>
						<div style="display:none" id="tfgg_scp_acct_check_firstname_alert" class="reg_alert"></div> 
					</div>
				</div>
				<div class="login-container">
					<div class="account-overview-input-single">
						<label for="tfgg_scp_acct_check_lastname"  class="account-overview-label"><?php _e('Last Name'); ?></label>
						<input name="tfgg_scp_acct_check_lastname" id="tfgg_scp_acct_check_lastname" class="required account-overview-input" type="text"/>
						<div style="display:none" id="tfgg_scp_acct_check_lastname_alert" class="reg_alert"></div> 
					</div>
				</div>
				<div class="login-container">
					<div class="account-overview-input-single">
						<label for="tfgg_scp_acct_check_dob"  class="account-overview-label"><?php _e('Date of Birth'); ?></label>
						<input name="tfgg_scp_acct_check_dob" id="tfgg_scp_acct_check_dob" class="required account-overview-input" type="text" readonly="true"/>
						<input data-alertpnl="new_reg_dob" name="tfgg_scp_acct_check_dob_value" id="tfgg_scp_acct_check_dob_value" class="required account-overview-input" type="hidden"/>
						<div style="display:none" id="tfgg_scp_acct_check_dob_alert" class="reg_alert"></div> 
					</div>
				</div>
				<input type="hidden" name="tfgg_sunlync_cp_acct_check_nonce" id="tfgg_sunlync_cp_acct_check_nonce" value="<?php echo wp_create_nonce('tfgg-sunlync-cp-acct-check-nonce'); ?>"/>
				<button id="tfgg_login_acct_check_submit" type="submit" class="account-overview-button account-overview-standard-button" onclick="portalLoginAcctCheck()"><?php _e('Check Account'); ?></button>
			</form>
			<br/><br/>
			<script>
				jQuery( function() {
					var now = new Date();					
					var maxDateAllowed = new Date();
					maxDateAllowed.setFullYear(maxDateAllowed.getFullYear() - 14);
    				jQuery( "#tfgg_scp_acct_check_dob" ).datepicker({
      					changeMonth: true,
						changeYear: true,
						yearRange: "-100:+0",
						maxDate: maxDateAllowed,
						showMonthAfterYear:true,
						dateFormat: 'dd-mm-yy',
						onSelect: function(dateText) {
							jQuery('#tfgg_scp_acct_check_dob_alert').hide();
							jQuery('#tfgg_scp_acct_check_dob_alert').html('');
							
							dateselected = jQuery("#tfgg_scp_acct_check_dob").datepicker('getDate');
							jQuery('#tfgg_scp_acct_check_dob_value').val(jQuery.datepicker.formatDate('yy-mm-dd',dateselected));
							jQuery('#tfgg_scp_acct_check_dob_alert').html('');
						  },//onselect
						  onChangeMonthYear:function(yearText, monthText){
							//get the currently selected date
							jQuery('#tfgg_scp_acct_check_dob_alert').hide();
							jQuery('#tfgg_scp_acct_check_dob_alert').html('');
							dateselected = jQuery("#tfgg_scp_acct_check_dob").datepicker('getDate');	
							if(dateselected!=null){
								//console.log('here');
								if(dateselected.getFullYear()!=yearText){
									dateselected.setFullYear(yearText)
									jQuery("#tfgg_scp_acct_check_dob").datepicker("setDate",dateselected);
								}
								dateselected = jQuery("#tfgg_scp_acct_check_dob").datepicker('getDate');
								jQuery('#tfgg_scp_acct_check_dob_value').val(jQuery.datepicker.formatDate('yy-mm-dd',dateselected));
								jQuery('#tfgg_scp_acct_check_dob_alert').html('');
							}
						  }
    				});
				  } );

				
    			jQuery('.js-example-basic-single').select2();
			</script>
		<?php
		return ob_get_clean();
	}

	function tfgg_scp_login_form(){
		ob_start();
		?>
		<hr/><br/>
		<h3 class="header"><?php _e('Login');?></h3>
        <?php
			tfgg_sunlync_cp_show_error_messages();
        ?>
        	<form method="POST" id="tfgg_cp_api_login" action="" autocomplete="FALSE">
            <div class="login-container">
				<div class="account-overview-input-single">
					<label for="tfgg_cp_user_login"  class="account-overview-label"><?php _e('Email'); ?></label>
					<input name="tfgg_cp_user_login" id="tfgg_cp_user_login" class="required account-overview-input" type="text"/>
                    <div style="display:none" id="login_alert_email" class="reg_alert"></div> 
				</div>
			</div>
				
			<div class="login-container">
				 <div class="account-overview-input-single">
						<label for="tfgg_cp_user_pass" class="account-overview-label"><?php _e('Password'); ?></label>
						<input name="tfgg_cp_user_pass" id="tfgg_cp_user_pass" class="required account-overview-input" type="password"/>
                        <div style="display:none" id="login_alert_password" class="reg_alert"></div> 
				</div>
			</div>
			
			
				<div class="login-container">
					<div class="account-overview-input-double">
						<input type="hidden" name="tfgg_cp_login_nonce" id="tfgg_cp_login_nonce" value="<?php echo wp_create_nonce('tfgg-cp-login-nonce'); ?>"/>
						<button id="tfgg_cp_login_submit" type="submit" class="account-overview-button account-overview-standard-button" onclick="portalLogin('tfgg_cp_api_login');"><?php _e('LOGIN'); ?></button>
					</div>
				</div>
				
				<br />
				
				<div class="login-container">
					<?php echo tfgg_scp_login_reset_pass_link(); ?>	
					<?php echo tfgg_scp_login_account_check_link(); ?>					
					<?php echo tfgg_scp_login_register_link(); ?>
		
				</div>
			
			</div> 
            </form>     
		<?php
		return ob_get_clean();
	}

	function tfgg_scp_reset_password(){
		ob_start();
	?>
	<hr/><br/>
		<h3 class="header"><?php _e('Password Reset Request');?></h3>
		<?php
			tfgg_sunlync_cp_show_error_messages();
		?>
		<form id="tfgg_cp_api_login_reset" method="POST" action="" autocomplete="FALSE">
			<p class="reset-password-message">
				Please enter the login used for the site.<br/>You will receive a new password via email.
			</p>
			
			<div class="login-container">
				<div class="account-overview-input-single">
					<label for="tfgg_cp_user_login" class="account-overview-label"><?php _e('Email'); ?></label>
					<input name="tfgg_cp_user_login" id="tfgg_cp_user_login" class="required account-overview-input" type="text"/>
					<div style="display:none" id="login_alert_email" class="reg_alert"></div> 
				</div>
			</div>
			
			<p>
				<input type="hidden" name="tfgg_sunlync_cp_pass_reset_nonce" id="tfgg_sunlync_cp_pass_reset_nonce" value="<?php echo wp_create_nonce('tfgg-sunlync-cp-pass-reset-nonce'); ?>"/>
				<button id="tfgg_cp_login_submit" type="submit" class="account-overview-button account-overview-standard-button" onclick="portalLoginReset()"><?php _e('Get New Password'); ?></button>
				
			</p>
			<br/>
			
			<div class="login-container">
				<?php echo tfgg_scp_login_link(); ?>	
				<?php echo tfgg_scp_login_account_check_link(); ?>					
				<?php echo tfgg_scp_login_register_link(); ?>
			</div>
		
		</form>
	<?php
		return  ob_get_clean();
	}

	function tfgg_scp_login_link(){
		?>
		<div class="account-overview-input-double">
		<a class="registration-link" href="<?php echo(get_site_url().'/'.tfgg_scp_remove_slashes(get_option('tfgg_scp_cplogin_page')).'/');?>"><?php _e('Retrun to login page'); ?></a>
		</div>
		<?php	
	}

	function tfgg_scp_login_reset_pass_link(){
		?>
		<div class="account-overview-input-double">
			<a class="registration-link" href="<?php echo(get_site_url().'/'.tfgg_scp_remove_slashes(get_option('tfgg_scp_cplogin_page')).'/');?>?login=reset"><?php _e('Forgot Password?'); ?></a>
		</div>
		<?php
	}

	function tfgg_scp_login_account_check_link(){
		?>
		<div class="account-overview-input-double">
			<a class="registration-link" href="<?php echo(get_site_url().'/'.tfgg_scp_remove_slashes(get_option('tfgg_scp_cplogin_page')).'/');?>?login=acct_check"><?php _e('Check your account to link your email'); ?></a>
		</div>
		<?php
	}

	function tfgg_scp_login_register_link(){
	
		if(get_option('tfgg_scp_cpnewuser_page')!=''){
			?>
			<div class="account-overview-input-single">
				<a class="registration-link"  href="<?php echo(get_site_url().'/'.tfgg_scp_remove_slashes(get_option('tfgg_scp_cpnewuser_page')).'/'); ?>" ><?php _e('Never used The Tanning Shop before? Register Now!'); ?></a>
			</div>
		<?php 
		} 
	}

    function tfgg_sunlync_client_login(){
        if(isset($_POST['tfgg_cp_login_nonce']) && wp_verify_nonce($_POST['tfgg_cp_login_nonce'],'tfgg-cp-login-nonce')){
            if((!isset($_POST['tfgg_cp_user_login']))||($_POST['tfgg_cp_user_login']==='')){
    			tfgg_cp_errors()->add('error_empty_username', __('Please enter a login'));	
    		}
    		
    		if((!isset($_POST['tfgg_cp_user_pass']))||($_POST['tfgg_cp_user_pass']==='')){
    			tfgg_cp_errors()->add('error_empty_password', __('Please enter a password'));	
    		}
    		
            $errors = tfgg_cp_errors()->get_error_messages();
			
			//2020-07-13 CB - added trim() to the password
            if(empty($errors)){
                $loginResponse = json_decode(tfgg_cp_api_client_login($_POST['tfgg_cp_user_login'],trim($_POST['tfgg_cp_user_pass'])));
				
                if(strToUpper($loginResponse->results)=='SUCCESS'){
                    tfgg_cp_errors()->add('success_login', __('Success! Logging into account'));
                    $data = $loginResponse->data[0];
					tfgg_cp_set_sunlync_client($data->clientnumber);
                }else{
                    tfgg_cp_errors()->add('error_no_matching_user', __('Email and/or password is incorrect, please try again'));
                }
                $errors = tfgg_cp_errors()->get_error_messages();
            }
        }
        
    }
	add_action('init','tfgg_sunlync_client_login');
	
	function tfgg_sunlync_client_login_reset(){
		if(isset($_POST['tfgg_sunlync_cp_pass_reset_nonce']) && 
    		wp_verify_nonce($_POST['tfgg_sunlync_cp_pass_reset_nonce'],'tfgg-sunlync-cp-pass-reset-nonce')){

				if((!isset($_POST['tfgg_cp_user_login']))||($_POST['tfgg_cp_user_login']==='')){
					tfgg_cp_errors()->add('error_empty_username', __('Please enter a login'));	
				}
				
				$errors = tfgg_cp_errors()->get_error_messages();

				if(empty($errors)){
					$user_login = sanitize_text_field($_POST['tfgg_cp_user_login']);
		
					$resetResponse = json_decode(tfgg_scp_api_pass_reset_request($user_login));
					if(strToUpper($resetResponse->results)=='SUCCESS'){
						tfgg_cp_errors()->add('success_pass_reset', __('An email with a temporary password has been sent to the associated email address'));
					}else{
						tfgg_cp_errors()->add('error_no_matching_user', __('We could not match an account to the login/email provided, please try again'));	
					}
				}

		}
	}
	add_action('init','tfgg_sunlync_client_login_reset');

	function tfgg_scp_process_login_acct_check(){
		if(isset($_POST['tfgg_sunlync_cp_acct_check_nonce']) && 
    		wp_verify_nonce($_POST['tfgg_sunlync_cp_acct_check_nonce'],'tfgg-sunlync-cp-acct-check-nonce')){
			
			//double check that everything is set
			if((!isset($_POST['tfgg_scp_acct_check_email']))||($_POST['tfgg_scp_acct_check_email']==='')){
				tfgg_cp_errors()->add('error_empty_email', __('Please enter an email'));	
			}
			if((!isset($_POST['tfgg_scp_acct_check_firstname']))||($_POST['tfgg_scp_acct_check_firstname']==='')){
				tfgg_cp_errors()->add('error_empty_firstname', __('Please enter a first name'));	
			}

			if((!isset($_POST['tfgg_scp_acct_check_lastname']))||($_POST['tfgg_scp_acct_check_lastname']==='')){
				tfgg_cp_errors()->add('error_empty_lastname', __('Please enter a lastname'));	
			}

			if((!isset($_POST['tfgg_scp_acct_check_dob_value']))||($_POST['tfgg_scp_acct_check_dob_value']==='')){
				tfgg_cp_errors()->add('error_empty_dob', __('Please enter a date of birth'));	
			}

			$errors = tfgg_cp_errors()->get_error_messages();

			if(empty($errors)){
				//2021-03-30 CB - new validation before registration
				$acct_check = json_decode(tfgg_api_multi_step_existing_user_check($_POST['tfgg_scp_acct_check_firstname'],
				$_POST['tfgg_scp_acct_check_lastname'],
				$_POST['tfgg_scp_acct_check_dob_value'],
				$_POST['tfgg_scp_acct_check_email']));
				
				if(StrToUpper($acct_check->results)==='FAIL'){
					//couldn't validate unique account
					//session values will take care of the display
				}else{
					if(isset($reg_validation->process)){
						switch($reg_validation->process){
							case 'insert':
								//alert the user to register as we can't locate their account
							break;
							case 'set':
								//set the password and the email on the account
								$clientNumber=$reg_validation->client->client_id;
								tfgg_api_set_password($clientNumber,$_POST['tfgg_cp_user_pass']);
								tfgg_api_update_single_demo($clientNumber,'email',$demographics['email']);
								$_SESSION['linked_reg']=$clientNumber;
								tfgg_cp_set_sunlync_client($clientNumber);
								//2020-01-12 CB V1.2.4.13 - tfgg_cp_redirect_after_login();
								tfgg_cp_redirect_after_registration();
							break;
						}
					}
				}
			}
		}
	}
	add_action('init','tfgg_scp_process_login_acct_check');
?>