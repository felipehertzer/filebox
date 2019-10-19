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
  subheader('Email Verification');
  
  if($sesslife == false) {
  if(isset($_GET['k'])) {
  $key = htmlspecialchars(trim($_GET['k']));
  if($key != "") {
  $q = mysqli_query("SELECT * FROM `members` WHERE(`key`='{$key}')") or die(mysqli_error());
  $n = mysqli_num_rows($q);
	if($n) {
	$f = mysqli_fetch_array($q);
	$verify = $f['verified'];
	if($verify == 0) {
	$update = mysqli_query("UPDATE `members` SET `verified`='1' WHERE(`key`='{$key}') LIMIT 1") or die(mysqli_error());
	echo "<center><div class='infobox' style='width:96%;'><p>{$lang[91]} <br/><small><a href='{$website}/user/login.php'>{$lang[92]}</a></small></div></center><br/><br/>";
	} else { echo "<center><div class='errorbox' style='width:96%;'><p>{$lang[93]}</p></div></center><br/><br/>"; }
	} else { echo "<center><div class='errorbox' style='width:96%;'><p>{$lang[94]}<br/><small>{$lang[44]} <a href='{$website}/static/contact.php'>{$lang[46]}</a>.</small></p></div></center><br/><br/>"; }
  } else { echo "<center><div class='errorbox' style='width:96%;'><p>{$lang[95]}<br/><small>{$lang[47]}</small></p></div></center><br/><br/>"; }
  } else { echo "<center><div class='errorbox' style='width:96%;'><p>{$lang[95]}<br/><small>{$lang[47]}</small></p></div></center><br/><br/>"; } } else { echo "<center><div class='errorbox' style='width:96%;'><p>{$lang[40]}</p></div></center><br/><br/>"; }include("footer.php");

?>