<?php

    function display_tfgg_card_data_log(){

        display_tfgg_card_data_header();

        $returnData = tfgg_scp_read_card_transaction_log('',0,100);

        //var_dump($returnData);

        if(!$returnData){
            display_tfgg_card_data_empty_dataset();    
        }else{
            display_tfgg_card_data_table_header();
            ?>
            <tbody>
            <?php
                foreach($returnData as &$details){
                ?>
                <tr>
                    <td><?php echo $details->entrylogged;?></td>
                    <td><?php echo $details->cart_id;?></td>
                    <td><?php echo $details->clientnumber;?></td>
                    <td><?php echo $details->firstname.' '.$details->lastname;?></td>
                    <td><?php echo $details->card_processor;?></td>
                    <td><?php echo $details->process;?></td>
                    <td class="tfgg-card-log-results" 
                    data-fullResult="<?php echo str_replace(', ',',<br/>',str_replace('%5D' ,']',str_replace('%5B','[',$details->result)));?>"><?php echo substr($details->result,0,20).'...';?></td>
                </tr>
                <?php
                }
            ?>
            </tbody>
            <?php
            display_tfgg_card_data_table_footer();
            display_tfgg_card_data_result_dialog();
        }

    }

    function display_tfgg_card_data_header(){
    ?>
        <div class="clearfix">&nbsp;</div>
        <h4>Card Transaction Log</h4>
        <div class="clearfix">&nbsp;</div>
    <?php
    }
    
    function display_tfgg_card_data_empty_dataset(){
    ?>
        <br/>
        <div id="" class="alert alert-warning">
        <span>No data returned</span>            
        </div>
        <br/>
    <?php
    }

    function display_tfgg_card_data_table_header(){
    ?>
        <table class="table table-sm table-bordered table-striped table-hover" style="width:95%">
        <thead class="thead-dark">
            <tr>
                <td>Date</td>
                <td>Cart ID</td>
                <td>Client Number</td>
                <td>Client Name</td>
                <td>Processor</td>
                <td>Action</td>
                <td>Results</td>
            </tr>
        </thead>
    <?php
    }

    function display_tfgg_card_data_table_footer(){
    ?>
        </table>
        <div class="clearfix">&nbsp;</div>
    <?php
    }

    function display_tfgg_card_data_result_dialog(){
    ?>
    <div <?php if(tfgg_ae_detect_ie()){?>style="z-index: 10000 !important; margin-top:25%"<?php } ?> class="modal fade" id="tfgg_scp_card_data_results" tabindex="-1" role="dialog" aria-labelledby="tfgg_scp_card_data_results aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-body" id="tfgg_scp_card_data_result_content">
                </div>
                <div class="modal-footer">
                    <button id="tfgg_scp_card_data_result_close" type="button" class="account-overview-button account-overview-standard-button account-overview-appt-cancel-button" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>
    <?php
    }

?>