<?php
require_once dirname( __FILE__ ) . '/include.php';

class EBP_FE_Modal {
  CONST FORM_BOOKING = 'FORM_BOOKING';
  CONST FORM_CONFIG = 'FORM_CONFIG';
  CONST FORM_MORE_DATES = 'FORM_MORE_DATES';
  CONST FORM_COMING = 'FORM_COMING';

  public static function getBookingStepPage($vars) {
    if (!isset($vars['step'])) return 'error';

    switch ($vars['step']) {
      case self::FORM_BOOKING:
        $eventId = $vars['eventId'];
        $dateId = $vars['dateId'];
        return self::getBookingForm($eventId, $dateId);

      case self::FORM_MORE_DATES:
        return self::getMoreDatesForm($vars['eventId']);
      break;

      case self::FORM_COMING:
        $eventId = $vars['eventId'];
        $dateId = $vars['dateId'];
        return self::getWhoIsComingForm($eventId, $dateId);

      case self::FORM_CONFIG:
        return self::getFormConfig();
      break;
    }
  }

  // ==============================================================
  // ======================== Form Config  ========================
  // ==============================================================

  private static function getFormConfig() {
    global $wpdb;
    $settings = $wpdb->get_row("SELECT tax_rate, showTaxInBookingForm, currency, currencyBefore, priceDecimalCount, priceDecPoint, priceThousandsSep FROM " . EbpDatabase::getTableName("settings")." where id='1'");
    $settings->currency = EventBookingHelpers::getSymbol($settings->currency);

    return json_encode($settings);
  }


  // ==============================================================
  // ===================== Form Who is Coming =====================
  // ==============================================================

  private static function getWhoIsComingForm($eventId, $dateId){
    $html = '';
    if (AddOnManager::getUsersAddonPath() && EventBookingProUsersClass::viewBooked()) {
      $html .= EventBookingProUsersClass::getWhoBookedContent($eventId, $dateId);
    }

    return $html;
  }


  // ==============================================================
  // ====================== Form More dates =======================
  // ==============================================================

  private static function getMoreDatesForm($eventId) {
    global $wpdb;

    $event = $wpdb->get_row( "SELECT * FROM " . EbpDatabase::getTableName("events")." where id='$eventId' ");
    $eventNotActive = $event->eventStatus != 'active';

    list ($passedDates, $upcomingOccurrence) = EbpEventOccurrence::getEventDatesAsPassedUpcoming($eventId);

    $settings = $wpdb->get_row("SELECT * FROM " . EbpDatabase::getTableName("settings")." where id='1'");

    $moreDatesTxts = self::getMoreDatesHtml($eventId, $settings, $upcomingOccurrence, $passedDates, $eventNotActive);

    $html = '<div class="Modal--Title">'.stripslashes($event->name).'</div>';
    $html .= '<input name= "ebpMobilegPage" value="'.get_page_link(get_option('ebp_page_id')).'" type="hidden"  />';
    $html .= $moreDatesTxts;

    return $html;
  }

  private static function getMoreDatesHtml($id, $settings, $upcomingOccurrence, $passedDates, $eventNotActive) {
    if ($settings->moreDateUpcoming == "false" && $settings->moreDatePassed == "false") return '';
      $today = current_time('Y-m-d');

      // extract passed dates
      $passed = '';
      $passedTemp = '';
      $p = 0;

      if ($passedDates != NULL) {
        $passedTemp = '<div class="Modal--DatesBlock"><div class="Modal--Dates--Title">'.$settings->passedOccurencesText.'</div>';

        foreach($passedDates as $dateRow) {
          $p++;

          if ($p > 1) {
            $passed .= '<div style="display:block; width:100%; height:'.$settings->moreDateSectionMarginBottom.'px;"></div>';
          }

          $passed .= EbpEventOccurrence::getDateMarkUp($settings, false, $dateRow, $eventNotActive, true, true, -2);
        }

        if ($p > 0) {
          $passed = $passedTemp.$passed .'</div>';
        }
      }

      // extract upcoming dates
      $i = 0;
      $upcoming = '';
      $upcomingTemp = '';
      if ($upcomingOccurrence != NULL) {
        $upcomingTemp.='<div class="EBP--DatesBlock"><div class="Modal--Dates--Title">'.$settings->upcomingOccurencesText.'</div>';

        foreach($upcomingOccurrence as $dateRow) {
          $i++;

          if ($i > 1) {
            $upcoming.='<div style="display:block; width:100%; height:'.$settings->moreDateSectionMarginBottom.'px;"></div>';
          }

          $upcoming .= EbpEventOccurrence::getDateMarkUp($settings, false, $dateRow, $eventNotActive, true, true, $id, null, $dateRow->id);
        }

        if ($i > 0) {
          $upcoming = $upcomingTemp.$upcoming.'</div>';
        }
      }

      if ($settings->moreDateUpcoming == "false") $upcoming = '';
      if ($settings->moreDatePassed == "false") $passed = '';

      return $upcoming.$passed;
  }

