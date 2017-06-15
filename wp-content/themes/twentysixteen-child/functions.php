<?php
/**
* Display Registration form on front end 
*/



function pippin_registration_form() {
	// only show the registration form to non-logged-in members
	if(!is_user_logged_in()) {
		global $pippin_load_css;
		
		// set this to true so the CSS is loaded
		$pippin_load_css = true;
		
		// check to make sure user registration is enabled
		$registration_enabled = get_option('users_can_register');
	
		// only show the registration form if allowed
		if($registration_enabled) {
			$output = pippin_registration_form_fields();
		} else {
			$output = 'User registration is disabled But you can change your contact information form Dashboard page. Before that you should <a href="'.site_url().'/login/">login from here</a>.';
		}
		return $output;
	} else{
			$output = 'You have already logged in. Please <a href="'.wp_logout_url( site_url() ) .'">Click Here</a> to logout';
			return $output;
	}
}
add_shortcode('register_form', 'pippin_registration_form');




/**
* WP Error Handling
*/
function pippin_errors(){
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

/**
* Display Error message
*/
function pippin_show_error_messages() {
	if($codes = pippin_errors()->get_error_codes()) {
		echo '<div class="pippin_errors">';
		    // Loop error codes and display errors
		   foreach($codes as $code){
		        $message = pippin_errors()->get_error_message($code);
		        echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
		    }
		echo '</div>';
	}	
}

/**
* Display Registration form fields
*/
function pippin_registration_form_fields() {
	ob_start(); ?>
	<br/><br/>		
		<?php 
		// show any error messages after form submission
		pippin_show_error_messages(); ?>
<script type="text/javascript">(function() {
	if (!window.mc4wp) {
		window.mc4wp = {
			listeners: [],
			forms    : {
				on: function (event, callback) {
					window.mc4wp.listeners.push({
						event   : event,
						callback: callback
					});
				}
			}
		}
	}
})();
</script>		
	
<form id="mc4wp-form-1" class="mc4wp-form mc4wp-form-15" method="post" data-id="15" data-name="register"><div class="mc4wp-form-fields"><p>
	<label>Email address <span style="color:red;">*</span></label>
	<input type="email" name="EMAIL" placeholder="Your email address" required>
</p>

<p>
    <label>First Name <span style="color:red;">*</span></label>
    <input name="FNAME" required="" type="text" required>
</p>

<p>
    <label>Last Name <span style="color:red;">*</span></label>
    <input name="LNAME" required="" type="text" required>
</p>

<p>
    <label>Gender <span style="color:red;">*</span></label>
    <label>
        <input name="MMERGE3" value="Male" required checked="true" type="radio"> <span>Male</span>
    </label>
    <label>
        <input name="MMERGE3" value="Female" required type="radio"> <span>Female</span>
    </label>
</p>
<p>
    <label>Street Address <span style="color:red;">*</span></label>
    <input name="addr1" required type="text" required>
</p>


<p>
    <label>City <span style="color:red;">*</span></label>
    <input name="city" type="text" required>
</p>

<p>
    <label>State <span style="color:red;">*</span></label>
    <input name="state" type="text" required>
</p>
<p>
    <label>ZIP <span style="color:red;">*</span></label>
    <input name="zip" type="text" required>
</p>
<p>
    <label>Country <span style="color:red;">*</span></label>
    <select name="country" required>
        <option value="AF">Afghanistan</option>
        <option value="AX">Aland Islands</option>
        <option value="AL">Albania</option>
        <option value="DZ">Algeria</option>
        <option value="AS">American Samoa</option>
        <option value="AD">Andorra</option>
        <option value="AO">Angola</option>
        <option value="AI">Anguilla</option>
        <option value="AQ">Antarctica</option>
        <option value="AG">Antigua and Barbuda</option>
        <option value="AR">Argentina</option>
        <option value="AM">Armenia</option>
        <option value="AW">Aruba</option>
        <option value="AU">Australia</option>
        <option value="AT">Austria</option>
        <option value="AZ">Azerbaijan</option>
        <option value="BS">Bahamas</option>
        <option value="BH">Bahrain</option>
        <option value="BD">Bangladesh</option>
        <option value="BB">Barbados</option>
        <option value="BY">Belarus</option>
        <option value="BE">Belgium</option>
        <option value="BZ">Belize</option>
        <option value="BJ">Benin</option>
        <option value="BM">Bermuda</option>
        <option value="BT">Bhutan</option>
        <option value="BO">Bolivia</option>
        <option value="BQ">Bonaire, Saint Eustatius and Saba</option>
        <option value="BA">Bosnia and Herzegovina</option>
        <option value="BW">Botswana</option>
        <option value="BV">Bouvet Island</option>
        <option value="BR">Brazil</option>
        <option value="IO">British Indian Ocean Territory</option>
        <option value="VG">British Virgin Islands</option>
        <option value="KH">Cambodia</option>
        <option value="CM">Cameroon</option>
        <option value="CA">Canada</option>
        <option value="CN">China</option>
        <option value="CX">Christmas Island</option>
        <option value="CC">Cocos Islands</option>
        <option value="CO">Colombia</option>
        <option value="KM">Comoros</option>
        <option value="HR">Croatia</option>
        <option value="CU">Cuba</option>
        <option value="CW">Curacao</option>
        <option value="CY">Cyprus</option>
       	<option value="FR">France</option>
        <option value="VI">U.S. Virgin Islands</option>
        <option value="UG">Uganda</option>
        <option value="UA">Ukraine</option>
        <option value="AE">United Arab Emirates</option>
        <option value="GB">United Kingdom</option>
        <option value="US">United States</option>
        <option value="UM">United States Minor Outlying Islands</option>
        <option value="UY">Uruguay</option>
        <option value="UZ">Uzbekistan</option>
        <option value="VN">Vietnam</option>
        <option value="WF">Wallis and Futuna</option>
        <option value="EH">Western Sahara</option>
        <option value="YE">Yemen</option>
        <option value="ZM">Zambia</option>
        <option value="ZW">Zimbabwe</option>
    </select>
</p>

<p>
    <label>Contact Phone <span style="color:red;">*</span></label>
    <input name="MMERGE5" required="" type="tel" required>
</p>
<p>
    <label>Emergency Contact Name</label>
    <input name="MMERGE6" type="text">
</p>
<p>
    <label>Emergency Contact Phone</label>
    <input name="MMERGE7" type="text">
</p>
    <label>Password <span style="color:red;">*</span></label>
    <input name="password" type="password" required>
</p>
<p>
	<label>
		<input type="checkbox" name="mc4wp-subscribe" value="1">
		Keep me on your mailing list.	
	</label>
</p>
<input name="add" type="hidden" value="add">
<p>
	<input type="submit" value="Sign up">
</p>

<div style="display: none;"><input type="text" name="_mc4wp_honeypot" value="" tabindex="-1" autocomplete="off"></div><input type="hidden" name="_mc4wp_timestamp" value="1493713011"><input type="hidden" name="_mc4wp_form_id" value="15"><input type="hidden" name="_mc4wp_form_element_id" value="mc4wp-form-1"></div><div class="mc4wp-response"></div></form>
	<?php
	return ob_get_clean();
}


/**
* Method for adding User information to database - user registration
*/
function pippin_add_new_member() {
  	if (isset( $_POST["EMAIL"] ) && isset($_POST['add'])) {
  		session_start();
  		$user_login = $_POST["EMAIL"];
		$pippin_user_first		= $_POST["FNAME"];	
		$b_email		= $_POST["EMAIL"];
		$pippin_user_last	 	= $_POST["LNAME"];
		$pippin_user_gender 	= $_POST["MMERGE3"];
		$pippin_user_address1 	= $_POST["addr1"];
		$user_city = $_POST["city"];
		$user_state = $_POST["state"];
		$user_zip = $_POST["zip"];
		$user_country = $_POST["country"];
		$pippin_user_phone 	= $_POST["MMERGE5"];
		$imergency_contact_name 	= $_POST["MMERGE6"];
		$imergency_contact_phone 	= $_POST["MMERGE7"];
		$user_pwd 	= $_POST["password"];
		
		// this is required for username checks
		require_once(ABSPATH . WPINC . '/registration.php');
		
		if(!is_email($b_email)) {
			//invalid email
			pippin_errors()->add('email_invalid', __('Invalid email'));
		}
		if(email_exists($b_email)) {
			//Email address already registered
			pippin_errors()->add('email_used', __('Email already registered'));
		}
		
		$errors = pippin_errors()->get_error_messages();
		
		// only create the user in if there are no errors
		if(empty($errors)) {
			
			$new_user_id = wp_insert_user(array(
					'user_login'		=> $b_email,
					'user_email'		=> $b_email,
					'first_name'		=> $pippin_user_first,
					'last_name'			=> $pippin_user_last,
					'user_pass'         => $user_pwd,
					'user_registered'	=> date('Y-m-d H:i:s'),
					'role'				=> 'subscriber'
				)
			);
            
			update_user_meta( $new_user_id, 'pippin_user_gender', $pippin_user_gender );
			update_user_meta( $new_user_id, 'pippin_user_address1', $pippin_user_address1 );
			update_user_meta( $new_user_id, 'pippin_user_state', $user_state );
			update_user_meta( $new_user_id, 'pippin_user_city', $user_city );
			update_user_meta( $new_user_id, 'pippin_user_country', $user_country );
			update_user_meta( $new_user_id, 'pippin_user_zip', $user_zip );
			update_user_meta( $new_user_id, 'pippin_user_phone', $pippin_user_phone );
			update_user_meta( $new_user_id, 'imergency_contact_name', $imergency_contact_name );
			update_user_meta( $new_user_id, 'imergency_contact_phone', $imergency_contact_phone );
		
			if($new_user_id) {
			      $to = $b_email;
                  $subject = 'Registration Successful.';
                  $sender = 'info';
                  $fromemail='online-registration@musicalretreat.org';
                  $message .= 'Thanks for registering with us.<br>';

				$message .= 'Here is your login info:<br>';

				$message .= "Login id = ".$b_email. "<br>";
				$message .= "Password = ".$user_pwd. "<br>";
				$message .= "<a href='".site_url()."/login'>Please click here to login.</a><br>";
				$message .= "Never share your login info with anyone.<br>";

                  $headers[] = 'MIME-Version: 1.0' . "\r\n";
                  $headers[] = 'Content-type: text/html; charset=ISO-8859-1' . "\r\n";

                  $headers[] = "X-Mailer: PHP \r\n";
                  $headers[] = 'From: '.$sender.' < '.$fromemail.'>' . "\r\n";
                  $mail = wp_mail( $to, $subject, $message, $headers );
				  $red=site_url().'/login';
				  session_start();
				  $_SESSION['succmsg'] = 'You are successfully registered.';
				  wp_redirect($red); exit;
			}
		}
		else{	
		}
	
	}
}
add_action('init', 'pippin_add_new_member');

/**
* Method for updating user profile info from dashboard
*/
function pippin_update_member() {
  	if (isset( $_POST["EMAIL"] ) && isset($_POST['update'])) {
  		session_start();
  		$user_id=get_current_user_id();
  		$user_login = $_POST["EMAIL"];
		$pippin_user_first		= $_POST["FNAME"];	
		$b_email		= $_POST["EMAIL"];
		$pippin_user_last	 	= $_POST["LNAME"];
		$pippin_user_gender 	= $_POST["MMERGE3"];
		$pippin_user_address1 	= $_POST["addr1"];
		$user_city = $_POST["city"];
		$user_state = $_POST["state"];
		$user_zip = $_POST["zip"];
		$user_country = $_POST["country"];
		$pippin_user_phone 	= $_POST["MMERGE5"];
		$imergency_contact_name 	= $_POST["MMERGE6"];
		$imergency_contact_phone 	= $_POST["MMERGE7"];
		
		// this is required for username checks
		require_once(ABSPATH . WPINC . '/registration.php');
		
		$errors = pippin_errors()->get_error_messages();
		
		// only create the user in if there are no errors
		if(empty($errors)) {
		    
			 $userdata = array(
                'ID'                => $user_id,
                'first_name'        => $pippin_user_first,
                'last_name'         => $pippin_user_last,
				'user_registered'	=> date('Y-m-d H:i:s')
            );
			$new_user_id = wp_update_user( $userdata );
		
			update_user_meta( $new_user_id, 'pippin_user_gender', $pippin_user_gender );
			update_user_meta( $new_user_id, 'pippin_user_address1', $pippin_user_address1 );
			update_user_meta( $new_user_id, 'pippin_user_state', $user_state );
			update_user_meta( $new_user_id, 'pippin_user_city', $user_city );
			update_user_meta( $new_user_id, 'pippin_user_country', $user_country );
			update_user_meta( $new_user_id, 'pippin_user_zip', $user_zip );
			update_user_meta( $new_user_id, 'pippin_user_phone', $pippin_user_phone );
			update_user_meta( $new_user_id, 'imergency_contact_name', $imergency_contact_name );
			update_user_meta( $new_user_id, 'imergency_contact_phone', $imergency_contact_phone );
		
			if($new_user_id) {
				// send the newly created user to the home page after logging them in
				$red=site_url('/dashboard/');
				wp_redirect($red); exit;
			}
			
		}
		else{
		}
	
	}
}
add_action('init', 'pippin_update_member');


/**
* Display Profile form
*/
function pippin_profile_form() {
	
	// only show the registration form to non-logged-in members
	if(is_user_logged_in()) {
		global $pippin_load_css;
		
		// set this to true so the CSS is loaded
		$pippin_load_css = true;
		
		// check to make sure user registration is enabled
		$output = pippin_profile_form_fields();
		return $output;
	}
}
add_shortcode('profile_form', 'pippin_profile_form');

/**
* Display Profile form fields on Dashboard
*/
function pippin_profile_form_fields() {
	$new_user_id=get_current_user_id();
	$user_info = get_userdata($new_user_id);
	$gender=get_user_meta( $new_user_id, 'pippin_user_gender', true );
	$address1=get_user_meta( $new_user_id, 'pippin_user_address1', true );
	$user_state=get_user_meta( $new_user_id, 'pippin_user_state', true );
	$user_city=get_user_meta( $new_user_id, 'pippin_user_city', true );
	$user_country=get_user_meta( $new_user_id, 'pippin_user_country', true );
	$user_zip=get_user_meta( $new_user_id, 'pippin_user_zip', true );
	$user_phone=get_user_meta( $new_user_id, 'pippin_user_phone', true );
	$user_imergency_name=get_user_meta( $new_user_id, 'imergency_contact_name', true );
	$user_imergency_phone=get_user_meta( $new_user_id, 'imergency_contact_phone', true );

	ob_start(); ?>
		<?php 
		// show any error messages after form submission
		pippin_show_error_messages(); ?>

<form id="mc4wp-form-1" class="mc4wp-form mc4wp-form-15" method="post" data-id="15" data-name="register"><div class="mc4wp-form-fields"><p>
	<label>Email address <span style="color:red;">*</span></label>
	<input type="email" name="EMAIL" placeholder="Your email address" value="<?php echo $user_info->user_email; ?>" required readonly>
</p>

<p>
    <label>First Name <span style="color:red;">*</span></label>
    <input name="FNAME" required="" type="text" value="<?php echo $user_info->first_name; ?>" required>
</p>

<p>
    <label>Last Name <span style="color:red;">*</span></label>
    <input name="LNAME" required="" type="text" value="<?php echo $user_info->last_name; ?>" required>
</p>

<p>
    <label>Gender <span style="color:red;">*</span></label>
    <label>
        <input name="MMERGE3" value="Male" required <?php if($gender=='Male') { echo 'checked="true"'; } else {echo '';}?> type="radio"> <span>Male</span>
    </label>
    <label>
        <input name="MMERGE3" value="Female" required <?php if($gender=='Female') { echo 'checked="true"'; } else {echo '';}?> type="radio"> <span>Female</span>
    </label>
</p>
<p>
    <label>Street Address <span style="color:red;">*</span></label>
    <input name="addr1" required type="text" value="<?php echo $address1; ?>">
</p>
<p>
    <label>City <span style="color:red;">*</span></label>
    <input name="city" type="text" required value="<?php echo $user_city; ?>">	
</p>

<p>
    <label>State <span style="color:red;">*</span></label>
    <input name="state" type="text" required value="<?php echo $user_state; ?>">
</p>
<p>
    <label>ZIP <span style="color:red;">*</span></label>
    <input name="zip" type="text" required value="<?php echo $user_zip; ?>">
</p>

<p>
    <label>Country <span style="color:red;">*</span></label>
    <select name="country" required>
        <option value="AF" <?php if($user_country=='AF') { echo $selected='selected'; } else {echo $selected='';}?>>Afghanistan</option>
        <option value="AX" <?php if($user_country=='AX') { echo $selected='selected'; } else {echo $selected='';}?>>Aland Islands</option>
        <option value="AL" <?php if($user_country=='AL') { echo $selected='selected'; } else {echo $selected='';}?>>Albania</option>
        <option value="DZ" <?php if($user_country=='DZ') { echo $selected='selected'; } else {echo $selected='';}?>>Algeria</option>
        <option value="AS" <?php if($user_country=='AS') { echo $selected='selected'; } else {echo $selected='';}?>>American Samoa</option>
        <option value="AD" <?php if($user_country=='AD') { echo $selected='selected'; } else {echo $selected='';}?>>Andorra</option>
        <option value="AO" <?php if($user_country=='AO') { echo $selected='selected'; } else {echo $selected='';}?>>Angola</option>
        <option value="AI" <?php if($user_country=='AI') { echo $selected='selected'; } else {echo $selected='';}?>>Anguilla</option>
        <option value="AQ" <?php if($user_country=='AQ') { echo $selected='selected'; } else {echo $selected='';}?>>Antarctica</option>
        <option value="AG" <?php if($user_country=='AG') { echo $selected='selected'; } else {echo $selected='';}?>>Antigua and Barbuda</option>
        <option value="AR" <?php if($user_country=='AR') { echo $selected='selected'; } else {echo $selected='';}?>>Argentina</option>
        <option value="AM" <?php if($user_country=='AM') { echo $selected='selected'; } else {echo $selected='';}?>>Armenia</option>
        <option value="AW" <?php if($user_country=='AW') { echo $selected='selected'; } else {echo $selected='';}?>>Aruba</option>
        <option value="AU" <?php if($user_country=='AU') { echo $selected='selected'; } else {echo $selected='';}?>>Australia</option>
        <option value="AT" <?php if($user_country=='AT') { echo $selected='selected'; } else {echo $selected='';}?>>Austria</option>
        <option value="AZ" <?php if($user_country=='AZ') { echo $selected='selected'; } else {echo $selected='';}?>>Azerbaijan</option>
        <option value="BS" <?php if($user_country=='BS') { echo $selected='selected'; } else {echo $selected='';}?>>Bahamas</option>
        <option value="BH" <?php if($user_country=='BH') { echo $selected='selected'; } else {echo $selected='';}?>>Bahrain</option>
        <option value="BD <?php if($user_country=='BD') { echo $selected='selected'; } else {echo $selected='';}?>">Bangladesh</option>
        <option value="BB" <?php if($user_country=='BB') { echo $selected='selected'; } else {echo $selected='';}?>>Barbados</option>
        <option value="BY" <?php if($user_country=='BY') { echo $selected='selected'; } else {echo $selected='';}?>>Belarus</option>
        <option value="BE" <?php if($user_country=='BE') { echo $selected='selected'; } else {echo $selected='';}?>>Belgium</option>
        <option value="BZ" <?php if($user_country=='BZ') { echo $selected='selected'; } else {echo $selected='';}?>>Belize</option>
        <option value="BJ" <?php if($user_country=='BJ') { echo $selected='selected'; } else {echo $selected='';}?>>Benin</option>
        <option value="BM" <?php if($user_country=='BM') { echo $selected='selected'; } else {echo $selected='';}?>>Bermuda</option>
        <option value="BT" <?php if($user_country=='BT') { echo $selected='selected'; } else {echo $selected='';}?>>Bhutan</option>
        <option value="BO" <?php if($user_country=='BO') { echo $selected='selected'; } else {echo $selected='';}?>>Bolivia</option>
        <option value="BQ" <?php if($user_country=='BQ') { echo $selected='selected'; } else {echo $selected='';}?>>Bonaire, Saint Eustatius and Saba</option>
        <option value="BA" <?php if($user_country=='BA') { echo $selected='selected'; } else {echo $selected='';}?>>Bosnia and Herzegovina</option>
        <option value="BW" <?php if($user_country=='BW') { echo $selected='selected'; } else {echo $selected='';}?>>Botswana</option>
        <option value="BV" <?php if($user_country=='BV') { echo $selected='selected'; } else {echo $selected='';}?>>Bouvet Island</option>
        <option value="BR" <?php if($user_country=='BR') { echo $selected='selected'; } else {echo $selected='';}?>>Brazil</option>
        <option value="IO" <?php if($user_country=='IO') { echo $selected='selected'; } else {echo $selected='';}?>>British Indian Ocean Territory</option>
        <option value="VG" <?php if($user_country=='VG') { echo $selected='selected'; } else {echo $selected='';}?>>British Virgin Islands</option>
        <option value="KH" <?php if($user_country=='KH') { echo $selected='selected'; } else {echo $selected='';}?>>Cambodia</option>
        <option value="CM" <?php if($user_country=='CM') { echo $selected='selected'; } else {echo $selected='';}?>>Cameroon</option>
        <option value="CA" <?php if($user_country=='CA') { echo $selected='selected'; } else {echo $selected='';}?>>Canada</option>
        <option value="CN" <?php if($user_country=='CN') { echo $selected='selected'; } else {echo $selected='';}?>>China</option>
        <option value="CX" <?php if($user_country=='CXF') { echo $selected='selected'; } else {echo $selected='';}?>>Christmas Island</option>
        <option value="CC" <?php if($user_country=='CC') { echo $selected='selected' ; } else {echo $selected='';}?>>Cocos Islands</option>
        <option value="CO" <?php if($user_country=='CO') { echo $selected='selected'; } else {echo $selected='';}?>>Colombia</option>
        <option value="KM" <?php if($user_country=='KM') { echo $selected='selected'; } else {echo $selected='';}?>>Comoros</option>
        <option value="HR" <?php if($user_country=='HR') { echo $selected='selected'; } else {echo $selected='';}?>>Croatia</option>
        <option value="CU" <?php if($user_country=='CU') { echo $selected='selected'; } else {echo $selected='';}?>>Cuba</option>
        <option value="CW" <?php if($user_country=='CW') { echo $selected='selected'; } else {echo $selected='';}?>>Curacao</option>
        <option value="CY" <?php if($user_country=='CY') { echo $selected='selected'; } else {echo $selected='';}?>>Cyprus</option>
       	<option value="FR" <?php if($user_country=='FR') { echo $selected='selected'; } else {echo $selected='';}?>>France</option>
        <option value="VI" <?php if($user_country=='VI') { echo $selected='selected'; } else {echo $selected='';}?>>U.S. Virgin Islands</option>
        <option value="UG" <?php if($user_country=='UG') { echo $selected='selected'; } else {echo $selected='';}?>>Uganda</option>
        <option value="UA" <?php if($user_country=='UA') { echo $selected='selected'; } else {echo $selected='';}?>>Ukraine</option>
        <option value="AE" <?php if($user_country=='AE') { echo $selected='selected'; } else {echo $selected='';}?>>United Arab Emirates</option>
        <option value="GB" <?php if($user_country=='GB') { echo $selected='selected'; } else {echo $selected='';}?>>United Kingdom</option>
        <option value="US" <?php if($user_country=='US') { echo $selected='selected'; } else {echo $selected='';}?>>United States</option>
        <option value="UM" <?php if($user_country=='UM') { echo $selected='selected'; } else {echo $selected='';}?>>United States Minor Outlying Islands</option>
        <option value="UY" <?php if($user_country=='UY') { echo $selected='selected'; } else {echo $selected='';}?>>Uruguay</option>
        <option value="UZ" <?php if($user_country=='UZ') { echo $selected='selected'; } else {echo $selected='';}?>>Uzbekistan</option>
        <option value="VN" <?php if($user_country=='VN') { echo $selected='selected'; } else {echo $selected='';}?>>Vietnam</option>
        <option value="WF" <?php if($user_country=='WF') { echo $selected='selected'; } else {echo $selected='';}?>>Wallis and Futuna</option>
        <option value="EH" <?php if($user_country=='EH') { echo $selected='selected'; } else {echo $selected='';}?>>Western Sahara</option>
        <option value="YE" <?php if($user_country=='YE') { echo $selected='selected'; } else {echo $selected='';}?>>Yemen</option>
        <option value="ZM" <?php if($user_country=='ZM') { echo $selected='selected'; } else {echo $selected='';}?>>Zambia</option>
        <option value="ZW" <?php if($user_country=='ZW') { echo $selected='selected'; } else {echo $selected='';}?>>Zimbabwe</option>
    </select>
</p>

<p>
    <label>Contact Phone <span style="color:red;">*</span></label>
    <input name="MMERGE5" type="tel" required value="<?php echo $user_phone; ?>">
</p>

<p>
    <label>Emergency Contact Name</label>
    <input name="MMERGE6" type="text" value="<?php echo $user_imergency_name; ?>">
</p>
<p>
    <label>Emergency Contact Phone</label>
    <input name="MMERGE7" type="text" value="<?php echo $user_imergency_phone; ?>">
</p>

 <input name="update" type="hidden" required value="update">
<p>
	<input type="submit" value="Update Profile">
</p>
</div>
</form>

	<?php
	return ob_get_clean();
}

add_filter( 'wp_nav_menu_items', 'wti_loginout_menu_link', 10, 2 );
function wti_loginout_menu_link( $items, $args ) {
   if ($args->theme_location == 'primary') {
      if (is_user_logged_in()) {
      	$items .= '<li class="right"><a href="'. get_permalink(23) .'">'. __("Dashboard") .'</a></li>';
         $items .= '<li class="right"><a href="'. wp_logout_url(site_url()) .'">'. __("Log Out") .'</a></li>';
      } else {
         $items .= '<li class="right"><a href="'. get_permalink(31) .'">'. __("Log In") .'</a></li>';
         $items .= '<li class="right"><a href="'. get_permalink(16) .'">'. __("Register") .'</a></li>';
      }
   }
   return $items;
}

/**
* Display Login Form
*/
function pippin_login_form() {
	
	if(!is_user_logged_in()) {
		session_start();
		echo "<div style='color: green;'>";
		echo $_SESSION['succmsg'];
		echo "</div>";
		unset($_SESSION['succmsg']);
		global $pippin_load_css;
		
		// set this to true so the CSS is loaded
		$pippin_load_css = true;
		
		$output = pippin_login_form_fields();
	} else {
		// could show some logged in user info here
	}
	return $output;
}
add_shortcode('login_form', 'pippin_login_form');


/**
* Login Form Fields, used in pippin_login_form
*/
function pippin_login_form_fields() {
		
	ob_start(); ?>
		<?php
		// show any error messages after form submission
		pippin_show_error_messages();
        if($_GET['msg']){
            echo 'Please check your email address for password.';
        }  ?>
        
		<form action="" class="login-form" method="post">
			<div class="form-group">
				<p>
				  <input  id="pippin_user_login1" name="pippin_user_login" placeholder="Email Address" class="pippin_user_login1" type="text" value="" required/></p>
				  
				<p>  <input id="password" name="pippin_user_pass" type="password" placeholder="Your password" class="required" value="" required>
				  <input type="hidden" name="pippin_login_nonce" value="<?php echo wp_create_nonce('pippin-login-nonce'); ?>"/>
				</p>
			</div>
			<span class="text-gray remember"><input type="checkbox" id="remember" value=""> <label for="remember">Remember me</label></span>
			<p> <input id="pippin_login_submit" class="btn btn-sigin" type="submit" value="Sign me in!"/></p>
			  <br style="clear:both;">
			<a class="link" href="<?php echo site_url();?>/forgot-password/">Forgot password?</a> <a class="link" href="<?php echo site_url();?>/register/">Sign up</a>
			<hr><br>
        </form>
	<?php
	return ob_get_clean();
}

/**
* Method for login and redirecting to dashboard area.
*/
function pippin_login_member() {
	if(isset($_POST['pippin_user_login']) && wp_verify_nonce($_POST['pippin_login_nonce'], 'pippin-login-nonce')) {
				
		// this returns the user ID and other info from the user name
		$user = get_userdatabylogin($_POST['pippin_user_login']);
		
		if(!$user) {
			// if the user name doesn't exist
			pippin_errors()->add('empty_username', __('Invalid username'));
		}
		
		if(!isset($_POST['pippin_user_pass']) || $_POST['pippin_user_pass'] == '') {
			// if no password was entered
			pippin_errors()->add('empty_password', __('Please enter a password'));
		}
				
		// check the user's login with their password
		if(!wp_check_password($_POST['pippin_user_pass'], $user->user_pass, $user->ID)) {
			// if the password is incorrect for the specified user
			pippin_errors()->add('empty_password', __('Incorrect password'));
		}
		
		// retrieve all error messages
		$errors = pippin_errors()->get_error_messages();
		
		// only log the user in if there are no errors
		if(empty($errors)) {
			wp_setcookie($_POST['pippin_user_login'], $_POST['pippin_user_pass'], true);
			wp_set_current_user($user->ID, $_POST['pippin_user_login']);	
			$red=home_url().'/dashboard/';
			wp_redirect($red); exit;
		}
	}
}
add_action('init', 'pippin_login_member');


/**
* Display extra user profile information for admin edit user screen
*/
add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );
function my_show_extra_profile_fields( $user ) {
	$gender=get_user_meta( $user->ID, 'pippin_user_gender', true );
	$address1=get_user_meta( $user->ID, 'pippin_user_address1', true );
	$user_state=get_user_meta( $user->ID, 'pippin_user_state', true );
	$user_city=get_user_meta( $user->ID, 'pippin_user_city', true );
	$user_country=get_user_meta( $user->ID, 'pippin_user_country', true );
	$user_zip=get_user_meta( $user->ID, 'pippin_user_zip', true );
	$user_phone=get_user_meta( $user->ID, 'pippin_user_phone', true );
	$user_imergency_name=get_user_meta( $user->ID, 'imergency_contact_name', true );
	$user_imergency_phone=get_user_meta( $user->ID, 'imergency_contact_phone', true );
?>

	<h3>Other profile information</h3>

	<table class="form-table">
		<tr>
		    <th><label for="Gender">Gender</label></th>
           	<td>
                <label>
                    <input name="MMERGE3" value="Male" required <?php if($gender=='Male') { echo 'checked="true"'; } else {echo '';}?> type="radio"> <span>Male</span>
                </label>
                <label>
                    <input name="MMERGE3" value="Female" required <?php if($gender=='Female') { echo 'checked="true"'; } else {echo '';}?> type="radio"> <span>Female</span>
                </label>
            </td>
        </tr>
        <tr>
            <th><label>Street Address </label></th>
            <td><input name="addr1" required type="text" value="<?php echo $address1; ?>"></td>
       	</tr>
        <tr>
            <th><label>City </label></th>
            <td><input name="city" type="text" required value="<?php echo $user_city; ?>"></td>
        </tr>
        
        <tr>
            <th><label>State </label></th>
            <td><input name="state" type="text" required value="<?php echo $user_state; ?>"></td>
        </tr>
        <tr>
            <th><label>ZIP </label></th>
            <td><input name="zip" type="text" required value="<?php echo $user_zip; ?>"></td>
        </tr>
        <tr>
            <th><label>Country </label></th>
            <td><select name="country" required>
                <option value="AF" <?php if($user_country=='AF') { echo $selected='selected'; } else {echo $selected='';}?>>Afghanistan</option>
                <option value="AX" <?php if($user_country=='AX') { echo $selected='selected'; } else {echo $selected='';}?>>Aland Islands</option>
                <option value="AL" <?php if($user_country=='AL') { echo $selected='selected'; } else {echo $selected='';}?>>Albania</option>
                <option value="DZ" <?php if($user_country=='DZ') { echo $selected='selected'; } else {echo $selected='';}?>>Algeria</option>
                <option value="AS" <?php if($user_country=='AS') { echo $selected='selected'; } else {echo $selected='';}?>>American Samoa</option>
                <option value="AD" <?php if($user_country=='AD') { echo $selected='selected'; } else {echo $selected='';}?>>Andorra</option>
                <option value="AO" <?php if($user_country=='AO') { echo $selected='selected'; } else {echo $selected='';}?>>Angola</option>
                <option value="AI" <?php if($user_country=='AI') { echo $selected='selected'; } else {echo $selected='';}?>>Anguilla</option>
                <option value="AQ" <?php if($user_country=='AQ') { echo $selected='selected'; } else {echo $selected='';}?>>Antarctica</option>
                <option value="AG" <?php if($user_country=='AG') { echo $selected='selected'; } else {echo $selected='';}?>>Antigua and Barbuda</option>
                <option value="AR" <?php if($user_country=='AR') { echo $selected='selected'; } else {echo $selected='';}?>>Argentina</option>
                <option value="AM" <?php if($user_country=='AM') { echo $selected='selected'; } else {echo $selected='';}?>>Armenia</option>
                <option value="AW" <?php if($user_country=='AW') { echo $selected='selected'; } else {echo $selected='';}?>>Aruba</option>
                <option value="AU" <?php if($user_country=='AU') { echo $selected='selected'; } else {echo $selected='';}?>>Australia</option>
                <option value="AT" <?php if($user_country=='AT') { echo $selected='selected'; } else {echo $selected='';}?>>Austria</option>
                <option value="AZ" <?php if($user_country=='AZ') { echo $selected='selected'; } else {echo $selected='';}?>>Azerbaijan</option>
                <option value="BS" <?php if($user_country=='BS') { echo $selected='selected'; } else {echo $selected='';}?>>Bahamas</option>
                <option value="BH" <?php if($user_country=='BH') { echo $selected='selected'; } else {echo $selected='';}?>>Bahrain</option>
                <option value="BD <?php if($user_country=='BD') { echo $selected='selected'; } else {echo $selected='';}?>">Bangladesh</option>
                <option value="BB" <?php if($user_country=='BB') { echo $selected='selected'; } else {echo $selected='';}?>>Barbados</option>
                <option value="BY" <?php if($user_country=='BY') { echo $selected='selected'; } else {echo $selected='';}?>>Belarus</option>
                <option value="BE" <?php if($user_country=='BE') { echo $selected='selected'; } else {echo $selected='';}?>>Belgium</option>
                <option value="BZ" <?php if($user_country=='BZ') { echo $selected='selected'; } else {echo $selected='';}?>>Belize</option>
                <option value="BJ" <?php if($user_country=='BJ') { echo $selected='selected'; } else {echo $selected='';}?>>Benin</option>
                <option value="BM" <?php if($user_country=='BM') { echo $selected='selected'; } else {echo $selected='';}?>>Bermuda</option>
                <option value="BT" <?php if($user_country=='BT') { echo $selected='selected'; } else {echo $selected='';}?>>Bhutan</option>
                <option value="BO" <?php if($user_country=='BO') { echo $selected='selected'; } else {echo $selected='';}?>>Bolivia</option>
                <option value="BQ" <?php if($user_country=='BQ') { echo $selected='selected'; } else {echo $selected='';}?>>Bonaire, Saint Eustatius and Saba</option>
                <option value="BA" <?php if($user_country=='BA') { echo $selected='selected'; } else {echo $selected='';}?>>Bosnia and Herzegovina</option>
                <option value="BW" <?php if($user_country=='BW') { echo $selected='selected'; } else {echo $selected='';}?>>Botswana</option>
                <option value="BV" <?php if($user_country=='BV') { echo $selected='selected'; } else {echo $selected='';}?>>Bouvet Island</option>
                <option value="BR" <?php if($user_country=='BR') { echo $selected='selected'; } else {echo $selected='';}?>>Brazil</option>
                <option value="IO" <?php if($user_country=='IO') { echo $selected='selected'; } else {echo $selected='';}?>>British Indian Ocean Territory</option>
                <option value="VG" <?php if($user_country=='VG') { echo $selected='selected'; } else {echo $selected='';}?>>British Virgin Islands</option>
                <option value="KH" <?php if($user_country=='KH') { echo $selected='selected'; } else {echo $selected='';}?>>Cambodia</option>
                <option value="CM" <?php if($user_country=='CM') { echo $selected='selected'; } else {echo $selected='';}?>>Cameroon</option>
                <option value="CA" <?php if($user_country=='CA') { echo $selected='selected'; } else {echo $selected='';}?>>Canada</option>
                <option value="CN" <?php if($user_country=='CN') { echo $selected='selected'; } else {echo $selected='';}?>>China</option>
                <option value="CX" <?php if($user_country=='CXF') { echo $selected='selected'; } else {echo $selected='';}?>>Christmas Island</option>
                <option value="CC" <?php if($user_country=='CC') { echo $selected='selected' ; } else {echo $selected='';}?>>Cocos Islands</option>
                <option value="CO" <?php if($user_country=='CO') { echo $selected='selected'; } else {echo $selected='';}?>>Colombia</option>
                <option value="KM" <?php if($user_country=='KM') { echo $selected='selected'; } else {echo $selected='';}?>>Comoros</option>
                <option value="HR" <?php if($user_country=='HR') { echo $selected='selected'; } else {echo $selected='';}?>>Croatia</option>
                <option value="CU" <?php if($user_country=='CU') { echo $selected='selected'; } else {echo $selected='';}?>>Cuba</option>
                <option value="CW" <?php if($user_country=='CW') { echo $selected='selected'; } else {echo $selected='';}?>>Curacao</option>
                <option value="CY" <?php if($user_country=='CY') { echo $selected='selected'; } else {echo $selected='';}?>>Cyprus</option>
               	<option value="FR" <?php if($user_country=='FR') { echo $selected='selected'; } else {echo $selected='';}?>>France</option>
                <option value="VI" <?php if($user_country=='VI') { echo $selected='selected'; } else {echo $selected='';}?>>U.S. Virgin Islands</option>
                <option value="UG" <?php if($user_country=='UG') { echo $selected='selected'; } else {echo $selected='';}?>>Uganda</option>
                <option value="UA" <?php if($user_country=='UA') { echo $selected='selected'; } else {echo $selected='';}?>>Ukraine</option>
                <option value="AE" <?php if($user_country=='AE') { echo $selected='selected'; } else {echo $selected='';}?>>United Arab Emirates</option>
                <option value="GB" <?php if($user_country=='GB') { echo $selected='selected'; } else {echo $selected='';}?>>United Kingdom</option>
                <option value="US" <?php if($user_country=='US') { echo $selected='selected'; } else {echo $selected='';}?>>United States</option>
                <option value="UM" <?php if($user_country=='UM') { echo $selected='selected'; } else {echo $selected='';}?>>United States Minor Outlying Islands</option>
                <option value="UY" <?php if($user_country=='UY') { echo $selected='selected'; } else {echo $selected='';}?>>Uruguay</option>
                <option value="UZ" <?php if($user_country=='UZ') { echo $selected='selected'; } else {echo $selected='';}?>>Uzbekistan</option>
                <option value="VN" <?php if($user_country=='VN') { echo $selected='selected'; } else {echo $selected='';}?>>Vietnam</option>
                <option value="WF" <?php if($user_country=='WF') { echo $selected='selected'; } else {echo $selected='';}?>>Wallis and Futuna</option>
                <option value="EH" <?php if($user_country=='EH') { echo $selected='selected'; } else {echo $selected='';}?>>Western Sahara</option>
                <option value="YE" <?php if($user_country=='YE') { echo $selected='selected'; } else {echo $selected='';}?>>Yemen</option>
                <option value="ZM" <?php if($user_country=='ZM') { echo $selected='selected'; } else {echo $selected='';}?>>Zambia</option>
                <option value="ZW" <?php if($user_country=='ZW') { echo $selected='selected'; } else {echo $selected='';}?>>Zimbabwe</option>
            </select></td>
        </tr>
        <tr>
            <th><label>Contact Phone</label></th>
            <td><input name="MMERGE5" type="text" required value="<?php echo $user_phone; ?>"></td>
        </tr>

        <tr>
            <th><label>Emergency Contact Name</label></th>
            <td><input name="MMERGE6" type="text" value="<?php echo $user_imergency_name; ?>"></td>
        </tr>
        <tr>
            <th><label>Emergency Contact Phone</label></th>
            <td><input name="MMERGE7" type="text" value="<?php echo $user_imergency_phone; ?>"></td>
        </tr>
	</table>
<?php }

