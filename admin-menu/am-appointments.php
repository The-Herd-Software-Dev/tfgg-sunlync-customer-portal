<?php

    function tfgg_scp_appointments_description(){
        echo '<p>Appointments Settings</p>';
    }
    
    function display_appointments_allow(){
        ?>
        <input type="checkbox" name="tfgg_scp_appointments_allow" value="1" <?php if(get_option('tfgg_scp_appointments_allow')==1){echo 'checked';} ?>/>
        <?php
    }
    
    function display_appt_update_employee(){
        $updateEmp = get_option('tfgg_scp_appt_update_employee');
        $employeeList = json_decode(tfgg_api_get_employees());
        $employeeList = $employeeList->employees;
        ?>
        <select name="tfgg_scp_appt_update_employee" style="width: 60%">
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
    
    function display_appointments_allow_cancel(){
        ?>
        <input type="checkbox" name="tfgg_scp_appointments_allow_cancel" value="1" <?php if(get_option('tfgg_scp_appointments_allow_cancel')==1){echo 'checked';} ?>/>
        <?php
    }
    
    function display_appointments_menu_label(){
        ?>
        <input type="text" name="tfgg_scp_appointments_menu_label" value="<?php echo get_option('tfgg_scp_appointments_menu_label'); ?>" size="70" />
        <?php
    }
    
    function display_appointments_cancel_allowed_hrs(){
        ?>
        <select name="tfgg_scp_appointments_cancel_allowed_hrs" style="width: 15%">
            <option value="0">Please Select...</option>
            <?php
                for($i=1; $i<25; $i++){
                    $option="<option value='".$i."'";
                    if(get_option('tfgg_scp_appointments_cancel_allowed_hrs')==$i){$option.=" selected";}
                    $option.=">".$i."</option>";
                    echo $option;
                }
            ?>
        </select>
        <?php
    }
    
    function display_appointments_allowed_hrs(){
        ?>
        <select name="tfgg_scp_appointments_allowed_hrs" style="width: 15%">
            <option value="0">Please Select...</option>
            <?php
                for($i=1; $i<25; $i++){
                    $option="<option value='".$i."'";
                    if(get_option('tfgg_scp_appointments_allowed_hrs')==$i){$option.=" selected";}
                    $option.=">".$i."</option>";
                    echo $option;
                }
            ?>
        </select>
        <?php
    }
    
    function display_appt_images_dir(){
    ?>
    
    <input type="text" name="tfgg_scp_appt_equip_dir" value="<?php echo get_option('tfgg_scp_appt_equip_dir'); ?>" size="70" />    
    
    <?php
    }
    
?>