  // ==============================================================
  // ======================== Form Booking ========================
  // ==============================================================

  private static function getBookingForm($eventId, $inputOccurId) {
    global $wpdb;
    $event = $wpdb->get_row( "SELECT * FROM " . EbpDatabase::getTableName("events")." where id='$eventId'");

    $settings = $wpdb->get_row("SELECT * FROM " . EbpDatabase::getTableName("settings")." where id='1'");
    $settings->curSymbol = EventBookingHelpers::getSymbol($settings->currency);

    switch ($settings->ticketsOrder) {
      case EbpTicketsOrder::NAME_ASCENDING:
        $ticketOrderingSQL = 'order by name asc';
      break;
      case EbpTicketsOrder::NAME_DESCENDING:
        $ticketOrderingSQL = 'order by name desc';
      break;
      case EbpTicketsOrder::COST_ASCENDING:
        $ticketOrderingSQL = 'order by cost asc';
      break;
      case EbpTicketsOrder::COST_DESCENDING:
        $ticketOrderingSQL = 'order by cost desc';
      break;
      default:
      case EbpTicketsOrder::CREATION_ORDER:
        $ticketOrderingSQL = 'order by id asc';
    }

    $ticketResults = $wpdb->get_results( "SELECT * FROM " . EbpDatabase::getTableName("tickets")." where event= '$eventId' ".$ticketOrderingSQL);

    $spotsLeft = 0;

    $html = '<div class="Modal--Title">'.stripslashes($event->name).'</div>';

    $html .= '<input name="eventID" value="'.$eventId.'" type="hidden" />';
    $html .= '<input name="eventName" value="'.$event->name.'" type="hidden"  />';
    $html .= '<input name= "ebpMobilegPage" value="'.get_page_link(get_option('ebp_page_id')).'" type="hidden" />';

    //occurrence, tickets and spots left
    $html .= '<div class="Modal--Tickets">';
      $html .= self::getBookFormOccurrences($eventId, $inputOccurId, $settings);
      $html .= self::getBookFormTickets($ticketResults, $settings);
      $html .= self::getBookFormSpotsLeft($spotsLeft, $event, $settings);
      $html .= '<div  class="topBorder" style= "margin-top:5px;"></div>';
      $html .= '</div>';

    // quantity
    $html .= self::getBookFormQuantity($settings);

    // coupon
    $html .= self::getBookFormCoupon($settings);

    // form inputs
    $html .= self::getBookFormInputs($event->form, $settings);

    // button
    $html .= self::getBookFormBtns($event->modal == "true", $event->paypal == "true", $event->gateways, $spotsLeft, $settings);

    // loader
    $html .= '<div class="Modal--BookingLoader" data-text="'.$settings->bookingTxt.'" data-text2="'.$settings->eventBookedTxt.'" >'.$settings->bookingTxt.'</div>';

    return $html;
  }

