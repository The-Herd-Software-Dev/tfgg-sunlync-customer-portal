<?php

    function tfgg_scp_display_cart(){
        ob_start();

        $cartContents = json_decode(tfgg_scp_get_cart_contents());
        
        if(StrToUpper($cartContents->results) === 'SUCCESS'){
            tfgg_scp_display_cart_banner();
            echo '<br/>';
            tfgg_scp_cart_continue_shopping();
            echo '<br/>';
            tfgg_scp_cart_items_display($cartContents->lineItems);
            echo '<br/>'.tfgg_scp_cart_continue_shopping().'<br/>';
            echo '<br/><hr/><br/>';
            tfgg_scp_cart_header_display($cartContents->header);
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
        <div style="float:left; padding:10px" class="container">
            <table class="account-overview-table">
            <?php
            $i=0;
            foreach($lineItems as &$details){
                $i++;
            ?>
                <tr class="account_overview_row" id="tfgg_cart_item_row_<?php echo $i;?>">
                <td><span class="account-overview-generic-value"><strong><?php echo $details->Description; ?></strong></span></td>
                <td><span class="account-overview-generic-value">Qty: <?php echo $details->Qty; ?> @ &#163;<?php echo $details->PPU; ?></span></td>
                <td><span class="account-overview-generic-value"><strong>Total: &#163;<?php echo ($details->Qty*$details->PPU); ?><strong></span><br/><br/>
                <button class="account-overview-button account-overview-standard-button account-overview-appt-cancel-button" 
                style="float:right;" onclick="tfggRemoveCartItem('<?php echo $details->ID;?>','tfgg_cart_item_row_<?php echo $i;?>')">REMOVE</button></td>
                </tr>
            <?php
            }//foreach
            ?>
            </table>
        </div>
        <br/><br/><br/>
        <?php
    }

    function tfgg_scp_cart_header_display($header){
        ?>
        <div style="float:right; padding:10px" class="container">
        <table class="account-overview-table">
            <tr class="account_overview_row account_overview_row_header">
                <td colspan="2"><span class="account-overview-generic-label">Order Summary</span></td>
            </tr>
            <tr class="account_overview_row">
                <td><span class="account-overview-generic-label">Items: </span></td>
                <td><span class="account-overview-generic-value"><?php echo $header->qty; ?></span></td>
            </tr>
            <tr class="account_overview_row">
                <td><span class="account-overview-generic-label">Sub-Total: </span></td>
                <td><span class="account-overview-generic-value">&#163;<?php echo $header->subtotal; ?></span></td>
            </tr>
            <tr class="account_overview_row">
                <td><span class="account-overview-generic-label">Tax Total: </span></td>
                <td><span class="account-overview-generic-value">&#163;<?php echo $header->taxtotal; ?></span></td>
            </tr>
            <tr class="account_overview_row">
                <td><span class="account-overview-generic-label">Total: </span></td>
                <td><span class="account-overview-generic-value">&#163;<?php echo $header->total; ?></span></td>
            </tr>
        </table>    
        </div>
        <?php
    }

    function tfgg_scp_display_cart_banner(){
        ?>
        <div style="background-color:rgba(203, 193, 193, 0.34); padding: 10px;">
        <h2>SHOPPING CART</h2>
        </div>
        <?php  
    }

    function tfgg_scp_display_cart_successful_add(){
        ?>
        <div class="modal fade" id="tfgg_scp_cart_add" tabindex="-1" role="dialog" aria-labelledby="tfgg_scp_cart_add" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-body" id="tfgg_scp_cart_add_message">
                </div>
                <div class="modal-footer">
                    <button type="button" class="account-overview-button account-overview-standard-button account-overview-appt-cancel-button" data-dismiss="modal">CONTINUE SHOPPING</button>
                </div>
                </div>
            </div>
            </div>
        <?php
    }

    function tfgg_scp_cart_continue_shopping(){
    ?>
        <div style="float:right; margin:10px;">
        <form action="<?php echo site_url();?>/cart">
            <button type="submit" class="account-overview-button account-overview-standard-button account-overview-appt-cancel-button">CONTINUE SHOPPING</button>
        </form>
        </div>
    <?php
    }

    function tfgg_scp_empty_cart_display(){
        tfgg_scp_display_cart_banner();
        ?>
        
        <br/>
        <div id="tfgg_package_search_warning" class="alert alert-warning" >
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
        
                ?>

                <div class="col-lg-3 services-items-item" id="tfgg_scp_pack_sale_<?php echo $packageDetails->package_id;?>"
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
                        <a href="javascript:tfggPostCartItem('P','<?php echo $packageDetails->package_id;?>','1')" class="overlay-items-item-link">ADD TO CART</a>         
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

                <div class="col-lg-3 services-items-membership" id="tfgg_scp_pack_sale_<?php echo $membershipDetails->membership_id;?>"
                    data-packagenumber="<?php echo $membershipDetails->membership_id;?>"
                    data-packagename="<?php echo $membershipDetails->description;?>">

                    <span class="overlay-items-item-description"><?php echo $membershipDetails->description;?></span>
                    <span class="overlay-items-item-price">&#163;<?php echo $membershipDetails->price; ?></span>
                    <br />

                    <span class="overlay-items-item-quantity-label"> Expiration:</span>

                    <span class="overlay-items-item-quantity-value"><?php echo tfgg_scp_service_exp_date($membershipDetails->exp_days, $membershipDetails->exp_date, $membershipDetails->open_ended); ?></span>

                    <br />
                    <div class="overlay-items-item-buttongroup">
                        <a href="javascript:tfggPostCartItem('M','<?php echo $membershipDetails->membership_id;?>','1')" class="overlay-items-item-link">ADD TO CART</a>         
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