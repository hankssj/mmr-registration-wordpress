<?php
/*
Template Name: step4
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

$getlead = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."rg_lead WHERE created_by = ".$user_id."",ARRAY_A);
$lead_id = $getlead[0][id];

$getprimaryinstrument = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."rg_lead_detail WHERE field_number = 23 AND lead_id = ".$lead_id."",ARRAY_A);
$primary_instrument = $getprimaryinstrument[0][value];


  
  if($_POST['submit']){
      if(isset($_REQUEST['choice'])){
        //print_r($_REQUEST['choice']);
        $count = count($_REQUEST['choice']);
        if($count >= 3){
        $current_user = wp_get_current_user();
        $user_id = $current_user->data->ID;
        $sqldele = "DELETE FROM ".$wpdb->prefix."questionnaires_electives WHERE user_id = '".$user_id."'";
        $wpdb->query($sqldele);
        $btrayarray = array();
        $k = 0;
        foreach($_REQUEST['choice'] as $choice){
        	$btrayarray[$k]['choice'] = $choice;
        	$k++;
        }
        $l = 0;
        foreach($_REQUEST['id'] as $id){
        	$btrayarray[$l]['id'] = $id;
        	$l++;
        }
        $j = 0;
        foreach($_REQUEST['options'] as $options){
        	$btrayarray[$j]['options'] = $options;
        	$j++;
        }
        
        foreach($btrayarray as $array){
    			$sqlins = "INSERT INTO ".$wpdb->prefix."questionnaires_electives (elective_id,user_id,choice,options) VALUES ('".$array['id']."','".$user_id."','".$array['choice']."', '".$array['options']."')";
    			$wpdb->query($sqlins);
    			$_SESSION['success'] = "saved successfully";
    		}
    		$sql = "UPDATE ".$wpdb->prefix."questionnaires_self_evaluations SET `completed`='no' WHERE `user_id`='".$user_id."' AND `group` =''";
        $wpdb->query($sql);
        /*$sql1 = "UPDATE ".$wpdb->prefix."questionnaires_read_accept_terms SET `completed`='no' WHERE `user_id`='".$user_id."'";
        $wpdb->query($sql1);*/
        $sql2 = "UPDATE ".$wpdb->prefix."questionnaires_review_and_commit SET `completed`='no' WHERE `user_id`='".$user_id."'";
        $wpdb->query($sql2);
    		?>
        <script> 
        //window.location.href = "<?php //echo esc_url(get_permalink( $post->post_parent )); ?>";
        </script>
        <?php
        }
        else
        {
          $current_user = wp_get_current_user();
        $user_id = $current_user->data->ID;
        $sqldele = "DELETE FROM ".$wpdb->prefix."questionnaires_electives WHERE user_id = '".$user_id."'";
        $wpdb->query($sqldele);
        $btrayarray = array();
        $k = 0;
        foreach($_REQUEST['choice'] as $choice){
          $btrayarray[$k]['choice'] = $choice;
          $k++;
        }
        $l = 0;
        foreach($_REQUEST['id'] as $id){
          $btrayarray[$l]['id'] = $id;
          $l++;
        }
        $j = 0;
        foreach($_REQUEST['options'] as $options){
          $btrayarray[$j]['options'] = $options;
          $j++;
        }
        
        foreach($btrayarray as $array){
          $sqlins = "INSERT INTO ".$wpdb->prefix."questionnaires_electives (elective_id,user_id,choice,options) VALUES ('".$array['id']."','".$user_id."','".$array['choice']."', '".$array['options']."')";
          $wpdb->query($sqlins);
        }

          $_SESSION['error'] ="You have to select atleast 3 electives";
           // echo "<div class='red'>You have to select atleast 3 electives</div>";
        }
        
      }
      else{
        $current_user = wp_get_current_user();
        $user_id = $current_user->data->ID;
        $sqldele = "DELETE FROM ".$wpdb->prefix."questionnaires_electives WHERE user_id = '".$user_id."'";
        $wpdb->query($sqldele);
        $btrayarray = array();
        $k = 0;
        foreach($_REQUEST['choice'] as $choice){
          $btrayarray[$k]['choice'] = $choice;
          $k++;
        }
        $l = 0;
        foreach($_REQUEST['id'] as $id){
          $btrayarray[$l]['id'] = $id;
          $l++;
        }
        $j = 0;
        foreach($_REQUEST['options'] as $options){
          $btrayarray[$j]['options'] = $options;
          $j++;
        }
        
        foreach($btrayarray as $array){
          $sqlins = "INSERT INTO ".$wpdb->prefix."questionnaires_electives (elective_id,user_id,choice,options) VALUES ('".$array['id']."','".$user_id."','".$array['choice']."', '".$array['options']."')";
          $wpdb->query($sqlins);
        }

        $_SESSION['error'] ="You have to select atleast 3 electives";
        // echo "<div class='red'>You have to select atleast 3 electives</div>";
      }
  }

  $querystr = "
        SELECT * 
        FROM ".$wpdb->prefix."questionnaires
        WHERE userID = ".$user_id."
        ";

  $check_user = $wpdb->get_results($querystr, OBJECT);

  $completeduser = '';

  foreach ($check_user as $formdata) {
    $completeduser = $formdata->completed;
  }

