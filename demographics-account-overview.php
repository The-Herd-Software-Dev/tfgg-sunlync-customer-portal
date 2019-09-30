<?php

    function account_overview(){
        ob_start();
        $client=tfgg_cp_get_sunlync_client();
        if ($client!=FALSE){
            //$client="0000000002";
            $demographics = json_decode(tfgg_api_get_client_demographics($client)); 
            //var_dump($demographics);
            
            
            if(StrToUpper($demographics->results) === 'SUCCESS'){
                $actualDemographics = $demographics->demographics[0];
                //print_r($actualDemographics);
                //foreach($actualDemographics as $key => $value){
                //    echo $key."::".$value."<br/>";
                //}
                
                $disabled = "";	
	           if (get_option('tfgg_scp_demogrphics_allow') !== '1')
	           { 
	           	$disabled = 'disabled';
	           }
	           else 
	        	$disabled = "";
	        	
			$commPref = json_decode(tfgg_api_get_client_comm_pref($client)); 
			
			if(StrToUpper($commPref->results)==='SUCCESS'){
				$commPref = $commPref->commPref[0];
			}
			
			//var_dump($commPref);
			
			$wp_login = tfgg_cp_get_user_loginid();
			//echo $wp_login;
                
        ?>
       
       <hr />
   
       
        <form id="tfgg_sunlync_cp_demo" method="POST" action="">
    	
	        <div class="account-overview-generic-container">
	        	
	        	<div class="account_overview-section account-overview-demo-info">
	        	
	       <?php
			if (get_option('tfgg_scp_demogrphics_allow') === '1') { ?>
				<div class="mobile-button-container">
					
				</div>
				
			<?php } ?>
	        		
					<div style="display:flex">
			        	<h4>Customer Information</h4>
						<?php
						if (get_option('tfgg_scp_demogrphics_allow') === '1') { ?>
						   <div style="margin-left: auto;" class="mobile-button-container">
								<button id="btn_demo_edit_mobile"   class="account-overview-button account-overview-standard-button" onclick="edit_mode();" type="button"><?php _e('EDIT') ?></button>
								<input type="hidden" name="tfgg_cp_demo_nonce" id="tfgg_cp_demo_nonce_mobile" value="<?php echo wp_create_nonce('tfgg_cp_demo_nonce'); ?>"/>
								<button id="btn_demo_save_mobile" class="account-overview-button account-overview-standard-button" style="display:none;" onclick="ValidDemoInfo();" type="submit"><?php _e('UPDATE') ?></button>
					    		<button id="btn_demo_cancel_mobile" class="account-overview-button account-overview-cancel-button" style="display:none;" onclick="CancelUpdate();" type="button"><?php _e('CANCEL') ?></button>	
							</div>
						<?php } ?>
					</div>
				
			        <?php
			        	tfgg_sunlync_cp_show_error_messages();
			        	if((array_key_exists('existingUser',$_GET))&&($_GET['existingUser']===1)){
			        		?>
			        		<div class="alert alert-warning alert-dismissible fade show">
			        			<span class="warning"><strong>Warning</strong>: This account already existed in store, but, you have now been successfully registered to use our online system!
					        		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		                            	<span aria-hidden="true">&times;</span>
		                        	</button>	
	                        	</span>
			        		</div>
			        		<?php
						}
						
						//2019-09-27 CB V1.0.0.5 - added client number at customer request
			        ?>
					 
					<div class="account-overview-generic-container">
						<div class="account-overview-input-single">
							<span><strong>Customer ID: </Strong><?php echo $client;?></span>
						</div>
					</div>
			    
				    <div class="account-overview-generic-container">
						<div class="account-overview-input-double">
							<label for="tfgg_cp_demo_firstname" class="account-overview-label"><?php _e('First Name') ?></label>
							<input data-alertpnl="alertpnl_firstname" name="tfgg_cp_demo_firstname" id="tfgg_cp_demo_firstname" class="required account-overview-input read-only" readonly <?php echo $disabled ?> type="text" value="<?php echo $actualDemographics->first_name ?>"/>
							<span id="alertpnl_firstname" style="display:none;" class="reg_alert"></span>
						</div>
						<div class="account-overview-input-single">
							<label for="tfgg_cp_demo_lastname" class="account-overview-label"><?php _e('Last Name') ?></label>
							<input data-alertpnl="alertpnl_lastname" name="tfgg_cp_demo_lastname" id="tfgg_cp_demo_lastname" class="required account-overview-input read-only" readonly  <?php echo $disabled ?> type="text" value="<?php echo $actualDemographics->last_name ?>" />
							<span id="alertpnl_lastname" style="display:none;" class="reg_alert"></span>
						</div>


					</div>
					
					<div class="account-overview-generic-container">
						<div class="account-overview-input-single">
							<label for="tfgg_cp_demo_address" class="account-overview-label"><?php _e('Address') ?></label>
							<input data-alertpnl="alertpnl_address" name="tfgg_cp_demo_address" id="tfgg_cp_demo_address" class="required account-overview-input read-only" readonly <?php echo $disabled ?> type="text" value="<?php echo $actualDemographics->address1 ?>" />
							<span id="alertpnl_address" style="display:none;" class="reg_alert"></span>
						</div>
					</div>
					
					<div class="account-overview-generic-container">
						<div class="account-overview-input-single">
							<label for="tfgg_cp_demo_address2" class="account-overview-label"><?php _e('Address 2')  ?></label>
							<input data-alertpnl="alertpnl_address2" name="tfgg_cp_demo_address2" id="tfgg_cp_demo_address2" class="account-overview-input read-only" readonly <?php echo $disabled ?> type="text" value="<?php echo $actualDemographics->address2 ?>" />
							<span id="alertpnl_address2" style="display:none;" class="reg_alert"></span>
						</div>
					</div>					
					
					<div class="account-overview-generic-container">
						<div class="account-overview-input-double">					
							<label for="tfgg_cp_demo_city" class="account-overview-label"><?php _e('City') ?></label>
							<input data-alertpnl="alertpnl_city" name="tfgg_cp_demo_city" id="tfgg_cp_demo_city" class="account-overview-input read-only" readonly <?php echo $disabled ?> type="text" value="<?php echo $actualDemographics->city ?>" />
							<span id="alertpnl_city" style="display:none;" class="reg_alert"></span>
						</div>
					
						<div class="account-overview-input-single">
							<label for="tfgg_cp_demo_postcode" class="account-overview-label"><?php _e('Post Code') ?></label>
							<input data-alertpnl="alertpnl_postcode" name="tfgg_cp_demo_postcode" id="tfgg_cp_demo_postcode" class="required account-overview-input read-only" readonly <?php echo $disabled ?> type="text" value="<?php echo $actualDemographics->zip ?>" />
							<span id="alertpnl_postcode" style="display:none;" class="reg_alert"></span>
						</div>
					</div>
			
	
					<div class="account-overview-generic-container">
						<div class="account-overview-input-single">	
							<label for="tfgg_cp_demo_email" class="account-overview-label"><?php _e('E-Mail') ?></label>
							<input data-alertpnl="alertpnl_email" name="tfgg_cp_demo_email" id="tfgg_cp_demo_email" class="required account-overview-input read-only" readonly <?php echo $disabled ?> type="text" value="<?php echo $actualDemographics->email ?>" />
							<span id="alertpnl_email" style="display:none;" class="reg_alert"></span>
						</div>
					</div>
					
				
					<div class="account-overview-generic-container">
						<div class="account-overview-input-single">	
							<label for="tfgg_cp_demo_cellphone" class="account-overview-label"><?php _e('Mobile Phone') ?></label>
							<input data-alertpnl="alertpnl_mobile" name="tfgg_cp_demo_cellphone" id="tfgg_cp_demo_cellphone" class="required account-overview-input read-only" readonly <?php echo $disabled ?> type="text" value="<?php echo $actualDemographics->cellphone?>" />
							<span id="alertpnl_mobile" style="display:none;" class="reg_alert"></span>
						</div>
					
						<?php
						/*2019-06-04 CB REMOVED
						<div class="account-overview-input-single">	
							<label for="tfgg_cp_demo_homephone"><?php _e('Home Phone') ?><span id="alertpnl_homephone" style="display:none; color:red"></span></label>
							<input data-alertpnl="alertpnl_homephone" name="tfgg_cp_demo_homephone" id="tfgg_cp_demo_homephone" class="required account-overview-input read-only" readonly <?php echo $disabled ?> type="text" value="<?php echo tfgg_format_number_for_display($actualDemographics->homephone) ?>" />
						</div>*/
						?>
					</div>
					
			
		        </div>
		        
		        <div class="account_overview-section account-overview-comm-prefs">
				<div <?php if($commPref->allow==='1'){echo 'style="display:none"';} ?>>
		        	<h4>Communication Preferences</h4>
	
					<p>Allow Marketing:
						<input type="radio" class="radio-button" name="allow_marketing" id="allow_marketing_yes" value="1" disabled <?php echo($commPref->allow==='1' ? 'checked':'');?> onclick="ToggleCommPref('1');"/><label class="radio-label">Yes</label>
						<input type="radio" class="radio-button" name="allow_marketing" id="allow_marketing_no" value="0" disabled <?php echo($commPref->allow==='0' ? 'checked':'');?> onclick="ToggleCommPref('0');"/><label class="radio-label">No</label>
					</p>
					
					<div class="comm_prefs_container">
						<input type="checkbox" class="comm-pref check-button" name="allow_text_marketing" id="allow_text_marketing" value="1" disabled <?php echo($commPref->cell_text==='1' ? 'checked':'');?>/><label class="check-label">Via Text</label>
						<br />
						<input type="checkbox" class="comm-pref check-button" name="allow_email_marketing" id="allow_email_marketing" value="1" disabled <?php echo($commPref->email==='1' ? 'checked':'');?>/><label class="check-label">Via E-mail</label>
						<br />
						<input type="checkbox" class="comm-pref check-button" name="allow_mail_marketing" id="allow_mail_marketing" value="1" disabled <?php echo($commPref->mail==='1' ? 'checked':'');?>/><label class="check-label">Via Mail</label>
						<br />
						<input type="checkbox" class="comm-pref check-button" name="allow_phone_marketing" id="allow_phone_marketing" value="1" disabled <?php echo($commPref->cell_voice==='1' ? 'checked':'');?>/><label class="check-label">Via Phone</label>
					</div>
					
					<br />
				</div>			   
		        	<h4>Login Info</h4>
					
					<div class="account-overview-generic-container">
						<div class="account-overview-input-single">
							<label for="tfgg_cp_demo_username" class="account-overview-label"><?php _e('Login ID') ?></label>
							<input  name="tfgg_cp_demo_username" id="tfgg_cp_demo_username" class="required account-overview-input-readonly read-only" readonly <?php echo $disabled ?> type="text" value="<?php echo $wp_login; ?>" />
							<span id="tfgg_cp_demo_username" style="display:none;" class="reg_alert"></span>
						</div>
					</div>
					
					<div class="account-overview-generic-container">
						<div class="account-overview-input-double">
							<label for="tfgg_cp_demo_password" class="account-overview-label"><?php _e('Password') ?></label>
							<input data-alertpnl="alertpnl_password" name="tfgg_cp_demo_password" id="tfgg_cp_demo_password" class="account-overview-input read-only" readonly <?php echo $disabled ?> type="password" value=""/>
							<span id="alertpnl_password" style="display:none;" class="reg_alert"></span>
						</div>
						<div class="account-overview-input-single">
							<label for="tfgg_cp_demo_password_confirm" class="account-overview-label"><?php _e('Confirm Password') ?></label>
							<input data-alertpnl="alertpnl_password_confirm" name="tfgg_cp_demo_password_confirm" id="tfgg_cp_demo_password_confirm" class="account-overview-input read-only" readonly  <?php echo $disabled ?> type="password" value="" />
							<span id="alertpnl_password_confirm" style="display:none;" class="reg_alert"></span>
						</div>
					</div>
					<div class="account-overview-generic-container">
						<div class="account-overview-input-single" style="font-size: x-small;">
							<span>Passwords must contain:</span><br/>
							<ul style="line-height:0.9;"><li>At least 1 lower and 1 upper case letter</li>
							<li>At least 1 number</li>
							<li>At least 8 characters long</li></ul>
						</div>
					</div>
		        </div>
		        
		        
		    </div>
		    
			<?php
			if (get_option('tfgg_scp_demogrphics_allow') === '1') { ?>
				<div class="standard-button-container" style="padding-left:20px;">
					<input type="hidden" name="tfgg_cp_demo_nonce" id="tfgg_cp_demo_nonce" value="<?php echo wp_create_nonce('tfgg_cp_demo_nonce'); ?>"/>
					<button id="btn_demo_save" class="account-overview-button account-overview-standard-button" style="display:none;" onclick="ValidDemoInfo();" type="submit"><?php _e('UPDATE') ?></button>
					<button id="btn_demo_cancel" class="account-overview-button account-overview-cancel-button" style="display:none;" onclick="CancelUpdate();" type="button"><?php _e('CANCEL') ?></button>
					<button id="btn_demo_edit" class="account-overview-button account-overview-standard-button" onclick="edit_mode();" type="button"><?php _e('EDIT') ?></button>
				</div>
			<?php } ?>
			
	
	
		    </form>
		    <hr />
		    
		    <div class="account-overview-generic-container">
		    	

		        
		        <div class="account_overview-section account-overview-comm-prefs">
					<div style="display:block;">
						<div style="width:50%; float:left;">
							<h4>Booked Appointments</h4>
						</div>
						<div style="">
							<button type="button" class="account-overview-button account-overview-standard-button account-overview-appt-book-button" onclick="location.href='<?php echo get_option('tfgg_scp_cpappt_page'); ?>'">Book Appointment</button>
						</div>
					</div>
					<br/>
		        <?php
		    		$appointments = json_decode(tfgg_api_get_client_appointments($client)); 	 
		    		
		    		if(StrToUpper($appointments->results) === 'SUCCESS'){
		    			$appointments = $appointments->appointments;
		    			foreach($appointments as &$details){
		    				//var_dump($details);
							?>
							<div id="appt-cancel-response<?php echo $details->appt_id;?>" style="display:none" role="alert" class="alert">
							
		    					<span id="appt-cancel-response<?php echo $details->appt_id;?>-message" style="text-align:left;"></span>
		    					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							    	<span aria-hidden="true">&times;</span>
							  	</button>
		    				</div>
		    				<div class="account-overview-appts-container" id="apptContainer<?php echo $details->appt_id;?>">
		    					<table class="account-overview-table">
		    					
		    					<?php if(StrToUpper($details->appt_type_desc)=='TAN') { ?>
		    						<tr class="account_overview_row account_overview_row_header">
			    						<td><span class="account-overview-generic-label">Equipment: </span></td>
			    						<td><span class="account-overview-generic-title"><?php echo $details->equip_type_desc ?></span>
			    						<?php
			    							if(tfgg_scp_can_appt_be_cancelled($details->date,$details->start_time)){
			    								/*onclick="CancelAppt(<?php echo $details->appt_id; ?>);"*/
			    						?>
			    						<button class="account-overview-button account-overview-standard-button account-overview-appt-cancel-button" onclick="CancelAppt(<?php echo $details->appt_id; ?>);">CANCEL</button>
			    						<?php
			    							}//if
			    						?>
			    						</td>
			    					</tr>	
			    				
			    				<?php } else { ?>
			    					<tr class="account_overview_row account_overview_row_header">
			    						<td><span class="account-overview-generic-label">Employee: </span></td>
			    						<td><span class="account-overview-generic-title "><?php echo $details->emp_name ?></span>
			    						<?php
			    							if(tfgg_scp_can_appt_be_cancelled($details->date,$details->start_time)){
			    						?>
			    						<button class="account-overview-button account-overview-standard-button account-overview-appt-cancel-button" onclick="CancelAppt(<?php echo $details->appt_id; ?>);">CANCEL</button></td>
			    						<?php
			    							}//if
			    						?>
			    						</td>
			    					</tr>	
			    				<?php } ?>
			    				
		    						<tr class="account_overview_row">
		    							<td><span class="account-overview-generic-label">Store: </span></td>
		    							<td><span class="account-overview-generic-value"><?php echo $details->store_location ?></span></td>
		    						</tr>
		    						
		    						<tr class="account_overview_row">
		    							<td><span class="account-overview-generic-label">Date: </span></td>
		    							<td><span class="account-overview-generic-value"><?php echo tfgg_format_date_for_display($details->date) . ' ' . tfgg_format_time_for_display($details->start_time) ?></span></td>
		    						</tr>
		    						
		    						<tr class="account_overview_row">
		    							<td><span class="account-overview-generic-label">Duration: </span></td>
		    							<td><span class="account-overview-generic-value"><?php echo $details->duration ?> minutes</span></td>
		    						</tr>
		    						
		    						<tr class="account_overview_row">
		    							<td colspan=2></td>
		    						</tr>
		    					
		    					</table>
		    				</div>
		    				
		    				<?php
		    				
		    			}
		    			
		    			unset($details);
		    			
		    		}else{
		    			?><br /><br /><div><Span>No appointments currently scheduled</Span></div><?php
		    		}
		    		
		        ?>
				</div>
				
				<div class="account_overview-section account-overview-comm-prefs">
		        	<h4>Active Services</h4>
		        <?php
		    		$clientPkgs = json_decode(tfgg_api_get_client_pkgs($client)); 
		    		$clientMems = json_decode(tfgg_api_get_client_mems($client)); 
		    		
		    		if(StrToUpper($clientPkgs->results) === 'SUCCESS'){
		    			$clientPkgs = $clientPkgs->clientPackages;
		    			//["description"]=> string(11) "100 Minutes" ["package_id"]=> string(10) "0000000004" ["purchase_date"]=> string(9) "3/26/2019" ["expiration_date"]=> string(10) "12/30/1899" ["status"]=> string(6) "Active" ["units"]=> string(2) "67" ["unit_type"]=> string(7) "Minutes" ["store_location"]=> string(4) "BFLO"
						//var_dump($clientPkgs);
						//purchase_date
		    			foreach($clientPkgs as &$details){
							//2019-09-27 CB V1.0.0.5 - changed if statement to include call to check of purchased with X months
							if(((StrToUpper($details->status)=='ACTIVE')||(StrToUpper($details->status)=='PURCHASED'))&&
							(tfgg_purchased_within_acceptable_period($details->purchase_date))){
		    					$description=tfgg_delete_all_between('(',')',$details->description);
		    					?>
		    					<div class="account-overview-service-container">
		    						<table class="account-overview-table">
										<tr class="account_overview_row account_overview_row_header">
			    							<td><span class="account-overview-generic-label">Package: </span></td>
			    							<td><span class="account-overview-generic-title "><?php echo $description ?></span></td>
			    						</tr>
			    						<tr class="account_overview_row">
			    							<td><span class="account-overview-generic-label">Purchased: </span></td>
			    							<td><span class="account-overview-generic-value"><?php echo $details->purchase_date ?></span></td>
			    						</tr>
			    						<?php if(strpos($details->expiration_date,'1899')<1){ ?>
			    						<tr class="account_overview_row">
			    							<td><span class="account-overview-generic-label">Expires: </span></td>
			    							<td><span class="account-overview-generic-value"><?php echo $details->expiration_date ?></span></td>
			    						</tr>
			    						<?php } ?>
			    						<tr class="account_overview_row">
			    							<td><span class="account-overview-generic-label">Units Remaining: </span></td>
			    							<td><span class="account-overview-generic-value"><?php echo $details->units ?></span></td>
			    						</tr>
			    						
		    						</table>
			    				</div>
			    				<?php
			    				
		    				}
		    			}
		    			unset($details);
		    		}
		    		
		    		if(StrToUpper($clientMems->results) === 'SUCCESS'){
		    			$clientMems = $clientMems->clientMemberships;
		    		}
		    		
		        ?>
		        </div>
		    	
		    	
		    </div>

	        
        <div id="tfgg_demo_update_response" class="notice is-dismissible" style="display:none">
        	<p><strong><span id="tfgg_demo_update_response_text"></span></strong></p>
        </div> 
        
        <?php
                
            }else{
                echo $demographics->response;                
            }
            
        }else{
            //this is a backup for the code in inc-shortcodes.php
            //echo '<script>location.href="'.get_option('tfgg_scp_cplogin_page').'"</script>';

        }
		return ob_get_clean();
        //return ob_get_contents();
    }
    
    function tfgg_sunlync_demo_udpate(){
    	
		if(isset($_POST['tfgg_cp_demo_firstname']) && wp_verify_nonce($_POST['tfgg_cp_demo_nonce'],'tfgg_cp_demo_nonce')){
			
			$client=tfgg_cp_get_sunlync_client();
			$demographics = json_decode(tfgg_api_get_client_demographics($client));
			$demographics = $demographics->demographics[0];
			
			$updateDemo = false;
			
			//check which values actually need to be updated
			if($_POST['tfgg_cp_demo_firstname']!=$demographics->first_name){
				$updateDemo=true;
				$newFirstName=$_POST['tfgg_cp_demo_firstname'];
			}else{
				$newFirstName='';
			}
			
			if($_POST['tfgg_cp_demo_lastname']!=$demographics->last_name){
				$updateDemo=true;
				$newLastName=$_POST['tfgg_cp_demo_lastname'];
			}else{
				$newLastName='';
			}
			
			if($_POST['tfgg_cp_demo_address']!=$demographics->address1){
				$updateDemo=true;
				$newAddress=$_POST['tfgg_cp_demo_address'];
			}else{
				$newAddress='';
			}
			
			if($_POST['tfgg_cp_demo_address2']!=$demographics->address2){
				$updateDemo=true;
				$newAddress2=$_POST['tfgg_cp_demo_address2'];
			}else{
				$newAddress2='';
			}
			
			if($_POST['tfgg_cp_demo_city']!=$demographics->city){
				$updateDemo=true;
				$newCity=$_POST['tfgg_cp_demo_city'];
			}else{
				$newCity='';
			}
			
			if($_POST['tfgg_cp_demo_postcode']!=$demographics->zip){
				$updateDemo=true;
				$newZip=$_POST['tfgg_cp_demo_postcode'];
			}else{
				$newZip='';
			}
			
			if($_POST['tfgg_cp_demo_email']!=$demographics->email){
				$updateDemo=true;
				$newEmail=$_POST['tfgg_cp_demo_email'];
			}else{
				$newEmail='';
			}
			
			$cellPhone=preg_replace('/\s+/', '', $_POST['tfgg_cp_demo_cellphone']);
			
			if($cellPhone!=$demographics->cellphone){
				$updateDemo=true;
				$newCellPhone=$cellPhone;
			}else{
				$newCellPhone='';
			}
			
			/*2019-06-04 CB  - REMOVED
			$homePhone=preg_replace('/\s+/', '', $_POST['tfgg_cp_demo_homephone']);
			
			if($homePhone!=$demographics->homephone){
				$updateDemo=true;
				$newHomePhone=$homePhone;
			}else{
				$newHomePhone='';
			}*/
			$newHomePhone='';
			
			if($updateDemo){
				$reg_result = json_decode(tfgg_scp_update_demographics(tfgg_cp_get_sunlync_client(),$newFirstName,$newLastName,
					$newAddress,$newAddress2,$newCity,$newZip,
					$newEmail,$newCellPhone,$newHomePhone));
				
				if(strtoupper($reg_result->results)=='SUCCESS'){
					tfgg_cp_errors()->add('success_update_demo',__('Your demographic information was successfully updated'));	
				}else{
					tfgg_cp_errors()->add('error_cannot_update_demo', __('There was an error updating demographic info: '.$reg_result->response));
				}
			}
			
			$updateMarketing=false;
			$commPref = json_decode(tfgg_api_get_client_comm_pref($client));
			$commPref = $commPref->commPref[0];
			
			if($_POST['allow_marketing']!=$commPref->allow){
				$updateMarketing=true;
				$allowMarketing=$_POST['allow_marketing'];
			}else{
				$allowMarketing='';	
			}
			
			if($updateMarketing){
			
				if($allowMarketing=='0'){
					$reg_result = json_decode(tfgg_scp_update_comm_pref(tfgg_cp_get_sunlync_client(),
						'1','0','0','0','0'));
						//donotsolicit == 1
				}else{
					$text=(array_key_exists('allow_text_marketing',$_POST) ? $_POST['allow_text_marketing'] : 0);
					$email=(array_key_exists('allow_email_marketing',$_POST) ? $_POST['allow_email_marketing'] : 0);
					$mail=(array_key_exists('allow_mail_marketing',$_POST) ? $_POST['allow_mail_marketing'] : 0);
					$phone=(array_key_exists('allow_phone_marketing',$_POST) ? $_POST['allow_phone_marketing'] : 0);
					
					$reg_result = json_decode(tfgg_scp_update_comm_pref(tfgg_cp_get_sunlync_client(),
					'0',$text,$email,$mail,$phone));
					//donotsolicit==0
				}
				
				
				
				if(strtoupper($reg_result->results)=='SUCCESS'){
					tfgg_cp_errors()->add('success_update_comm_pref',__('Your marketing preferences were successfully updated'));	
				}else{
					tfgg_cp_errors()->add('error_cannot_update_comm_pref', __('There was an error updating marketing preferences: '.$reg_result->response));
				}
			}
			
			if(array_key_exists('tfgg_cp_demo_password',$_POST)&&$_POST['tfgg_cp_demo_password']!=''){
				$user = wp_get_current_user();
				wp_set_password($_POST['tfgg_cp_demo_password'],$user->ID);
				wp_signon(array('user_login'=>$user->user_login,'user_password'=>$_POST['tfgg_cp_demo_password']));
				tfgg_cp_errors()->add('success_update_password',__('Your password was successfully updated'));
				tfgg_api_sync_password(tfgg_cp_get_sunlync_client(),$_POST['tfgg_cp_demo_password']);//tfgg_api_sync_password will call the password hash
			}
	
		}
	
    }

	add_action('init','tfgg_sunlync_demo_udpate');

?>