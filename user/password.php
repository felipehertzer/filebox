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
 include("header.php");
 subheader('Change Password');
 
 if($sesslife == true) { $err = '';	
	if(isset($_POST['changeme'])) {
		$currentpass = htmlspecialchars(trim($_POST['currentpass']));
		$newpass = htmlspecialchars(trim($_POST['newpass']));
		$confirmpass = htmlspecialchars(trim($_POST['confirmpass']));
	if((!empty($currentpass)) && (!empty($newpass)) && (!empty($confirmpass))) {	
		if($currentpass == $userpass) {
			if($newpass == $confirmpass) {
				if(strlen($newpass) <= 25) {
					$sql = mysqli_query("UPDATE `members` SET `password`='{$newpass}' WHERE(`id`={$userid})") or die(mysqli_error());
					$_SESSION["pass"] = $newpass;
					$err = "<div class='infobox'><p>Password changed<br/><small>Your password has been changed successfully and is effective immediately.</small></p></div>"; ft_changepass();
				} else { $err = "<div class='errorbox'><p>Password too long<br/><small>Your password contains more than 25 characters. Please restrict your password to 25 or less characters.</small></p></div>"; ft_changepass(); }			
			} else { $err = "<div class='errorbox'><p>Password mismatch<br/><small>The new password and confirm password entered by you do not match. Make sure they both are same. This is used to double check the password entered by you.</p></div>"; ft_changepass(); }
		} else { $err = "<div class='errorbox'><p>Current password is not correct<br/><small>The current password entered by you does not match with the one we have in our records. Please recheck and enter correct password.</small></p></div>"; ft_changepass(); }
	} else { $err = "<div class='errorbox'><p>Please fill in all the fields</p></div>"; ft_changepass(); }
	} else { ft_changepass(); }
 } else { $err = "<div class='infobox'><p>Not Logged Version<br/><small>You does not seem to be logged in to the website. Please login in order to view this page.</small></p></div>";
 am_showLogin(); }
 
 include("footer.php");
 
 function ft_changepass() { global $err;
		echo "<center><div class='logindiv' style='text-align:left;'><div id='logindiv-header'><p>Change Password</p></div>";
		echo "{$err}<form id='changepassword' method='POST'><table id='userinfo' style='margin-left:30px;'><tr><td class='first'>Current Password</td><td><input type='password' name='currentpass' value='' size='30' /></td></tr>
		<tr><td class='first'>New Password</td><td><input type='password' name='newpass' value='' size='30' /></td></tr>
		<tr><td class='first'>Re-enter Password</td><td><input type='password' name='confirmpass' value='' size='30' /></td></tr>
		<tr><td></td><td><input type='submit' name='changeme' class='button' value='Change Password' /></td></tr></table><br/></div></center>";
 }
 
?>