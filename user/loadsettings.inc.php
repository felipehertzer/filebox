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

$result = mysqli_query($link, "SELECT * FROM `settings`");
$r = mysqli_fetch_array($result);
$website = $r['website'];
if ($website == "") {
    die("<br/><center><h1 style='font-size:20px;font-weight:100;font-family:arial;'><b>FileBox</b> does not seem to be installed. Please run Install wizard to install the product.</h1></center>");
}
$webtitle = $r['title'];
$description = $r['description'];
$keywords = $r['keywords'];
$publickey = $r['recaptcha_public'];
$privatekey = $r['recaptcha_private'];
$adminemail = $r['admin_email'];
$premium_cost = $r['premium_cost'];
$premium_timer = $r['premium_timer'];
$normal_timer = $r['normal_timer'];
$anon_timer = $r['anon_timer'];
$premium_space = $r['premium_space'];
$normal_space = $r['normal_space'];
$premium_maxFile = $r['premium_maxfile'];
$normal_maxFile = $r['normal_maxfile'];
$anon_maxFile = $r['anon_maxfile'];

/* Variables for tier system */
$tier1_points = $r['tier1_points'];
$tier2_points = $r['tier2_points'];
$tier3_points = $r['tier3_points'];
$tier4_points = $r['tier4_points'];

?>