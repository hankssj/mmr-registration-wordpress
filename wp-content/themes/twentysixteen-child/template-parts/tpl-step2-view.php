<?php
/*
Template Name: step-2 view
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
<?php }
	if($current_user->roles[0] == 'administrator'){
	    $user_ids = $_REQUEST['id'];
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
        <label for="">MORNING LARGE ENSEMBLE CHOICE</label>
    </div>
    <?php
    if(is_user_logged_in()){
    $querystr = "
        SELECT * 
        FROM ".$wpdb->prefix."questionnaires_ensemble
        WHERE user_id = ".$user_ids."
        ";
    
          $check_user = $wpdb->get_results($querystr, OBJECT);
        
          $completeduser = '';
        
          foreach ($check_user as $formdata) {
            $instrumental = $formdata->instrumental;
            $ensemble = $formdata->ensemble;
            $bandoption = $formdata->bandoption;
          }
    }
    ?>
    <div class="form-group">
        <label for="">Your Primary Instrument or Voice is <?php echo $instrumental; ?>; This will be used for choice of large ensemble.</label>
    </div>
    <?php
    if($bandoption == "none"){
        ?>
        <div class="form-group">
            <label for="">Ensemble Choice</label><br>-
            <label for=""><?php echo $ensemble; ?></label>
        </div>
        <?php
    }
    if($ensemble == "none"){
        ?>
        <div class="form-group">
            <label for="">Please indicate your preferred saxophone voice for Symphonic Band</label><br>-
            <label for=""><?php echo $bandoption; ?></label>
        </div>
        <?php
    }
    if($bandoption !="none" && $ensemble !="none"){
        ?>
        <div class="form-group">
            <label for="">Ensemble Choice</label><br>-
            <label for=""><?php echo $ensemble; ?></label>
        </div>
        <div class="form-group">
            <label for="">Please indicate your preferred saxophone voice for Symphonic Band</label><br>-
            <label for=""><?php echo $bandoption; ?></label>
        </div>
        <?php
    }
    ?>
    
</div>


<?php

get_footer();
} ?>