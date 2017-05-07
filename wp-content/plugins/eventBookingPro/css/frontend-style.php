<?php
  header("Content-type: text/css; charset: UTF-8");

  require_once( '../../../../wp-load.php' );
  global $wpdb;

  include_once dirname( __FILE__ ) . '../../include.php';
  $settings = $wpdb->get_row("SELECT * FROM ".EventBookingHelpers::getTableName('settings')." where id='1' ");

  include_once dirname( __FILE__ ) . '/vars.php';


  $GoogleCalToRight = ($settings->addToCalendarAlign == "right")?'right:0;':'';
?>

.ebp-prep {
  display: inline-block;
}
.EBP--Stripped {
  text-decoration: line-through;
  font-size: 0.8em !important;
  font-style: italic;
  margin-right: 5px;
  opacity: .8;
}

/* MORE DATES */
.ebpBox .moreDates {
  margin-top: <?php echo $settings->moreDateMarginTop;?>px;
}

.moreDates a {
  color: <?php echo $settings->moreDateColor;?>;
  float: <?php echo $settings->moreDateTextAlign;?>;
  font-size: <?php echo $settings->moreDateSize;?>px;
  line-height:120%;

  <?php
    $moreDatefontStyle = ($settings->infoFontStyle == "italic") ? 'font-style:italic;': 'font-weight:'.$settings->infoFontStyle.';';
    echo $moreDatefontStyle;
  ?>
}

.moreDates a:hover{
  color: <?php echo $settings->moreDateHoverColor;?>;
}

.eventDisplayCnt:not(.isCalendar) .info a.expand{
  background-color: <?php echo hex2rgba($settings->boxBgColor,.9); ?>;
  -webkit-box-shadow:  0px -5px 10px 0px  <?php echo hex2rgba($settings->boxBgColor,.8); ?>;
  -moz-box-shadow:  0px -5px 10px 0px  <?php echo hex2rgba($settings->boxBgColor,.8); ?>;
  box-shadow:  0px -5px 10px 0px rgba <?php echo hex2rgba($settings->boxBgColor,.8); ?>;
}

.ebpBox .info a.expand {
  color: <?php echo $settings->infoColor;?>;
  margin-left: -<?php echo $settings->infoPaddingSides;?>px;
}

.ebpBox .ebp_btn_people,
.ebpBox .ebp_btn_people:hover {
  color: <?php echo $settings->btnBgColor; ?>;
}

.ebpBox .addToCalDiv {
  <?php echo $GoogleCalToRight;?>
}

a.addToGoogleCal {
  color:<?php echo $settings->addToCalendarTextColor; ?>;
  font-size:<?php echo $settings->addToCalendarTextFontSize; ?>px;

  <?php echo ($settings->addToCalendarTextFontStyle == "italic") ? 'font-style:italic;' : 'font-weight:'.$settings->addToCalendarTextFontStyle.';'; ?>

  margin-left:<?php echo $settings->addToCalendarMarginSide; ?>px;
  margin-right:<?php echo $settings->addToCalendarMarginSide; ?>px;
  margin-bottom:<?php echo $settings->addToCalendarMarginBottom; ?>px;
}

a.addToGoogleCal:hover{
  color:<?php echo $settings->addToCalendarTextHoverColor; ?>;
}

/* TODO... check if for evenBox or eventCard or all */
.eventDisplayCnt .dateCnt .datelabel {
  display:inline-block;
  margin-right: 10px;
  <?php
    $fontStyle = ($settings->dateLabelStyle == "italic") ? 'font-style:italic;' : 'font-weight:'.$settings->dateLabelStyle.';';
    echo $fontStyle;
  ?>
  color: <?php echo $settings->dateLableColor; ?>;
  font-size: <?php echo $settings->dateLableSize; ?>px;
  line-height: <?php echo $settings->dateLabelLineHeight; ?>px;
}

<?php
  if (!($overlayAlpha == 100 &&  strcasecmp($settings->modalMainColor, $settings->modalOverlayColor) == 0)) {
  echo '.EBP--content, .EBP--show.ebp-fullpage .EBP--content {background:<?php echo $settings->modalMainColor; ?>;}';
  }
?>

.ebpBox .Ebp--EventDetails {
  color: <?php echo $settings->detailsColor?> ;
  font-size: <?php echo $settings->detailsFontSize?>px;
  line-height: <?php echo $settings->detailsFontLineHeight?>px;
  <?php
    $fontStyle = ($settings->detailsFontStyle == "italic") ? 'font-style:italic; ' : 'font-weight:'.$settings->detailsFontStyle.';';
    echo $fontStyle;
  ?>
  padding: <?php echo $settings->detailsPaddingTop.'px '.$settings->detailsPaddingSides?>px;
  padding-bottom: <?php echo $settings->detailsPaddingBottom?>px;
  margin-bottom: <?php echo $settings->detailsMarginBottom?>px;
  margin-top: <?php echo $settings->detailsMarginTop?>px;
  border-bottom: <?php echo $settings->detailsBorderSize.'px solid '.$settings->detailsBorderColor?> ;' ;
}

.ebpBox .EBP--Location {
  background:url(../images/icon/location.png) 20px center no-repeat;
  padding: 20px 40px;
  color:<?php echo $settings->locationColor;?>;
  font-size:<?php echo $settings->locationFontSize;?>px;
  text-align:<?php echo $settings->locationTextAlign; ?>;
  <?php
    if ($settings->locationTextAlign=="center") {
      echo 'margin: 0 auto;';
    }

    $fontStyle=($settings->locationFontStyle == "italic")?'font-style:italic;':'font-weight:'.$settings->locationFontStyle.';';
    echo $fontStyle;
  ?>
}


.ebpBox .Ebp--EventDetails {
 text-align: center;
}
.ebpBox .Ebp--EventDetails .Ebp--Spots {
  display:inline-block;
  text-align:center;
  width: 50%;
  margin: 0 auto;
  min-width: 150px;
  vertical-align: middle;
}


.ebpBox .Ebp--EventDetails .Ebp--Spots span{
  vertical-align:middle;
  position:relative;
  text-align:left;
  margin-left: 6px;
  display:inline-block;

  color: <?php echo $settings->detailsLableColor ?>;
  font-size: <?php echo $settings->detailsLableSize ?>px;
  line-height: <?php echo $settings->detailsLabelLineHeight ?>px;
  height: <?php echo $settings->detailsLabelLineHeight ?>px;

  <?php
    $fontStyle = ($settings->detailsLabelStyle == "italic")?'font-style:italic;':'font-weight:'.$settings->detailsLabelStyle.';';
    echo $fontStyle;
  ?>
}

.ebpBox .Ebp--EventDetails .Ebp--Price {
  display: inline-block;
  text-align: center;
  width: 50%;
  margin: 0 auto;
  min-width: 150px;
}

.ebpBox .Ebp--EventDetails .Ebp--SubTickets {
  width: 50%;
  min-width: 150px;
  display: inline-block;
  vertical-align: middle;
}
.ebpBox .Ebp--EventDetails .Ebp--SubTicket {
  width: 100%;
  font-size: 14px;
}

.ebpBox .Ebp--PassedEvent{
  display:inline-block;
  width:50%;
  text-align:center;
}

.ebpBox .multipleTickets .Ebp--TicketName {
  text-align: left;
  font-size: 16px;
}



/******************************************************************
========================  Book btn STYLE ==========================
******************************************************************/

.ebpBox .EBP--BookBtn,
.eventCardCnt .EBP--BookBtn,
.EBP--GiftCard .EBP--BookBtn {
  cursor:pointer;
  display:inline-block;
  margin:0 auto;
  font-family:inherit;
  text-align:center;
  vertical-align:middle;
  text-decoration:none;
  -webkit-transition: 0.3s ease;
  -moz-transition: 0.3s ease;
  -o-transition: 0.3s ease;
  transition: 0.3s ease;
  -webkit-backface-visibility: hidden;
}

