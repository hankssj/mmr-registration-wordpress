<?php

class BookingStatus {
  CONST NOT_PAID = 'Not Paid';
  CONST PAID = 'Paid';
  CONST PENDING = 'Pending';
  CONST COMPLETED = 'Completed';
}

class BookingType {
  CONST SITE = 'site';
  CONST PAYPAL = 'paypal';
}

class EbpBooking {

  public static function getTablesSQL() {
    //payments
    $paymentTable = EbpDatabase::getTableName("payments");
    $paymentsSQL = "CREATE TABLE " . $paymentTable ." (
            id INT NOT NULL AUTO_INCREMENT ,
            event_id INT NOT NULL,
            date_id INT NOT NULL,
            ticket_id INT NOT NULL,
            date_paid date DEFAULT NULL,
            coupon VARCHAR(100) NOT NULL,
            type VARCHAR(255) DEFAULT NULL,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) DEFAULT NULL,
            phone VARCHAR(20) NOT NULL,
            address VARCHAR(255) NOT NULL,
            txn_id VARCHAR(45) DEFAULT NULL,
            amount VARCHAR(20) DEFAULT NULL,
            quantity INT NOT NULL,
            tax_rate DECIMAL(5,2) default NULL,
            amount_taxed VARCHAR(20) DEFAULT NULL,
            status VARCHAR(45) NOT NULL DEFAULT 'pending',
            extras TEXT NOT NULL,
            came VARCHAR(7) default 'false',
            PRIMARY KEY (id)
          );";


    //gateways table
    $gatewaysTable = EbpDatabase::getTableName("gateways");
    $gatewaysTableSQL = "CREATE TABLE " . $gatewaysTable ." (
            id INT NOT NULL AUTO_INCREMENT ,
            name VARCHAR(255) NOT NULL,
            module VARCHAR(255) NOT NULL,
            active int default '1',
            PRIMARY KEY (id)
          );";

    return array($paymentsSQL, $gatewaysTableSQL);
  }

  public static function bookEvent($eventId, $ticket, $dateid, $coupon, $couponId, $couponAmountUsed, $couponType, $amount, $bookName,
    $bookEmail, $quantity, $bookingDetails, $currentPage, $eventName, $bookingType, $taxRate, $amountTaxed) {

    global $wpdb;
    $paymentTable = EbpDatabase::getTableName("payments");

    if ($coupon == '' || $coupon == NULL) $coupon = 'N.A.';

    $address = 'N.A.';
    $phone = 'N.A.';

    $date = new DateTime();
    $payment_date = $date->format('Y-m-d');

    $settings = $wpdb->get_row("SELECT modalSpotsLeftTxt, limitBookingPerEmail, limitBookingPerEmailCount, bookingLimitText, limitBookingPerTime, limitBookingPerTimeCount, bookingLimitTimeText, eventBookedTxt, doAfterSuccess, doAfterSuccessRedirectURL, doAfterSuccessTitle, doAfterSuccessMessage FROM " . EbpDatabase::getTableName("settings")." where id=1 ");

    $bookingsPerEmail = $wpdb->get_results("SELECT quantity FROM ".$paymentTable ." where date_id='$dateid' and email='$bookEmail'");
    $bookingCount = intval($wpdb->num_rows);

    // check if person can book
    // check if booking allowed
    if ($settings->limitBookingPerTime == 'true') {
      $limitPerTime = intval($settings->limitBookingPerTimeCount);

      if ($limitPerTime < $quantity) {
        $msg = str_replace('%left%', $limitPerTime, $settings->bookingLimitTimeText);

        return array('code'=>'ERROR', 'error'=>$msg);
      }
    }

    // check if person can book
    // check if quantity allowed
    if ($settings->limitBookingPerEmail == 'true') {
      $bookingsLeft = intval($settings->limitBookingPerEmailCount);

      foreach($bookingsPerEmail as $booking) {
        $bookingsLeft -= intval($booking->quantity);
      }

      if ($bookingsLeft < 0) $bookingsLeft = 0;
      if ($bookingsLeft < $quantity) {
        $msg = str_replace('%left%', $bookingsLeft, $settings->bookingLimitText);

        return array('code'=>'ERROR', 'error'=>$msg);
      }
    }

    //check if quantity is still available.
    $totalAllowed = intval(Event::checkSpots($dateid, $ticket));

    if (intval($quantity) > $totalAllowed) {
      $msg = 'Unsuccessful! '.$settings->modalSpotsLeftTxt.' '.$totalAllowed;

      return array('code'=>'ERROR', 'error'=>$msg);
    }

    $status = ($bookingType == BookingType::SITE) ? BookingStatus::NOT_PAID : BookingStatus::PENDING;

    // case paypal and price was zero then mark it as Completed
    if ($bookingType == BookingType::PAYPAL && $amount == 0) {
      $status = BookingStatus::COMPLETED;
      // treat it as a site booking
      $bookingType = BookingType::SITE;
    }

    $wpdb->insert($paymentTable,
      array(
        'event_id'=> $eventId,
        'date_id'=> $dateid,
        'ticket_id'=> $ticket,
        'coupon'=> $coupon,
        'date_paid'=> $payment_date,
        'type'=> $bookingType,
        'name'=> $bookName,
        'email'=> $bookEmail,
        'phone'=> $phone,
        'address'=> $address,
        'txn_id'=> 'N.A',
        'amount'=> $amount,
        'quantity'=> $quantity,
        'tax_rate'=>$taxRate,
        'amount_taxed'=>$amountTaxed,
        'status'=> $status,
        'extras'=> $bookingDetails)
    );

    $paymentID = $wpdb->insert_id;

    if ($couponId != '' && $couponId != NULL) {
      if (AddOnManager::usesGiftCardAddon() && $couponType == EventBookingProGiftCardClass::GIFT_CARD_TYPE) {
        EventBookingProGiftCardClass::giftCardUsed($couponId, $paymentID, couponAmountUsed);
      } else {
        $wpdb->insert(EbpDatabase::getTableName("couponsUsed"),
        array('coupon'=> $couponId, 'payment'=> $paymentID, 'date_used'=>$payment_date));
      }
    }

     // handle emails
    if (AddOnManager::usesEmailRules()) {
      eventBookingProEmailsClass::applyRules($eventId, $dateid, $paymentID);
    }

    // handle booking type
    $retrunArr = array('code'=>'ERROR', 'error'=>'No corresponding booking type');

    switch($bookingType) {
      case BookingType::SITE:
        EmailService::createEmailAndSend($paymentID, "booking");

        // return message
        $action = $settings->doAfterSuccess;
        $retrunArr = array('code'=>'SUCCESS', 'action'=>$action, 'successText'=>$settings->eventBookedTxt);
        if ($action == 'redirect') {
          $retrunArr['url'] = $settings->doAfterSuccessRedirectURL;;
        } else if ($action == "popup") {
          $retrunArr['popupid'] = 'ebpSuccessPopup';
          $retrunArr['popup'] = self::getSuccessPopUpHTML('ebpSuccessPopup', $settings->doAfterSuccessTitle, $settings->doAfterSuccessMessage);
        }

      break;

      case BookingType::PAYPAL:
        $settings = $wpdb->get_row( "SELECT dateFormat, timeFormat, tax_rate, return_page_url FROM " . EbpDatabase::getTableName("settings")." where id='1' ");

        $date_format = EventBookingHelpers::convertDateFormat($settings->dateFormat);
        $time_format = $settings->timeFormat;

        $eventDate = $wpdb->get_row("SELECT start_date, end_date, start_time, end_time FROM " .EbpDatabase::getTableName("eventDates")." where id='$dateid'");
        $startDate = utf8_encode(strftime($date_format, strtotime($eventDate->start_date)));
        $endDate = utf8_encode(strftime($date_format, strtotime($eventDate->end_date)));

        $start_time = date($time_format, strtotime($eventDate->start_time));
        $end_time = date($time_format, strtotime($eventDate->end_time));

        // generate paypal item name
        $desc = $eventName . ' - '.$startDate. ' @ '.$start_time;

        $form = self::getPayPalForm($paymentID, $desc, ($amount / $quantity), $quantity, $settings->tax_rate, $settings->return_page_url, $currentPage);


        $retrunArr = array('code'=>'FORM', 'form'=>$form);
      break;

      default:
        $gatewayInfo = $wpdb->get_row( "SELECT * FROM " . EbpDatabase::getTableName("gateways")." where name= '$bookingType'");
        $module = $gatewayInfo->module."Helpers";
        require_once(ABSPATH . 'wp-content/plugins/'.$gatewayInfo->module.'/'.$module . ".php");
        $gatewayObj = new $module;
        $gatewayType = $gatewayObj->getType();

        switch($gatewayType) {
          case "form":
            $form = $gatewayObj->getPayForm($paymentID);
            $retrunArr = array('code'=>'FORM', 'form'=>$form);
          break;

          case "url":
            $url = $gatewayObj->getURL($paymentID);
            $retrunArr = array('code'=>'URL', 'url'=>$url);
          break;
        }
      break;
    }

    return $retrunArr;
  }

  public static function getSuccessPopUpHTML($id, $title, $message) {
    $html = '<div class= "Modal--SuccessTitle">'.$title.'</div>';
    $html .= '<div class= "Modal--SuccessMsg">'.$message.'</div>';
    return $html;
  }

  public static function getPayPalForm($id, $desc, $cost, $quantity, $taxRate, $returnPage, $currentPage) {
    global $wpdb;
    $cost = number_format($cost, 2, ".", "");
    $settings = $wpdb->get_row( "SELECT return_same_page, cpp_payflow_color, cpp_logo_image, cpp_headerborder_color, cpp_headerback_color, cpp_header_image, sandbox, currency, paypalAccount, dateFormat, timeFormat, email_utf8, tax_rate FROM " . EbpDatabase::getTableName("settings")." where id='1' ");

    $paypalAccount = $settings->paypalAccount;
    $curr = $settings->currency;
    $sandbox = $settings->sandbox;
    $cpp_header_image = $settings->cpp_header_image;
    $cpp_headerback_color = $settings->cpp_headerback_color;
    $cpp_headerborder_color = $settings->cpp_headerborder_color;
    $cpp_logo_image = $settings->cpp_logo_image;
    $cpp_payflow_color = $settings->cpp_payflow_color;
    $return_page_url = ($settings->return_same_page == "false") ? $returnPage : $currentPage;
    $utf8 = $settings->email_utf8;

    // get paypal url
    $uri = ($sandbox == "true") ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';

    // generate form html
    $form = '<form id= "paypalForm" action="'.$uri.'" method= "post" style= "display:none;">';
    $form .= '<input type= "hidden" name= "business" value="'.$paypalAccount.'">';
    $form .= '<input type= "hidden" name= "cmd" value= "_xclick">';
    $form .= '<input type= "hidden" name= "custom"  id= "custom" value="'.$id.'">';
    $form .= '<input type= "hidden" name= "notify_url" value="'.site_url().'/paypal/paypalScript.php">';
    $form .= '<input type= "hidden" name= "cpp_header_image" value="'.$cpp_header_image.'"> ';
    $form .= '<input type= "hidden" name= "cpp_headerback_color" value="'.$cpp_headerback_color.'"> ';
    $form .= '<input type= "hidden" name= "cpp_headerborder_color" value="'.$cpp_headerborder_color.'"> ';
    $form .= '<input type= "hidden" name= "image_url" value="'.$cpp_logo_image.'"> ';
    $form .= '<input type= "hidden" name= "cpp_payflow_color" value="'.$cpp_payflow_color.'"> ';
    $form .= '<input type= "hidden" name= "quantity" value="'.$quantity.'">';
    $form .= '<input type= "hidden" name= "item_name" value="'.$desc.'">';
    $form .= '<input type= "hidden" name= "amount" value="'.$cost.'"> ';
    $form .= '<input type= "hidden" name= "currency_code" value="'.$curr.'">';
    $form .= '<input type= "hidden" name= "return" value="'.$return_page_url.'">';

    if ($taxRate > 0) {
      $form .= ' <input type="hidden" name="tax_rate" value="'.$taxRate.' ">';
    }

    if ($utf8 == "true") {
      $form .= ' <input type="hidden" name="charset" value="utf-8">';
    }

    $form .= '</form>';

    return $form;
  }


  public static function getBookingsPageForEvent($id) {
    global $wpdb;

    $date_id = -1;

    $settings = $wpdb->get_row( "SELECT * FROM " . EbpDatabase::getTableName("settings")." where id=1 ");

    $bookingDates = $wpdb->get_results( "SELECT * FROM " . EbpDatabase::getTableName("eventDates")." where event= '$id' order by start_date asc");

    $html = '<div>';

    $html .= '<input name= "ajaxlink" value="'.site_url().'" type= "hidden"  />';
    $html .= '<input name= "taxRate" value="' . $settings->tax_rate . '" type= "hidden"  />';
      $html .= '<div style= "margin:10px 0px; border-bottom:1px solid #eee; padding-bottom:10px;">';
          $html .= '<span style= "display:inline-block;  line-height:40px;  margin-right:15px; font-weight:700;">Bookings for date: </span>';

        $selHtml = '<select id= "ticketDateIDSELECT" class= "ticketDate">';
          $first = 0;
          foreach ($bookingDates as $dateRow) {
              $first++;
              $date = new DateTime($dateRow->start_date);
              $startDate = $date->format($settings->dateFormat);
              $startTime = date($settings->timeFormat, strtotime($dateRow->start_time));
              if ($first == 1) {
                $selHtml .= '<option value="'.$dateRow->id.'" selected data-startTime="'.$startTime.'">'.$startDate.' @ '.$startTime.'</option>';
                $date_id = $dateRow->id;
              } else {
                $selHtml .= '<option value="'.$dateRow->id.'" data-startTime="'.$startTime.'">'.$startDate.' @ '.$startTime.'</option>';
              }
          }

          $selHtml .= '</select>';

          if ($first > 1) {
            $html .= $selHtml;
          } else if ($first == 1) {
            $html .= '<div style= "font-size:14px; margin-left:15px; display:inline-block; line-height:40px;">'.$startDate.'</div>';
          } else {
            $html .= '<input type= "hidden" id= "ticketDate" value= "-1" />';
          }

          $html .= '<a href= "#" id= "addNewBooking" class= "btn btn-primary">Add Booking</a>';

      $html .= '</div>';

      $html .= '<select style= "display:none" id= "availabeTickets">';

      $eventTickets = $wpdb->get_results( "SELECT * FROM " . EbpDatabase::getTableName("tickets")." where event='$id' order by id asc");

      foreach ($eventTickets as $ticketData) {
        $html .= '<option value="'.$ticketData->id.'" data-cost="'.$ticketData->cost.'" >'.$ticketData->name.'</option>';
      }

      $html .= '</select>';

      $html .= '<select style= "display:none" id="availabeCoupons">';
        $html .= '<option value="" data-cost="0"  data-type="" selected="selected">----Select coupon---</option>';
      $couponResults = $wpdb->get_results( "SELECT * FROM " . EbpDatabase::getTableName("coupons"));
        foreach( $couponResults as $result_c ) {
          $c_id = $result_c->id;

          $isAvilable = $wpdb->get_var( "select COUNT(*) from ". EbpDatabase::getTableName("eventCoupons")." where event='$id' and coupon='$c_id'");

          if ($isAvilable > 0) {
            $html .= '<option value="'.$result_c->name.'" data-amount="'.$result_c->amount.'"  data-type="'.$result_c->type.'" >'.$result_c->name.'</option>';
          }
        }

      $html .= '</select>';

      $html .= '<div class= "alert alert-warning noRecords" style= "margin-top:20px;">No Bookings Yet!</div>';

      $html .= '<div class= "bookings">'.self::getBookingForDate($date_id).'</div>';
      $html .= '<div style="overflow: hidden; margin-bottom: 20px;"><em class="right">Tip: Scroll left/right: You can edit/delete bookings.</em></div>';

      if (!AddOnManager::usesAnalyticsAddOn()) {
        $html .= '<div class="alert alert-info">';
          $html .='<a href="http://iplusstd.com/item/eventBookingPro/buyAllBookings.php">';
          $html .= 'Check "Analytics and Check-in Addon" for more advanced features.';
          $html .= '</a>';
        $html .= '</div>';
      }

    $html .= '</div>';

    return $html;
  }

  public static function detailsPrettyPrint($str) {
    if ($str == '') return $str;

    $json = (object) json_decode(stripslashes($str), true);

    $html = '<div class="EBP--BookingDetails">';
    if (property_exists($json, 'general')) {
      if (property_exists($json, 'breakdown') && sizeof($json->breakdown) > 1) {
        $html .= '<div><strong>Quantity breakdown:</strong> </div>';
        foreach ($json->breakdown as $breakdown) {
          if (intval($breakdown['quantity'] > 0)) {
           $html .= '<div style="margin-left: 10px;"><strong>'.$breakdown['type']. ': </strong> <span>'.$breakdown['quantity'].'</span></div>';
          }
        }
        $html .= '<br/>';
      }

      if (sizeof($json->general) > 0) {
        $html .= '<div><strong>General:</strong> </div>';
        foreach ($json->general as $key => $value) {
          $html .= '<div style="margin-left: 10px;"><strong>'.$key. ':</strong> <span>'.$value.'</span></div>';
        }
        $html .= '<br/>';
      }

      foreach ($json->subTickets as $subticket) {
         $html .= '<div style="margin-bottom:10px;"><strong>'.$subticket['subticketinfo'].':</strong>';
         foreach ($subticket as $key => $value) {
          if ($key == 'subticketinfo' || $key == 'subtickettype') {
            continue;
          }
          if ($key == 'subtickettype') {
            $key = 'Sub ticket type';
          }

          $html .= '<div style="margin-left: 10px;"><strong>'.$key. ':</strong> <span>'.$value.'</span></div>';
        }
        $html .= '</div>';
      }
      $html .= '</div>';
    } else {
      $html .= rtrim(str_replace("%","<br/>", $str), ",");
    }
    $html .= '</div>';

    return $html;
  }

  public static function getBookingForDate ($date_id) {
    global $wpdb;
    $results = $wpdb->get_results("SELECT * FROM " . EbpDatabase::getTableName("payments")." where date_id= '$date_id' order by id desc");

    $event_id = -1;

    foreach ($results as $result) {
      $event_id = $result->event_id;
      break;
    }

    $settings = $wpdb->get_row( "SELECT currency, currencyBefore, priceDecimalCount, priceDecPoint, priceThousandsSep FROM " . EbpDatabase::getTableName("settings")." where id=1 ");

    $curSymbol = EventBookingHelpers::getSymbol($settings->currency);

    $data = '<input type= "hidden" name= "data_id" value="'.$date_id.'" />';

    $data .= ' <table class= "table table-hover table-striped" style= "text-align:center;">';
      $data .= '<thead>';
        $data .= '<th >ID</th>';
        $data .= '<th >Ticket</th>';
        $data .= '<th >Type</th>';
        $data .= '<th >Coupon</th>';
        $data .= '<th >Quantity</th>';
        $data .= '<th >Name</th>';
        $data .= '<th >Email</th>';
        $data .= '<th >Details</th>';
        $data .= '<th >Amount</th>';
        $data .= '<th >Tax Rate</th>';
        $data .= '<th >Amount Taxed</th>';
        $data .= '<th >Date</th>';
        $data .= '<th >Payment ID</th>';
        $data .= '<th >Status</th>';
        $data .= '<th >Edit</th>';
        $data .= '<th >Remove</th>';
        $data .= '<th >Resend email</th>';
      $data .= '</thead>';
    $data .= '<tbody>';

    foreach ($results as $result) {
      $extraClass = ($result->status == BookingStatus::PENDING) ? 'class= "warning"' : '';

      $ticketID = $result->ticket_id;
      $ticket = $wpdb->get_row("SELECT * FROM " . EbpDatabase::getTableName("tickets")." where id= '$ticketID'");
      $ticketName = ($ticket != NULL) ? stripslashes($ticket->name) : '';

      $data .= '<tr '.$extraClass.'>';
      $data .= '<td >'.$result->id.'</td>';
      $data .= '<td >'.$ticketName.'</td>';
      $data .= '<td >'.$result->type.'</td>';
      $data .= '<td >'.$result->coupon.'</td>';
      $data .= '<td >'.$result->quantity.'</td>';
      $data .= '<td >'.$result->name.'</td>';
      $data .= '<td >'.$result->email.'</td>';

      $data .= '<td class= "detailsCell" >'.EbpBooking::detailsPrettyPrint($result->extras).'</td>';


      $data .= '<td >'.EventBookingHelpers::currencyPricingFormat($result->amount,
        $curSymbol, $settings->currencyBefore, $settings->priceDecimalCount,
        $settings->priceDecPoint, $settings->priceThousandsSep, '%cost%').'</td>';

      $data .= '<td >'.$result->tax_rate.'</td>';
      $data .= '<td >'.EventBookingHelpers::currencyPricingFormat($result->amount_taxed,
        $curSymbol, $settings->currencyBefore, $settings->priceDecimalCount,
        $settings->priceDecPoint, $settings->priceThousandsSep, '%cost%').'</td>';

      $data .= '<td >'.$result->date_paid.'</td>';
      $data .= '<td >'.$result->txn_id.'</td>';

      if ($result->status == BookingStatus::NOT_PAID) {
        $data .= '<td ><a href= "#" class= "markPaid" data-id="'.$result->id.'" >'.$result->status.'</a></td>';
      } else {
        $data .= '<td >'.$result->status.'</td>';
      }

      $data .= '<td style= "text-align:center;" class="dontInclude"><a href= "#" class= "editBooking"></a></td>';

      $data .= '<td style= "text-align:center;" class="dontInclude"><a href= "#" class= "deleteBooking">x</a></td>';

      $data .= '<td style= "text-align:center;" class="dontInclude"><a href= "#" class= "resendEmail">Resend email</a></td>';

      $data .= '</tr>';
    }

    $data .= '</tbody></table>';
    return $data;
  }


  public static function deleteBooking($bookingId) {
    global $wpdb;
    $wpdb->delete(EbpDatabase::getTableName("payments"), array('id'=> $bookingId), array('%d'));
    $wpdb->delete(EbpDatabase::getTableName("couponsUsed"), array('payment'=> $bookingId ), array('%d'));

    if (AddOnManager::usesEmailRules()) {
      eventBookingProEmailsClass::deleteRulesForBookingId($bookingId);
    }

    return $bookingId;
  }

  public static function updateBookingStatus($id) {
    global $wpdb;

    $newStatus = BookingStatus::PAID;
    $wpdb->update(EbpDatabase::getTableName("payments"), array("status"=> $newStatus) , array( 'id'=> $id));

    return $newStatus;
  }

  public static function editBooking_old($id, $data) {
    global $wpdb;
    $table_name  = EbpDatabase::getTableName("payments");

    $came = ($data[13]) ? $data[13] : 'false';

    $fields = array(
      'ticket_id'=> $data[2],
      'type'=> $data[3],
      'coupon'=> $data[4],
      'quantity'=> $data[5],
      'name'=> $data[6],
      'email'=> $data[7],
      'extras'=> str_replace("<br/>", "%", $data[8]),
      'amount'=> preg_replace('/\s+/','',str_replace("$", "", $data[9])),
      'tax_rate'=> 0,
      'amount_taxed'=> 0,
      'date_paid'=> $data[10],
      'txn_id'=> $data[11],
      'status'=> $data[12],
      'came' => $came
    );

    if ($id != "-1") {
      $wpdb->update($table_name, $fields, array('id'=> $id));
    } else {
      $fields['event_id'] = $data[0];
      $fields['date_id'] = $data[1];

      $wpdb->insert($table_name, $fields);

      $id = $wpdb->insert_id;
    }

     // handle emails
    if (AddOnManager::usesEmailRules()) {
      eventBookingProEmailsClass::applyRules($data[0], $data[1], $id);
    }

    return $id;
  }

  public static function editBooking($id, $data) {
    global $wpdb;
    $table_name  = EbpDatabase::getTableName("payments");

    $came = ($data[15]) ? $data[15] : 'false';

    $fields = array(
      'ticket_id'=> $data[2],
      'type'=> $data[3],
      'coupon'=> $data[4],
      'quantity'=> $data[5],
      'name'=> $data[6],
      'email'=> $data[7],
      'extras'=> str_replace("<br/>","%",$data[8]),
      'amount'=> preg_replace('/\s+/','',str_replace("$", "", $data[9])),
      'tax_rate'=>$data[10],
      'amount_taxed'=> preg_replace('/\s+/','',str_replace("$", "", $data[11])),
      'date_paid'=> $data[12],
      'txn_id'=> $data[13],
      'status'=> $data[14],
      'came' => $came
    );

    if ($id != "-1") {
      $wpdb->update($table_name, $fields, array('id'=> $id));

    } else {
      $fields['event_id'] = $data[0];
      $fields['date_id'] = $data[1];

      $wpdb->insert($table_name,$fields);

      $id = $wpdb->insert_id;
    }


     // handle emails
    if (AddOnManager::usesEmailRules()) {
      eventBookingProEmailsClass::applyRules($data[0], $data[1], $id);
    }

    return $id;
  }

  public static function getSpotsBookedForEvent($eventId, $dateId, $spotsLeftStrict, $statusesStr) {
    global $wpdb;
    $bookingsAll = $wpdb->get_results("SELECT id, quantity, status FROM " . EbpDatabase::getTableName("payments")." where date_id='$dateId' and event_id='$eventId'");
    $spotsBookedAll = 0;
    $statusesStr = strtolower($statusesStr);
    $statuses = array_map("trim", explode(',', $statusesStr));

    foreach($bookingsAll as $bookingA) {
      if ($spotsLeftStrict == "false") {
         $spotsBookedAll += intval($bookingA->quantity);
      } else {
        if (in_array(strtolower($bookingA->status), $statuses) ) {
          $spotsBookedAll += intval($bookingA->quantity);
        }
      }
    }

    return $spotsBookedAll;
  }

}
?>
