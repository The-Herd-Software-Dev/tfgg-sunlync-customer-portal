<?php
    function login_form_display(){
        
		ob_start(); 
		
        echo "<hr /><br />";
        
        if(isset($_GET['login'])&&($_GET['login']='reset')){
        ?>
        	<h3 class="header"><?php _e('Password Reset Request');?></h3>
        	<?php
		        tfgg_sunlync_cp_show_error_messages();
		    ?>
        	<form id="tfgg_cp_api_login_reset" method="POST" action="">
        
        
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
				<?php
					if(get_option('tfgg_scp_cpnewuser_page')!=''){
				?>
				
				<br />
					
				<a class="registration-link" href="<?php echo(get_site_url().'/'.tfgg_scp_remove_slashes(get_option('tfgg_scp_cplogin_page')).'/');?>"><?php _e('Retrun to login page'); ?></a>
				<br />
				<a class="registration-link"  href="<?php echo(get_site_url().'/'.tfgg_scp_remove_slashes(get_option('tfgg_scp_cpnewuser_page')).'/'); ?>"><?php _e('Never used the site before? Register!'); ?></a>
				
				<?php } ?>
			
        	</form>
        <?php
        }else{
        	
        ?>
        <h3 class="header"><?php _e('Login');?></h3>
        <?php
			tfgg_sunlync_cp_show_error_messages();
        ?>
        <form method="POST" id="tfgg_cp_api_login" action="">
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
					<div class="account-overview-input-double">
						<a class="registration-link" href="<?php echo(get_site_url().'/'.tfgg_scp_remove_slashes(get_option('tfgg_scp_cplogin_page')).'/');?>?login=reset"><?php _e('Forgot Password?'); ?></a>
						<?php /*<a class="registration-link" href="<?php echo wp_lostpassword_url();?>">Forgot Password?</a>*/ ?>
					</div>
				
				
				<?php
					if(get_option('tfgg_scp_cpnewuser_page')!=''){
				?>
					<div class="account-overview-input-single">
						<a class="registration-link"  href="<?php echo(get_site_url().'/'.tfgg_scp_remove_slashes(get_option('tfgg_scp_cpnewuser_page')).'/'); ?>" ><?php _e('Never used this site before? Register!'); ?></a>
					</div>

				<?php } ?>
		
				</div>
			
			</div> 
            </form>      
        
        
        <?php
        }
		return ob_get_clean();
        //return ob_get_contents();
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
?>