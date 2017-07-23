<?php
/*
Template Name: step2
*/
get_header();

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
  if($_POST['complted']){
                
        $current_user = wp_get_current_user();
        $user_id = $current_user->data->ID;

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
              
              $update = $wpdb->query($sql);
              $_SESSION['success'] = "Saved Successfully";
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
            $inster = $wpdb->query($sql);
            if($inster){
              $_SESSION['success'] = "Saved Successfully";
            }else{
              $_SESSION['error'] = "Error";
            }
            // End Insert Query
          }
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
    <li class="active-li"><a href="<?php echo site_url(); ?>/morning-ensemble/">Morning Ensemble</a>
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

  if($instrumental != $instrumentalget && $user_id != ''){
    /*$wpdb->query(
                "DELETE  FROM ".$wpdb->prefix."questionnaires_ensemble
                 WHERE user_id = ".$user_id.""
  );

    $wpdb->query(
                "DELETE  FROM ".$wpdb->prefix."questionnaires
                 WHERE user_id = ".$user_id.""
  );*/

    $instrumentalget = '';
    $ensembleget = '';
    $bandoptionget = '';

  }

  $completecounter = 0;
  if($bandoptionget == '' || $ensembleget == ''){
    $completecounter = 1;
  }
  
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
<style type="text/css">
    .bs-example{
          margin-top: 25px;
          margin-bottom: 25px;
          padding: 15px;
          border: double 1px;
          border-radius: 15px;
    }
    .redio-center{
      text-align: center;
    }
    .label-custom{
      text-align: center;
    }
    .row{
      border: 1px solid #cccccc;
      padding: 10px;
      width: 97%;
      text-align: center;
      margin: 0 auto;
    }
    .col-xs-2 {
      width: 20%;
    }
</style>
</head>
<body>
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
<div class="form-group">
    <?php 
    $args2 = array('post_type'=>'instruments','post_name__in'=>array($instrumental));
    $query2 = new WP_Query( $args2 );
    while ( $query2->have_posts() ) {
		$query2->the_post();
		  $group_morning = get_post_meta(get_the_ID(),'morning_ensemble_choice_group',true);
    } 
    ?>
    <label for="inputEmail">Your Primary Instrument or Voice is <?php echo $group_morning; ?>; This will be used for choice of large ensemble.</label>
</div>
<div class="form-group">
    <label for="inputPassword">MORNING LARGE ENSEMBLE CHOICE</label>
