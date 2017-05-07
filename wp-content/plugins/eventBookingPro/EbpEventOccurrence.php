<?php
require_once dirname( __FILE__ ) . '/include.php';

class EbpEventOccurrence {
  public static function getTablesSQL() {
    //event Dates table
    $occurTable = EbpDatabase::getTableName("eventDates");
    $occurTableSQL = "CREATE TABLE " . $occurTable ." (
            id INT NOT NULL AUTO_INCREMENT ,
            event INT NOT NULL,
            start_date DATE NOT NULL,
            end_date DATE NOT NULL,
            start_time time NOT NULL,
            end_time time NOT NULL,
            bookingDirectly VARCHAR(6) default 'true',
            startBooking_date DATE NOT NULL,
            startBooking_time time,
            bookingEndsWithEvent VARCHAR(6) default 'true',
            endBooking_date DATE NOT NULL,
            endBooking_time time,
            PRIMARY KEY (id)
          );";

    return array($eventsSQL, $occurTableSQL);
  }

  public static function getEventOccurence($id) {
    global $wpdb;
    $table_name = EbpDatabase::getTableName("eventDates");
    $results = $wpdb->get_results("SELECT id, start_date, start_time, end_date, end_time, bookingDirectly, startBooking_date, startBooking_time, bookingEndsWithEvent, endBooking_date, endBooking_time FROM " . $table_name." where event= '$id' order by start_date asc, start_time asc");

    return $results;
  }

  public static function addEventOccurence($eventId, $data) {
    global $wpdb;
    $eventDatesTable = EbpDatabase::getTableName("eventDates");

    $occurrenceId = $data->id;

    $date_s = new DateTime($data->start_date);
    $startDate = $date_s->format('Y-m-d');

    $date_e = new DateTime($data->end_date);
    $endDate = $date_e->format('Y-m-d');

    $start_time = $data->start_time;
    $end_time = $data->end_time;

    $bookingDirectly = $data->bookingDirectly;
    $startBooking_date = $data->startBooking_date;
    $startBooking_time = $data->startBooking_time;

    $bookingEndsWithEvent = $data->bookingEndsWithEvent;
    $endBooking_date = $data->endBooking_date;
    $endBooking_time = $data->endBooking_time;

    $sqlData = array(
      'event' => $eventId,
      'start_date'=> $startDate,
      'start_time'=> $start_time,
      'end_date'=> $endDate,
      'end_time'=> $end_time,
      'bookingDirectly'=> $bookingDirectly,
      'startBooking_date'=>$startBooking_date,
      'startBooking_time'=>$startBooking_time,
      'bookingEndsWithEvent'=> $bookingEndsWithEvent,
      'endBooking_date'=>$endBooking_date,
      'endBooking_time'=>$endBooking_time
    );

    if ($occurrenceId != "new") {
      $wpdb->update($eventDatesTable, $sqlData, array("id"=> $occurrenceId));
    } else {
      $wpdb->insert($eventDatesTable, $sqlData);
      $occurrenceId = $wpdb->insert_id;
    }

    return $occurrenceId;
  }

  public static function deleteOccurence($id, $settings) {
    global $wpdb;

    if ($settings == null) {
      $settings = $wpdb->get_row("SELECT emailOccurenceDeleted, emailOccurenceCanceledTemplate FROM " . EbpDatabase::getTableName("settings")." where id='1'");
    }


    // delete payments related to occurrence
    if ($settings->emailOccurenceDeleted == 'true' && AddOnManager::usesEmailRules()) {
      $payments = $wpdb->get_results("SELECT id FROM " . EbpDatabase::getTableName("payments") ." where date_id='$id'");

      foreach ($payments as $payment) {
        EmailService::sendCustomEmail($payment->id, $settings->emailOccurenceCanceledTemplate);
        $wpdb->delete(EbpDatabase::getTableName("payments"), array('id'=> $payment->id));
      }
    }

    $wpdb->delete(EbpDatabase::getTableName("eventDates"), array('id'=> $id));

  }


  // Functions

  public static function occurenceHasStarted ($occur) {
    if (!$occur)  return false;

    $today = intval(current_time('Ymd'));
    $currentTime = strtotime(current_time("H:i:s"));

    $eventStartDateObj = new DateTime($occur->start_date);
    $eventStartDate = intval($eventStartDateObj->format('Ymd'));
    $eventStartTime = strtotime($occur->start_time);

    // previous days
    if ($today > $eventStartDate) return true;

    // same day but passed time
    if ($today == $eventStartDate && $currentTime > $eventStartTime) return true;

    return false;
  }

  public static function occurenceHasEnded ($occur) {
    if (!$occur)  return false;

    $today = intval(current_time('Ymd'));
    $currentTime = strtotime(current_time("H:i:s"));

    $eventEndDateObj = new DateTime($occur->end_date);
    $eventEndDate = intval($eventEndDateObj->format('Ymd'));
    $eventEndTime = strtotime($occur->end_time);

    // previous days
    if ($today > $eventEndDate) return true;

    // same day but passed time
    if ($today == $eventEndDate && $currentTime > $eventEndTime) return true;

    return false;
  }

  // 0: open
  // 1: will open
  // 2: ended
  // 3: invalid
  public static function bookingOpen($occur) {
    if (!$occur) return 3;

    $startsDirectly = $occur->bookingDirectly == 'true';
    $endsWhenEventStarts = $occur->bookingEndsWithEvent == 'true';

    $today = intval(current_time('Ymd'));
    $currentTime = intval(str_replace(":", "", current_time("H:i:s")));

    if ($startsDirectly) {
      $bookingStart_date = '1990-01-01';
      $bookingStartTime = 0;
    } else {
      $bookingStart_date = $occur->startBooking_date;
      $bookingStartTime = intval(str_replace(":", "", $occur->startBooking_time));
    }

    $bookingStartDateObj = new DateTime($bookingStart_date);
    $bookingStartDate = intval($bookingStartDateObj->format('Ymd'));


    if ($endsWhenEventStarts) {
      $bookingEnds_date = $occur->start_date;
      $bookingEnds_time = $occur->start_time;
    } else {
      $bookingEnds_date = $occur->endBooking_date;
      $bookingEnds_time = $occur->endBooking_time;
    }

    $bookingEndDateObj = new DateTime($bookingEnds_date);
    $bookingEndDate = intval($bookingEndDateObj->format('Ymd'));
    $bookingEndTime = intval(str_replace(":", "", $bookingEnds_time));


    if ($today < $bookingStartDate || $today == $bookingStartDate && $currentTime < $bookingStartTime) {
      return 1;
    }

    if ($today > $bookingEndDate || $today == $bookingEndDate && $currentTime > $bookingEndTime) {
      return 2;
    }

    return 0;
  }


  // occurrence can still be booked
  public static function occurrenceClosed($occur, $willOpen = true) {
    $bookingOpenStatus = ($willOpen) ? 1 : 0;

    // if still can book, then occurrence hasn't passed
    $occurStarted = EbpEventOccurrence::occurenceHasStarted($occur);
    $occurEnded = EbpEventOccurrence::occurenceHasEnded($occur);
    $bookingOpen = EbpEventOccurrence::bookingOpen($occur) <= $bookingOpenStatus;

    $isClosed = true;
    if (!$occurStarted && $bookingOpen ) $isClosed = false;
    if (!$occurEnded && $bookingOpen ) $isClosed = false;

    return $isClosed;
  }


  // get event Dates from db as array of (passed, upcoming)
  public static function getEventDatesAsPassedUpcoming($id) {
    global $wpdb;
    $today = current_time('Y-m-d');


    $passedDates = array();
    $upcomingDates = array();
    $results =  $wpdb->get_results( "SELECT * FROM " . EbpDatabase::getTableName("eventDates")." where event='$id' order by start_date asc, start_time asc");

    foreach ($results as $occur) {
      if (EbpEventOccurrence::occurenceHasEnded($occur)) {
        array_push($passedDates, $occur);
      } else {
        array_push($upcomingDates, $occur);
      }
    }

    return array($passedDates, $upcomingDates);
  }

  public static function getOpenUpcomingEventDates($id) {
    global $wpdb;
    $today = current_time('Y-m-d');

    $upcomingDates = array();
    $results =  $wpdb->get_results( "SELECT * FROM " . EbpDatabase::getTableName("eventDates")." where event='$id' order by start_date asc, start_time asc");

    foreach ($results as $occur) {
      if (EbpEventOccurrence::bookingOpen($occur) == 0) {
        array_push($upcomingDates, $occur);
      }
    }

    return $upcomingDates;
  }

  public static function getEventDateMarkUp($data, $dateId, $settingsOption, $upcomingDates, $passedDates) {
    $id = (property_exists($data, 'event')) ? $data->event : $data->id;

    $moreDatesContentObject = EbpEventOccurrence::getMoreDatesContentObject($dateId, $upcomingDates, $passedDates);

    $occurrence = $moreDatesContentObject['occurrence'];
    $hasDates = $moreDatesContentObject['hasDates'];
    $occurrenceId = $moreDatesContentObject['occurrenceId'];

    $date = new DateTime($occurrence->start_date);
    $html = '<div class="dateDetails" style="'.$settingsOption["date"].'">';
    $html .= '<input name="ebpMobilegPage" value="'.get_page_link(get_option('ebp_page_id')).'" type= "hidden" />';

    $eventNotActive = $data->eventStatus != 'active';
    $html .= EbpEventOccurrence::getDateMarkUp($settingsOption['settings'], false, $occurrence, $eventNotActive);


    if (($hasDates & $settingsOption["moreDatesOn"] == "true") ||
      ($settingsOption["moreDatesOn"] == "true" && $settingsOption["settings"]->permenantMoreButton == "true")) {

      $html .= '<div class="moreDates">';
        $html .= '<a class="ebp-trigger isMoreDate" data-seperatePage="'.$settingsOption["settings"]->mobileSeperatePage.'" data-id="'.$id.'">'.$settingsOption["moreDateTxt"].'</a>';
      $html .= '</div>';
    }

    $html .= '</div>';

    return array(
      'dateID'=>$occurrenceId,
      'html'=>$html,
      'date'=>$date,
      'start_time'=>$occurrence->start_time,
      'occurrence'=>$occurrence
    );
  }


  public static function getMoreDatesContentObject($dateId, $upcomingDates, $passedDates) {
    $selectedOccurrenceId =  $dateId;

    // extract passed dates
    $p = 0;
    if ($passedDates != NULL) {
      foreach($passedDates as $dateRow) {
        $p++;
        if (($p == 1 & $dateId < 0) || (intval($dateId) ==  intval($dateRow->id)) ) {
          $occurrence = $dateRow;
          $selectedOccurrenceId = $dateRow->id;
        }
      }
    }

    // extract upcoming dates
    $i = 0;
    if ($upcomingDates != NULL) {
      foreach($upcomingDates as $dateRow) {
        $i++;
        if (($i == 1 & $dateId < 0) || (intval($dateId) ==  intval($dateRow->id)) ) {
          $occurrence = $dateRow;
          $selectedOccurrenceId = $dateRow->id;
        }
      }

    }

    $hasDates = $i + $p > 1;

    if (intval($dateId) > -1) $selectedOccurrenceId = $dateId;

    return  array(
      'occurrence' => $occurrence,
      'hasDates' => $hasDates,
      'occurrenceId' => $selectedOccurrenceId
    );
  }



  public static function getDateMarkUp($settings, $forceBoth, $dateObj, $eventNotActive=false, $modal=false, $bookBtn=false, $id = -1, $dateId = -1, $toOpenDate = -1) {

    $start_date = $dateObj->start_date;
    $end_date = $dateObj->end_date;

    $start_time = $dateObj->start_time;
    $end_time = $dateObj->end_time;

    $date_format = EventBookingHelpers::convertDateFormat($settings->dateFormat);
    $time_format = $settings->timeFormat;

    $dateLanguaged_start = utf8_encode(strftime($date_format, strtotime($start_date)));
    $dateLanguaged_end = utf8_encode(strftime($date_format, strtotime($end_date)));

    $startTime = date($time_format, strtotime($start_time));
    $endTime = date($time_format, strtotime($end_time));


    $html = '<div class="dateCnt">';
      $html .= '<div class="dates">';
        $html .= '<div class="dateWrap">';
          if ($settings->includeEndsOn == "true" || $forceBoth)
            $html .= '<div class="datelabel">'.$settings->statsOnTxt.'</div>';
          $html .= '<div class="eventDate">'.$dateLanguaged_start.'</div>';

          $html .= '<div class="time">'.$startTime.'</div>';

        $html .= '</div>';

        if ($settings->includeEndsOn == "true" || $forceBoth) {
          $html .= '<div class="dateWrap" style="margin-top:'.$settings->datePaddingBottom.'px;"><div class="datelabel">'.$settings->endsOnTxt.'</div>';
            $html .= '<div class="eventDate" >'.$dateLanguaged_end.'</div>';
            $html .= '<div class="time" >'.$endTime.'</div>';

          $html .= '</div>';
        }

        $html .= '</div>';

        if ($bookBtn && $id > -1) {
          $html .= '<div class="btns">';
            //check if booked
            global $wpdb;
            $eventTickets = $wpdb->get_results("SELECT id FROM " . EbpDatabase::getTableName("tickets")." where event= '$id'");

            $hasLeft = false;
            foreach($eventTickets as $ticketInfo) {
             if (intval(Event::checkSpots($toOpenDate, $ticketInfo->id)) > 0 ) {
                $hasLeft = true;
                break;
             }
            }

            if ($hasLeft) {
              // check if booking open
              $bookingStatus = EbpEventOccurrence::bookingOpen($dateObj);

              if ($eventNotActive) {
                $html .= '<cite class="small">'.$settings->eventCancelledTxt.'</cite>';
              } else if ($bookingStatus == 0) {
                $modalLink = $id.$dateId;
                $txt = $settings->btnTxt;
                $html .= '<a href="#" data-seperatePage="'.$settings->mobileSeperatePage.'"  class="Modal--directDateBook" data-modal="offlineBooking'.$modalLink.'" data-id="'.$id.'" data-dateid="'.$dateId.'" data-to-open="'.$toOpenDate.'">'.$txt.'</a>';
              } else if ($bookingStatus == 1) {
                $startDate = utf8_encode(strftime($date_format, strtotime($dateObj->startBooking_date)));
                $startTime = date($time_format, strtotime($dateObj->startBooking_time));


                $html .= '<cite class="small">'.str_replace(array('%date%', '%time%'), array($startDate, $startTime), $settings->bookingStartsTxts).'</cite>';

              } else {
                $html .= '<cite class="small">'.$settings->bookingEndedTxt.'</cite>';
              }


            } else {
              $html .= '<div class="allBooked">'.$settings->bookedTxt.'</div>';
            }

          $html .= '</div>';
        }

    $html .= '</div>';
    return $html;
  }

}
