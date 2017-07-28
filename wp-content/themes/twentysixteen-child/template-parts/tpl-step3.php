<?php
/*
Template Name: step3
*/
get_header();
session_start();
global $wpdb;

$current_user = wp_get_current_user();
$user_id = $current_user->data->ID;
 $querystr = "
      SELECT DISTINCT *
      FROM ".$wpdb->prefix."rg_lead   
      WHERE form_id = 1 
      AND created_by = ".$user_id."
      GROUP BY created_by DESC
      ";

$check_user = $wpdb->get_results($querystr, OBJECT);

  foreach ($check_user as $getuserID) {

    $querydetails = "
      SELECT * 
      FROM ".$wpdb->prefix."rg_lead_detail
      WHERE lead_id = ".$getuserID->id."
      ";

      $get_user_details = $wpdb->get_results($querydetails, OBJECT);
      $instrumental = '';
      $meal = 'N';
      $room = '';
      $single = 'N';
      $dorm = '';
      $first = 'N';
      $vol = '';
      
    foreach ($get_user_details as $getUserDetails) {
      
      $meta_value = gform_get_meta( $entry_id, $meta_key );
      
      if($getUserDetails->field_number == '54'){
           $Participation = $getUserDetails->value;
      }
    }
  }
if($Participation == "non-participant"){
  ?>
  <script>
    window.location="dashboard";
  </script>
  <?php
}

$getlead = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."rg_lead WHERE created_by = ".$user_id."",ARRAY_A);
$lead_id = $getlead[0][id];

$getprimaryinstrument = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."rg_lead_detail WHERE field_number = 23 AND lead_id = ".$lead_id."",ARRAY_A);
$primary_instrument = $getprimaryinstrument[0][value];

