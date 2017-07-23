<?php
/*
Template Name: step-4 view
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
        <label for="">Elective preference</label>
    </div>
    <div class="form-group">
        <label for="">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</label>
    </div>
    <div class="form-group">
        <label for="">Elective Choices (Highest to lowest priority)-></label>
    </div>
    <?php 
			
     $querystr4 = "
      SELECT * 
      FROM ".$wpdb->prefix."questionnaires_electives
      WHERE user_id = ".$user_ids."
      ";

      $check_user4 = $wpdb->get_results($querystr4, OBJECT);
      foreach ($check_user4 as  $value) {
            ?>
            <div class="form-group">
                <label for=""><?php echo $value->choice; ?></label>
                <?php
                if(!empty($value->options)){
                    echo ": ( Instrument name :";
                    ?>
                    <label for=""><?php echo $value->options ?></label>
                    <?php
                    echo")";
                }
                ?>
                
            </div>
            <?php
        }
    
    ?>
<?php
}
get_footer(); ?>