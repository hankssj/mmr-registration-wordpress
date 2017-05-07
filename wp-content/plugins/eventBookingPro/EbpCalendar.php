<?php
require_once dirname( __FILE__ ) . '/include.php';

class EbpCalendar {

  public static function getCalendarTranslation() {
    global $wpdb;

    $settings = $wpdb->get_row( "SELECT cal_weeks, cal_weekabbrs, cal_months, cal_monthabbrs FROM " . EbpDatabase::getTableName("settings")." where id='2'");

    return $settings;
  }

  public static function getCalendarHTML($width, $categories, $height, $loadall, $tooltip, $show_events_directly, $show_spots_left, $display_mode) {
    // get settings
    global $wpdb;
    $settingsOption = EventBookingHelpers::getStyling($width, 1);

    // compaibility
    if ($show_events_directly == 'on' && $display_mode == 'tooltip') {
      $display_mode = 'show_directly';
    }

    // default options
    $calendarModeClass = ($display_mode == 'show_directly' || $display_mode == 'show_spread') ? ' showEventsDirectly' : '';

    $spreadEvents = ($show_events_directly == 'spread') ? ' true' : 'false';
    $calheight = ($height == NULL) ? $settingsOption["settings"]->cal_height : $height;

     $html = '<div class="eventDisplayCnt isCalendar">';
      $html .= '<section class="calInstance '.$calendarModeClass.'" style="'.$settingsOption["calStyle"].'">';
      $html .= '<input name= "ajaxlink" value="'.site_url().'" type= "hidden"  />';
      $html .= '<div class="EBP--CalendarWrap">';

        $calendarHasBoxShadow =  ($settingsOption["settings"]->cal_hasBoxShadow == 'true') ? ' hasBoxShadow' : '';
        $html .= '<div  class="EBP--Inner '. $calendarHasBoxShadow.'" style="'.$settingsOption["calBodyStyle"].'">';
          $html .= '<div class="EBP--Header clearfix" >';
            $html .= '<nav>';
              $html .= '<span class="EBP--Prev"></span>';
              $html .= '<span class="EBP--Next"></span>';
            $html .= '</nav>';
            $html .= '<h2 class="EBP--Month"></h2>';
            $html .= '<h3 class="EBP--Year"></h3>';
          $html .= '</div>';
          $html .= '<div id= "calendar" style="height:'.$calheight.'px" class="EBP--CalendarContainer" data-show-spots-left="'.$show_spots_left.'" data-allLoaded="'.$loadall.'" data-categories="'.$categories.'" data-init-width="'.$settingsOption['width'].'" data-startIn="'.$settingsOption["startIn"].'" data-displayMonthAbbr="'.$settingsOption["displayMonthAbbr"].'" data-displayWeekAbbr="'.$settingsOption["displayWeekAbbr"].'" data-display-mode="'.$show_events_directly.'" data-background="'.$settingsOption["settings"]->calendarImageAsBackground.'" data-displayMode="'.$display_mode.'" data-calHeight="'.$calheight.'">';

            $html .= '<div class="EBP--CalendarBlocker"><div id="calendarLoader"><div id="calendarLoader_1" class="calendarLoader"></div><div id="calendarLoader_2" class="calendarLoader"></div><div id="calendarLoader_3" class="calendarLoader"></div><div id="calendarLoader_4" class="calendarLoader"></div><div id="calendarLoader_5" class="calendarLoader"></div><div id="calendarLoader_6" class="calendarLoader"></div><div id="calendarLoader_7" class="calendarLoader"></div><div id="calendarLoader_8" class="calendarLoader"></div></div></div>';
            $html .= '</div>';
          $html .= '</div>';
        $html .= '</div>';
      $html .= '</section>';
      $html .= '</div>';
      return $html;
  }