/**
* my_save_extra_profile_fields - Update user profile fields on admin screen
*/
add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );
function my_save_extra_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_user_meta( $user_id, 'pippin_user_gender', $_POST['MMERGE3'] );
	update_user_meta( $user_id, 'pippin_user_address1', $_POST['addr1'] );
	update_user_meta( $user_id, 'pippin_user_state', $_POST['state'] );
	update_user_meta( $user_id, 'pippin_user_city', $_POST['city'] );
	update_user_meta( $user_id, 'pippin_user_country', $_POST['country'] );
	update_user_meta( $user_id, 'pippin_user_zip', $_POST['zip'] );
	update_user_meta( $user_id, 'pippin_user_phone', $_POST['MMERGE5'] );
	update_user_meta( $user_id, 'imergency_contact_name', $_POST['MMERGE6'] );
	update_user_meta( $user_id, 'imergency_contact_phone', $_POST['MMERGE7'] );
}

////////////////////////////////  Testing visual form builder SH May 15
// Form ID = 1 


add_filter( 'vfb_field_default', 'vfb_filter_field_default', 10, 4 );

function vfb_filter_field_default( $default, $field_type, $field_id, $form_id ){    
    $first = $last = '';

    switch ( $field_type ) :

        case 'name' :
            if ( 1 == $form_id && is_user_logged_in() ) :
                $current_user = wp_get_current_user();

                if ( !empty( $current_user->user_firstname ) && empty( $current_user->user_lastname ) )
                    $first = $current_user->user_firstname;
                else if ( empty( $current_user->user_firstname ) && !empty( $current_user->user_lastname ) )
                    $last = $current_user->user_lastname;
                else if ( !empty( $current_user->user_firstname ) && !empty( $current_user->user_lastname ) ) {
                    $first = $current_user->user_firstname;
                    $last = $current_user->user_lastname;
                }

                return "$first $last";
            endif;
	    break;

        case 'email' :
            if ( 114 == $form_id && is_user_logged_in() ) :
                $current_user = wp_get_current_user();

                if ( !empty( $current_user->user_email ) )
                    $default = $current_user->user_email;

                return $default;
            endif;
            break;
        
    endswitch;

    return $default;
}




