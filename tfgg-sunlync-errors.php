<?php

    //used to track errors through out the plugin
    function tfgg_cp_errors(){
        static $wp_error; // Will hold global variable safely
        return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));    
    }//tfgg_cp_errors
    
    //used to format and display any errors
    function tfgg_sunlync_cp_show_error_messages(){
        if($codes = tfgg_cp_errors()->get_error_codes()) {
    		$output= '<div class="tfgg_cp_errors">';//need to setup a special CSS class for this
    		    // Loop error codes and display errors
    		   foreach($codes as $code){
    		        $message = tfgg_cp_errors()->get_error_message($code);
                    //echo($code);
                    $error_pos=strpos($code,'error');
                    $warning_pos=strpos($code,'warning');
    		        if($error_pos===0){
    		            $output.='<div class="alert alert-danger alert-dismissible fade show">
    		            <span class="error"><strong>' . __('Error') . '</strong>: ';
    		        }else if($warning_pos===0){
    		            $output.='<div class="alert alert-warning alert-dismissible fade show">
    		            <span class="warning"><strong>' . __('Warning') . '</strong>: ';    
    		        }else{
    		            $output.='<div class="alert alert-success alert-dismissible fade show">
    		            <span class="success">';    
    		        }
    		        $output.=$message . '</span>
		                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
		            </div>'; 
    		    }
			$output.= '</div>';
			
			//2019-11-14 CB V1.2.3.1
			$script='';
			if($code=='success_reg_complete'){
				$script="<script type=\"text/javascript\">";
				$script.='setTimeout(function() {';
				$script.='jQuery(".alert-dismissible").remove();';
				$script.='}, 10000);';
				$script.="</script>";
			}
			$output.=$script;

    		echo $output;
    	}    
    }//tfgg_sunlync_cp_show_error_messages

?>