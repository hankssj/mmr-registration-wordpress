<?php
require_once dirname( __FILE__ ) . '/include.php';

class EmailService {

  const SENT_SUCCESS = "sent";

  public static function sendCustomEmail($paymentID, $emailTemplate) {
    return self::createEmailAndSend($paymentID, "", $emailTemplate, true, true);
  }

  public static function createEmailAndSend($paymentID, $type="", $paramEmailTemplate = null, $paramForceToCustomer = false, $paramForceNotToAdmin = false) {
    global $wpdb;

    $settings = $wpdb->get_row("SELECT currencyBefore, priceDecimalCount, priceDecPoint, priceThousandsSep, currency, timeFormat, dateFormat, refundEmailTemplate, emailTemplate, refundOwnerEmailTemplate, ownerEmailTemplate, emailSubject, SMTP_EMAIL, SMTP_NAME, sendEmailToCustomer, sendEmailToAdmin FROM " . EbpDatabase::getTableName("settings")." where id=1 ");

    $sendEmailToCustomer = $paramForceToCustomer || $settings->sendEmailToCustomer != 'false';
    $sendEmailToAdmin = !$paramForceNotToAdmin && $settings->sendEmailToAdmin != 'false';

    if (!$sendEmailToCustomer && !$sendEmailToAdmin) {
      return false; // no need to generateEmails
    }

    $refundEmailTemplate = $settings->refundEmailTemplate;
    $refundOwnerEmailTemplate = $settings->refundOwnerEmailTemplate;
    $ownerEmailTemplate = $settings->ownerEmailTemplate;
    $emailSubject = $settings->emailSubject;

    $confirmationEmailTemplate = $settings->emailTemplate;

    $SMTP_EMAIL = $settings->SMTP_EMAIL;
    $SMTP_NAME = $settings->SMTP_NAME;


    $curSymbol = EventBookingHelpers::getSymbol($settings->currency);
    $date_format = EventBookingHelpers::convertDateFormat($settings->dateFormat);
    $time_format = $settings->timeFormat;

    $currencyBefore = $settings->currencyBefore;
    $priceDecimalCount = $settings->priceDecimalCount;
    $priceDecPoint = $settings->priceDecPoint;
    $priceThousandsSep = $settings->priceThousandsSep;

    // get payment data
    $payment = $wpdb->get_row("SELECT * FROM " . EbpDatabase::getTableName("payments")." where id='$paymentID'");
    $eventID = $payment->event_id;
    $ticketID = $payment->ticket_id;
    $dateID = $payment->date_id;
    $coupon = $payment->coupon;
    $payerName = $payment->name;
    $quantity = $payment->quantity;
    $paymentAmount = $payment->amount;
    $paymentAmountTaxed = $payment->amount_taxed;
    $taxRate = $payment->tax_rate;

    $payerEmail = $payment->email;
    $payment_date = $payment->date_paid;
    $txn_id = $payment->txn_id;
    $bookingType = $payment->type;
    $paymentExtras = $payment->extras;

    // format IDs
    $formattedID = str_pad($eventID, 10, '0', STR_PAD_LEFT);
    $paymentIDFormatted = str_pad($paymentID, 10, '0', STR_PAD_LEFT);


    // format Amount
    $paymentAmount = EventBookingHelpers::currencyPricingFormat($paymentAmount, $curSymbol, $currencyBefore,
      $priceDecimalCount, $priceDecPoint, $priceThousandsSep, '%cost%');

    // get event data
    $event = $wpdb->get_row("SELECT name, info, mapAddress, ownerEmail, emailTemplateID FROM " . EbpDatabase::getTableName("events")." where id='$eventID' ");
    $eventname = stripslashes($event->name);
    $eventDesc = $event->info;
    $eventAddress = $event->mapAddress;
    $eventOwnerEmail = $event->ownerEmail;
    $emailTemplateID = $event->emailTemplateID;
    if ($paramEmailTemplate != null) $emailTemplateID = $paramEmailTemplate;

    // get ticket data
    $eventTickets = $wpdb->get_row("SELECT name FROM " . EbpDatabase::getTableName("tickets")." where id='$ticketID'");
    $ticketName = $eventTickets->name;

    // get event occurrence data
    $eventDate = $wpdb->get_row("SELECT start_date, end_date, start_time, end_time FROM " .EbpDatabase::getTableName("eventDates")." where id='$dateID'");
    $startDate = utf8_encode(strftime($date_format, strtotime($eventDate->start_date)));
    $endDate = utf8_encode(strftime($date_format, strtotime($eventDate->end_date)));

    $start_time = date($time_format, strtotime($eventDate->start_time));
    $end_time = date($time_format, strtotime($eventDate->end_time));

    // get coupon data
    if ($coupon != "" && $coupon != "N.A.") {
      $couponData = $wpdb->get_row("SELECT code, amount FROM " . EbpDatabase::getTableName("coupons")." where code='$coupon'");
      if ($couponData != null ) {
        $couponMarkUp ="<p>You used a coupon (".$couponData->code.") and got a ".$couponData->amount." discount.</p>";
      }
    } else {
      $couponMarkUp = "";
    }

    // get QR and Bar code data
    $QRCODE_id = self::generateBookingIdQRCode($paymentID);
    $barcode_id = self::generateBarCode($paymentID);
    $QRCODE_txn_id = self::generateBookingIdQRCode($txn_id);
    $barcode_txn_id = self::generateBarCode($txn_id);

    // populate keywords with data
    $extraFieldsMarkUp = EbpBooking::detailsPrettyPrint($paymentExtras);

    $eventCatList = array_map(function ($object) { return $object->name; }, EbpCategories::getEventCategoriesFull($eventID));
    $event_categories = implode(', ', $eventCatList);

    $EmailVars = array (
      '%booking_QR_Code%',
      '%transactionID_QR_Code%',

      '%bar_code%',
      '%barcode_id%',
      '%barcode_transaction_id%',

      '%eventname%',
      '%event_desc%',
      '%event_address%',
      '%payer_name%',
      '%quantity%',

      '%ticketName%',
      '%payment_amount%',
      '%payment_amount_taxed%',
      '%tax_rate%',
      '%currency%',
      '%couponMarkUp%',

      '%start_time%',
      '%startDate%',
      '%end_time%',
      '%endDate%',

      '%paymentDate%',
      '%paymentID%',
      '%eventid%',
      '%payer_email%',
      '%transaction_id%',

      '%ticketID%',
      '%dateID%',
      '%allExtraFields%',
      '%eventid_formatted%',
      '%bookingType%',

      '%paymentIDFormatted%',
      '%event_categories%'
      );

    $emailVarsValues = array (
      $QRCODE_id,
      $QRCODE_txn_id,

      $barcode_id,
      $barcode_id,
      $barcode_txn_id,

      $eventname,
      $eventDesc,
      $eventAddress,
      $payerName,
      $quantity,

      $ticketName,
      $paymentAmount,
      $paymentAmountTaxed,
      $taxRate,
      $curSymbol,
      $couponMarkUp,

      $start_time,
      $startDate,
      $end_time,
      $endDate,

      $payment_date,
      $paymentID,
      $eventID,
      $payerEmail,
      $txn_id,

      $ticketID,
      $dateID,
      $extraFieldsMarkUp,
      $formattedID,
      $bookingType,

      $paymentIDFormatted,
      $event_categories
    );

    // add extra fields
    // depreciated
    // $extraFieldsArr = explode("%", $paymentExtras);
    // foreach($extraFieldsArr as $e) {
    //   $field = explode(":", $e);
    //   if (count ($field) > 1) {
    //     array_push($EmailVars, "%".$field[0]."%");
    //     array_push($emailVarsValues, $field[1]." ");
    //   }
    // }

    // send logic
    if ($type == "refund") {
      $generatedEmail = str_replace($EmailVars, $emailVarsValues, stripslashes($refundEmailTemplate));
    } else {

      // override emailTemplate from passed param.
      if ($paramEmailTemplate != null) {
        $confirmationEmailTemplate = $paramEmailTemplate;
      }


      if ($emailTemplateID != '-1') {
        $confirmEmailTempalteData = $wpdb->get_row("SELECT subject, message FROM " . EbpDatabase::getTableName("emailTemplates")." where id='$emailTemplateID'");
        if ($confirmEmailTempalteData != null) {
          $confirmationEmailTemplate = $confirmEmailTempalteData->message;
          $emailSubject = $confirmEmailTempalteData->subject;
        }
      }

      $generatedEmail = str_replace($EmailVars, $emailVarsValues, stripslashes($confirmationEmailTemplate));
    }

    $generatedEmail = preg_replace('/%.*%/', '', $generatedEmail) ;

    $generatedSubject = str_replace($EmailVars, $emailVarsValues, $emailSubject);

    $returnStatus = array();

    if ($sendEmailToCustomer) {
      $resultCustomer = self::sendEmail($payerName, $payerEmail, $generatedSubject, $generatedEmail);
      array_push($returnStatus, $resultCustomer);
    }

    if ($sendEmailToAdmin) {
      if ($type == "refund") {
        $generatedAdminEmail = str_replace($EmailVars, $emailVarsValues, stripslashes($refundOwnerEmailTemplate));
      } else {
        $generatedAdminEmail = str_replace($EmailVars, $emailVarsValues, stripslashes($ownerEmailTemplate));
      }

      $ownerEmail = (self::isValidEmail($eventOwnerEmail)) ? $eventOwnerEmail : $SMTP_EMAIL;
      $resultAdmin = self::sendEmail($SMTP_NAME, $ownerEmail, $generatedSubject, $generatedAdminEmail);

      array_push($returnStatus, $resultAdmin);
    }

    return $returnStatus;
  }

