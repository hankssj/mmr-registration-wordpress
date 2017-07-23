<?php
/*
Template Name: step5
*/
get_header();
session_start();
if(!is_user_logged_in()){
  ?>
  <script> 
        window.location.href = "<?php echo home_url(); ?>";
  </script>
  <?php
}
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
        window.location.href = "<?php echo home_url(); ?>";
  </script>
  <?php
}

  if($_POST['complted']){
  	
  		
        $current_user = wp_get_current_user();
        $user_id = $current_user->data->ID;

        $sqldele = "DELETE FROM ".$wpdb->prefix."questionnaires_self_evaluations WHERE user_id = '".$user_id."' AND completed ='yes'";
            $wpdb->query($sqldele);
            $sql = "INSERT INTO ".$wpdb->prefix."questionnaires_self_evaluations
                (`user_id`,`completed`)
                values (".$user_id.", 'yes')";
                $wpdb->query($sql);

        ////// Vocal Code //////
        $count = $_POST['voice'];
        for($i = 0;$i<$count;$i++){
        	$voice[$_POST['instrument_vocal'.$i.'']] = array(
        		"group" => $_POST['groups_vocal'.$i.''],
        		"instrument" => $_POST['instrument_vocal'.$i.''], 
        		"rate_ability" => $_POST['rate_ability'.$i.''],
        		"groups_during_year" => $_POST['groups_during_year'.$i.''],
        		"required_audition" => $_POST['required_audition'.$i.''],
        		"learn_peice" => $_POST['learn_peice'.$i.''],
        		"difficult_peice" => $_POST['difficult_peice'.$i.''],
        		"studied_theory" => $_POST['studied_theory'.$i.''],
        		"voice_class" => $_POST['voice_class'.$i.''],
        		"voice_lessons" => $_POST['voice_lessons'.$i.''],
        		"singing_with_ensamble" => $_POST['singing_with_ensamble'.$i.''],
        		"sightreading_ability" =>$_POST['sightreading_ability'.$i.''],
        		"what_year_1" => $_POST['what_year_1'.$i.''],
        		"what_year_2" => $_POST['what_year_2'.$i.''],
        		"what_year_3" => $_POST['what_year_3'.$i.''],
        		);
        }

        foreach ($voice as $key => $value) {
        	# code...
        	$querystr = "
                SELECT *
                FROM ".$wpdb->prefix."questionnaires_self_evaluations
                WHERE user_id = ".$user_id." AND instrument = '".$key."'
                ";

          	$count_row = $wpdb->get_var($querystr);;

          	$sqldele = "DELETE FROM ".$wpdb->prefix."questionnaires_self_evaluations WHERE user_id = '".$user_id."' AND instrument ='".$key."'";
        		$wpdb->query($sqldele);
	      		$sql = "INSERT INTO ".$wpdb->prefix."questionnaires_self_evaluations
	              (`user_id`,`group`,`instrument`,`json`)
	              values (".$user_id.", 'vocal' , '".$key."' , '".json_encode($value)."')";
	              $wpdb->query($sql);

        }
        //////  Vocal Code End  ///////


        /////  String Code Start //////

        $count_string = $_POST['string'];
        for($i = 0;$i<$count_string;$i++){
        	$string[$_POST['instrument_string'.$i.'']] = array(
        		"group" => $_POST['groups_vocal'.$i.''],
        		"instrument" => $_POST['instrument_string'.$i.''], 
        		"groups_during_year_string" => $_POST['groups_during_year_string'.$i.''],
				"required_audition_string" => $_POST['required_audition_string'.$i.''],
				"learn_peice_string" => $_POST['learn_peice_string'.$i.''],
				"practice_alone_reg" => $_POST['practice_alone_reg'.$i.''],
				"study_privately" => $_POST['study_privately'.$i.''],
				"how_many_years" => $_POST['how_many_years'.$i.''],
				"play_chamber_music" => $_POST['play_chamber_music'.$i.''],
				"read_positions" => $_POST['read_positions'.$i.''],
				"like_to_play_jazz_ensamble" => $_POST['like_to_play_jazz_ensamble'.$i.''],
				"played_in_small_jazz_ensamble" => $_POST['played_in_small_jazz_ensamble'.$i.''],
				"played_in_big_band" => $_POST['played_in_big_band'.$i.''],
				"sightreading_ability_string" => $_POST['sightreading_ability_string'.$i.''],
				"chamber_music_ability" => $_POST['chamber_music_ability'.$i.''],
				"large_ensemble_ability" => $_POST['large_ensemble_ability'.$i.''],
        		);
        	foreach ($string as $key => $value) {
        		$sqldele = "DELETE FROM ".$wpdb->prefix."questionnaires_self_evaluations WHERE user_id = '".$user_id."' AND instrument ='".$key."'";
        		$wpdb->query($sqldele);
	      		$sql = "INSERT INTO ".$wpdb->prefix."questionnaires_self_evaluations
	              (`user_id`,`group`,`instrument`,`json`)
	              values (".$user_id.", 'string' , '".$key."' , '".json_encode($value)."')";
	              $wpdb->query($sql);
        	}
        }

        /////  String Code End //////


        /////  Piano Code Start //////

        $count_piano = $_POST['piano'];
        for($i = 0;$i<$count_piano;$i++){
          $piano[$_POST['instrument_piano'.$i.'']] = array(
            "group" => $_POST['groups_vocal'.$i.''],
            "instrument" => $_POST['instrument_piano'.$i.''], 
            "practice_alone_reg_piano" => $_POST['practice_alone_reg_piano'.$i.''],
            "study_privately_piano" => $_POST['study_privately_piano'.$i.''],
            "how_many_years_piano" => $_POST['how_many_years_piano'.$i.''],
            "like_to_play_jazz_ensamble_piano" => $_POST['like_to_play_jazz_ensamble_piano'.$i.''],
            "played_in_small_jazz_ensamble_piano" => $_POST['played_in_small_jazz_ensamble_piano'.$i.''],
            "played_in_big_band_piano" => $_POST['played_in_big_band_piano'.$i.''],
            "play_chamber_music_piano" => $_POST['play_chamber_music_piano'.$i.''],
            "list_composers_piano" => $_POST['list_composers_piano'.$i.''],
            "sightreading_ability_string_piano"=> $_POST['sightreading_ability_string_piano'.$i.''],
            "chamber_music_ability_piano" => $_POST['chamber_music_ability_piano'.$i.''],
            );
          foreach ($piano as $key => $value1) {
            $sqldele = "DELETE FROM ".$wpdb->prefix."questionnaires_self_evaluations WHERE user_id = '".$user_id."' AND instrument ='".$key."'";
            $wpdb->query($sqldele);
            $sql = "INSERT INTO ".$wpdb->prefix."questionnaires_self_evaluations
                (`user_id`,`group`,`instrument`,`json`)
                values (".$user_id.", 'piano' , '".$key."' , '".json_encode($value1)."')";
                $wpdb->query($sql);
          }
        }

        /////  Piano Code End //////

        /////  Brass_wind_percussionins Code Start //////
        $count_brass_wind_percussionins = $_POST['brass_wind_percussionins'];
        for($i = 0;$i<$count_brass_wind_percussionins;$i++){
          $brass_wind_percussionins[$_POST['instrument_brass_wind_percussionins'.$i.'']] = array(
              "group" => $_POST['groups_vocal'.$i.''],
              "instrument" => $_POST['instrument_brass_wind_percussionins'.$i.''], 
              "groups_during_year_brass" => $_POST['groups_during_year_brass'.$i.''],
              "required_audition_brass" => $_POST['required_audition_brass'.$i.''],
              "transpose_to_q" => $_POST['transpose_to_q'.$i.''],
              "practice_alone_reg_brass" => $_POST['practice_alone_reg_brass'.$i.''],
              "study_privately_brass" => $_POST['study_privately_brass'.$i.''],
              "how_many_years_brass" => $_POST['how_many_years_brass'.$i.''],
              "play_chamber_music_brass" => $_POST['play_chamber_music_brass'.$i.''],
              "instrunment_bring_camp_q" => $_POST['instrunment_bring_camp_q'.$i.''],
              "any_other_ins_brass" => $_POST['any_other_ins_brass'.$i.''],
              "sightreading_ability_string_brass" => $_POST['sightreading_ability_string_brass'.$i.''],
              "chamber_music_ability_brass" => $_POST['chamber_music_ability_brass'.$i.''],
              "large_ensemble_ability_brass" => $_POST['large_ensemble_ability_brass'.$i.''],
            );
          foreach ($brass_wind_percussionins as $key => $value1) {
            $sqldele = "DELETE FROM ".$wpdb->prefix."questionnaires_self_evaluations WHERE user_id = '".$user_id."' AND instrument ='".$key."'";
            $wpdb->query($sqldele);
            $sql = "INSERT INTO ".$wpdb->prefix."questionnaires_self_evaluations
                (`user_id`,`group`,`instrument`,`json`)
                values (".$user_id.", 'brass_wind_percussionins' , '".$key."' , '".json_encode($value1)."')";
                $wpdb->query($sql);
          }
        }

        /////  Brass_wind_percussionins Code End //////
        
          $querystr = "
                SELECT *
                FROM ".$wpdb->prefix."questionnaires_ensemble
                WHERE user_id = ".$user_id."
                ";

          $check_user = $wpdb->get_results($querystr, OBJECT);

          if(!empty($check_user)){
            // Update query
            $completed = 1;
            if($_REQUEST['bandoption'] == '' || $_REQUEST['ensemble'] == ''){
              $completed = 0;
            }

             $sql = "UPDATE ".$wpdb->prefix."questionnaires_ensemble set
                  instrumental = '".$_REQUEST['instrumental']."',
                  ensemble = '".$_REQUEST['ensemble']."',
                  bandoption = '".$_REQUEST['bandoption']."',
                  step2_completed = '".$completed."'
                  WHERE user_id = ".$user_id."";

              //$wpdb->query($sql);
            // End Update query
          }
          else{
            // Insert Query
            $completed = 1;
            if($_REQUEST['bandoption'] == '' || $_REQUEST['ensemble'] == ''){
              $completed = 0;
            }
            $sql = "INSERT INTO ".$wpdb->prefix."questionnaires_ensemble
              (`user_id`,`instrumental`,`ensemble`,`bandoption`,`step2_completed`)
              values (".$user_id.", '".$_REQUEST['instrumental']."' , '".$_REQUEST['ensemble']."' , '".$_REQUEST['bandoption']."', '".$completed."')";
            //$wpdb->query($sql);
            // End Insert Query
          }
          $_SESSION['success'] = "Saved successfully";
  }

  $queryselect = "
        SELECT *
        FROM ".$wpdb->prefix."questionnaires_ensemble
        WHERE user_id = ".$user_id."
        ";

  $getens = $wpdb->get_results($queryselect, OBJECT);

  $instrumentalget = '';
  $ensembleget = '';
  $bandoptionget = '';

  foreach ($getens as $ensembleget) {
    $instrumentalget = $ensembleget->instrumental;
    $bandoptionget = $ensembleget->bandoption;
    $ensembleget = $ensembleget->ensemble;
  }


