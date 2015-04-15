<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == "sucuri.class.php"):
    exit;
endif;
class MMB_SUCURI extends MMB_Core
{
    public function __construct() {
        parent::__construct();
    }
    
    public function scan() {
        if ($this->_checkSucuri()) {
        	 global $wp_version;
            $website_scanned = home_url();
            $remote_url = 'http://sitecheck.sucuri.net/scanner/?serialized&clear&fromwp&scan=' . $website_scanned;
            $myresults = wp_remote_get($remote_url, array('timeout' => 180));
            $myresults = unserialize($myresults['body']);
            $myresults['PHPVERSION'] = phpversion();
            $myresults['UPDATES'] = function_exists('get_core_updates') ? get_core_updates() : array();
            $myresults['WEBSITESCANNED'] = $website_scanned;
            $myresults['WPVERSION'] = htmlspecialchars($wp_version);
            if (is_wp_error($myresults)) {
                $return['type'] = 'error';
                $return['data'] = print_r($myresults, 1);
            } else if (preg_match('/^ERROR:/', $myresults)) {
                $return['type'] = 'error';
                $return['data'] = $myresults['body'] . ' The URL scanned was: <code>' . $website_scanned . '</code>';
            } else {
                $return['type'] = 'success';
                $return['data'] = $myresults;
            }
            return $return;
        } else {
            $return['type'] = 'error';
            $return['msg'] = "Sucuri plugin is not activated";
        }
    }
    
    private function _checkSucuri() {
        include_once (ABSPATH . 'wp-admin/includes/plugin.php');
        if (is_plugin_active('sucuri-scanner/sucuri.php')) {
            return true;
        } else {
            return false;
        }
    }
}