  public static function getDayEvents($displayType, $currentDay, $categories, $width) {
    global $wpdb;

    $eventsData = $wpdb->get_results("SELECT *, o.id as date_id FROM ".EbpDatabase::getTableName("events")." as e right OUTER join ".EbpDatabase::getTableName("eventDates")."  as o on e.id = o.event where o.start_date= '$currentDay' order by o.start_time  ");

    $settingsOption = EventBookingHelpers::getStyling($width, 1);

    $eventOrder = array();

    foreach ($eventsData as $eventData ) {
      $id = $eventData->event;
      if (EventBookingHelpers::eventBelongsToCategoreis($id, $categories)) {
        $date = new DateTime($eventData->start_date);
        $eventDate = $date->format('m-d-Y');
        $sortDate = $date->format('Ymd');

        if ($displayType == "card") {
          $data = EventCard::getCard($id, $eventData->date_id, $width);
        } else if ($displayType == "cardExpand") {
          $data = EventCardExtended::getCard($id, $eventData->date_id, $width);
        } else {
          // to do, pass -1 to list
          $markUp = EbpEventBox::getEventMarkUp($id, $eventData->date_id, $eventData, $settingsOption);
          $data = '<div class="eventDisplayCnt"  style="'.$settingsOption["box"].'" data-init-width="'.$width.'">';
          $data .= $markUp["html"];
          $data .= '</div>';
        }

        $eventOrder[$sortDate.$eventData->start_time.$eventData->date_id]= $data;
      }
    }

    ksort($eventOrder);
    $data = '';
    foreach ($eventOrder as $key=> $val) {
      $data .= $val;
    }

    return $data;
  }

