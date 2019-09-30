<?php

    function appt_booking(){
        ob_start();
        
        $client=tfgg_cp_get_sunlync_client();
        if($client!=FALSE){
            
            $storelist=json_decode(tfgg_api_get_stores());
            $storelist=$storelist->stores;
            $minDate=new DateTime();

            $appointments = json_decode(tfgg_api_get_client_appointments($client));
            $excludeApptsDateTime=''; 
            if(StrToUpper($appointments->results) === 'SUCCESS'){
                $appointments = $appointments->appointments;
                //var_dump($appointments);
                $excludeAppts='';
                $i=0;
                foreach($appointments as &$details){
                    if($i===0){
                        $excludeAppts.='"'.tfgg_format_date_to_ymd($details->date).'"';
                    }else{
                        $excludeAppts.=',"'.tfgg_format_date_to_ymd($details->date).'"';
                    }
                    $excludeApptsDateTime.='<inputn type="hidden" class="excludeAptsDateTime" value="'.$details->date.' '.$details->start_time.'" '
                    .'data-apptdate="'.$details->date.'" data-appttime="'.$details->start_time.'"/>';
                    $i++;
                }
            }else{
                $excludeAppts='';
            }
        ?>
        <hr />
        <div id="alertpnl_tfgg_appt_set_store_date" style="display:none;" class="alert alert-warning alert-dismissible fade show">
            <span id="alertpnl_tfgg_appt_set_store_date_message">Please select a store</span> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>    
        </div>
        
        <div class="appts-container">
            
            <div id="appts_date_store" class="appts-content appts-container-active">

                <div class="appts-child-main">

                    <h5 style="display:inline-block;padding-right:25px;">Appointment Date</h5>
                    <input type="text" readonly="true" name="tfgg_appt_set_date" id="tfgg_appt_set_date" min="<?php echo date_format($minDate,'Y-m-d');?>" style="display:inline-block;line-height:20px;border-radius:5px;font-size:0.9em !important;" /><br/>
                    <span style="font-size:x-small;"><em>You may only book one appointment per calendar day & 24hr period - future appointment dates are blocked</em></span>
                    <hr />

                    <h5>Select Store</h5>
                    <div>
                        <label for="tfgg_store_filter" class="account-overview-label"><?php _e('Filter store by name'); ?></label>
						<input id="tfgg_store_filter" name="tfgg_store_filter" class="account-overview-input" type="text"/>							
                    </div>
                    <hr/>
                  
                    <div class="appts-store-container">
                        <div id="tfgg_storeselect_warning" class="alert alert-warning" style="display: none;">
                        <p> 
                            No stores match your search
                        </p>
                        </div>
                    <?php
                        foreach($storelist as &$details){
                            if((!strpos(StrToUpper($details->store_loc),'CLOSED'))&&
							(!strpos(StrToUpper($details->store_loc),'DELETED'))){
                                if(($details->allowappts==='1')&&($details->ApptLync==='0')){
                                
                                    echo '<div class="appts-selector appts-store-selector" id="appt_store_panel_' . $details->store_id . '" data-storelocation="' .$details->store_loc . '" data-storecode="'.$details->store_id.'" data-apptlength="'.$details->apptlength.'" '.
                                    'data-apptstarttime="'.$details->apptstarttime.'" data-apptendtime="'.$details->apptendtime.'" onclick="selectStore(\'appt_store_panel_' . $details->store_id.'\');">';
                                    
                                    echo '<span class="appts-store-name"><strong>'.$details->store_loc.'</strong></span>';
                                    echo  '<br />';
                                    echo '<span class="appts-store-address">';
                                    echo substr($details->address1,0,35).'<br /> ';//2019-09-30 CB V1.0.0.6 - substr starts at 0
                                    echo substr($details->address2,0,35).'<br /> ';//2019-09-30 CB V1.0.0.6 - substr starts at 0
                                    
                                    if (!empty($details->city))
                                        echo $details->city.', ';
                                    
                                    echo $details->zip;

                                    echo '</span>';
                                    echo '<br />';
                                    echo '<span class="appts-store-phone">Phone: ';
                                    echo $details->phone;
                                    echo '</span>';
                                    echo '</div>';
                                    
                                }
                            }
    				    }
                    ?>
                        
                    </div>

                </div>

                <div class="appts-child-bottom">

                    <div class="appts-button-container">
                        <button id="tfgg_appt_store_next" class="appts-button appts-standard-button" onclick="ApptStoreSelect();">NEXT</button>
                    </div>

                </div>

            </div><!--appts_date_store-->

            <div id="appts_loading" class="appts-content appts-container-inactive">
                <div class="loading-container">
                    <img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/loading.gif" class="loading-image"/>
                </div>
            </div>
          
            <div id="appts_equipment" class="appts-content appts-container-inactive">

                <div class="appts-child-main">

                    <h5>Select Equipment</h5><hr/>
                           
                    <div name="tfgg_appt_set_equip" id="tfgg_appt_set_equip" class="appts-equipment-container">
                    </div>

                </div>
    
                <div class="appts-child-bottom">

                    <div class="appts-button-container">
                        <button id="btn_equipment_back" class="appts-button appts-back-button" onclick="changeActiveContentPanel('appts_date_store')">BACK</button>
                        <button id="btn_equipment_next" class="appts-button appts-standard-button" onclick="ApptEquipSelect()">NEXT</button>
                    </div>

                </div>
    
            </div><!--appts_equipment-->
        
            <div id="appts_timeslots" class="appts-content appts-container-inactive">

                <div class="appts-child-main">

                    <h5>Select Time Slot</h5><hr/>
                    <div class="appts-timeslot-container" id="appts-timeslot-container">

                    </div>

                </div>
    
                <div class="appts-child-bottom">

                    <div class="appts-button-container">
                        <button  class="appts-button appts-back-button" id="btn_timeslots_back" onclick="changeActiveContentPanel('appts_equipment')">BACK</button>
                        <button id="btn_timeslots_next" class="appts-button appts-standard-button" onclick="TimeSlotSelect();">NEXT</button>
                    </div>

                </div>
    
            </div><!--appts_equipment-->

            <div id="appts_confirm" class="appts-content appts-container-inactive">

                <div class="appts-child-main">

                    <h5>Appointment Details</h5><hr/>
                    
                    <div>
                        <p><span><strong>Store: </strong></span><span id="tfgg_appt_store_text"></span></p>
                        <p><span><strong>Date: </strong></span><span id="tfgg_appt_date_text"></span></p>
                        <p><span><strong>Time: </strong></span><span id="tfgg_appt_time_text"></span></p>
                        <p><span><strong>Equipment: </strong></span><span id="tfgg_appt_equip_text"></span></p>
                        <p><span><strong>Appt Length: </strong></span><span id="tfgg_appt_len_text"></span></p>
                        
                        <input type="hidden" name="tfgg_appt_client" id="tfgg_appt_client" value="<?php echo $client; ?>"/>
                        <input type="hidden" name="tfgg_appt_storecode" id="tfgg_appt_storecode"/>
                        <input type="hidden" name="tfgg_appt_storeloc" id="tfgg_appt_storeloc"/>
                        <input type="hidden" name="tfgg_appt_date" id="tfgg_appt_date"/>
                        <input type="hidden" name="tfgg_appt_equip_type" id="tfgg_appt_equip_type"/>
                        <input type="hidden" name="tfgg_appt_equip_room" id="tfgg_appt_equip_room"/>
                        <input type="hidden" name="tfgg_appt_equip_type_multiplier" id="tfgg_appt_equip_type_multiplier"/>
                        <input type="hidden" name="tfgg_appt_len" id="tfgg_appt_len"/>
                        <input type="hidden" name="tfgg_appt_start_time" id="tfgg_appt_start_time"/>
                    </div>
                       
    
                </div>
    
                    <div class="appts-child-bottom">
    
                        <div class="appts-button-container">
                            <button id="btn_timeslots_back" class="appts-button appts-back-button" onclick="changeActiveContentPanel('appts_timeslots')">BACK</button>
                            <button id="btn_timeslots_next" class="appts-button appts-standard-button" onclick="bookAppt()">BOOK NOW</button>
                        </div>
    
                    </div>
    
            </div><!--appts_confirmation-->
            
            <div id="appts_booking_results" class="appts-content appts-container-inactive">

                <div class="appts-child-main">

                    <h5>Appointment Booking Confirmation</h5><hr/>
                    
                    <div class="appts-booking-results-container" id="appts-booking-results-container">
                        <div id="appt-booking-success" name="appt-booking-success" class="alert alert-success">
                        <?php
                            $message=get_option('tfgg_scp_appts_success');
                            $message=str_replace('!@#store#@!','<span id="tfgg_appt_success_store"></span>', $message);
                            $message=str_replace('!@#apptdate#@!','<span id="tfgg_appt_success_date"></span>', $message);
                            $message=str_replace('!@#appttime#@!','<span id="tfgg_appt_success_time"></span>', $message);
                            echo $message;
                        ?>
                        </div>
                        <div id="appt-booking-fail" name="appt-booking-fail" class="alert alert-warning">
                        <?php
                            echo get_option('tfgg_scp_appts_fail');
                        ?>
                        </div>

                    </div>
                       
    
                </div>
    
                    <div class="appts-child-bottom">
                        <div id="appts-booking-results-container-bottom" class="appts-button-container">
                            <button id="btn_timeslots_back" class="appts-button appts-back-button" onclick="changeActiveContentPanel('appts_timeslots')">BACK</button>
                        </div>
    
                    </div>
    
            </div><!--appts_confirmation-->

        </div><!-- MAIN CONTAINER-->

        <br />
        <button id="btn_accountoverview" class="appts-button appts-standard-button" onclick="javascript:window.location = '<?php echo(get_option('tfgg_scp_acct_overview'));?>';  ">RETURN TO ACCOUNT OVERVIEW</button>

        <script>
            jQuery( function() {
                var now = new Date();
                var exclude = [<?php echo $excludeAppts;?>];
                //exclude=[];
                jQuery( "#tfgg_appt_set_date" ).datepicker({ 
                    minDate: now,
                    dateFormat: 'dd-mm-yy',
                    beforeShowDay: function(date){
                        var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                        return [ exclude.indexOf(string) == -1 ]
                    }}
                );
            } );
        </script>
        
        <?php
        echo $excludeApptsDateTime;
        }else{
            //this is a backup for the code in inc-shortcodes.php
            //echo '<script>location.href="'.get_option('tfgg_scp_cplogin_page').'"</script>';    
        }
        return ob_get_clean();
        //return ob_get_contents();
    }

?>