  public static function testEmail() {
    global $wpdb;
    $settings = $wpdb->get_row( "SELECT SMTP_NAME, SMTP_EMAIL  FROM " . EbpDatabase::getTableName("settings")." where id=1 ");

    return self::sendEmail($settings->SMTP_NAME, $settings->SMTP_EMAIL,
     "Event Booking Pro Plugin Test Email","The email system is working!");
  }

  public static function sendEmail($name, $email, $sub, $msg) {
    require_once( ABSPATH . WPINC . '/pluggable.php');

    global $wpdb;
    $settings = $wpdb->get_row("SELECT email_utf8, SMTP_PORT, SMTP_HOST, SMTP_EMAIL, SMTP_PASS, emailSSL, email_mode, SMTP_NAME FROM " . EbpDatabase::getTableName("settings") ." where id=1 ");

    $fromEmail = $settings->SMTP_EMAIL;
    $fromName = $settings->SMTP_NAME;

    if ($settings->email_mode == '4') {
      $headers = 'From: '.$fromName.' <'.$settings->SMTP_EMAIL.'>' . "\r\n";
      $headers .= 'Content-Type: text/html; charset="UTF-8" \r\n';
      $stat = wp_mail($email, $sub, $msg, $headers);

      return ($stat) ? self::SENT_SUCCESS : 'Error with wp mail';
    } else {
      if (!class_exists('PHPMailer')) {
        require_once dirname( __FILE__ ) . '/libs/class.phpmailer.php';
        require_once dirname( __FILE__ ) . '/libs/class.smtp.php';
        require_once dirname( __FILE__ ) . '/libs/class.pop3.php';
      }

      try {
        $mail = new PHPMailer(true);
        $mail->PluginDir = dirname( __FILE__ ).'/libs/';

        if ($settings->email_mode == '1') {
          $mail->IsMail();
        } else {
          $mail->IsSMTP();
        }

        $mail->SMTPAuth = true;

        if ($settings->emailSSL != "false") $mail->SMTPSecure = $settings->emailSSL;

        $mail->Port = $settings->SMTP_PORT;
        $mail->Host = $settings->SMTP_HOST;
        $mail->Username = $settings->SMTP_EMAIL;
        $mail->Password = $settings->SMTP_PASS;


        $mail->AddReplyTo($fromEmail, $fromName);
        $mail->From = $fromEmail;
        $mail->FromName = $fromName;

        $mail->AddAddress($email);

        if ($settings->email_utf8 == "true") {
          $mail->Subject = '=?utf-8?B?'.base64_encode($sub).'?=';
        } else {
          $mail->Subject = $sub;
        }

        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!";

        if ($settings->email_utf8 == "true") {
          $mail->CharSet = 'UTF-8';
        }

        $mail->IsHTML(true);
        $mail->MsgHTML($msg);


        if (!$mail->Send()) {
          return $mail->ErrorInfo;
        }

        return  self::SENT_SUCCESS;
      } catch (phpmailerException $e) {
        return  "Exception Error: ".$e->errorMessage() ;
      }
    }
  }