?>

<style>

.steps li{
    text-align:center;
    display: inline-block;
    font-size: 14px;
    margin-right: 10px;
   
}
.steps li a{
  background: #eee;
    color: #aaa;
     display: block;
    width: 100%;
    height: auto;
    line-height: 48px;
    padding: 0px 15px;
    border-radius: 10px;
}
.steps .active-li{
    
    color: #fff !important;
    text-align:center;
}
.steps .active-li a{
    color: #fff;
    background: #2184be !important;
}
</style>
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
    <li class="active-li"><a href="<?php echo site_url(); ?>/electives/">Electives</a>
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
<style>
  #sortable1{
    border: 1px solid #eee;
    width: 100%;
    min-height: 20px;
    list-style-type: none;
    margin: 0;
    padding: 5px 0 0 0;
    float: left;
    margin-right: 10px;
  }
  #sortable2 {
    border: 1px solid #eee;
    width: 100%;
    min-height: 300px;
    list-style-type: none;
    margin: 0;
    padding: 5px 0 0 0;
    float: left;
    margin-right: 10px;
  }
  #sortable1 li, #sortable2 li {
    margin: 0 5px 5px 5px;
    padding: 5px;
    font-size: 1.2em;
  }
  .red{
    color:red;
  }
  .detailed_information {
    left: 100%;
    position: absolute;
    top: 0;
    width: 100%;
    z-index: 990;
    display: none;
  }
  #sortable1 li:hover .detailed_information {
    display:block;
  }
  #information_box{
    background: #fff;
    border: 2px solid #cccccc;
    margin-top: 10px;
    padding: 10px;
  }
  #information_box .title_box{
    border: 1px solid #cccccc;
    font-size: 18px;
    padding: 0 10px;
  }
  #information_box .instructor_info{
    border: 1px solid #cccccc;
    font-size: 12px;
    padding: 5px 10px;
  }
  .label-top{
    border: 1px solid #cccccc;
    font-size: 17px;
    font-weight: bold;
    text-align: center;
    text-transform: uppercase;
  }
  .bs-example{
          margin-top: 25px;
          margin-bottom: 25px;
          padding: 15px;
          border: double 1px;
          border-radius: 15px;
    }
.row{
      border: 1px solid #cccccc;
      padding: 10px;
      width: 97%;
      text-align: center;
      margin: 0 auto;
    }
  </style>
  <script>
  jQuery( function() {
    jQuery( "#sortable1" ).sortable({
      connectWith: ".connectedSortable",
      update: function( ) {
           jQuery(this).find('select').hide();
      }
    }).disableSelection();
    jQuery( "#sortable1" ).draggable({
      connectWith: ".connectedSortable",
      update: function( ) {
           jQuery(this).find('select').hide();
      }
    }).disableSelection();
    
    jQuery( "#sortable2" ).sortable({
      connectWith: ".connectedSortable",
      update: function( ) {
           jQuery(this).find('select').show();
      }
    }).disableSelection();
    jQuery( "#sortable2" ).draggable({
      connectWith: ".connectedSortable",
      update: function( ) {
           jQuery(this).find('select').show();
      }
    }).disableSelection();
    
   /*jQuery("#sortable1 li").hover(function(){
          var getclass = jQuery(this).attr('id');
          jQuery('.'+getclass).show();
        }, function(){
          var getclass = jQuery(this).attr('id');
          jQuery('.'+getclass).hide();
    });*/
  } );
  </script>

