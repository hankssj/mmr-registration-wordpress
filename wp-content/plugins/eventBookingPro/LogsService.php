<?php
require_once dirname( __FILE__ ) . '/include.php';

class LogsTypes {

  const EVENT_UPDATED = 'EVENT_UPDATED';
  const EVENT_CREATED = 'EVENT_CREATED';
  const EVENT_CANCELED = 'EVENT_CANCELED';
  const EVENT_ACTIVATED = 'EVENT_ACTIVATED';
  const EVENT_DEACTIVATED = 'EVENT_DEACTIVATED';

}

class LogsService {
  public static function getTablesSQL() {
    $eventLogsSQL = "CREATE TABLE " . EbpDatabase::getTableName("eventLogs") ." (
            id INT NOT NULL AUTO_INCREMENT,
            date DATETIME NOT NULL,
            type VARCHAR(255) NOT NULL,
            event int NOT NULL,
            log VARCHAR(255) default '',
            user int NOT NULL,
            PRIMARY KEY (id)
          );";

    return array($eventLogsSQL);
  }

  public static function eventLog($event, $type, $log) {
    global $wpdb;
    $wpdb->insert(EbpDatabase::getTableName("eventLogs"), array('user'=> get_current_user_id(), 'event'=>$event, 'type' => $type, 'log'=>$log, 'date'=> date('Y-m-d H:i:s')));

    return true;
  }

  public static function getOperationLogsForEvent($event) {
    global $wpdb;
    // $types = '"'.LogsTypes::EVENT_CANCELED . '", "' . LogsTypes::EVENT_ACTIVATED . '", "' . LogsTypes::EVENT_DEACTIVATED . '"';
    $usersTable =  $wpdb->base_prefix .'users';
    $results = $wpdb->get_results("SELECT l.*, u.user_login FROM " .EbpDatabase::getTableName("eventLogs")." as l LEFT JOIN ".$usersTable." as u ON l.user=u.ID where event='$event'");
    return $results;
  }
}