/**
* display_logindata - Displays user information on Add/Edit Enrollment screen
*/
function display_logindata( $atts ) {
	$user = new WP_User(get_current_user_id());

	$all_meta_for_user = get_user_meta( $user->ID );

?>
        
         <li id="field_1_45" class="gfield field_sublabel_below field_description_below gfield_visibility_visible">
            <label class="gfield_label" for="input_1_45">First Name</label> : 
           <?php  print_r( $all_meta_for_user['first_name'][0] ); ?>
         </li>
         <li id="field_1_3" class="gfield field_sublabel_below field_description_below gfield_visibility_visible">
            <label class="gfield_label" for="input_1_3">Last Name</label> : 
            <?php  print_r( $all_meta_for_user['last_name'][0] ); ?>
         </li>
		 <li id="field_1_45" class="gfield field_sublabel_below field_description_below gfield_visibility_visible">
            <label class="gfield_label" for="input_1_45">Email</label> : 
            <?php print_r( $user->data->user_email ); ?>
         </li>
         <li id="field_1_44" class="gfield field_sublabel_below field_description_below gfield_visibility_visible">
            <label class="gfield_label">Gender</label> : 
           <?php  echo $all_meta_for_user['pippin_user_gender'][0];  ?>
         </li>
         <li id="field_1_4" class="gfield field_sublabel_below field_description_below gfield_visibility_visible">
            <label class="gfield_label" for="input_1_4">Street Address</label> : 
            
            <?php   print_r( $all_meta_for_user['pippin_user_address1'][0] ); ?>
         </li>
         <li id="field_1_5" class="gfield field_sublabel_below field_description_below gfield_visibility_visible">
            <label class="gfield_label" for="input_1_5">City</label> : 
            
            <?php print_r( $all_meta_for_user['pippin_user_city'][0] ); ?>
         </li>
		 <li id="field_1_6" class="gfield field_sublabel_below field_description_below gfield_visibility_visible">
            <label class="gfield_label" for="input_1_6">State</label> : 
            
            <?php print_r( $all_meta_for_user['pippin_user_state'][0] ); ?>
         </li>
         <li id="field_1_7" class="gfield field_sublabel_below field_description_below gfield_visibility_visible">
            <label class="gfield_label" for="input_1_7">Zip</label> : 
            
            <?php print_r( $all_meta_for_user['pippin_user_zip'][0] ); ?>
           
         </li>
		 <li id="" class="gfield field_sublabel_below field_description_below gfield_visibility_visible">
            <label class="gfield_label" for="">Country</label> : 
            
            <?php  echo $all_meta_for_user['pippin_user_country'][0]  ?>
         </li>
         <li id="" class="gfield field_sublabel_below field_description_below gfield_visibility_visible">
            <label class="gfield_label" for="">Contact Phone</label> : 
            
            <?php  echo $all_meta_for_user['pippin_user_phone'][0]  ?>
         </li>
		 <li id="field_1_9" class="gfield field_sublabel_below field_description_below gfield_visibility_visible">
            <label class="gfield_label" for="input_1_9">Emergency Contact Name</label>
            :
            <?php print_r( $all_meta_for_user['imergency_contact_name'][0] ); ?>
            
         </li>
         <li id="field_1_10" class="gfield field_sublabel_below field_description_below gfield_visibility_visible">
            <label class="gfield_label" for="input_1_10">Emergency Contact Phone</label>
            :
            <?php print_r( $all_meta_for_user['imergency_contact_phone'][0] ); ?>
            	
         </li>
         
		 <?php
  
}
add_shortcode( 'logindata', 'display_logindata' );

