<?php
ini_set('log_errors', true);
ini_set('error_log', dirname(__FILE__).'/errors.log');

require_once dirname( __FILE__ ) . '/include.php';

class EventBookingHelpers {
	// backwards compatibility
	public static function getTableName($name) {
    return EbpDatabase::getTableName($name);
  }

  public static function getIndexedArray($array) {
  	$indexArray = array();
		foreach($array as $item) {
		   array_push($indexArray, $item->id);
		}

  	return $indexArray;
  }

  public static function getElementById($array, $id, $indexedArray = null) {
  	if ($indexedArray == null) $indexedArray = indexEventsAsArray($array);

  	$index = array_search($id, $indexedArray);

  	return $array[$index];
  }

  // backward compatibility
	public static function eventBelongsToCategoreis($id, $categories) {
		return EbpCategories::eventBelongsToCategories($id, $categories);
	}

	public static function currencyPricingFormat($cost, $curr, $isBefore, $decimals, $decimalPoint, $thousandSep, $wrapperCost="%cost%") {
		if ($cost == "" || !is_numeric($cost)) $cost = 0;

		$cost = number_format($cost, $decimals, $decimalPoint, $thousandSep);

		$cost = str_replace("%cost%", $cost, $wrapperCost);
		if ($isBefore == "true") {
			return  $curr . '' . $cost;
		} else {
			return $cost . ' ' . $curr;
		}
	}


	public static function hex2rgba($color, $opacity = false) {
		$default = 'rgb(0,0,0)';
		//Return default if no color provided
		if (empty($color)) return $default;

		//Sanitize $color if "#" is provided
		if ($color[0] == '#' ) {
			$color = substr( $color, 1 );
		}

		//Check if color has 6 or 3 characters and get values
		if (strlen($color) == 6) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} else if ( strlen( $color ) == 3 ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}

		//Convert hexadec to rgb
		$rgb =  array_map('hexdec', $hex);

		//Check if opacity is set(rgba or rgb)
		if ($opacity) {
			if (abs($opacity) > 1)
				$opacity = 1.0;
			$output = 'rgba('.implode(",", $rgb).','.$opacity.')';
		} else {
			$output = 'rgb('.implode(",", $rgb).')';
		}