.ebpBox .EBP--BookBtn,
.EBP--GiftCard .EBP--BookBtn  {
  height: auto;
  background-color: <?php echo $settings->btnBgColor; ?>;
  color: <?php echo $settings->btnColor; ?>;
  font-size: <?php echo $settings->btnFontSize; ?>px;
  line-height: <?php echo $settings->btnLineHeight; ?>px;
  padding: <?php echo $settings->btnTopPadding.'px '.$settings->btnSidePadding; ?>px;
  font-weight: <?php echo $settings->btnFontType; ?>;
  <?php echo getBorderRadius($settings->btnBorderRadius); ?>
  border-top: <?php echo $settings->btnBorder.'px solid'.$settings->btnBorderColor.';'; ?>
}


.eventCardCnt .eventDetails .EBP--BookBtn:hover,
.ebpBox .EBP--BookBtn:hover,
.EBP--GiftCard .EBP--BookBtn:hover {
  opacity:0.7;
}

.ebpBox .EBP--BookBtn.deactive,
.eventCardCnt .EBP--BookBtn.deactive,
.EBP--GiftCard .EBP--BookBtn.deactive {
  opacity: 0.7;
  cursor: default;
 }

.ebpBox .EBP--BookBtn span,
.EBP--GiftCard .EBP--BookBtn span {
  font-size: .8em;
  font-style: italic;
}


/******************************************************************
========================  Gift Card STYLE ==========================
******************************************************************/



/******************************************************************
========================  Calendar STYLE ==========================
******************************************************************/

.EBP--CalendarBlocker{
  display:none;
  width:100%;
  height:100%;
  position:absolute;
  top:0;
  left:0;
  background:rgba(255,255,255,0.7);
  z-index:100;
}


.calExample {
  padding: 0 30px 50px 30px;
  width: 100%;
  max-width: 600px;
  margin: 0 auto;
}

.EBP--Calendar *,
.EBP--Calendar *:after,
.EBP--Calendar *:before {
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  padding: 0;
  margin: 0;
}

.EBP--Calendar {
  width: 100%;
  height: 100%;
}

.isCalendar{
  margin:0 auto;
}


.EBP--CalendarLoaderCnt {
  width: 100%;
  height: 100%;
  position: absolute;
  top:0;
  left:0;
}



.EBP--CalendarContainer {
  position: relative;
  width: auto;
  padding: 30px;
  background: #f6f6f6;
  box-shadow: inset 0 1px rgba(255,255,255,0.8);
  background-color: <?php echo $settings->cal_bgColor; ?>;
}


.EBP--CalendarHead {
  background: transparent;
  font-weight: bold;
  text-transform: uppercase;
  font-size: 12px;
  height: 30px;
  line-height: 30px;
  color: <?php echo $settings->cal_color ?>;
}

.EBP--Header {
  padding: 5px 10px 10px 20px;
  position: relative;
  border-bottom: 1px solid #ddd;
  background-color: <?php echo $settings->cal_titleBgColor; ?>;
}

.EBP--Header h2,
.EBP--Header h3 {
  text-align: center;
  text-transform: uppercase;
}

.EBP--Header h2 {
  color: #495468;
  font-weight: 300;
  font-size: 18px;
  margin-top: 10px;
}

.EBP--Header h3 {
  font-size: 10px;
  font-weight: 700;
  color: #b7bbc2;
}

