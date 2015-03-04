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
        require_once (pb_backupbuddy::plugin_path() . '/destinations/bootstrap.php');
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
    
    function deleteSchedule($params) {
        $schedules = $this->listschedule();
        if ($schedules) {
            foreach ($schedules as $schedule) {
                if ($schedule['title'] == $params['account_detail']['title']) {
                    $scheduleid = $schedule['id'];
                    break;
                }
            }
            $result = backupbuddy_api::deleteSchedule($scheduleid, $confirm = true);
            return 'success';
        }
    }
    
    function addSchedule($params) {
        $type = $params['type'];
        $profile = $params['profile'];
        
        //1 or 2
        $dest = $this->listDestinations();
        $accountfound = false;
        $key = false;
        
        if ($dest) {
            foreach ($dest as $key => $d) {
                
                if ($d['title'] == $params['account_detail']['title']) {
                    $useAccount = $d;
                    $accountfound = true;
                    $currentAccountProfileId = $key;
                }
            }
            $totaldest = count($dest);
        }
        
        if (!$accountfound) {
            
            //add destination
            $this->addDestination($params['account_detail']);
            if (!$key) {
                $currentAccountProfileId = 0;
            } else {
                $currentAccountProfileId = $totaldest;
            }
            backupbuddy_api::addSchedule($params['backup_name'], $params['backup_type'], $params['schedule'], $params['starttime'], array($currentAccountProfileId), true, true);
            return 'success';
        } else {
            //adding the schedule
            backupbuddy_api::addSchedule($params['backup_name'], $params['backup_type'], $params['schedule'], $params['starttime'], array($currentAccountProfileId), true, true);
            return 'success';
        }
    }
    
    function runbackup($params) {
        $type = $params['type'];
        $profile = $params['profile'];
        
        //1 or 2
        $dest = $this->listDestinations();
        $accountfound = false;
        $key = false;
        if ($dest) {
            foreach ($dest as $key => $d) {
                if ($d['title'] = $params['title']) {
                    $useAccount = $d;
                    $accountfound = true;
                    $currentAccountProfileId = $key;
                }
            }
        }
        if ($accountfound == false) {
            
            //add destination
            $this->addDestination($params['account_detail'], $type);
            if (!$key) {
                $currentAccountProfileId = $key;
            } else {
                $currentAccountProfileId = $key + 1;
            }
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
}
?>