<?php
/*
Template Name: View steps Form
*/
get_header();

global $wpdb;
$current_user = wp_get_current_user();
if($current_user->roles[0] != 'administrator'){
?>
<script>
jQuery(document).ready(function(){
window.location.href = '<?php  echo site_url(); ?>';
});

</script>
<?php

}


$pagetitle = get_post_meta( get_the_ID(), 'page_title', true );
// Check if the custom field has a value.
if ( ! empty( $pagetitle ) ) {
    echo "<h2><center>".$pagetitle."<center></h2>";
}


if($current_user->roles[0] == 'administrator'){
session_start();
echo "<h2>";
echo "<div style='color: green;'>";
echo $_SESSION['succmsg'];
echo "</div>";
echo "</h2>";
unset($_SESSION['succmsg']);
// Get all form data and user data
$user = wp_get_current_user();

$querystr = "
			SELECT DISTINCT *
			FROM ".$wpdb->prefix."rg_lead		
			WHERE form_id = 1 
			AND created_by != ".$user->ID."
			GROUP BY created_by DESC
			";

$check_user = $wpdb->get_results($querystr, OBJECT);
?>
	
	<input type="hidden" name="sortsteps" value="<?php echo $sortsteps; ?>">
	<input type="hidden" name="sortstatus" value="<?php echo $sortstatus; ?>">
	<table class="table-view" id="example-enrollment">
		<thead>
			<tr>
			<th >Steps</th>
			<th>Title</th>
			<th>Status</th>
			<th></th>
			</tr>
		</thead>
	<tbody>
   	<?php
			
		$user_ids = $_REQUEST['id'];
             $querystr1 = "
              SELECT * 
              FROM ".$wpdb->prefix."questionnaires
              WHERE userID = ".$user_ids."
              ";

              $check_user1 = $wpdb->get_results($querystr1, OBJECT);
              foreach ($check_user1 as $formdata1) {
                $stp1_completed = $formdata1->stp1_completed;
              }
              if($stp1_completed == "1"){
              	$status = "finished";
              }else{
              	$status = "unfinished";
              }
	?>
      	<tr>
      		<td class="">Step-1</td>
      		<td class="">Instructions</td>
      		<td class=""><?php echo $status; ?></td>
      		<td class=""></td>
			
      	</tr>
      	<?php
			
             $querystr2 = "
              SELECT * 
              FROM ".$wpdb->prefix."questionnaires_ensemble
              WHERE user_id = ".$user_ids."
              ";

              $check_user2 = $wpdb->get_results($querystr2, OBJECT);
              foreach ($check_user2 as $formdata2) {
                $stp2_completed = $formdata2->step2_completed;
              }
              if($stp2_completed == "1"){
              	$status = "finished";
              }else{
              	$status = "unfinished";
              }
	?>
      	<tr>
      		<td class="">Step-2</td>
      		<td class="">Morning Ensemble</td>
      		<td class=""><?php echo $status; ?></td>
      		<td class=""><a href="<?php echo site_url(); ?>/step-2/?id=<?php echo $user_ids; ?>">View</a></td>
			
      	</tr>
      	<?php
			
             $querystr3 = "
              SELECT * 
              FROM ".$wpdb->prefix."questionnaires_afternoon
              WHERE userID = ".$user_ids."
              ";

              $check_user3 = $wpdb->get_results($querystr3, OBJECT);
              foreach ($check_user3 as $formdata3) {
                $stp3_completed = $formdata3->stp3_completed;
              }
              if($stp3_completed == "yes"){
              	$status = "finished";
              }else{
              	$status = "unfinished";
              }
	?>
      	<tr>
      		<td class="">Step-3</td>
      		<td class="">Afternoon Ensembles</td>
      		<td class=""><?php echo $status; ?></td>
      		<td class=""><a href="<?php echo site_url(); ?>/step-3/?id=<?php echo $user_ids; ?>">View</a></td>
			
      	</tr>
      	<?php
			
             $querystr4 = "
              SELECT * 
              FROM ".$wpdb->prefix."questionnaires_electives
              WHERE user_id = ".$user_ids."
              ";

              $check_user4 = $wpdb->get_results($querystr4, OBJECT);
              
              if(!empty($check_user4)){
              	$status = "finished";
              }else{
              	$status = "unfinished";
              }
	?>
      	<tr>
      		<td class="">Step-4</td>
      		<td class="">Electives</td>
      		<td class=""><?php echo $status; ?></td>
      		<td class=""><a href="<?php echo site_url(); ?>/step-4/?id=<?php echo $user_ids; ?>">View</a></td>
			
      	</tr>
      		<?php
			
              $querystr = "
		        SELECT * 
		        FROM ".$wpdb->prefix."questionnaires_self_evaluations
		
		        WHERE user_id = ".$user_ids." AND completed='yes'
		        ";
		
		        $questionnaires_self_evaluations = $wpdb->get_results($querystr, OBJECT);
        	
              if(!empty($questionnaires_self_evaluations)){
              	$status = "finished";
              }else{
              	$status = "unfinished";
              }
	?>
      	<tr>
      		<td class="">Step-5</td>
      		<td class="">Self-evaluations</td>
      		<td class=""><?php echo $status; ?></td>
      		<td class=""><a href="<?php echo site_url(); ?>/step-5/?id=<?php echo $user_ids; ?>">View</a></td>
			
      	</tr>
      	
      	<?php
			
              $querystr6 = "
		        SELECT * 
		        FROM ".$wpdb->prefix."questionnaires_read_accept_terms
		        WHERE user_id = ".$user_ids."
		        ";
		
		        $check_user6 = $wpdb->get_results($querystr6, OBJECT);
        	
              foreach ($check_user6 as $formdata6) {
                $completed = $formdata6->completed;
              }
              if($completed == "yes"){
              	$status = "finished";
              }else{
              	$status = "unfinished";
              }
	?>
      	<tr>
      		<td class="">Step-6</td>
      		<td class="">Read And Accept Terms</td>
      		<td class=""><?php echo $status; ?></td>
      		<td class=""></td>
			
      	</tr>
      	<?php
			
              $querystr7 = "
		        SELECT * 
		        FROM ".$wpdb->prefix."questionnaires_review_and_commit
		        WHERE user_id = ".$user_ids."
		        ";
		
		        $check_user7 = $wpdb->get_results($querystr7, OBJECT);
        	
              foreach ($check_user7 as $formdata7) {
                $completed = $formdata7->completed;
              }
              if($completed == "yes"){
              	$status = "finished";
              }else{
              	$status = "unfinished";
              }
	?>
      	<tr>
      		<td class="">Step-7</td>
      		<td class="">Review And Commit</td>
      		<td class=""><?php echo $status; ?></td>
      		<td class=""><a href="<?php echo site_url(); ?>/step-7/?id=<?php echo $user_ids; ?>">View</a></td>
			
      	</tr>
 
	</tbody>
   	<tfoot>
		<tr>
			<th>Steps</th>
			<th>Title</th>
			<th>Status</th>
			<th></th>
			</tr>
   </tfoot>
</table>
<?php } 
get_footer(); ?>