.EBP--Header nav span {
  position: absolute;
  top: 17px;
  width: 30px;
  height: 30px;
  color: transparent;
  cursor: pointer;
  margin: 0 1px;
  font-size: 20px;
  line-height: 30px;
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

.EBP--Header nav span:first-child {
  left: 5px;
}

.EBP--Header nav span:last-child {
  right: 5px;
}

.EBP--Header nav span:before {
  font-family: 'fontawesome-selected';
  position: absolute;
  text-align: center;
  width: 100%;
  color: <?php echo $settings->cal_color ?>;
}

.EBP--Header .EBP--Prev:before {
  content: '\25c2';
}

.EBP--Header .EBP--Next:before {
  content: '\25b8';
}

.EBP--Header .EBP--Prev:hover:before,
.EBP--Header .EBP--Next:hover:before {
  color: #495468;
}

.EBP--Month {
  margin: 15px 0 0px;
}
.EBP--Year {
  font-size: 12px; margin:0;
}



.EBP--CalendarWrap {
  margin: 10px auto;
  position: relative;
  overflow: hidden;
}

.EBP--Inner {
  background: #fff;
}


.EBP--Inner:before,
.EBP--Inner:after  {
  content: '';
  width: 99%;
  height: 50%;
  position: absolute;
  background: #f6f6f6;
  bottom: -4px;
  left: 0.5%;
  z-index: -1;
  box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

.EBP--Inner:after {
  content: '';
  width: 98%;
  bottom: -7px;
  left: 1%;
  z-index: -2;
}



.EBP--CalendarBody {
  position: relative;
  width: 100%;
  height: 100%;
  height: -moz-calc(100% - 30px);
  height: -webkit-calc(100% - 30px);
  height: calc(100% - 30px);
  border: 1px solid #ddd;
}



.EBP--CalendarRow {
  width: 100%;
  border-bottom: 1px solid #ddd;
  position: relative;
}

.EBP--CalendarRow:last-child {
  border-bottom: none;
}

.EBP--CalendarRow-events {
  width: 100%;
  position: absolute;
  top: 0;
  height: 100%;
}

.EBP--Calendar-four-rows .EBP--CalendarRow{
  height: 25%;
}

.EBP--Calendar-five-rows .EBP--CalendarRow {
  height: 20%;
}

.EBP--Calendar-six-rows .EBP--CalendarRow{
  height: 16.66%;
  height: -moz-calc(100%/6);
  height: -webkit-calc(100%/6);
  height: calc(100%/6);
}



.EBP--CalendarCell,
.EBP--CalendarHead > div {
  float: left;
  height: 100%;
  width:  14.28%; /* 100% / 7 */
  width: -moz-calc(100%/7);
  width: -webkit-calc(100%/7);

  position: relative;
}

.EBP--CalendarCell {
  padding: 4px;
}

/* IE 9 is rounding up the calc it seems */
.ie9 .EBP--CalendarCell,
.ie9 .EBP--CalendarHead > div {
  width:  14.2%;
}


.EBP--CalendarHead > div {
  text-align: center;
}


.EBP--CalendarRow > .EBP--CalendarCell {
  border-right: 1px solid #ddd;
  background:<?php echo $settings->calEventDayColor; ?>;
}
.EBP--CalendarRow > .EBP--CalendarCell:nth-child(7) {
  border-right: none;
}

.EBP--CalendarRow .EBP--CalendarCell:empty {
  background: transparent;
}

.EBP--CalendarCell:last-child,
.EBP--CalendarHead > div:last-child {
  border-right: none;
}


.showEventsDirectly .EBP--CalendarCell {
  padding: 0;
}

.EBP--CalendarCellWeekDay {
  display: none;
}

.EBP--CalendarCellDate {
  position: absolute;
  font-size: 18px;
  line-height: 20px;
  background: rgba(255,255,255,0.5);
  top: 50%;
  left: 50%;
  text-align: center;
  width: 30px;
  margin: -10px 0 0 -15px;
  font-weight: 400;
  pointer-events: none;

  color:<?php echo $settings->cal_dateColor;?>;
}


.showEventsDirectly .EBP--CalendarCellDate {
  font-size: 14px;
  line-height: 16px;
  padding: 0px 2px;
  width: auto;
  margin: 0;
  top: 8px;
  left: 5px;
  text-align: center;
  pointer-events: none;
  z-index: 101;
  background: <?php echo $settings->calEventDayColor; ?>;
  opacity: .8;
  <?php echo getTransition(); ?>
}


.EBP--CalendarRow  .EBP--CalendarCellToday {
  background-color: <?php echo $settings->calTodayColor ?>;
  /*border: 1px solid <?php echo $settings->calTodayColor ?>;*/
  /*box-shadow: inset 0 -1px 1px rgba(0,0,0,0.1);*/
}
.EBP--CalendarRow  .EBP--CalendarCellToday .EBP--CalendarCellDate {
  background: none;
}
.showEventsDirectly .EBP--CalendarRow  .EBP--CalendarCellToday .EBP--CalendarCellDate {
  background: <?php echo $settings->calEventDayColor; ?>;
}
.EBP--CalendarRow  .EBP--CalendarCellToday:hover{
  background-color: <?php echo $settings->calTodayColor; ?>;
}

.EBP--CalendarCellToday.EBP--CalendarCell--hasContent:after {
  color: rgba(0,0,0,0.1);
}

.EBP--CalendarCellToday.EBP--CalendarCell--hasContent:hover:after{
  color: #fff;
}


.EBP--CalendarCell--hasContent{
  background: <?php echo $settings->calEventDayColor; ?>;
  cursor: pointer;
}
.EBP--CalendarCell--hasContent:hover {
  background: <?php echo $settings->calEventDayColorHover; ?>;
  color: <?php echo $settings->calEventDayDotColorHover; ?>;
}


.EBP--CalendarCell--hasContent:after {
  content: '\00B7';
  text-align: center;
  margin-left: -10px;
  position: absolute;
  color: <?php echo $settings->calEventDayDotColor; ?>;
  font-size: 70px;
  line-height: 20px;
  left: 50%;
  bottom: 3px;
}

.EBP--CalendarCell--hasContent:hover:after {
  color: <?php echo $settings->calEventDayDotColorHover; ?>;
}

.showEventsDirectly .EBP--CalendarCell--hasContent:after {
  content: '';
  margin: 0;
  position: relative;
}


.EBP--CalendarContent {
  display: block;
  white-space: normal;
  z-index: 98;
  font-size: 14px;
  line-height: 16px;
  text-align: center;
  width: 90%;
  height: auto;
  padding: 5px 10px;
  margin: 5%;
  box-sizing: border-box;
  position: absolute;
  bottom: 0px;
  max-height: 90%;
  opacity: 0.8;
  overflow: hidden;
  cursor: pointer;

  <?php echo getBorderRadius(5); ?>;
  <?php echo getTransition();?>;

  color: <?php echo $settings->calEventDayColor; ?>;
  background-color: <?php echo $settings->calEventDayDotColorHover; ?>;
}

.EBP--CalendarContent:hover{
  opacity: 1;
  max-height: none;
  z-index: 102;
}

/*.EBP--CalendarSpreadWrapper {

}

.EBP--CalendarSpreadWrapper:hover{

}
*/

.EBP--SpreadEvent {
  font-size: 13px;
  line-height: 13px;
  padding: 4px 5px;
  height: 22px;
  text-align: left;
  box-sizing: border-box;
  color: <?php echo $settings->calEventDayColor; ?>;
  background-color: <?php echo $settings->calEventDayDotColorHover; ?>;
  <?php echo getBorderRadius(5); ?>;
  margin-top: 5px;
  display: block;
  white-space: nowrap;
  text-overflow: ellipsis;
  opacity: 0.8;
  cursor: pointer;
  z-index: 100;
  margin-left: 6%;
  overflow: hidden;
}

.EBP--SpreadEvent:hover {
  opacity: 1;
}
.EBP--SpreadEvent:first-child {
  margin-top: 25px;
}

.EBP--SpreadEvent--Continues {
  border-top-right-radius: 0px; webkit-border-top-right-radius: 0px; -moz-border-top-right-radius: 0px;
  border-bottom-right-radius: 0px; webkit-border-bottom-right-radius: 0px; -moz-border-bottom-right-radius: 0px;
}


.EBP--SpreadEvent--Continuation {
  border-top-left-radius: 0px; webkit-border-top-left-radius: 0px; -moz-border-top-left-radius: 0px;
  border-bottom-left-radius: 0px; webkit-border-bottom-left-radius: 0px; -moz-border-bottom-left-radius: 0px;
  margin-left: 0;
}


.EBP--SpreadEvent.EBP--SpreadEvent--Empty {
  background: transparent;
  cursor: default;
  z-index: 0;
  pointer-events: none
}

.EBP--CalendarCell--Spread {
  float: right;
}


.EBP--CalendarSpots{
  font-size: 10px;
  line-height: 15px;
  font-style: normal;
  opacity: .8;
}

.EBP--CellEvent {
  display: block;
  padding: 4px 0px;
  margin-bottom: 4px;
  font-size: 13px;
  line-height: 15px;
}


.EBP--CellEvent:last-child {
  margin-bottom: 0px;
}

.ebp-qtip {
  background: <?php echo $settings->calEventDayDotColorHover; ?>;
}


.EBP--CalendarEventContent {
  height: 100%;
  position: absolute;
  z-index: 104;
  top: 0px;
  left: 0px;
  text-align: center;

  width: 100%;
  background-color: <?php echo $settings->cal_bgColor; ?>;
}

.calanderCnt {
  padding:10px <?php echo $settings->cal_paddingSides;?>px;
}


.EBP--CalendarEventContent .eventClose {
  position: absolute;
  top: 25px;
  right: 10px;
  width: 20px;
  height: 20px;
  line-height: 20px;
  font-size:18px;
  text-align: center;
  cursor: pointer;
  padding: 0;
  vertical-align:middle;

  opacity:1;

  <?php echo getTransition(); ?>

  background:url(../images/icon/close.png) center center no-repeat;
  background-color: <?php echo $settings->cal_color ?>;
  border: 1px solid <?php echo $settings->cal_color ?>;
}
.EBP--CalendarEventContent .eventClose:hover {
  opacity:0.5;
}

.EBP--CalendarEventContent h4 {
  background-color: <?php echo $settings->cal_titleBgColor; ?>;
  text-transform: uppercase;
  font-size: 14px;
  font-weight: 300;
  letter-spacing: 3px;
  color: #777;
  padding: 20px;
  border-bottom: 1px solid #ddd;
  box-shadow: 0 1px rgba(255,255,255,0.9);
  margin-bottom:0px;
  margin-top:25px;
}

.CalendarContainer-WeeklyView,
.EBP--Inner:after {
  background-color: <?php echo $settings->cal_bgColor; ?>;
}

.EBP--CalendarContainer-WeeklyView .EBP--CalendarRow > .EBP--CalendarCell {
  background: none;
}

.EBP--CalendarContainer-WeeklyView .EBP--CalendarRow > .EBP--CalendarCell.hasEvents {
  background: <?php echo $settings->calEventDayColor; ?>;
}



/* Events List */
.filterTags a, .filterTags span {
  border-radius: <?php $settings->eventsListFilterBorderRadius; ?>px;
  background-color: <?php $settings->eventsListFilterColor; ?>;
  color: <?php $settings->eventsListFilterTextColor; ?>;
  font-size: <?php $settings->eventsListFilterFontSize; ?>px;
  padding: <?php $settings->eventsListFilterPaddingVertical.'px '.$settings->eventsListFilterPaddingSides;?>px;
}

/* CARDS */
<?php echo getCardStyle(); ?>


/* Modal */
.EBP--perspective,
.EBP--perspective body {
  height: 100%;
  overflow: hidden;
}

.EBP--perspective body  {
  background: #222;
  -webkit-perspective: 600px;
  -moz-perspective: 600px;
  perspective: 600px;
}

.EBP--overlay {
  position: fixed;
  width: 100%;
  height: 100%;
  visibility: hidden;
  top: 0;
  left: 0;
  z-index: 99000;
  opacity: 0;

  <?php echo getTransition();?>;
  cursor: url(../images/icon/closeBig.png), auto;

  background-color: <? echo $BookngForm_BackgroundColor; ?>;
}

.EBP--modal {
  position: fixed;
  top: 50%;
  left: 50%;
  width: 960px;
  max-width: 100%;
  height: auto;
  z-index: 99900;
  visibility: hidden;
  -webkit-backface-visibility: hidden;
  -moz-backface-visibility: hidden;
  backface-visibility: hidden;
  -webkit-transform: translateX(-50%) translateY(-50%);
  -moz-transform: translateX(-50%) translateY(-50%);
  -ms-transform: translateX(-50%) translateY(-50%);
  transform: translateX(-50%) translateY(-50%);

}

.EBP--modal.fullHeight {
  height: 100%;
}

.EBP--show {
  visibility: visible;
  -webkit-backface-visibility: visible;
  -moz-backface-visibility: visible;
  backface-visibility: visible;
}

.EBP--mobilePage {
  position: relative;
  top: 0;
  left: 0;
  width: auto;
  max-width: 100%;
  height: auto;
  z-index: 1;
  visibility: visible;
  -webkit-backface-visibility: visible;
  -moz-backface-visibility: visible;
  backface-visibility: visible;
  -webkit-transform: none;
  -moz-transform: none;
  -ms-transform: none;
  transform: none;
}

.EBP--mobilePage.EBP--show {
  display: block;
}

.EBP--show ~ .EBP--overlay {
  opacity: <?php echo $BookngForm_BackgroundOpacity; ?>;
  visibility: visible;
}

/* Content styles */
.EBP--content {
  position: relative;
  margin: <?php echo $BookngForm_ContentMarginVertical;?>px auto;
  padding: <?php echo $BookngForm_ContentPaddingVertical.'px '.$BookngForm_ContentPaddingHorizental.'px'; ?>;
  text-align: center;

  background: <?php echo $BookngForm_ContentBackgroundColor; ?>;
  <?php echo getBorderRadius($BookingForm_ContentRadius); ?>;

  height: -moz-calc(100% - <?php echo intval($BookngForm_ContentPaddingVertical) * 2 + intval($BookngForm_ContentMarginVertical) * 2; ?>px);
  height: -webkit-calc(100% - <?php echo intval($BookngForm_ContentPaddingVertical) * 2 + intval($BookngForm_ContentMarginVertical) * 2; ?>px);
  height: -o-calc(100% - <?php echo intval($BookngForm_ContentPaddingVertical) * 2 + intval($BookngForm_ContentMarginVertical) * 2; ?>px);
  height: calc(100% - <?php echo intval($BookngForm_ContentPaddingVertical) * 2 + intval($BookngForm_ContentMarginVertical) * 2; ?>px);

  -webkit-transform: scale(0.8);
  -moz-transform: scale(0.8);
  -ms-transform: scale(0.8);
  transform: scale(0.8);
  opacity: 0;
  text-align: center;

  <?php echo getTransition();?>;
}

.EBP--show .EBP--content {
  -webkit-transform: scale(1);
  -moz-transform: scale(1);
  -ms-transform: scale(1);
  transform: scale(1);
  opacity: 1;
}

.EBP--show .EBP--content.EBP--hiddenState  {
  -webkit-transform: scale(0.6);
  -moz-transform: scale(0.6);
  -ms-transform: scale(0.6);
  transform: scale(0.6);
  opacity: 0;
}


.EBP--content * {
  -webkit-box-sizing: content-box;
  -moz-box-sizing: content-box;
  box-sizing: content-box;
}

.EBP--content .topBorder,
.EBP--content .bottomBorder {
 border-bottom: 1px solid rgba(255,255,255,0.3);
 display: block;
 width: 100%;
}

.EBP--content .topBorder {
  margin-bottom:10px;
}

.EBP--content .bottomBorder {
  margin-top:10px;
}


.EBP--content ul li{
  width:100%;
}

.EBP--content > div p {
  margin: 0;
  padding: 10px 0;
}

.EBP--content > div ul {
  margin: 0;
  padding: 0 0 30px 20px;
}

.EBP--content > div ul li {
  padding: 5px 0;
}

.EBP--content button {
  display: block;
  margin: 0 auto;
  font-size: 1em;
}

.EBP--content .Modal--NoBuy {
  display: none;
  opacity: .8;
  font-size: 1.2em;
}


.EBP--content input,
.EBP--content textarea {
  text-align:left;
  outline:0; border:none;
  letter-spacing: 1px;

  <?php echo getTransition();?>;
  <?php echo getBorderRadius(3);?>;

   box-shadow: -1px -1px 0px 0px rgba(0,0,0,0.2);
   -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;
   width:100% !important;
}

.EBP--content input[type="text"] {
  padding:0px;
  text-align:center;
  width:100%;
  border:none;
}

.EBP--content textarea {
  padding:0px;
  text-align:center;
  width:100%;
  height:100px;
  overflow:hidden;
  overflow-x:auto;
}

.EBP--content input[type="checkbox"],
.EBP--content input[type="radio"]{
  margin-right:4px; visibility:hidden;
}

.EBP--content form .ebp_form_duplicate_cnt {
  margin-bottom: 25px;
}

.EBP--content form .duplHolder {
  margin: 20px 0px;
  padding: 15px;
}

.EBP--content form .duplHolder {
  background: rgba(255, 255, 255, 0.2);
}
.EBP--content form .duplHolder .EBP--DuplicateTitle {
  margin-bottom: 10px;
  font-size: 1.5em;
}

.EBP--content form .inputholder {
  margin: 10px 20px 10px 0px;
  display:inline-block;
  vertical-align: middle;
  line-height:20px;
}
.EBP--content form .fieldHolder{
  display:block; width:100%; padding:0;
}
.EBP--content form span.label{
  margin-right:20px;
  vertical-align: baseline;
}

.EBP--content form .inputholder:last-child,
.EBP--content form span.label:last-child{
  margin-right: 0
}

.EBP--content form span.label a{
  opacity: 1
}
.EBP--content form span.label a:hover{
  opacity: .7
}

.EBP--content .formInput.overflowed {
  overflow: hidden;
}

.EBP--content form .formInput input[type="text"] {
  font-size: <?php echo $settings->modal_input_fontSize?>px;
  line-height: <?php echo $settings->modal_input_lineHeight?>px;
  padding: <?php echo $settings->modal_input_topPadding?>px 0px ;
  margin: <?php echo $settings->modal_input_space?>px 0px 0px;
}
.EBP--content form .formInput textarea {
  font-size: <?php echo$settings->modal_input_fontSize; ?>px;
  line-height: <?php echo$settings->modal_input_lineHeight; ?>px;
  padding: <?php echo$settings->modal_input_topPadding; ?>px 0px ;
  margin-top: <?php echo$settings->modal_input_space; ?>px;
}

.EBP--contetn form .staticText {
  margin: <?php echo $settings->modal_input_space;?>px 0px 0px;
}

.EBP--content form .hasSelectField {
  margin: <?php echo $settings->modal_input_space;?>px 0px 0px;
}
.EBP--content form .hasCheckBoxes,
.EBP--content form .hasRadioButton {
  margin-bottom: <?php echo $settings->checkBoxMarginBottom;?>px;
  margin-top: <?php echo $settings->checkBoxMarginTop;?>px;
}

.EBP--content input.half,
.EBP--content textarea.half,
.EBP--content .formInput .fieldHolder.half {
  width: 49.5% !important;
}

.EBP--content input.half:last-child,
.EBP--content textarea.half:last-child,
.EBP--content .formInput .fieldHolder.half:last-child {
  float:right;
}
.EBP--content input.half:first-child,
.EBP--content textarea.half:first-child,
.EBP--content .formInput .fieldHolder.half:first-child {
  float:left;
}

.EBP--content input:focus::-webkit-input-placeholder,
.EBP--content textarea:focus::-webkit-input-placeholder {
  opacity: .3
}
.EBP--content input:focus:-moz-placeholder,
.EBP--content textarea:focus:-moz-placeholder {
  opacity: .3
}
.EBP--content input:focus::-moz-placeholder,
.EBP--content textarea:focus::-moz-placeholder{
  opacity: .3
}
.EBP--content input:focus:-ms-input-placeholder,
.EBP--content textarea:focus:-ms-input-placeholder {
  opacity: .3
}

.EBP--content input.incorrect::-webkit-input-placeholder,
.EBP--content textarea.incorrect::-webkit-input-placeholder {
  color: red !important;
}
.EBP--content input.incorrect:-moz-placeholder,
.EBP--content textarea.incorrect:-moz-placeholder {
  color: red !important;
}
.EBP--content input.incorrect::-moz-placeholder,
.EBP--content textarea.incorrect::-moz-placeholder{
  color: red !important;
}
.EBP--content input.incorrect:-ms-input-placeholder,
.EBP--content textarea.incorrect:-ms-input-placeholder {
  color: red !important;
}

.EBP--content form .fieldHolder.incorrect:after {
  content: "!";
  width: 24px;
  height: 24px;
  display: inline-block;
  vertical-align: middle;
  font-weight: bold;
  color: #ff0000;
}

.EBP--content input.incorrect,
.EBP--content textarea.incorrect{
  color:#ff0000 !important;
}

.EBP--content input.couponInput {
  display: inline-block;
  padding: 8px 10px;
  font-size: 14px !important;
  line-height: 14px;
  width: 100px !important;
  margin: 0;
  text-align: center;
  display: inline-block;
}

.EBP--content input[disabled] {
  opacity: .8;
  cursor: not-allowed;
}



/* CHECK BOX STYLE */

.EBP--content .checkBoxStyle {
  width: 25px;
  position: relative;
  display:inline-block;
  margin-right:5px;
  vertical-align: text-top;
}

.EBP--content .checkBoxStyle label {
  cursor: pointer;
  position: absolute;
  width: 22px;
  height: 20px;
  top: 0;
  left:0;
  border-radius: 4px;
  -webkit-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.5), 0px 1px 0px rgba(255,255,255,.4);
  -moz-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.5), 0px 1px 0px rgba(255,255,255,.4);
  box-shadow: inset 0px 1px 1px rgba(0,0,0,0.5), 0px 1px 0px rgba(255,255,255,.4);
  background: rgba(255,255,255,0.2);

}

