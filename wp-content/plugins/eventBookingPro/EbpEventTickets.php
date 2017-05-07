<?php
require_once dirname( __FILE__ ) . '/include.php';

// ================================================
// ================ Tickets Order Class ============
// ================================================

class EbpTicketsOrder {
  // Note duplicated in frontend.
  const CREATION_ORDER = '1';
  const NAME_ASCENDING = '2';
  const NAME_DESCENDING = '3';
  const COST_ASCENDING = '4';
  const COST_DESCENDING = '5';

  // TODO - Use getAsOptions in admin.js
  public static function getAsOptions() {
    return array(
      (object) ['name' => 'Creation Order', 'value' => self::CREATION_ORDER],
      (object) ['name' => 'Name Ascending', 'value' => self::NAME_ASCENDING],
      (object) ['name' => 'Name Descending', 'value' => self::NAME_DESCENDING],
      (object) ['name' => 'Cost Ascending', 'value' => self::COST_ASCENDING],
      (object) ['name' => 'Cost Descending', 'value' => self::COST_DESCENDING]
      );
  }

  public static function getDefault() {
    return self::CREATION_ORDER;
  }
}


// ================================================
// ================ Tickets Order Class ============
// ================================================

class EbpEventTickets {
  public static function getTablesSQL() {
    //tickets table
    $ticketsTable = EbpDatabase::getTableName("tickets");
    $ticketsTableSQL = "CREATE TABLE " . $ticketsTable ." (
            id INT NOT NULL AUTO_INCREMENT ,
            event int NOT NULL,
            name VARCHAR(100) NOT NULL,
            cost DECIMAL(10,2) NOT NULL,
            allowed int NOT NULL,
            breakdown TEXT,
            PRIMARY KEY (id)
          );";

    return array($ticketsTableSQL);
  }

  public static function addTicket($eventId, $ticket) {
    global $wpdb;
    $ticketsTable = EbpDatabase::getTableName("tickets");

    $ticketId  = $ticket->id;
    $breakdown = (sizeof($ticket->breakdown) > 0 ) ? json_encode($ticket->breakdown) : '';
    $sqlData = array(
      'event'=> $eventId,
      'name'=> $ticket->name,
      'cost'=> $ticket->cost,
      'allowed'=> $ticket->allowed,
      'breakdown'=> $breakdown
    );

    if ($ticketId != "new") {
      $wpdb->update($ticketsTable, $sqlData, array("id"=> $ticketId));
    } else {
      $wpdb->insert($ticketsTable, $sqlData);
      $ticketId = $wpdb->insert_id;
    }

    return $ticketId;
  }

  public static function getTickets($id) {
    global $wpdb;
    $results = $wpdb->get_results("SELECT id, name, cost, allowed, breakdown FROM " .EbpDatabase::getTableName("tickets")." where event= '$id'");

    return $results;
  }

  public static function deleteTicket($id) {
    global $wpdb;
    $wpdb->delete(EbpDatabase::getTableName("tickets"), array('id'=> $id), array('%d'));
  }


}
