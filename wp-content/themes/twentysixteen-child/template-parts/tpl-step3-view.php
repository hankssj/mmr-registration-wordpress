<?php
/*
Template Name: step-3 view
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
        <label for="">AFTERNOON ENSEMBLES AND ELECTIVES</label>
    </div>
    <?php
    $querystr = "
        SELECT * 
        FROM ".$wpdb->prefix."questionnaires_afternoon
        WHERE userID = ".$user_ids."
        ";
          $instrument = array();
          
          $check_user = $wpdb->get_results($querystr, OBJECT);
          foreach ($check_user as $formdata) {
            $chamber_assemble = $formdata->chamber_assemble;
            $prearranged_group = $formdata->prearranged_group;
            $instrument[1] = $formdata->instrument1;
            $instrumentyesno1 = $formdata->instrumentyesno1;
            $comment[1] = $formdata->comment1;
            $instrument[2] = $formdata->instrument2;
            $instrumentyesno2 = $formdata->instrumentyesno2;
            $comment[2] = $formdata->comment2;
            $p_instrument[1] = $formdata->p_instrument1;
            $groupname1 = $formdata->groupname1;
            $p_instrumentyesno[1] = $formdata->p_instrumentyesno1;
            $contactpersonname[1] = $formdata->contactpersonname1;
            $groupname[1] = $formdata->groupname1;
            $contactpersonemail[1] = $formdata->contactpersonemail1;
            $otherparticipant[1] = $formdata->otherparticipant1;
            $ownmusic1 = $formdata->ownmusic1;
            $composer1 = $formdata->composer1;
            $p_comment[1] = $formdata->p_comment1;
            $p_instrument[2] = $formdata->p_instrument2;
            $groupname[2] = $formdata->groupname2;
            $p_instrumentyesno[2] = $formdata->p_instrumentyesno2;
            $contactpersonname[2] = $formdata->contactpersonname2;
            $contactpersonemail[2] = $formdata->contactpersonemail2;
            $otherparticipant[2] = $formdata->otherparticipant2;
            $ownmusic2 = $formdata->ownmusic2;
            $composer2 = $formdata->composer2;
            $p_comment[2] = $formdata->p_comment2;
            
          }
    ?>
    <div class="form-group">
        <label for="">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</label>
    </div>
    <div class="form-group" style="border: 1px solid #ccc;padding: 10px;">
        <label for="">AFTERNOON ENSEMBLE PREFERENCES</label><br>
        <label for="">MMR Arranged Chamber Ensembles</label> :
        <label for=""><?php echo $chamber_assemble; ?></label><br>
        <label for="">Prearranged Groups</label> :
        <label for=""><?php echo $prearranged_group; ?></label>
    </div>
    <?php
    if($chamber_assemble != ""){
        ?>
        
        <?php
        for($i=1;$i<=$chamber_assemble;$i++){
            ?>
            <div class="form-group">
                <u><b><h4>Assigned Groups <?php echo $i; ?></h4></b></u>
            </div>
            <div class="form-group">
                <label for="">Which instrument or vocal part are you playing in this group?</label><br>->
                <label for=""><?php echo $instrument[$i]; ?></label>
            </div>
            <div class="form-group">
                <label for=""></label>Other Comments, questions, or requests <br>->
                <label for=""><?php echo $comment[$i] ?></label>
            </div>
            <?php
        }
    }
    ?>
    <?php
    if($prearranged_group != ""){
        ?>
        
        <?php
        for($i=1;$i<=$prearranged_group;$i++){
            ?>
            <div class="form-group">
                <u><b><h4>Prearranged Groups <?php echo $i; ?></h4></b></u>
            </div>
            <div class="form-group">
                <label for="">Which instrument or vocal part are you playing in this group?</label><br>->
                <label for=""><?php echo $p_instrument[$i]; ?></label>
            </div>
            <div class="form-group">
                <label for="">Group Name</label> <br>->
                <label for=""><?php echo $groupname[$i] ?></label>
            </div>
            <?php
            if($p_instrumentyesno[$i] == "yes"){
                ?>
            <div class="form-group">
                <label for="">Names and instruments/voices of other participants :</label><br>->
                <label for=""><?php echo $otherparticipant[$i]; ?></label>
            </div>
                <?php
            }else{
                ?>
            <div class="form-group">
                <label for="">Contact person name</label><br>->
                <label for=""><?php echo $contactpersonname[$i]; ?></label>
            </div>
            <div class="form-group">
                <label for="">Contact person email</label><br>->
                <label for=""><?php echo $contactpersonemail[$i]; ?></label>
            </div>
                <?php
            }
            ?>
            <div class="form-group">
                <label for="">Other Comments, questions, or requests</label><br>->
                <label for=""><?php echo $p_comment[$i]; ?></label>
            </div>
            <?php
        }
    }
    ?>
    

</div>
<?php
}
get_footer(); ?>