if($_POST['submit']){
  
        if($_REQUEST['chamber_assemble'] == 1){
          $_REQUEST['instrument2'] = 'NULL';
          $_REQUEST['instrumentyesno2'] = 'NULL';
          $_REQUEST['comment2'] = 'NULL';

        }

        if($_REQUEST['chamber_assemble'] == 0 || $_REQUEST['chamber_assemble'] == ""){
          $_REQUEST['instrument1'] = 'NULL';
          $_REQUEST['instrumentyesno1'] = 'NULL';
          $_REQUEST['comment1'] = 'NULL';
          
          $_REQUEST['instrument2'] = 'NULL';
          $_REQUEST['instrumentyesno2'] = 'NULL';
          $_REQUEST['comment2'] = 'NULL';
        }
        
        $completestatus = 'yes';

        $current_user = wp_get_current_user();
        $user_id = $current_user->data->ID;
        
          $ins_instrument1 = '';
          $ins_instrumentyesno1 = '';
          $ins_comment1 = '';
          $ins_instrument2 = '';
          $ins_instrumentyesno2 = '';
          $ins_comment2 = '';
          
          $ins_p_instrument1 = '';
          $ins_groupname1 = '';
          $ins_p_instrumentyesno1 = '';
          $ins_contactpersonname1 = '';
          $ins_contactpersonemail1 = '';
          $ins_otherparticipant1 = '';
          $ins_ownmusic1 = '';
          $ins_composer1 = '';
          $ins_p_comment1 = '';
          
          $ins_p_instrument2 = '';
          $ins_groupname2 = '';
          $ins_p_instrumentyesno2 = '';
          $ins_contactpersonname2 = '';
          $ins_contactpersonemail2 = '';
          $ins_otherparticipant2 = '';
          $ins_ownmusic2 = '';
          $ins_composer2 = '';
          $ins_p_comment2 = '';
          
          if($_REQUEST['chamber_assemble'] == 1 || $_REQUEST['chamber_assemble'] == 2){
          $ins_instrument1 = (isset($_REQUEST['instrument1']) && $_REQUEST['instrument1'] != 'NULL') ? $_REQUEST['instrument1'] : '';
          $ins_instrumentyesno1 = (isset($_REQUEST['instrumentyesno1']) && $_REQUEST['instrumentyesno1'] != 'NULL') ? $_REQUEST['instrumentyesno1'] : '';
          $ins_comment1 = (isset($_REQUEST['comment1']) && $_REQUEST['comment1'] != 'NULL') ? $_REQUEST['comment1'] : '';
          }
          if($_REQUEST['chamber_assemble'] == 2){
          $ins_instrument2 = (isset($_REQUEST['instrument2']) && $_REQUEST['instrument2'] != 'NULL') ? $_REQUEST['instrument2'] : '';
          $ins_instrumentyesno2 = (isset($_REQUEST['instrumentyesno2']) && $_REQUEST['instrumentyesno2'] != 'NULL') ? $_REQUEST['instrumentyesno2'] : '';
          $ins_comment2 = (isset($_REQUEST['comment2']) && $_REQUEST['comment2'] != 'NULL') ? $_REQUEST['comment2'] : '';
          }
          if($_REQUEST['prearranged_group'] == 1 || $_REQUEST['prearranged_group'] == 2){
          $ins_p_instrument1 = (isset($_REQUEST['p_instrument1']) && $_REQUEST['p_instrument1'] != 'NULL') ? $_REQUEST['p_instrument1'] : '';
          $ins_groupname1 = (isset($_REQUEST['groupname1']) && $_REQUEST['groupname1'] != 'NULL') ? $_REQUEST['groupname1'] : '';
          $ins_p_instrumentyesno1 = (isset($_REQUEST['p_instrumentyesno1']) && $_REQUEST['p_instrumentyesno1'] != 'NULL') ? $_REQUEST['p_instrumentyesno1'] : '';
          $ins_contactpersonname1 = (isset($_REQUEST['contactpersonname1']) && $_REQUEST['contactpersonname1'] != 'NULL') ? $_REQUEST['contactpersonname1'] : '';
          $ins_contactpersonemail1 = (isset($_REQUEST['contactpersonemail1']) && $_REQUEST['contactpersonemail1'] != 'NULL') ? $_REQUEST['contactpersonemail1'] : '';
          $ins_otherparticipant1 = (isset($_REQUEST['otherparticipant1']) && $_REQUEST['otherparticipant1'] != 'NULL') ? $_REQUEST['otherparticipant1'] : '';
          $ins_ownmusic1 = (isset($_REQUEST['ownmusic1']) && $_REQUEST['ownmusic1'] != 'NULL') ? $_REQUEST['ownmusic1'] : '';
          $ins_composer1 = (isset($_REQUEST['composer1']) && $_REQUEST['composer1'] != 'NULL') ? $_REQUEST['composer1'] : '';
          $ins_p_comment1 = (isset($_REQUEST['p_comment1']) && $_REQUEST['p_comment1'] != 'NULL') ? $_REQUEST['p_comment1'] : '';
          }
          if($_REQUEST['prearranged_group'] == 2){
          $ins_p_instrument2 = (isset($_REQUEST['p_instrument2']) && $_REQUEST['p_instrument2'] != 'NULL') ? $_REQUEST['p_instrument2'] : '';
          $ins_groupname2 = (isset($_REQUEST['groupname2']) && $_REQUEST['groupname2'] != 'NULL') ? $_REQUEST['groupname2'] : '';
          $ins_p_instrumentyesno2 = (isset($_REQUEST['p_instrumentyesno2']) && $_REQUEST['p_instrumentyesno2'] != 'NULL') ? $_REQUEST['p_instrumentyesno2'] : '';
          $ins_contactpersonname2 = (isset($_REQUEST['contactpersonname2']) && $_REQUEST['contactpersonname2'] != 'NULL') ? $_REQUEST['contactpersonname2'] : '';
          $ins_contactpersonemail2 = (isset($_REQUEST['contactpersonemail2']) && $_REQUEST['contactpersonemail2'] != 'NULL') ? $_REQUEST['contactpersonemail2'] : '';
          $ins_otherparticipant2 = (isset($_REQUEST['otherparticipant2']) && $_REQUEST['otherparticipant2'] != 'NULL') ? $_REQUEST['otherparticipant2'] : '';
          $ins_ownmusic2 = (isset($_REQUEST['ownmusic2']) && $_REQUEST['ownmusic2'] != 'NULL') ? $_REQUEST['ownmusic2'] : '';
          $ins_composer2 = (isset($_REQUEST['composer2']) && $_REQUEST['composer2'] != 'NULL') ? $_REQUEST['composer2'] : '';
          $ins_p_comment2 = (isset($_REQUEST['p_comment2']) && $_REQUEST['p_comment2'] != 'NULL') ? $_REQUEST['p_comment2'] : '';
          }
      
        if(isset($_REQUEST['edit_form']) && $_REQUEST['edit_form'] == 'yes'){
          $wpdb->query("DELETE FROM ".$wpdb->prefix."questionnaires_afternoon WHERE userID = ".$user_id."");
          $sql = "INSERT INTO ".$wpdb->prefix."questionnaires_afternoon
                    (`userID`,`chamber_assemble`,`prearranged_group`,`instrument1`,`instrumentyesno1`,`comment1`,`instrument2`,`instrumentyesno2`,`comment2`,`p_instrument1`,`groupname1`,`p_instrumentyesno1`,`contactpersonname1`,`contactpersonemail1`,`otherparticipant1`,`ownmusic1`,`composer1`,`p_comment1`,`p_instrument2`,`groupname2`,`p_instrumentyesno2`,`contactpersonname2`,`contactpersonemail2`,`otherparticipant2`,`ownmusic2`,`composer2`,`p_comment2`,`stp3_completed`) 
                    values (".$user_id.", 
                    '".$_REQUEST['chamber_assemble']."', 
                    '".$_REQUEST['prearranged_group']."', 
                    '".$ins_instrument1."', 
                    '".$ins_instrumentyesno1."', 
                    '".$ins_comment1."', 
                    '".$ins_instrument2."', 
                    '".$ins_instrumentyesno2."', 
                    '".$ins_comment2."', 
                    '".$ins_p_instrument1."', 
                    '".$ins_groupname1."', 
                    '".$ins_p_instrumentyesno1."', 
                    '".$ins_contactpersonname1."', 
                    '".$ins_contactpersonemail1."', 
                    '".$ins_otherparticipant1."', 
                    '".$ins_ownmusic1."',
                    '".$ins_composer1."',
                    '".$ins_p_comment1."', 
                    '".$ins_p_instrument2."', 
                    '".$ins_groupname2."', 
                    '".$ins_p_instrumentyesno2."', 
                    '".$ins_contactpersonname2."', 
                    '".$ins_contactpersonemail2."', 
                    '".$ins_otherparticipant2."',
                    '".$ins_ownmusic2."',
                    '".$ins_composer2."',
                    '".$ins_p_comment2."','".$completestatus."')"; 
            $wpdb->query($sql);
            // End Insert Query
            $_SESSION['success'] = "Saved Successfully";
        }else{
          $sql = "INSERT INTO ".$wpdb->prefix."questionnaires_afternoon
                    (`userID`,`chamber_assemble`,`prearranged_group`,`instrument1`,`instrumentyesno1`,`comment1`,`instrument2`,`instrumentyesno2`,`comment2`,`p_instrument1`,`groupname1`,`p_instrumentyesno1`,`contactpersonname1`,`contactpersonemail1`,`otherparticipant1`,`ownmusic1`,`composer1`,`p_comment1`,`p_instrument2`,`groupname2`,`p_instrumentyesno2`,`contactpersonname2`,`contactpersonemail2`,`otherparticipant2`,`ownmusic2`,`composer2`,`p_comment2`,`stp3_completed`) 
                    values (".$user_id.", 
                    '".$_REQUEST['chamber_assemble']."', 
                    '".$_REQUEST['prearranged_group']."', 
                    '".$ins_instrument1."', 
                    '".$ins_instrumentyesno1."', 
                    '".$ins_comment1."', 
                    '".$ins_instrument2."', 
                    '".$ins_instrumentyesno2."', 
                    '".$ins_comment2."', 
                    '".$ins_p_instrument1."', 
                    '".$ins_groupname1."', 
                    '".$ins_p_instrumentyesno1."', 
                    '".$ins_contactpersonname1."', 
                    '".$ins_contactpersonemail1."', 
                    '".$ins_otherparticipant1."', 
                    '".$ins_ownmusic1."',
                    '".$ins_composer1."',
                    '".$ins_p_comment1."', 
                    '".$ins_p_instrument2."', 
                    '".$ins_groupname2."', 
                    '".$ins_p_instrumentyesno2."', 
                    '".$ins_contactpersonname2."', 
                    '".$ins_contactpersonemail2."', 
                    '".$ins_otherparticipant2."',
                    '".$ins_ownmusic2."',
                    '".$ins_composer2."',
                    '".$ins_p_comment2."','".$completestatus."')"; 
            $wpdb->query($sql);
            // End Insert Query
            $_SESSION['success'] = "Saved Successfully";
        }
        $sql = "UPDATE ".$wpdb->prefix."questionnaires_self_evaluations SET `completed`='no' WHERE `user_id`='".$user_id."' AND `group` =''";
        $wpdb->query($sql);
        /*$sql1 = "UPDATE ".$wpdb->prefix."questionnaires_read_accept_terms SET `completed`='no' WHERE `user_id`='".$user_id."'";
        $wpdb->query($sql1);*/
        $sql2 = "UPDATE ".$wpdb->prefix."questionnaires_review_and_commit SET `completed`='no' WHERE `user_id`='".$user_id."'";
        $wpdb->query($sql2);
  }


  $querystr = "
        SELECT * 
        FROM ".$wpdb->prefix."questionnaires_afternoon
        WHERE userID = ".$user_id."
        ";

  $check_user = $wpdb->get_results($querystr, OBJECT);

  $completeduser = '';
  $MAE = '';
  $PAE = '';
  $instrument1 = '';
  $instrumentyesno1 = '';
  $comment1 = '';
  $instrument2 = '';
  $instrumentyesno2 = '';
  $comment2 = '';
  $p_instrument1 = '';
  $groupname1 = '';
  $p_instrumentyesno1 = '';
  $contactpersonname1 = '';
  $contactpersonemail1 = '';
  $otherparticipant1 = '';
  $ownmusic1 = '';
  $composer1 = '';
  $p_comment1 = '';
  $p_instrument2 = '';
  $groupname2 = '';
  $p_instrumentyesno2 = '';
  $contactpersonname2 = '';
  $contactpersonemail2 = '';
  $otherparticipant2 = '';
  $ownmusic2 = '';
  $composer2 = '';
  $p_comment2 = '';
  foreach ($check_user as $formdata) {
    $completeduser = $formdata->stp3_completed;
    $MAE = $formdata->chamber_assemble;
    $PAE = $formdata->prearranged_group;
    $instrument1 = $formdata->instrument1;
    $comment1 = $formdata->comment1;
    $instrument2 = $formdata->instrument2;
    $instrumentyesno1 = $formdata->instrumentyesno1;
    $instrumentyesno2 = $formdata->instrumentyesno2;
    $comment2 = $formdata->comment2;
    $p_instrument1 = $formdata->p_instrument1;
    $groupname1 = $formdata->groupname1;
    $p_instrumentyesno1 = $formdata->p_instrumentyesno1;
    $contactpersonname1 = $formdata->contactpersonname1;
    $contactpersonemail1 = $formdata->contactpersonemail1;
    $otherparticipant1 = $formdata->otherparticipant1;
    $ownmusic1 = $formdata->ownmusic1;
    $composer1 = $formdata->composer1;
    $p_comment1 = $formdata->p_comment1;
    $p_instrument2 = $formdata->p_instrument2;
    $groupname2 = $formdata->groupname2;
    $p_instrumentyesno2 = $formdata->p_instrumentyesno2;
    $contactpersonname2 = $formdata->contactpersonname2;
    $contactpersonemail2 = $formdata->contactpersonemail2;
    $otherparticipant2 = $formdata->otherparticipant2;
    $ownmusic2 = $formdata->ownmusic2;
    $composer2 = $formdata->composer2;
    $p_comment2 = $formdata->p_comment2;
  }
  

