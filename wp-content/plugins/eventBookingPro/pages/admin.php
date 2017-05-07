<div class="wrap eventBookingWrap">
<br class="ev-clear">
	<div class="ev-admin">
	<?php

		global $wpdb;
		$results = $wpdb->get_results('SELECT name, id, eventStatus FROM ' . $wpdb->base_prefix . 'ebp_events');
		$eventCount = $wpdb->get_var("SELECT max(Id) from " . $wpdb->base_prefix . 'ebp_events' );
    $formsNeedUpgrade = (get_option("ebp_forms_version") != false && floatval(get_option("ebp_forms_version"))  < 1.6);
    $formsErrorMessage = 'You have an incompatible EBP Form Manager addon. Please <a href="'.admin_url().'plugins.php">update to version 1.6+</a> before using it!';

    if ($formsNeedUpgrade) {
      echo '<script language="javascript">';
      echo 'alert("You have an incompatible EBP Form Manager addon. Please update it to 1.6+ before using it!\n\nThank You")';
      echo '</script>';

      echo '<div class="alert alert-danger" style="margin:20px;"><h2>You have an incompatible EBP Form Manager addon. Please update it to 1.6+ before using it</h2></div>';
    }

    if (floatval(get_option("ebp_forms_version")) < 1.8) {
      echo '<div class="alert alert-danger" style="margin:20px;"><h1>Form Manager addon upgrade recommended</h1>It is recommended that you upgrade your forms manager addon to version 1.8+. We added new features that will improve your experience like the duplicate container position.</div>';
    }
		?>

	  <div class="loader" id="loader">
			<?php echo '<img src="' .plugins_url( 'preloader.gif' , __FILE__ ) . '" />';?>
	  </div>

        <div class="adminHeader">
        	<h2>Event Booking Pro</h2>
        	<a href="#" class="EBP--TopBtn settings">Settings</a>
          <a href="#" class="EBP--TopBtn shortcode">Shortcodes</a>
          <a href="#" class="EBP--TopBtn coupon">Manage Coupons</a>
          <a href="#" class="EBP--TopBtn category">Manage Categories</a>
          <a href="#" class="EBP--TopBtn addons">Add-Ons</a>
        </div>

        <div class="main">
          <div class="events">
            <input type="hidden" id="eventCount" value="<?php echo $eventCount; ?>"/>
            <div class="events_header">
             	<a class="EBP--CreateEventBtn" id="createEventBtn" href="#">Create Event</a>
            </div>
            <br class="ev-clear">
           	<ul class="eventlist">
              <?php
							foreach( $results as $result ) {
								$key = $result->id;
                $liClass = ($result->eventStatus != 'active') ? 'eventDeactivated' : '';
							?>

								<li id="event_<?php echo $key; ?>" class="<?php echo $liClass; ?>"><a href="#" data-id="<?php echo $key; ?>" class="btnE btn-block  "><small><?php echo $key; ?></small><span><?php echo stripslashes($result->name); ?></span></a></li>

		          <?php
							}
							?>
				     </ul>
				  </div>

				  <div class="eventDetails">
				    <div class="cnt">
				      <h1>Dashboard</h1>
				       <?php
								 if(isset($_GET['opt'])){
									switch($_GET['opt']){
										case 'showUCM':
											update_option("ebp_update_checker_show",true);
										break;

										case 'hideUCM':
											update_option("ebp_update_checker_show",false);
										break;

										case 'dUp':
											if(get_option("ebp_update_checker" )){
												update_option("ebp_update_checker",false);
												echo '<div class="alert alert-info">Version check is deactivated.</div>';
											}
										break;


										case 'aUp':
											if(!get_option("ebp_update_checker" )){
												update_option("ebp_update_checker",true);
												echo '<div class="alert alert-info">Version check is activated.</div>';
											}
                    break;

										case 'hideEventCardNotice':
											if(!get_option("eventCardNotice"))
												add_option("eventCardNotice",2);
										break;

                    case 'hideAnalyticsNotice':
                      if(!get_option("eventAnalyticsNotice"))
                        add_option("eventAnalyticsNotice", 2);
                      break;

                    case 'hideformsExtraAddon':
                      if(!get_option("formsExtraAddon"))
                        add_option("formsExtraAddon", 2);
                      break;

                    case 'hideEmailAddonReleased':
                      if(!get_option("EmailAddonReleased"))
                        add_option("EmailAddonReleased", 2);
                    break;

                    case 'hideChangeLog':
                      if(!get_option("EBP_CHANGE_LOG"))
                        add_option("EBP_CHANGE_LOG", 2);
                    break;

                    case 'hideFeb_2017_update':
                      add_option("ebp_feb_2017_update", 2);
                    break;

									}
								}

                if ($formsNeedUpgrade) {
                  echo '<div class="alert alert-danger">'.$formsErrorMessage.'</div>';
                }

                if (floatval(EventBookingAdmin::getVersion()) != floatval(get_option("ebp_version"))) {
                  echo '<div class="alert alert-danger">Plugin is not activated correctly! <a href="'.admin_url().'plugins.php">Deactivating the plugin and reactivating it to fix the problem!</a></div>';
                }

                  if(get_option("ebp_update_checker")) {
                    echo '<div class="alert alert-info" id="ebp-version" data-version="'.get_option("ebp_version").'">Fetching most recent version ...</div>';
                  }


								if (get_option("ebp_update_checker_show") && get_option("ebp_update_checker" )) {
									echo '<div class="alert alert-info">The plugin will display a message when an update is available! <a href="http://iplusstd.com/item/eventBookingPro/example/plugin-update-notifier/" target="_blank">More information on how to disable/enable this feature</a><br/><br/><a href="'.admin_url().'admin.php?page=eventManagement&opt=hideUCM">Never show this again</a></div>';
								}


								if (get_option("ebp_feb_2017_update") != 2) {
									echo '<div class="alert alert-info"><h3>February, 2017 Releases</h3><ul><li><strong>New addons: </strong>Weekly Calendar Addon and Gift Card Addon</li><li><strong>Updated addons:</strong> Forms Manager Addon, byDAy Addon and Analytics Addon</li></ul><br/><a href="'.admin_url().'admin.php?page=eventManagement&opt=hideFeb_2017_update">Never show this again</a></div>';
								}

                if (get_option("eventAnalyticsNotice") != 2) {
                  echo '<div class="alert alert-info"><h3>Analytics & Check in Addon</h3>QR Code, Checkin page and an Analytics system all in one addon.<br/>Feature rich : graphs, statistics, view all bookings, advanced search, sort & filter, edit booking, add booking, advanced csv export, etc...<br/><br/><a href="'.admin_url().'admin.php?page=eventManagement&opt=hideAnalyticsNotice">Never show this again</a></div>';
                }

                if (get_option("formsExtraAddon") != 2) {
                  echo '<div class="alert alert-info"><h3>Forms Extra Addon</h3>Extend the Forms manager addon to include fee-able options. You can add: Checkboxes, Radio button and select fields. <br/><br/><a href="'.admin_url().'admin.php?page=eventManagement&opt=hideformsExtraAddon">Never show this again</a></div>';
                }

                if (get_option("EmailAddonReleased") != 2) {
                  echo '<div class="alert alert-info"><h3>Email Templates Addon Released</h3>Create/Edit email templates. See a preview live of your email. Set an alternative email address to receive email when a booking happens.<br/><br/><a href="'.admin_url().'admin.php?page=eventManagement&opt=hideEmailAddonReleased">Never show this again</a></div>';
                }

                if (get_option("EBP_CHANGE_LOG") != 2) {
                  echo '<div class="alert alert-info"><h3>Change log:</h3>
                  <a href="http://iplusstd.com/item/eventBookingPro/example/change-log-and-upcoming-features/" target="_blank">Press here to check the full list of what is new or updated.</a><br/><br/><a href="'.admin_url().'admin.php?page=eventManagement&opt=hideChangeLog">Never show this again</a></div>';
                }


							?>
        </div>
      </div>
    </div>

    <div class="ebp-modal ev-admin popup" id="popUpBox">
			<div class="ebp-content">
        <div></div>
			</div>
		</div>

    <div class="ebp-modal" id="advancedDates">
			<div class="ebp-content">
				<div>
          <h3>Batch Occurrences</h3>
          <div class="sec">
            <h2>Times</h2>
            <span>Starts on: <input  name="start_time" value="" data-value="12:00" class="txtField" style="width:100px" type="text"  /></span>
            <span>Ends on: <input  name="end_time" value="" data-value="14:00" class="txtField" style="width:100px" type="text"  /></span>
            <span>Days: <input  name="event_days" value="1" class="txtField" style="width:50px" type="number"  />
             	<a href="#" class="tip-below tooltip" data-tip="Number of days the event will take. Example: 2 days, the event will start on monday (start time) and end on tuesday (end time).">?</a>
            </span>
          </div>

          <div class="sec">
          	<h2>Dates</h2>
              <div class="fromTo">
              	<?php $today=date('Y-m-d');?>
              	<span><h3>From:</h3><input  name="start_date" value="<?php echo $today ?>" class="txtField" type="text"  /></span>
               <span><h3>To:</h3><input  name="end_date" value="<?php echo $today ?>" class="txtField" type="text"  /></span>
              </div>

             <input type="radio" name="advanced_per" value="week"  data-toggle="weekDaysCheck" id="perWeek" checked="checked"/>
             <label for="perWeek" ><strong>Every Week</strong></label>

              <div class="radioToggled weekDaysCheck">
                <div class="advancedOptions">
                  <input type="checkbox" id="d-1" /><label for="d-1">Monday</label>
                  <input type="checkbox" id="d-2" /><label for="d-2">Tuesday</label>
                  <input type="checkbox" id="d-3" /><label for="d-3">Wednesday</label>
                  <input type="checkbox" id="d-4" /><label for="d-4">Thursday</label>
                </div>
                <div class="advancedOptions">
                  <input type="checkbox" id="d-5" /><label for="d-5">Friday</label>
                  <input type="checkbox" id="d-6" /><label for="d-6">Saturday</label>
                  <input type="checkbox" id="d-0" /><label for="d-0">Sunday</label>
                </div>
              </div>

              <br/>

              <input type="radio" name="advanced_per"  value="month"  data-toggle="daysCheck" id="perMonth" />

              <label for="perMonth"><strong>Every Month</strong><a href="#" class="tip-below tooltip" data-tip="If the day doest occur during a month then it is disregarded.">?</a></label>

              <div class="radioToggled daysCheck" style="display:none;">
                <div class="advancedOptions">
                	<input type="checkbox" id="w-1" /><label for="w-1">1</label>
                  <input type="checkbox" id="w-2" /><label for="w-2">2</label>
                  <input type="checkbox" id="w-3" /><label for="w-3">3</label>
                  <input type="checkbox" id="w-4" /><label for="w-4">4</label>
                  <input type="checkbox" id="w-5" /><label for="w-5">5</label>
                  <input type="checkbox" id="w-6" /><label for="w-6">6</label>
                  <input type="checkbox" id="w-7" /><label for="w-7">7</label>
                	<input type="checkbox" id="w-8" /><label for="w-8">8</label>
                  <input type="checkbox" id="w-9" /><label for="w-9">9</label>
                  <input type="checkbox" id="w-10" /><label for="w-10">10</label>
                </div>

                <div class="advancedOptions">
                  <input type="checkbox" id="w-11" /><label for="w-11">11</label>
                  <input type="checkbox" id="w-12" /><label for="w-12">12</label>
                  <input type="checkbox" id="w-13" /><label for="w-13">13</label>
                  <input type="checkbox" id="w-14" /><label for="w-14">14</label>
                	<input type="checkbox" id="w-15" /><label for="w-15">15</label>
                  <input type="checkbox" id="w-16" /><label for="w-16">16</label>
                  <input type="checkbox" id="w-17" /><label for="w-17">17</label>
                  <input type="checkbox" id="w-18" /><label for="w-18">18</label>
                  <input type="checkbox" id="w-19" /><label for="w-19">19</label>
                  <input type="checkbox" id="w-20" /><label for="w-20">20</label>
               </div>

               <div class="advancedOptions">
                  <input type="checkbox" id="w-21" /><label for="w-21">21</label>
                  <input type="checkbox" id="w-22" /><label for="w-22">22</label>
                  <input type="checkbox" id="w-23" /><label for="w-23">23</label>
                  <input type="checkbox" id="w-24" /><label for="w-24">24</label>
              	 	<input type="checkbox" id="w-25" /><label for="w-25">25</label>
                  <input type="checkbox" id="w-26" /><label for="w-26">26</label>
                  <input type="checkbox" id="w-27" /><label for="w-27">27</label>
                  <input type="checkbox" id="w-28" /><label for="w-29">28</label>
                  <input type="checkbox" id="w-29" /><label for="w-29">29</label>
                  <input type="checkbox" id="w-30" /><label for="w-30">30</label>
                  <input type="checkbox" id="w-31" /><label for="w-31">31</label>
              </div>
            </div>
          </div>


          <div class="sec" id="bookingsStartClose">
            <h2>Booking</h2>

            <div class="bookingOpens">
              <input type="checkbox" name="bookingOpensCB" id="CB_BookingOpensImmediately" checked="checked" />
              <label for="CB_BookingOpensImmediately">Booking opens immediately.</label>

              <div class="CB_deselected_Cnt">

                <span>Open before <input  name="bookingCloseOpenDays" value="7" class="txtField" style="width:50px" type="number"  /> (days)
                <a href="#" class="tip-below tooltip" data-tip="Number of days to open booking before event starts. Example: '2' will open booking 2 days prior to start date of event. 0, means booking will open same day.">?</a> </span>
                <span>At: <input  name="BookingCloseOpenTime" value="" data-value="14:00" class="txtField" style="width:100px" type="text"  /></span>

              </div>
            </div>

            <div class="bookingCloses">
              <input type="checkbox" name="bookingClosesCB" id="CB_BookingClosesImmediately"  checked="checked"/>
              <label for="CB_BookingClosesImmediately">Booking closes when events starts.</label>

              <div class="CB_deselected_Cnt">
                <span>Close before <input  name="bookingCloseOpenDays" value="0" class="txtField" style="width:50px" type="number"  /> (days)
                <a href="#" class="tip-below tooltip" data-tip="Number of days to close bookings before event starts. Use negative numbers to close booking after event starts.  Example:  '2' will close booking 2 days prior to start date. '-2' will close booking after event starts by 2 days.">?</a> </span>
                <span>At: <input  name="BookingCloseOpenTime" value="" data-value="14:00" class="txtField" style="width:100px" type="text"  /></span>
              </div>
            </div>

          </div>

          <div style="text-align:center; margin-top:30px;">
						<a href="#" class="btn btn-small btn-primary generateDates">Generate</a>
          </div>
				</div>
			</div>
		</div>

    <div class="ebp-overlay"></div>
    <br class="ev-clear">

  </div>

	<br class="ev-clear">
</div>

<?php

function simplexml_load_file_curl($url) {
  $xml="";

  if(in_array('curl', get_loaded_extensions())){
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $xml = simplexml_load_string(curl_exec($ch));
      curl_close($ch);
  }
  else{
      $xml = simplexml_load_file($url);
  }

  return $xml;
}


?>
