<?php

    function tfgg_scp_output_freebie_marketing_processing(){
        ob_start();

        global $post;
		$post_slug = $post->post_name;

        if(!array_key_exists('cn',$_GET)){
            //no clientnumber, so just leave
            return ob_get_clean();
        }
        
        $processingResults=tfgg_scp_process_freebie_marketing($post_slug,$_GET['cn']);

        if(!$processingResults){return ob_get_clean();}

        $cs_email = get_option('tfgg_scp_customer_service_email');
        $processingResults=json_decode($processingResults);
        
        switch(StrToupper($processingResults->results)){
            case 'SUCCESS':
            ?>
            <div class="card alert-success">
                <div class="card-header">
                    Congratulations!
                </div>
                <div class="card-body">
                    <p class="card-text">The <?php echo $processingResults->freebie_desc;?> has been successfully applied to your account</p>
                </div>
            </div>
            <div style="margin-bottom:10px"></div>
            <?php
                break;
            default:
            ?>
            <div class="card alert-warning">
                <div class="card-header">
                    Unable to process
                </div>
                <div class="card-body">
                    <h5 class="card-title">There appears to be an issue with applying the promotion to your account</h5>
                    <p class="card-text">When attempting to process this free <?php echo $processingResults->freebie_desc;?> to your account, we were returned this warning: <?php echo $processingResults->response;?></p>
                    <p class="card-text">Please contact our customer service representatives at <a href="mailto:<?php echo $cs_email; ?>?subject=Marketing Promo Issues"><?php echo $cs_email; ?></a> for assistance</p>
                </div>
            </div>
            <div style="margin-bottom:10px"></div>
            <?php
                break;
        }

        return ob_get_clean();
    }

?>