?>
<style type="text/css">
  .error{color:red;}
</style>

<ul class='steps'>
    <?php
    $querystr = "
        SELECT * 
        FROM ".$wpdb->prefix."questionnaires
        WHERE userID = ".$user_id."
        ";

        $questionnaires = $wpdb->get_results($querystr, OBJECT);

        foreach ($questionnaires as $formdata) {
            $step1 = $formdata->stp1_completed;
        }
    ?>
    <li><a href="<?php echo site_url(); ?>/questionnaires/">Instructions</a>
    <?php
    if($step1 == '1'){
      ?>
      <span class="label label-success">finished</span>
      <?php 
    }else{
      ?>
      <span class="label label-danger">unfinished</span>
      <?php 
    }
    ?>
    </li>
    <?php
    $querystr = "
        SELECT * 
        FROM ".$wpdb->prefix."questionnaires_ensemble
        WHERE user_id = ".$user_id."
        ";

        $questionnaires_ensemble = $wpdb->get_results($querystr, OBJECT);

        foreach ($questionnaires_ensemble as $formdata) {
            $step2 = $formdata->step2_completed;
        }
    ?>
    <li><a href="<?php echo site_url(); ?>/morning-ensemble/">Morning Ensemble</a>
      <?php
    if($step2 == '1'){
      ?>
      <span class="label label-success">finished</span>
      <?php 
    }else{
      ?>
      <span class="label label-danger">unfinished</span>
      <?php 
    }
    ?>
    </li>
    <?php
    $querystr = "
        SELECT * 
        FROM ".$wpdb->prefix."questionnaires_afternoon
        WHERE userID = ".$user_id."
        ";

        $questionnaires_afternoon = $wpdb->get_results($querystr, OBJECT);

        foreach ($questionnaires_afternoon as $formdata) {
            $step3 = $formdata->stp3_completed;
        }
    ?>
    <li class="active-li"><a href="<?php echo site_url(); ?>/afternoon-ensembles/">Afternoon Ensembles</a>
       <?php
    if($step3 == 'yes'){
      ?>
      <span class="label label-success">finished</span>
      <?php 
    }else{
      ?>
      <span class="label label-danger">unfinished</span>
      <?php 
    }
    ?>
    </li>
    <?php
    $querystr = "
        SELECT * 
        FROM ".$wpdb->prefix."questionnaires_electives

        WHERE user_id = ".$user_id."
        ";

        $questionnaires_electives = $wpdb->get_results($querystr, OBJECT);

    ?>
    <li><a href="<?php echo site_url(); ?>/electives/">Electives</a>
     <?php
    if(!empty($questionnaires_electives)){
      ?>
      <span class="label label-success">finished</span>
      <?php 
    }else{
      ?>
      <span class="label label-danger">unfinished</span>
      <?php 
    }
    ?> 
    </li>
    <?php
    $querystr = "
        SELECT * 
        FROM ".$wpdb->prefix."questionnaires_self_evaluations

        WHERE user_id = ".$user_id." AND completed='yes'
        ";

        $questionnaires_self_evaluations = $wpdb->get_results($querystr, OBJECT);

    ?>
    <?php 
    if($step1 == "1"){
      if($step2 == "1"){
        if($step3 == "yes"){
          if(!empty($questionnaires_electives)){
             $style_5 = "";
          }else{
            $style_5 ="pointer-events: none;";
          }
        }else{
          $style_5 = "pointer-events: none;";
        }
      }else{
        $style_5 = "pointer-events: none;";
      }
    }else{
      $style_5 = "pointer-events: none;";
    }
    ?>
    <li><a style="<?php echo $style_5; ?>" href="<?php echo site_url(); ?>/self-evaluations/">Self-evaluations</a>
      <?php
    if(!empty($questionnaires_self_evaluations)){
      ?>
      <span class="label label-success">finished</span>
      <?php 
    }else{
      ?>
      <span class="label label-danger">unfinished</span>
      <?php 
    }
    ?> 
    </li>
    <?php
    $querystr = "
        SELECT * 
        FROM ".$wpdb->prefix."questionnaires_read_accept_terms

        WHERE user_id = ".$user_id."
        ";

        $questionnaires_read_accept_terms = $wpdb->get_results($querystr, OBJECT);
        foreach ($questionnaires_read_accept_terms as $formdata) {
          # code...
          $step6 = $formdata->completed;
        }

    ?>
    <li><a href="<?php echo site_url(); ?>/read-and-accept-terms/">Read And Accept Terms</a>
       <?php
    if($step6 == 'yes'){
      ?>
      <span class="label label-success">finished</span>
      <?php 
    }else{
      ?>
      <span class="label label-danger">unfinished</span>
      <?php 
    }
    ?>
    </li>
    <?php
    $querystr = "
        SELECT * 
        FROM ".$wpdb->prefix."questionnaires_review_and_commit

        WHERE user_id = ".$user_id."
        ";

        $questionnaires_review_and_commit = $wpdb->get_results($querystr, OBJECT);
        foreach ($questionnaires_review_and_commit as $formdata) {
          # code...
          $step7 = $formdata->completed;
        }

    ?>
    <?php
    if($step1 == "1"){
      if($step2 == "1" ){
        if($step3 == "yes"){
          if(!empty($questionnaires_electives)){
            if(!empty($questionnaires_self_evaluations)){
              if($step6 == "yes"){
                $style_7 = "";
              }else{
                $style_7 = "pointer-events: none;";
              }

            }else{
              $style_7 = "pointer-events: none;";
            }

          }else{
            $style_7 = "pointer-events: none;";
          }

        }else{
          $style_7 = "pointer-events: none;";
        }

      }else{
        $style_7 = "pointer-events: none;";  
      }
    }else{
      $style_7 = "pointer-events: none;";
    }
    ?>
    <li><a style="<?php echo $style_7; ?>" href="<?php echo site_url(); ?>/review-and-commit/">Review And Commit</a>
      <?php
    if($step7 == 'yes'){
      ?>
      <span class="label label-success">finished</span>
      <?php 
    }else{
      ?>
      <span class="label label-danger">unfinished</span>
      <?php 
    }
    ?>
    </li>
 
