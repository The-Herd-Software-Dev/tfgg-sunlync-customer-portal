<?php

    function tfgg_scp_api_description(){
        echo '<p>API Settings</p>';
    }
    
    function display_tfgg_api_protocol(){
        ?>
        <input type="text" name="tfgg_scp_api_protocol" value="<?php echo get_option('tfgg_scp_api_protocol'); ?>" style="width: 60%" />
        <?php
    }
    
    function display_tfgg_api_url(){
        ?>
        <input type="text" name="tfgg_scp_api_url" value="<?php echo get_option('tfgg_scp_api_url'); ?>" style="width: 60%" />
        <?php
    }
    
    function display_tfgg_api_port(){
        ?>
        <input type="number" name="tfgg_scp_api_port" value="<?php echo get_option('tfgg_scp_api_port'); ?>" style="width: 30%" />
        <?php
    }
    
    function display_tfgg_api_market(){
        ?>
        <input type="text" name="tfgg_scp_api_mrkt" value="<?php echo get_option('tfgg_scp_api_mrkt'); ?>" style="width: 60%" />
        <?php
    }
    
    function display_tfgg_api_user(){
        ?>
        <input type="text" name="tfgg_scp_api_user" value="<?php echo get_option('tfgg_scp_api_user'); ?>" style="width: 60%" />
        <?php
    }
    
    function display_tfgg_api_pass(){
        ?>
        <input type="password" name="tfgg_scp_api_pass" value="<?php echo get_option('tfgg_scp_api_pass'); ?>" style="width: 60%" />
        <?php
    }

?>