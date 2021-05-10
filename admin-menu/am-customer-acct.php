<?php
    function tfgg_scp_admin_cust_acct_options(){
        add_settings_section("tfgg_customer_acct_section", '', null, "tfgg-customer-acct-options");

        add_settings_field("tfgg_scp_update_employee", "Log Updates As:", "display_update_employee", "tfgg-customer-acct-options", "tfgg_customer_acct_section");
        register_setting("tfgg_customer_acct_section", "tfgg_scp_update_employee");

        add_settings_field("tfgg_scp_demogrphics_allow", "Enable Demographics Updates:", "display_demogrphics_allow", "tfgg-customer-acct-options", "tfgg_customer_acct_section");
        register_setting("tfgg_customer_acct_section", "tfgg_scp_demogrphics_allow");
        
        add_settings_field("tfgg_scp_comm_pref_allow", "Enable Marketing Updates:", "display_comm_pref_allow", "tfgg-customer-acct-options", "tfgg_customer_acct_section");
        register_setting("tfgg_customer_acct_section", "tfgg_scp_comm_pref_allow");
    }

    function tfgg_scp_admin_customer_accts(){
        tfgg_scp_admin_menu_header();
        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-8">
                    <div class="card">
                        <h5 class="card-header">Customer Accounts</h5>
                        <div class="card-body">
                            <form method="POST" action="options.php">
                            <?php
                            settings_fields('tfgg_customer_acct_section');
                            do_settings_sections('tfgg-customer-acct-options');
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