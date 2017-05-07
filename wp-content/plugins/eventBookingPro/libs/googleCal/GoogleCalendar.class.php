<?php

class GoogleCalendar {
  private $disallowed = array('=',);
  public $eventTitle, $eventStartTime, $eventEndTime, $eventLocation, $timeZone;

  public function __construct() {
    $this->defConfig = array('url' => 'http://www.google.com/calendar/event?',
      'title' => '',
      'datetime' => array('start' => '2012-12-01 00:00', 'end' => '2012-12-01 00:00'),
      'location' => '',
      'description' => '',
     	'target' => '_blank',
      'linkTxt' => '+google Calendar',
    	'class'=> 'addToGoogleCal',
    	'style'=> ''
    );
  }

  private function formatTime($time) {
    $tempStart = strtotime($time . " ".$this->timeZone);
    return date('Ymd\THis\Z', $tempStart);
  }

  public function createEventReminder($params, $returnButton=false) {
    $params += $this->defConfig;
    $this->eventTitle = $params['title'];
    /* Date and time of the event, in UTC format */
    $this->eventStartTime = $this->formatTime($params['datetime']['start']);
    $this->eventEndTime = $this->formatTime($params['datetime']['end']);
    $this->eventLocation = $params['location'];

    return '<a title="Add to My Google Calendar" style="'.$params['style'].'" class="'.$params['class'].'" target="'.$params['target'].'" href="'.$params['url'].'action=TEMPLATE&text='.$this->eventTitle.'&dates='.$this->eventStartTime.'/'.$this->eventEndTime.'&location='.$this->eventLocation.'&details='.strip_tags($params['description']).'&trp=true">'.$params['linkTxt'].'</a>';
  }

  public function setTimeZone($timeZone = "America/New_York") {
    $this->timeZone = $timeZone;
    date_default_timezone_set($timeZone);
  }


}

?>
