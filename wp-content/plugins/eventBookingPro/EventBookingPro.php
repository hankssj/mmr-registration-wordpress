<?php
/*
Plugin Name: Event Booking Pro
Plugin URI: http://iplusstd.com/item/eventBookingPro/
Description: EBP :: The only booking system that you need.
Version: 3.80
Author: Moe Haydar
Author URI: http://iplusstd.com/item/eventBookingPro/
*/

if (!class_exists("EventBookingAdmin")) {
	require_once dirname( __FILE__ ) . '/EventBookingClass.php';
	new EventBookingAdmin (__FILE__);

}

?>