</div>
    <form method="post">
        <input type="hidden" name="instrumental" value="<?php echo $instrumental; ?>">
        <?php $incomplete = 0; ?>
        <?php /* First (Voice-Soprano) */ ?>
        <?php if($instrumental == 'Voice-Soprano' || $instrumental == 'Voice-Alto' || $instrumental == 'Voice-Baritone' || $instrumental == 'Voice-Bass' || $instrumental == 'Voice-Tenor'){ ?>
        <b><h4>Ensemble Choice</h4></b>
        <div class="row">
        <div class="col-sm-12">
        <input type="hidden" name="bandoption" value="none">
            <div class="redio-center">
            <input type="checkbox" name="ensemble" value="Festival-Chorus"
            <?php if($instrumental == $instrumentalget && $ensembleget == 'Festival-Chorus'){ ?> checked="checked"  <?php } else{
              $incomplete = 1;
              } ?>
            >
            </div>
            <div class="label-custom">
            <label for="inputEmail1">Festival Chorus</label>
            </div>
        </div>
        </div><br>
        <?php } ?>
        <?php /* Second (Euphonium) */ ?>
        <?php if($instrumental == 'Euphonium' || $instrumental == 'Saxophone-Alto' || $instrumental == 'Saxophone-Baritone' || $instrumental == 'Saxophone-Soprano' || $instrumental == 'Saxophone-Tenor'){ ?>
        <div class="form-group">
          <label for="inputEmail1">2017 Band Repertoire</label><br>
          <ul class="list-group">
            <li class="list-group-item">Jay Bocook, Kirkpatrick's Muse</li>
            <li class="list-group-item">Gershwin, arr. John Krance Second Prelude</li>
            <li class="list-group-item">James Curnow, Where Never Lark Nor Eagle Flew</li>
          </ul>
        </div>
        <div class="row">
        <div class="col-sm-12">
          <b><h4>Ensemble Choice</h4></b>
          <input type="hidden" name="bandoption" value="none">
            <div class="label-custom">
            <label for="inputEmail1">Symphonic Band</label>
            </div>
            <div class="redio-center">
           <input type="checkbox" name="ensemble" value="Symphonic-Band"
            <?php if($instrumental == $instrumentalget && $ensembleget == 'Symphonic-Band'){ ?> checked="checked"  <?php } ?>>
            </div>
        </div>
        </div><br>
        <?php } ?>
        <?php /* Third (Saxophone) */ ?>
        <?php if($instrumental == 'Saxophone'){ ?>
        <div class="form-group">
          <div class="form-group">
          <label for="inputEmail1">2017 Band Repertoire</label><br>
          <ul class="list-group">
            <li class="list-group-item">Jay Bocook, Kirkpatrick's Muse</li>
            <li class="list-group-item">Gershwin, arr. John Krance Second Prelude</li>
            <li class="list-group-item">James Curnow, Where Never Lark Nor Eagle Flew</li>
          </ul>
        </div>
        </div>
        <b><h4>Ensemble Choice</h4></b>
        <div class="row">
        <div class="col-sm-12">
          <input type="hidden" name="bandoption" value="none">
            <div class="redio-center">
            <input type="checkbox" name="ensemble" value="Symphonic-Band"
            <?php if($instrumental == $instrumentalget && $ensembleget == 'Symphonic-Band'){ ?> checked="checked"  <?php } ?>
            >
            </div>
            <div class="label-custom">
            <label for="inputEmail1">Symphonic Band</label>
            </div>

        </div>
        </div><br>

        <b><h4>Ensemble Choice</h4></b>
        <div class="row">
        <div class="col-xs-2">
          <div class="redio-center">
          <input type="radio" name="bandoption" value="No-Preference"
          <?php if($instrumental == $instrumentalget && $bandoptionget == 'No-Preference'){ ?> checked="checked"  <?php } ?>
          >
          </div>
          <div class="label-custom">
          <label>No Preference</label>
          </div>
        </div>
        <div class="col-xs-2 col-half-offset">
          <div class="redio-center">
          <input type="radio" name="bandoption" value="Soprano"
          <?php if($instrumental == $instrumentalget && $bandoptionget == 'Soprano'){ ?> checked="checked"  <?php } ?>
          >
          </div>
          <div class="label-custom">
          <label>Soprano</label>
          </div>
        </div>
        <div class="col-xs-2 col-half-offset">
          <div class="redio-center">
          <input type="radio" name="bandoption" value="Alto"
          <?php if($instrumental == $instrumentalget && $bandoptionget == 'Alto'){ ?> checked="checked"  <?php } ?>
          >
          </div>
          <div class="label-custom">
          <label>Alto</label>
          </div>
        </div>
        <div class="col-xs-2 col-half-offset">
          <div class="redio-center">
          <input type="radio" name="bandoption" value="Tenor"
          <?php if($instrumental == $instrumentalget && $bandoptionget == 'Tenor'){ ?> checked="checked"  <?php } ?>
          >
          </div>
          <div class="label-custom">
          <label>Tenor</label>
          </div>
        </div>
        <div class="col-xs-2 col-half-offset">
          <div class="redio-center">
          <input type="radio" name="bandoption" value="Bariton"
          <?php if($instrumental == $instrumentalget && $bandoptionget == 'Bariton'){ ?> checked="checked"  <?php } ?>
          >
          </div>
          <div class="label-custom">
          <label>Bariton</label>
          </div>
        </div>
        </div><br>

        <?php } ?>
        <?php /* Forth (Oboe) */ ?>
        <?php if($instrumental == 'Oboe' || $instrumental == 'Trumpet' || $instrumental == 'Horn' || $instrumental == 'Trombone' || $instrumental == 'Tuba' || $instrumental == 'Percussion' || $instrumental == 'Timpani' || $instrumental == 'Bassoon' || $instrumental == 'Clarinet-Bass'){ ?>
        <div class="form-group">
        <label>2017 Band Repertoire</label>
        <ul class="list-group">
          <li class="list-group-item">Eric Coates, Everyday Suite, Selected Movements</li>
          <li class="list-group-item">Gershwin, arr. John Krance Second Prelude</li>
          <li class="list-group-item">James Curnow, Where Never Lark Nor Eagle Flew</li>
        </ul>
        </div>
        <div class="form-group">
          <label>2017 Festival Orchestra Repertoire</label>
        <ul class="list-group">
          <li class="list-group-item">Eric Coates, Everyday Suite, Selected Movements</li>
          <li class="list-group-item">Grainger Molly on the shore, shephard's Hey</li>
          <li class="list-group-item">Saint-Saens Suite Algerienne op. 60, 4th Movement</li>
        </ul>
        </div>
        <input type="hidden" name="ensemble" value="none">
        <b><h4>Ensemble Choice</h4></b>
        <div class="row">
        <div class="col-sm-4">
          <div class="redio-center">
          <input type="radio" name="bandoption" value="Either-Symphonic-Band-or-Festival-Orchestra"
         <?php if($instrumental == $instrumentalget && $bandoptionget == 'Either-Symphonic-Band-or-Festival-Orchestra'){ ?> checked="checked"  <?php } ?>>
          </div>
          <div class="label-custom">
          <label>Either Symphonic Band or Festival Orchestra</label>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="redio-center">
          <input type="radio" name="bandoption" value="Prefer-Symphonic-Band"
          <?php if($instrumental == $instrumentalget && $bandoptionget == 'Prefer-Symphonic-Band'){ ?> checked="checked"  <?php } ?>
          >
          </div>
          <div class="label-custom">
          <label>Prefer Symphonic Band</label>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="redio-center">
          <input type="radio" name="bandoption" value="Prefer-Festival-Orchestra"
          <?php if($instrumental == $instrumentalget && $bandoptionget == 'Prefer-Festival-Orchestra'){ ?> checked="checked"  <?php } ?>
          >
          </div>
          <div class="label-custom">
          <label>Prefer Festival Orchestra</label>
          </div>
        </div>
        </div><br>
        <?php }  ?>
        <?php /* Fifth (Flute) */ ?>
        <?php if($instrumental == 'Flute'){ ?>
       
        <div class="form-group">
          <label>2017 Band Repertoire</label>
          <ul class="list-group">
          <li class="list-group-item">Eric Coates, Everyday Suite, Selected Movements</li>
          <li class="list-group-item">Gershwin, arr. John Krance Second Prelude</li>
          <li class="list-group-item">James Curnow, Where Never Lark Nor Eagle Flew</li>
          </ul>
        </div>
        <div class="form-group">
          <label>2017 Festival Orchestra Repertoire</label>
          <ul class="list-group">
            <li class="list-group-item">Eric Coates, Everyday Suite, Selected Movements</li>
            <li class="list-group-item">Grainger Molly on the shore, shephard's Hey</li>
            <li class="list-group-item">Saint-Saens Suite Algerienne op. 60, 4th Movement</li>
          </ul>
        </div><br>
      <b><h4>Ensemble Choice</h4></b>
      <div class="row">
      <div class="col-sm-4">
        <div class="redio-center">
        <input type="radio" name="ensemble" value="Either-Symphonic-Band-or-Festival-Orchestra"
        <?php if($instrumental == $instrumentalget && $ensembleget == 'Either-Symphonic-Band-or-Festival-Orchestra'){ ?> checked="checked"  <?php } ?>
        >
        </div>
        <div class="label-custom">
         <label>Either Symphonic Band or Festival Orchestra</label>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="redio-center">
        <input type="radio" name="ensemble" value="Prefer-Symphonic-Band"
        <?php if($instrumental == $instrumentalget && $ensembleget == 'Prefer-Symphonic-Band'){ ?> checked="checked"  <?php } ?>
        >
        </div>
        <div class="label-custom">
        <label>Prefer Symphonic Band</label>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="redio-center">
        <input type="radio" name="ensemble" value="Prefer-Festival-Orchestra"
        <?php if($instrumental == $instrumentalget && $ensembleget == 'Prefer-Festival-Orchestra'){ ?> checked="checked"  <?php } ?>
        >
        </div>
        <div class="label-custom">
        <label>Prefer Festival Orchestra</label>
        </div>
      </div>
      </div><br>
      <b><h4>Please indicate your seating preference for Symphonic Band</h4></label></b>
      <div class="row">
      <div class="col-sm-3">
        <div class="redio-center">
        <input type="radio" name="bandoption" value="No-Preference"
        <?php if($instrumental == $instrumentalget && $bandoptionget == 'No-Preference'){ ?> checked="checked"  <?php } ?>
        >
        </div>
        <div class="label-custom">
        <label>No Preference</label>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="redio-center">
        <input type="radio" name="bandoption" value="First-Flute"
        <?php if($instrumental == $instrumentalget && $bandoptionget == 'First-Flute'){ ?> checked="checked"  <?php } ?>
        >
        </div>
        <div class="label-custom">
        <label>First Flute</label>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="redio-center">
        <input type="radio" name="bandoption" value="Second-Flute"
        <?php if($instrumental == $instrumentalget && $bandoptionget == 'Second-Flute'){ ?> checked="checked"  <?php } ?>
        >
        </div>
        <div class="label-custom">
        <label>Second Flute</label>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="redio-center">
        <input type="radio" name="bandoption" value="Piccolo"
        <?php if($instrumental == $instrumentalget && $bandoptionget == 'Piccolo'){ ?> checked="checked"  <?php } ?>
        >
        </div>
        <div class="label-custom">
        <label>Piccolo</label>
        </div>
      </div>
      </div>
      <br>
        <?php } ?>
        <?php /* Sixth (Clarinet) */ ?>
      <?php if($instrumental == 'Clarinet'){ ?>
        <div class="form-group">
          <label>2017 Band Repertoire</label>
          <ul class="list-group">
          <li class="list-group-item">Eric Coates, Everyday Suite, Selected Movements</li>
          <li class="list-group-item">Gershwin, arr. John Krance Second Prelude</li>
          <li class="list-group-item">James Curnow, Where Never Lark Nor Eagle Flew</li>
          </ul>
        </div>
        <div class="form-group">
          <label>2017 Festival Orchestra Repertoire</label>
          <ul class="list-group">
            <li class="list-group-item">Eric Coates, Everyday Suite, Selected Movements</li>
            <li class="list-group-item">Grainger Molly on the shore, shephard's Hey</li>
            <li class="list-group-item">Saint-Saens Suite Algerienne op. 60, 4th Movement</li>
          </ul>
        </div><br>
      <b><h4>Ensemble Choice</h4></b>
       <div class="row">
      <div class="col-sm-4">
        <div class="redio-center">
        <input type="radio" name="ensemble" value="Either-Symphonic-Band-or-Festival-Orchestra"
        <?php if($instrumental == $instrumentalget && $ensembleget == 'Either-Symphonic-Band-or-Festival-Orchestra'){ ?> checked="checked"  <?php } ?>
        >
        </div>
        <div class="label-custom">
         <label>Either Symphonic Band or Festival Orchestra</label>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="redio-center">
        <input type="radio" name="ensemble" value="Prefer-Symphonic-Band"
        <?php if($instrumental == $instrumentalget && $ensembleget == 'Prefer-Symphonic-Band'){ ?> checked="checked"  <?php } ?>
        >
        </div>
        <div class="label-custom">
        <label>Prefer Symphonic Band</label>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="redio-center">
        <input type="radio" name="ensemble" value="Prefer-Festival-Orchestra"
        <?php if($instrumental == $instrumentalget && $ensembleget == 'Prefer-Festival-Orchestra'){ ?> checked="checked"  <?php } ?>
        >
        </div>
        <div class="label-custom">
        <label>Prefer Festival Orchestra</label>
        </div>
        </div>
        </div>
        <b><h4>Please indicate your seating preference for symphonic Band</h4></b>
       <div class="row">
      <div class="col-sm-3">
        <div class="redio-center">
        <input type="radio" name="bandoption" value="No-Preference"
            <?php if($instrumental == $instrumentalget && $bandoptionget == 'No-Preference'){ ?> checked="checked"  <?php } ?>
            >
        </div>
        <div class="label-custom">
         <label>No Preference</label>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="redio-center">
        <input type="radio" name="bandoption" value="First"
            <?php if($instrumental == $instrumentalget && $bandoptionget == 'First'){ ?> checked="checked"  <?php } ?>
            >
        </div>
        <div class="label-custom">
         <label>First</label>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="redio-center">
        <input type="radio" name="bandoption" value="Second"
            <?php if($instrumental == $instrumentalget && $bandoptionget == 'Second'){ ?> checked="checked"  <?php } ?>
            >
        </div>
        <div class="label-custom">
         <label>Second</label>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="redio-center">
        <input type="radio" name="bandoption" value="Third"
            <?php if($instrumental == $instrumentalget && $bandoptionget == 'Third'){ ?> checked="checked"  <?php } ?>
            >
        </div>
        <div class="label-custom">
         <label>Third</label>
        </div>
      </div>
      </div><br>
      <?php } ?>
      
      <?php /* Seventh (Viola) */ ?>
      <?php if($instrumental == 'Viola' || $instrumental == 'Cello' || $instrumental == 'Double Bass' || $instrumental == 'Harp'){ ?>
      <div class="form-group">
          <label>2017 String Orchestra Repertoire</label>
          <ul class="list-group">
          <li class="list-group-item">Bartok, Romanian Dances</li>
          <li class="list-group-item">Grieg, Elegaic Melodies</li>
          </ul>
        </div>
        <div class="form-group">
          <label>2017 Festival Orchestra Repertoire</label>
          <ul class="list-group">
            <li class="list-group-item">Eric Coates, Everyday Suite, Selected Movements</li>
            <li class="list-group-item">Grainger Molly on the shore, shephard's Hey</li>
            <li class="list-group-item">Saint-Saens Suite Algerienne op. 60, 4th Movement</li>
          </ul>
        </div><br>
      <b><h4>Ensemble Choice</h4></b>
     <input type="hidden" name="bandoption" value="none">
       <div class="row">
      <div class="col-sm-4">
        <div class="redio-center">
        <input type="radio" name="ensemble" value="Either-Symphonic-Band-or-Festival-Orchestra"
        <?php if($instrumental == $instrumentalget && $ensembleget == 'Either-Symphonic-Band-or-Festival-Orchestra'){ ?> checked="checked"  <?php } ?>
        >
        </div>
        <div class="label-custom">
         <label>Either Symphonic Band or Festival Orchestra</label>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="redio-center">
        <input type="radio" name="ensemble" value="Prefer-Symphonic-Band"
        <?php if($instrumental == $instrumentalget && $ensembleget == 'Prefer-Symphonic-Band'){ ?> checked="checked"  <?php } ?>
        >
        </div>
        <div class="label-custom">
        <label>Prefer Symphonic Band</label>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="redio-center">
        <input type="radio" name="ensemble" value="Prefer-Festival-Orchestra"
        <?php if($instrumental == $instrumentalget && $ensembleget == 'Prefer-Festival-Orchestra'){ ?> checked="checked"  <?php } ?>
        >
        </div>
        <div class="label-custom">
        <label>Prefer Festival Orchestra</label>
        </div>
        </div>
        </div><br>
      <?php } ?>

      <?php /* Eight (Violin) */ ?>