  private static function getBookFormOccurrences($eventId, $inputOccurId, $settings) {
    $upcomingOccurrence = EbpEventOccurrence::getOpenUpcomingEventDates($eventId);
    $today = current_time('Y-m-d');

    $dateFormat = EventBookingHelpers::convertDateFormat($settings->dateFormat);
    $currentTime = strtotime(current_time("H:i:s"));

    $selectHTML = '<select id= "Modal--Occurrence" class="cd-select Modal--Occurrence">';
    foreach($upcomingOccurrence as $occur) {
      if ($today == $occur->start_date && $currentTime >= strtotime($occur->start_time)) continue;

      $occurId = $occur->id;
      $dateLanguaged = utf8_encode(strftime($dateFormat, strtotime($occur->start_date)));

      if ($settings->modal_includeTime == "true") {
        $time_of_event = ','.date($settings->timeFormat, strtotime($occur->start_time));
      } else {
        $time_of_event = '';
      }

      $bookingStatus = EbpEventOccurrence::bookingOpen($occur);
      $startsTxt = '';
      $endsTxt = '';

      if ($bookingStatus == 1) {
        $dateFormat = EventBookingHelpers::convertDateFormat($settings->dateFormat);
        $startDate = utf8_encode(strftime($dateFormat, strtotime($occur->startBooking_date)));
        $startTime = date($settings->timeFormat, strtotime($occur->startBooking_time));
        $startsTxt = str_replace(array('%date%', '%time%'), array($startDate, $startTime),
          $settings->bookingStartsTxts);
      } else if ($bookingStatus > 1){
        $endsTxt = $settings->bookingEndedTxt;
      }

      $isSelected = (intval($inputOccurId) == intval($occur->id)) ? 'selected="selected"' : '';

      $selectHTML .= '<option value="' . $occur->id . '" ' . $isSelected . ' data-bookingStatus="'.$bookingStatus.'" data-startsTxt="'.$startsTxt.'" data-endsTxt="'.$endsTxt.'" >' . $dateLanguaged . $time_of_event . '</option>';
    }
    $selectHTML .= '</select>';


    $html = '<div class="Modal--OccurrenceSelect">';
    if (sizeof($upcomingOccurrence) == 1) {
      $html .= '<div><input type="hidden" name="Modal--Occurrence" value="' . $occurId . '" />' . $dateLanguaged . $time_of_event.'</div>';
    } else if (sizeof($upcomingOccurrence) > 1) {
      $html .= $selectHTML;
    } else {
      $html .= '<input type="hidden"  name= "Modal--Occurrence" value= "-1" />';
    }

    $html .= '</div>';
    return $html;
  }

  private static function getCostFormatted($cost, $settings) {
    if ($cost == 0) {
      $ticketPrice = $settings->freeTxt;
    } else {
      $ticketPrice = EventBookingHelpers::currencyPricingFormat($cost,
        $settings->curSymbol, $settings->currencyBefore,
        $settings->priceDecimalCount,
        $settings->priceDecPoint,
        $settings->priceThousandsSep);
    }

    return $ticketPrice;
  }

  private static function getBookFormTickets($ticketResults, $settings) {
    $html = '';
    $selectHtml = '';
    $i = 0;

    $dataCost = '';
    $dataNames = '';
    $dataBreakdown = '';
    foreach ($ticketResults as $ticket) {
      $i++;
      $sel = ($i == 1) ? 'selected' : '';

      if ($ticket->breakdown != '') {
        $ticket->breakdown = json_decode($ticket->breakdown);
        $max = 0;
        $min = $ticket->breakdown[0]->cost;
        $dataBreakdown = 'true';
        $firstBreakdown = true;
        foreach ($ticket->breakdown as $breakdown) {
          $breakdownCost = floatval($breakdown->cost);
          if ($breakdownCost > $max) {
            $max = $breakdownCost;
          }

          if ($breakdownCost < $min) {
            $min = $breakdownCost;
          }

          if ($firstBreakdown) {
            $firstBreakdown = false;
          } else {
            $dataCost .= '&;';
            $dataNames .= '&;';
          }

          $dataCost .= $breakdown->cost;
          $dataNames .= $breakdown->name;
        }

        $ticketPrice = self::getCostFormatted($max, $settings);
        if ($min < $max) {
          $ticketPrice .= ' - ' . self::getCostFormatted($min, $settings);
        }
      } else {
        $ticketPrice = self::getCostFormatted(floatval($ticket->cost), $settings);
        $dataCost = '';
        $dataNames = '';
        $dataBreakdown = 'false';
      }

      $selectHtml .= '<option data-cost="'.$ticket->cost.'" data-costs="'.$dataCost.'" data-names="'.$dataNames.'" data-breakdown="'.$dataBreakdown.'" value="'.$ticket->id.'" '.$sel.'>';
      $selectHtml .= stripslashes($ticket->name);

      if ($settings->bookingFormTicketCntShowPrice == 'true' && $ticketPrice != "") {
        $selectHtml .= ' ('.$ticketPrice.')';
      }

      $selectHtml .= '</option>';

      $singleTicketHtml = '<input type="hidden" data-costs="'.$dataCost.'" data-names="'.$dataNames.'" data-breakdown="'.$dataBreakdown.'" name= "Modal--TicketType" data-cost="'.$ticket->cost.'"  value="'.$ticket->id.'"/>'.stripslashes($ticket->name);

      if ($settings->bookingFormTicketCntShowPrice == 'true' && $ticketPrice != "") $singleTicketHtml .= ' ('.$ticketPrice.')';
    }

    $html = '<div class="Modal--TicketSelect">';
    if (sizeof($ticketResults) > 1) {
      $html .= '<select id= "Modal--TicketType"  class="cd-select Modal--TicketType">';
      $html .= $selectHtml;
      $html .= '</select>';
    } else {
      $html .= '<div>'.$singleTicketHtml.'</div>';
    }

    $html .= '</div>';
    return $html;
  }

