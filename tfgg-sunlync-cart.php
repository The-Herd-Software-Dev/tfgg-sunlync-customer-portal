<?php

    function tfgg_scp_display_cart(){
        ob_start();

        $cartContents = json_decode(tfgg_scp_get_cart_contents());
        //var_dump($cartContents);
        if(StrToUpper($cartContents->results) === 'SUCCESS'){
           // tfgg_scp_display_cart_banner();
           // echo '<br/><br/><br/><br/>';
            tfgg_sunlync_cp_show_error_messages();
            echo'<div class="row" id="tfgg_scp_cart_contents" >';
                tfgg_scp_cart_items_display($cartContents->header, $cartContents->lineItems);
                tfgg_scp_cart_header_display($cartContents->header, $cartContents->paymentItems);
            echo '</div>';//row

                //echo '<br/>'.tfgg_scp_cart_continue_shopping().'<br/>';
            echo '<br/><hr/><br/>';
            //tfgg_scp_cart_header_display($cartContents->header);
            echo '<br/><br/>';
            
            ?>
            <script>
                tfggSetCartLinkQty(<?php echo $cartContents->header->qty; ?>);
            </script>
            <?php
        }else{
            if(empty(tfgg_cp_errors()->get_error_messages())){
                tfgg_scp_empty_cart_display();
            }else{
                tfgg_scp_successful_cart_finalize();
            }
            ?>
            <script>
            tfggSetCartLinkQty(0);
            </script>
            <?php
        }
        
        return ob_get_clean();
    }

    function tfgg_scp_cart_items_display($header, $lineItems){
        ?>

        <div id="cart-items-left" class="col-lg-4">

            <div class="cart-items-header"><h4>ITEMS</h4></div>

            <?php
                $i=0;
                foreach($lineItems as &$details){
                    $i++;
            ?>

                <div class="overlay-items-item-container" id="tfgg_cart_item_row_<?php echo $i;?>">
                    <div class="cart-items-item">
                        <span class="overlay-items-item-description"><?php echo $details->alias; ?></span>
                        <span class="overlay-items-item-price">&#163;<?php echo ($details->Qty*$details->PPU);?></span>
                        <br />
                        <span class="overlay-items-item-quantity-label">Quantity:</span>
                        <span class="overlay-items-item-quantity-value"><?php echo $details->Qty; ?> @ &#163;<?php echo $details->PPU; ?></span>
                        <br />
                        <div class="overlay-items-item-buttongroup">
                            <a href="javascript:  tfggRemoveCartItem('<?php echo $details->ID;?>','tfgg_cart_item_row_<?php echo $i;?>')" class="overlay-items-item-link">REMOVE</a>
                        </div>             
                    </div>
                </div>


            <?php 
                }
            ?>

            <hr />
            <br />
            <div class="cart-items-header"><h4>ORDER SUMMARY</h4></div>

            <div id="cart-totals-content-labels-container">

            <span class="cart-totals-content-label">Items</span>
            <span class="cart-totals-content-value"><?php echo $header->qty; ?></span>

            <br />

            <span class="cart-totals-content-label">Sub-total</span>
            <span class="cart-totals-content-value">&#163;<?php echo $header->subtotal; ?></span>

            <br />
            <?php
            if ($header->payments>0){
            ?>
            <span class="cart-totals-content-label">Payments</span>
            <span class="cart-totals-content-value">&#163;<?php echo $header->totalPayments; ?></span>
            <br />
            <?php
            }
            ?>

            <span class="cart-totals-content-label overlay-totals-content-total-line">Total</span>
            <span class="cart-totals-content-value overlay-totals-content-total-line">&#163;<?php echo ($header->total - $header->totalPayments); ?></span>

            </div>

        </div>

        <?php
    }

    function tfgg_scp_successful_cart_finalize(){
        //tfgg_scp_display_cart_banner();
        ?>
        <br/<br/><br/><br/>
        <?php  
            tfgg_sunlync_cp_show_error_messages();
            tfgg_scp_cart_continue_shopping();
        ?>
        <br/><br/>
        <?php
    }

    function tfgg_scp_cart_header_display($header, $paymentItems){
        ?>


        <div id="cart-items-right" class="col-lg-4">

                <div id="cart-totals-content">


                    <?php
                    if($header->payments < -1){
                    ?>
                    <div id="cart_payment_items_container">
                        <h4>Payments</h4>
                        <?php
                        foreach($paymentItems as &$details){
                        ?>
                            <span class="cart-totals-content-label overlay-totals-content-total-line"><?php echo $details->ExternalDesc;?>: </span>
                            <span class="cart-totals-content-value overlay-totals-content-total-line">&#163;<?php echo $details->Amt?></span>

                            <br />    
                        <?php
                        }
                        ?>
                        

                    </div>
                    <?php
                    }
                    ?>
                    
        
                    <h4><?php _e('Please select your payment type');?></h4>
                    <div class="overlay-button-container">
                        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                            <div class="btn-group d-flex" role="group" aria-label="Second group" style="width:100%;">
                                <button type="button" class="btn account-overview-button account-overview-standard-button-active overlay-checkout-group-button"
                                id="sagepayCartPayment" onclick="tfgg_scp_toggle_cart_payment('sage');">Credit Card</button>
                                <button type="button" class="btn account-overview-button account-overview-standard-button overlay-checkout-group-button"
                                id="paypalCartPayment" onclick="tfgg_scp_toggle_cart_payment('paypal');">PayPal</button>
                            </div>
                        </div>
                        <br/>
                        <div id="paypal-button-container" style="display:none"></div>
                        <?php echo tfgg_scp_display_sage_entry_form();?>
                    </div>
                    </div>
                </div>

            </div>
        </div>
        <div id="tfgg_scp_cart_finalized" class="alert alert-success" style="display:none">
            <?php
            $message=get_option('tfgg_scp_cart_success_message');
            $message=str_replace('!@#receiptnumber#@!','<span id="tfgg_scp_cart_finalized_receipt"></span>',$message);
            echo $message;
            ?>
        </div>
        <?php 
        if(($header->total - $header->totalPayments)>0){
            tfgg_scp_cart_display_paypal_buttons($header);
        }
    }

    function tfgg_scp_display_cart_banner(){
        ?>
        <div class="cart-banner">
        <h3>SHOPPING CART</h3>
        <div id="cart_banner_button_container"><?php tfgg_scp_cart_continue_shopping(); ?></div>
        </div>
        <?php  
    }

    function tfgg_scp_display_cart_successful_add(){

        $viewCartURL=esc_url(add_query_arg('viewcart','cart',site_url(get_option('tfgg_scp_cart_slug'))));

        ?>
        <div class="modal fade" id="tfgg_scp_cart_add" tabindex="-1" role="dialog" aria-labelledby="tfgg_scp_cart_add" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-body" id="tfgg_scp_cart_add_message">
                </div>
                <div class="modal-footer">
                    <button id="tfgg_scp_cart_add_pay_btn" type="button" class="account-overview-button cart-standard-button-paynow account-overview-appt-cancel-button" onclick="tfgg_scp_changePage('<?php echo $viewCartURL; ?>');">Pay Now</button>
                    <button id="tfgg_scp_cart_add_continue_btn" type="button" class="account-overview-button account-overview-standard-button account-overview-appt-cancel-button" data-dismiss="modal">CONTINUE SHOPPING</button>
                </div>
                </div>
            </div>
        </div>
        <?php
    }

    function tfgg_scp_cart_continue_shopping(){
    ?>      
        <form action="<?php echo site_url(get_option('tfgg_scp_cart_slug'));?>">
            <button type="submit" class="account-overview-button account-overview-standard-button">CONTINUE SHOPPING</button>
        </form>     
    <?php
    }

    function tfgg_scp_display_sage_cart(){
        return false;//this is for use in the 'drop in cart' integration
        if((get_option('tfgg_scp_cart_allow_sage_payment','0')=='0')||
        (get_option('tfgg_scp_cart_sage_key')=='')||(get_option('tfgg_scp_cart_sage_pass')=='')){
            return false;
        }
        if(!tfgg_scp_sagepay_generate_merchant_session_key()){
            return false;
        }
        ?>
        <script src="https://pi-test.sagepay.com/api/v1/js/sagepay.js"></script>
        
        <script>
            sagepayCheckout({ merchantSessionKey: '<?php echo $_SESSION['sageMerchantSession'];?>' }).form();
        </script>
        <?php
    }

    function tfgg_scp_display_sage_entry_form(){
        $demographics = json_decode(tfgg_api_get_client_demographics(tfgg_cp_get_sunlync_client()));
        $demographics = $demographics->demographics[0];
    ?>
        <div id="sagepay-button-container">
            <form action="" method="post" id="tfgg_scp_sagepay_cart"> 
                <div class="registration-container-main">
                    <div class="account-overview-input-single-left">
                        <h4><?php _e('Billing Details');?></h4>
                        <div class="registration-container">
                            <div class="account-overview-input-single">
                                <label for="tfgg_cp_user_email" class="account-overview-label"><?php _e('Email'); ?></label>
                                <input data-alertpnl="new_reg_email" name="tfgg_cp_user_email" id="tfgg_cp_user_email" class="required account-overview-input" type="email" value="<?php _e($demographics->email);?>"/>
                                <div style="display:none" id="new_reg_email" class="reg_alert"></div> 
                            </div>
                        </div>

                        <div class="registration-container">
                            <div class="account-overview-input-double">
                                <label for="tfgg_cp_user_first" class="account-overview-label"><?php _e('First Name'); ?></label>
                                <input data-alertpnl="new_reg_fname" name="tfgg_cp_user_first" id="tfgg_cp_user_first" class="required account-overview-input" type="text" value="<?php _e($demographics->first_name);?>"/>
                                <div style="display:none" id="new_reg_fname" class="reg_alert"></div>
                            </div>
                            <div class="account-overview-input-single">
                                <label for="tfgg_cp_user_last" class="account-overview-label"><?php _e('Last Name'); ?></label>
                                <input data-alertpnl="new_reg_lname" name="tfgg_cp_user_last" id="tfgg_cp_user_last" class="required account-overview-input" type="text" value="<?php _e($demographics->last_name);?>"/>
                                <div style="display:none" id="new_reg_lname" class="reg_alert"></div>
                            </div>
                        </div>

                        <div class="registration-container">
                            <div class="account-overview-input-double">
                                <label for="tfgg_cp_street_address" class="account-overview-label"><?php _e('Street Address'); ?></label>
                                <input data-alertpnl="new_reg_street_address" id="tfgg_cp_street_address" name="tfgg_cp_street_address" class="required account-overview-input" type="text" value="<?php _e($demographics->address1);?>"/>
                                <div style="display:none" id="new_reg_street_address" class="reg_alert"></div>
                            </div>
                            <div class="account-overview-input-single">
                                <label for="tfgg_cp_street_address_2" class="account-overview-label"><?php _e('Unit / Apt. #'); ?></label>
                                <input data-alertpnl="new_reg_street_address_2_alertpnl" name="tfgg_cp_street_address_2" id="tfgg_cp_street_address_2" class="account-overview-input" type="text" value="<?php _e($demographics->address2);?>"/>
                                <div style="display:none" id="new_reg_street_address_2_alertpnl" class="reg_alert"></div>
                            </div>
                        </div>
                        <div class="registration-container">
                            <div class="account-overview-input-double">
                                <label for="tfgg_cp_city" class="account-overview-label"><?php _e('City'); ?></label>
                                <input data-alertpnl="new_reg_city" id="tfgg_cp_city" name="tfgg_cp_city" class="required account-overview-input" type="text" value="<?php _e($demographics->city);?>"/>
                                <div style="display:none" id="new_reg_city" class="reg_alert"></div>
                            </div>
                            <div class="account-overview-input-single">
                                <label for="tfgg_cp_post_code" class="account-overview-label"><?php _e('Post Code'); ?></label>
                                <input data-alertpnl="new_reg_post_code_alertpnl" name="tfgg_cp_post_code" id="tfgg_cp_post_code" class="required account-overview-input" type="text" value="<?php _e($demographics->zip);?>"/>
                                <div style="display:none" id="new_reg_post_code_alertpnl" class="reg_alert"></div>
                            </div>
                        </div>

                        <div class="registration-container">
                            <div class="account-overview-input-single">
                            <input name="tfgg_cp_update_demographics" id="tfgg_cp_update_demographics" class="required account-overview-survey-input" type="checkbox"/>
                            <label for="tfgg_cp_update_demographics" style="color:#F16631; font-weight:700px; padding-left: 5px;"><?php _e('Save updated account information');?></label>	
                            </div>   
                        </div>                     
                        <hr/>

                        <h4><?php _e('Card Details');?></h4>
                        <div class="registration-container">
                            <div class="account-overview-input-single">
                                <label for="tfgg_cp_sage_card_name" class="account-overview-label"><?php _e('Name On Card'); ?></label>
                                <input data-card-details="cardholder-name" data-alertpnl="tfgg_cart_card_name_alertpnl" id="tfgg_cp_sage_card_name" class="required account-overview-input" type="text"/>
                                <div style="display:none" id="tfgg_cart_card_name_alertpnl" class="reg_alert"></div> 
                            </div>
                        </div>
                        <div class="registration-container">
                            <div class="account-overview-input-single">
                                <label for="tfgg_cp_sage_card_number" class="account-overview-label"><?php _e('Card number'); ?></label>
                                <input data-card-details="card-number" data-alertpnl="tfgg_cart_card_number_alertpnl" id="tfgg_cp_sage_card_number" class="required account-overview-input" type="text" placeholder="0000 0000 0000 0000"/>
                                <div style="display:none" id="tfgg_cart_card_number_alertpnl" class="reg_alert"></div> 
                            </div>
                        </div>
                        <div class="registration-container">
                            <div class="account-overview-input-double">
                                <label for="tfgg_cp_sage_card_expiry" class="account-overview-label"><?php _e('Expiry'); ?></label>
                                <input data-card-details="expiry-date" data-alertpnl="tfgg_cart_card_expiry_alertpnl" id="tfgg_cp_sage_card_expiry" class="required account-overview-input" type="text" placeholder="MMYY"/>
                                <div style="display:none" id="tfgg_cart_card_expiry_alertpnl" class="reg_alert"></div>
                            </div>
                            <div class="account-overview-input-single">
                                <label for="tfgg_cp_sage_card_cvc" class="account-overview-label"><?php _e('CVC'); ?></label>
                                <input data-card-details="security-code" data-alertpnl="tfgg_cart_card_cvc_alertpnl" id="tfgg_cp_sage_card_cvc" class="required account-overview-input" type="text" placeholder="000"/>
                                <div style="display:none" id="tfgg_cart_card_cvc_alertpnl" class="reg_alert"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="cartid" value="<?php echo $_SESSION['tfgg_scp_cartid'];?>"/>
                <input type="hidden" name="tfgg_cp_cart_sage_nonce" value="<?php echo wp_create_nonce('tfgg-cp-cart-sage-nonce'); ?>"/> 
                <input type="hidden" name="card-identifier"/>
                <input type="hidden" name="sageMerchantSession" id="sageMerchantSession"/> 
                <div id="sp-container"></div>
                <button type="submit" class="account-overview-button account-overview-standard-button overlay-checkout-button" id="tfgg_scp_cart_complete" >COMPLETE PURCHASE</button>
            </form>

            <script src="https://pi-test.sagepay.com/api/v1/js/sagepay.js"></script>
            <script>
                document.getElementById('tfgg_scp_cart_complete')
                    .addEventListener('click', function(e) {
                        event.preventDefault();
                        //validate data entry
                        var validateResult = true;
                        
                        var alertPanels = document.querySelectorAll('.reg_alert');
                        //reset the warnings
                        alertPanels.forEach(function(alertPnl){
                            alertPnl.innerHTML='';
                            alertPnl.style.display='none';
                        });
                        
                        var inputFields = document.querySelectorAll('.required');
                        inputFields.forEach(function(inputData){

                            if(inputData.value==''){
                                validateResult = false;
                                var label = jQuery('label[for="'+inputData.id+'"]').text();
                                var alertPnl = document.getElementById(inputData.dataset.alertpnl);
                                alertPnl.innerHTML=label+' is required';
                                alertPnl.style.display='';  
                            }

                            //alert(inputData.dataset.alertpnl);
                        });

                        if(!validateResult){return false};

                        tfgg_scp_sage_cart_merchant_session_key(function(merchantKey){
                            sagepayOwnForm({ merchantSessionKey: merchantKey })
                            .tokeniseCardDetails({
                                cardDetails: {
                                    cardholderName: document.querySelector('[data-card-details="cardholder-name"]').value,
                                    cardNumber: document.querySelector('[data-card-details="card-number"]').value,
                                    expiryDate: document.querySelector('[data-card-details="expiry-date"]').value,
                                    securityCode: document.querySelector('[data-card-details="security-code"]').value
                                },
                                onTokenised : function(result) {
                                    if (result.success) {
                                        document.querySelector('[name="card-identifier"]').value = result.cardIdentifier;
                                        document.querySelector('[name="sageMerchantSession"]').value = merchantKey;
                                        document.getElementById('tfgg_scp_sagepay_cart').submit();
                                    } else {
                                        //alert(JSON.stringify(result));
                                        //var errors = JSON.parse(result.errors);
                                        //alert(errors);
                                        //console.log(result.errors);
                                        result.errors.forEach(function(thisError){
                                            if(thisError.message.indexOf('card number')>0){
                                                var pnl=document.getElementById('tfgg_cart_card_number_alertpnl');  
                                            }else if(thisError.message.indexOf('security code')>0){
                                                var pnl=document.getElementById('tfgg_cart_card_cvc_alertpnl');
                                            }else if(thisError.message.indexOf('expiry')>0){
                                                var pnl=document.getElementById('tfgg_cart_card_expiry_alertpnl');
                                            }
                                            pnl.innerHTML=pnl.innerHTML+'<br/>'+thisError.message;
                                            pnl.style.display=''; 
                                        });
                                    }
                                }
                            });
                        });
                        
                                           
                }, false);

            </script>

        </div>
    <?php
    }

    function tfgg_scp_cart_display_paypal_buttons($header){
        if((get_option('tfgg_scp_cart_allow_paypal_payment','0')=='0')||
        (get_option('tfgg_scp_cart_paypal_clientid')=='')){
            return false;
        }
        ?>
        <script type="text/javascript">

        paypal.Buttons({
            createOrder: function(data, actions) {
            // This function sets up the details of the transaction, including the amount and line item details.
            return actions.order.create({
                purchase_units: [{
                amount: {
                    "custom_id": "<?php echo $_SESSION['tfgg_scp_cartid']; ?>",
                    "value": "<?php echo ($header->total-$header->totalPayments); ?>"
                },
                description: "<?php echo $header->processingStoreName; ?> Services"
                }]
                });
            },
            onApprove: function(data, actions) {
            // This function captures the funds from the transaction.
            return actions.order.capture().then(function(details) {

                jQuery.post('<?php echo admin_url( 'admin-ajax.php' );?>',{
                'action'    : 'tfgg_scp_post_payment_item',
                'data'      : {amt: details.purchase_units[0].amount.value,
                    externalID: details.id,
                    externalDesc:'PayPal'},
                'dataType'  : 'json',
                'pathname'  : window.location.pathname
                },function(data){
                    jQuery('#tfgg_scp_cart_complete').prop('disabled', false);
                    
                    jQuery.get('<?php echo admin_url( 'admin-ajax.php' );?>',{
                        'action'    : 'tfgg_api_finalize_cart',
                        'dataType'  : 'json',
                        'pathname'  : window.location.pathname
                    },function(data){
                        var obj = jQuery.parseJSON(data);

                        jQuery('#tfgg_scp_cart_contents').hide();
                        jQuery('#tfgg_scp_cart_finalized_receipt').text(obj["receipt"]);
                        jQuery('#tfgg_scp_cart_finalized').css('display','block');

                        tfggSetCartLinkQty(0);

                    });

                }); 
            });
            }
        }).render('#paypal-button-container');

        </script>
        <?php
    }

    function tfgg_scp_empty_cart_display(){
        //tfgg_scp_display_cart_banner();
        ?>
        
        <br/<br/><br/><br/>
        <div id="" class="alert alert-warning" >
                <p> 
                    No items currently in your shopping cart
                </p>
            </div>
        <?php  
            tfgg_scp_cart_continue_shopping();
        ?>
        <br/><br/>
        <?php
    }

    function tfgg_scp_service_exp_date($exp_days, $exp_date, $open_ended){
        if(($exp_days > 0)&&($open_ended == 0)){
            //service expires x days after purchase
            $exp = new DateTime();
            $exp->add(new DateInterval('P'.$exp_days.'D'));
            $exp = $exp->format('d-m-Y');
        }elseif(($exp_date !='')&&($open_ended == 0)){
            //fixed expiration date regardless of purchase date
            $exp = tfgg_format_date_for_display($exp_date);
        }elseif($open_ended == 1){
            //no exp
            $exp='Does not expire';
        }

        return $exp;
    }

    function tfgg_scp_display_services_for_sale(){
        ob_start();

        if(isset($_SESSION['tfgg_scp_cart_store'])){$browsingStore = $_SESSION['tfgg_scp_cart_store'];}else{$browsingStore=$_SESSION['clientHomeStore'];}
        
        //$packageList = json_decode(tfgg_scp_get_packages_from_api(tfgg_scp_get_packages_selected_for_api()));
        $packageList = json_decode(tfgg_scp_get_packages_from_api(tfgg_scp_get_packages_selected_for_api(), $browsingStore));
        
        if(StrToUpper($packageList->results) === 'SUCCESS'){
            $packageList = $packageList->packages;
        }else{
            $packageList = '';    
        }

        $membershipList = json_decode(tfgg_scp_get_memberships_from_api(tfgg_scp_get_memberships_selected_for_api(), $browsingStore));
        if(StrToUpper($membershipList->results) === 'SUCCESS'){
            $membershipList = $membershipList->memberships;
        }else{
            $membershipList = '';    
        }

        tfgg_scp_display_store_service_selection();
        
        if($packageList<>''){
            ?>
            <div style="display:block;">
                <h4><?php echo get_option('tfgg_scp_package_header_label');?></h4>
                <hr />
            </div>
            <?php
                if(get_option('tfgg_scp_package_allow_search')==1){
            ?>
            <div>
                <label for="tfgg_cart_package_filter" class="account-overview-label"><?php _e('Filter by name'); ?></label>
                <input id="tfgg_cart_package_filter" name="tfgg_cart_package_filter" class="account-overview-input" type="text"/>							
            </div>
            <br/>
            <div id="tfgg_package_search_warning" class="alert alert-warning" style="display: none;">
                <p> 
                    No items match your search
                </p>
            </div>
            <br/>
            <?php
                }//allow search

            //loop through the allowed services and output
            $rowCounter = 1;
            echo '<div id="tfgg_scp_package_for_sale_list" class="row" style="padding: 10px">';

            foreach($packageList as &$packageDetails){
                
                switch(StrToUpper($packageDetails->unit_type)){
                    case 'M':$unitType = get_option('tfgg_scp_package_unit_minutes');
                    case 'S':$unitType = get_option('tfgg_scp_package_unit_sessions');
                    case 'C':$unitType = get_option('tfgg_scp_package_unit_credits');
                }

                ?>

                <div class="col-lg-3 services-items-item pack-sale-container" id="tfgg_scp_pack_sale_<?php echo $packageDetails->package_id;?>"
                    data-packagenumber="<?php echo $packageDetails->package_id;?>"
                    data-packagename="<?php echo $packageDetails->description;?>">

                    <span class="overlay-items-item-description"><?php echo $packageDetails->alias;?></span>
                    <span class="overlay-items-item-price">&#163;<?php echo $packageDetails->price; ?></span>
                    <br />
                    <span class="overlay-items-item-quantity-label">Units:</span>
                    <span class="overlay-items-item-quantity-value"><?php echo $packageDetails->num_units.' ('.$packageDetails->unit_type.')'; ?></span>
                    <br />
                    <span class="overlay-items-item-quantity-label"> Expiration:</span>

                    <span class="overlay-items-item-quantity-value"><?php echo tfgg_scp_service_exp_date($packageDetails->exp_days, $packageDetails->exp_date, $packageDetails->open_ended); ?></span>

                    <br />
                    <div class="overlay-items-item-buttongroup">
                        <a href="javascript:tfggPostCartItem('P','<?php echo $packageDetails->package_id;?>','1')" class=" cart-paynow-font-color overlay-items-item-link">BUY NOW</a>         
                    </div>
                </div>


            <?php


            }//foreach
            echo '</div></div>';
            //before anything else, we will add some padding here
            echo '<br/><br/>';        
        }//packageList<>''

        if($membershipList<>''){
            ?>
            <div style="display:block;">
                <h4><?php echo get_option('tfgg_scp_membership_header_label');?></h4>
                <hr />
            </div>
            <?php
                if(get_option('tfgg_scp_membership_allow_search')==1){
            ?>
            <div>
                <label for="tfgg_cart_membership_filter" class="account-overview-label"><?php _e('Filter by name'); ?></label>
                <input id="tfgg_cart_membership_filter" name="tfgg_cart_membership_filter" class="account-overview-input" type="text"/>							
            </div>
            <br/>
            <div id="tfgg_membership_search_warning" class="alert alert-warning" style="display: none;">
                <p> 
                    No items match your search
                </p>
            </div>
            <br/>
            <?php   
                }//allow search

                //loop through the allowed services and output
            $rowCounter = 1;
            echo '<div id="tfgg_membership_for_sale_list" class="row" style="padding: 10px">';


            foreach($membershipList as &$membershipDetails){

                ?>

                <div class="col-lg-3 services-items-membership mems-sale-container" id="tfgg_scp_pack_sale_<?php echo $membershipDetails->membership_id;?>"
                    data-membershipnumber="<?php echo $membershipDetails->membership_id;?>"
                    data-membershipname="<?php echo $membershipDetails->description;?>">

                    <span class="overlay-items-item-description"><?php echo $membershipDetails->alias;?></span>
                    <span class="overlay-items-item-price">&#163;<?php echo $membershipDetails->price; ?></span>
                    <br />

                    <span class="overlay-items-item-quantity-label"> Expiration:</span>

                    <span class="overlay-items-item-quantity-value"><?php echo tfgg_scp_service_exp_date($membershipDetails->exp_days, $membershipDetails->exp_date, $membershipDetails->open_ended); ?></span>

                    <br />
                    <div class="overlay-items-item-buttongroup">
                        <a href="javascript:tfggPostCartItem('M','<?php echo $membershipDetails->membership_id;?>','1')" class=" cart-paynow-font-color overlay-items-item-link">BUY NOW</a>        
                    </div>
                </div>


            <?php
               
            }//foreach
            echo '</div></div>';
            
        }//membershipList<>''
        
        tfgg_scp_display_cart_successful_add();
        return ob_get_clean();
    }

    function tfgg_scp_display_store_service_selection(){
        $storeList = json_decode(tfgg_api_get_stores());
        if(StrToUpper($storeList->results)==='SUCCESS'){
            $storeList = $storeList->stores;
            if(isset($_SESSION['tfgg_scp_cart_store'])){$selected = $_SESSION['tfgg_scp_cart_store'];}else{$selected=$_SESSION['clientHomeStore'];}

            ?>
            <div class="" style="margin-bottom: 1em;">
                <label for="tfgg_scp_store_purchasing_selection"><?php _e('You are viewing packages and services offered by '); ?></label>
                <select name="tfgg_scp_store_purchasing_selection" id="tfgg_scp_store_purchasing_selection" style="max-width: 30%">
                <?php
                    foreach($storeList as &$details){
                        $output='<option value="'.$details->store_id.'" '.($details->store_id === $selected ? "selected" : "").'>'.$details->store_loc.'</option>';
                        echo $output; 
                    }
                ?>
                </select>
                <div style="display:inline">
                <button type="button" class="account-overview-button account-overview-standard-button" onclick="confirmChangeCartStore(<?php echo $selected?>);">Change Store Selection</button>
                </div>

                <div class="modal fade" id="tfgg_scp_store_purchasing_selection_confirm" tabindex="-1" role="dialog" aria-labelledby="tfgg_scp_store_purchasing_selection_confirm" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-body" id="tfgg_scp_store_purchasing_selection_confirm_message">
                            Any items in your cart that cannot be used at the new store location will be removed, continue?
                        </div>
                        <div class="modal-footer">
                            <button id="tfgg_scp_cart_add_pay_btn" type="button" class="account-overview-button account-overview-standard-button account-overview-appt-cancel-button" onclick="changeCartStore();">Yes</button>
                            <button id="tfgg_scp_cart_add_continue_btn" type="button" class="account-overview-button account-overview-standard-button account-overview-appt-cancel-button" data-dismiss="modal">Cancel</button>
                        </div>
                        </div>
                    </div>
                </div>

            </div>
            <?php
        }
    }

?>