<?php
require_once dirname( __FILE__ ) . '/include.php';

class EbpEventButton {

  public static function getEventButtonHTML($id, $date_id, $include_price, $content) {
    global $wpdb;

    if ($date_id != -1) {
      $isAvilable = $wpdb->get_var("SELECT count(*) FROM " . EbpDatabase::getTableName("eventDates")." where id='$date_id' && event='$id' ");
      if ($isAvilable == 0) {
        return '<div class="eventNotFound" style="width:auto;">Event Occurrence not found or deleted</div>';
      }
    };

    $settingsOption = EventBookingHelpers::getStyling(NULL,1);
    $btnStyling = $settingsOption["btn"];

    $result = $wpdb->get_row("SELECT * FROM " . EbpDatabase::getTableName("events")." where id= '$id'");

    $curSymbol = EventBookingHelpers::getSymbol($settingsOption["currency"]);


    if ($result != NULL) {
      //get cost
      $eventTickets = $wpdb->get_results("SELECT * FROM " . EbpDatabase::getTableName("tickets")." where event= '$id' order by id asc");

      $i = 0;
      foreach($eventTickets as $eventData) {
        if ($i == 0) {
          $ticketID = $eventData->id;
          $cost = $eventData->cost;
          break;
        }
      }

      $isBooked = true;
      $today = current_time('Y-m-d');

      $p = 0;
      $dateId = $date_id;
      if ($date_id < 0) {
        $upcomingDates = $wpdb->get_results("SELECT * FROM " . EbpDatabase::getTableName("eventDates")." where event='$id' order by start_date asc");
        foreach($upcomingDates as $dateRow) {
          if (EbpEventOccurrence::occurrenceClosed($dateRow, true)) continue;

          $p++;
          if ($p == 1) {
            $eventDateObj = $dateRow;
            $dateId = $dateRow->id;
          }

          //get booked
          foreach($eventTickets  as $ticketInfo) {
            if (Event::checkSpots($dateRow->id,$ticketInfo->id)>0) {
              $isBooked=false;
              break;
            }
          }
        }
        if ($p == 0) $eventDateObj = false;
      } else {
        $eventDateResult = $wpdb->get_row("SELECT * FROM " . EbpDatabase::getTableName("eventDates")." where id= '$date_id'");
        $eventDateObj = $eventDateResult;
        $dateId = $date_id;
        foreach($eventTickets  as $ticketInfo) {
          $p ++;
          if (Event::checkSpots($date_id,$ticketInfo->id) > 0) {
            $isBooked = false;
            break;
          }
        }
      }

      $today = current_time('Y-m-d');

      if ($content == NULL) {
        $txt = $result->name;
      } else {
        $txt = $content;
      }

      $active = true;
      $price = '';

      if (EbpEventOccurrence::occurrenceClosed($eventDateObj, true) || $p == 0) {
        $buttonText = EbpEventOccurrence::occurenceHasStarted($eventDateObj) ? $settingsOption["passedTxt"] :  $settingsOption['settings']->bookingEndedTxt;
        $price = ' <span> - '.$buttonText.'</span>';
        $active = false;
      } else if ($isBooked) {
        $price = ' <span> - '.$settingsOption["bookedTxt"].'</span>';
        $active = false;
      } else {
        // will show button.
        if ($include_price == "true") {
          $price = ' <span>(';

          $price .= EventBookingHelpers::currencyPricingFormat($cost, $curSymbol,
            $settingsOption["settings"]->currencyBefore, $settingsOption["settings"]->priceDecimalCount,
            $settingsOption["settings"]->priceDecPoint,$settingsOption["settings"]->priceThousandsSep);

          $price .=')</span>';
        }
      }

      $html = '<div class="eventBtnCnt">';
      $html .= '<input name= "ajaxlink" value="'.site_url().'" type= "hidden"  />';
      $html .= '<div class="eb_frontend">';


      $bookingBtn = Event::getModalBtn($id, $eventDateObj, $dateId, $dateId, $settingsOption["settings"]->mobileSeperatePage, $active, $btnStyling,
        $txt.$price, $settingsOption["settings"]->bookingStartsTxts, $settingsOption["settings"]->bookingEndedTxt, $settingsOption["dateFormat"], $settingsOption["timeFormat"]);
      $html .= $bookingBtn['html'] . $bookingBtn['modal'];

      $html .= '</div>';
      $html .= '</div>';

    } else {
       $html = '<div class="eventNotFound">Event Not Found!</div>';
    }

    return $html;
  }
}
