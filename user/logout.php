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
  
  if($sesslife == true) { 
	if(!$cookie) {
		$session->stop(); 
	}
  } else {
		echo "<meta http-equiv='Refresh' Content='0;URL={$website}/' />";
  }
  
  include("header.php");
  subheader($lang[36], '', '', 'logout');
  
  if($cookie) { echo "<script>FB.logout(function(response){window.location.reload();});</script>"; }
  echo "<center><div id='loginmsg'><img src='{$website}/images/working.gif' /><br/>";
  echo "<p>{$lang[75]}</p></div></center>";
  echo "<meta http-equiv='Refresh' Content='5;URL={$website}/' />";
   
  include("footer.php");

?>