<?php if($instrumental == 'Violin'){ ?>
<div class="form-group">
          <label>2017 String Orchestra Repertoire</label>
          <ul class="list-group">
          <li class="list-group-item">Bartok, Romanian Dances</li>
          <li class="list-group-item">Grieg, Elegaic Melodies</li>
          </ul>
        </div>
        <div class="form-group">
          <label>2017 Festival Orchestra Repertoire</label>
          <ul class="list-group">
            <li class="list-group-item">Eric Coates, Everyday Suite, Selected Movements</li>
            <li class="list-group-item">Grainger Molly on the shore, shephard's Hey</li>
            <li class="list-group-item">Saint-Saens Suite Algerienne op. 60, 4th Movement</li>
          </ul>
        </div><br>
      <b><h4>Ensemble Choice</h4></b>
       <div class="row">
      <div class="col-sm-4">
        <div class="redio-center">
        <input type="radio" name="ensemble" value="Either-Symphonic-Band-or-Festival-Orchestra"
        <?php if($instrumental == $instrumentalget && $ensembleget == 'Either-Symphonic-Band-or-Festival-Orchestra'){ ?> checked="checked"  <?php } ?>
        >
        </div>
        <div class="label-custom">
         <label>Either Symphonic Band or Festival Orchestra</label>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="redio-center">
        <input type="radio" name="ensemble" value="Prefer-Symphonic-Band"
        <?php if($instrumental == $instrumentalget && $ensembleget == 'Prefer-Symphonic-Band'){ ?> checked="checked"  <?php } ?>
        >
        </div>
        <div class="label-custom">
        <label>Prefer Symphonic Band</label>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="redio-center">
        <input type="radio" name="ensemble" value="Prefer-Festival-Orchestra"
        <?php if($instrumental == $instrumentalget && $ensembleget == 'Prefer-Festival-Orchestra'){ ?> checked="checked"  <?php } ?>
        >
        </div>
        <div class="label-custom">
        <label>Prefer Festival Orchestra</label>
        </div>
        </div>
        </div><br>


        <b><h4>What part would you prefer to play in the orchestra?</h4></b>
       <div class="row">
      <div class="col-sm-4">
        <div class="redio-center">
        <input type="radio" name="bandoption" value="No-Preference"
      <?php if($instrumental == $instrumentalget && $bandoptionget == 'No-Preference'){ ?> checked="checked"  <?php } ?>
      >
        </div>
        <div class="label-custom">
         <label>No Preference</label>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="redio-center">
        <input type="radio" name="bandoption" value="First"
        <?php if($instrumental == $instrumentalget && $bandoptionget == 'First'){ ?> checked="checked"  <?php } ?>
        >
        </div>
        <div class="label-custom">
        <label>First</label>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="redio-center">
        <input type="radio" name="bandoption" value="Second"
        <?php if($instrumental == $instrumentalget && $bandoptionget == 'Second'){ ?> checked="checked"  <?php } ?>
        >
        </div>
        <div class="label-custom">
        <label>Second</label>
        </div>
        </div>
        </div><br>
        <?php } ?>
        <?php /* Nine (Piano) */ ?>
