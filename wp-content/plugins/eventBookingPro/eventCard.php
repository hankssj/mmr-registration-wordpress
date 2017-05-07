<?php
require_once dirname( __FILE__ ) . '/include.php';

class EventCard {

	public static function getCard($id, $date_id, $width) {
		global $wpdb;

		if ($date_id != -1) {
      $isAvilable = $wpdb->get_var( "SELECT count(*) FROM " . EbpDatabase::getTableName("eventDates")." where id='$date_id' && event='$id' ");
      if ($isAvilable == 0) {
        return '<div class="eventNotFound" style="width:auto;">Event Occurrence not found or deleted</div>';
      }
    };

		$data = $wpdb->get_row( "SELECT * FROM " . EbpDatabase::getTableName("events")." where id='$id' ");
		$settingsOption = EventBookingHelpers::getStyling($width,3);
		$curSymbol = EventBookingHelpers::getSymbol($settingsOption["currency"]);
		$html = '';
		if ($data != NULL) {

			$widthStyle = "";

			if ($width != "" && $width != NULL) {
				$widthStyle = 'style="max-width:'.$width.'px"';
			}

			$eventClasses = EbpCategories::eventIdentificationClasses($id);

			$bgStyle = '';
			$bgImageArr = explode('__and__', $data->background);
			$bgImage = $bgImageArr[0];
			if ($settingsOption["settings"]->eventCardImageAsBackground == "true" && $bgImage != "") {
				$bgStyle = 'style=" background: '.$settingsOption["settings"]->boxBgColor.' url('.$bgImage.') center center no-repeat;"';
			}


			$html .= '<div '.$bgStyle .' class="eventCardCnt lite eventDisplayCnt '.$eventClasses.'" '.$widthStyle.'>';
   		$html .= '<input name= "ajaxlink" value="'.site_url().'" type= "hidden"  />';
			$html .= '<div class="cntHolder" '.$widthStyle.'>';

			// image
			if ($settingsOption["settings"]->eventCardShowThumbnail =="true" && $data->image!=""){

				$html .= '<div class="imageHolder"><img src="'.$data->image.'"/></div>';
			}


			$html .= '<div class="details">';
			// tittle

			$html .= '<span class="title">'.stripslashes($data->name).'</span>';

			// date
			$dateId = $date_id;
			list ($passedDates, $upcomingDates) = EbpEventOccurrence::getEventDatesAsPassedUpcoming($id);

			$allDates = array_merge($passedDates, $upcomingDates);


			$dateMarkUp = EbpEventOccurrence::getEventDateMarkUp($data, $dateId, $settingsOption, $upcomingDates, $passedDates);

			$html .= $dateMarkUp["html"];
			$date_id = $dateMarkUp["dateID"];
			$date = $dateMarkUp["date"];
    	$occurrence = $dateMarkUp["occurrence"];

			// location
			$displayAddress = ($data->address != '') ? $data->address : $data->mapAddress;
			if ($displayAddress != "" && $settingsOption['settings']->googleMapsEnabled == 'true') {
				$address = preg_replace('/\s+/','+',$data->mapAddress);
				$html .= '<div class="EBP--Location"><a href="http://maps.google.com/?q='.$address.'" target="_blank">'.$displayAddress.'</a></div>';
			}


			//details
			$eventTickets = $wpdb->get_results( "SELECT * FROM " . EbpDatabase::getTableName("tickets")." where event='$id' order by id asc");
			$i = 0;
			$left = 0;

			foreach ($eventTickets as $ticketInfo) {
				$i++;

				if ($i == 1) {
					$ticketID = $ticketInfo->id;
					$cost = $ticketInfo->cost;
					$allowed = $ticketInfo->allowed;
				}
				$left += Event::checkSpots($date_id,$ticketInfo->id);
			}

			$spotsBookedAll = $spotsBookedAll = EbpBooking::getSpotsBookedForEvent($id, $date_id, $settingsOption["settings"]->spotsLeftStrict, $settingsOption["settings"]->statusesCountedAsCompleted);

			$maxSpots = intval($data->maxSpots);

			$left = ($maxSpots>0 && $maxSpots<$left) ? ($maxSpots-$spotsBookedAll) : $left;

			$showBtn = false;
			$today = date('Y-m-d');
			foreach ( $allDates as $occur ) {
				if (EbpEventOccurrence::occurrenceClosed($occurrence, true)) continue;

				foreach($eventTickets as $ticketInfo) {
					if (Event::checkSpots($occur->id,$ticketInfo->id)>0) {
						$showBtn = true;
						break;
					}
				}
			}

			$html .= '<div class="eventDetails">';
				$isClosed = EbpEventOccurrence::occurrenceClosed($occurrence, true);
				$hasPassed =  EbpEventOccurrence::occurenceHasStarted($occurrence);
					if ($data->showPrice == "true") {
						if (!$hasPassed) {
							if (intval($cost) == 0) {
								$html .= '<div class="price">'.$settingsOption["settings"]->freeTxt.'</div>';
							} else if ($data->showPrice == "true") {
								$html .= '<div class="price" >'.EventBookingHelpers::currencyPricingFormat($cost,$curSymbol,$settingsOption["settings"]->currencyBefore,$settingsOption["settings"]->priceDecimalCount,$settingsOption["settings"]->priceDecPoint,$settingsOption["settings"]->priceThousandsSep).'</div>';
							}
						}
					}

					if ($data->showSpots == "true") {
						if ($isClosed) {
							$buttonText = EbpEventOccurrence::occurenceHasStarted($occurrence) ? $settingsOption["passedTxt"] :  $settingsOption['settings']->bookingEndedTxt;

							$html .= '<div class="passedEvent">'.$buttonText.'</div>';
						} else {
							if ($left == 0) $html .= '<div class="passedEvent" >'.$settingsOption["bookedTxt"].'</div>';
							else $html .= '<div class="spots" style="">'.$left.' '.$settingsOption["spotsLeftTxt"].'</div>';
						}
					}

					$pattern = '/[%=]/';
					$activeGatewaysArr = preg_split($pattern,$data->gateways);

					$showBtn = ($showBtn && ($data->paypal=="true" || $data->modal=="true" || in_array('true', $activeGatewaysArr) )) ;

					if ($data->eventStatus != 'active') {
			      $showBtn = false;
			      $html .= '<div class="buy"><cite>'.$settingsOption["settings"]->eventCancelledTxt.'</cite></div>';
			    }
					if ($showBtn) {
						$bookingBtn = Event::getModalBtn($id, $occurrence, $date_id, $dateId, $settingsOption["settings"]->mobileSeperatePage, true, '',
							$settingsOption["btnTxt"], $settingsOption["settings"]->bookingStartsTxts, $settingsOption["settings"]->bookingEndedTxt, $settingsOption["dateFormat"], $settingsOption["timeFormat"], 'buyCnt');
						$html .= $bookingBtn['html'] . $bookingBtn['modal'];
					}

				$html .= '</div>';

				$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';

		} else {
		  $html = '<div class="eventNotFound" style="'.$boxStyling.' width:auto;">Event Not Found!</div>';
		}


		return $html;
	}


