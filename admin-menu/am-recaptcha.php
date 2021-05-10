<?php


    function tfgg_scp_admin_recaptcha_options(){
        add_settings_section("tfgg_scp_recaptcha_options", '', null, "tfgg-scp-recaptcha-options");

        add_settings_field("tfgg_scp_recaptcha_site_key","reCaptcah Site Key:","display_tfgg_recaptcha_site_key", "tfgg-scp-recaptcha-options", "tfgg_scp_recaptcha_options");
        register_setting("tfgg_scp_recaptcha_options","tfgg_scp_recaptcha_site_key");
        
        add_settings_field("tfgg_scp_recaptcha_secret_key","reCaptcah Secret Key:","display_tfgg_recaptcha_secret_key", "tfgg-scp-recaptcha-options", "tfgg_scp_recaptcha_options");
        register_setting("tfgg_scp_recaptcha_options","tfgg_scp_recaptcha_secret_key");

        add_settings_field("tfgg_scp_recaptcha_score","reCaptcah Score:","display_tfgg_recaptcha_score", "tfgg-scp-recaptcha-options", "tfgg_scp_recaptcha_options");
        register_setting("tfgg_scp_recaptcha_options","tfgg_scp_recaptcha_score");
    }

    function tfgg_scp_admin_recaptcha(){
        tfgg_scp_admin_menu_header();
        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-8">
                    <div class="card">
                        <h5 class="card-header">Google reCaptcha</h5>
                        <div class="card-body">
                            <p class="card-text">Please enter the Keys for the Google reCaptcha that are registered for this site</p>
                            <form method="POST" action="options.php">
                            <?php
                            settings_fields('tfgg_scp_recaptcha_options');
                            do_settings_sections('tfgg-scp-recaptcha-options');
                            ?>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <div class="form-group col-12">
                                        <button type="submit" class="btn btn-primary"><?php echo __('Save Settings');?></button>
                                    </div>
                                </div>
                            </div>
                            </form>
                            <?php
                            if((get_option('tfgg_scp_recaptcha_site_key','')!='')&&(get_option('tfgg_scp_recaptcha_secret_key','')!='')){
                                display_tfgg_recaptcha_test();
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

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
        <div class="form-row">
            <div class="form-group col-md-6">
                <div class="form-group col-12">
                <button type="button" class="btn btn-primary" id="tfgg_scp_recaptcha_get_token_button" onclick="GetReCaptchaTestToken()">Test reCaptcha</button>
                <button type="button" class="btn btn-primary" id="tfgg_scp_recaptcha_test_token_button" onclick="VerifyTestToken()" style="display:none">Verify Token</button>
                    <img src="<?php echo plugin_dir_url( __DIR__ ); ?>/images/loading.gif" class="loading-image" style="width: 40px; display:none"
                    id="tfgg_scp_recaptcha_busy"/>
                    <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                    <div id="tfgg_scp_recaptcha_test_results" class="alert" style="display:none; width:30">
                    </div>
                </div>
            </div>
        </div>
        </form>
        <br/><br/>
        <?php
    }

?>