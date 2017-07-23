<?php
/*
Template Name: step6
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
        window.location.href = "<?php echo home_url(); ?>";
  </script>
  <?php
}
  if($_POST['submit']){

        $current_user = wp_get_current_user();
        $user_id = $current_user->data->ID;
        
            // Insert Query
            $sql = "INSERT INTO ".$wpdb->prefix."questionnaires_read_accept_terms
                    (`user_id`,`completed`) 
                    values (".$user_id.", '".$_REQUEST['completed']."')";
            $wpdb->query($sql);
            $_SESSION['success'] ="Saved successfully";
            // End Insert Query
        }
          $querystr = "
        SELECT * 
        FROM ".$wpdb->prefix."questionnaires_read_accept_terms
        WHERE user_id = ".$user_id."
        ";

        $check_user = $wpdb->get_results($querystr, OBJECT);
        foreach ($check_user as $formdata) {
          $completeduser = $formdata->completed;
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
    <li class="active-li"><a href="<?php echo site_url(); ?>/read-and-accept-terms/">Read And Accept Terms</a>
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
</style>
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
<form method="post">
    <div class="form-group">
      <b><lable>Read And Accept Terms</lable></b>
    </div>
    <div class="form-group">
      <label>
        <?php
          //$description = get_post_meta( get_the_ID(), 'description', true );
          // Check if the custom field has a value.
          //if ( !empty( get_the_content() ) ) {
          while ( have_posts() ) : the_post();
          echo get_the_content();
          endwhile;
          //}
        ?>
      </label>
    </div>
    <div class="form-group">
      <div class="redio-center">
      <input type="checkbox" value="yes" name="completed"
        <?php
        if($completeduser == 'yes'){
          ?> checked="checked" <?php
          } ?>
        >
        </div>
        <div class="label-custom">
          <label>I have read and acknowlege the Waiver and Release</label>
        </div>
    </div>
    <?php
    if($completeduser == "yes"){
      ?>
      <td><button type="submit" class="btn btn-primary" name="submit" value="Complete this step" disabled>COMPLETE THIS STEP</button></td>
      <?php
    }else{
    ?>
      <td><button type="submit" class="btn btn-primary" name="submit" value="Submit">EDIT THIS STEP</button></td>
    <?php } ?>
    </tr>
</table>
</form>
</div>
<?php
get_footer();
?>