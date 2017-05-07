<?php
class AddOnManager {

	public static function includeRequiredFiles() {
		if (self::usesEmailTemplateAddOn()) {
			$emailClassFile = ABSPATH . '/wp-content/plugins/eventBookingProEmails/eventBookingProEmailsClass.php';
			if (file_exists($emailClassFile)) {
				require_once($emailClassFile);
			}
		}

		if (self::usesGiftCardAddon()) {
			$giftCardClass = ABSPATH . '/wp-content/plugins/EventBookingProGiftCard/EventBookingProGiftCardClass.php.php';
			if (file_exists($giftCardClass)) {
				require_once($giftCardClass);
			}
		}

	}

	public static function hasFormAddOn(){
		return class_exists('EventBookingFormsAdmin');
	}

	public static function hasEmailTemplatesAddOn() {
		return class_exists('eventBookingProEmailsClass');
	}

	public static function usesFormAddOn(){
		return (get_option("ev_uses_form") == 1);
	}

	public static function usesEmailTemplateAddOn(){
		return self::hasEmailTemplatesAddOn() && (get_option("ebp_uses_emails") == 1);
	}

	public static function usesEmailRules(){
		return self::hasEmailTemplatesAddOn() && (get_option("ebp_uses_email_rules") == 1);
	}

	public static function getFormManagerVersion(){
		return get_option("ebp_forms_version");
	}

	public static function usesDayCalAddOn(){
		return (get_option("ebp_uses_dayCalendar") == 1);
	}

	public static function usesAnalyticsAddOn(){
		return (get_option("ebp_uses_analytics") == 1);
	}

	public static function usesGiftCardAddon(){
		return (get_option("ebp_giftcard_addon") == 1);
	}

	public static function usesWeeklyView(){
		return (get_option("ebp_weeklyview_addon") == 1);
	}



	public static function getDayCalAddonVersion(){
		return get_option("ebp_dayCalendar_version");
	}

	public static function getUsersAddonPath () {
    if (!get_option('ebp_users_addon')) return false;

		if (!class_exists("eventBookingProUsersClass")) {
			$addonPath = 'wp-content/plugins/eventBookingProUsers/eventBookingProUsersClass.php';
			if (is_file($addonPath)) {
				include_once($addonPath);
				return true;
			}
			return false;
		} else {
			return true;
		}
	}


	public static function getAddonsPage(){
		$html = '<h2>Available AddOns</h2>';

		$html .= '<div class="addonCnt">';
			$html .= '<h3>Form Manager Addon</h3>';
			$html .= '<p class="desc"><strong>Full control</strong> over the form in the events booking page.You can add unlimited number of inputs, textAreas, checkboxes, radio buttons and drop down fields. As always everything is customizable!</p>';

			if (self::hasFormAddOn()) {
				$html .= '<span>Active</span>';
			} else {
				$html .= '<span><a href="http://iplusstd.com/item/eventBookingPro/buyFormsAddon.php">View & Buy</a> or enable the Addon</span>';
			}

		$html .= '</div>';

		$html .= '<div class="addonCnt">';
			$html .= '<h3>ByDay Calendar Addon</h3>';
			$html .= '<p class="desc">Full Responsive Horizontal Calendar that displays events by day. This interactive horizontal calendar will allow you to see events for a chosen day for the focused month. </p>';
			if (self::usesDayCalAddOn())
				$html .= '<span>Active</span>';
			else
				$html .= '<span><a href="http://iplusstd.com/item/eventBookingPro/buyDayCalAddon.php">View & Buy</a> or enable the Addon</span>';
		$html .= '</div>';


		$html .= '<div class="addonCnt">';
			$html .= '<h3>Analytics and Check In Addon </h3>'; //<span class="new">NEW</span>
			$html .= '<p class="desc">QR Code, Checkin page and an Analytics system all in one addon.<br/>Feature rich : graphs, statistics, view all bookings, advanced search, sort & filter, edit booking, add booking, advanced csv export, etc...</p>';
			$html .= '<p>Made with mobile in mind<p>';
			if (self::usesAnalyticsAddOn())
				$html .= '<span>Active</span>';
			else
				$html .= '<span><a href="http://iplusstd.com/item/eventBookingPro/buyAllBookings.php">View & Buy</a> or enable the Addon</span>';
		$html .= '</div>';


		$html .= '<div class="addonCnt">';
			$html .= '<h3>Email Templates</h3>';
			$html .= '<p class="desc">Create multiple email templates. Supports live preview while editing.</p>';
			$html .= '<p>Made with mobile in mind<p>';

			if (self::usesEmailTemplateAddOn()) {
				$html .= '<span>Active</span>';
			} else {
				$html .= '<span><a href="http://iplusstd.com/item/eventBookingPro/">View & Buy</a> or enable the Addon</span>';
			}

		$html .= '</div>';


		$html .= '<div class="addonCnt">';
			$html .= '<h3>Weekly Calendar</h3>';
			$html .= '<p class="desc">A weekly view of your events calendar.</p>';

			if (self::usesWeeklyView()) {
				$html .= '<span>Active</span>';
			} else {
				$html .= '<span><a href="http://iplusstd.com/item/eventBookingPro/example/weekly-calendar-addon/">View & Buy</a> or enable the Addon</span>';
			}

		$html .= '</div>';

		$html .= '<div class="addonCnt">';
			$html .= '<h3>Gift Card </h3>';
			$html .= '<p class="desc">Let your customer buy coupons as gifts and email them to the lucky people.</p>';

			if (self::usesGiftCardAddon()) {
				$html .= '<span>Active</span>';
			} else {
				$html .= '<span><a href="http://iplusstd.com/item/eventBookingPro/example/gift-card-addon/">View & Buy</a> or enable the Addon</span>';
			}

		$html .= '</div>';


		$html .= '<div class="addonCnt">';
			$html .= '<h3>Request any Payment Gateway</h3>';
			$html .= '<p class="desc">The plugin supports any custom payment gateway such as iDeal, WePay, ipaymu, Authorize, SagePay, Stripe, securepay ...</p>';
			$html .= '<span><a href="mailto:moe@iplusstd.com">Request Now</a></span>';

		$html .= '</div>';

		return $html;
	}
}

?>
