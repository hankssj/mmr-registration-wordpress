<?php
require_once dirname( __FILE__ ) . '/include.php';

class EbpDatabase {
  public static function getAllTables() {
    global $wpdb;

    return array(
      'events' => $wpdb->base_prefix . 'ebp_events',
      'eventDates' => $wpdb->base_prefix . 'ebp_events_occurrence',
      'settings' => $wpdb->base_prefix . 'ebp_settings',
      'payments' => $wpdb->base_prefix . 'ebp_payments',
      'coupons' => $wpdb->base_prefix . "ebp_coupons",
      'couponsUsed' => $wpdb->base_prefix . "ebp_coupons_used",
      'eventCoupons' => $wpdb->base_prefix . "ebp_event_coupons",
      'categories' => $wpdb->base_prefix . "ebp_categories",
      'categoryEvents' => $wpdb->base_prefix . "ebp_categories_events",
      'tickets' => $wpdb->base_prefix . "ebp_events_tickets",
      'forms' => $wpdb->base_prefix . "ebp_forms",
      'formsInput' => $wpdb->base_prefix . "ebp_forms_inputs",
      'gateways' => $wpdb->base_prefix . "ebp_gateways",
      'emailTemplates' =>$wpdb->base_prefix . "ebp_email_templates",
      'emailRules' =>$wpdb->base_prefix . "ebp_email_rules",
      'emailsPlanned' =>$wpdb->base_prefix . "ebp_email_planned",
      'eventLogs' => $wpdb->base_prefix . 'ebp_eventlogs',
      'giftCard' => $wpdb->base_prefix . 'ebp_gift_card'
    );
  }

  public static function getTableName($name) {
    $tables = EbpDatabase::getAllTables();
    return (string) $tables[$name];
  }

  public static function updateDatabase ($version) {
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $SQLS = array_merge(
        EbpCategories::getTablesSQL(),
        EbpCoupon::getTablesSQL(),
        EbpBooking::getTablesSQL(),
        Event::getTablesSQL(),
        LogsService::getTablesSQL(),
        EbpEventTickets::getTablesSQL(),
        EbpSettings::getTablesSQL());

    foreach ($SQLS as $sql) {
      dbDelta($sql);
    }

    $settingsTable = EbpDatabase::getTableName("settings");

    if (!get_option("ebp_version")) {
      $defaultTemplate = addslashes(EmailService::getDefaultEmailTemplate());
      $refundEmailTemplate = 'A refund was issued fot $eventname% booking!';
      $refundOwnerEmailTemplate = 'A refund was issued to %payer_name%(%payer_email%) on booking no %paymentID%';

      $defaultSettings = array('id'=> '1', "emailMsg"=> "Dont Be late","emailTemplate"=> $defaultTemplate,
            "refundEmailTemplate"=> $refundEmailTemplate, 'refundOwnerEmailTemplate'=> $refundOwnerEmailTemplate);

      $defaultSettings2 = array('id'=> '2', "emailMsg"=> "Dont Be late","boxAlign"=> "false","emailTemplate"=> $defaultTemplate,
          "refundEmailTemplate"=> $refundEmailTemplate, 'refundOwnerEmailTemplate'=> $refundOwnerEmailTemplate);

      $defaultSettings3 = array('id'=> '3', 'includeEndsOn' => 'false', "emailMsg"=> "Dont Be late","emailTemplate"=> $defaultTemplate,
              "refundEmailTemplate"=> $refundEmailTemplate, 'refundOwnerEmailTemplate'=> $refundOwnerEmailTemplate);

      $wpdb->insert($settingsTable, $defaultSettings);
      $wpdb->insert($settingsTable, $defaultSettings2);
      $wpdb->insert($settingsTable, $defaultSettings3);
    }

    $currVersion = floatval(get_option("ebp_version"));

    if ($currVersion < 3.02) {
      self::fixDateFormats();
      self::updateDefaultEmailTemplate();
    }

    if ($currVersion < 3.2) {
      //change emailSSL from true to SSL
      $emailSSL = $wpdb->get_var( "select emailSSL from " . $settingsTable ." where id= '1'");
      if ($emailSSL == "true")
        $wpdb->update($settingsTable, array('emailSSL'=> 'ssl'), array("id"=> '1'));

      $emailSSL2 = $wpdb->get_var( "select emailSSL from " . $settingsTable ." where id= '2'");
      if ($emailSSL2== "true")
        $wpdb->update($settingsTable, array('emailSSL'=> 'ssl'), array("id"=> '2'));
    }

    $dbSettings = $wpdb->get_row( "SELECT modalNameTxt, modalEmailTxt, boxPaddingBottom FROM " . self::getTableName("settings")." where id= '1'");

    if ($currVersion < 3.33) {
      //style fix
      //box
      $wpdb->update(self::getTableName("settings"), array("btnMarginBottom"=> $dbSettings->boxPaddingBottom), array("id"=> "1"));
      $wpdb->update(self::getTableName("settings"), array("boxPaddingBottom"=> '0'), array("id"=> "1"));
      //cal
      $settingsCal = $wpdb->get_row( "SELECT boxPaddingBottom FROM " . self::getTableName("settings")." where id= '2' ");
      $wpdb->update(self::getTableName("settings"), array("btnMarginBottom"=> $settingsCal->boxPaddingBottom), array("id"=> "2"));
      $wpdb->update(self::getTableName("settings"), array("boxPaddingBottom"=> '0'), array("id"=> "2"));
      update_option('ebp_styleMigrate', 1);

      $defaultOwnerTemplate=addslashes(EmailService::getDefaultOwnerEmailTemplate());
      $wpdb->update($settingsTable, array("ownerEmailTemplate"=> $defaultOwnerTemplate), array("id"=> "1"));
      $wpdb->update($settingsTable, array("ownerEmailTemplate"=> $defaultOwnerTemplate), array("id"=> "2"));
    }

    if ($currVersion < 3.6) {
      $mig_formsTableName = self::getTableName("forms");

      if($wpdb->get_var("SHOW TABLES LIKE '$mig_formsTableName'") ==  $mig_formsTableName) {
        $mig_forms = $wpdb->get_results( "SELECT * FROM " . $mig_formsTableName);

        $mig_formFieldsTableName = self::getTableName("formsInput");

        foreach($mig_forms as $mig_form) {
          $mig_formid = $mig_form->id;
          $mig_formFields = $wpdb->get_results( "SELECT * FROM " . $mig_formFieldsTableName." where form='$mig_formid' order by fieldorder");
          // increment order by 2
          foreach($mig_formFields as $mig_formField) {
            $wpdb->update($mig_formFieldsTableName, array('fieldorder' => (intval($mig_formField->fieldorder) + 2)),
              array( 'id' => $mig_formField->id ));
          }

          // add email and name
          $mig_nameData = array('form'=>$mig_formid, 'type'=>'name', 'name'=>'name', 'fieldorder'=>'1',
            'label'=>'name', 'required'=>'true', 'feeType'=>'none', 'options'=>$dbSettings->modalNameTxt);
          $mig_emailData = array('form'=>$mig_formid, 'type'=>'requiredEmail', 'name'=>'requiredEmail',
            'fieldorder'=>'2', 'label'=>'requiredEmail', 'required'=>'true', 'feeType'=>'none', 'options'=>$dbSettings->modalEmailTxt);

          $wpdb->insert($mig_formFieldsTableName, $mig_nameData);

          $wpdb->insert($mig_formFieldsTableName, $mig_emailData);
        }
      }
    }

    if ($currVersion < 3.8) {
      $duplicateOnQuantityText = $wpdb->get_var("SELECT duplicateOnQuantityText FROM " . $settingsTable." where id= '1' ");
      if (strpos($duplicateOnQuantityText, '%name%') === false) {
        $duplicateOnQuantityText = $duplicateOnQuantityText . '%name_group% - (%name%)%name_group%';
        $wpdb->update($settingsTable, array("duplicateOnQuantityText"=> $duplicateOnQuantityText), array("id"=> "1"));
      }
      self::migrateCalendarSettings();
    }

    $settings_count = $wpdb->get_var("select  COUNT(*)  from ". $settingsTable);

    if ($settings_count < 3) {
      $defaultSettings3 = $wpdb->get_row( "SELECT * FROM " . $settingsTable." where id= '1' ", ARRAY_A);
      $defaultSettings3["id"] = 3;
      $wpdb->insert($settingsTable, $defaultSettings3 );
    }

    if ($currVersion < 3.38) {
      $wpdb->query("ALTER TABLE " . $couponsTable ." MODIFY  amount VARCHAR(40)");
      $wpdb->update($settingsTable,EventCard::getDefaultSettings(), array("id"=> "3"));
    }

    update_option("ebp_version", $version);
  }