/**
* enrollment_button - Display enrollment button based on different criteria, that set by admin, ie, Enrollment Status, Questionaire Status, Payment
*/
function enrollment_button( $atts ) {

// Add User Payment data
$current_user = wp_get_current_user();
	global $wpdb;

	$user_last = get_user_meta( $current_user->ID );
	$user_info = get_userdata($current_user->ID);
	
	if($_REQUEST['mc_gross'] != ''){

		if($current_user->ID != ''){

		echo "<h2>Transcation successfully.</h2>";
		
		// Insert paid user data
		$payamount = $_REQUEST['mc_gross']  - $_REQUEST['mc_fee'];
		
		$sql = "INSERT INTO `payments` (payment_type, txnid, payer_email, payer_id, payer_status, first_name, last_name, payment_amount, payment_status, payment_fee, payment_gross, type,txn_type, receiver_id, notify_version, verify_sign, itemid, createdtime, user_ID) VALUES (
				'online',
				'".$_REQUEST['txn_id']."',
				'".$_REQUEST['payer_email']."',
				'".$_REQUEST['payer_id']."',
				'".$_REQUEST['payer_status']."',
				'".$_REQUEST['first_name']."',
				'".$_REQUEST['last_name']."',
				'".$payamount."',
				'".$_REQUEST['payment_status']."',
				'".$_REQUEST['payment_fee']."',
				'".$_REQUEST['payment_gross']."',
				'".$_REQUEST['payment_type']."',
				'".$_REQUEST['txn_type']."',
				'".$_REQUEST['receiver_id']."',
				'".$_REQUEST['notify_version']."',
				'".$_REQUEST['verify_sign']."',
				'".$_REQUEST['item_number']."',
				'".date("Y-m-d H:i:s")."',
				'".$current_user->ID."'
				)";

		$wpdb->query($sql);

		// User details for send mail
		$first_name = $user_last['first_name'][0];
		$last_name = $user_last['last_name'][0];
		$user_email = $user_info->user_email;

		$payer_id = $_REQUEST['payer_id'];
		$receiver_id = $_REQUEST['receiver_id'];
		$payment_gross = $_REQUEST['payment_gross'];
		$payment_fee = $_REQUEST['payment_fee'];
		$receiver_id = $_REQUEST['receiver_id'];
		$txn_id = $_REQUEST['txn_id'];
		// End User details for send mail

		// Send mail to user
		$to = $user_email;
        $subject = 'New Transcation';
        $sender = 'MMR Enrollment';
        $fromemail='charlessamuel733@gmail.com';

        $message .= "Hello ".$first_name. " ". $last_name."<br>";
        $message .= "New Transcation Successfully Added<br>";
        $message .= "Check Below Details";
        $message .= "<ul><li>Transcation ID: ".$txn_id."</li><li>Payer ID: ".$payer_id."</li><li>Receiver ID : ".$receiver_id."</li><li>Payment Amount: ".$payment_gross."</li></ul>";

        $headers[] = 'MIME-Version: 1.0' . "\r\n";
        $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers[] = "X-Mailer: PHP \r\n";
        $headers[] = 'From: '.$sender.' < '.$fromemail.'>' . "\r\n";
        
        $mail = wp_mail( $to, $subject, $message, $headers );
        // End send mail to user

        // Send mail to admin
       	$toadmin = get_option( 'admin_email' );;
        $subjectadmin = 'New Transcation';
        $senderadmin = 'MMR Enrollment';
        $fromemailadmin = $user_email;

        $messageadmin .= "Hello Administrator<br>";
        $messageadmin .= "New Transcation Successfully Added<br>";
        $messageadmin .= "Check Below Details";
        $messageadmin .= "<ul><li>User Name: ".$first_name. " ". $last_name."</li><li>Transcation ID: ".$txn_id."</li><li>Payer ID: ".$payer_id."</li><li>Receiver ID : ".$receiver_id."</li><li>Payment Amount: ".$payment_gross."</li></ul>";

        $headersadmin[] = 'MIME-Version: 1.0' . "\r\n";
        $headersadmin[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headersadmin[] = "X-Mailer: PHP \r\n";
        $headersadmin[] = 'From: '.$sender.' < '.$fromemail.'>' . "\r\n";
        
        $mail = wp_mail( $toadmin, $subjectadmin, $messageadmin, $headersadmin );
        // End send mail to admin
    	}
	}
// End Add User Payment data

echo "<h2>";
session_start();
echo "<div style='color: green;'>";
echo $_SESSION['succmsg'];
echo "</div>";
echo "</h2>";
unset($_SESSION['succmsg']);

$displaypaymentbutton = 0;
	if(!is_user_logged_in()){ 
	?>
	<script>
	jQuery(document).ready(function(){
		window.location.href = '<?php  echo site_url(); ?>';
	});
	</script>
	
	<?php
	}
else{

	// check is free or paid

	// 218 id of enrollment page
	// $is_paid = get_post_meta( 218, 'is_paid', true );
	// // Check if the custom field has a value.
	
	// if ( ! empty( $is_paid ) ) {
	// 	if($is_paid == 'no')
	// 	{
	// 	}	
	// 	else{
	// 		echo "<a href='#'>Make Payment</a><br>";
	// 	}
	// }
	// End check is free or paid
	
	// Link For enrollment form
	$status = get_post_meta( 218, 'enrollment_status', true );
		
		$user = wp_get_current_user();
			
			global $wpdb;
			$querystr = "
						SELECT * 
						FROM ".$wpdb->prefix."rg_lead
						WHERE created_by = $user->ID
						AND form_id = 1
						";

			$check_user = $wpdb->get_results($querystr, OBJECT);
			$entry_id = array();
			foreach ($check_user as $formdata) {
				$FormID = $formdata->form_id;
				$entry_id[] = $formdata->id;
			}

			if($FormID == ''){
				// if status is open
				if($status == 'open'){
					$current_user = wp_get_current_user();
					if($current_user->roles[0] == 'administrator'){
						echo "<a href='".site_url()."/admin-enrollment-form/'> View All Enrolled Form </a>";
						$displaypaymentbutton = 1;
					}
					else{
						echo "<a href='".site_url()."/enrollment-form/'> Add Enrollment Form </a>";

					}
				}
				// End if status is open
				// if status is close
				else{
					echo get_post_meta( 60, 'enrollment_status_text', true );
				}
				//End  if status is close
			}
			else{
				// if status is open
				if($status == 'open'){

					$current_user = wp_get_current_user();
					if($current_user->roles[0] == 'administrator'){
						echo "<a href='".site_url()."/admin-enrollment-form/'> View All Enrolled Form </a>";
						$displaypaymentbutton = 1;
					}
					else{

						echo do_shortcode('[gv_edit_entry_link entry_id="'.$entry_id[0].'" view_id="220"] Edit Enrollment [/gv_edit_entry_link]'); 
						$displaypaymentbutton = 1;
					}
					
				}
				// End if status is open
				// if status is close
				else{
					$current_user = wp_get_current_user();
					if($current_user->roles[0] == 'administrator'){
						echo "<a href='".site_url()."/admin-enrollment-form/'> View All Enrolled Form </a>";
						$displaypaymentbutton = 1;
					}
					else{

						echo do_shortcode('[gv_edit_entry_link entry_id="'.$entry_id[0].'" view_id="220"] View Enrollment [/gv_edit_entry_link]');
						$displaypaymentbutton = 1; 
					}

				}
			}
}

?>
<br><br>
<?php
 
$current_user = wp_get_current_user();
if($current_user->roles[0] != 'administrator'){
	global $wpdb;
	// Display user payment data
	$querypaymentdata = "
				SELECT * 
				FROM payments
				WHERE user_ID = ".$current_user->ID."
				";
	$paymentdata = $wpdb->get_results($querypaymentdata, OBJECT);
	if(!empty($paymentdata)){
	?>
		<a href="/user-transcation/">Show All Transactions</a>
	<?php
	}

	if($displaypaymentbutton != 0){
		global $wpdb;
		$querystr = "
					SELECT * 
					FROM gravity_amount
					WHERE user_id = ".$current_user->ID."
					";

		$get_data = $wpdb->get_results($querystr, OBJECT);
		$entry_id = array();
		$paymentamount = '';
		foreach ($get_data as $formdata) {
			$paymentamount = $formdata->amount;
		}

		// Get paid amount
		$qryamount = "
					SELECT * 
					FROM payments
					WHERE user_ID = ".$current_user->ID."
					";

		$get_amount = $wpdb->get_results($qryamount, OBJECT);
		$paidary = array();
		foreach ($get_amount as $amountdata) {
			$paidary[] = $amountdata->payment_amount;
		}
		$paidamount = array_sum($paidary);


 		$paymentamount = round($paymentamount - $paidamount);
 		if($paymentamount > 0){
		echo "<br><b>BALANCE : $";
		echo $paymentamount;
			?>
			<br>
			<a href="<?php echo site_url(); ?>/payment-options/">Make Payment</a>
			</b>
			<?php 
		}
		else if($paymentamount == 0){
			echo "<br>";
			echo "Your balance is $0";
		}
		else{
			echo "<br><b>BALANCE : $";
			echo $paymentamount;
		}
}


} 
else{
echo "<a href='".site_url()."/view-all-transcation/'>View All Transcations</a><br>";
echo "<a href='".site_url()."/view-all-notifications/'>View All Notifications</a>";
}
?>
<br><br>
<?php
}

