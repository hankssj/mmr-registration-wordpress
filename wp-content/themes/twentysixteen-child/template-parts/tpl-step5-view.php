<?php
/*
Template Name: step-5 view
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
          margin: 26px 0;
          padding: 15px;
          border: double 1px;
          border-radius: 15px;
          display: inline-block;
          width: 100%;
    }
</style>
</head>
<body>
<div class="bs-example">
    <div class="form-group">
        <label for="">Submitted Evalution Forms </label>
    </div>
    <div class="form-group">
    <?php 
			
     $querystr4 = "
      SELECT * 
      FROM ".$wpdb->prefix."questionnaires_self_evaluations
      WHERE user_id = ".$user_ids."
      ";

      $check_user4 = $wpdb->get_results($querystr4, OBJECT);
      /*echo"<pre>";
      print_r($check_user4);exit;*/
      foreach ($check_user4 as  $value) {
            if(!empty($value->instrument)){
            ?>
                <div class="col-sm-6">
                <label for=""><?php echo $value->instrument; ?></label><br>
                </div>
                <div class="col-sm-6">
                <label for="">
                    <?php 
                    if(!empty($value->json)){
                        echo "finished";
                    }else{
                        echo "unfinished";
                    }
                    ?>
                </label>
            </div>
            <?php
        }
      }
        ?>
        </div>
        </div>
<?php
}
get_footer(); ?>