<?php

    function tfgg_scp_admin_appointments_options(){
        add_settings_section("tfgg_appointments_section", '', null, "tfgg-appointments-options");

        add_settings_field("tfgg_scp_appointments_allow", "Enable Appointments:", "display_appointments_allow", "tfgg-appointments-options", "tfgg_appointments_section");
        register_setting("tfgg_appointments_section", "tfgg_scp_appointments_allow");
        
        add_settings_field("tfgg_scp_appointments_allowed_hrs", "Appts Must Be Booked Hrs In Advance:", "display_appointments_allowed_hrs", "tfgg-appointments-options", "tfgg_appointments_section");
        register_setting("tfgg_appointments_section", "tfgg_scp_appointments_allowed_hrs");
        
        add_settings_field("tfgg_scp_appointments_allow_cancel", "Allow Cancellations:", "display_appointments_allow_cancel", "tfgg-appointments-options", "tfgg_appointments_section");
        register_setting("tfgg_appointments_section", "tfgg_scp_appointments_allow_cancel");
        
        add_settings_field("tfgg_scp_appointments_cancel_allowed_hrs", "Appts Must Be Cancelled Hrs In Advance:", "display_appointments_cancel_allowed_hrs", "tfgg-appointments-options", "tfgg_appointments_section");
        register_setting("tfgg_appointments_section", "tfgg_scp_appointments_cancel_allowed_hrs");
        
        add_settings_field("tfgg_scp_appt_update_employee", "Log Updates As:", "display_appt_update_employee", "tfgg-appointments-options", "tfgg_appointments_section");
        register_setting("tfgg_appointments_section", "tfgg_scp_appt_update_employee");
        
        add_settings_field("tfgg_scp_appt_equip_dir", "Equipment Images Dir:", "display_appt_images_dir", "tfgg-appointments-options", "tfgg_appointments_section");
        register_setting("tfgg_appointments_section", "tfgg_scp_appt_equip_dir");
    }
    
    function tfgg_scp_admin_appointments(){
        tfgg_scp_admin_menu_header();
    ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-8">
                    <div class="card">
                        <h5 class="card-header">Appointments</h5>
                        <div class="card-body">
                            <form method="POST" action="options.php">
                            <?php
                            settings_fields('tfgg_appointments_section');
                            do_settings_sections('tfgg-appointments-options');
                            ?>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <div class="form-group col-12">
                                        <button type="submit" class="btn btn-primary"><?php echo __('Save Settings');?></button>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
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