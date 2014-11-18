<?php
class MMB_BuddyBackup
{
    function __construct() {
        include_once (ABSPATH . 'wp-admin/includes/plugin.php');
        if (!is_plugin_active('backupbuddy/init_global.php')) {
            return false;
        }
    }
    
    function listDestinationType() {
        require_once (pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php');
        return pb_backupbuddy_destinations::get_destinations_list();
    }
    
    function listDestinations() {
        return pb_backupbuddy::$options['remote_destinations'];
    }
    
    function addDestination($params) {
        pb_backupbuddy::$options['remote_destinations'][] = $params;
        pb_backupbuddy::save();
        return true;
    }
    
    function deleteDestination($arguments) {
        require_once (pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php');
        $response = pb_backupbuddy_destinations::delete($arguments['id'], true);
    }
    function getDestinationAll() {
        return pb_backupbuddy::$options['remote_destinations'];
    }
    
    function getDestinationById($arguments) {
        return pb_backupbuddy::$options['remote_destinations'][$arguments['id']];
    }
    
    function listschedule() {
        return $schedules = backupbuddy_api::getSchedules();
    }
    
    function runbackup($params) {
        $type = $params['type'];
        $profile = $params['profile'];
         //1 or 2
        $dest = $this->listDestinations();
        $accountfound = false;
        
        if ($dest) {
            foreach ($dest as $d) {
                if ($d['title'] = $params['title']) {
                    $useAccount = $d;
                    $accountfound = true;
                }
            }
        }
        if ($accountfound == false) {
            
            //add destination
            $this->addDestination($params['account_detail'], $type);
        } else {
            $params['account_detail']['type'] = $type;
            $a['profile'] = $params['account_detail'];
        }
        
        return backupbuddy_api::runBackup($profile, 'iThemes Sync', $backupMode = '2');
    }
    function testDestination($arguments) {
        require_once (pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php');
        return pb_backupbuddy_destinations::test($settings);
    }
    
    function deleteSchedule($arguments) {
        return $result = backupbuddy_api::deleteSchedule($arguments['id'], $confirm = true);
    }
}
?>