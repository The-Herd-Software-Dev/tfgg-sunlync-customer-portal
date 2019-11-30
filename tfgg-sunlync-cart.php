<?php

    function tfgg_scp_display_cart(){
        ob_start();

        $cartContents = json_decode(tfgg_scp_get_cart_contents());
        //var_dump($cartContents);
        if(StrToUpper($cartContents->results) === 'SUCCESS'){
            tfgg_scp_display_cart_banner();

            echo '<br/><br/><br/> <br/>';

            echo'<div class="row" id="tfgg_scp_cart_contents" >';
                tfgg_scp_cart_items_display($cartContents->lineItems);
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
            tfgg_scp_empty_cart_display();
            ?>
            <script>
                tfggSetCartLinkQty(0);
            </script>
            <?php
        }
        
        return ob_get_clean();
    }

    function tfgg_scp_cart_items_display($lineItems){
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
                        <span class="overlay-items-item-description"><?php echo $details->Description; ?></span>
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

        </div>


        <?php
    }

    function tfgg_scp_cart_header_display($header, $paymentItems){
        ?>


        <div id="cart-items-right" class="col-lg-4">

            <div class="cart-items-header"><h4>ORDER SUMMARY</h4></div>

                <div id="cart-totals-content">

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
                    <div id="cart_payment_container">&nbsp;
                    </div>
                    <div class="overlay-button-container">
                        <div id="paypal-button-container">
                        </div>
                        <button type="button" class="account-overview-button account-overview-standard-button overlay-checkout-button" id="tfgg_scp_cart_complete" <?php if($header->allowToFinalize==0){echo "disabled";} ?>>COMPLETE</button>
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
                    <button type="button" class="account-overview-button cart-standard-button-paynow account-overview-appt-cancel-button" onclick="tfgg_scp_changePage('<?php echo $viewCartURL; ?>');">Pay Now</button>
                    <button type="button" class="account-overview-button account-overview-standard-button account-overview-appt-cancel-button" data-dismiss="modal">CONTINUE SHOPPING</button>
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
                }
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
        tfgg_scp_display_cart_banner();
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

        //$packageList = json_decode(tfgg_scp_get_packages_from_api(tfgg_scp_get_packages_selected_for_api()));
        $packageList = json_decode(tfgg_scp_get_packages_from_api(tfgg_scp_get_packages_selected_for_api()));
        if(StrToUpper($packageList->results) === 'SUCCESS'){
            $packageList = $packageList->packages;
        }

        $membershipList = json_decode(tfgg_scp_get_memberships_from_api(tfgg_scp_get_memberships_selected_for_api()));
        if(StrToUpper($membershipList->results) === 'SUCCESS'){
            $membershipList = $membershipList->memberships;
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

                    <span class="overlay-items-item-description"><?php echo $packageDetails->description;?></span>
                    <span class="overlay-items-item-price">&#163;<?php echo $packageDetails->price; ?></span>
                    <br />
                    <span class="overlay-items-item-quantity-label">Units:</span>
                    <span class="overlay-items-item-quantity-value"><?php echo $packageDetails->num_units.' ('.$packageDetails->unit_type.')'; ?></span>
                    <br />
                    <span class="overlay-items-item-quantity-label"> Expiration:</span>

                    <span class="overlay-items-item-quantity-value"><?php echo tfgg_scp_service_exp_date($packageDetails->exp_days, $packageDetails->exp_date, $packageDetails->open_ended); ?></span>

                    <br />
                    <div class="overlay-items-item-buttongroup">
                        <a href="javascript:tfggPostCartItem('P','<?php echo $packageDetails->package_id;?>','1')" class=" cart-paynow-font-color overlay-items-item-link">ADD TO CART</a>         
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

                    <span class="overlay-items-item-description"><?php echo $membershipDetails->description;?></span>
                    <span class="overlay-items-item-price">&#163;<?php echo $membershipDetails->price; ?></span>
                    <br />

                    <span class="overlay-items-item-quantity-label"> Expiration:</span>

                    <span class="overlay-items-item-quantity-value"><?php echo tfgg_scp_service_exp_date($membershipDetails->exp_days, $membershipDetails->exp_date, $membershipDetails->open_ended); ?></span>

                    <br />
                    <div class="overlay-items-item-buttongroup">
                        <?php
                        /*<a href="javascript:tfggPostCartItem('M','<?php echo $membershipDetails->membership_id;?>','1')" class="overlay-items-item-link">ADD TO CART</a>*/
                        ?>         
                    </div>
                </div>


            <?php
               
            }//foreach
            echo '</div></div>';
            
        }//membershipList<>''
        
        tfgg_scp_display_cart_successful_add();
        return ob_get_clean();
    }

?>