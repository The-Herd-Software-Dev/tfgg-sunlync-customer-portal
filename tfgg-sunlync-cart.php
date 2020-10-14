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

    function tfgg_scp_cart_display_countdown(){
        //2020-10-08 CB V1.2.7.1 - new code to refresh the page, preventing 'expired' promotions from being used
        ?>
        <script type="text/javascript">
            function checklength(i) {
                if (i < 10) {
                    i = "0" + i;
                }
                return i;
            }
            var minutes, seconds, count, counter, timer;
            count = 601; //seconds
            counter = setInterval(timer, 1000);

            function timer() {
                count = count - 1;
                minutes = checklength(Math.floor(count / 60));
                seconds = checklength(count - minutes * 60);
                if (count < 0) {
                    clearInterval(counter);
                    return;
                }
                document.getElementById("timer").innerHTML = minutes + ':' + seconds ;
                if (count === 0) {
                    location.reload();
                }
            }

        </script>
        <span id="cart_countdown">Cart will refresh in: <span id="timer"></span></span>
        <?php
    }

    function tfgg_scp_cart_display_promo_entry(){
    //2020-03-15 CB V1.2.6.1 - new method to allow promo entry
    ?>
    <hr />
    <div class="registration-container">
        <div class="account-overview-input-single">
            <label for="tfgg_cp_promo_entry" class="account-overview-label promo-code-entry-label"  onclick="tfgg_scp_toggle_promo_entry();"><?php _e('Have a Promotion Code?'); ?></label>
            <div id="tfgg_scp_promo_code_entry_box" style="display:none">
                <input name="tfgg_cp_promo_entry" id="tfgg_cp_promo_entry" class="account-overview-input" type="text" value=""/>
                <div class="reg_alert">Only one discount code can be used at a time</div> 
                <div id="tfgg_cp_promo_entry_err_pnl" style="display:none" class="reg_alert"></div> 
                <div style="float:right">
                    <button type="button" class="account-overview-button account-overview-standard-button account-overview-appt-book-button" onclick="tfgg_scp_cart_promo_add();">Add Discount</button>
                </div>
            </div>
        </div>
    </div>   
    <?php   
    }

    function tfgg_scp_cart_items_display($header, $lineItems){
        ?>
        
        <div id="cart-items-left" class="col-lg-4">
        
            <div class="cart-items-header"><h4>ITEMS</h4></div>

            <?php
                $i=0;  
                //var_dump($lineItems);              
                foreach($lineItems as &$details){
                    $i++;
            ?>
                <div class="overlay-items-item-container" id="tfgg_cart_item_row_<?php echo $i;?>">
                    <div class="cart-items-item">
                        <span class="overlay-items-item-description">
                        <?php echo (tfgg_delete_all_between('(',')',$details->alias).' ('.tfgg_delete_all_between('(',')',$header->processingStoreName)).')'; ?>
                        </span>
                        <span class="overlay-items-item-price"><?php echo tfgg_display_currency_symbol();?><?php echo number_format(($details->Qty*$details->PPU),2,'.',',');?></span>
                        <?php
                        if($details->PromoValue>0.00){
                        ?>
                        <br/>
                        <span class="overlay-items-item-description small">Promotion: <?php echo $details->PromoDesc;?></span>
                        <span class="overlay-items-item-price small">-<?php echo tfgg_display_currency_symbol();?><?php echo number_format(($details->PromoValue),2,'.',',');?></span>
                        <br/>
                        <span class="overlay-items-item-description small">Item Total: </span>
                        <span class="overlay-items-item-price small"><?php echo tfgg_display_currency_symbol();?><?php echo number_format(($details->Total),2,'.',',');?></span>
                        <?php
                        }
                        ?>
                        <br />
                        <span class="overlay-items-item-quantity-label">Quantity:</span>
                        <span class="overlay-items-item-quantity-value"><select class="tfgg_scp_cart_qty_select"
                        data-itemid="<?php echo $details->ID; ?>"
                        data-oldQty="<?php echo $details->Qty; ?>"
                        data-itemtype="<?php echo $details->ItemType; ?>"
                        data-keyvalue="<?php echo $details->KeyValue; ?>"
                        data-itemrow="tfgg_cart_item_row_<?php echo  $i; ?>"><?php 
                            for($j=0; $j<(get_option('tfgg_scp_cart_max_item_count',10)+1); $j++){
                                if($j==$details->Qty){$selected='selected';}else{$selected='';}

                                echo '<option value="'.$j.'" '.$selected.'>'.$j.'</option>';
                            }
                        ?></select> @ <?php echo tfgg_display_currency_symbol();?><?php echo number_format($details->PPU,2,'.',','); ?></span>
                        <br />
                        <div class="overlay-items-item-buttongroup">
                            <a href="javascript:tfggRemoveCartItem('<?php echo $details->ID;?>','tfgg_cart_item_row_<?php echo $i;?>')" class="overlay-items-item-link">REMOVE</a>
                            <br />
                        </div>          
                    </div>
                </div>


            <?php 
                }
            ?>
                <?php tfgg_scp_cart_display_promo_entry();?>
            <hr />
            <br />
            <div class="cart-items-header"><h4>ORDER SUMMARY</h4></div>

            <div id="cart-totals-content-labels-container">

            <span class="cart-totals-content-label">Items</span>
            <span class="cart-totals-content-value"><?php echo $header->qty; ?></span>

            <?php
            /*
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
            */
            ?>
            <br/>
            <span class="cart-totals-content-label overlay-totals-content-total-line">Total</span>
            <span class="cart-totals-content-value overlay-totals-content-total-line"><?php echo tfgg_display_currency_symbol();?><?php echo number_format(($header->total - $header->totalPayments),2,'.',','); ?></span>

            
            </div>
            <?php tfgg_scp_cart_display_countdown();?>
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

            //2020-03-01 CB V1.2.5.1 - new code to output gsat tag
            if(array_key_exists('processedCartReceipt',$_SESSION)){
                $cartContents = json_decode(tfgg_scp_get_processed_cart_contents($_SESSION['processedCartReceipt']));
                if(StrToUpper($cartContents->results) === 'SUCCESS'){
                    /*
                    gtag('event', 'purchase', {
                    "transaction_id": "REFERENCE NUMBER",
                    "affiliation": "The Tanning Shop",
                    "value": 23.07,
                    "currency": "GBP",
                    "tax": 0.00,
                    "shipping": 0,
                    "items": [
                    {
                        "id": "PACKAGE ID",
                        "name": "PACKAGE NAME",
                        "list_name": "Search Results",
                        "brand": "The Tanning Shop",
                        "category": "Tanning",
                        "variant": "Tan",
                        "quantity": 2,
                        "price": '2.0'
                    },
                    */
                    $gtag = 'gtag(\'event\', \'purchase\', {
                        "transaction_id": "'.$cartContents->header->receipt.'",
                        "affiliation": "The Tanning Shop",
                        "value": '.$cartContents->header->total.',
                        "currency": "GBP",
                        "tax": 0.00,
                        "shipping": 0,
                        "items": [';
                        
                    foreach($cartContents->lineItems as &$details){
                        $gtag.='{"id": "'.$details->KeyValue.'",
                            "name": "'.$details->Description.'",
                            "list_name": "Search Results",
                            "brand": "The Tanning Shop",
                            "category": "Tanning",
                            "variant": "Tan",
                            "quantity": '.$details->Qty.',
                            "price": '.$details->Total.'},';                    
                    }
                    $gtag=rtrim($gtag,",");
                    $gtag.=']});';

                    echo ('<script>'.$gtag.'</script>');
                    unset($_SESSION['processedCartReceipt']);
                    unset($_SESSION['tfgg_scp_cartid']); 
                }
            }

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
                            <span class="cart-totals-content-value overlay-totals-content-total-line"><?php echo tfgg_display_currency_symbol();?><?php echo $details->Amt?></span>

                            <br />    
                        <?php
                        }
                        ?>
                        

                    </div>
                    <?php
                    }
                    ?>
                    
                    <?php
                        if ((get_option('tfgg_scp_cart_allow_paypal_payment',0)==1)&&
                        (get_option('tfgg_scp_cart_allow_sage_payment',0)==1)){
                    ?>
                    <h4><?php _e('Please select your payment type');?></h4>
                    <?php
                        }
                    ?>
                    <div class="overlay-button-container">
                        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                            <div class="btn-group d-flex" role="group" aria-label="Second group" style="width:100%;">
                                <button <?php if(get_option('tfgg_scp_cart_allow_sage_payment',0)==0){echo('style="display:none;"');}?>
                                type="button" class="btn account-overview-button account-overview-standard-button-active overlay-checkout-group-button"
                                id="sagepayCartPayment" onclick="tfgg_scp_toggle_cart_payment('sage');">Credit/Debit Card</button>
                                <button <?php if(get_option('tfgg_scp_cart_allow_paypal_payment',0)==0){echo('style="display:none;"');}?>
                                type="button" class="btn account-overview-button paypal-standard-button overlay-checkout-group-button"
                                id="paypalCartPayment" onclick="tfgg_scp_toggle_cart_payment('paypal');" ></button>
                            </div>
                        </div>
                        <br/>
                        <div id="paypal-button-container-parent" style="display:none">
                            <div class='reg-checkbox-container'>
                                <input name="tfgg_cp_paypal_tandc_confirm" id="tfgg_cp_paypal_tandc_confirm" class="account-overview-survey-input" type="checkbox"/>
                                <label id="tfgg_cp_paypal_tandc_confirm_label" for="tfgg_cp_paypal_tandc_confirm" style="color:#F16631; font-weight:700px; padding-left: 5px;"><?php echo get_option('tfgg_scp_cart_paypal_tandc_label'); ?></label>	
                                <div style="display:none" id="new_reg_tandc_confirm" class="reg_alert"></div>
                            </div>
                            <br/>
                            <div id="paypal-button-container" style="display:none">
                            </div>
                        </div>
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

        //$viewCartURL=esc_url(add_query_arg('viewcart','cart',site_url(get_option('tfgg_scp_cart_slug'))));
        $viewCartURL=esc_url(site_url(get_option('tfgg_scp_cart_slug')));

        ?>
        <div <?php if(tfgg_ae_detect_ie()){?>style="z-index: 10000 !important; margin-top:25%"<?php } ?> class="modal fade" id="tfgg_scp_cart_add" tabindex="-1" role="dialog" aria-labelledby="tfgg_scp_cart_add" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-body" id="tfgg_scp_cart_add_message">
                </div>
                <div class="modal-footer">
                    <button id="tfgg_scp_cart_add_pay_btn" type="button" class="account-overview-button cart-standard-button-paynow account-overview-appt-cancel-button" onclick="tfgg_scp_changePage('<?php echo $viewCartURL; ?>');">PAY NOW</button>
                    <button id="tfgg_scp_cart_add_continue_btn" type="button" class="account-overview-button account-overview-standard-button account-overview-appt-cancel-button" data-dismiss="modal">CONTINUE SHOPPING</button>
                </div>
                </div>
            </div>
        </div>
        <?php
    }

    function tfgg_scp_cart_continue_shopping(){
    ?>      
        <form action="<?php echo site_url(get_option('tfgg_scp_services_sale_slug'));?>">
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
        if(get_option('tfgg_scp_cart_allow_sage_payment',0)==0){
            return false;
        }
        $demographics = json_decode(tfgg_api_get_client_demographics(tfgg_cp_get_sunlync_client()));
        $demographics = $demographics->demographics[0];

        $commPref = json_decode(tfgg_api_get_client_comm_pref(tfgg_cp_get_sunlync_client()));
        $commPref = $commPref->commPref[0];
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
                        
                        <div class="registration-container" <?php if(get_option('tfgg_scp_cart_save_demographics')==''){echo 'style="display:none"';}?>>
                            <div class="account-overview-input-single">
                            <input name="tfgg_cp_update_demographics" id="tfgg_cp_update_demographics" class="account-overview-survey-input" type="checkbox"/>
                            <label for="tfgg_cp_update_demographics" style="color:#F16631; font-weight:700px; padding-left: 5px;"><?php echo get_option('tfgg_save_cart_demographics_label');?></label>	
                            </div>   
                        </div>

                        <div class="registration-container" <?php if(($commPref->allow==='0')||(get_option('tfgg_scp_cart_save_commpref')=='')){echo 'style="display:none"';} ?>>
                            <div class="account-overview-input-single">
                            <input name="tfgg_cp_update_comm_pref" id="tfgg_cp_update_comm_pref" class="account-overview-survey-input" type="checkbox"/>
                            <label for="tfgg_cp_update_comm_pref" style="color:#F16631; font-weight:700px; padding-left: 5px;"><?php echo get_option('tfgg_scp_cart_save_commpref_label') ?></label>	
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
                                <label for="tfgg_cp_sage_card_expiry" class="account-overview-label"><?php _e('Expiry MMYY'); ?></label>
                                <input data-card-details="expiry-date" data-alertpnl="tfgg_cart_card_expiry_alertpnl" 
                                id="tfgg_cp_sage_card_expiry" class="required account-overview-input" type="text" placeholder="MMYY" maxlength="4"
                                onkeyup="tfgg_input_strip_nonumeric('tfgg_cp_sage_card_expiry');"/>
                                <div style="display:none" id="tfgg_cart_card_expiry_alertpnl" class="reg_alert"></div>
                            </div>
                            <div class="account-overview-input-single">
                                <label for="tfgg_cp_sage_card_cvc" class="account-overview-label"><?php _e('CVC'); ?></label>
                                <input data-card-details="security-code" data-alertpnl="tfgg_cart_card_cvc_alertpnl" 
                                id="tfgg_cp_sage_card_cvc" class="required account-overview-input" type="text" placeholder="000" maxlength="3"
                                onkeyup="tfgg_input_strip_nonumeric('tfgg_cp_sage_card_cvc');"/>
                                <div style="display:none" id="tfgg_cart_card_cvc_alertpnl" class="reg_alert"></div>
                            </div>
                        </div>
                        <div style="display:none" class="registration-container reg-alert" id="tfgg_cart_card_gen_error">
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
            <?php if(get_option('tfgg_scp_cart_sage_pay_sandbox','1')=='1'){ ?>
            <script src="https://pi-test.sagepay.com/api/v1/js/sagepay.js"></script>
            <?php }else{ ?>
            <script src="https://pi-live.sagepay.com/api/v1/js/sagepay.js"></script>
            <?php } ?>
            <script>
                document.getElementById('tfgg_scp_cart_complete')
                    .addEventListener('click', function(e) {
                        event.preventDefault();
                        jQuery('#tfgg_scp_cart_complete').prop('disabled',true);
                        //validate data entry
                        jQuery('#tfgg_scp_cart_complete').text('PROCESSING...');
                        var validateResult = true;
                        
                        //2020-02-20 CB V1.2.4.18 - removing any spaces
                        var cardNum = document.getElementById('tfgg_cp_sage_card_number').value;
                        cardNum = cardNum.replace(/\s/g,"");
                        jQuery('#tfgg_cp_sage_card_number').val(cardNum);

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

                        if(!validateResult){
                            jQuery('#tfgg_scp_cart_complete').text('COMPLETE PURCHASE');   
                            jQuery('#tfgg_scp_cart_complete').prop('disabled',false);                         
                            return false
                        };

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
                                            console.log(thisError);
                                            if(thisError.message.indexOf('card number')>0){
                                                var pnl=document.getElementById('tfgg_cart_card_number_alertpnl');  
                                            }else if(thisError.message.indexOf('security code')>0){
                                                var pnl=document.getElementById('tfgg_cart_card_cvc_alertpnl');
                                            }else if(thisError.message.indexOf('expiry')>0){
                                                var pnl=document.getElementById('tfgg_cart_card_expiry_alertpnl');
                                            }else{
                                                //2020-02-20 CB V1.2.4.18
                                                var pnl=document.getElementById('tfgg_cart_card_gen_error');    
                                            }
                                            pnl.innerHTML=pnl.innerHTML+'<br/>'+thisError.message;
                                            pnl.style.display=''; 
                                            jQuery('#tfgg_scp_cart_complete').text('COMPLETE PURCHASE');//2020-02-07 CB
                                            jQuery('#tfgg_scp_cart_complete').prop('disabled',false);
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

    function tfgg_scp_display_no_services_warning(){
        ?>
        
        <br/<br/><br/><br/>
        <div id="" class="alert alert-warning" >
                <p> 
                    There are no items are currently configured for sale
                </p>
            </div>
        <br/><br/>
        <?php   
    }

    function tfgg_scp_display_services_for_sale(){
        ob_start();     

         //2020-02-20 CB V1.2.4.20 - if no services selected, don't show any at all
         $membershipsForSale =tfgg_scp_get_memberships_selected_for_api();
         $packagesForSale = tfgg_scp_get_packages_selected_for_api();

        if(($membershipsForSale=='')&&($packagesForSale=='')){
            tfgg_scp_display_no_services_warning();
            return false;
        }


        if(!tfgg_scp_display_store_service_selection()){
            return false;
        }

        if(isset($_SESSION['tfgg_scp_cart_store'])){$browsingStore = $_SESSION['tfgg_scp_cart_store'];}else{$browsingStore=$_SESSION['clientHomeStore'];}        
        
        if($packagesForSale<>''){
            $packageList = json_decode(tfgg_scp_get_packages_from_api($packagesForSale, $browsingStore, false));
            
            if(StrToUpper($packageList->results) === 'SUCCESS'){
                $packageList = $packageList->packages;
            }else{
                $packageList = '';    
            }
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
                /*2020-03-04 CB V1.2.5.5 - removed at customers request
                <div>
                    <label for="tfgg_package_sort_order"><?php _e("Sort By");?></label>
                    <select id="tfgg_package_sort_order" onchange="tfgg_sortServiceDisplayOrder('tfgg_scp_package_for_sale_list');">
                        <option value="0">Price: High to Low</option>
                        <option value="1">Price: Low to High</option>
                    </select>
                </div>
                */

                //loop through the allowed services and output
                $rowCounter = 1;
                echo '<div id="tfgg_scp_package_for_sale_list" class="row" style="padding: 10px">';
                    
                foreach($packageList as &$packageDetails){

                    //2020-02-09 CB V1.2.4.15
                    if(!tfgg_scp_validate_service_dates($packageDetails->available_from, $packageDetails->available_to)){
                        continue;
                    }
                    
                    
                    switch(StrToUpper($packageDetails->unit_type)){
                        case 'M':$unitType = get_option('tfgg_scp_package_unit_minutes');
                        case 'S':$unitType = get_option('tfgg_scp_package_unit_sessions');
                        case 'C':$unitType = get_option('tfgg_scp_package_unit_credits');
                    }

                    ?>

                    <div class="col-lg-4 services-items-item pack-sale-container" id="tfgg_scp_pack_sale_<?php echo $packageDetails->package_id;?>"
                        data-packagenumber="<?php echo $packageDetails->package_id;?>"
                        data-packagename="<?php echo $packageDetails->description;?>">

                        <div class="services-inner-container">

                            <span class="overlay-items-item-description"><?php echo tfgg_delete_all_between('(',')',$packageDetails->alias);?></span>
                            <span class="overlay-items-item-price"><?php echo tfgg_display_currency_symbol();?><?php echo number_format($packageDetails->price,2,'.',','); ?></span>
                            <br />

                            <div class="services-item-details-container">

                                <?php if ($packageDetails->img != "") {?>
                                <div class="services-image-container">
                                    <img src="<?php echo $packageDetails->img;?>" class="service-image"/>
                                </div>

                                <?php 
                                }
                                ?>

                                <div class="services-details-container">
                                    <span class="overlay-items-item-quantity-label">Units:</span>
                                    <span class="overlay-items-item-quantity-value"><?php echo $packageDetails->num_units.' ('.$packageDetails->unit_type.')'; ?></span>
                                    <br />
                                    <span class="overlay-items-item-quantity-label"> Expiration:</span>
                                    <span class="overlay-items-item-quantity-value"><?php echo tfgg_scp_service_exp_date($packageDetails->exp_days, $packageDetails->exp_date, $packageDetails->open_ended); ?></span>
                                    <br />
                                    <span class="overlay-items-item-quantity-label"><?php echo $packageDetails->freeText; ?></span>
                                    <br />
                                </div>

                                </div>
                    

                            <div class="overlay-items-item-buttongroup overlay-items-item-service-buttongroup ">
                                <select id="tfgg_scp_post_item_qty_P<?php echo $packageDetails->package_id;?>">
                                    <?php
                                    for($j=1; $j<(get_option('tfgg_scp_cart_max_item_count',10)+1); $j++){
                                        if($j==1){$selected='selected';}else{$selected='';}
        
                                        echo '<option value="'.$j.'" '.$selected.'>'.$j.'</option>';
                                    }
                                    ?>
                                </select>
                                <input type="button" onclick="tfggPostCartItem('P','<?php echo $packageDetails->package_id;?>','1','',true, true)" class="btn btn-sm btn-light" value="BUY NOW"/>         
                            </div>

                        </div>
                        
                    </div>


                <?php


                }//foreach
                echo '</div></div>';
                //before anything else, we will add some padding here
                echo '<br/><br/>';        
            }//packageList<>''
        }//if $packagesForSale<>''        

        if($membershipsForSale<>''){

            $membershipList = json_decode(tfgg_scp_get_memberships_from_api($membershipsForSale, $browsingStore, false));
            if(StrToUpper($membershipList->results) === 'SUCCESS'){
                $membershipList = $membershipList->memberships;
            }else{
                $membershipList = '';    
            }
            
            

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
                    /*2020-03-04 CB V1.2.5.5 - removed at customers request
                    <div>
                        <label for="tfgg_package_sort_order"><?php _e("Sort By");?></label>
                        <select id="tfgg_package_sort_order" onchange="tfgg_sortServiceDisplayOrder('tfgg_membership_for_sale_list');">
                            <option value="0">Price: High to Low</option>
                            <option value="1">Price: Low to High</option>
                        </select>
                    </div>
                    */   
                    //loop through the allowed services and output
                $rowCounter = 1;
                echo '<div id="tfgg_membership_for_sale_list" class="row" style="padding: 10px">';


                foreach($membershipList as &$membershipDetails){
                    //2020-02-09 CB V1.2.4.15
                    if(!tfgg_scp_validate_service_dates($membershipDetails->available_from, $membershipDetails->available_to)){
                        continue;
                    }
                    ?>

                    <div class="col-lg-4 services-items-item mems-sale-container" id="tfgg_scp_pack_sale_<?php echo $membershipDetails->membership_id;?>"
                        data-membershipnumber="<?php echo $membershipDetails->membership_id;?>"
                        data-membershipname="<?php echo $membershipDetails->description;?>">

                        <div class="services-inner-container">

                            <span class="overlay-items-item-description"><?php echo tfgg_delete_all_between('(',')',$membershipDetails->alias);?></span>
                            <span class="overlay-items-item-price"><?php echo tfgg_display_currency_symbol();?><?php echo number_format($membershipDetails->price,2,'.',','); ?></span>
                            <br />

                            <div class="services-item-details-container">

                            <?php if ($membershipDetails->img != "") {?>
                                <div class="services-image-container">
                                    <img src="<?php echo $membershipDetails->img;?>" class="service-image"/>
                                </div>
                                <?php 
                                }
                                ?>
                    

                                <div class="services-details-container">
                                    <span class="overlay-items-item-quantity-label"> Expiration:</span>
                                    <span class="overlay-items-item-quantity-value"><?php echo tfgg_scp_service_exp_date($membershipDetails->exp_days, $membershipDetails->exp_date, $membershipDetails->open_ended); ?></span>
                                    <br />
                                    <span class="overlay-items-item-quantity-label"><?php echo $membershipDetails->freeText; ?></span>
                                    <br />
                                    <br />
                                </div>
                            </div>

                            <div class="overlay-items-item-buttongroup overlay-items-item-service-buttongroup">
                                <select id="tfgg_scp_post_item_qty_M<?php echo $packageDetails->membership_id;?>">
                                    <?php
                                    for($j=1; $j<(get_option('tfgg_scp_cart_max_item_count',10)+1); $j++){
                                        if($j==1){$selected='selected';}else{$selected='';}
        
                                        echo '<option value="'.$j.'" '.$selected.'>'.$j.'</option>';
                                    }
                                    ?>
                                </select>
                                <input type="button" onclick="tfggPostCartItem('M','<?php echo $membershipDetails->membership_id;?>','1','',true, true)" class="btn btn-sm btn-light" value="BUY NOW" />                                   
                            </div>

                        </div>
                    </div>

                
                <?php
                
                }//foreach
                echo '</div></div>';
                
            }//membershipList<>''
        }
        
        tfgg_scp_display_cart_successful_add();
        return ob_get_clean();
    }

    function tfgg_scp_display_store_service_selection(){
        $storeList = json_decode(tfgg_api_get_stores());
        if(StrToUpper($storeList->results)==='SUCCESS'){
            $storeList = $storeList->stores;

            //2020-03-05 CB V1.2.5.8 - either viewing their 'shopping' store, or, force selection
            if(isset($_SESSION['tfgg_scp_cart_store'])){
                $selected = $_SESSION['tfgg_scp_cart_store'];
            }else{                
                if((isset($_SESSION['clientHomeStore']))&&($_SESSION['clientHomeStore']<>'')){
                    //echo 'here';
                    $selected=$_SESSION['clientHomeStore'];
                }else{
                    //2020-03-05 CB V1.2.5.8 - changed to default to a 'Please select...' option
                    $selected='xxxxxxxxxx';
                    //$_SESSION['tfgg_scp_cart_store']=$storeList[0]->store_id;;
                    //$selected=$_SESSION['tfgg_scp_cart_store'];
                }
            }
            
            if((isset($_SESSION['tfgg_cp_cart_warning']))&&($_SESSION['tfgg_cp_cart_warning']=='1')){
                $onclick = 'changeCartStore();';                
            }else{
                $onclick = 'confirmChangeCartStore();';
            }
            ?>
            <div class="registration-container">
                <div class="account-overview-input-double">
                    <label for="tfgg_select_store" class="account-overview-label"><?php _e('You are viewing packages and services offered by'); ?></label>
                    <select name="tfgg_scp_store_purchasing_selection" id="tfgg_scp_store_purchasing_selection" onchange="<?php echo $onclick;?>"
                    class="js-example-basic-single account-overview-input">
                    <option value="xxxxxxxxxx" <?php if('xxxxxxxxxx'===$selected){echo 'selected';}?> >Please select a store....</option>
                    <?php
                        //echo 'selected: "'.$selected.'" ;';
                        foreach($storeList as &$details){
                            $output='<option value="'.$details->store_id.'" '.($details->store_id === $selected ? "selected" : "").'>'.$details->store_loc.'</option>';
                            echo $output; 
                        }
                    ?>
                    </select>                    
                </div>
                <?php
                /*2020-03-02 CB V1.2.5.4 - removing button
                <div class="account-overview-input-single" style="padding-top:23px">
                    <button type="button" class="account-overview-button account-overview-standard-button" onclick="<?php echo $onclick;?>">Change Store Selection</button>
                </div>
                */
                ?>
            </div><?php //<div class="registration-container"><?>
            <div <?php if(tfgg_ae_detect_ie()){?>style="z-index: 10000 !important; margin-top:25%"<?php } ?> class="modal fade" id="tfgg_scp_store_purchasing_selection_confirm" tabindex="-1" role="dialog" aria-labelledby="tfgg_scp_store_purchasing_selection_confirm" aria-hidden="true">
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
            </div><?php //tfgg_ae_detect_ie ?>
            <script>
            jQuery('.js-example-basic-single').select2();
            </script>
            <?php
            display_store_cart_details($selected);
            if($selected==='xxxxxxxxxx'){
                return false;
            }else{
                return true;
            }
        }
    }

    function tfgg_ae_detect_ie(){
        $ua = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
        if (preg_match('~MSIE|Internet Explorer~i', $ua) || (strpos($ua, 'Trident/7.0') !== false && strpos($ua, 'rv:11.0') !== false)) {
            return true;
        }else{
            return false;
        }
    }

    function display_store_cart_details($store){
        //get_post('post_content',url_to_postid(site_url(get_option('tfgg_scp_tandc_slug_instore'))))
        echo get_post_field('post_content',url_to_postid(site_url(get_option('tfgg_scp_store_cart_details_page'))),'display');
        
        if($store!='xxxxxxxxxx'){
            $storeCartDetailsID = (array)get_option('tfgg_scp_store_cart_details_id');
            if((array_key_exists($store, $storeCartDetailsID))&&
                ($storeCartDetailsID[$store]<>'')){ $storeDetailsID = $storeCartDetailsID[$store]; }else{ $storeDetailsID = ''; } 
        }else{
            $storeDetailsID='xxxxxxxxxx';
        }

        if($storeDetailsID<>''){
        ?>
        <br/><br/>
        <script>
            jQuery('#<?php echo $storeDetailsID;?>').show();
        </script>
        <?php
        }//if
    }

?>