.EBP--content .checkBoxStyle label.dot {
  ebkit-border-radius: 50%;
  -moz-border-radius: 50%;
  border-radius:50%;
}

.EBP--content .checkBoxStyle label:after {
  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
  filter: alpha(opacity=0);
  opacity: 0;
  content: '';
  position: absolute;
  background: transparent;

}

.EBP--content .checkBoxStyle label.check:after{
  border: 3px solid #111;
  border-top: none;
  border-right: none;
  -webkit-transform: rotate(-45deg);
  -moz-transform: rotate(-45deg);
  -o-transform: rotate(-45deg);
  -ms-transform: rotate(-45deg);
  transform: rotate(-45deg);
  width: 9px;
  height: 5px;
  top: 5px;
  left: 5px;
}

.EBP--content .checkBoxStyle label.dot:after {
  background-color:#111;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  border-radius:50%;
  width: 10px;
  height: 10px;
  top: 6px;
  left: 6px;

}

.EBP--content .checkBoxStyle label:hover::after {
  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";
  filter: alpha(opacity=50);
  opacity: 0.5;
}

.EBP--content .checkBoxStyle input[type=checkbox]:checked + label:after,
.EBP--content .checkBoxStyle input[type=radio]:checked + label:after {
  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
  filter: alpha(opacity=100);
  opacity: 1;
}