</ul>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 

<div class="bs-example">
  <?php
  if(isset($_SESSION['success'])){
    ?>
    <div class="alert alert-success">
      <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
    <?php 
  }else if(isset($_SESSION['error'])){
    ?>
    <div class="alert alert-danger">
      <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
    <?php
  }
  ?>
<form method="post" id="step3_form">
<!-- Display static data -->
   <div class="form-group">
            <label for="title">
                  <?php
                    $title = get_post_meta( get_the_ID(), 'title', true );
                    // Check if the custom field has a value.
                    if ( ! empty( $title ) ) {
                    echo $title;
                    }
                  ?>
             </label>
        </div>
        <div class="form-group">
             <p>
               <?php
                    $description = get_post_meta( get_the_ID(), 'description', true );
                    // Check if the custom field has a value.
                    if ( ! empty( $description ) ) {
                    echo $description;
                    }
                  ?>
             </p>
        </div>

  <div class="form-group">
          <label>AFTERNOON ENSEMBLE PREFERENCES</label>
        </div>
        <div class="row">
        <div class="col-sm-6">
          <label>MMR Arranged Chamber Ensembles</label>
          <select name="chamber_assemble" id="chamber_assemble">
                <option value="">Please Select</option>
                <option class="c_0" value="0" <?php if($MAE == 0 && $completeduser == 'yes'){ echo 'selected'; } ?> >0</option>
                <option class="c_1" value="1" <?php if($MAE == 1 && $completeduser == 'yes'){ echo 'selected'; } ?> >1</option>
                <option class="c_2" value="2" <?php if($MAE == 2 && $completeduser == 'yes'){ echo 'selected'; } ?> >2</option>
          </select>
        </div>
        <div class="col-sm-6">
          <label>Prearranged Groups</label>
          <select name="prearranged_group" id="prearranged_groupall">
                <option value="">Please Select</option>
                <option class="0" value="0" <?php if($PAE == 0 && $completeduser == 'yes'){ echo 'selected'; } ?> >0</option>
                <option class="1" value="1" <?php if($PAE == 1 && $completeduser == 'yes'){ echo 'selected'; } ?> >1</option>
                <option class="2" value="2" <?php if($PAE == 2 && $completeduser == 'yes'){ echo 'selected'; } ?> >2</option>
          </select>

        </div>
        </div><br>
        <div class="form-group">
          <!--<button type="button" name="next" value="next" id="nextensemble" class="btn btn-primary" <?php //if($completeduser == 'yes'){  }else{ echo 'disabled'; } ?>>NEXT</button>-->
          <!-- <input type="button" name="next" value="next" id="nextensemble" <?php //if($completeduser == 'yes'){  }else{ echo 'disabled'; } ?>> -->
        </div>
  <?php
  $get_instruments = new WP_Query( array( 'post_type'=>'instruments', 'posts_per_page' => -1 ) );
  ?>
  <table class="table3 chamber_assembleappend1" <?php if($completeduser == 'yes' && $MAE == 1 || $completeduser == 'yes' && $MAE == 2){ }else{ ?> style="display: none;" <?php } ?> >
  <div class="form-group">
    <tr><th> Assigned Groups </th></tr>
      <tr>
        <td>
                  <label>Which instrument or vocal part are you playing in this group?</label>
                  <select name="instrument1" id="instrument1">
                      <option value="">Please Select</option>
                      <?php while ( $get_instruments->have_posts() ) { $get_instruments->the_post(); 
                      if($completeduser == 'yes'){
                        if($instrument1 == get_the_title()){
                          $selected = 'selected';
                        }
                        else
                        {
                          $selected = '';
                        }
                      }
                      else
                      {
                        if($primary_instrument == get_the_title()){
                          $selected = 'selected';
                        }
                        else
                        {
                          $selected = '';
                        }
                      }
                      ?>
                      <option <?php echo $selected; ?> value="<?php echo get_the_title(); ?>"><?php echo get_the_title(); ?></option>
                      <?php } ?>
                  </select>
        </td>
      </tr>
      <?php 
         if($instrument1 != ''){
           $get_instruments_jazz = new WP_Query( array( 'post_type'=>'instruments', 'posts_per_page' => 1 ,'post_name__in' => array($instrument1) ) );
           $is_jazz = get_post_meta($get_instruments_jazz->posts[0]->ID,'is_jazz',true);
         }
      ?>
      <tr id="instrumentyesno1" <?php if($is_jazz == 'yes'){ }else{ ?> style="display: none;" <?php } ?>>
        <td>
            MMR Now has a collection of jazz scores for verious ensembles including some with non-standard instrumentation. We also have two outstanding jazz performers and teachers on the faculty.
            <br>
            Would you like to be assigned to a small jazz ensemble or combo?
            
            <label class="radio-inline">
              <input name="instrumentyesno1" id="instrumentno1" type="radio" <?php if($instrumentyesno1 == 'no'){ ?> checked='checked' <?php } ?> value="no" > No
            </label>
            
            <label class="radio-inline">
              <input name="instrumentyesno1" id="instrumentyes1" type="radio" <?php if($instrumentyesno1 == 'yes'){ ?> checked='checked' <?php } ?> value="yes">Yes
            </label>
        </td>
      </tr>
      
      <tr class="assignedchamber1" <?php if($completeduser == 'yes' && $MAE == 1 || $completeduser == 'yes' && $MAE == 2){ }else{ ?> style="display: none;" <?php } ?> >
        <td>
        <div class="form-group">
          <label for="comment">Other Comments, questions, or requests:</label>
          <textarea name="comment1" id="comment1" class="form-control" rows="5"><?php echo $comment1; ?></textarea>
        </div>
        </td>
      </tr>
  </table>
  <?php
  $get_instruments2 = new WP_Query( array( 'post_type'=>'instruments', 'posts_per_page' => -1 ) );
  ?>
  <table class="table4 chamber_assembleappend2" <?php if($completeduser == 'yes' && $MAE == 2){  }else{ ?> style="display: none; border-top: 1px solid #ccc; padding-top: 27px;" <?php } ?>>
  <!-- <tr><th> Assigned Groups </th></tr> -->

      <tr>
        <td>
                  <label>Which instrument or vocal part are you playing in this group?</label>
                  <select name="instrument2" id="instrument2">
                      <option value="">Please Select</option>
                      <?php while ( $get_instruments2->have_posts() ) { $get_instruments2->the_post();
                      if($completeduser == 'yes'){
                        if($instrument2 == get_the_title()){
                          $selected2 = 'selected';
                        }
                        else
                        {
                          $selected2 = '';
                        }
                      }
                      else
                      {
                        if($primary_instrument == get_the_title()){
                          $selected2 = 'selected';
                        }
                        else
                        {
                          $selected2 = '';
                        }
                      }
                      ?>
                      <option <?php echo $selected2; ?> value="<?php echo get_the_title(); ?>"><?php echo get_the_title(); ?></option>
                      <?php } ?>
                  </select>
        </td>
      </tr>
      <?php 
         if($instrument2 != ''){
           $get_instruments_jazz2 = new WP_Query( array( 'post_type'=>'instruments', 'posts_per_page' => 1 ,'post_name__in' => array($instrument2) ) );
           $is_jazz2 = get_post_meta($get_instruments_jazz2->posts[0]->ID,'is_jazz',true);
         }
      ?>
      <tr id="instrumentyesno2" <?php if($is_jazz2 == 'yes'){ }else{ ?> style="display: none;" <?php } ?>>
        <td>
                    MMR Now has a collection of jazz scores for verious ensembles including some with non-standard instrumentation. We also have two outstanding jazz performers and teachers on the faculty.
                    <br>
                    Would you like to be assigned to a small jazz ensemble or combo?
                    <input name="instrumentyesno2" id="instrumentno2" <?php if($instrumentyesno2 == 'no'){ ?> checked='checked' <?php } ?> type="radio" value="no" checked="checked">No
                    <input name="instrumentyesno2" id="instrumentyes2" <?php if($instrumentyesno2 == 'yes'){ ?> checked='checked' <?php } ?> type="radio" value="yes" >Yes
        </td>
      </tr>

      <tr>
        <td>
          <div class="row assignedchamber2" <?php if($completeduser == 'yes' && $MAE == 2){ }else{ ?> style="display: none;" <?php } ?>>
            <div class="col-sm-12">
              <div class="form-group">
                <label>Other Comments, questions, or requests:</label>
                <textarea name="comment2" class="form-control" rows="5" id="comment2"><?php echo $comment2; ?></textarea>
                </div>
            </div>
          </div>
        </td>
      </tr>
  </table>
