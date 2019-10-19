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
 subheader('User Account', '', '', 'account');
 
 if($sesslife == true) { 
 echo "<div class='global'><div id='main'><div id='account-header'><p>User Information</p></div><br/><small class='margin'>Information related to your account on {$webtitle} is displayed over here. To edit them just click on the info you want to change.</small>";
 ft_showinfo($userid); echo "</div></div><div class='sidebar'><a href='#' onClick='userInfo();'><p id='userinfo' class='selected'>User Information</p></a><a href='#' onClick='userClose();'><p id='deleteaccount'>Close Account</p></a></div>";
 } else { $err = "<div class='infobox'><p>Login to continue<br/><small>You does not seem to be logged in to the website. Please login in order to view this page.</small></p></div>"; am_showLogin(); }
 
 include("footer.php");
 
?>