<?php

	/*
	+--------------------------------------------------------------------------
	|   FileBox - File Hosting & Sharing Script (v1.5)
	|   =======================================================================
	|   by ScriptsApart
	|   (c) 2010 ScriptsApart
	|   http://www.scriptsapart.com
	|   =======================================================================
	|   Web: http://www.scriptsapart.com
	|   Email: support@scriptsapart.com
	+--------------------------------------------------------------------------
	|   > Developed On: 25th December 2010
	+--------------------------------------------------------------------------
	*/
 
  include("../init.php");
  include('../modules/paypal.class.php');
  include("header.php");
  subheader('Order Status');
  
  if(isset($_GET['s'])) { $s = htmlspecialchars(trim($_GET['s']));
  
  $p = new paypal_class; // initiate an instance of the class
  $p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
  //$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url
            
  // setup a variable for this script (ie: 'http://www.micahcarrick.com/paypal.php')
  $this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

  // if there is not action variable, set the default action of 'process'
  if(empty($s)) { $s = 'process'; }

  switch($s) {
    
   case 'process':      // Process and order...

      // There should be no output at this point.  To process the POST data,
      // the submit_paypal_post() function will output all the HTML tags which
      // contains a FORM which is submited instantaneously using the BODY onload
      // attribute.  In other words, don't echo or printf anything when you're
      // going to be calling the submit_paypal_post() function.
 
      // This is where you would have your form validation  and all that jazz.
      // You would take your POST vars and load them into the class like below,
      // only using the POST values instead of constant string expressions.
 
      // For example, after ensureing all the POST variables from your custom
      // order form are valid, you might have:
      //
      // $p->add_field('first_name', $_POST['first_name']);
      // $p->add_field('last_name', $_POST['last_name']);
      
	  echo "<div class='global'><div id='main'>";
	  if($sesslife == true) {
      $p->add_field('business', $adminemail);
      $p->add_field('return', $this_script.'?s=success');
      $p->add_field('cancel_return', $this_script.'?s=cancel');
      $p->add_field('notify_url', $this_script.'?s=ipn');
      $p->add_field('item_name', $webtitle.' Premium Membership');
      $p->add_field('amount', $premium_cost);
	  $p->add_field('custom', $userid);
      $p->submit_paypal_post(); // submit the fields to paypal
	  } else { echo "<div class='errorbox'><p>Session expired.</p></div><br/><p class='margin'>You are not allowed to perform this action. You must <a href='{$website}/user/login.php'>login</a> to the website before you can go for premium membership.</p>"; }
      //$p->dump_fields();      // for debugging, output a table of all the fields
	  echo "</div></div>";
      break;
      
   case 'success':      // Order was successful...
   
      // This is where you would probably want to thank the user for their order
      // or what have you.  The order information at this point is in POST 
      // variables.  However, you don't want to "process" the order until you
      // get validation from the IPN.  That's where you would have the code to
      // email an admin, update the database with payment status, activate a
      // membership, etc.  
 
      echo "<div class='global'><div id='main'><div id='account-header'><p>Order Processed Successfully</p></div>";
      $key = $_POST;
	  echo "<table id='userinfo'><tr><td class='first'>Buyer Name</td><td>{$key['first_name']} {$key['last_name']}</td></tr>
	  <tr><td class='first'>Email</td><td>{$key['payer_email']}</td></tr>
	  <tr><td class='first'>Item</td><td>{$key['item_name']}</td></tr>
	  <tr><td class='first'>Payment Time</td><td>{$key['payment_date']}</td></tr>
	  <tr><td class='first'>Amount</td><td>USD {$key['payment_gross']}</td></tr>
	  <tr><td class='first'>Status</td><td>{$key['payment_status']}</td></tr>";
	  echo "</table><br/><br/>";
	  echo "<small class='margin' style='color:#333333;'>Your payment has been received and your membership will be turned to <i><b>\"Premium\"</b></i> once it has been verified. In any case, you will be notified of the same via email.</small><br/><br/>";
	  echo "</div></div>";
      break;
      
   case 'cancel':
   
      echo "<div class='global'><div id='main'><div id='account-header'><p>Order Cancelled</p></div>";
      echo "<small class='margin' style='color:#333333;'>You have cancelled the payment for your <i><b>\"Premium\"</b></i> membership. In case you change your mind and want to go for it, then you can follow <a href='{$website}/user/upgrade.php'>this link</a> to upgrade your account.</small>";
	  echo "</div></div>";
	  break;
      
   case 'ipn':          // Paypal is calling page for IPN validation...
   
      // It's important to remember that paypal calling this script.  There
      // is no output here.  This is where you validate the IPN data and if it's
      // valid, update your database to signify that the user has payed.  If
      // you try and use an echo or printf function here it's not going to do you
      // a bit of good.  This is on the "backend".  That is why, by default, the
      // class logs all IPN data to a text file.
      
      if ($p->validate_ipn()) {
          
         // Payment has been recieved and IPN is verified.  This is where you
         // update your database to activate or process the order, or setup
         // the database with the user's order details, email an administrator,
         // etc.  You can access a slew of information via the ipn_data() array.
  
         // Check the paypal documentation for specifics on what information
         // is available in the IPN POST variables.  Basically, all the POST vars
         // which paypal sends, which we send back for validation, are now stored
         // in the ipn_data() array.
  
         // For this example, we'll just email ourselves ALL the data.
		  
		 $subject = "Instant Payment Notification - Recieved Payment";
		 $headers = "From: ".$p->ipn_data['payer_email']. "\r\n";
		 $headers .= "Reply-To: ".$p->ipn_data['payer_email']. "\r\n";
		 $headers .= "MIME-Version: 1.0\r\n";
		 $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		 
		 $message =  "An instant payment notification was successfully recieved\n";
         $message .= "from ".$p->ipn_data['payer_email']." on ".date('m/d/Y');
         $message .= " at ".date('g:i A')."<br/><br/>Details:<br/><br/>";
         foreach ($p->ipn_data as $key => $value) { $message .= "{$key}: {$value}<br/>"; }		 
		 $mailsent = mail($adminemail, $subject, $message, $headers);
		 
		 $date = time(); $expires_on = $date + 2592000;
		 $transaction_query = mysqli_query("INSERT INTO `transactions`(`amount`, `userid`, `date`, `expires_on`) VALUES({$p->ipn_data['payment_gross']}, {$p->ipn_data['custom']}, '{$date}', '{$expires_on}')") or die(mysqli_error());
		 $make_premium = mysqli_query("UPDATE `members` SET `premium`=1 WHERE(`id`={$p->ipn_data['custom']})") or die(mysqli_error());
		 }
		 
      break;
 } // Switch ends over here
 } // End of isset($_GET) stuff

 include("footer.php");
 
?>