<?php
    
    function list_out_shortcodes(){
        ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">Login Form:</th>
                    <td>[cp_sunlync_loginform]</td>
                </tr>
                <tr>
                    <th scope="row">Registration Form (new customers):</th>
                    <td>[cp_sunlync_registrationform_new]</td>
                </tr>
                <tr>
                    <th scope="row">Demographics:</th>
                    <td>[cp_sunlync_demographics]</td>
                </tr>
                <tr>
                    <th scope="row">Appointments:</th>
                    <td>[cp_sunlync_appts]</td>
                </tr>
            </tbody>
        </table>
        <?php
    }
    
    function display_cplogin_page(){
        ?>
        <input type="text" name="tfgg_scp_cplogin_page" value="<?php echo get_option('tfgg_scp_cplogin_page'); ?>" style="width: 60%" />
        <?php
    }
    function display_cplogin_page_success(){
        ?>
        <input type="text" name="tfgg_scp_cplogin_page_success" value="<?php echo get_option('tfgg_scp_cplogin_page_success'); ?>" style="width: 60%" />
        <?php
    }
    
    function display_cpnewuser_page(){
        ?>
        <input type="text" name="tfgg_scp_cpnewuser_page" value="<?php echo get_option('tfgg_scp_cpnewuser_page'); ?>" style="width: 60%" />
        <?php
    }
    
    function display_cpexistinguser_page(){
        ?>
        <input type="text" name="tfgg_scp_cpexistinguser_page" value="<?php echo get_option('tfgg_scp_cpexistinguser_page'); ?>" style="width: 60%" />
        <?php
    }
    
    function display_acct_overview(){
        ?>
        <input type="text" name="tfgg_scp_acct_overview" value="<?php echo get_option('tfgg_scp_acct_overview'); ?>" style="width: 60%" />
        <?php   
    }
    
    function display_cpappt_page(){
        ?>
        <input type="text" name="tfgg_scp_cpappt_page" value="<?php echo get_option('tfgg_scp_cpappt_page'); ?>" style="width: 60%" />
        <?php   
    }
    
?>
