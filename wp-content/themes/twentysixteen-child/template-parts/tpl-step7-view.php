<?php
/*
Template Name: step-7 view
*/
get_header();
$user_id = $_REQUEST['id'];
global $wpdb;
$current_user = wp_get_current_user();
if($current_user->roles[0] != 'administrator'){
?>
<script>
jQuery(document).ready(function(){
window.location.href = '<?php  echo site_url(); ?>';
});
</script>
<?php }
	if($current_user->roles[0] == 'administrator'){
?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">


<style type="text/css">
    .bs-example{
          margin: 26px;
          padding: 15px;
          border: double 1px;
          border-radius: 15px;
    }
</style>
</head>
<body>
<div class="bs-example">
    <div class="form-group">
      <b><label for="title">Review And Commit</label></b>
    </div>
    <div class="form-group">
      <p>Thank You; Your ensemble and elective preferences and self-evaluations are complete</p>
    </div>
    <div class="form-group">
      <ul>
      <li>Contact Information</li>
      <?php
             $querystr = "
              SELECT * 
              FROM ".$wpdb->prefix."users
              WHERE ID = ".$user_id."
              ";
  
              $check_user = $wpdb->get_results($querystr, OBJECT);
              foreach ($check_user as $formdata) {
                $name = $formdata->display_name;
                $email = $formdata->user_email;
              }
             ?>
              <ul>
               <li> Name  : <?php echo $name;?></li>
               <li> Email : <?php echo $email;?></li>
              </ul>
      </ul>
    </div>
    <div class="form-group">
      <ul>
        <li>Morning Ensemble</li>
        <ul>
          <li>
            <?php
             $querystr1 = "
              SELECT * 
              FROM ".$wpdb->prefix."questionnaires_ensemble
              WHERE user_id = ".$user_id."
              ";

              $check_user1 = $wpdb->get_results($querystr1, OBJECT);
              foreach ($check_user1 as $formdata1) {
                $bandoption = $formdata1->bandoption;
              }
            ?>
            Your morning ensemble preference is <?php echo $bandoption ?>
          </li>
        </ul>
      </ul>
    </div>
    <div class="form-group">
      <ul>
        <li>Afternoon Groups</li>
        <ul>
          <?php
             $querystr2 = "
              SELECT * 
              FROM ".$wpdb->prefix."questionnaires_afternoon
              WHERE userID = ".$user_id."
              ";

              $check_user2 = $wpdb->get_results($querystr2, OBJECT);
              
              foreach ($check_user2 as $formdata2) {
                $chamber_assemble = $formdata2->chamber_assemble;
                $instrument1 = $formdata2->instrument1;
                $groupname1 = $formdata2->groupname1;
              }
            ?>
           <?php
              if($chamber_assemble == ""){
                $chamber_assemble = "0";
              } 
              ?>
              <li>Your <?php echo $chamber_assemble; ?> afternoon chamber group preference <?php echo $instrument1; ?> </li>
              <ul>
                <li>
                  <?php
                  if($groupname1 == ""){
                    $groupname1 = "No";
                  }else{
                    $groupname1 = $groupname1;
                  }
                  ?>
                  <?php echo $groupname1; ?> afternoon chamber group
                </li>
              </ul>
        </ul>
      </ul>
    </div>
    <div class="form-group">
      <ul>
        <li>
          You Selected the following electives
        </li>
        <ul>
          <?php
                 $querystr3 = "
                  SELECT * 
                  FROM ".$wpdb->prefix."questionnaires_electives
                  WHERE user_id = ".$user_id."
                  ";
  
                  $check_user3 = $wpdb->get_results($querystr3, OBJECT);
                  
                  foreach ($check_user3 as $formdata3) {
                    $choice = $formdata3->choice;
                    ?>
                    <li><?php echo $choice; ?></li>
                    <?php
                    
                  }
                ?>
        </ul>
      </ul>
    </div>
    <div class="form-group">
      <ul>
        <li>You Completed evaluations for the following instruments/voices</li>
        <ul>
          <?php
                 $querystr4 = "
                  SELECT * 
                  FROM ".$wpdb->prefix."questionnaires_self_evaluations
                  WHERE user_id = ".$user_id."
                  ";
  
                  $check_user4 = $wpdb->get_results($querystr4, OBJECT);
                  
                  foreach ($check_user4 as $formdata4) {
                    if($formdata4->instrument == ""){
                        continue;
                    }
                    $choice = $formdata4->instrument;
                    ?>
                    <li><?php echo $choice; ?></li>
                    <?php
                    
                  }
                ?>
        </ul>
      </ul>
    </div>
    <?php
                 $querystr4 = "
                  SELECT * 
                  FROM ".$wpdb->prefix."questionnaires_review_and_commit
                  WHERE user_id = ".$user_id."
                  ";
  
                  $check_user4 = $wpdb->get_results($querystr4, OBJECT);
                  
                  foreach ($check_user4 as $formdata4) {
                    $data = $formdata4->any_questions;
                  }
                ?>
    <div class="form-group">
      <label>Any questions or comments to help us with your selections? </label><br>->
      <label for=""><?php echo $data; ?></label>
    </div>
    
</div>
<?php
}
get_footer(); ?>