<?php
  $get_instruments_p = new WP_Query( array( 'post_type'=>'instruments', 'posts_per_page' => -1 ) );
?>
<table class="prearranged_groupall1 table5" <?php if($completeduser == 'yes' && $PAE == 1 || $completeduser == 'yes' && $PAE == 2){ }else{ ?> style="display: none;" <?php } ?>>
<tr><th> Prearranged Groups </th></tr>
  <tr>
    <td>
      <label>Which instrument or vocal part are you playing in this group?</label>
      <select name="p_instrument1" id="p_instrument1">
      <option value="">Please Select</option>
      <?php while ( $get_instruments_p->have_posts() ) { $get_instruments_p->the_post();
          if($completeduser == 'yes'){
            if($p_instrument1 == get_the_title()){
              $selected3 = 'selected';
            }
            else
            {
              $selected3 = '';
            }
          }
          else
          {
            if($primary_instrument == get_the_title()){
              $selected3 = 'selected';
            }
            else
            {
              $selected3 = '';
            }
          }
      ?>
      <option <?php echo $selected3; ?> value="<?php echo get_the_title(); ?>"><?php echo get_the_title(); ?></option>
      <?php } ?>
      </select>
    </td>
  </tr>

  <tr>
    <td>
      <div class="row">
        <div class="col-sm-12 col-md-6">
          <label>Group Name (Optional): </label>
          <input type="text" value="<?php echo $groupname1; ?>" name="groupname1">
        </div>
      </div>
    </td>
  </tr>

  <tr id="p_instrumentyesno1">
    <td>
        Each Prearranged group must have a designated contact person, who will provide additional information about the group.
        <br>
        Are you contact person person for this ensemble?
        <label class="radio-inline">
          <input name="p_instrumentyesno1" id="p_instrumentno1" <?php if($p_instrumentyesno1 == 'no') { ?> checked='checked' <?php } ?> type="radio" value="no" >No
        </label>
        <label class="radio-inline">
          <input name="p_instrumentyesno1" id="p_instrumentyes1" <?php if($p_instrumentyesno1 == 'yes') { ?> checked='checked' <?php } ?> type="radio" value="yes">Yes
        </label>
    </td>
  </tr>

  <tr id="prearrangedno1" <?php if($p_instrumentyesno1 == 'yes') { ?> style="display: none;" <?php } ?> >
    <td>
      <table>
        <tr>
          <td>
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <label>Contact person name (required) : </label>
              <input type="text" value="<?php echo $contactpersonname1; ?>" name="contactpersonname1" id="contactpersonname1">
            </div>
          </div>
          </td>
        </tr>
        <tr>
          <td>
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <label>Contact person Email (encouraged) : </label>
              <input type="email" value="<?php echo $contactpersonemail1; ?>" name="contactpersonemail1" id="contactpersonemail1">
            </div>
          </div>
          </td>
        </tr>
      </table> 
    </td>
  </tr>

  <tr <?php if($p_instrumentyesno1 == 'yes') { } else { ?> style="display: none;" <?php } ?> id="prearrangedyes1">
    <td>
      <table>
        <tr>
          <td>
          <div class="form-group">
          <label>Names and instruments/voices of other participants :</label> <textarea name="otherparticipant1" class="form-control" rows="5" id="otherparticipant1"><?php echo $otherparticipant1; ?></textarea>
          </div>
          </td>
        </tr>
        <tr>
          <td><label>Are you bringing your own music? : </label>
            <label class="radio-inline">
              <input type="radio" id="ownmusicno1" name="ownmusic1" <?php if($ownmusic1 == 'No') { ?> checked='checked' <?php } ?> value="No">No
            </label>
            <label class="radio-inline">
              <input type="radio" id="ownmusicyes1" name="ownmusic1" <?php if($ownmusic1 == 'Yes') { ?> checked='checked' <?php } ?> value="Yes">Yes
            </label>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr <?php if($ownmusic1 == 'Yes') { } else { ?> style="display: none" <?php } ?> class="ownmusicyes1">
  <td>
  <div class="input-group">
  <label>Composer and name of piece:</label><input type="text" value="<?php echo $composer1; ?>" name="composer1">
  <br>
  Please write measure numbers in all parts. Bring a score with measure numbers for the coach.
  </div>
  </td>
  </tr>
  <tr>
    <td>
      <div class="form-group">
      <label>Other Comments, questions, or requests:</label>
      <textarea class="form-control" rows="5" name="p_comment1" id="p_comment1"><?php echo $p_comment1; ?></textarea>
      </div>
    </td>
  </tr>
