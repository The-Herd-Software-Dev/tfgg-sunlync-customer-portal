<?php

function tfgg_scp_cart_description(){
    echo '<p>Cart Settings</p>';
}

function display_tfgg_cart_slug(){
    ?>
    <input type="text" name="tfgg_scp_cart_slug" value="<?php echo get_option('tfgg_scp_cart_slug'); ?>" style="width: 60%" />
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

?>