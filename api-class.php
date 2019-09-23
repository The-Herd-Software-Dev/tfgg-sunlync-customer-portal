<?php


    class sun_api{
        private $_url;
    	private $_user;
    	private $_pass;
    	private $_mrkt;
    	
    	private function parseResult($s){
    		
    		$s=str_replace('{"result":[','',$s);
    		$s=str_replace(']}','',$s);
    		$s=json_decode($s);
    
    		return $s;
    
    	}//parseResult
    	
    	private function getData($url){
    	    $ch = curl_init($url);
    		$ch_options=array(
    			CURLOPT_RETURNTRANSFER=> true,
    			CURLOPT_USERPWD=>$this->_user.":".$this->_pass,
    			CURLOPT_HTTPHEADER=>array('Content-type: application/json')
    		);
    
    		curl_setopt_array($ch,$ch_options);
    		$result=curl_exec($ch);
    		curl_close($ch);
    		
    		if(($result===FALSE)||($result=='')){
    			throw new Exception("ERROR: Invalid URL");
    			exit;
    		}
    		
    		$result=$this->parseResult($result);
    		
    		return $result;
    		
    	}
        
        public function __construct(){
            $this->_url = get_option('tfgg_scp_api_protocol').'://'.get_option('tfgg_scp_api_url').':'.get_option('tfgg_scp_api_port').'/datasnap/rest/';
            $this->_mrkt = get_option('tfgg_scp_api_mrkt');
            $this->_user = get_option('tfgg_scp_api_user');
            $this->_pass = get_option('tfgg_scp_api_pass');
        }
        
        public function GetAPIVersion(){
            $url = $this->_url.'TSunLyncAPI/GenericGetAPIVersion';
            
            $data = $this->getData($url);
            
            return $data[0]->result;
        }
    }
    
    $sunlync_api = new sun_api();
?>