<!-- Display static data -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
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
<table class="table1">
  <div class="form-group"> 
      <label for="title">
          <?php
            $title = get_post_meta( get_the_ID(), 'title', true );
            // Check if the custom field has a value.
            if ( ! empty( $title ) ) {
            echo $title;
            }
          ?>
     </label>
 </div>
  <div class="form-group"> 
      <p>
          <?php
              $description = get_post_meta( get_the_ID(), 'description', true );
              // Check if the custom field has a value.
              if ( ! empty( $description ) ) {
              echo $description;
              }
            ?>
     </p>
 </div>
      
      <td style="vertical-align: top;">
      <div class="form-group">
      <label>Available Electives</label>
      <!-- <div class="label-top">Available Electives</div>   -->
      <?php
        $get_elective_records = $wpdb->get_results("SELECT * FROM wp_questionnaires_electives WHERE user_id = '".$user_id."'",ARRAY_A);
          $elevtiveids = array();
          $electiveoptions = array();
          foreach($get_elective_records as $single_records){
            $elevtiveids[] = $single_records['elective_id'];
            $electiveoptions[] = $single_records['options'];
          }
          
        $args = array('post_type'=>'elective','posts_per_page'=>-1,'post__not_in'=>$elevtiveids);
        $the_query = new WP_Query( $args );
        $i = 0;
      ?>
      <ul id="sortable1" class="connectedSortable list-group">
        <?php while ( $the_query->have_posts() ) { $the_query->the_post();
        $get_instruments = get_post_meta(get_the_ID(),'instruments',true);
        ?>
        <li class="ui-state-default list-group-item" id="info_display<?php echo get_the_ID(); ?>">
          <input type="hidden" name="choice[<?php echo $i; ?>]" value="<?php echo get_the_title(); ?>">
          <input type="hidden" name="id[<?php echo $i; ?>]" value="<?php echo get_the_ID(); ?>">
          <?php echo get_the_title(); ?>
          <?php if(!empty($get_instruments)){ ?>
            <select style="display:none;" name="options[<?php echo $i; ?>]" >
              <?php foreach($get_instruments as $instruments){ ?>
                <option value="<?php echo $instruments; ?>"><?php echo $instruments; ?></option>
              <?php } ?>
            </select>
          <?php } else { ?>
              <input type="hidden" name="options[<?php echo $i; ?>]" value=""/>
          <?php } ?>
        <div class="detailed_information">
        <div class="form-group">
            <?php
            $instructorname = get_post_meta(get_the_ID(),'instructor',true);
            ?>
            <div id="information_box" class="info_display<?php echo get_the_ID(); ?>">
              <label class="title_box"><?php echo get_the_title(); ?></label><br>
              <!-- <div class="title_box"><?php //echo get_the_title(); ?></div> -->
              <label class="instructor_info">Instructor : <?php echo $instructorname; ?></label><br>
              <!-- <div class="instructor_info">Instructor : <?php //echo $instructorname; ?> --></br>
              <p>Description : <?php echo get_the_content(); ?></p>
              
              </div>
            </div>
          </div>
        </li>
        <?php $i++; } ?>
        </div>
      </ul>
      </div>
      </td>
      
      <td style="vertical-align: top;">
      <div class="form-group">
      <label style="padding-left: 9px;" for="inputEmail1">Elective Choices (Highest to lowest priority)</label><br>
      <!-- <div class="label-top">Elective Choices (Highest to lowest priority)</div> -->
      <form method="post" name="step4_form" id="step4_form" action="">
        <?php 
          
          $args2 = array('post_type'=>'elective','posts_per_page'=>-1,'post__in'=>$elevtiveids,'orderby' => 'post__in');
          $the_query2 = new WP_Query( $args2 );
          
          $args3 = array('post_type'=>'elective','posts_per_page'=>-1);
          $the_query3 = new WP_Query( $args3 );
          
          $j = 100;
        ?>
        <ul id="sortable2" class="connectedSortable list-group">
          <?php while ( $the_query2->have_posts() ) { $the_query2->the_post();
          $get_instruments2 = get_post_meta(get_the_ID(),'instruments',true);
          ?>
          <?php if(!empty($elevtiveids)){ ?>
          <li class="ui-state-default list-group-item">
            
            <input type="hidden" name="choice[<?php echo $j; ?>]" value="<?php echo get_the_title(); ?>">
            <input type="hidden" name="id[<?php echo $j; ?>]" value="<?php echo get_the_ID(); ?>">
            <?php echo get_the_title(); ?>
            <?php if(!empty($get_instruments2)){ ?>
              <select name="options[<?php echo $j; ?>]" >
                <?php foreach($get_instruments2 as $instruments2){
                ?>
                  <option <?php if($electiveoptions[$j-100] == $instruments2){ ?> selected <?php } ?> value="<?php echo $instruments2; ?>"><?php echo $instruments2; ?></option>
                <?php } ?>
              </select>
            <?php } else { ?>
                <input type="hidden" name="options[<?php echo $j; ?>]" value=""/>
            <?php } ?>
          </li>
          <?php } ?>
          <?php $j++; } ?>
        </ul>
        </div>
        <input type="submit" style="margin: 10px;background-color: #2184be;   border-radius: 5px;" name="submit" value="<?php if(!empty($elevtiveids)){ ?>Edit this Step<?php } else { ?>Complete this Step<?php } ?>"/>
      </form>
      </td>
    </tr>

</table>
</div>





<?php get_footer(); ?>