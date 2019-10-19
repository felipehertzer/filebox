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
 
 include("init.php");
 include("user/header.php"); $css = ""; $js = ""; $page_header = "Error";
 $body = "<center><div class='logindiv'><div id='logindiv-header'><p>Error: Page not found</p></div>
 <p id='forall'>The information or page you are looking for either does not exist or has been moved to another location. Please make sure you followed the right link. <br/><br/>If you are sure that you followed the right link, then please tell our staff about this error by reporting it <a href='{$website}/static/contact.php'>over here</a>.</p></div></center>";
 
 if(isset($_GET['page_id'])) { $page_id = htmlspecialchars(trim($_GET['page_id']));
 $sql = mysqli_query($link, "SELECT * FROM `pages` WHERE(`page_id`='{$page_id}')") or die(mysqli_error($link));
 if(mysqli_num_rows($sql)) { $sql_f = mysqli_fetch_array($sql); $page_header = $sql_f['page_header']; $body = $sql_f['body']; }
 else { $page_header = "Error"; } }
 subheader($page_header, $css, $js, '');
 
 echo "<div id='static'>".$body."</div>";
 	
 include("user/footer.php");
 
?>