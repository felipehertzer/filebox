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
  require_once('../modules/recaptchalib.php');
  include("header.php");
  subheader($lang[35]);
  
  if($sesslife == false) { // Only allowed if the user is not logged in
  
  if(isset($_POST['forgot'])) { // If form is submitted then process and display the result
	$email = htmlspecialchars(trim($_POST['email']));
	if($email != "") {
		
	$resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
    if ($resp->is_valid) {
		
		$q = mysqli_query($link, "SELECT * FROM `members` WHERE(email='{$email}')") or die(mysqli_error($link));
		$n = mysqli_num_rows($q);
		
		if($n) {
		$f = mysqli_fetch_array($q);	$uid = $f['id'];
		$password = createRandomPassword();
		$q = mysqli_query($link, "UPDATE `members` SET `password` = '{$password}' WHERE `id`={$uid} LIMIT 1") or die(mysqli_error($link));
		forgotpass_email($email, $password);
		$err = "<div class='infobox'><p>{$lang[52]}</p></div>"; am_showForgot();
		} else { $err = "<div class='errorbox'><p>{$lang[53]}</p></div>"; am_showForgot(); }
		} else { $err = "<div class='errorbox'><p>{$lang[54]}</p></div>"; am_showForgot(); }
	} else { $err = "<div class='errorbox'><p>{$lang[55]}</p></div>"; am_showForgot(); }
  }
  else { am_showForgot(); }
  } else { echo "<br/><center><div class='errorbox'><p>{$lang[40]}</p></div></center><br/><br/>"; }
  
  function am_showForgot() { global $lang; global $website; global $publickey; global $webtitle; global $err; ?>
  <center><form method='POST' action='<?php echo $website; ?>/user/forgot.php'>
  <div class='logindiv'>
  <div id='logindiv-header'><p><?php echo $lang[35]; ?></p></div>
  <?php echo $err; ?>
  <table>
  <tr><td class='first'><?php echo $lang[3]; ?>:</td>
  <td><input type='text' name='email' id='email' size='25' /><br/><small>(<?php echo $lang[56]; ?> <?php echo $webtitle; ?>)</small></td></tr>
  <tr><td></td>
  <tr><td class='first'><?php echo $lang[51]; ?>:</td><td>
  <?php echo recaptcha_get_html($publickey); ?>
  </td></tr><tr><td></td><td><input type='submit' name='forgot' class='button' value='<?php echo $lang[8]; ?>' /></td></tr>
  <tr><td></td><td><small><?php echo $lang[10]; ?> <?php echo $webtitle; ?>? <a href='<?php echo $website; ?>/user/login.php'><?php echo $lang[11]; ?></a></small></td></tr>
  </table></div></form></center><br/><br/>
  
  <?php } include("footer.php"); ?>