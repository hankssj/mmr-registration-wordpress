<?php  
/* 
Template Name: Forgot Passoword Page
*/ 
get_header();
global $post;

?>
 <?php
    global $wpdb;
    
    $error = '';
    $success = '';
    
    // check if we're in reset form
    if( isset( $_POST['action'] ) && 'reset' == $_POST['action'] ) 
    {
      $email = trim($_POST['user_login']);
      
      if( empty( $email ) ) {
        $error = 'Enter a username or e-mail address..';
      } else if( ! is_email( $email )) {
        $error = 'Invalid username or e-mail address.';
      } else if( ! email_exists( $email ) ) {
        $error = 'There is no user registered with that email address.';
      } else {
        
        $random_password = wp_generate_password( 12, false );
        $user = get_user_by( 'email', $email );
        
        $update_user = wp_update_user( array (
            'ID' => $user->ID, 
            'user_pass' => $random_password
          )
        );
        
        // if  update user return true then lets send user an email containing the new password
        if( $update_user ) {
          $to = $email;
          $subject = 'Your new password';
          $sender = 'info';
          $fromemail='charlessamuel733@gmail.com';
          $message = 'Your new password is: '.$random_password;
          
          $headers[] = 'MIME-Version: 1.0' . "\r\n";
          $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
          $headers[] = "X-Mailer: PHP \r\n";
          $headers[] = 'From: '.$sender.' < '.$fromemail.'>' . "\r\n";
          
          $mail = wp_mail( $to, $subject, $message, $headers );
          //echo $mail;
          //var_dump($mail);
          if( $mail )
           
           echo '<script type="text/javascript">

          location.href = "'.site_url('/login/?msg=reset_pwd').'";

            </script>';
             
            
            
        } else {
          $error = 'Oops something went wrong updaing your account.';
        }
        
      }
      
      
      ?>

     
<?php 
        //wp_redirect( site_url('/registration/?msg=reset_pwd') ); exit;

        //header(site_url('/registration/?msg=reset_pwd')); /* Redirect browser */
       // exit();
    }
  ?> 
<style>
  .header-bottom {
    background: url(<?= $image['url'];?>) no-repeat fixed;
  }
</style>
<!-- Header Bottom -->

 
  
 
  <!--html code-->
  <div class="container">

<div class="wrapper forgot_pwd">
 <?php if( ! empty( $error ) )
        echo '<div class="message" style="color:red;"><p class="error" style="color:red;"><strong>ERROR:</strong> '. $error .'</p></div>';
      ?>
  <form method="post" id="forgot-pwd" class="form-ui" >
      <h3 class="forgot_title">Forgot your password?</h3>
     
      <div class="form-group">
        <?php $user_login = isset( $_POST['user_login'] ) ? $_POST['user_login'] : ''; ?>
        <div class="form-left"><lable>Email Address:</lable><input type="text" name="user_login" id="user_login_email" class="form-control" value="" placeholder="E-mail" required /></div>
        </div>
        <div class="form-group">      
        <input type="hidden" name="action" value="reset"/><br/>
      <input type="submit" value="Save" class="btn btn-4 blue default" id="submit" />
      </div>
      </div>
    
  </form>
</div>
</div>
<?php get_footer() ?>