?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
    <li><a href="<?php echo site_url(); ?>/afternoon-ensembles/">Afternoon Ensembles</a>
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
    <li class="active-li"><a style="<?php echo $style_5; ?>" href="<?php echo site_url(); ?>/self-evaluations/">Self-evaluations</a>
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
<style>
.title-of-form{
	text-align: center;
    text-transform: uppercase;
    font-weight: bold;
}
input[type=checkbox].error, input[type=textbox].error, input[type=text].error{ -webkit-box-shadow:inset 2px 1px 1px , 1px 1px 3px red;
    -moz-box-shadow:inset 2px 1px 1px red, 1px 1px 3px red;
    box-shadow:inset 2px 1px 1px red, 1px 1px 3px red;}
input[type=radio].error{
    box-shadow:inset 2px 1px 1px red, 1px 1px 3px red;
    border-radius: 10px;
}
textarea.error {
    border-color: red;
}

.row{
      border: 1px solid #cccccc;
      padding: 10px;
      width: 97%;
      text-align: center;
      margin: 0 auto;
    }
</style>
<?php


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

      if($getUserDetails->field_number == '23'){
           $instrumental = $getUserDetails->value;
      }
    }
  }
$selected_instruments = array();
$selected_instruments[] = $instrumental;
$get_second_step_ins = $wpdb->get_results('SELECT * FROM wp_questionnaires_afternoon where userID = "'.$user_id.'"', ARRAY_A);
foreach($get_second_step_ins as $secondary_ins){
	if($secondary_ins['instrument1'] != ''){
		$selected_instruments[] = $secondary_ins['instrument1'];
	}
	if($secondary_ins['instrument2'] != ''){
		$selected_instruments[] = $secondary_ins['instrument2'];
	}
	if($secondary_ins['p_instrument1'] != ''){
		$selected_instruments[] = $secondary_ins['p_instrument1'];
	}
	if($secondary_ins['p_instrument2'] != ''){
		$selected_instruments[] = $secondary_ins['p_instrument2'];
	}
}
$get_third_step_ins = $wpdb->get_results('SELECT * FROM wp_questionnaires_electives where user_id = "'.$user_id.'"', ARRAY_A);
foreach($get_third_step_ins as $thirdry_ins){
		if($thirdry_ins['options'] != ''){
			$selected_instruments[] = ucfirst($thirdry_ins['options']);
		}
}
$finalinstruments = array_unique($selected_instruments);
$get_instruments = new WP_Query( array( 'post_type'=>'instruments', 'posts_per_page' => -1 ) );
$all_instruments = array();

$ins_id = '';
while ( $get_instruments->have_posts() ) { $get_instruments->the_post();
	$all_instruments[] = get_the_title();
}

