<?php
  $overlayAlpha = intval($settings->popupOverlayAlpha);

  $loaderColor = $settings->cal_color;
  $loaderColor2 = '#FFF';

  $BookingFormSeperator = '1px solid rgba(255,255,255,.3)';
  $BookingForm_LetterSpace = '1px';

  // single padding
  $BookngForm_ContentPaddingVertical = 15;
  $BookngForm_ContentPaddingHorizental = 40;
  $BookngForm_ContentMarginVertical = 20;

  $BookngForm_BackgroundColor = $settings->modalOverlayColor;
  $BookngForm_BackgroundOpacity = intval($settings->popupOverlayAlpha)/100;

  $BookngForm_ContentBackgroundColor = 'rgba(0,0,0,0)';
  $BookngForm_TextColor = '#FFF';
  $BookingForm_ContentRadius = '5';


  function getBorderRadius($radius) {
    echo 'border-radius: '.$radius.'px; -webkit-border-radius: '.$radius.'px; -moz-border-radius: '.$radius.'px;';
  }

  function getTransition() {
    echo '-webkit-transition: all 0.3s ease; -moz-transition: all 0.3s ease; transition: all 0.3s ease;';
  }

  function hex2rgba($color, $opacity = false) {
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

  function getCardStyle() {
    global $wpdb;
    $settings = $wpdb->get_row( "SELECT * FROM " . EbpDatabase::getTableName("settings")." where id='3' ");
    $style = '';

    if ($settings->boxAlign == "true") {
      $boxAlign = 'margin: auto; margin-top: '.$settings->boxMarginTop.'px; margin-bottom: '.$settings->boxMarginBottom.'px;';
    }
    else {
      $boxAlign = 'margin: '.$settings->boxMarginTop.'px '.$settings->boxMarginSides.'px; margin-bottom: '.$settings->boxMarginBottom.'px;';
    }
      $style .= '.eventCardCnt{max-width:'.(intval($settings->boxWidth)+2*intval($settings->boxPaddingSides)).'px; padding:'.$settings->boxPaddingTop.'px '.$settings->boxPaddingSides.'px; padding-bottom: '.$settings->boxPaddingBottom.'px;  background-color:'.EventBookingHelpers::hex2rgba($settings->boxBgColor,1).';  -webkit-border-radius: '.$settings->boxBorderRadius.'px; -moz-border-radius: '.$settings->boxBorderRadius.'px; border-radius: '.$settings->boxBorderRadius.'px; border:'.$settings->boxBorder.'px solid'.$settings->boxBorderColor.'; }';
      $style .= '.eventCardCnt:not(.extended) {'.$boxAlign.'}';
      $style .= '.eventCardCnt:hover{ background-color:'.EventBookingHelpers::hex2rgba($settings->boxBgColor,.5).'; border-left: 5px solid '.$settings->btnBgColor.';}';

      $style .= '.eventCardCnt .cntHolder{max-width:'.$settings->boxWidth.'px; }';
      $style .= '.eventCardCnt .imageHolder{-webkit-border-radius: '.$settings->boxBorderRadius.'px; -moz-border-radius: '.$settings->boxBorderRadius.'px; border-radius: '.$settings->boxBorderRadius.'px;}';

    $detailsStyle = '';
    $imageHolder = '';
    if ($settings->eventCardShowThumbnail == "true") {

      $thumbWidth = intval($settings->eventCardThumbnailWidth);
      if ($thumbWidth < 0 ) $thumbWidth = 0;
      if ($thumbWidth > 100 ) $thumbWidth = 100;

      $imageHolder = 'width: '.$thumbWidth;
      $detailsStyle = 'max-width: ';
      if (strrpos($settings->eventCardThumbnailWidth, "%") > 0) {
        $imageHolder .= '%';
        $detailsStyle .= (100 - $thumbWidth).'%';
      } else {
        $imageHolder .= 'px';
        $possibleWidth = (intval($settings->boxWidth) - 2 * intval($settings->boxPaddingSides));
        $detailsStyle .= ($possibleWidth - $thumbWidth).'px';
      }

      $imageHolder .= ' !important;';
      $detailsStyle .= ' !important;';

    } else {
      $detailsStyle = "max-width: 100% !important;";
    }


    $style .= '.eventCardCnt:not(.extended) .imageHolder{'.$imageHolder.'}';
    $style .= '.eventCardCnt:not(.extended) .details{'.$detailsStyle.'}';

    $titleAignment='text-align:'.$settings->titleTextAlign.';';
    if ($settings->titleTextAlign == "center")
      $titleAignment .= 'margin:0 auto;';

    $fontStyle=($settings->titleFontStyle == "italic")?'font-style:italic;':'font-weight:'.$settings->titleFontStyle.';';

    $style .= '.eventCardCnt .details span.title{color:'.$settings->titleColor.'; font-size:'.$settings->titleFontSize.'px;'.$fontStyle.$titleAignment.'}';


    $LocAlign='text-align:'.$settings->locationTextAlign.';';
    if ($settings->locationTextAlign=="center")
      $LocAlign .= 'margin:0 auto;';

    $fontStyle=($settings->locationFontStyle == "italic")?'font-style:italic;':'font-weight:'.$settings->locationFontStyle.';';

    //background:url(../images/icon/location.png) 0px center no-repeat; padding-left: 20px;
    $style .= '.eventCardCnt .EBP--Location a{color:'.$settings->locationColor.'; font-size:'.$settings->locationFontSize.'px;'.$fontStyle.$LocAlign.'}';

    //details
    $fontStyle=($settings->detailsFontStyle == "italic")?'font-style:italic;':'font-weight:'.$settings->detailsFontStyle.';';

    $style .= '.eventCardCnt .eventDetails .price{color:'.$settings->detailsColor.'; font-size:'.$settings->detailsFontSize.'px;'.$fontStyle .';}';


    $fontStyle=($settings->detailsLabelStyle == "italic")?'font-style:italic;':'font-weight:'.$settings->detailsLabelStyle.';';

    $style .= '.eventCardCnt .eventDetails .passedEvent,.eventCardCnt .eventDetails .spots {color:'.$settings->detailsLableColor.'; font-size:'.$settings->detailsLableSize.'px;'.$fontStyle.'}';

    $boxAlign='margin: 0 auto; margin-top: '.$settings->btnMarginTop.'px; margin-bottom:'.$settings->btnMarginBottom.'px;';

    $style .= '.buyCnt a.EBP--BookBtn{background-color:'.$settings->btnBgColor.'; color:'.$settings->btnColor.'; font-size:'.$settings->btnFontSize.'px;  line-height:'.$settings->btnFontSize.'px; padding:'.$settings->btnTopPadding.'px '.$settings->btnSidePadding.'px; font-weight:'.$settings->btnFontType.';  -webkit-border-radius: '.$settings->btnBorderRadius.'px; -moz-border-radius: '.$settings->btnBorderRadius.'px;border-radius: '.$settings->btnBorderRadius.'px; border-top:'.$settings->btnBorder.'px solid'.$settings->btnBorderColor.';'.$boxAlign.'}';
    $style .= '.ebp_btn_people,.ebp_btn_people:hover{color:'.$settings->btnBgColor.';}';
    $style .= '';




    // EXTENDED
    if ($settings->boxAlign == "true") {
      $boxAlign = 'margin: 0 auto; margin-top: '.$settings->boxMarginTop.'px; margin-bottom: '.$settings->boxMarginBottom.'px;';
    } else {
      $boxAlign = 'margin: '.$settings->boxMarginTop.'px '.$settings->boxMarginSides.'px;';
    }


    $style .= '.eventCardExtendedCnt{max-width:'.(intval($settings->boxWidth)+2*intval($settings->boxPaddingSides)).'px; '.$boxAlign.' padding:0;}';
      $maxAllowedWidth = (intval($settings->boxWidth) + 2 * intval($settings->boxPaddingSides));


      $bgColor = EventBookingHelpers::hex2rgba($settings->boxBgColor, 1);
      $bgColorHover = EventBookingHelpers::hex2rgba($settings->boxBgColor, .5);

    if ($settings->boxAlign=="true") {
      $boxAlign='margin: 0 auto; margin-top: '.$settings->boxMarginTop.'px; margin-bottom: 0px;';
    } else {
      $boxAlign= 'margin: '.$settings->boxMarginTop.'px '.$settings->boxMarginSides.'px;';
    }

      $style .= '.eventCardCnt {max-width:'.$maxAllowedWidth.'px;'.$boxAlign.'padding:'.$settings->boxPaddingTop.'px '.$settings->boxPaddingSides.'px; padding-bottom: '.$settings->boxPaddingBottom.'px;  background-color:'.$bgColor.';  -webkit-border-radius: '.$settings->boxBorderRadius.'px; -moz-border-radius: '.$settings->boxBorderRadius.'px; border-radius: '.$settings->boxBorderRadius.'px; border:'.$settings->boxBorder.'px solid'.$settings->boxBorderColor.'; }';


      $style .= '.eventCardCnt:hover{ background-color:'.$bgColorHover.'; border-left: 5px solid '.$settings->btnBgColor.';}';

      $style .= '.eventCardCnt .arrow-down {border-top-color: '.$settings->btnBgColor.';}';



      $style .= '.eventCardCnt .cntHolder{max-width:'.$settings->boxWidth.'px; }';
      $style .= '.eventCardCnt .imageHolder{-webkit-border-radius: '.$settings->boxBorderRadius.'px; -moz-border-radius: '.$settings->boxBorderRadius.'px; border-radius: '.$settings->boxBorderRadius.'px;}';

      $detailsStyle = '';
      $imageHolder = '';
      if ($settings->eventCardShowThumbnail == "true") {

        $thumbWidth = intval($settings->eventCardExpandThumbnailWidth);
        if ($thumbWidth < 0 ) $thumbWidth = 0;
        if ($thumbWidth > 100 ) $thumbWidth = 100;

        $imageHolder = 'width: '.$thumbWidth;
        $detailsStyle = 'max-width: ';
        if (strrpos($settings->eventCardExpandThumbnailWidth, "%") > 0) {
          $imageHolder .= '%';
          $detailsStyle .= (100 - $thumbWidth).'%';
        } else {
          $imageHolder .= 'px';
          $possibleWidth = (intval($settings->boxWidth) - 2 * intval($settings->boxPaddingSides));
          $detailsStyle .= ($possibleWidth - $thumbWidth).'px';
        }

        $imageHolder .= ' !important;';
        $detailsStyle .= ' !important;';

      } else {
        $detailsStyle = "max-width: 100% !important;";
      }


    $style .= '.eventCardExtendedCnt .imageHolder{'.$imageHolder.'}';
    $style .= '.eventCardExtendedCnt .details{'.$detailsStyle.'}';



    $titleAignment = 'text-align:'.$settings->titleTextAlign.';';
    if ($settings->titleTextAlign == "center")
      $titleAignment.='margin:0 auto;';

    $fontStyle=($settings->titleFontStyle == "italic")?'font-style:italic;':'font-weight:'.$settings->titleFontStyle.';';

    $style .= '.eventCardCnt .details span.title{color:'.$settings->titleColor.'; margin-bottom: '.$settings->titleMarginBottom .'px; font-size:'.$settings->titleFontSize.'px;'.$fontStyle.$titleAignment.'}';



    $LocAlign = 'text-align:'.$settings->locationTextAlign.';';
    if ($settings->locationTextAlign == "center")
      $titleAignment.='margin:0 auto;';

    $fontStyle = ($settings->locationFontStyle == "italic")?'font-style:italic;':'font-weight:'.$settings->locationFontStyle.';';




    //details
    $fontStyle = ($settings->detailsFontStyle == "italic")?'font-style:italic;':'font-weight:'.$settings->detailsFontStyle.';';

    $style .= '.eventCardCnt .eventDetails .price{color:'.$settings->detailsColor.'; font-size:'.$settings->detailsFontSize.'px;'.$fontStyle .';}';


    $fontStyle=($settings->detailsLabelStyle == "italic")?'font-style:italic;':'font-weight:'.$settings->detailsLabelStyle.';';

    $style .= '.eventCardCnt .eventDetails .passedEvent,.eventCardCnt .eventDetails .spots {color:'.$settings->detailsLableColor.'; font-size:'.$settings->detailsLableSize.'px;'.$fontStyle.'}';

    $boxAlign = 'margin: 0 auto; margin-top: '.$settings->btnMarginTop.'px; margin-bottom:'.$settings->btnMarginBottom.'px;';

    $style .= '.buyCnt a.EBP--BookBtn{background-color:'.$settings->btnBgColor.'; color:'.$settings->btnColor.'; font-size:'.$settings->btnFontSize.'px;  padding:'.$settings->btnTopPadding.'px '.$settings->btnSidePadding.'px; font-weight:'.$settings->btnFontType.';  -webkit-border-radius: '.$settings->btnBorderRadius.'px; -moz-border-radius: '.$settings->btnBorderRadius.'px;border-radius: '.$settings->btnBorderRadius.'px; border-top:'.$settings->btnBorder.'px solid'.$settings->btnBorderColor.';'.$boxAlign.'}';
    $style .= '.ebp_btn_people,.ebp_btn_people:hover{color:'.$settings->btnBgColor.';}';

    $style .= '.eventCardExtendedCnt .eventDescription{width:100%; background-color:'.EventBookingHelpers::hex2rgba($settings->cardDescriptionBackColor,1).'; padding:0px;}';

    //info
    $style .= '.eventCardExtendedCnt .eventDescription .info a.expand{background-color:'.EventBookingHelpers::hex2rgba($settings->cardDescriptionBackColor,.9).';
-webkit-box-shadow:  0px -5px 10px 0px '.EventBookingHelpers::hex2rgba($settings->cardDescriptionBackColor,.8).';
-moz-box-shadow:  0px -5px 10px 0px '.EventBookingHelpers::hex2rgba($settings->cardDescriptionBackColor,.8).';
box-shadow:  0px -5px 10px 0px rgba'.EventBookingHelpers::hex2rgba($settings->cardDescriptionBackColor,.8).'
}';

    $alignment='text-align:'.$settings->infoTextAlign.';';

    $fontStyle = ($settings->infoFontStyle == "italic")?'font-style:italic;':'font-weight:'.$settings->infoFontStyle.';';

    $style .= '.eventCardExtendedCnt .eventDescription .info{color:'.$settings->infoColor.'; font-size:'.$settings->infoFontSize.'px; line-height:'.$settings->infoLineHeight.'px;  '.$fontStyle.' padding:'.$settings->infoPaddingTop.'px '.$settings->infoPaddingSides.'px; padding-bottom:'.$settings->infoPaddingBottom.'px'.$alignment.'}' ;

    $style .= '.eventCardExtendedCnt .eventDescription .info a.expand{color:'.$settings->infoColor.'; margin-left: -'.$settings->infoPaddingSides.'px;}' ;

    $style .= '.eventCardExtendedCnt .eventDescription .infoTitle{color:'.$settings->infoTitleColor.';  font-size:'.$settings->infoTitleFontSize.'px; line-height:'.$settings->infoTitleFontSize.'px; margin:0px 0px; padding:'.$settings->infoMarginTop.'px '.$settings->infoPaddingSides.'px;}';


    $style .= '.eventCardExtendedCnt .eventDescription .cntForSpace{margin:0;padding:0; width:100%; height:'.$settings->infoPaddingBottom.'px; border-bottom:'.$settings->infoBorderSize.'px solid '.$settings->infoBorderColor.'; }';
    //margin-bottom:'.$settings->infoMarginBottom.'px;

    //image

    $style .= '.eventCardExtendedCnt .eventDescription  .eventImage{margin:0px '.$settings->imageMarginSides.'px; margin-top: '.$settings->imageMarginTop.'px; margin-bottom: '.$settings->imageMarginBottom.'px;}';


    return $style;
  }
?>