	public static function getDefaultSettings() {
		return
  array('passedTxt' => 'Event Passed','bookedTxt' => 'No spots left','btnTxt' => 'Book','showPrice' => 'false','includeEndsOn' => 'false','imageMarginSides' => '0','imageCrop' => 'true','imageHeight' => '100','imageMarginTop' => '0','imageMarginBottom' => '0','btnColor' => '#FFFFFF','btnBgColor' => '#2ecc71','btnFontSize' => '14','btnFontType' => 'normal','btnLineHeight' => '18','btnSidePadding' => '10','btnTopPadding' => '5','btnBorder' => '0','btnBorderColor' => '#FFF','btnBorderRadius' => '3','btnMarginTop' => '0','btnMarginBottom' => '0','boxWidth' => '500','boxPaddingSides' => '15','boxPaddingTop' => '10','boxPaddingBottom' => '10','boxMarginSides' => '0','boxMarginBottom' => '20','boxAlign' => 'true','boxMarginTop' => '0','boxBgColor' => '#f9f9f9','boxBorder' => '1','boxBorderColor' => '#F2F2F2','boxBorderRadius' => '3','titleColor' => '#495468','titleFontSize' => '28','titleLineHeight' => '24','titleTextAlign' => 'left','titleFontStyle' => 'normal','titlePaddingSides' => '0','titlePaddingTop' => '10','titlePaddingBottom' => '10','titleMarginTop' => '0','titleMarginBottom' => '0','titleBottomBorder' => '0','titleBottomBorderColor' => '#EEEEEE','dateTextAlign' => 'left','datePaddingTop' => '3','datePaddingBottom' => '0','datePaddingSides' => '0','dateMarginTop' => '0','dateMarginBottom' => '5','dateColor' => '#999999','dateLableColor' => '#666666','dateLableSize' => '12','dateLabelLineHeight' => '16','dateLabelStyle' => 'normal','dateFontSize' => '14','dateFontLineHeight' => '26','dateFontStyle' => 'normal','dateBorderColor' => '#EEEEEE','dateBorderSize' => '0','moreDateTextAlign' => 'left','moreDateColor' => '#c4c4c4','moreDateSize' => '10','moreDateLineHeight' => '16','moreDateMarginTop' => '0','moreDateFontStyle' => 'italic','moreDateHoverColor' => '#a3a3a3','moreDateOn' => 'true','moreDatePassed' => 'true','moreDateUpcoming' => 'true','moreDateTxt' => 'More','moreDateSectionMarginBottom' => '30','modal_dateTitleTextAlign' => 'center','modal_dateTitlePaddingSides' => '0','modal_dateTitleMarginBottom' => '5','modal_dateTitleColor' => '#000000','modal_dateTitleFontSize' => '28','modal_dateTitleFontLineHeight' => '30','modal_dateTitleFontStyle' => 'italic','modal_dateTextAlign' => 'left','modal_datePaddingTop' => '0','modal_datePaddingBottom' => '0','modal_datePaddingSides' => '20','modal_dateMarginTop' => '10','modal_dateMarginBottom' => '10','modal_dateColor' => '#999','modal_dateLableColor' => '#f2f2f2','modal_dateLableSize' => '12','modal_dateLabelLineHeight' => '16','modal_dateLabelStyle' => 'normal','modal_dateFontSize' => '14','modal_dateFontLineHeight' => '16','modal_dateFontStyle' => 'normal','detailsPaddingTop' => '10','detailsPaddingBottom' => '10','detailsPaddingSides' => '20','detailsMarginTop' => '10','detailsMarginBottom' => '0','detailsColor' => '#999999','detailsLableColor' => '#CCC','detailsLableSize' => '18','detailsLabelLineHeight' => '32','detailsLabelStyle' => 'normal','detailsFontSize' => '26','detailsFontLineHeight' => '32','detailsFontStyle' => 'normal','detailsBorderColor' => '#EEE','detailsBorderSize' => '0','detailsBorderSide' => '0','infoMaxHeight' => '120','locationTextAlign' => 'left','locationColor' => '#111','locationFontSize' => '14','locationLineHeight' => '14','locationFontStyle' => 'normal', 'infoExpandText' => 'more','infoTextAlign' => 'left','infoColor' => '#111','infoFontSize' => '14','infoLineHeight' => '14','infoFontStyle' => 'normal','infoPaddingSides' => '20','infoPaddingTop' => '0','infoPaddingBottom' => '20','infoMarginTop' => '20','infoMarginBottom' => '20','infoBorderColor' => '#e5e5e5','infoBorderSize' => '1','modalMainColor' => '#2ecc71','modalNameTxt' => 'Name','modalEmailTxt' => 'Email Address','modalPhoneTxt' => 'Phone Number','modalAddressTxt' => 'Your Address','modal_btnTxtColor' => '#FFF','modal_btnFontSize' => '16','modal_btnLineHeight' => '16','modal_btnFontType' => 'normal','modal_btnTopPadding' => '15','modal_btnSidePadding' => '35','modal_btnMarginTop' => '30','modal_btnBorderRadius' => '6','modal_titleSize' => '48','modal_titleLineHeight' => '48','modal_titleFontType' => 'normal','modal_titleMarginBottom' => '40','modal_input_fontSize' => '16','modal_input_lineHeight' => '20','modal_input_topPadding' => '12','modal_input_space' => '10','modalBookText' => 'Pay Later','paypalBtnTxt' => 'Pay Now','requirePhone' => 'true','requireAddress' => 'true','modal_txtColor' => '#ffffff','dateFormat' => 'F jS, Y','timeFormat' => 'g:i a','endsOnTxt' => 'Ends On:','statsOnTxt' => 'Starts On:','spotsLeftTxt' => 'spots left','modalSpotsLeftTxt' => 'Spots left:','modalQuantityTxt' => 'Quantity','modalSingleCostTxt' => 'Single Cost','modalTotalCostTxt' => 'Total Cost','eventBookedTxt' => 'Event Booked!','bookingTxt' => 'Booking Event ...','ExpandTextTxt' => 'Expand','closeTextTxt' => 'Close','termsLink' => 'terms','modal_input_txtColor' => '#FFFFFF','modal_inputHover_txtColor' => '#333333','modal_input_bgColor' => '#FFFFFF','modal_inputHover_bgColorHover' => '#FFFFFF','modal_input_bgColorAlpha' => '20','modal_inputHover_bgColorAlpha' => '60','modal_selectHoverColor' => '#208f4f','modal_selectTxtHoverColor' => '#FFFFFF','currencyBefore' => 'true','emailSubject' => 'Event Booking Information','emailSSL' => 'false','mapHeight' => '150','checkBoxMarginBottom' => '20','checkBoxMarginTop' => '20','checkBoxTextColor' => '#EEE','checkBoxColor' => '#111','cal_height' => '400','applyTxt' => 'Apply','couponTxt' => 'Coupon','freeTxt' => 'Free','popupOverlayAlpha' => '100','modalOverlayColor' => '#2ecc71','return_same_page' => 'true','return_page_url' => '','infoNoButton' => 'true','calEventDayColor' => '#FFFFFF','calEventDayColorHover' => '#FFFFFF','calTodayColor' => '#2eCC71','calEventDayDotColorHover' => '#2ecc71','calEventDayDotColor' => '#DDDDDD','modal_includeTime' => 'false','eventCardShowImage' => 'true','eventDescriptionTitle' => 'Event Information','infoTitleFontSize' => '18','infoTitleColor' => '#111111','cardDescriptionBackColor' => '#f1f1f1');

	}

}




?>
