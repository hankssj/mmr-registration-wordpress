<?php
require_once dirname( __FILE__ ) . '/include.php';

class EventCardExtended {

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
				$widthStyle ='style="max-width:'.$width.'px"';
			} else{
				$width = $settingsOption["width"];
			}

			$eventClasses = EbpCategories::eventIdentificationClasses($id);

			$bgStyle = '';
			$bgImageArr = explode('__and__', $data->background);
			$bgImage = $bgImageArr[0];
			if ($settingsOption["settings"]->eventCardImageAsBackground == "true" && $bgImage != "") {
				$bgStyle = 'style=" background: '.$settingsOption["settings"]->boxBgColor.' url('.$bgImage.') center center no-repeat;"';
			}

			$html .= '<div class="eventCardExtendedCnt '.$eventClasses.'" '.$widthStyle.'  data-init-width="'.$width.'">';

    	$html .= '<input name= "ajaxlink" value="'.site_url().'" type= "hidden"  />';
			$html .= '<div  class="eventCardCnt lite extended eventDisplayCnt" '.$widthStyle.' >';

			if ($settingsOption["settings"]->eventCardImageAsBackground == "true" && $bgImage != "") {
				$html .= '<div class="cardBg" ><img src="'.$bgImage.'" /></div>';
			}

			$html .= '<div class="cntHolder" '.$widthStyle.'>';

			if ($settingsOption["settings"]->eventCardShowThumbnail == "true" && $data->image != "") {
				$html .= '<div class="imageHolder"><img src="'.$data->image.'"/></div>';
			}

			//title


			$html .= '<div class="details">';
			$html .= '<span class="title">'.stripslashes($data->name).'</span>';

			//date
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
			foreach($eventTickets as $ticketInfo) {
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

			$left = ($maxSpots > 0 && $maxSpots < $left) ? ($maxSpots - $spotsBookedAll) : $left;

			$showBtn = false;
			$today = date('Y-m-d');
			foreach( $allDates as $occur ) {
				if (EbpEventOccurrence::occurrenceClosed($occurrence, true)) continue;

				foreach($eventTickets as $ticketInfo) {
					if (Event::checkSpots($occur->id,$ticketInfo->id) > 0) {
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
							if ($left == 0)
								$html .= '<div class="passedEvent" >'.$settingsOption["bookedTxt"].'</div>';
							else
								$html .= '<div class="spots" style="">'.$left.' '.$settingsOption["spotsLeftTxt"].'</div>';

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
			$html .= '<div class="arrow-down"></div>';
			$html .= '</div>';

			$html .= '<div class="eventDescription">';

					//image
					if ($settingsOption["settings"]->eventCardShowImage == 'true' && $data->image != "") {
						$html .= '<div class="eventImage"  data-image="'.$data->image.'" data-image-crop="'.$settingsOption["imageCrop"].'" data-image-height="'.$settingsOption["imageHeight"].'"  data-image-width="'.$settingsOption["imgWidth"].'" ></div>';
					}

					//info
					if ($data->info != "") {
						$html .= '<div class="infoTitle">'.$settingsOption["settings"]->eventDescriptionTitle.'</div>';
						$infoDeactive=($settingsOption["settings"]->infoNoButton=="true")?'':'deactive';
						$html .= '<div class="info '.$infoDeactive.'"  data-closeTxt="'.$settingsOption["closeTextTxt"].'" data-expandTxt="'.$settingsOption["ExpandTextTxt"].'" data-height="'.$settingsOption["infoHeight"].'">';

							$html .= '<div class="cnt">';

								$html .= stripslashes ($data->info);
							$html .= '</div>';

						$html .= '</div>';
							$html .= '<div class="cntForSpace"></div>';
					}

					//map
					if ($data->mapAddress != "" && $settingsOption['settings']->googleMapsEnabled == 'true')  {
						$html .= '<div class="map_canvas" style="display:none; height:'.$settingsOption["settings"]->mapHeight.'px;" data-address="'.$data->mapAddress.'" data-zoom="'.$data->mapZoom.'" data-maptype="'.$data->mapType.'" data-addressType="'.$data->mapAddressType.'" ></div>';
					}

						$html .= '<a href="#" class="hideDetails"></a>';
				$html .= '</div>';

			$html .= '</div>';

		} else {
		  $html ='<div class="eventNotFound" style="'.$boxStyling.' width:auto;">Event Not Found!</div>';
		}

		return $html;
	}


}


?>
