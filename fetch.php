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
  
  if(isset($_GET["id"])) {
  $id = htmlspecialchars(trim($_GET["id"]));
  $q = mysqli_query("SELECT `name`, `downloads`, `size`, `location`, `extension`, `userid` FROM `files` WHERE(`id`={$id})") or die(mysqli_error());
  if(mysqli_num_rows($q)) { $f = mysqli_fetch_array($q);
  $file = $f['location']; $name = $f['name']; $ext = $f['extension']; $dl = $f['downloads'] + 1;
  $size = $f['size']; $owner = $f['userid']; }
  
  /* Static variables */
  $ip = $_SERVER['REMOTE_ADDR']; $fileSize = $size; $date = date("d M Y");
  
  $dl_record = mysqli_query("INSERT INTO `downloads`(`ip_address`, `file_id`, `file_size`, `date`, `owner_id`, `dl_user`) VALUES('{$ip}', {$id}, {$fileSize}, '{$date}', {$owner}, {$userid})") or die(mysqli_error());
  $dl_update = mysqli_query("UPDATE `files` SET `downloads`={$dl} WHERE(`id`={$id})") or die(mysqli_error());
  if($owner != 0) { 
  $bw_get = mysqli_query("SELECT `bandwidth_used` FROM `members` WHERE(`id`={$owner})") or die(mysqli_error());
  if(mysqli_num_rows($bw_get)) { $bw_fetch = mysqli_fetch_array($bw_get); $bw_used = $bw_fetch['bandwidth_used'] + $fileSize;
  $bw_update = mysqli_query("UPDATE `members` SET `bandwidth_used`={$bw_used} WHERE(`id`={$owner})") or die(mysqli_error());
  }
  
  /* Getting the country of the user using the IP address and making use of that for the
  points system */
  
  tier_points();
  
  $temp_month = date("M"); $last_action = date("d M Y H:i"); $temp_year = date("Y");
  $point_query = mysqli_query("SELECT * FROM `points` WHERE(`userid`={$owner}) AND (`month`='{$temp_month}') AND (`year`='{$temp_year}')") or die(mysqli_error());
  if(mysqli_num_rows($point_query)) {
	$point_fetch = mysqli_fetch_array($point_query);
	$owner_points = $point_fetch['points'] + $points;
	$point_update = mysqli_query("UPDATE `points` SET `points`={$owner_points}, `last_action`='{$last_action}' WHERE(`userid`={$owner}) AND (`month`='{$temp_month}') AND (`year`='{$temp_year}')") or die(mysqli_error());
  }
  else {
	$point_insert = mysqli_query("INSERT INTO `points`(`userid`, `month`, `year`, `points`, `last_action`) VALUES({$owner}, '{$temp_month}', '{$temp_year}', {$points}, '{$last_action}')") or die(mysqli_error());
  }
  }
  
  $size = $size * 1024;
  /* Create the output file */
  
  header("Content-type: ".$ext." File");
  header("Content-Transfer-Encoding: Binary");
  header("Content-length: ".$size);
  header("Content-disposition: attachment; filename=\"".$name."\"");
  readfile($file);
  }
  
?>