</table>
<?php
  $get_instruments_p2 = new WP_Query( array( 'post_type'=>'instruments', 'posts_per_page' => -1 ) );
?>
<table class="prearranged_groupall2 table5" <?php if($completeduser == 'yes' && $PAE == 2){ }else{ ?> style="display: none; border-top: 1px solid #ccc; padding-top: 27px;" <?php } ?>>
<!-- <tr><th> Prearranged Groups </th></tr> -->
  <tr>
    <td>
      <label>Which instrument or vocal part are you playing in this group?</label>
      <select name="p_instrument2" id="p_instrument2">
      <option value="">Please Select</option>
      <?php while ( $get_instruments_p2->have_posts() ) { $get_instruments_p2->the_post();
      if($completeduser == 'yes'){
        if($p_instrument2 == get_the_title()){
          $selected4 = 'selected';
        }
        else
        {
          $selected4 = '';
        }
      }
      else
      {
        if($primary_instrument == get_the_title()){
          $selected4 = 'selected';
        }
        else
        {
          $selected4 = '';
        }
      }
      ?>
      <option <?php echo $selected4; ?> value="<?php echo get_the_title(); ?>"><?php echo get_the_title(); ?></option>
      <?php } ?>
      </select>
    </td>
  </tr>

  <tr>
    <td>
    <div class="row">
        <div class="col-sm-12 col-md-6">
        <label>Group Name (Optional): </label>
        <input type="text" value="<?php echo $groupname2; ?>" name="groupname2">
        </div>
    </div>
    </td>
  </tr>

  <tr id="p_instrumentyesno2">
    <td>
        Each Prearranged group must have a designated contact person, who will provide additional information about the group.
        <br>
        Are you contact person person for this ensemble?
        <label class="radio-inline">
          <input name="p_instrumentyesno2" <?php if($p_instrumentyesno2 == 'no') { ?> checked='checked' <?php } ?> id="p_instrumentno2" type="radio" value="no">No
        </label>
        <label class="radio-inline">
          <input name="p_instrumentyesno2" <?php if($p_instrumentyesno2 == 'yes') { ?> checked='checked' <?php } ?> id="p_instrumentyes2" type="radio" value="yes">Yes
        </label>
    </td>
  </tr>

  <tr id="prearrangedno2" <?php if($p_instrumentyesno2 == 'yes') { ?> style="display: none;" <?php } ?>>
    <td>
      <table>
        <tr>
          <td>
          <div class="row">
            <div class="col-sm-12 col-md-6">
          <label>Contact person name (required) : </label><input type="text" name="contactpersonname2" value="<?php echo $contactpersonname2; ?>" id="contactpersonname2">
          </div>
          </div>
          </td>
        </tr>
        <tr>
          <td>
          <div class="row">
            <div class="col-sm-12 col-md-6">
              <label>Contact person Email (encouraged) : </label><input type="email" name="contactpersonemail2" value="<?php echo $contactpersonemail2; ?>" id="contactpersonemail2">
            </div>
          </div>
          </td>
        </tr>
      </table> 
    </td>
  </tr>

  <tr <?php if($p_instrumentyesno2 == 'yes') { } else { ?> style="display: none;" <?php } ?> id="prearrangedyes2">
    <td>
      <table>
        <tr>
          <td>
          <div class="form-group">
          <label>Names and instruments/voices of other participants :</label> <textarea name="otherparticipant2" class="form-control" rows="5" id="otherparticipant2"><?php echo $otherparticipant2; ?></textarea>
          </div>
          </td>
        </tr>
        <tr>
          <td><label>Are you bringing your own music? : </label>
            <label class="radio-inline">
              <input type="radio" id="ownmusicno2" name="ownmusic2" <?php if($ownmusic2 == 'No') { ?> checked='checked' <?php } ?> value="No">No
            </label>
            <label class="radio-inline">
              <input type="radio" id="ownmusicyes2" name="ownmusic2" <?php if($ownmusic2 == 'Yes') { ?> checked='checked' <?php } ?> value="Yes">Yes
            </label>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <tr <?php if($ownmusic2 == 'Yes') { } else { ?> style="display: none" <?php } ?> class="ownmusicyes2">
  <td>
  <div class="input-group custom-group">
  <label>Composer and name of piece:</label><input type="text" value="<?php echo $composer2; ?>" name="composer2">
  <br>
  Please write measure numbers in all parts. Bring a score with measure numbers for the coach.
  </div>
  </td>
  </tr>

  <tr>
    <td>
      <label>Other Comments, questions, or requests:</label>
      <textarea name="p_comment2" id="p_comment2"><?php echo $p_comment2; ?></textarea>
    </td>
  </tr>
</table>
<?php if($completeduser == 'yes'){ ?>
  <input type="hidden" name="edit_form" value="yes">
<?php } ?>
<input type="submit" class="btn btn-primary" value="<?php if($completeduser == 'yes'){ echo 'Edit this Step'; } else { echo 'Complete this Step'; } ?>" name="submit" id="step3_submit" <?php if($completeduser == 'yes'){ } else { ?> disabled <?php } ?>>
</div>
</form>
</div>
<?php get_footer(); ?>