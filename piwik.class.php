<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == "piwik.class.php"):
    exit;
endif;
class MMB_PIWIK extends MMB_Core
{
    public function __construct() {
        parent::__construct();
    }
    
    public function addpiwikcode($params){
        if($params['id']){
           update_option('_piwik_domain_id',$params['id']);
           return 'success';
        }
    }
}