  public static function getCalData($POST) {
    global $wpdb;

    $month = sprintf('%02d', $POST['month']);
    $year = sprintf('%04d', $POST['year']);
    $width = intval($POST['width']);
    $categories = $POST['categories'];
    $type = $POST['type'];
    $isWeek = isset($POST['calmode']) && $POST['calmode'] == 'week';
    $showSpotsLeft = (isset($POST['showSpotsLeft']) && $POST['showSpotsLeft'] == 'true');
    $displayMode = (isset($POST['displaymode'])) ? $POST['displaymode'] : 'normal';

    if ($isWeek) {
      $startDay = $POST['startDay'];
      $dareStr = $year . '-' . $month . '-' . $startDay;
      $minDateRange = date($dareStr);
      $maxDateRange = date('Y-m-d', strtotime($dareStr . ' + 7 days'));

      $eventsData = $wpdb->get_results("SELECT *, o.id as date_id FROM ".EbpDatabase::getTableName("events")." as e right OUTER join ".EbpDatabase::getTableName("eventDates")."  as o on e.id = o.event where o.start_date<'$maxDateRange' && o.start_date>= '$minDateRange' order by o.start_date asc, o.start_time asc");
    } else {
      $minDateRange = date($year . '-' . $month . '-01');
      $nextMonth = sprintf('%02d', intval($POST['month']) + 1);
      if ($nextMonth > 12) {
        $nextYear = sprintf('%04d', intval($POST['year']) + 1);
        $maxDateRange = date($nextYear . '-01-01');
      } else {
        $maxDateRange = date($year . '-' . $nextMonth . '-01');
      }

      $eventsData = $wpdb->get_results("SELECT *, o.id as date_id FROM ".EbpDatabase::getTableName("events")." as e right OUTER join ".EbpDatabase::getTableName("eventDates")."  as o on e.id = o.event where o.start_date BETWEEN '$minDateRange' AND'$maxDateRange' order by o.start_date asc, o.start_time asc");
    }

    $data = array();

    $settings = $wpdb->get_row( "SELECT timeFormat, dateFormat, calendarImageAsBackground, spotsLeftTxt FROM " . EbpDatabase::getTableName("settings")." where id='2'");
    $calendatImgAsbackground = $settings->calendarImageAsBackground == 'true';
    $spotsLeftTxt = $settings->spotsLeftTxt;

    $time_format = $settings->timeFormat;
    $date_format = EventBookingHelpers::convertDateFormat($settings->dateFormat);


    foreach ($eventsData as $occur) {
      $id = $occur->event;
      if (EbpCategories::eventBelongsToCategories($id,$categories)) {

        $date = new DateTime($occur->start_date);
        $eventDate = $date->format('m-d-Y');
        $eventObj = array();

        if ($type == 'hasevent') {
          $data[$eventDate] = 'eventname';
        } else {
          $eventObj['name'] = stripcslashes($occur->name);
          $eventObj['dateId'] = $occur->date_id;

          if ($showSpotsLeft) {
            $eventObj['spots'] = Event::getAllSpotsLeft($id, $occur->id) .' '. $spotsLeftTxt;
          }

          $endDate = new DateTime($occur->end_date);
          $eventObj['days'] = intval($endDate->diff($date)->format("%a")) + 1;

          if ($isWeek) {
            $eventObj['startTime'] = date($time_format, strtotime($occur->start_time));
            $eventObj['endTime'] = date($time_format, strtotime($occur->end_time));
            if ($occur->end_date != $occur->start_date) {
              $eventObj['endDate'] = utf8_encode(strftime($date_format, strtotime($occur->end_date)));
            }
          }

          if ($calendatImgAsbackground && $occur->background) {
            $bgImageArr = explode('__and__', $occur->background);
            $occurBackground = $bgImageArr[sizeof($bgImageArr) -1];
            $eventObj['background'] = $occurBackground;
          }

          if (!array_key_exists($eventDate, $data)) {
            $data[$eventDate] = array();
          }
          array_push($data[$eventDate], $eventObj);
        }
      }
    }


    // get events from previous months but span through this month
    if ($displayMode == 'show_spread') {
      $eventsDataPrevious = $wpdb->get_results("SELECT *, o.id as date_id FROM ".EbpDatabase::getTableName("events")." as e right OUTER join ".EbpDatabase::getTableName("eventDates")."  as o on e.id = o.event where (o.end_date BETWEEN '$minDateRange'AND'$maxDateRange') and  o.start_date < '$minDateRange' order by o.start_date asc, o.start_time asc");

      $firstDateOfMonth = new DateTime($year . '-' . $month . '-01');
      foreach ($eventsDataPrevious as $prevEvents) {
        $id = $prevEvents->event;
        if (EbpCategories::eventBelongsToCategories($id,$categories)) {

          $eventDate = $month . '-01'.'-'.$year;
          $eventObj = array();
          $eventObj['name'] = stripcslashes($prevEvents->name);
          $eventObj['dateId'] = $prevEvents->date_id;

          if ($showSpotsLeft) {
            $eventObj['spots'] = Event::getAllSpotsLeft($id, $prevEvents->id) .' '. $spotsLeftTxt;
          }

          $endDate = new DateTime($prevEvents->end_date);
          $eventObj['days'] = intval($endDate->diff($firstDateOfMonth)->format("%a")) + 1;

          $eventObj['fromPreviousWeeks'] = true;

          // no need to get background

          if (!array_key_exists($eventDate, $data)) {
            $data[$eventDate] = array();
          }
          array_push($data[$eventDate], $eventObj);
        }
      }
    }

    if ($type == 'hasevent') {
      return $data;
    } else {
      $returnObj["events"] = $data;

      return $returnObj;
    }
  }

  public static function getCalDayData($dateIdList, $width) {
    global $wpdb;

    $settingsOption = EventBookingHelpers::getStyling($width, 1);

    $results = $wpdb->get_results("SELECT *, o.id as date_id FROM ".EbpDatabase::getTableName("events")." as e right OUTER join ".EbpDatabase::getTableName("eventDates")."  as o on e.id = o.event where o.id in (" . $dateIdList . ")");

    $returnObj['modals'] = '';
    $returnObj['html'] = '';
    foreach ($results as $occur) {
      $id = $occur->event;

      $markUp = self::getCalDayDataReturn($id, $occur->date_id, $occur, $settingsOption);
      $returnObj['modals'] .= $markUp["modal"];
      $returnObj['html'] .= $markUp["html"];
    }

    return $returnObj;
  }

  public static function getCalDayDataReturn($id, $date_id, $occur, $settingsOption) {
    return EbpEventBox::getEventMarkUp($id, $date_id, $occur, $settingsOption, true);
  }

}