  private static function getBookFormSpotsLeft($spotsLeft, $event, $settings) {
    $spotsLeftShow = ($event->showSpots == "true") ? '' : 'display:none';
    return '<div class="Modal--SpotsLeft" style="'.$spotsLeftShow.'">'.$settings->modalSpotsLeftTxt.' <span>'.$spotsLeft.'</span></div>';
  }


  private static function getBookFormQuantity($settings) {
    // coupled with frontend.js too

    $html = '';
    $html .= '<input name="totalAmount" value="" type="hidden"/>';
    $html .= '<input name="couponAmountUsed" value="" type="hidden"/>';
    $html .= '<input name="totalAmountTaxed" value="" type="hidden" />';
    $html .= '<input name="totalQuantity" value="" type="hidden" />';

    $showQuantutyStyle = ($settings->multipleBookings == 'true') ? '' : 'display:none;';
    $html .= '<div class="Modal--Quantity" style="'.$showQuantutyStyle.'">';
     $html .= '<div class="Modal--QuantityCnt" >';
      $html .= '<div class="Modal--QuantityCnt-Inside" >';
        $html .= '<div class="Modal-QuantityColumn nameLabel">'.$settings->modalQuantityTxt.'</div>';
        $html .= '<div class="Modal-QuantityColumn singleLabel">'.$settings->modalSingleCostTxt.'</div>';
        $html .= '<div class="Modal-QuantityColumn totalLabel">'.$settings->modalTotalCostTxt.'</div>';
      $html .= '</div>';

      $html .= '<div class="Modal--QuantityCnt-Inside" style="'.$showQuantutyStyle.'">';

        $html .= '<div class="Modal-QuantityColumn Modal--QuantityBtns"><a href= "#" class="Modal--QuantityBtn down">-</a><span></span><a href= "#" class="Modal--QuantityBtn up">+</a></span></div>';

        $html .= '<div class="Modal-QuantityColumn single"><div class="ebp-prep"></div><span>'.EventBookingHelpers::currencyPricingFormat('0',
          $settings->curSymbol, $settings->currencyBefore,
          $settings->priceDecimalCount,
          $settings->priceDecPoint,
          $settings->priceThousandsSep, '<strong>%cost%</strong>').'</span></div>';

        $html .= '<div class="Modal-QuantityColumn total"><div class="ebp-prep"></div><span class="main">'.EventBookingHelpers::currencyPricingFormat('0',
          $settings->curSymbol, $settings->currencyBefore,
          $settings->priceDecimalCount,
          $settings->priceDecPoint,
          $settings->priceThousandsSep, '<strong>%cost%</strong>').'</span></div>';
      $html .= '</div>';
      $html .= '</div>';
    $html .= '</div>';
    $html .= '<div  class="topBorder" style="'.$showQuantutyStyle.'"></div>';

    return $html;
  }

