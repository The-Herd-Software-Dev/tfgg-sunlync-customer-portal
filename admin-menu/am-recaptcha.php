<?php

    function display_tfgg_recaptcha_site_key(){
        ?>
        <input type="text" name="tfgg_scp_recaptcha_site_key" value="<?php echo get_option('tfgg_scp_recaptcha_site_key'); ?>" style="width: 60%" />
        <div id="tfgg_scp_recaptcha_site_key_error" style="color:red"></div>
        <?php
    }

    function display_tfgg_recaptcha_secret_key(){
        ?>
        <input type="text" name="tfgg_scp_recaptcha_secret_key" value="<?php echo get_option('tfgg_scp_recaptcha_secret_key'); ?>" style="width: 60%" />
        <div id="tfgg_scp_recaptcha_secret_key_error" style="color:red">
        <?php   
    }

    function display_tfgg_recaptcha_score(){
        ?>
        <input type="range" name="tfgg_scp_recaptcha_score" value="<?php echo get_option('tfgg_scp_recaptcha_score','0'); ?>" 
        id="tfgg-scp-recaptcha-score"
        min="0" max="1" style="width: 60%" step="0.1"/><br/>
        <span>Score: <span id="tfgg-scp-recaptcha-current-score"><?php echo get_option('tfgg_scp_recaptcha_score','0'); ?></span></span>
        <script>
        var slider = document.getElementById('tfgg-scp-recaptcha-score');
        slider.oninput = function(){
            document.getElementById('tfgg-scp-recaptcha-current-score').innerHTML = this.value; 
        }
        </script>
        <?php  
    }

    function display_tfgg_recaptcha_test(){
        ?>
        <br/><br/>
        <form id="tfgg-scp-recaptcha-test">
        <button type="button" class="button button-primary" id="tfgg_scp_recaptcha_get_token_button" onclick="GetReCaptchaTestToken()">Test reCaptcha</button>
        <button type="button" class="button button-primary" id="tfgg_scp_recaptcha_test_token_button" onclick="VerifyTestToken()" style="display:none">Verify Token</button>
            <img src="<?php echo plugin_dir_url( __DIR__ ); ?>/images/loading.gif" class="loading-image" style="width: 40px; display:none"
            id="tfgg_scp_recaptcha_busy"/>
            <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
            <div id="tfgg_scp_recaptcha_test_results" class="alert" style="display:none; width:30">
            </div>
        </form>
        <br/><br/>
        <?php
    }

?>