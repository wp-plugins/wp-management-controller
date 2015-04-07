<?php
if(basename($_SERVER['SCRIPT_FILENAME']) == "wordfence.class.php"):
    exit;
endif;
class MMB_WORDFENCE extends MMB_Core
{
    public function __construct()
    {
        parent::__construct();
    }
	
	 public function load() {
	 	if($this->_checkWordFence()) {
	 		
	 		if(wfUtils::isScanRunning()){
	 			return array('scan'=>'yes');
	 		} else {
	 			return wordfence::ajax_loadIssues_callback();
	 		}
	 	} else {
	 		return array('warning'=>"Word Fence plugin is not activated");
	 	}
	 }
	 
	 public function scan() {
	 	if($this->_checkWordFence()) {
	 		return wordfence::ajax_scan_callback();
	 	} else {
	 		return array('error'=>"Word Fence plugin is not activated");
	 	}
	 }
	 
	 private function _checkWordFence() {
	 	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	 	if ( is_plugin_active( 'wordfence/wordfence.php' ) ) {
	 		@include_once(WP_PLUGIN_DIR . '/wordfence/wordfence.php');
	 		if (class_exists('wordfence')) {
		    	return true;
			} else {
				return false;
			}
	 	} else {
	 		return false;
	 	}
	 	
		
		
	 }
    
}