  private static function getBookFormCoupon($settings) {
    // COUPONS SECTION
    $couponVisible = ($settings->couponsEnabled == "true") ? '' : "display:none;";
    $html = '<div class="Modal--CouponCnt" style="'.$couponVisible.'">';
    $html .= '<input type="text" name="coupon-code" placeholder="'.$settings->couponTxt.'" title= "'.$settings->couponTxt.'" class="couponInput"/>';
    $html .= '<a href= "#" class="Modal--CouponBtn">'.$settings->applyTxt.'</a>';
    $html .= '<span class="Modal--CouponResult"></span>';
    $html .= '<div class="bottomBorder"></div>';
    $html .= '</div>';
    return $html;
  }

  public static function getBookFormBtns($modal, $paypal, $gateways, $spotsLeft, $settings) {
    global $wpdb;

    $html = '<div class="Modal--NoBuy"></div>';
    $html .= '<div class="Modal--BookingBtnsCnt">';

    if ($modal == "true") {
      $btndeactive = ($spotsLeft == 0) ? "deactive" : "";
      $html .= '<a class="Modal--BookBtn '.$btndeactive.'" data-type="site">'.$settings->modalBookText.'</a>';
    }

    // gateways
    $btndeactive = ($spotsLeft == 0) ? "deactive" : "";

    if ($paypal== "true") {
      $html .= '<a class="Modal--BookBtn paypal '.$btndeactive.'" data-type="paypal">'.$settings->paypalBtnTxt.'</a>';
    }

    $gatewayArr = explode("%", $gateways);
    foreach($gatewayArr as $gateway) {
      $gatewayData = explode("=", $gateway);

      if (count($gatewayData) > 1 && $gatewayData[1] == "true") {
        $gatewayName = $gatewayData[0];
        $gatewayInfo = $wpdb->get_row( "SELECT * FROM " . EbpDatabase::getTableName("gateways")." where name= '$gatewayName' ");

        if ($gatewayInfo->active == 1) {
          $module = $gatewayInfo->module."Helpers";
          require_once(ABSPATH . 'wp-content/plugins/'.$gatewayInfo->module.'/'.$module . ".php");
          $gatwayClass = new $module;
          $html .= '<a class="Modal--BookBtn '.$gatewayName.' '.$btndeactive.'"  data-type="'.$gatewayName.'">'.$gatwayClass->getButtonText().'</a>';
        }
      }
    }
    $html .= '</div>';
    return $html;
  }