/* DROP DOWN STYLE */
.EBP--content .cd-dropdown *,
.EBP--content .cd-dropdown *:after,
.EBP--content .cd-dropdown *:before {
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  padding: 0;
  margin: 0;
}

/* Clearfix hack by Nicolas Gallagher: http://nicolasgallagher.com/micro-clearfix-hack/ */
.clearfix:before,
.clearfix:after {
  content: " ";
  display: table;
}

.clearfix:after {
  clear: both;
}

.clearfix {
  *zoom: 1;
}


.EBP--content .cd-dropdown,
.EBP--content .cd-select {
  position: relative;
  min-width: 200px;
  margin:0;
  text-align:center;
  display: inline-block;
}

.EBP--content .cd-dropdown > span {
  width: 100%;
  height: 40px;
  line-height: 40px;
  font-weight: normal;
  font-size: 16px;
  background: none;
  display: block;
  padding: 0 0px 0 0px;
  position: relative;
  cursor: pointer;
}

.EBP--content .cd-dropdown > span:after {
  content: '\25BC';
  font-size:11px;
  background: rgba(0,0,0,.1);
  width:20px;
  line-height:20px;
  height:20px;
  vertical-align:top;
  margin-left:5px;
  display:inline-block;
  text-decoration:none;
  cursor:pointer;
  -webkit-transition: all 0.3s ease;
  -moz-transition: all 0.3s ease;
  transition: all 0.3s ease;
  margin-top:10px;  opacity:0.8;
  box-shadow: 0px 1px 0px 0px rgba(0,0,0,0.2);
  border-radius:3px;
  webkit-border-radius: 3px;
  -moz-border-radius: 3px;

}

.EBP--content .cd-dropdown.cd-active > span:after {
  content: '\25B2';
}

.EBP--content .cd-dropdown ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  display: block;
  position: relative;
  z-index: 2000;
}

.EBP--content .cd-dropdown ul li {
  display: block;
}

.EBP--content .cd-dropdown ul li span {
  width: 100%;
  background: rgba(255,255,255,.8);
  line-height: 40px;
  padding: 0;
  text-align:center;
  display: block;
  color: #111;
  cursor: pointer;
  font-weight: normal;

}

.EBP--content .cd-dropdown > span,
.EBP--content .cd-dropdown ul li span {
  -webkit-backface-visibility: hidden;
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Select fallback styling */
.EBP--content .cd-select {
  border: 1px solid #ddd;
}

.EBP--content .cd-dropdown ul {
  position: absolute;
  top: 0px;
  width: 100%;
}


.EBP--content .cd-dropdown ul li {
  position: absolute;
  width: 100%;
  pointer-events: none;
}

.EBP--content .cd-active.cd-dropdown > span {
  color: #208F4F;
  display:inline-block;
}

.EBP--content .cd-active.cd-dropdown ul li {
  pointer-events: auto;
}

.EBP--content .cd-active.cd-dropdown ul li span {
  -webkit-transition: all 0.2s linear 0s;
  -moz-transition: all 0.2s linear 0s;
  -ms-transition: all 0.2s linear 0s;
  -o-transition: all 0.2s linear 0s;
  transition: all 0.2s linear 0s;
}

.EBP--content .cd-active.cd-dropdown ul li span:hover {
  background: #208F4F;
}


.cd-dropdown ul::-webkit-scrollbar {
  width: 6px;
}

.cd-dropdown ul::-webkit-scrollbar-thumb {
  border-radius: 10px;
  background:rgba(0,0,0,.8);
  -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5);
}


/* Modal button*/

.EBP--content a,
.EBP--content a:hover,
.EBP--content a:focus {
  text-decoration:none;
  outline: none;
}

.EBP--content a.Modal--BookBtn,
.EBP--content a.paypalPay,
.EBP--content a.Modal--CouponBtn,
.EBP--content a.Modal--directDateBook {
  background: rgba(0,0,0,.1);
  cursor:pointer;
  font-size: 1em;
  display:inline-block;
  letter-spacing: 1px;
  <?php echo getTransition(); ?>
  <?php echo getBorderRadius(3); ?>;

  box-shadow: 0px 1px 0px 0px rgba(0,0,0,0.2);
  text-decoration: none;
  white-space: nowrap;
}

.EBP--content a.Modal--CouponBtn,
.EBP--content a.Modal--directDateBook {
  padding:8px 10px;
  font-size: 14px;
  line-height: 14px;
  margin: auto;
  margin-top: -4px;
  margin-left: 10px;
  display: inline-block;
  vertical-align: middle;
}

.EBP--content a.Modal--BookBtn {
  margin-left: 20px;
  margin-top: <?php echo $settings->modal_btnMarginTop; ?>px;
  color: <?php echo $settings->modal_btnTxtColor; ?>;
  font-size:<?php echo $settings->modal_btnFontSize; ?>px;
  height:auto;
  line-height:<?php echo $settings->modal_btnLineHeight; ?>px;
  padding: <?php echo $settings->modal_btnTopPadding.'px '.$settings->modal_btnSidePadding.'px'; ?>;
  font-weight:<?php echo $settings->modal_btnFontType; ?>;
  <?php echo getBorderRadius($settings->modal_btnBorderRadius); ?>;
}

.EBP--content a.Modal--BookBtn:hover,
.EBP--content a.paypalPay:hover,
.EBP--content a.Modal--CouponBtn:hover,
.EBP--content a.Modal--directDateBook:hover,
.EBP--content .Modal--QuantityBtns a:hover{
  background: rgba(0,0,0,.3);
}

.EBP--content a.Modal--BookBtn.deactive,
.EBP--content a.paypalPay.deactive {
   cursor:default;
   opacity:0.5;
}
.EBP--content a.Modal--BookBtn.deactive:hover,
.EBP--content a.paypalPay.deactive:hover {
  background: rgba(0,0,0,.1);
}
.EBP--content a.Modal--CouponBtn.deactive,
.EBP--content a.Modal--directDateBook.deactive{
  opacity:0.7;
 }

.EBP--content a.Modal--CouponBtn.deactive:hover,
.EBP--content a.Modal--directDateBook.deactive:hover{
  cursor:default;
}

