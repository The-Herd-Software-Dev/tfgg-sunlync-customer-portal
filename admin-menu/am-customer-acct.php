<?php
    function display_demogrphics_allow(){
        ?>
        <input type="checkbox" name="tfgg_scp_demogrphics_allow" value="1" <?php if(get_option('tfgg_scp_demogrphics_allow')==1){echo 'checked';} ?>/>
        <?php
    }

    function tfgg_scp_demogrphics_description(){
        echo '<p>Demographics Settings</p>';
    }
    
    function display_comm_pref_allow(){
        ?>
        <input type="checkbox" name="tfgg_scp_comm_pref_allow" value="1" <?php if(get_option('tfgg_scp_comm_pref_allow')==1){echo 'checked';} ?> />
        <?php
    }
    
    function display_update_employee(){
        $updateEmp = get_option('tfgg_scp_update_employee');
        $employeeList = json_decode(tfgg_api_get_employees());
        $employeeList = $employeeList->employees;
        ?>
        <select name="tfgg_scp_update_employee" style="width: 60%">
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
?>