add_shortcode( 'enrollment_option', 'enrollment_button' );


/**
* blockusers_init - Method restricts Normal user to wordpress admin Screen.
*/
add_action( 'init', 'blockusers_init' );
function blockusers_init() {
if ( is_admin() && ! current_user_can( 'administrator' ) &&
! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
wp_redirect( home_url() );
exit;
}
}



/**
* my_custom_fonts - Style for disable add new user option on Admin Screen
*/
add_action('admin_head', 'my_custom_fonts');

function my_custom_fonts() {
  echo '<style>
    .users-php .wrap .page-title-action,.users-php .wp-submenu li:nth-child(3),.user-edit-php .wrap .page-title-action, .user-edit-php .wp-submenu li:nth-child(3),.profile-php .wrap .page-title-action,.profile-php .wp-submenu li:nth-child(3),.users_page_wpfront-user-role-editor-assign-roles .wrap .page-title-action,.users_page_wpfront-user-role-editor-assign-roles .wp-submenu li:nth-child(3),#menu-users .wp-submenu-wrap li:nth-child(3){ display:none !important; }
    
  </style>';
}

/**
* Add payment data after submit grevity form
*/
add_action( 'gform_after_submission', 'set_post_content', 10, 2 );
function set_post_content( $entry, $form ) {

	if($_REQUEST['lid'] == ''){
		$entryID = $entry['id'];
	}
	else{
		$entryID = $_REQUEST['lid'];
	}
	global $wpdb;
	$querystr = "
				SELECT * 
				FROM ".$wpdb->prefix."rg_lead
				WHERE id = ".$entryID."
				";

	$check_user = $wpdb->get_results($querystr, OBJECT);
	$entry_id = array();
	$created_by = '';

	foreach ($check_user as $formdata) {
		$created_by = $formdata->created_by;
	}

	$qrygetuser = "
				SELECT * 
				FROM gravity_amount
				WHERE user_id = ".$created_by."
				";
	$getuser = $wpdb->get_results($qrygetuser, OBJECT);

	if (empty($getuser)) {
		global $wpdb;
		$sql = "INSERT INTO gravity_amount
	          	(`entry_id`,`user_id`,`amount`) 
	   			values (".$entryID.", ".$created_by.", ".$_REQUEST['amount'].")";
		$wpdb->query($sql);
	}
	else{
		global $wpdb;
		$sql = "UPDATE gravity_amount set
				amount = ".$_REQUEST['amount'].",
				entry_id = ".$entryID."
				WHERE user_id = ".$created_by."";

		$wpdb->query($sql);
	}
	$current_user = wp_get_current_user();
	if($current_user->roles[0] != 'administrator'){ 
		$redirect_url = site_url()."/dashboard/";
		wp_redirect( $redirect_url );
	}
}

/**
* Hook for get user role by form id
*/
add_action( 'wp_ajax_get_userrole', 'prefix_ajax_get_userrole' );
add_action( 'wp_ajax_nopriv_get_userrole', 'prefix_ajax_get_userrole' );

function prefix_ajax_get_userrole() {

	global $wpdb;
	$querystr = "
				SELECT * 
				FROM ".$wpdb->prefix."rg_lead
				WHERE id = ".$_REQUEST['formID']."
				";

	$check_user = $wpdb->get_results($querystr, OBJECT);
	$entry_id = array();
	foreach ($check_user as $formdata) {
		$created_by = $formdata->created_by;
		
		$user_info = get_userdata($created_by);
     
      echo implode(', ', $user_info->roles);
     
	}
exit;
}