<?php if($instrumental == 'Piano'){ ?>
<table>
<input type="hidden" name="bandoption" value="none">
  <b><h4>Ensemble Choice</h4></b>
       <div class="row">
      <div class="col-sm-4">
        <div class="redio-center">
        <input type="radio" name="ensemble" value="I-understand-that-i-will-not-be-assigned-a-morning-large-ensemble"
      <?php if($instrumental == $instrumentalget && $ensembleget == 'I-understand-that-i-will-not-be-assigned-a-morning-large-ensemble'){ ?> checked="checked"  <?php } ?>
      >
        </div>
        <div class="label-custom">
         <label>I understand that i will not be assigned a morning large ensemble</label>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="redio-center">
        <input type="radio" name="ensemble" value="I-would-like-to-sing-in-the-festival-chorus"
      <?php if($instrumental == $instrumentalget && $ensembleget == 'I-would-like-to-sing-in-the-festival-chorus'){ ?> checked="checked"  <?php } ?>
      >
        </div>
        <div class="label-custom">
        <label>I would like to sing in the festival chorus</label>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="redio-center">
        <input type="radio" name="ensemble" value="I-would-like-to-talk-to-a-faculty-member-about-playing-percussion-in-symphonic-Band"
      <?php if($instrumental == $instrumentalget && $ensembleget == 'I-would-like-to-talk-to-a-faculty-member-about-playing-percussion-in-symphonic-Band'){ ?> checked="checked"  <?php } ?>
      >
        </div>
        <div class="label-custom">
        <label>I would like to talk to a faculty member about playing percussion in symphonic Band </label>
        </div>
        </div>
        </div><br>
<?php } ?>
      
        <?php if($completecounter != 1){
        ?><button type="submit" name="complted" class="btn btn-primary" value="Complete this Step">Edit this step</button><?php
        } 
        else{
          ?> <button type="submit" name="complted" class="btn btn-primary" value="Submit">Complete this Step</button>`<?php
          }?>

       
        <!-- <button  type="submit" name="completed" class="btn btn-primary">Login</button> -->
    </form>
</div>
<?php
get_footer();
?>