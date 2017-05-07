<?php
require_once dirname( __FILE__ ) . '/include.php';

class EbpEventsList {

  public static function getEventsListHTML($events, $order, $type, $categories, $limit, $width, $months, $nextdays, $filter, $show_occurences_as_seperate) {
    $html = '<input name= "ajaxlink" value="'.site_url().'" type= "hidden"  />';
    $html .= '<div class="Ebp--EventsList Ebp--NotInited" data-events="'.$events.'" data-order="'.$order.'" data-type="'.$type.'" data-categories="'.$categories.'" data-limit="'.$limit.'" data-width="'.$width.'" data-months="'.$months.'" data-nextdays="'.$nextdays.'" data-filter="'.$filter.'" data-show_occurences_as_seperate="'.$show_occurences_as_seperate.'"></div>';

    return $html;
  }

  public static function getEventsListData($events, $order, $type, $categories, $limit, $width, $months, $nextdays, $filter, $show_occurences_as_seperate) {
    global $wpdb;

    $settingsOption = EventBookingHelpers::getStyling($width, 1);

    $eventsListHtml = self::getEventListData($settingsOption, $events, $order, $type, $categories, $limit, $width, $months, $nextdays, $show_occurences_as_seperate);

    $finalHtml = '';

    if ($eventsListHtml == '') {
      $finalHtml = $settingsOption["settings"]->NoEventsInList;
    } else {
      $finalHtml = '<div class="eventsGroup">';
        if ($filter == 'on') {
          $finalHtml .= self::getUsedCategoriesAsFilters($categories, $settingsOption["settings"]);
        }

        $finalHtml .= $eventsListHtml;
    }

    return $finalHtml;
  }

  public static function getUsedCategoriesAsFilters($categories, $settings) {
    $categoriesList = EbpCategories::getUsedCategories($categories);

    $catNames = '';
    foreach ($categoriesList as $category ) {
      $catNames .= '<a class="catFilter" href="#" data-cat-id="ebpCat_'.$category['id'].'">'.$category['name'].'</a>';
    }

    if ($catNames != '') {
      $catNames = '<a class="catFilter" href="#" data-cat-id="ebpCat_all">'.$settings->eventsListFilterLable.'</a>'.$catNames;
      $catNames = '<div class="filterTags">'.$catNames.'</div>';
    }

    return $catNames;
  }

  public static function getEventListData ($settingsOption, $events, $order, $type, $categories, $limit, $width,
   $months, $nextdays, $showOccurencesAsSeperate) {
    global $wpdb;
    $finalHtml = '';

    $COND = '';
    switch ($events) {
      case 'passed':
        $COND = 'where start_date < CURDATE() or (start_date = CURDATE() and start_time <= CURTIME())';
        break;
      case 'all':
        $COND = '';
        break;
      default:
        $COND = 'where o.start_date > CURDATE() or (o.start_date = CURDATE() and o.start_time > CURTIME())';
        break;
    }

    $results = $wpdb->get_results("SELECT *, o.id as date_id FROM ".EbpDatabase::getTableName("events")." as e right OUTER join ".EbpDatabase::getTableName("eventDates")."  as o on e.id = o.event ".$COND." order by o.start_date ".$order.", o.start_time  ".$order);

    $addedEvents = array();

    foreach ($results as $result) {
      $id = $result->event;

      if ($showOccurencesAsSeperate == "off" && in_array($id, $addedEvents)) {
        continue;
      }

      if (sizeof($addedEvents) >=  $limit) {
        continue;
      }

      $inMonths = false;
      $isNextdays = false;


      $inMonths = $inMonths || Event::eventBelongsToMonths($result->start_date, $months);
      $isNextdays = $isNextdays || Event::eventBelongsToNextDays($result->start_date, $nextdays);


      if (EbpCategories::eventBelongsToCategories($id, $categories) && $inMonths && $isNextdays) {
        array_push($addedEvents, $id);

        if ($type == "card") {
          $htmlTemp = EventCard::getCard($id, $result->date_id, $width);
        } else if ($type == "cardExpand") {
          $htmlTemp = EventCardExtended::getCard($id, $result->date_id, $width);
        } else {
          $htmlTemp = '<div class="eventDisplayCnt" data-init-width="'.$settingsOption['width'].'" style="'.$settingsOption["box"].'">';

            // to do, pass -1 to list
            $markUp = EbpEventBox::getEventMarkUp($id, $result->date_id, $result, $settingsOption);
            $htmlTemp .= $markUp["html"];
            $htmlTemp .= $markUp["modal"];
          $htmlTemp .= '</div>';
        }

        $finalHtml .= $htmlTemp;
      }

    }

    return $finalHtml;
  }

}