$get_instruments_group = new WP_Query( array( 'post_type'=>'instruments', 'posts_per_page' => -1 , 'post_name__in' => $finalinstruments ) );
$all_instruments_group_name = array();
while ( $get_instruments_group->have_posts() ) { $get_instruments_group->the_post();
	$all_instruments_group_name[get_post_meta(get_the_ID(),'self_evaluation_choice_group',true)][] = get_the_title();
	$ins_id = get_the_ID();
}

?>
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
<form method="post" name="self_evalutation" id="step5_form">

<input type="hidden" name="instrumental" value="<?php echo $instrumental; ?>">
<?php $incomplete = 0; 
$vocal = array();
$piano = array();
$brass_wind_percussion = array();
$string = array();
foreach($all_instruments_group_name as $key=>$groups_diff){
	if($key=='vocal'){
		$vocal = $groups_diff;
	}
	if($key=='piano'){
		$piano = $groups_diff;
	}
	if($key=='brass_wind_percussion'){
		$brass_wind_percussion = $groups_diff;
	}
	if($key=='string'){
		$string = $groups_diff;
	}
}
$v=0;
if(in_array($instrumental,$all_instruments)){
if(!empty($vocal)){
	$i = 0;
	foreach($vocal as $vocalins){


	?>
	<input type="hidden"  name="instrument_vocal<?php echo $v ?>" value="<?php echo $vocalins; ?>">
	<input type="hidden" name="groups_vocal<?php echo $v; ?>" value="vocal">
	<table class="custom-table">
	<tr><td class="title-of-form">EVALUATION FOR <?php echo $vocalins; ?></td></tr>
	<tr><td>Contact Jason Anderson if you have questions: 206-799-5158 or <a href="mailto:andersonjason3@me.com">andersonjason3@me.com</a></td></tr>
	<tr>
	<?php 
	$vocal_data = "
                SELECT *
                FROM ".$wpdb->prefix."questionnaires_self_evaluations
                WHERE user_id = ".$user_id." AND instrument = '".$vocalins."'
                ";

          $vocal_final_data = $wpdb->get_results($vocal_data, OBJECT);
          foreach ($vocal_final_data as $value) {
          	$json = $value->json;
          }
          $voice_array = json_decode($json);
	?>
		<td>
		<label>Rate your overall ability as a singer : </label>
		<label class="radio-inline">
		  <input  type="radio" <?php if($voice_array->rate_ability == "Beginner"){ echo "checked"; } ?> name="rate_ability<?php echo $v; ?>" value="Beginner" />Beginner 
		</label>
		<label class="radio-inline">
		  <input type="radio" <?php if($voice_array->rate_ability == "Intermediate"){ echo "checked"; } ?> name="rate_ability<?php echo $v; ?>" value="Intermediate" />Intermediate
		</label>
		<label class="radio-inline">
		  <input type="radio" <?php if($voice_array->rate_ability == "Experianced"){ echo "checked"; } ?> name="rate_ability<?php echo $v; ?>" value="Experianced" />Experianced
		</label>
		<label class="radio-inline">
		  <input type="radio" <?php if($voice_array->rate_ability == "Advanced"){ echo "checked"; } ?> name="rate_ability<?php echo $v; ?>" value="Advanced" />Advanced
	  </label>
		</td>
	</tr>
	<tr>
		<td>
		  <label>Groups you play/sing with during the year : </label>
		  <textarea pattern="[a-zA-Z0-9\s]+" name="groups_during_year<?php echo $v; ?>"><?php echo $voice_array->groups_during_year ;?></textarea>
		</td>
	</tr>
	<tr>
		<td>
		  <label>Do any of these groups require an audition? : </label>
		  <input type="checkbox" <?php if($voice_array->required_audition =="yes"){echo "checked"; } ?> name="required_audition<?php echo $v; ?>" value="yes" />
		</td>
	</tr>
	<tr>
		<td>
		  <label>How best do you learn a new peice : </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($voice_array->learn_peice == "Reading the music"){ echo "checked"; } ?> name="learn_peice<?php echo $v; ?>" value="Reading the music" />Reading the music
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($voice_array->learn_peice == "By Ear with repetition"){ echo "checked"; } ?> name="learn_peice<?php echo $v; ?>" value="By Ear with repetition" />By Ear with repetition
		  </label>
		</td>
	</tr>
	<tr>
		<td>
		  <label>Most difficult peice you have learned : </label>
		  <input pattern="^\S+$" type="text" name="difficult_peice<?php echo $v; ?>" value="<?php echo $voice_array->difficult_peice; ?>" />
		</td>
	</tr>
	<tr>
		<td>
		  <label>Musical Training : </label>
		  <table>
		  <tr><td>Have you studied music theory? <input type="checkbox" <?php if($voice_array->studied_theory == "yes"){ echo "checked"; } ?> name="studied_theory<?php echo $v; ?>" value="yes" /></td><td>What year : <input type="text" pattern="^\S+$" name="what_year_1<?php echo $v; ?>" value="<?php echo $voice_array->what_year_1; ?>" /></td></tr>
		  <tr><td>Have you taken a voice class? <input type="checkbox" name="voice_class<?php echo $v; ?>" <?php if($voice_array->voice_class == "yes"){ echo "checked"; } ?>  value="yes" /></td><td>What year : <input pattern="^\S+$" type="text" name="what_year_2<?php echo $v; ?>" value="<?php echo $voice_array->what_year_2;?>" /></td></tr>
		  <tr><td>Have you taken a voice lessons? <input type="checkbox" name="voice_lessons<?php echo $v; ?>" <?php if($voice_array->voice_lessons == "yes"){ echo "checked"; } ?> value="yes" /></td><td>What year : <input pattern="^\S+$" type="text" name="what_year_3<?php echo $v; ?>" value="<?php echo $voice_array->what_year_3?>" /></td></tr>
		  </table>
		</td>
	</tr>
  <tr>
		<td>
		  <label>When singing with a small ensemble, you : </label><br/>
		  
		  <label class="radio-inline">
		    <input type="radio" name="singing_with_ensamble<?php echo $v; ?>" <?php
		    if($voice_array->singing_with_ensamble == "Prefers to sing with others, accompained"){ echo "checked"; } ?> value="Prefers to sing with others, accompained" />Prefers to sing with others, accompained
		  </label>
		  <label class="radio-inline">
		    <input type="radio" name="singing_with_ensamble<?php echo $v; ?>" <?php
		    if($voice_array->singing_with_ensamble == "Prefers to sing with others, with or without accompaniment"){ echo "checked"; } ?> value="Prefers to sing with others, with or without accompaniment" />Prefers to sing with others, with or without accompaniment
		  </label>
		  <label class="radio-inline">
		    <input type="radio" name="singing_with_ensamble<?php echo $v; ?>" <?php
		    if($voice_array->singing_with_ensamble == "Can handle your own part, without accompaniment"){ echo "checked"; } ?> value="Can handle your own part, without accompaniment" />Can handle your own part, without accompaniment
		  </label>
		</td>
	</tr>
	<tr>
		<td>
		  <label>Sightreading ability : </label><br/>
		  <label class="radio-inline">
		    <input type="radio" <?php if($voice_array->sightreading_ability == "Poor"){ echo "checked";} ?> name="sightreading_ability<?php echo $v; ?>" value="Poor" />Poor
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($voice_array->sightreading_ability == "Average"){ echo "checked";} ?> name="sightreading_ability<?php echo $v; ?>" value="Average" />Average
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($voice_array->sightreading_ability == "Good"){ echo "checked";} ?> name="sightreading_ability<?php echo $v; ?>" value="Good" />Good
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($voice_array->sightreading_ability == "Excellent"){ echo "checked";} ?> name="sightreading_ability<?php echo $v; ?>" value="Excellent" />Excellent
		  </label>
		</td>
	</tr>
	</table>
	<?php
	$i++;
	$v++;
	}
	?>
	<input type="hidden" name="voice" value="<?php echo $i;?>">
	<?php
}
$s = 0;
if(!empty($string)){
	$i =0;
	foreach($string as $stringins){
 ?>
 <table class="custom-table">
	<tr><td class="title-of-form">EVALUATION FOR <?php echo $stringins; ?></td></tr>
	<tr><td>Contact Thane Lewis if you have questions: 206-363-3903 or <a href="mailto:thanemail33@gmail.com">thanemail33@gmail.com</a></td></tr>
	<input type="hidden"  name="instrument_string<?php echo $s ?>" value="<?php echo $stringins; ?>">
	<input type="hidden" name="groups_vocal<?php echo $s; ?>" value="string">
  <?php 
  $vocal_data = "
                SELECT *
                FROM ".$wpdb->prefix."questionnaires_self_evaluations
                WHERE user_id = ".$user_id." AND instrument = '".$stringins."'
                ";

          $string_final_data = $wpdb->get_results($vocal_data, OBJECT);
          foreach ($string_final_data as $value) {
            $json = $value->json;
          }
          $string_array = json_decode($json);
  ?>
	<tr>
		<td>
		  <label>Groups you play/sing with during the year : </label>
		  <textarea pattern="^\S+$" name="groups_during_year_string<?php echo $s; ?>"><?php echo $string_array->groups_during_year_string; ?></textarea>
		</td>
	</tr>
	<tr>
		<td>
		  <label>Do any of these groups require an audition? : </label>
		  <input type="checkbox" <?php if($string_array->required_audition_string == "yes"){ echo "checked"; }  ?> name="required_audition_string<?php echo $s; ?>" value="yes" />
		</td>
	</tr>
	<tr>
		<td>
		  <label>How best do you learn a new peice : </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($string_array->learn_peice_string == "Reading the music") { echo "checked"; } ?> name="learn_peice_string<?php echo $s; ?>" value="Reading the music" />Reading the music
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($string_array->learn_peice_string == "By Ear with repetition") { echo "checked"; } ?> name="learn_peice_string<?php echo $s; ?>" value="By Ear with repetition" />By Ear with repetition
		  </label>
		</td>
	</tr>
	<tr>
		<td>
		  <label>Do you practice alone regularly? : </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($string_array->practice_alone_reg == "Rarely"){ echo "checked"; } ?> name="practice_alone_reg<?php echo $s; ?>" value="Rarely" />Rarely
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($string_array->practice_alone_reg == "2 or 3 times a week"){ echo "checked"; } ?> name="practice_alone_reg<?php echo $s; ?>" value="2 or 3 times a week" />2 or 3 times a week
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($string_array->practice_alone_reg == " or more times a week"){ echo "checked"; } ?> name="practice_alone_reg<?php echo $s; ?>" value="4 or more times a week" />4 or more times a week
		  </label>
		</td>
	</tr>
	<tr>
		<td>
		  <label>Do you study privately? : </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($string_array->study_privately == "Yes"){ echo "checked"; } ?> id="study_privately_yes" name="study_privately<?php echo $s; ?>" value="Yes" />Yes
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($string_array->study_privately == "No"){ echo "checked"; } ?>  id="study_privately_no" name="study_privately<?php echo $s; ?>" value="No" />No</br>
		    For how many years? <input id="how_many_years" type="text" name="how_many_years<?php echo $s; ?>" disabled="disabled" />
		  </label>
		</td>
	</tr>
	<tr>
		<td>
		  <label>How often you play chamber music? : </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($string_array->play_chamber_music == "Less than once a week"){ echo "checked"; } ?> name="play_chamber_music<?php echo $s; ?>" value="Less than once a week" />Less than once a week
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($string_array->play_chamber_music == "Weekly"){ echo "checked"; } ?> name="play_chamber_music<?php echo $s; ?>" value="Weekly" />Weekly
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($string_array->play_chamber_music == "More than once a week"){ echo "checked"; } ?> name="play_chamber_music<?php echo $s; ?>" value="More than once a week" />More than once a week
		  </label>
		</td>
	</tr>
	<tr>
		<td>
      <?php $array = $string_array->read_positions;?>
		  <label>I can read well in these positions (check all that apply) : </label>
		  <input type="checkbox" <?php if(in_array("3rd position", $array)){ echo "checked"; } ?> name="read_positions<?php echo $s; ?>[]" value="3rd position" />3rd position
		  <input type="checkbox" <?php if(in_array("4th position", $array)){ echo "checked"; } ?> name="read_positions<?php echo $s; ?>[]" value="4th position" />4th position
		  <input type="checkbox" <?php if(in_array("5th position", $array)){ echo "checked"; } ?> name="read_positions<?php echo $s; ?>[]" value="5th position" />5th position
		  <input type="checkbox" <?php if(in_array("6th position", $array)){ echo "checked"; } ?> name="read_positions<?php echo $s; ?>[]" value="6th position" />6th position
		  <input type="checkbox" <?php if(in_array("7th position", $array)){ echo "checked"; } ?> name="read_positions<?php echo $s; ?>[]" value="7th position" />7th position
		</td>
	</tr>
	<?php if( $stringins == 'Double Bass' ){ ?>
	<tr>
		<td>
		  <label>Jazz ensembles : </label>
		  <table>
		    <tr>
		      <td>
		        Would you like to play in a jazz ensemble or big band?</br>
		        <label class="radio-inline">
		          <input type="radio" <?php if($string_array->like_to_play_jazz_ensamble == "Yes"){ echo "checked"; } ?> name="like_to_play_jazz_ensamble<?php echo $s; ?>" value="Yes" />Yes
		        </label>
		        <label class="radio-inline">
		          <input type="radio" <?php if($string_array->like_to_play_jazz_ensamble == "No"){ echo "checked"; } ?> name="like_to_play_jazz_ensamble<?php echo $s; ?>" value="No" />No
		        </label>
		      </td>
		      <td>
		        Have you played in a small jazz ensemble?</br>
		        <label class="radio-inline">
		          <input type="radio" <?php if($string_array->played_in_small_jazz_ensamble == "Yes"){ echo "checked"; } ?> name="played_in_small_jazz_ensamble<?php echo $s; ?>" value="Yes" />Yes
		        </label>
		        <label class="radio-inline">
		          <input type="radio" <?php if($string_array->played_in_small_jazz_ensamble == "No"){ echo "checked"; } ?> name="played_in_small_jazz_ensamble<?php echo $s; ?>" value="No" />No
		        </label>
		      </td>
		      <td>
		        Have you played in a big band?</br>
		        <label class="radio-inline">
		          <input type="radio" <?php if($string_array->played_in_big_band == "Yes"){ echo "checked"; } ?> name="played_in_big_band<?php echo $s; ?>" value="Yes" />Yes
		        </label>
		        <label class="radio-inline">
		          <input type="radio" <?php if($string_array->played_in_big_band == "No"){ echo "checked"; } ?> name="played_in_big_band<?php echo $s; ?>" value="No" />No
		        </label>
		
		      </td>
		    </tr>
		  </table>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td>
		  <label>Sightreading ability : </label><br/>
		  <input type="radio" <?php if($string_array->sightreading_ability_string == "Poor"){ echo "checked"; } ?> name="sightreading_ability_string<?php echo $s; ?>" value="Poor" />Poor
		  <input type="radio" <?php if($string_array->sightreading_ability_string == "Average"){ echo "checked"; } ?> name="sightreading_ability_string<?php echo $s; ?>" value="Average" />Average
		  <input type="radio" <?php if($string_array->sightreading_ability_string == "Good"){ echo "checked"; } ?> name="sightreading_ability_string<?php echo $s; ?>" value="Good" />Good
		  <input type="radio" <?php if($string_array->sightreading_ability_string == "Excellent"){ echo "checked"; } ?> name="sightreading_ability_string<?php echo $s; ?>" value="Excellent" />Excellent
		</td>
	</tr>
	<tr>
		<td>
		  <label>Chamber music ability : </label>
		  <label class="radio-inline">
		  <input type="radio" <?php if($string_array->chamber_music_ability == "Beginner"){ echo "checked"; } ?> name="chamber_music_ability<?php echo $s; ?>" value="Beginner" />Beginner
		  </label>
		<label class="radio-inline">
		  <input type="radio" <?php if($string_array->chamber_music_ability == "Novice"){ echo "checked"; } ?> name="chamber_music_ability<?php echo $s; ?>" value="Novice" />Novice
		  </label>
		<label class="radio-inline">
		  <input type="radio" <?php if($string_array->chamber_music_ability == "Intermediate"){ echo "checked"; } ?> name="chamber_music_ability<?php echo $s; ?>" value="Intermediate" />Intermediate
		  </label>
		<label class="radio-inline">
		  <input type="radio" <?php if($string_array->chamber_music_ability == "Experianced"){ echo "checked"; } ?> name="chamber_music_ability<?php echo $s; ?>" value="Experianced" />Experianced
		  </label>
		<label class="radio-inline">
		  <input type="radio" <?php if($string_array->chamber_music_ability == "Advanced"){ echo "checked"; } ?> name="chamber_music_ability<?php echo $s; ?>" value="Advanced" />Advanced
		</label>
		</td>
	</tr>
	<tr>
		<td>
		  <label>Large ensemble ability (band,orchestra,chorus) : </label>
		  <label class="radio-inline">
		  <input type="radio" <?php if($string_array->large_ensemble_ability == "Beginner"){ echo "checked"; } ?> name="large_ensemble_ability<?php echo $s; ?>" value="Beginner" />Beginner
		  </label>
		<label class="radio-inline">
		  <input type="radio" <?php if($string_array->large_ensemble_ability == "Novice"){ echo "checked"; } ?> name="large_ensemble_ability<?php echo $s; ?>" value="Novice" />Novice
		  </label>
		<label class="radio-inline">
		  <input type="radio" <?php if($string_array->large_ensemble_ability == "Intermediate"){ echo "checked"; } ?> name="large_ensemble_ability<?php echo $s; ?>" value="Intermediate" />Intermediate
		  </label>
		<label class="radio-inline">
		  <input type="radio" <?php if($string_array->large_ensemble_ability == "Experianced"){ echo "checked"; } ?> name="large_ensemble_ability<?php echo $s; ?>" value="Experianced" />Experianced
		  </label>
		<label class="radio-inline">
		  <input type="radio" <?php if($string_array->large_ensemble_ability == "Advanced"){ echo "checked"; } ?> name="large_ensemble_ability<?php echo $s; ?>" value="Advanced" />Advanced
		  </label>
		</td>
	</tr>
	</table>
 <?php
 $i++;
 $s++;
	}
	?>
	<input type="hidden" name="string" value="<?php echo $i;?>">
	<?php 
 }
?>

<?php $p=0; $i=0; if(!empty($piano)){
	foreach($piano as $pianoins){
 ?>
 <table class="custom-table">
	<tr><td class="title-of-form">EVALUATION FOR <?php echo $pianoins; ?></td></tr>
	<tr><td>Contact Thane Lewis if you have questions: 206-363-3903 or <a href="mailto:thanemail33@gmail.com">thanemail33@gmail.com</a></td></tr>
  <input type="hidden"  name="instrument_piano<?php echo $p ?>" value="<?php echo $pianoins; ?>">
  <input type="hidden" name="groups_vocal<?php echo $p; ?>" value="piano">
  <?php 
  $piano_data = "
                SELECT *
                FROM ".$wpdb->prefix."questionnaires_self_evaluations
                WHERE user_id = ".$user_id." AND instrument = '".$pianoins."'
                ";

          $piano_final_data = $wpdb->get_results($piano_data, OBJECT);
          foreach ($piano_final_data as $value) {
            $json = $value->json;
          }
          $piano_array = json_decode($json);
  ?>
	<tr>
		<td>
		  <label>Do you practice alone regularly? : </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($piano_array->practice_alone_reg_piano == "Rarely"){ echo "checked"; } ?> name="practice_alone_reg_piano<?php echo $p; ?>" value="Rarely" />Rarely
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($piano_array->practice_alone_reg_piano == "2 or 3 times a week"){ echo "checked"; } ?> name="practice_alone_reg_piano<?php echo $p; ?>" value="2 or 3 times a week" />2 or 3 times a week
		  </label>
		  <label class="radio-inline">
		  <input type="radio" <?php if($piano_array->practice_alone_reg_piano == "4 or more times a week"){ echo "checked"; } ?> name="practice_alone_reg_piano<?php echo $p; ?>" value="4 or more times a week" />4 or more times a week
		  </label>
		</td>
	</tr>
	<tr>
		<td>
		  <label>Do you study privately? : </label>
		  <label class="radio-inline">
		  <input type="radio" <?php if($piano_array->study_privately_piano == "Yes"){ echo "checked"; } ?>  id="study_privately_yes_piano" name="study_privately_piano<?php echo $p; ?>" value="Yes" />Yes
		  </label>
		<label class="radio-inline">
		  <input type="radio" <?php if($piano_array->study_privately_piano == "No"){ echo "checked"; } ?> id="study_privately_no_piano" name="study_privately_piano<?php echo $p; ?>" value="No" />No</br>
		  </label>
		  For how many years? <input id="how_many_years_piano" type="text" name="how_many_years_piano<?php echo $p; ?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td>
		  <label>Jazz ensembles : </label>
		  <table>
		    <tr>
		      <td>
		        Would you like to play in a jazz ensemble or big band?</br>
		        <label class="radio-inline">
		        <input type="radio" <?php if($piano_array->like_to_play_jazz_ensamble_piano == "Yes"){ echo "checked"; } ?>  name="like_to_play_jazz_ensamble_piano<?php echo $p; ?>" value="Yes" />Yes
		        </label>
		        <label class="radio-inline">
		        <input type="radio" <?php if($piano_array->like_to_play_jazz_ensamble_piano == "No"){ echo "checked"; } ?> name="like_to_play_jazz_ensamble_piano<?php echo $p; ?>" value="No" />No
		        </label>
		      </td>
		      <td>
		        Have you played in a small jazz ensemble?</br>
		        <label class="radio-inline">
		        <input type="radio" <?php if($piano_array->played_in_small_jazz_ensamble_piano == "Yes"){ echo "checked"; } ?> name="played_in_small_jazz_ensamble_piano<?php echo $p; ?>" value="Yes" />Yes
		        </label>
		        <label class="radio-inline">
		        <input type="radio" <?php if($piano_array->played_in_small_jazz_ensamble_piano == "No"){ echo "checked"; } ?>  name="played_in_small_jazz_ensamble_piano<?php echo $p; ?>" value="No" />No
		        </label>
		      </td>
		      <td>
		        Have you played in a big band?</br>
		        <label class="radio-inline">
		        <input type="radio" <?php if($piano_array->played_in_big_band_piano == "Yes"){ echo "checked"; } ?> name="played_in_big_band_piano<?php echo $p; ?>" value="Yes" />Yes
		        </label>
		        <label class="radio-inline">
		        <input type="radio" <?php if($piano_array->played_in_big_band_piano == "No"){ echo "checked"; } ?> name="played_in_big_band_piano<?php echo $p; ?>" value="No" />No
		        </label>
		      </td>
		    </tr>
		  </table>
		</td>
	</tr>
	<tr>
		<td>
		  <label>How often you play chamber music? : </label>
		  <label class="radio-inline">
		  <input type="radio" <?php if($piano_array->play_chamber_music_piano == "Less than once a week"){ echo "checked"; } ?> name="play_chamber_music_piano<?php echo $p; ?>" value="Less than once a week" />Less than once a week
		  </label>
		  <label class="radio-inline">
		  <input type="radio" <?php if($piano_array->play_chamber_music_piano == "Weekly"){ echo "checked"; } ?> name="play_chamber_music_piano<?php echo $p; ?>" value="Weekly" />Weekly
		  </label>
		  <label class="radio-inline">
		  <input type="radio" <?php if($piano_array->play_chamber_music_piano == "More than once a week"){ echo "checked"; } ?> name="play_chamber_music_piano<?php echo $p; ?>" value="More than once a week" />More than once a week
		  </label>
		</td>
	</tr>
	<tr>
		<td>
		  <label>List composers whose works you have recently enjoyed practicing : </label>
		  <textarea pattern="^\S+$" name="list_composers_piano<?php echo $p; ?>"><?php echo $piano_array->list_composers_piano; ?></textarea>
		</td>
	</tr>
	<tr>
		<td>
		  <label>Sightreading ability : </label><br/>
		  <label class="radio-inline">
		  <input type="radio" <?php if($piano_array->sightreading_ability_string_piano == "Poor"){ echo "checked"; } ?> name="sightreading_ability_string_piano<?php echo $p; ?>" value="Poor" />Poor
		  </label>
		        <label class="radio-inline">
		  <input type="radio" <?php if($piano_array->sightreading_ability_string_piano == "Average"){ echo "checked"; } ?>  name="sightreading_ability_string_piano<?php echo $p; ?>" value="Average" />Average
		  </label>
		        <label class="radio-inline">
		  <input type="radio" <?php if($piano_array->sightreading_ability_string_piano == "Good"){ echo "checked"; } ?> name="sightreading_ability_string_piano<?php echo $p; ?>" value="Good" />Good
		  </label>
		        <label class="radio-inline">
		  <input type="radio" <?php if($piano_array->sightreading_ability_string_piano == "Excellent"){ echo "checked"; } ?> name="sightreading_ability_string_piano<?php echo $p; ?>" value="Excellent" />Excellent
		  </label>
		</td>
	</tr>
	<tr>
		<td>
		  <label>Chamber music ability : </label>
		  <label class="radio-inline">
		  <input type="radio" <?php if($piano_array->chamber_music_ability_piano == "Beginner"){ echo "checked"; } ?> name="chamber_music_ability_piano<?php echo $p; ?>" value="Beginner" />Beginner
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($piano_array->chamber_music_ability_piano == "Novice"){ echo "checked"; } ?> name="chamber_music_ability_piano<?php echo $p; ?>" value="Novice" />Novice
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($piano_array->chamber_music_ability_piano == "Intermediate"){ echo "checked"; } ?> name="chamber_music_ability_piano<?php echo $p; ?>" value="Intermediate" />Intermediate
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($piano_array->chamber_music_ability_piano == "Experianced"){ echo "checked"; } ?> name="chamber_music_ability_piano<?php echo $p; ?>" value="Experianced" />Experianced
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($piano_array->chamber_music_ability_piano == "Advanced"){ echo "checked"; } ?> name="chamber_music_ability_piano<?php echo $p; ?>" value="Advanced" />Advanced
		  </label>
		</td>
	</tr>
	</table>
 <?php
  $i++;
	$p++;
	}
  ?>
  <input type="hidden" name="piano" value="<?php echo $i;?>">
  <?php
 }
?>

<?php 
	$b = 0;
  $i = 0;
	if(!empty($brass_wind_percussion)){
	foreach($brass_wind_percussion as $brass_wind_percussionins){
	$get_id = array();
	$get_instruments_id = new WP_Query( array( 'post_type'=>'instruments', 'posts_per_page' => -1 , 'post_name__in' => array($brass_wind_percussionins) ) );
	while ( $get_instruments_id->have_posts() ) { $get_instruments_id->the_post();
		$get_id = get_the_ID();
	}
	$get_transposition_q = get_post_meta($get_id,'transposition_q',true);
	$get_other_q = get_post_meta($get_id,'other_ins_q',true);
 ?>
 <table class="custom-table">
	<tr><td class="title-of-form">EVALUATION FOR <?php echo $brass_wind_percussionins; ?></td></tr>
	<tr><td>Contact Dane Williams if you have questions: 206-365-3158 or <a href="mailto:reedroom@comcast.net">reedroom@comcast.net</a></td></tr>
	<tr>
  <input type="hidden"  name="instrument_brass_wind_percussionins<?php echo $b ?>" value="<?php echo $brass_wind_percussionins; ?>">
  <input type="hidden" name="groups_vocal<?php echo $b; ?>" value="brass_wind_percussionins">
  <?php 
  $brass_data = "
                SELECT *
                FROM ".$wpdb->prefix."questionnaires_self_evaluations
                WHERE user_id = ".$user_id." AND instrument = '".$brass_wind_percussionins."'
                ";

          $brass_final_data = $wpdb->get_results($brass_data, OBJECT);
          foreach ($brass_final_data as $value) {
            $json = $value->json;
          }
          $brass_array = json_decode($json);
  ?>

		<td>
		  <label>Groups you play/sing with during the year : </label>
		  <textarea pattern="^\S+$" name="groups_during_year_brass<?php echo $b; ?>"><?php echo $brass_array->groups_during_year_brass; ?></textarea>
		</td>
	</tr>
	<tr>
		<td>
		  <label>Do any of these groups require an audition? : </label>
		  <input type="checkbox" <?php if($brass_array->required_audition_brass == "yes"){ echo "checked"; } ?> name="required_audition_brass<?php echo $b; ?>" value="yes" />
		</td>
	</tr>
	<?php if($get_transposition_q != ''){
	$explode_q1 = explode(',',$get_transposition_q);
	?>
	<tr>
		<td>
		  <label>What keys can you transpose to? : </label>
		  <?php foreach($explode_q1 as $q1){ ?>
		  <input type="checkbox" <?php if($brass_array->transpose_to_q == $q1){ echo "checked"; } ?>  name="transpose_to_q<?php echo $b; ?>[]" value="<?php echo $q1; ?>" /><?php echo $q1; ?>
		  <?php } ?>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td>
		  <label>Do you practice alone regularly? : </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($brass_array->practice_alone_reg_brass == "Rarely"){ echo "checked"; } ?> name="practice_alone_reg_brass<?php echo $b; ?>" value="Rarely" />Rarely
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($brass_array->practice_alone_reg_brass == "2 or 3 times a week"){ echo "checked"; } ?> name="practice_alone_reg_brass<?php echo $b; ?>" value="2 or 3 times a week" />2 or 3 times a week
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($brass_array->practice_alone_reg_brass == "4 or more times a week"){ echo "checked"; } ?> name="practice_alone_reg_brass<?php echo $b; ?>" value="4 or more times a week" />4 or more times a week
		  </label>
		</td>
	</tr>
	<tr>
		<td>
		  <label>Do you study privately? : </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($brass_array->study_privately_brass == "Yes"){ echo "checked"; } ?> id="study_privately_yes_brass" name="study_privately_brass<?php echo $b; ?>" value="Yes" />Yes
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($brass_array->study_privately_brass == "No"){ echo "checked"; } ?> id="study_privately_no_brass" name="study_privately_brass<?php echo $b; ?>" value="No" />No
		  </label>
		  </br>
		  For how many years? <input id="how_many_years_brass" type="text" name="how_many_years_brass<?php echo $b; ?>" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td>
		  <label>How often you play chamber music? : </label>
		  <label class="radio-inline">
		  <input type="radio" <?php if($brass_array->play_chamber_music_brass == "Less than once a week"){ echo "checked"; } ?> name="play_chamber_music_brass<?php echo $b; ?>" value="Less than once a week" />Less than once a week
		  </label>
		  <label class="radio-inline">
		  <input type="radio" <?php if($brass_array->play_chamber_music_brass == "Weekly"){ echo "checked"; } ?> name="play_chamber_music_brass<?php echo $b; ?>" value="Weekly" />Weekly
		  </label>
		  <label class="radio-inline">
		  <input type="radio" <?php if($brass_array->play_chamber_music_brass == "More than once a week"){ echo "checked"; } ?> name="play_chamber_music_brass<?php echo $b; ?>" value="More than once a week" />More than once a week
		  </label>
		</td>
	</tr>
	<?php if($get_other_q != ''){
	$explode_q2 = explode(',',$get_other_q);
	?>
	<tr>
		<td>
		  <label>What instruments are you bringing to camp? : </label>
		  <?php foreach($explode_q2 as $q2){ ?>
      <?php $array = $brass_array->instrunment_bring_camp_q; ?>
      <label class="checkbox-inline">
		    <input type="checkbox" <?php if(in_array($q2, $array)){ echo "checked"; } ?> name="instrunment_bring_camp_q<?php echo $b; ?>[]" value="<?php echo $q2; ?>" /><?php echo $q2; ?>
		  </label>
		  <?php } ?>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td>
		  <label>Any other instruments we should know about? : </label>
		  <textarea pattern="^\S+$" name="any_other_ins_brass<?php echo $b; ?>"><?php echo $brass_array->any_other_ins_brass; ?></textarea>
		</td>
	</tr>
	<tr>
		<td>
		  <label>Sightreading ability : </label><br/>
		  <label class="radio-inline">
		    <input type="radio" <?php if($brass_array->sightreading_ability_string_brass == "Poor"){ echo "checked"; } ?> name="sightreading_ability_string_brass<?php echo $b; ?>" value="Poor" />Poor
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($brass_array->sightreading_ability_string_brass == "Average"){ echo "checked"; } ?> name="sightreading_ability_string_brass<?php echo $b; ?>" value="Average" />Average
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($brass_array->sightreading_ability_string_brass == "Good"){ echo "checked"; } ?> name="sightreading_ability_string_brass<?php echo $b; ?>" value="Good" />Good
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($brass_array->sightreading_ability_string_brass == "Excellent"){ echo "checked"; } ?> name="sightreading_ability_string_brass<?php echo $b; ?>" value="Excellent" />Excellent
		  </label>
		</td>
	</tr>
	<tr>
		<td>
		  <label>Chamber music ability : </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($brass_array->chamber_music_ability_brass == "Beginner"){ echo "checked"; } ?> name="chamber_music_ability_brass<?php echo $b; ?>" value="Beginner" />Beginner
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($brass_array->chamber_music_ability_brass == "Novice"){ echo "checked"; } ?> name="chamber_music_ability_brass<?php echo $b; ?>" value="Novice" />Novice
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($brass_array->chamber_music_ability_brass == "Intermediate"){ echo "checked"; } ?> name="chamber_music_ability_brass<?php echo $b; ?>" value="Intermediate" />Intermediate
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($brass_array->chamber_music_ability_brass == "Experianced"){ echo "checked"; } ?> name="chamber_music_ability_brass<?php echo $b; ?>" value="Experianced" />Experianced
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($brass_array->chamber_music_ability_brass == "Advanced"){ echo "checked"; } ?> name="chamber_music_ability_brass<?php echo $b; ?>" value="Advanced" />Advanced
		  </label>
		</td>
	</tr>
	<tr>
		<td>
		  <label>Large ensemble ability (band,orchestra,chorus) : </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($brass_array->large_ensemble_ability_brass == "Beginner"){ echo "checked"; } ?> name="large_ensemble_ability_brass<?php echo $b; ?>" value="Beginner" />Beginner
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($brass_array->large_ensemble_ability_brass == "Novice"){ echo "checked"; } ?> name="large_ensemble_ability_brass<?php echo $b; ?>" value="Novice" />Novice
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($brass_array->large_ensemble_ability_brass == "Intermediate"){ echo "checked"; } ?> name="large_ensemble_ability_brass<?php echo $b; ?>" value="Intermediate" />Intermediate
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($brass_array->large_ensemble_ability_brass == "Experianced"){ echo "checked"; } ?> name="large_ensemble_ability_brass<?php echo $b; ?>" value="Experianced" />Experianced
		  </label>
		  <label class="radio-inline">
		    <input type="radio" <?php if($brass_array->large_ensemble_ability_brass == "Advanced"){ echo "checked"; } ?> name="large_ensemble_ability_brass<?php echo $b; ?>" value="Advanced" />Advanced
		</td>
	</tr>
	</table>
 <?php
 $i++;
 $b++;
	}
  ?>
  <input type="hidden" name="brass_wind_percussionins" value="<?php echo $i;?>">
  <?php
 }
?>
<?php } ?>
<?php 
  $complete_data = "
                SELECT *
                FROM ".$wpdb->prefix."questionnaires_self_evaluations
                WHERE user_id = ".$user_id." AND completed = 'yes'
                ";

          $complete_final_data = $wpdb->get_results($complete_data, OBJECT);
          foreach ($complete_final_data as $value) {
            # code...
           $c_data = $value->completed;
          }
          
  ?>
  <?php
  if($c_data = "yes"){
  ?>
  <input type="submit" name="complted" value="Edit this Step">
  <?php }else { ?>
<input type="submit" name="complted" value="complted this Step">
<?php } ?>
</div>
</form>

<?php
get_footer();
?>
<script>
	
	validator_step5 = jQuery( "#step5_form" ).validate({
		  rules: {
		  	<?php $v1 = 0; foreach($vocal as $vocalitems){ ?>
		  	rate_ability<?php echo $v1; ?> :{
		    	required: true
		    },
		    groups_during_year<?php echo $v1; ?>: {
		      required: true
		    },
		    required_audition<?php echo $v1; ?> :{
		    	required: true
		    },
		    learn_peice<?php echo $v1; ?>: {
		      required: true
		    },
		    difficult_peice<?php echo $v1; ?>: {
		      required: true
		    },
		    studied_theory<?php echo $v1; ?>: {
		      required: true
		    },
		    what_year_1<?php echo $v1; ?>: {
		      required: true
		    },
		    voice_class<?php echo $v1; ?> :{
		    	required: true
		    },
		    what_year_2<?php echo $v1; ?>: {
		      required: true
		    },		    
		    voice_lessons<?php echo $v1; ?>: {
		      required: true
		    },
		   
		    what_year_3<?php echo $v1; ?>: {
		      required: true
		    },
		    singing_with_ensamble<?php echo $v1; ?>: {
		      required: true
		    },
		    sightreading_ability<?php echo $v1; ?> :{
		    	required: true
		    },
		    <?php $v1++; } ?>
		    <?php $s1 = 0; foreach($string as $stringitems){ ?>
		    groups_during_year_string<?php echo $s1; ?> :{
		    	required: true
		    },
		    learn_peice_string<?php echo $s1; ?> :{
		    	required: true
		    },
		    practice_alone_reg<?php echo $s1; ?> :{
		    	required: true
		    },
		    study_privately<?php echo $s1; ?> :{
		    	required: true
		    },
		    how_many_years<?php echo $s1; ?> :{
		    	required: true
		    },
		    play_chamber_music<?php echo $s1; ?> :{
		    	required: true
		    },
		    'read_positions<?php echo $s1; ?>[]' :{
		    	required: true,
				minlength: 1
		    },
		    sightreading_ability_string<?php echo $s1; ?> :{
		    	required: true
		    },
		    chamber_music_ability<?php echo $s1; ?> :{
		    	required: true
		    },
		    large_ensemble_ability<?php echo $s1; ?> :{
		    	required: true
		    },
		    like_to_play_jazz_ensamble<?php echo $s1; ?> :{
		    	required: true
		    },
		    played_in_small_jazz_ensamble<?php echo $s1; ?> :{
		    	required: true
		    },
		    played_in_big_band<?php echo $s1; ?> :{
		    	required: true
		    },
		    <?php $s1++; } ?>
		    <?php $p1 = 0; foreach($piano as $pianoitems){ ?>
		    practice_alone_reg_piano<?php echo $p1; ?> :{
		    	required: true
		    },
		    study_privately_piano<?php echo $p1; ?> :{
		    	required: true
		    },
		    how_many_years_piano<?php echo $p1; ?> :{
		    	required: true
		    },
		    like_to_play_jazz_ensamble_piano<?php echo $p1; ?> :{
		    	required: true
		    },
		    played_in_small_jazz_ensamble_piano<?php echo $p1; ?> :{
		    	required: true
		    },
		    played_in_big_band_piano<?php echo $p1; ?> :{
		    	required: true
		    },
		    play_chamber_music_piano<?php echo $p1; ?> :{
		    	required: true
		    },
		    list_composers_piano<?php echo $p1; ?> :{
		    	required: true
		    },
		    sightreading_ability_string_piano<?php echo $p1; ?> :{
		    	required: true
		    },
		    chamber_music_ability_piano<?php echo $p1; ?> :{
		    	required: true
		    },
		    <?php $p1++; } ?>
		    <?php $b1 = 0; foreach($brass_wind_percussion as $brassitems){ ?>
		    	groups_during_year_brass<?php echo $b1; ?> :{
		    	required: true
		    },
		    required_audition_brass<?php echo $b1; ?> :{
		    	required: true
		    },
		    'transpose_to_q<?php echo $b1; ?>[]' :{
		    	required: true,
				minlength: 1
		    },
		    practice_alone_reg_brass<?php echo $b1; ?> :{
		    	required: true
		    },
		    study_privately_brass<?php echo $b1; ?> :{
		    	required: true
		    },
		    how_many_years_brass<?php echo $b1; ?> :{
		    	required: true
		    },
		    play_chamber_music_brass<?php echo $b1; ?> :{
		    	required: true
		    },
		    'instrunment_bring_camp_q<?php echo $b1; ?>[]' :{
		    	required: true,
				minlength: 1
		    },
		    any_other_ins_brass<?php echo $b1; ?> :{
		    	required: true
		    },
		    sightreading_ability_string_brass<?php echo $b1; ?> :{
		    	required: true
		    },
		    chamber_music_ability_brass<?php echo $b1; ?> :{
		    	required: true
		    },
		    large_ensemble_ability_brass<?php echo $b1; ?> :{
		    	required: true
		    },
		    <?php $b1++; } ?>
		  },
		  errorPlacement: function(error,element) {
		    return true;
		  }
		});
	
</script>