		//Return rgb(a) color string
		return $output;
	}


	public static function getStyling($cust_width = NULL, $id) {
		global $wpdb;
		$settings = $wpdb->get_row( "SELECT * FROM " . EventBookingHelpers::getTableName("settings")." where id='$id' ");

		if ($cust_width != NULL)
			$thewidth = $cust_width;
		else if ($id == 1 || $id == 3)
			$thewidth = $settings->boxWidth;
		else if ($id == 2)
			$thewidth = $settings->cal_width;

		$originalWidth = $thewidth;

		if ($id == 2) {
			$thewidth = intval($thewidth) - 2 * intval($settings->cal_paddingSides);
		}

		$innerWidth = (intval($thewidth) / 2 - intval($settings->detailsPaddingSides)-intval($settings->detailsBorderSide));


		if ($settings->boxAlign == "true")
			$boxAlign = 'margin: 0 auto; margin-top: '.$settings->boxMarginTop.'px; margin-bottom: '.$settings->boxMarginBottom.'px;';
		else
			$boxAlign = 'margin: '.$settings->boxMarginTop.'px '.$settings->boxMarginSides.'px; margin-bottom: '.$settings->boxMarginBottom.'px;';


		$boxstyle = 'width:'.$thewidth.'px; max-width: 100%; padding:'.$settings->boxPaddingTop.'px '.$settings->boxPaddingSides.'px; padding-bottom: '.$settings->boxPaddingBottom.'px;  background-color:'.$settings->boxBgColor.';  -webkit-border-radius: '.$settings->boxBorderRadius.'px; -moz-border-radius: '.$settings->boxBorderRadius.'px; border-radius: '.$settings->boxBorderRadius.'px; border:'.$settings->boxBorder.'px solid'.$settings->boxBorderColor.';'.$boxAlign;


		$buyBtnCnt = 'margin: 0 auto; padding-top: '.$settings->btnMarginTop.'px; padding-bottom:'.$settings->btnMarginBottom.'px;';

		$bookBtnStyle = '';

		$btnStyle = array('btn' => $bookBtnStyle, 'cnt' => $buyBtnCnt);

		$titleAignment = 'text-align:'.$settings->titleTextAlign.';';
		if ($settings->titleTextAlign == "center")
			$titleAignment .= 'margin:0 auto;';

		$fontStyle = ($settings->titleFontStyle == "italic") ? 'font-style:italic;' : 'font-weight:'.$settings->titleFontStyle.';';

		$titleStyle = 'color:'.$settings->titleColor.'; font-size:'.$settings->titleFontSize.'px ;line-height:'.$settings->titleLineHeight.'px;'.$fontStyle.' padding:'.$settings->titlePaddingTop.'px '.$settings->titlePaddingSides.'px; padding-bottom:'.$settings->titlePaddingBottom.'px;'.$titleAignment.' margin-top:'.$settings->titleMarginTop.'px; margin-bottom:'.$settings->titleMarginBottom.'px; border-bottom:'.$settings->titleBottomBorder.'px solid '.$settings->titleBottomBorderColor.';' ;


		//date
		$alignment = 'text-align:'.$settings->dateTextAlign.';';
		if ($settings->dateTextAlign == "center")
			$alignment .= 'margin:0 auto;';

		$fontStyle = ($settings->dateFontStyle == "italic")?'font-style:italic;':'font-weight:'.$settings->dateFontStyle.';';

		$dateStyle = 'color:'.$settings->dateColor.'; font-size:'.$settings->dateFontSize.'px; line-height:120%;'.$fontStyle.' padding:'.$settings->datePaddingTop.'px '.$settings->datePaddingSides.'px; padding-bottom:'.$settings->datePaddingBottom.'px;'.$alignment.' margin-bottom:'.$settings->dateMarginBottom.'px; margin-top:'.$settings->dateMarginTop.'px; border-bottom:'.$settings->dateBorderSize.'px solid '.$settings->dateBorderColor.';' ;


		//date modal
		$alignment = 'text-align:'.$settings->modal_dateTextAlign.';';
		if ($settings->modal_dateTextAlign == "center")
			$alignment .= 'margin:0 auto;';

		$fontStyle = ($settings->modal_dateFontStyle == "italic")?'font-style:italic;':'font-weight:'.$settings->modal_dateFontStyle.';';

		$modal_dateStyle = 'color:'.$settings->modal_dateColor.'; font-size:'.$settings->modal_dateFontSize.'px; line-height:120% '.$fontStyle.' padding:'.$settings->modal_datePaddingTop.'px '.$settings->modal_datePaddingSides.'px; padding-bottom:'.$settings->modal_datePaddingBottom.'px;'.$alignment.' margin-bottom:'.$settings->modal_dateMarginBottom.'px; margin-top:'.$settings->modal_dateMarginTop.'px;' ;




		$alignment ='text-align:'.$settings->infoTextAlign.';';


		$fontStyle = ($settings->infoFontStyle == "italic")?'font-style:italic;':'font-weight:'.$settings->infoFontStyle.';';

		$infoStyle = 'color:'.$settings->infoColor.'; font-size:'.$settings->infoFontSize.'px; line-height:'.$settings->infoLineHeight.'px; '.$fontStyle.' padding:'.$settings->infoPaddingTop.'px '.$settings->infoPaddingSides.'px; margin-bottom: 0px; padding-bottom:'.$settings->infoPaddingBottom.'px;'.$alignment.' border-bottom:'.$settings->infoBorderSize.'px solid '.$settings->infoBorderColor.'; overflow:hidden;' ;
		//margin-bottom:'.$settings->infoMarginBottom.'px; margin-top:'.$settings->infoMarginTop.'px;

		//details
		$fontStyle = ($settings->detailsFontStyle == "italic")?'font-style:italic;':'font-weight:'.$settings->detailsFontStyle.';';

		$detialsStyle = 'color:'.$settings->detailsColor.'; font-size:'.$settings->detailsFontSize.'px; line-height:'.$settings->detailsFontLineHeight.'px; '.$fontStyle.' padding:'.$settings->detailsPaddingTop.'px '.$settings->detailsPaddingSides.'px; padding-bottom:'.$settings->detailsPaddingBottom.'px; margin-bottom:'.$settings->detailsMarginBottom.'px; margin-top:'.$settings->detailsMarginTop.'px; border-bottom:'.$settings->detailsBorderSize.'px solid '.$settings->detailsBorderColor.';' ;

		$fontStyle = ($settings->detailsLabelStyle == "italic")?'font-style:italic;':'font-weight:'.$settings->detailsLabelStyle.';';

		$detialsLabelStyle = 'color:'.$settings->detailsLableColor.'; font-size:'.$settings->detailsLableSize.'px; line-height:'.$settings->detailsLabelLineHeight.'px; height:'.$settings->detailsLabelLineHeight.'px;'.$fontStyle;

		//costs
		$spotsStyle = '';
		$costStyle = '';


		$imgWidth = intval($thewidth)-intval($settings->imageMarginSides)*2;
		$imageStyle = 'width: 100%; margin: 0px '.$settings->imageMarginSides.'px; margin-top: '.$settings->imageMarginTop.'px; margin-bottom: '.$settings->imageMarginBottom.'px;';

		$calStyle = 'max-width:'.$originalWidth.'px;';

		$calBodyStyle = 'border: '.$settings->cal_sideBorder.'px solid '.$settings->cal_sideBorderColor.'; border-top: '.$settings->cal_topBorder.'px solid '.$settings->cal_topBorderColor.'; border-bottom: '.$settings->cal_bottomBorder.'px solid '.$settings->cal_bottomBorderColor.';';

		$calCloseStyle = 'border-top: '.$settings->cal_topBorder.'px solid '.$settings->cal_topBorderColor.';';


		$innerWidthEvent= $originalWidth;
		$eventContentStyling='width: 100%; padding:0px '.$settings->cal_paddingSides.'px;';

		$moreDatefontStyle = ($settings->infoFontStyle == "italic")?'font-style:italic;':'font-weight:'.$settings->infoFontStyle.';';

		$LocAlign='text-align:'.$settings->locationTextAlign.';';
		if ($settings->locationTextAlign=="center")
			$LocAlign .= 'margin:0 auto;';

		$fontStyle=($settings->locationFontStyle == "italic")?'font-style:italic;':'font-weight:'.$settings->locationFontStyle.';';
		$locationStyle = 'color:'.$settings->locationColor.'; font-size:'.$settings->locationFontSize.'px;'.$fontStyle.$LocAlign.';';

		return	array('btn'=>$btnStyle,'box'=>$boxstyle,
				"title"=>$titleStyle,
				"date"=>$dateStyle,
				"modal_date"=>$modal_dateStyle,
				"details"=>$detialsStyle,
				"detailsLabel"=>$detialsLabelStyle,
				"costStyle"=>$costStyle,
				"spotsStyle"=>$spotsStyle,
				"width"=>$originalWidth,
				"imageStyle"=>$imageStyle,
				"imgWidth"=>$imgWidth,
				"info"=>$infoStyle,
				"infoColor" =>$settings->infoColor,
				"infoPaddingSides" =>$settings->infoPaddingSides,
				"calStyle"=>$calStyle,
				"calBodyStyle"=>$calBodyStyle,
				"calCloseStyle"=>$calCloseStyle,
				'location' => $locationStyle,
				"innerWidth"=>$thewidth,
				"settings"=>$settings,
				"showPrice"=>$settings->showPrice,
				"bookedTxt"=>$settings->bookedTxt,
				"passedTxt"=>$settings->passedTxt,
				"btnTxt"=>$settings->btnTxt,
				"dateEnds"=>$settings->includeEndsOn,
				"datePaddingBottom"=>$settings->datePaddingBottom,
				"paypalAccount"=>$settings->paypalAccount,
				"imageCrop"=>$settings->imageCrop,
				"imageHeight"=>$settings->imageHeight,
				"infoHeight"=>$settings->infoMaxHeight,
				"displayWeekAbbr"=>$settings->cal_displayWeekAbbr,
				"displayMonthAbbr"=>$settings->cal_displayMonthAbbr,
				"startIn"=>$settings->cal_startIn,
				"calColor"=>$settings->cal_color,
				"cal_bgColor"=>$settings->cal_bgColor,
				"cal_boxColor"=>$settings->cal_boxColor,
				"cal_dateColor"=>$settings->cal_dateColor,
				"cal_titleBgColor"=>$settings->cal_titleBgColor,
				"eventContentStyling"=>$eventContentStyling,
				"cpp_header_image"=>$settings->cpp_header_image,
				"cpp_headerback_color"=>$settings->cpp_headerback_color,
				"cpp_headerborder_color"=>$settings->cpp_headerborder_color,
				"cpp_logo_image"=>$settings->cpp_logo_image,
				"cpp_payflow_color"=>$settings->cpp_payflow_color,
				"currency"=>$settings->currency,
				"sandbox"=>$settings->sandbox,
				"moreDatesOn"=>$settings->moreDateOn,
				"moreDatePassed"=>$settings->moreDatePassed,
				"moreDateUpcoming"=>$settings->moreDateUpcoming,
				"moreDatefontStyle"=>$settings->moreDateMarginTop,
				"moreDateColor"=>$settings->moreDateColor,
				"moreDateTextAlign"=>$settings->moreDateTextAlign,
				"moreDateSize"=>$settings->moreDateSize,
				"moreDateLineHeight"=>$settings->moreDateLineHeight,
				"moreDatefontStyle"=>$moreDatefontStyle,
				"moreDateHoverColor"=>$settings->moreDateHoverColor,
				"moreDateTxt"=>$settings->moreDateTxt,
				"moreDateSectionMarginBottom"=>$settings->moreDateSectionMarginBottom,
				"dateFormat"=>$settings->dateFormat,
				"timeFormat"=>$settings->timeFormat,
				"statsOnTxt"=>$settings->statsOnTxt,
				"endsOnTxt"=>$settings->endsOnTxt,
				"spotsLeftTxt"=>$settings->spotsLeftTxt,
				"modalSpotsLeftTxt"=>$settings->modalSpotsLeftTxt ,
				"modalQuantityTxt"=>$settings->modalQuantityTxt ,
				"modalSingleCostTxt"=>$settings->modalSingleCostTxt,
				"modalTotalCostTxt"=>$settings->modalTotalCostTxt,
				"eventBookedTxt"=>$settings->eventBookedTxt,
				"bookingTxt"=>$settings->bookingTxt,
				"ExpandTextTxt"=>$settings->ExpandTextTxt,
				"closeTextTxt"=>$settings->closeTextTxt
		);
	}

	public static function getSymbol($code = 'USD') {
	   $currencies = array(
			'RUB' => array('name' => "Russian ruble", 'symbol' => "руб", 'ASCII' => ""),
			'AUD' => array('name' => "Australian Dollar", 'symbol' => "A$", 'ASCII' => "A&#36;"),
			'CAD' => array('name' => "Canadian Dollar", 'symbol' => "$", 'ASCII' => "&#36;"),
			'CZK' => array('name' => "Czech Koruna", 'symbol' => "Kč", 'ASCII' => ""),
			'DKK' => array('name' => "Danish Krone", 'symbol' => "Kr", 'ASCII' => ""),
			'EUR' => array('name' => "Euro", 'symbol' => "€", 'ASCII' => "&#128;"),
			'HKD' => array('name' => "Hong Kong Dollar", 'symbol' => "$", 'ASCII' => "&#36;"),
			'HUF' => array('name' => "Hungarian Forint", 'symbol' => "Ft", 'ASCII' => ""),
			'ILS' => array('name' => "Israeli New Sheqel", 'symbol' => "₪", 'ASCII' => "&#8361;"),
			'JPY' => array('name' => "Japanese Yen", 'symbol' => "¥", 'ASCII' => "&#165;"),
			'MXN' => array('name' => "Mexican Peso", 'symbol' => "$", 'ASCII' => "&#36;"),
			'NOK' => array('name' => "Norwegian Krone", 'symbol' => "Kr", 'ASCII' => ""),
			'NZD' => array('name' => "New Zealand Dollar", 'symbol' => "$", 'ASCII' => "&#36;"),
			'PHP' => array('name' => "Philippine Peso", 'symbol' => "₱", 'ASCII' => ""),
			'PLN' => array('name' => "Polish Zloty", 'symbol' => "zł", 'ASCII' => ""),
			'GBP' => array('name' => "Pound Sterling", 'symbol' => "£", 'ASCII' => "&#163;"),
			'SGD' => array('name' => "Singapore Dollar", 'symbol' => "$", 'ASCII' => "&#36;"),
			'SEK' => array('name' => "Swedish Krona", 'symbol' => "kr", 'ASCII' => ""),
			'CHF' => array('name' => "Swiss Franc", 'symbol' => "CHF", 'ASCII' => ""),
			'TWD' => array('name' => "Taiwan New Dollar", 'symbol' => "NT$", 'ASCII' => "NT&#36;"),
			'THB' => array('name' => "Thai Baht", 'symbol' => "฿", 'ASCII' => "&#3647;"),
			'USD' => array('name' => "U.S. Dollar", 'symbol' => "$", 'ASCII' => "&#36;"),
			'TLR' => array('name' => "Turkish Lira (TLR)", 'symbol' => "₺", 'ASCII' => "&#8378;"),
			'TRY' => array('name' => "Turkish Lira (TRY)", 'symbol' => "₺", 'ASCII' => "&#8378;"),
			'MYR' => array('name' => "Malaysia Ringgit", 'symbol' => "RM", 'ASCII' => ""),
			'BRL' => array('name' => "Brazilian Rea", 'symbol' => "BRL", 'ASCII' => ""),
			'LEM' => array('name' => "Lempira", 'symbol' => "L.", 'ASCII' => ""),
			'IND' => array('name' => "Indonesian (Rp)", 'symbol' => "Rp", 'ASCII' => ""),
			'LTL' => array('name' => "Lithuanian Litas", 'symbol' => "Ltl", 'ASCII' => ""),
			'INR' => array('name' => "Indian Rupee", 'symbol' => "₹", 'ASCII' => ""),
			'ZAR' => array('name' => "South African", 'symbol' => "R", 'ASCII' => ""),
			'VEF' => array('name' => "Venezuelan Bolívar", 'symbol' => "Bs.", 'ASCII' => ""),
			'KRW' => array('name' => "Korea Won", 'symbol' => "&#65510;", 'ASCII' => "&#65510;"),
			'VND' => array('name' => "Vietnam", 'symbol' => "đ", 'ASCII' => ""),
			'GHC' => array('name' => "Ghana Cedis", 'symbol' => "GH¢", 'ASCII' => ""),
			'RWF' => array('name' => "Rwandan Franc", 'symbol' => "RWF", 'ASCII' => ""),
			'CRC' => array('name' => "Costa Rican Colon", 'symbol' => "₡", 'ASCII' => ""),
		);

		return (string) $currencies[$code]['symbol'];
  }

	// left for backwards compatibility
	public static function generateEmails($paymentID, $type="") {
		return EmailService::createEmailAndSend($paymentID, $type);
	}

	// left for legacy reasons but not used anymore
	public static function isValidEmail($email) {
	  return EmailService::isValidEmail($email);
	}

	public static function convertDateFormat($format){
		$old = array('m', 'n', 'Y', 'y', 'F', 'l', 'D', 'd', 'j', 'M', 'S');
    $new = array('%m','%m', '%Y', '%y', '%B', '%A', '%a', '%d', '%d', '%b', '');
    $format = str_replace($old, $new, $format);
    $format = str_replace('%%', '%', $format);
    return $format;
	}
}

?>