/*increase decrease btns*/
.EBP--content .Modal--QuantityBtns a {
  display:inline-block;
  background: rgba(0,0,0,.1);
  font-size: 18px;
  line-height: 25px;
  width: 30px;
  margin: 0px 10px;
  opacity: 0.8;

  text-decoration: none;
  cursor: pointer;

  <?php echo getTransition(); ?>
  <?php echo getBorderRadius(3); ?>;

  box-shadow: 0px 1px 0px 0px rgba(0,0,0,0.2);
}

.EBP--content a.Modal--QuantityBtn:first-child{
  margin-top: 0px;
}

.Modal--QuantityCnt {
  margin-bottom: 15px;
}

.Modal--QuantityCnt:last-child {
  margin-bottom: 0px;
}

.EBP--content .Modal--QuantityCnt-Inside {
  margin-bottom: <?php echo $settings->modal_input_space;?>px;
}

.EBP--closeBtn {
  position: fixed;
  top: 8px;
  right: -10px;
  text-align: center;

}

.EBP--show .EBP--closeBtn  {
  opacity: 1;
}

.EBP--closeBtn a {
  display:inline-block;
  background: rgba(0,0,0,.1);
  color: #FFF;
  font-size: 18px;
  line-height: 25px;
  width: 30px;
  margin: 0 10px;
  opacity: 0.8;
  text-decoration: none;
  cursor: pointer;

  <?php echo getBorderRadius(3); ?>;
  box-shadow: 0 1px 0 0 rgba(0,0,0,0.2);
  <?php echo getTransition();?>;
}


.EBP--content,
.EBP--content .Modal--SpotsLeft,
.EBP--content .cd-dropdown > span,
.EBP--content .Modal--BookingLoader,
.EBP--content .Modal-QuantityColumn span,
.EBP--content a.Modal--QuantityBtn,
.EBP--content .title,
.EBP--content .Modal--Dates--Title,
.EBP--content .Modal--Title,
.EBP--content a.Modal--BookBtn,
.EBP--content a.paypalPay,
.EBP--content a.Modal--CouponBtn,
.EBP--content a.Modal--directDateBook,
.EBP--content a.Modal--directDateBook:hover,
.EBP--content .cd-active.cd-dropdown ul li span:hover{
  color:<?php echo $settings->modal_txtColor; ?>;
}

.EBP--content input,
.EBP--content textarea,
.EBP--content select{
  color:<?php echo $settings->modal_input_txtColor; ?> !important;
  background:<?php echo hex2rgba($settings->modal_input_bgColor,intval($settings->modal_input_bgColorAlpha)/100); ?> !important;
}

.EBP--content input::-webkit-input-placeholder,
.EBP--content textarea::-webkit-input-placeholder {
  color:<?php echo $settings->modal_input_txtColor; ?>
}

.EBP--content input:-moz-placeholder,
.EBP--content textarea:-moz-placeholder {
  color:<?php echo $settings->modal_input_txtColor; ?>
}

.EBP--content input::-moz-placeholder,
.EBP--content textarea::-moz-placeholder {
  color:<?php echo $settings->modal_input_txtColor; ?>
}

.EBP--content input:-ms-input-placeholder,
.EBP--content textarea:-ms-input-placeholder {
  color:<?php echo $settings->modal_input_txtColor; ?>
}

.EBP--content input:hover::-webkit-input-placeholder,
.EBP--content textarea:hover::-webkit-input-placeholder {
  color:<?php echo $settings->modal_inputHover_txtColor; ?>
}

.EBP--content input:hover:-moz-placeholder,
.EBP--content textarea:hover:-moz-placeholder {
  color:<?php echo $settings->modal_inputHover_txtColor; ?>
}

.EBP--content input:hover::-moz-placeholder,.EBP--content textarea:hover::-moz-placeholder {
  color:<?php echo $settings->modal_inputHover_txtColor; ?>
}

.EBP--content input:hover:-ms-input-placeholder,
.EBP--content textarea:hover:-ms-input-placeholder {
  color:<?php echo $settings->modal_inputHover_txtColor; ?>
}

.EBP--content form span.label a {
  color:<?php echo $settings->modal_input_txtColor; ?> !important;
}

.EBP--content select option {
  color:#000;
}

.EBP--content input:hover,
.EBP--content input:focus,
.EBP--content textarea:hover,
.EBP--content textarea:focus {
  color:<?php echo $settings->modal_inputHover_txtColor; ?> !important;
  background:<?php echo hex2rgba($settings->modal_inputHover_bgColorHover ,intval($settings->modal_inputHover_bgColorAlpha )/100); ?> !important;
}


.EBP--content input[disabled]:hover {
  color:<?php echo $settings->modal_input_txtColor; ?> !important;
  background:<?php echo hex2rgba($settings->modal_input_bgColor,intval($settings->modal_input_bgColorAlpha)/100); ?> !important;
}

.EBP--content .checkBoxStyle label {
  background:<?php echo hex2rgba($settings->modal_input_bgColor ,intval($settings->modal_input_bgColorAlpha )/100); ?> ;
}

.EBP--content .checkBoxStyle label.check:after {
  border: 3px solid <?php echo $settings->checkBoxColor; ?>;
  border-top: none;
  border-right: none;
}

.EBP--content .checkBoxStyle label.dot:after {
  background-color: <?php echo $settings->checkBoxColor; ?>;
}

.EBP--content .inputholder{
  color:<?php echo $settings->checkBoxTextColor; ?>;
}

.EBP--content .cd-dropdown > span:after ,
.EBP--content .Modal-QuantityColumn,
.EBP--content a.Modal--CouponBtn {
  color:<?php echo hex2rgba($settings->modal_txtColor,0.8); ?>;
}

.EBP--content .cd-active.cd-dropdown ul li span:hover {
  background: <?php echo $settings->modal_selectHoverColor; ?>;
  color: <?php echo $settings->modal_selectTxtHoverColor; ?>;
}

.EBP--content .cd-active.cd-dropdown > span {
  color: <?php echo $settings->modal_selectHoverColor; ?>;
}

.EBP--Scrollable--outer {
  height: 100%;
}

.EBP--content::-webkit-scrollbar {
 height: 12px;
}
.EBP--content::-webkit-scrollbar-track {
 box-shadow:0 0 2px rgba(0,0,0,0.15) inset;
 background: #f0f0f0;
}
.EBP--content::-webkit-scrollbar-thumb {
 border-radius:6px;
 background: #ccc;
}

/* TICKETS*/
.EBP--content .Modal--Tickets > div:not(.topBorder){
 display:inline-block;
 width:33%;
 text-align:center;
 margin-left:0;
 margin-right:0px;
 padding-left:0;
 padding-right:0px;
 font-size:16px;
}

/* SPOTS LEFT*/

.EBP--content .Modal--SpotsLeft {
  font-size: 16px;
  line-height: 40px;
  height: 40px;
  display: inline-block;
  margin: 0;
  padding: 0;
  vertical-align: central;
  text-align: center;
}

.EBP--content .Modal--SpotsLeft span {
  font-size: 18px;
  line-height: 40px;
  height: 40px;
  vertical-align: central;
  font-weight: normal;
  vertical-align:baseline;
}

/* QUANTIY*/
.EBP--content .Modal-QuantityColumn {
  padding:0px; margin:0;
  display:inline-block;
  width:33%;
  text-align:center;
  font-size:14px;
  height:25px;
  background:none;
}

.EBP--content .Modal-QuantityColumn span {
  display: inline-block;
  font-size: 14px;
}

.EBP--content .Modal-QuantityColumn span.taxed {
  margin-left: 10px;
  opacity: .8;
}

.EBP--content .Modal-QuantityColumn span strong{
 font-weight:normal;
}
.EBP--content .Modal--QuantityFinalTotal .total,
.EBP--content .Modal--QuantityFinalTotal .totalLabel {
  margin-left: 66%;
}

/* COUPON*/

.EBP--content .Modal--CouponCnt {
  text-align:left;
}

.EBP--content  .Modal--CouponCnt span.Modal--CouponResult {
  margin-left:10px;
  text-align:left;
  font-size:14px;
}


/* TITLE*/
.EBP--content .title,
.EBP--content .Modal--Dates--Title,
.EBP--content .Modal--Title {
  margin: 0;
  text-align: center;
  opacity: 0.5;
  background:none;
  border: none;
  height: auto;
  font-size: 2em;
}