  private static function migrateCalendarSettings() {
    global $wpdb;

    $calendarSettings = $wpdb->get_row( "SELECT cal_startIn, cal_displayWeekAbbr, cal_displayMonthAbbr, cal_weeks, cal_weekabbrs, cal_months, cal_monthabbrs, cal_width, cal_height, boxMarginTop, boxMarginBottom, cal_color, cal_bgColor, cal_boxColor, cal_titleBgColor, calTodayColor, calEventDayColor, calEventDayColorHover, cal_dateColor, calEventDayDotColor, calEventDayDotColorHover, calendarImageAsBackground, cal_hasBoxShadow, cal_topBorder, cal_topBorderColor, cal_bottomBorder, cal_bottomBorderColor, cal_sideBorder, cal_sideBorderColor FROM " . self::getTableName("settings")." where id='2' ");

    $newSettings = array();
    foreach ($calendarSettings as $key => $value) {
      // todo filter out in case
      $newSettings[$key] = $value;
    }
    $wpdb->update(self::getTableName("settings"), $newSettings, array("id"=> "1"));
  }

  private static function updateDefaultEmailTemplate() {
    global $wpdb;
    $defaultTemplate = addslashes(EmailService::getDefaultEmailTemplate());
    $wpdb->update(self::getTableName("settings"), array("emailTemplate"=> $defaultTemplate), array("id"=> "1"));
    $wpdb->update(self::getTableName("settings"), array("emailTemplate"=> $defaultTemplate), array("id"=> "2"));

    return;
  }

  private static function fixDateFormats() {
    global $wpdb;

    $table_name = self::getTableName("eventDates");

    $wpdb->query("ALTER TABLE " . $table_name ." MODIFY end_date VARCHAR(20)");
    $wpdb->query("ALTER TABLE " . $table_name ." MODIFY start_date VARCHAR(20)");

    $results = $wpdb->get_results( "SELECT * FROM " . $table_name);

    foreach ($results as $result) {
      $date_s = new DateTime($result->start_date);
      $startDate = $date_s->format('Y-m-d');

      $date_e = new DateTime($result->end_date);
      $endDate = $date_e->format('Y-m-d');

      $wpdb->update($table_name, array('start_date'=> $startDate, 'end_date'=> $endDate), array("id"=> $result->id));
    }
  }

  public static function setCollation() {
    global $wpdb;
    $result = '';
    foreach (EbpDatabase::getAllTables() as $table) {
      $result .= ' table= ' +  $table + ', result=';
      $result .= $wpdb->query("ALTER TABLE " . $table ." CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci");
    }

    return $result;
  }


}
?>
