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
        	<form id="tfgg_sunlync_cp_pass_reset" method="POST" action="" autocomplete="OFF">
        
        
        		<p class="reset-password-message">
        			Please enter the login used for the site.<br/>You will receive a new password via email.
        		</p>
        		
			    <div class="login-container">
					<div class="account-overview-input-single">
						<label for="tfgg_cp_user_login" class="account-overview-label">Email</label>
						<input name="tfgg_cp_user_login" id="tfgg_cp_user_login" class="required account-overview-input" type="text"/>
					</div>
				</div>
				
				<p>
					<input type="hidden" name="tfgg_sunlync_cp_pass_reset_nonce" id="tfgg_sunlync_cp_pass_reset_nonce" value="<?php echo wp_create_nonce('tfgg-sunlync-cp-pass-reset-nonce'); ?>"/>
					<button id="tfgg_cp_login_submit" type="submit" class="account-overview-button account-overview-standard-button">Get New Password</button>
					
				</p>
				<?php
					if(get_option('tfgg_scp_cpnewuser_page')!=''){
				?>
				
				<br />
					
				<a class="registration-link" href="<?php echo(get_option('tfgg_scp_cplogin_page'));?>">Return to login page</a>
				<br />
				<a class="registration-link"  href="<?php echo(get_option('tfgg_scp_cpnewuser_page')); ?>">Never used the site before? Register!</a>
				
				<?php } ?>
			
        	</form>
        <?php
        }else{
        	
        ?>
        <h3 class="header"><?php _e('Login');?></h3>
        <?php
            tfgg_sunlync_cp_show_error_messages();
        ?>
        
      
        <form id="tfgg_sunlync_cp_login" method="POST" action=""  autocomplete="OFF">
           
            <div class="login-container">
				<div class="account-overview-input-single">
					<label for="tfgg_cp_user_login"  class="account-overview-label">Email</label>
					<input name="tfgg_cp_user_login" id="tfgg_cp_user_login" class="required account-overview-input" type="text"/>
				</div>
			</div>
				
			<div class="login-container">
				 <div class="account-overview-input-single">
						<label for="tfgg_cp_user_pass" class="account-overview-label">Password</label>
						<input name="tfgg_cp_user_pass" id="tfgg_cp_user_pass" class="required account-overview-input" type="password"/>
				</div>
			</div>
			
			
				<div class="login-container">
					<div class="account-overview-input-double">
						<input type="hidden" name="tfgg_cp_login_nonce" id="tfgg_cp_login_nonce" value="<?php echo wp_create_nonce('tfgg-cp-login-nonce'); ?>"/>
						<button id="tfgg_cp_login_submit" type="submit" class="account-overview-button account-overview-standard-button">LOGIN</button>
					</div>
				</div>
				
				<br />
				
				<div class="login-container">
					<div class="account-overview-input-double">
						<a class="registration-link" href="<?php echo(get_option('tfgg_scp_cplogin_page'));?>?login=reset">Forgot Password?</a>
						<?php /*<a class="registration-link" href="<?php echo wp_lostpassword_url();?>">Forgot Password?</a>*/ ?>
					</div>
				
				
				<?php
					if(get_option('tfgg_scp_cpnewuser_page')!=''){
				?>
					<div class="account-overview-input-single">
						<a class="registration-link"  href="<?php echo(get_option('tfgg_scp_cpnewuser_page')); ?>" >Never used the site before? Register Now!</a>
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
			
			tfgg_cp_errors()->add('error_empty_password', __('Please enter a password'));	

    		$errors = tfgg_cp_errors()->get_error_messages();
    		
    		if(empty($errors)){
	    		//get the user credentials
	    		$user = get_user_by('login', $_POST['tfgg_cp_user_login'] );
	    		//print_r($user);
	    		//username does not exist
	    		if(!$user) {
					// if the user name doesn't exist
					//tfgg_cp_errors()->add('error_no_matching_user', __('No user account found'));
					//2019-09-30 CB V1.0.0.6 - new error message
					tfgg_cp_errors()->add('error_no_matching_user', __('Email and/or password is incorrect, please try again'));					
				}else{
					if(!wp_check_password($_POST['tfgg_cp_user_pass'], $user->user_pass)) {
						// if the password is incorrect for the specified user
						tfgg_cp_errors()->add('error_password_mismatch', __('Incorrect Password'));
					}
				}
				
				//get any errors we may have registered
				$errors = tfgg_cp_errors()->get_error_messages();
				
				if(empty($errors)) {
					$user = wp_signon(array('user_login'=>$_POST['tfgg_cp_user_login'],'user_password'=>$_POST['tfgg_cp_user_pass']));
					/*wp_set_auth_cookie($_POST['tfgg_cp_user_login'], false);
					wp_set_current_user($user->ID, $user->display_name);	
					do_action( 'wp_login', $user->user_login, $user);*/
					tfgg_cp_redirect_after_login();
					
				}
    		}
    		
    	}//if isset
    }
    add_action('init','tfgg_sunlync_client_login');
    
    function tfgg_sunlync_client_pass_reset(){
    	if(isset($_POST['tfgg_sunlync_cp_pass_reset_nonce']) && 
    		wp_verify_nonce($_POST['tfgg_sunlync_cp_pass_reset_nonce'],'tfgg-sunlync-cp-pass-reset-nonce')){
    			
    		if((!isset($_POST['tfgg_cp_user_login']))||($_POST['tfgg_cp_user_login']==='')){
    			tfgg_cp_errors()->add('error_empty_username', __('Please enter a login'));	
    		}
    		
    		$errors = tfgg_cp_errors()->get_error_messages();
    		
    		if(empty($errors)){
    			
    			$user_login = sanitize_text_field($_POST['tfgg_cp_user_login']);
    			
    			$user_data = get_user_by('login', $_POST['tfgg_cp_user_login'] );
    			if(!$user_data) {
					// if the user name doesn't exist
					tfgg_cp_errors()->add('error_no_matching_user', __('There is no account with that username or email address.'));
				}else{
					global $wpdb, $wp_hasher;
					do_action('lostpassword_post');
					
					//these should be the same for sunlync clients
					$user_login = $user_data->user_login;
					$user_email = $user_data->user_email;
					$key        = get_password_reset_key( $user_data );
				
					if ( is_wp_error( $key ) ) {
						//2019-10-07 CB V1.0.1.1 - return the error code
						tfgg_cp_errors()->add('error_generating_reset_key', __('We encountered an error generateing your reset key: '.$key->get_error_message()));
						return $key;
					}
				
					if ( is_multisite() ) {
						$site_name = get_network()->site_name;
					} else {
						/*
						 * The blogname option is escaped with esc_html on the way into the database
						 * in sanitize_option we want to reverse this for the plain text arena of emails.
						 */
						$site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
					}
					if(get_option('tfgg_scp_email_pass_reset')==''){
				
						$message = __( 'Someone has requested a password reset for the following account:' ) . "\r\n\r\n";
						$message .= sprintf( __( 'Site Name: %s' ), $site_name ) . "\r\n\r\n";
						$message .= sprintf( __( 'Username: %s' ), $user_login ) . "\r\n\r\n";
						$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.' ) . "\r\n\r\n";
						$message .= __( 'To reset your password, visit the following address:' ) . "\r\n\r\n";
						$message .= '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ">\r\n";
					}else{
						$message = get_option('tfgg_scp_email_pass_reset');
						//$message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );
						
						$message=str_replace('!@#url#@!',network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ),$message);
						$message=str_replace('!@#sitename#@!',$site_name,$message);
						$message=str_replace('!@#username#@!',$user_login,$message);
						$headers = array('Content-Type: text/html; charset=UTF-8');
					}
				
					$title = sprintf( __( '[%s] Password Reset' ), $site_name );
				
					$title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );
					
				
					if ( $message && ! wp_mail( $user_email, wp_specialchars_decode( $title ), $message, $headers ) ) {
						wp_die( __( 'The email could not be sent.' ) . "<br />\n" . __( 'Possible reason: your host may have disabled the mail() function.' ) );
					}
			
			    	tfgg_cp_errors()->add('success_reset_sent', __('Link for password reset has been emailed to you. Please check your email.'));
									        
				}
    		}
    		
    	}
    }
    add_action('init','tfgg_sunlync_client_pass_reset');
?>