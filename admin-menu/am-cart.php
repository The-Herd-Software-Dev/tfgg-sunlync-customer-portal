<?php

function tfgg_scp_cart_description(){
    echo '<p>Cart Settings</p>';
}

function display_tfgg_cart_slug(){
    ?>
    <input type="text" name="tfgg_scp_cart_slug" value="<?php echo get_option('tfgg_scp_cart_slug'); ?>" style="width: 60%" />
    <?php
}

function display_tfgg_menu_link_label(){
    ?>
    <input type="text" name="tfgg_scp_cart_menu_link_text" value="<?php echo get_option('tfgg_scp_cart_menu_link_text','Pay Now'); ?>" style="width: 60%" />
    <?php
}

function display_tfgg_allow_paypal_payment(){
    ?>
    <input type="checkbox" name="tfgg_scp_cart_allow_paypal_payment" value="1" <?php if(get_option('tfgg_scp_cart_allow_paypal_payment')==1){echo 'checked';} ?> />
    <?php
}

function display_tfgg_paypal_clientid(){
    ?>
    <input type="text" name="tfgg_scp_cart_paypal_clientid" value="<?php echo get_option('tfgg_scp_cart_paypal_clientid'); ?>" style="width: 60%" />
    <?php
}

function display_tfgg_cart_employee(){
    $updateEmp = get_option('tfgg_scp_cart_employee');
    $employeeList = json_decode(tfgg_api_get_employees());
    $employeeList = $employeeList->employees;
    ?>
    <select name="tfgg_scp_cart_employee" style="width: 60%">
        <option value="">Please Select...</option>
    <?php
        foreach($employeeList as &$details){
            $output='<option value="'.$details->emp_no.'" '.($details->emp_no === $updateEmp ? "selected" : "").'>'.$details->lname.', '.$details->fname.'</option>';
            echo $output; 
        }
    ?>
    </select>
<?php
}

function display_tfgg_paypal_payment(){
    $paypal_payment = get_option('tfgg_scp_cart_paypal_payment');
    $paymentList = json_decode(tfgg_api_get_payment_types());
    $paymentList = $paymentList->payment_types;
    ?>
    <select name="tfgg_scp_cart_paypal_payment" style="width: 60%">
        <option value="">Please Select...</option>
    <?php
        foreach($paymentList as &$details){
            if($details->is_deleted==0){
                $output='<option value="'.$details->payment_number.'" '.($details->payment_number === $paypal_payment ? "selected" : "").'>'.$details->description.'</option>';
                echo $output; 
            }
        }
    ?>
    </select>
    <?php
}

function display_tfgg_allow_sage_payment(){
    ?>
    <input type="checkbox" name="tfgg_scp_cart_allow_sage_payment" value="1" <?php if(get_option('tfgg_scp_cart_allow_sage_payment')==1){echo 'checked';} ?> />
    <?php
}

function display_tfgg_sage_key(){
    ?>
    <input type="text" name="tfgg_scp_cart_sage_key" value="<?php echo get_option('tfgg_scp_cart_sage_key'); ?>" style="width: 60%" />
    <?php
}

function display_tfgg_sage_pass(){
    ?>
    <input type="text" name="tfgg_scp_cart_sage_pass" value="<?php echo get_option('tfgg_scp_cart_sage_pass'); ?>" style="width: 60%" />
    <?php
}

function display_tfgg_sage_vendor_name(){
    ?>
    <input type="text" name="tfgg_scp_cart_sage_vendor_name" value="<?php echo get_option('tfgg_scp_cart_sage_vendor_name'); ?>" style="width: 60%" />
    <?php
}

function display_tfgg_sage_payment(){
    $sage_payment = get_option('tfgg_scp_cart_sage_payment');
    $paymentList = json_decode(tfgg_api_get_payment_types());
    $paymentList = $paymentList->payment_types;
    ?>
    <select name="tfgg_scp_cart_sage_payment" style="width: 60%">
        <option value="">Please Select...</option>
    <?php
        foreach($paymentList as &$details){
            if($details->is_deleted==0){
                $output='<option value="'.$details->payment_number.'" '.($details->payment_number === $sage_payment ? "selected" : "").'>'.$details->description.'</option>';
                echo $output; 
            }
        }
    ?>
    </select>
    <?php
}

function display_tfgg_successful_cart_message(){
    $settings = array(
        'textarea_rows' => 15,
        'tabindex' => 1,
        'media_buttons' => false,
        'wpautop' => false
    );
    wp_editor( get_option('tfgg_scp_cart_success_message'), 'tfgg_scp_cart_success_message', $settings); 
    if(wp_is_mobile()){
    ?>
    <div>
    <?php }else{ ?>
    <div style="font-size: small">
    <?php } ?>
        <p>Placeholders: <ul>
        <li>!@#receiptnumber#@! -> Receipt Number</li>
        </ul></p>
    </div>
    <?php
}

?>