.EBP--content .Modal--Title {
  margin-bottom: <?php echo $settings->modal_titleMarginBottom;?>px;
  font-size: <?php echo $settings->modal_titleSize;?>px;
  line-height: <?php echo $settings->modal_titleLineHeight ;?>px;

  <?php
    $fontStyle=($settings->modal_titleFontType == "italic")?'font-style:italic;':'font-weight:'.$settings->modal_titleFontType.';';
    echo $fontStyle;
  ?>
}


.EBP--mobilePage .Modal--Title {
  margin-top: 20px;
}



.EBP--content cite.small {
  font-size: .8em;
  max-width: 100px;
  display: inline-block;
}

/* MORE DARES FORM*/
.EBP--content .EBP--DatesBlock {
  width:100%;
  display:block;
  margin-bottom:40px;
}

.EBP--content .Modal--Dates--Title {

  margin-bottom:<?php echo $settings->modal_dateTitleMarginBottom;?>px;
  color: <?php echo $settings->modal_dateTitleColor;?>;
  font-size:<?php echo $settings->modal_dateTitleFontSize;?>px;
  line-height:<?php echo $settings->modal_dateTitleFontLineHeight;?>px;
  <?php
    $fontStyle = ($settings->modal_dateTitleFontStyle == "italic")?'font-style:italic;':'font-weight:'.$settings->modal_dateTitleFontStyle.';';
    echo $fontStyle;
  ?>

  text-align: <?php echo $settings->modal_dateTitleTextAlign;?>;
}

.EBP--content .dateCnt .datelabel {
  display:inline-block;
  margin-right: 10px;

  <?php
    $fontStyle = ($settings->modal_dateLabelStyle == "italic")?'font-style:italic;':'font-weight:'.$settings->modal_dateLabelStyle.';';
    echo $fontStyle;
  ?>

  /*color:<?php echo $settings->modal_dateLableColor; ?>;*/
  font-size:<?php echo $settings->modal_dateLableSize; ?>px;
  line-height:<?php echo $settings->modal_dateLabelLineHeight; ?>px;
}

.EBP--content .dateCnt .eventDate {
  /*color:<?php echo $settings->modal_dateColor; ?>;
  font-size:<?php echo $settings->modal_dateFontSize; ?>px;
  line-height:<?php echo $settings->modal_dateFontSize; ?>px;*/
}


.Modal--BookingBtnsCnt {
  margin-bottom: 20px
}

/* SUCCESS BOX */
.EBP--content .Modal--SuccessTitle {
  margin: 10px;
  font-size: 48px;
  display:block;
}
.EBP--content .Modal--SuccessMsg {
  font-size: 16px;
  display:block;
}


/* BOOKING LOADER*/
.EBP--content .Modal--BookingLoader {
  display:none;
  margin-top:20px;
  height:20px;
  width:100%;
  text-align:center;
}


/* LOADER */
#EBPLOADER {
  position: relative;
  width: 240px;
  height: 29px;
  margin: 0 auto;
}

.EBPLOADER{
  position: absolute;
  top: 0;
  width: 29px;
  height: 29px;

  -moz-animation-name:bounce_EBPLOADER;
  -moz-animation-duration:1.3s;
  -moz-animation-iteration-count:infinite;
  -moz-animation-direction:linear;
  -moz-transform:scale(.3);
  -moz-border-radius:19px;
  -webkit-animation-name:bounce_EBPLOADER;
  -webkit-animation-duration:1.3s;
  -webkit-animation-iteration-count:infinite;
  -webkit-animation-direction:linear;
  -webkit-transform:scale(.3);
  -webkit-border-radius:19px;
  -ms-animation-name:bounce_EBPLOADER;
  -ms-animation-duration:1.3s;
  -ms-animation-iteration-count:infinite;
  -ms-animation-direction:linear;
  -ms-transform:scale(.3);
  -ms-border-radius:19px;
  -o-animation-name:bounce_EBPLOADER;
  -o-animation-duration:1.3s;
  -o-animation-iteration-count:infinite;
  -o-animation-direction:linear;
  -o-transform:scale(.3);
  -o-border-radius:19px;
  animation-name:bounce_EBPLOADER;
  animation-duration:1.3s;
  animation-iteration-count:infinite;
  animation-direction:linear;
  transform:scale(.3);
  border-radius:19px;
}

#EBPLOADER_1{
  left:0;
  -moz-animation-delay:0.52s;
  -webkit-animation-delay:0.52s;
  -ms-animation-delay:0.52s;
  -o-animation-delay:0.52s;
  animation-delay:0.52s;
}

#EBPLOADER_2{
  left:30px;
  -moz-animation-delay:0.65s;
  -webkit-animation-delay:0.65s;
  -ms-animation-delay:0.65s;
  -o-animation-delay:0.65s;
  animation-delay:0.65s;
}

#EBPLOADER_3{
  left:60px;
  -moz-animation-delay:0.78s;
  -webkit-animation-delay:0.78s;
  -ms-animation-delay:0.78s;
  -o-animation-delay:0.78s;
  animation-delay:0.78s;
}

#EBPLOADER_4{
  left:90px;
  -moz-animation-delay:0.91s;
  -webkit-animation-delay:0.91s;
  -ms-animation-delay:0.91s;
  -o-animation-delay:0.91s;
  animation-delay:0.91s;
}

#EBPLOADER_5{
  left:120px;
  -moz-animation-delay:1.04s;
  -webkit-animation-delay:1.04s;
  -ms-animation-delay:1.04s;
  -o-animation-delay:1.04s;
  animation-delay:1.04s;
}

#EBPLOADER_6{
  left:150px;
  -moz-animation-delay:1.17s;
  -webkit-animation-delay:1.17s;
  -ms-animation-delay:1.17s;
  -o-animation-delay:1.17s;
  animation-delay:1.17s;
}

#EBPLOADER_7{
  left:180px;
  -moz-animation-delay:1.3s;
  -webkit-animation-delay:1.3s;
  -ms-animation-delay:1.3s;
  -o-animation-delay:1.3s;
  animation-delay:1.3s;
}

#EBPLOADER_8{
  left:210px;
  -moz-animation-delay:1.43s;
  -webkit-animation-delay:1.43s;
  -ms-animation-delay:1.43s;
  -o-animation-delay:1.43s;
  animation-delay:1.43s;
}

.EBPLOADER{
  background-color: <?php echo $loaderColor; ?>;
}
@-moz-keyframes bounce_EBPLOADER{
0%{
  -moz-transform:scale(1);
  background-color: <?php echo $loaderColor; ?>;
}
100%{
  -moz-transform:scale(0);
  background-color: <?php echo $loaderColor2; ?>;
}}

@-webkit-keyframes bounce_EBPLOADER{
0%{
  -webkit-transform:scale(1);
  background-color: <?php echo $loaderColor; ?>;
}
100%{
  -webkit-transform:scale(0);
  background-color: <?php echo $loaderColor2; ?>;
}}

@-ms-keyframes bounce_EBPLOADER{
0%{
  -ms-transform:scale(1);
  background-color: <?php echo $loaderColor; ?>;
}
100%{
  -ms-transform:scale(0);
  background-color: <?php echo $loaderColor2; ?>;
}}

@-o-keyframes bounce_EBPLOADER{
0%{
  -o-transform:scale(1);
  background-color: <?php echo $loaderColor; ?>;
}
100%{
  -o-transform:scale(0);
  background-color: <?php echo $loaderColor2; ?>;
}}

@keyframes bounce_EBPLOADER{
0%{
  transform:scale(1);
  background-color: <?php echo $loaderColor; ?>;
}
100%{
  transform:scale(0);
  background-color: <?php echo $loaderColor2; ?>;
}
}


/* ADDON: WHO BOOKED */
.EBP--content .Modal--WhoBooked .grouped {
  padding: 15px 0px;
  border-bottom: 1px solid rgba(255,255,255,.2);
}
.EBP--content .Modal--WhoBooked .grouped:last-child{
  border-bottom: none;
}
.EBP--content .Modal--WhoBooked .dateCnt {
  margin-bottom: 15px;
}
.EBP--content .Modal--WhoBooked ul{
  list-style: none;
  padding: 0;
}
.EBP--content .Modal--WhoBooked ul li{
  display: inline-block;
  width: auto;
  padding: 0px 15px 15px;
  font-size: .9em;
  opacity: .8;
}


