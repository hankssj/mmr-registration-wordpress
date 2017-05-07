<?php
require_once dirname( __FILE__ ) . '/include.php';

class EbpCoupon {
  const COUPON_NOT_FOUND = 'COUPON_NOT_FOUND';
  const COUPON_EXPIRED = 'COUPON_EXPIRED';
  const COUPON_FOUND = 'COUPON_FOUND';

  public static function getTablesSQL() {
    //coupon table
    $couponsTable = EbpDatabase::getTableName("coupons");
    $couponSQL = "CREATE TABLE " . $couponsTable ." (
            id INT NOT NULL AUTO_INCREMENT,
            code VARCHAR(100) NOT NULL,
            name VARCHAR(100) NOT NULL,
            type VARCHAR(10) default 'single',
            amount VARCHAR(10) NOT NULL,
            isActive VARCHAR(6) NOT NULL,
            maxAllowed INT default '-1',
            PRIMARY KEY (id)
          );";

    $couponsUsedTable = EbpDatabase::getTableName("couponsUsed");
    $couponsUsedSQL = "CREATE TABLE " . $couponsUsedTable ." (
            id INT NOT NULL AUTO_INCREMENT,
            coupon INT NOT NULL,
            payment INT NOT NULL,
            date_used date DEFAULT NULL,
            PRIMARY KEY (id)
          );";


    //eventCoupons table
    $eventCouponsTable = EbpDatabase::getTableName("eventCoupons");
    $eventCouponsTableSQL = "CREATE TABLE " . $eventCouponsTable ." (
            id INT NOT NULL AUTO_INCREMENT ,
            event int NOT NULL,
            coupon int NOT NULL,
            PRIMARY KEY (id)
          );";

    return array($couponSQL, $couponsUsedSQL, $eventCouponsTableSQL);
  }
  /**
  * FRONTEND FUNCTIONS
  */

  public static function checkCoupon($event, $code) {
    global $wpdb;

    $settings = $wpdb->get_row("select coupon_expired_msg, coupon_msg, coupon_not_found_msg, currency, priceThousandsSep, currencyBefore, priceDecimalCount, priceDecPoint  from ". EbpDatabase::getTableName("settings")." where id=1");

    $curSymbol = EventBookingHelpers::getSymbol($settings->currency);

    $EXPIRED_ARRAY = array('code' => EbpCoupon::COUPON_EXPIRED, 'msg'=> $settings->coupon_expired_msg);
    $NOT_FOUND_ARRAY = array('code' => EbpCoupon::COUPON_NOT_FOUND, 'msg'=> $settings->coupon_not_found_msg);

    $coupon = $wpdb->get_row("select * from ". EbpDatabase::getTableName("coupons")." where code='$code'");

    if($wpdb->num_rows < 1) {
      return $NOT_FOUND_ARRAY;
    }

    if ($coupon->isActive == 'false') {
      return $EXPIRED_ARRAY;
    }

    $couponid = $coupon->id;
    $available = $wpdb->get_var("select COUNT(*) from ". EbpDatabase::getTableName("eventCoupons")." where event= '$event' and coupon= '$couponid'");

    $couponUsed = $wpdb->get_var("select COUNT(*) from ". EbpDatabase::getTableName("couponsUsed")." where coupon='$couponid'");

    if (intval($coupon->maxAllowed) >= 0 && intval($coupon->maxAllowed) <=  intval($couponUsed)) {
      return $EXPIRED_ARRAY;
    } else if ($available > 0) {
      $msg = str_replace('%name%', stripslashes($coupon->name), $settings->coupon_msg);

      if (strpos($coupon->amount, '%') !== false) {
        $msg = str_replace('%amount%', '<strong>'.stripslashes($coupon->amount).'</strong>', $msg);
      } else {
        $msg = str_replace('%amount%', EventBookingHelpers::currencyPricingFormat($coupon->amount, $curSymbol,
          $settings->currencyBefore, $settings->priceDecimalCount, $settings->priceDecPoint, $settings->priceThousandsSep,
          '<strong>%cost%</strong>'), $msg);
      }

      return array(
        'code' => EbpCoupon::COUPON_FOUND,
        'id' => $couponid,
        'type' => $coupon->type,
        'coupon'=> $code,
        'amount'=> $coupon->amount,
        'msg' => $msg
        );
    } else {
      return $NOT_FOUND_ARRAY;
    }
  }

  /**
  * ADMIN PAGE FUNCTIONS
  */
  public static function getCouponsAdminPage() {
    global $wpdb;
    $results = $wpdb->get_results("SELECT * FROM " . EbpDatabase::getTableName("coupons"));

    $html = '<h2>Coupons</h2>';
    $html .= '<div class="coupons">';


    foreach ($results as $result) {
      $active = ($result->isActive == "false")  ? "deactive" : '';
      $html .= '<a href="#" class= "coupon editCoupon '.$active.'" data-id="'.$result->id.'">' . stripslashes($result->name) . '</a>';
    }

    $couponCount = intval($wpdb->get_var("select max(id) from " . EbpDatabase::getTableName("coupons"))) + 1;
    $html .= '<a href="#" class= "coupon newCoupon" data-id="'.$couponCount.'">+ Add new coupon</a>';
    $html .= '</div>';
    $html .= '<div class="EBP--CouponsDetails">';
    $html .= '</div>';

    return $html;
  }

  public static function getCoupon($id) {
    global $wpdb;

    $result = $wpdb->get_row( "SELECT * FROM " . EbpDatabase::getTableName("coupons")." where id= '$id'");
    if ($result != NULL) {
      return $result;
    } else {
      return array('error' => 'Error while getting coupon data');
    }
  }

  public static function saveCoupon($id, $name, $amount, $code, $type, $maxAllowed, $isActive) {
    global $wpdb;
    $tableName = EbpDatabase::getTableName("coupons");

    $valid = $wpdb->get_var("select COUNT(*) from " . $tableName ." where code= '$code' and id<>'$id'");

    if ($valid > 0) {
      return array('error' => 'codeError');
    }

    $isAvilable = $wpdb->get_var("select COUNT(*) from " . $tableName ." where id= '$id'");

    $couponData = array(
      'name'=> $name,
      'amount'=> $amount,
      'code'=> $code,
      'type'=> $type,
      'isActive'=> $isActive,
      'maxAllowed'=> $maxAllowed
    );

    if ($isAvilable > 0) {
      $wpdb->update($tableName, $couponData, array('id'=> $id));
    } else {
      $couponData['id'] = $id;
      $wpdb->insert($tableName, $couponData);
    }

    $maxId = intval($wpdb->get_var( "select max(id) from " . $tableName )) + 1;

    $html = $name;

    return array('maxId' => $maxId, 'id' => $id, 'html' => $html);
  }

  public static function deleteCoupon($id) {
    global $wpdb;
    $wpdb->delete(EbpDatabase::getTableName("coupons"), array('id'=> $id));

    return $id;
  }

  public static function getEventCoupons($event) {
    global $wpdb;
    $results = $wpdb->get_results( "SELECT * FROM " . EbpDatabase::getTableName("coupons"));
    $data = '';

    foreach ($results as $result) {
      $id = $result->id;
      $active = "notselected";
      $isAvilable = $wpdb->get_var( "select COUNT(*) from ". EbpDatabase::getTableName("eventCoupons")." where event= '$event' and coupon= '$id'");

      if ($isAvilable > 0) $active = "";

      $data .= '<a href="#" class= "coupon toggle '.$active.'" data-id="'.$id.'">'.stripslashes($result->name).' ('.$result->amount.') - code: '.$result->code.'</a>';
    }

    return '<div class= "coupons">' . $data . '</div>';;
  }

  public static function updateEventCoupon($params) {
    global $wpdb;
    $event = $params['id'];
    $coupon = '';
    $tableName = EbpDatabase::getTableName("eventCoupons");

    foreach ($params as $key=> $value) {
      list($type, $id) = explode('-', $key);

      if ($type == "couponid") {
        $coupon = $value;
      } else if ($type == "selected") {
        if ($value == "false") {
          $isAvilable = $wpdb->get_var( "select COUNT(*) from " . $tableName ." where event= '$event' and coupon= '$coupon'");

          if ($isAvilable == 0) {
            $wpdb->insert($tableName, array('event'=> $event, 'coupon'=> $coupon));
          }

        } else if ($value == "true") {
          $wpdb->delete( $tableName, array( 'event'=> $event, 'coupon'=> $coupon ) );
        }
      }
    }

    return true;
  }
}
?>