  private static function getBookFormInputs($form, $settings) {
    global $wpdb;

    if (!AddOnManager::usesFormAddOn()) {
      $isAvalable = -1;
    } else {
      $formData = $wpdb->get_row( "SELECT * FROM " . EbpDatabase::getTableName("forms")." where id='$form'");
      $isAvalable = ($formData) ? 1 : -1;
    }

    $requiresAccount = false;

    if (AddOnManager::getUsersAddonPath()) {
      $isLoggedIn = EventBookingProUsersClass::isLoggedIn();
      $requiresAccount = EventBookingProUsersClass::requiresAccount();
      if ($isLoggedIn) {
        $currentUser = EventBookingProUsersClass::getCurrentUser();
      }
    } else {
      $isLoggedIn = false;
    }

    $disableFields = ($isLoggedIn && $requiresAccount) ? 'disabled' : '';

    $emailvalue = ($isLoggedIn) ? $currentUser->user_email : '';

    if ($isAvalable >= 0 && $formData->splitName == 'true') {
      $firstNamevalue = ($isLoggedIn) ? $currentUser->user_firstname : '';
      $lastNamevalue = ($isLoggedIn) ? $currentUser->user_lastname : '';
    } else {
      $fullNamevalue = ($isLoggedIn) ? $currentUser->user_firstname.' '.$currentUser->user_lastname : '';
    }

    $html = '<form>';

    if ($isAvalable > 0) {
        $results = $wpdb->get_results( "SELECT * FROM " . EbpDatabase::getTableName("formsInput")." where form= '$form' order by fieldorder asc");
      //duplication constant
      $EBP_FORM_CNT_CLASS_NAME = 'ebp_form_duplicate_cnt';
      $duplicateDiv = '<div class="'.$EBP_FORM_CNT_CLASS_NAME.'" data-titletext="'.$settings->duplicateOnQuantityText.'"></div>';

      // newLine
      // halfAdded
      $currentState;
      $FIRST_INPUT = "FIRST_INPUT";
      $INPUT_NEW_LINE = "INPUT_NEW_LINE";
      $HALF_INPUT_ADDED = "HALF_INPUT_ADDED";

      $currentState = null;
      $nextState = $FIRST_INPUT;

      $hasDuplicateCnt = false;
      foreach($results as $field) {
        if ($field->type == 'duplicate_cnt') {
            $hasDuplicateCnt = true;
            break;
        }
      }

      foreach ($results as $field) {
        $isRequired = ($field->required == "true") ? "isRequired" : "";
        $dataDuplicate = 'data-duplicate="'.$field->duplicate.'"';
        $fieldIsHalf = ($field->size == "half");

        $formInputClass = 'formInput';
        if ($fieldIsHalf || isset($formData->splitName) && $field->type == "name" && $formData->splitName == 'true') {
          $formInputClass .= ' overflowed';
        }

        if ($field->type == 'duplicate_cnt') {
          $dataDuplicate = '';
          $fieldIsHalf = false;
        }

        $formInput_div = '<div class="'.$formInputClass.'" '.$dataDuplicate.'>';

        $currentState = $nextState;
        // Check if we should be on same line or different on:
        if ($currentState == $FIRST_INPUT) {
          $html .= $formInput_div;

          $nextState = ($fieldIsHalf) ? $HALF_INPUT_ADDED : $INPUT_NEW_LINE;

        } else if ($currentState == $INPUT_NEW_LINE) {
          $html .= '</div>';
          $html .= $formInput_div;

          $nextState = ($fieldIsHalf) ? $HALF_INPUT_ADDED : $INPUT_NEW_LINE;
        } else {
          // we only close the line, if current is not half
          if (!$fieldIsHalf) {
             $html .= '</div>';
            $html .= $formInput_div;
          }

          // in this case the next field should start at a new line. 3 halfs do not fit.
          $nextState = $INPUT_NEW_LINE;
        }

        $halfClass =  ($fieldIsHalf) ? 'half' : '';

        switch($field->type) {
          case "name":

            if ($formData->splitName == 'true') {
              $html .= '<input name="firstName" value="'.$firstNamevalue.'" placeholder="'.stripslashes($formData->firstNameTxt).'" class="bookInput half isRequired" type="text"  '.$disableFields.' />';

              $html .= '<input name="lastName" value="'.$lastNamevalue.'" placeholder="'.stripslashes($formData->lastNameTxt).'" class="bookInput half isRequired" type="text"  '.$disableFields.' />';
            } else {
              $fullNamevalue = ($isLoggedIn) ? $currentUser->user_firstname.' '.$currentUser->user_lastname : '';

              $html .= '<input name="name" value="'.$fullNamevalue.'" placeholder="'.stripslashes($field->options).'" class="bookInput isRequired" type="text"  '.$disableFields.' />';
            }
          break;

          case "requiredEmail":
            $emailvalue = ($isLoggedIn) ? $currentUser->user_email : '';
            $html .= '<input  name="email" value="'.$emailvalue.'" placeholder="'.stripslashes($field->options).'" class="bookInput email isRequired" type="text"  '.$disableFields.' />';
          break;

          case "txt":
            $html .= '<input name="'.$field->name.'" value="" placeholder="'.stripslashes($field->options).'"  class="bookInput '.$isRequired.' '.$halfClass.'" type="text"  />';
          break;

          case "staticText":
            $html .= '<div class="fieldHolder staticText'.$halfClass.'"><p style="font-size:'.stripslashes($field->label).'px">'.stripslashes($field->name).'</p></div>';
          break;

          case "email":
            $html .= '<input name="'.$field->name.'" placeholder="'.stripslashes($field->options).'"  class="bookInput email '.$isRequired.'  '.$halfClass.'" type="text"  />';
          break;

          case "txtArea":
            $html .= '<textarea name="'.$field->name.'" placeholder="'.stripslashes($field->options).'"  class="bookInput '.$isRequired.' '.$halfClass.'"></textarea>';
          break;

          case "select":
          case "select_fee":
            // same logic is done in frontend (for duplication)
            $hasFee = ($field->type == 'select_fee');
            $feeClass = ($hasFee) ? 'hasFee' : '';


            $html .= '<div class="fieldHolder '.$isRequired.' hasSelectField '.$halfClass.'">';

            if ($settings->modal_selectLabelAsNoneOption == 'false' && stripslashes($field->label)!= "") {
              $html .= '<span class="label" >'.stripslashes($field->label).'</span>';
            }

            $html .= '<select class="'.$feeClass.'" name="'.$field->name.'" >';

            $firstOptionText = ($settings->modal_selectLabelAsNoneOption == 'true') ? stripslashes($field->label) :  stripslashes($settings->modal_selectNoneOption);
            $html .= '<option value= "none" selected="selected">'.$firstOptionText.'</option>';

            $opts = str_replace(array("\n\r","\n"), "", stripslashes($field->options));
            $split = explode(';', $opts);

            $prices = str_replace(array("\n\r","\n"), "",stripslashes($field->prices));
            $splitPrices = explode(';', $prices);

            $feeClass = ($hasFee) ? 'hasFee' : '';
            $feeType = ($hasFee) ? 'data-fee-type="' . $field->feeType . '"' : '';

            for ($i = 0; $i < sizeof($split); $i++) {
              $item = $split[$i];
              $itemPrice = (sizeof($splitPrices) > $i) ? $splitPrices[$i] : 0;

              $itemPriceData = ($hasFee) ? 'data-cost="'.$itemPrice.'"': '';

              $itemName = $item;
              $itemName .= (!$hasFee) ? '' :' ('.EventBookingHelpers::currencyPricingFormat($itemPrice, $settings->curSymbol, $settings->currencyBefore,$settings->priceDecimalCount,$settings->priceDecPoint,$settings->priceThousandsSep).')';

              $html .= '<option value="'.$itemName.'" '.$feeType.' '.$itemPriceData.'>'.$itemName.'</option>';
            }
            $html .= '</select></div>';

          break;

          case "check":
          case "check_fee":
            $hasFee = ($field->type == 'check_fee');
            $feeClass = ($hasFee) ? 'hasFee' : '';

            $html .= '<div class="fieldHolder hasCheckBoxes '.$isRequired.' '.$feeClass.' '.$halfClass.'" data-name="'.$field->name.'">';
            $opts = str_replace(array("\n\r","\n"), "",stripslashes($field->options));
            $split = explode(';', $opts);

            $prices = str_replace(array("\n\r","\n"), "",stripslashes($field->prices));
            $splitPrices = explode(';', $prices);

            if (stripslashes($field->label)!= "") {
              $html .= '<span class="label" >'.stripslashes($field->label).'</span>';
            }


            $feeType = ($hasFee) ? 'data-fee-type="' . $field->feeType . '"' : '';

            for ($i = 0; $i < sizeof($split); $i++) {
              $item = $split[$i];
              $itemPrice = (sizeof($splitPrices) > $i) ? $splitPrices[$i] : 0;

              $itemPriceData = ($hasFee) ? 'data-cost="'.$itemPrice.'"': '';

              $rand = rand();
              if ($item != "") {
                $itemName = $item;
                $itemName .= (!$hasFee) ? '' :' ('.EventBookingHelpers::currencyPricingFormat($itemPrice, $settings->curSymbol, $settings->currencyBefore,$settings->priceDecimalCount,$settings->priceDecPoint,$settings->priceThousandsSep).')';

                $html .= '<div class="inputholder"><div class="checkBoxStyle '.$feeClass.'">';
                $html .= '<input id="'.$field->name.$i.$rand.'" type="checkbox" value="'.$itemName.'" '.$feeType.' '.$itemPriceData.'>';
                $html .= '<label class="check"  for="'.$field->name.$i.$rand.'"></label>';
                $html .= '</div>'.$itemName. '</div>';
              }
            }
            $html .= '</div>';
          break;


          case "radio":
          case "radio_fee":
            $hasFee = ($field->type == 'radio_fee');
            $feeClass = ($hasFee) ? 'hasFee' : '';

            $html .= '<div class="fieldHolder hasRadioButton '.$isRequired.' '.$feeClass.' '.$halfClass.'">';
            $opts = str_replace(array("\n\r","\n"), "",stripslashes($field->options));
            $split = explode(';', $opts);

            $prices = str_replace(array("\n\r","\n"), "",stripslashes($field->prices));
            $splitPrices = explode(';', $prices);


            $feeType = ($hasFee) ? 'data-fee-type="' . $field->feeType . '"' : '';


            if (stripslashes($field->label)!= "")
              $html .= '<span class="label" >'.stripslashes($field->label).'</span>';

            for ($i = 0; $i < sizeof($split); $i++) {
              $item = $split[$i];
              $itemPrice = (sizeof($splitPrices) > $i) ? $splitPrices[$i] : 0;

              $itemPriceData = ($hasFee) ? 'data-cost="'.$itemPrice.'"': '';

              if ($item != "") {
                $itemName = $item;
                $itemName .= (!$hasFee) ? '' :' ('.EventBookingHelpers::currencyPricingFormat($itemPrice, $settings->curSymbol, $settings->currencyBefore,$settings->priceDecimalCount,$settings->priceDecPoint,$settings->priceThousandsSep).')';

                $rand = rand();
                $html .= '<div class="inputholder"><div class="checkBoxStyle">';
                $html .= '<input id="'.$field->name.$i.$rand.'" name="'.$field->name.'" type="radio" value="'.$itemName.'" '.$feeType.' '.$itemPriceData.'>';
                $html .= '<label class="dot" for="'.$field->name.$i.$rand.'"></label>';
                $html .= '</div>'.$itemName.'</div>';
              }
            }
            $html .= '</div>';
          break;

          case "terms":
            // Add duplicate field if not no container
            if (!$hasDuplicateCnt) {
              $html .= $duplicateDiv;
            }
            $html .= '<div class="fieldHolder hasCheckBoxes isTerms '.$isRequired.' '.$halfClass.'" data-name="'.$field->name.'">';

              $rand = rand();
              $html .= '<div class="checkBoxStyle">';

              $html .= '<input id="'.$field->id.$rand.'" type="checkbox" value= "terms" >';

              $html .= '<label class="check" for="'.$field->id.$rand.'"></label>';

              $html .= '</div>';


                $html .= '<span class="label" >';
              if (stripslashes($field->label) != "")
                $html .= '<a target="_blank" href="'.stripslashes($field->label).'">';
              $html .= stripslashes($field->name);
              if (stripslashes($field->label) !=  "")
                $html .= '</a>';
              $html .= '</span>';


            $html .= '</div>';
          break;

          case 'duplicate_cnt':
            $html .= $duplicateDiv;
          break;
        }
      }

      // close last field
      if ($currentState != null) {
        $html .= '</div>';
      }

      // place duplication cnt if not already placed
      if (strpos($html, $EBP_FORM_CNT_CLASS_NAME) === false) {
        $html .= $duplicateDiv;
      }

    } else {
      // add email field
      $html .= '<div class="formInput"><input name="name" value="'.$fullNamevalue.'" placeholder="'.stripslashes($settings->modalNameTxt).'"  class="bookInput isRequired" type="text" '.$disableFields.' /></div>';

      // add email field
      $html .= '<div class="formInput"><input  name="email" value="'.$emailvalue.'" placeholder="'.stripslashes($settings->modalEmailTxt).'" class="bookInput email isRequired" type="text"  '.$disableFields.' /></div>';

      // Phone input if enabled
      if ($settings->requirePhone == "true") {
        $html .= '<div class="formInput"><input id="bookPhone" name="phone" placeholder="'.$settings->modalPhoneTxt.'" class="bookInput isRequired" type="text"  /></div>';
      }

      // Address input if enabled
      if ($settings->requireAddress == "true") {
        $html .= '<div class="formInput"><input id= "bookAddress" name="address" placeholder="'.$settings->modalAddressTxt.'" class="bookInput isRequired" type="text"  /></div>';
      }
    }

    $html .= '</form>';
    return $html;
  }

}