/* SCROLLER HACK*/
.EBP--content .EBP--Scrollable--outer > .scroll-element.scroll-y {
  right: -40px;
}

.EBP--CalendarContainer-WeeklyView .scroll-element.scroll-y ,
.EBP--CalendarEventContent.EBP--Scrollable--outer > .scroll-element.scroll-y {
  right: 5px;
  z-index: 2000;
}

.EBP--CalendarEventContent.EBP--Scrollable--outer > .scroll-element .scroll-bar,
.EBP--CalendarContainer-WeeklyView  .EBP--Scrollable--outer > .scroll-element .scroll-bar{
  background: #AFAFAF;
}

/* Smartphones (portrait and landscape) ----------- */
@media only screen and (max-width : 760px) {
  .ebpBox .details .Ebp--Spots{
    display: block;
    width: 95%;
  }
  .ebpBox .details .Ebp--Price{
    display:block;
    width: 95%;
  }

  .EBP--modal {
    width:100%;
    visibility: visible;
    display:none;
    opacity:1;
    -webkit-backface-visibility: visible;
    -moz-backface-visibility: visible;
    backface-visibility: visible;
  }

  .EBP--Show {
    display:block;
  }

  .EBP--content {
    padding: 20px 10px;
  }

  .EBP--content .EBP--Scrollable--outer > .scroll-element.scroll-x {
    display: none;
  }

  .EBP--content .EBP--Scrollable--outer > .scroll-element.scroll-y {
    right: -12px;
  }

  .EBP--Show ~ .ebp-overlay {
    opacity: 1;
    display:block;
  }

  .Modal--Title {
    margin-top: 20px;
  }

  .EBP--content .Modal--Tickets > div {
    display:block !important;
    text-align:center !important;
    width:100% !important;
  }

  .EBP--content .Modal-QuantityColumn {
    display:block;
    width:100%;
    margin-bottom: 10px;
  }

  .EBP--content .Modal--CouponCnt {
    text-align:center;
  }

  .EBP--content .Modal--CouponCnt span.Modal--CouponResult {
    display:block;
    margin-top:10px;
    width:100%;
  }

  .Modal--Quantity {
    overflow: hidden;
  }

  .EBP--content .Modal--QuantityCnt-Inside {
    float:left;
    width:50%;
    padding:0;
  }
  .EBP--content .Modal--QuantityFinalTotal .total,
  .EBP--content .Modal--QuantityFinalTotal .totalLabel {
    margin: 0;
  }
  .Modal--QuantityCnt {
    border-bottom: 1px solid rgba(255,255,255,0.3);
    overflow: hidden;
  }
  .EBP--content .Modal--QuantityFinalTotal .topBorder {
    display: none;
  }
  .EBP--content .Modal--QuantityCnt .topBorder {
    display: none;
  }

  .EBP--content .formInput {
    margin-bottom: 15px;
  }

  .EBP--content input.half, .EBP--content textarea.half, .EBP--content .formInput .fieldHolder.half {
    width: 100% !important;
  }


  .EBP--Content .dateCnt .btns {
    display: block;
    margin: 15px 0px;
  }

  .EBP--content a.Modal--directDateBook {
    margin: 0px;
  }
  .EBP--Content .dateCnt .dateWrap {
    font-size: 1em;
  }

  .showEventsDirectly .EBP--CalendarCellDate {
    font-size: 12px;
    line-height: 12px;
    left: 2px;
    top: 2px;
  }
  .EBP--CalendarContent:hover {
    width: 200%;
    margin-left: -50%;
  }
}




/* CALENDAR*/

#calendarLoader {
  position:relative;
  width:240px;
  height:29px;
  margin:0px auto;
  top:50%;
  margin-top:-15px;
}
.calendarLoader{
  background-color: <?php echo $settings->cal_color;?>;
  position:absolute;
  top:0;
  width:29px;
  height:29px;
  -moz-animation-name: calendarLoaderAnimation;
  -moz-animation-duration:1.3s;
  -moz-animation-iteration-count:infinite;
  -moz-animation-direction:linear;
  -moz-transform:scale(.3);
  -moz-border-radius:19px;
  -webkit-animation-name: calendarLoaderAnimation;
  -webkit-animation-duration:1.3s;
  -webkit-animation-iteration-count:infinite;
  -webkit-animation-direction:linear;
  -webkit-transform:scale(.3);
  -webkit-border-radius:19px;
  -ms-animation-name: calendarLoaderAnimation;
  -ms-animation-duration:1.3s;
  -ms-animation-iteration-count:infinite;
  -ms-animation-direction:linear;
  -ms-transform:scale(.3);
  -ms-border-radius:19px;
  -o-animation-name: calendarLoaderAnimation;
  -o-animation-duration:1.3s;
  -o-animation-iteration-count:infinite;
  -o-animation-direction:linear;
  -o-transform:scale(.3);
  -o-border-radius:19px;
  animation-name: calendarLoaderAnimation;
  animation-duration:1.3s;
  animation-iteration-count:infinite;
  animation-direction:linear;
  transform:scale(.3);
  border-radius:19px;
}

#calendarLoader_1{
  left:0;
  -moz-animation-delay:0.52s;
  -webkit-animation-delay:0.52s;
  -ms-animation-delay:0.52s;
  -o-animation-delay:0.52s;
  animation-delay:0.52s;
}

#calendarLoader_2{
  left:30px;
  -moz-animation-delay:0.65s;
  -webkit-animation-delay:0.65s;
  -ms-animation-delay:0.65s;
  -o-animation-delay:0.65s;
  animation-delay:0.65s;
}

#calendarLoader_3{
  left:60px;
  -moz-animation-delay:0.78s;
  -webkit-animation-delay:0.78s;
  -ms-animation-delay:0.78s;
  -o-animation-delay:0.78s;
  animation-delay:0.78s;
}

#calendarLoader_4{
  left:90px;
  -moz-animation-delay:0.91s;
  -webkit-animation-delay:0.91s;
  -ms-animation-delay:0.91s;
  -o-animation-delay:0.91s;
  animation-delay:0.91s;
}

#calendarLoader_5{
  left:120px;
  -moz-animation-delay:1.04s;
  -webkit-animation-delay:1.04s;
  -ms-animation-delay:1.04s;
  -o-animation-delay:1.04s;
  animation-delay:1.04s;
}

#calendarLoader_6{
  left:150px;
  -moz-animation-delay:1.17s;
  -webkit-animation-delay:1.17s;
  -ms-animation-delay:1.17s;
  -o-animation-delay:1.17s;
  animation-delay:1.17s;
}

#calendarLoader_7{
  left:180px;
  -moz-animation-delay:1.3s;
  -webkit-animation-delay:1.3s;
  -ms-animation-delay:1.3s;
  -o-animation-delay:1.3s;
  animation-delay:1.3s;
}

#calendarLoader_8{
  left:210px;
  -moz-animation-delay:1.43s;
  -webkit-animation-delay:1.43s;
  -ms-animation-delay:1.43s;
  -o-animation-delay:1.43s;
  animation-delay:1.43s;
}
@-moz-keyframes  calendarLoaderAnimation {
0%{
  -moz-transform:scale(1);
  background-color:<?php echo $settings->cal_color;?>
}
100%{
  -moz-transform:scale(0);
  background-color: #FFF;
}}

@-webkit-keyframes  calendarLoaderAnimation{
0%{
  -webkit-transform:scale(1);
  background-color:<?php echo $settings->cal_color;?>
}
100%{
  -webkit-transform:scale(0);
  background-color: #FFF;
}}

@-ms-keyframes  calendarLoaderAnimation{
0%{
  -ms-transform:scale(1);
  background-color:<?php echo $settings->cal_color;?>
}
100%{
  -ms-transform:scale(0);
  background-color: #FFF;
}}

@-o-keyframes  calendarLoaderAnimation{
0%{
  -o-transform:scale(1);
  background-color:<?php echo $settings->cal_color;?>
}
100%{
  -o-transform:scale(0);
  background-color: #FFF;
}}

@keyframes  calendarLoaderAnimation{
0%{
  transform:scale(1);
  background-color:<?php echo $settings->cal_color;?>
}
100%{
  transform:scale(0);
  background-color: #FFF;
}
}


