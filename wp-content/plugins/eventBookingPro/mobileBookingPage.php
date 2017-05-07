<?php
	require_once dirname( __FILE__ ) . '/include.php';

	global $wpdb;

	if (!isset($_GET["id"]) || !isset($_GET["type"])) {
		die("You should not be here!");
	} else {
		// get page type
		$type = $_GET['type'];
		$isBooking  = $type == 'book';
		$isEventOccurrences = $type == 'eventOccurrences';
		$isWhoIsComing = $type == 'coming';
		$eventId = $_GET["id"];

		// validate params
		if (!$isBooking && !$isEventOccurrences && !$isWhoIsComing) {
			die("Wrong type params");
		}

		if (intval($eventId) < 0) {
			die("invalid id");
		}

		// check if specific occurrence is passed
		if (!isset($_GET["date_id"])) {
			$dateId = -1;
		} else {
			$dateId = intval($_GET["date_id"]);
		}

		// get data
		$settings = $wpdb->get_row("SELECT * FROM " . EbpDatabase::getTableName("settings")." where id='1'");
		$today = date('Y-m-d');
  	$data = $wpdb->get_row("SELECT * FROM " . EbpDatabase::getTableName("events")." where id='$eventId' ");
	}
?>

<!doctype html>
<html>
<head>
	<title><?php echo $data->name?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<?php echo '<link href='.plugins_url( '/css/frontend.css', __FILE__ ).' rel="stylesheet">'; ?>
	<?php echo '<link href='.plugins_url( '/css/frontend-style.php', __FILE__ ).' rel="stylesheet">'; ?>

	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.css">
	<script src="<?php echo plugins_url( '/js/jquery-1.11.0.min.js', __FILE__ )?>"></script>
	<script src="<?php echo plugins_url( '/js/EbpUtil.js', __FILE__ )?>"></script>
	<script src="<?php echo plugins_url( '/js/helpers.js', __FILE__ )?>"></script>
	<script src="<?php echo plugins_url( '/js/jquery.dropdown.js', __FILE__ )?>"></script>

	<script type="text/javascript">
		window.ebpIsMobile = true;
		<?php if ($isBooking) echo 'window.ebpMobileIsBooking = true;'; ?>
	</script>
	<script src="<?php echo plugins_url( '/js/frontend.js', __FILE__ )?>"></script>

</head>

<body bgcolor="<?php echo $settings->modalMainColor; ?>">
	<div class="EBP--modal EBP--mobilePage">
		<div class="EBP--content">
			<input name="ajaxlink" value="<?php echo site_url();?>" type="hidden"  />
			<div class="EBP--closeBtn"><a href="javascript:history.back();" >x</a></div>

			<?php

			$opts = array('eventId'=>$eventId, 'dateId'=>$dateId );

			if ($isBooking) {
				$opts['step'] = EBP_FE_Modal::FORM_BOOKING;
			} else if ($isEventOccurrences) {
				$opts['step'] = EBP_FE_Modal::FORM_MORE_DATES;
			} else if ($isWhoIsComing) {
				$opts['step'] = EBP_FE_Modal::FORM_COMING;
			}

			echo EBP_FE_Modal::getBookingStepPage($opts);
			?>
		</div>
	</div>

</body>
</html>
