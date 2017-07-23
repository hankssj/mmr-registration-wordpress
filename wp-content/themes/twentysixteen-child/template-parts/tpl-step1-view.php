<?php
/*
Template Name: step-1 view
*/
get_header();
$user_ids = $_REQUEST['id'];
global $wpdb;
$current_user = wp_get_current_user();
if($current_user->roles[0] != 'administrator'){
?>
<script>
jQuery(document).ready(function(){
window.location.href = '<?php  echo site_url(); ?>';
});
<?php }
	if($current_user->roles[0] == 'administrator'){
?>
</script>
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
    <form method="post">
        <div class="form-group">
            <label for="title">
                MIDSUMMER MUSICAL RETRENT ENSEMBLE AND ELECTIVE CHOICES
           </label>
        </div>
        <div class="form-group">
            <label for="inputPassword">
               Welcome to MMR 2017. This lead form will lead you through requesting the Elective and Ensemble program you prefer. You will also complete the self-eveluations needed by the faculty to plan the best possible program for you. if programs arise please contact us at (800) 471-2491 or midsummer@musicalretret.org. You will be ask to review and confirm your choices at the end. If you are attending with a pre-arranged chamber or vocal group. please have the name and email of a designated contact person available, as you need it to request time for your group.
            </label>
        </div>
        <div class="checkbox">
            <label><input type="checkbox" name="completed"
        <?php
        $querystr = "
        SELECT * 
        FROM ".$wpdb->prefix."questionnaires
        WHERE userID = ".$user_ids."
        ";

		  $check_user = $wpdb->get_results($querystr, OBJECT);
		
		  $completeduser = '';
		
		  foreach ($check_user as $formdata) {
		    $completeduser = $formdata->completed;
		    $stp1_completed = $formdata->stp1_completed;
		  }
        if($completeduser == 'on'){
          ?> checked="checked" <?php
          } ?>
        > I Accept Terms And Condition</label>
        </div>
    </form>
</div>

</form>
<?php
}
get_footer(); ?>