  private static function generateBookingIdQRCode($id) {
    require_once( ABSPATH . WPINC . '/link-template.php' );
    require_once( ABSPATH . WPINC . '/formatting.php' );

    $link = site_url('/wp-admin/admin.php?page=eventCheckIn%23/'.$id);
    $QRCODE = '<img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl='.htmlentities($link).'" title="Your Booking Id" />';

    return $QRCODE;
  }

  private static function generateBarCode($id) {
    require_once( ABSPATH . WPINC . '/link-template.php' );
    require_once( ABSPATH . WPINC . '/formatting.php' );

    $link = site_url('/wp-content/plugins/eventBookingProBarCode/barcode.php?id='.$id);
    $barcode = '<img src="'.$link.'" title="Barcode" />';

    return $barcode;
  }

  public static function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);
  }


  public static function getDefaultEmailTemplate() {
    return  '<html>
    <table width= "100%" border= "0" cellpadding= "20" cellspacing= "0" >
    <tr bgcolor= "#0099CC">
      <td align= "center" bgcolor= "#0099CC"><h1 style= "color:#FFF;">Email Title</h1></td>
    </tr>
    <tr>
      <td><h3>Dear %payer_name%,</h3>
      <p>This is an email to confirm that you booked %quantity% %ticketName% tickets for the event <b>%eventname%</b> for a total of %currency% %payment_amount%.</p>
      %couponMarkUp%
      <p>The event starts at %start_time% on %startDate%.</p>
      <p>Do not be late</p></td>
    </tr>
    <tr>
      <td style= "color:#333333;">Looking forward to seeing you.</td>
    </tr>
    </table></html>';
  }

  public static function getDefaultOwnerEmailTemplate() {
    return  '<html><table width= "100%" border= "0" cellpadding= "20" cellspacing= "0" >
      <tr>

      <td><h3>A booking (%bookingType%) just occured!</h3></p>
      <h4>Event: %eventname% (%eventid%)</h4>
      <em>Booking ID: </em> %paymentID%
      <br/><br/><em>Date:</em> %startDate% ( Date ID: %dateID%)
      <br/><br/><em>Start Time:</em> %start_time%
      <br/><em>Ticket:</em> %ticketName% (%ticketID%)
      <br/><em>Quantity:</em> %quantity%
      <br/><em>Coupon:</em> %couponMarkUp%
      <br/><em>Total Amount Due:</em> %payment_amount%
      <br/><br/><em>Transaction Date:</em> %paymentDate%
      <br/><em>Transaction ID:</em> %transaction_id%
      <p><h3>Details:</h3>
      <em>Name:</em> %payer_name%
      <br/><em>Email:</em> %payer_email%
      <br/><h3>Extra Fields:</h3>%allExtraFields%
      </td>
    </tr></table></html>';
  }

}

?>
