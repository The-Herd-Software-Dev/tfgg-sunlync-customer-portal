<?php

function tfgg_scp_cart_description(){
    echo '<p>Cart Settings</p>';
}

function display_tfgg_enable_cart(){
    ?>
    <input type="checkbox" name="tfgg_scp_enable_cart" value="1" <?php if(get_option('tfgg_scp_enable_cart',0)==1){echo 'checked';} ?> />
    <?php
}

function display_tfgg_cart_slug(){
    ?>
    <input type="text" name="tfgg_scp_cart_slug" value="<?php echo get_option('tfgg_scp_cart_slug'); ?>" style="width: 60%" />
    <?php
}

function display_tfgg_cart_success_slug(){
    ?>
    <input type="text" name="tfgg_scp_cart_success_slug" value="<?php echo get_option('tfgg_scp_cart_success_slug'); ?>" style="width: 60%" />
    <?php
}

function display_tfgg_cart_currency_symbol(){
    $selected = get_option('tfgg_scp_cart_currency_symbol','1');
    ?>
    <input type="radio" id="currency_sterling" name="tfgg_scp_cart_currency_symbol" value="1" <?php echo(($selected=='1')? 'checked':''); ?>/>
    <label for="currency_sterling">&#163;</label><br>
    <input type="radio" id="currency_euro" name="tfgg_scp_cart_currency_symbol" value="2" <?php echo(($selected=='2')?'checked':''); ?>/>
    <label for="currency_euro">&euro;</label><br>
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

function display_tfgg_paypal_tand_label(){
    ?>
    <input type="text" name="tfgg_scp_cart_paypal_tandc_label" value='<?php echo get_option('tfgg_scp_cart_paypal_tandc_label'); ?>' style="width: 60%" />
    <?php    
}

function display_tfgg_save_cart_demographics_label(){
    ?>
    <input type="text" name="tfgg_save_cart_demographics_label" value='<?php echo get_option('tfgg_save_cart_demographics_label'); ?>' style="width: 60%" />
    <?php    
}

function display_tfgg_save_cart_demographics(){
    ?>
    <input type="checkbox" name="tfgg_scp_cart_save_demographics" value="1" <?php if(get_option('tfgg_scp_cart_save_demographics')==1){echo 'checked';} ?> />
    <?php    
}

function display_tfgg_scp_cart_save_commpref_label(){
    ?>
    <input type="text" name="tfgg_scp_cart_save_commpref_label" value='<?php echo get_option('tfgg_scp_cart_save_commpref_label'); ?>' style="width: 60%" />
    <?php    
}

function display_tfgg_save_cart_commpref(){
    ?>
    <input type="checkbox" name="tfgg_scp_cart_save_commpref" value="1" <?php if(get_option('tfgg_scp_cart_save_commpref')==1){echo 'checked';} ?> />
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

function display_tfgg_sage_pay_sandbox(){
    ?>
    <input type="checkbox" name="tfgg_scp_cart_sage_pay_sandbox" value="1" <?php if(get_option('tfgg_scp_cart_sage_pay_sandbox','1')==1){echo 'checked';} ?> />
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