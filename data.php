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
	if($sesslife == true) {
	$date = date("d"); $month = date("M"); $year = date("Y");
	// Static variables
	$first = 1;
	switch($month) {
	case "Jan":
		$last = 31;
		$previous = "Dec";
		break;
	case "Feb":
		$last = 31;
		$previous = "Jan";
		break;
	case "Mar":
		if($year % 4 == 0) { $last = 29; } 
		else { $last = 28; }
		$previous = "Feb";
		break;
	case "Apr":
		$last = 31;
		$previous = "Mar";
		break;
	case "May":
		$last = 30;
		$previous = "Apr";
		break;
	case "Jun":
		$last = 31;
		$previous = "May";
		break;
	case "Jul":
		$last = 30;
		$previous = "Jun";
		break;
	case "Aug":
		$last = 31;
		$previous = "Jul";
		break;
	case "Sep":
		$last = 31;
		$previous = "Aug";
		break;
	case "Oct":
		$last = 30;
		$previous = "Sep";
		break;
	case "Nov":
		$last = 31;
		$previous = "Oct";
		break;
	case "Dec":
		$last = 30;
		$previous = "Nov";
		break;
	default:
		$last = 30;
	}
	
	if(isset($_GET['t'])) { $t = htmlspecialchars(trim($_GET['t']));
	if($t == 'fileuploads') {
	$data_1 = "<graph caption='User Activity on FileBox' xAxisName='7 days Report' yAxisName='No. of files' decimalPrecision='0' formatNumberScale='0'>";
	$x = 0; for($i=0; $i < 7; $i++) {
	$q = mysqli_query("SELECT COUNT(*) FROM `files` WHERE(`date`='{$date} {$month} {$year}') AND (`userid`={$userid})") or die(mysqli_error());
	$n = mysqli_result($q, 0);
    $data_1 .= "<set name='{$date} {$month}' value='{$n}' color='3b5988' />";
	if($date == 1) { $date = $last;
		if($month == "Jan") { $month = $previous; $year = $year - 1; }
		else { $month = $previous; }
	} else { $date = $date - 1; if($date < 10) { $date = "0".$date; } }
	$x = $x + $n;
	} // End the loop after 7 days data
	$data_1 .= "</graph>";
	if($x == 0) { $data_1 = "<graph></graph>"; }
	echo $data_1;
	} // File Uploads data end over here

	if($t == 'bandwidth') {
	$data_2 = "<graph caption='Bandwidth Usage on FileBox' xAxisName='7 days Report' yAxisName='Bandwidth Usage (MB)' decimalPrecision='0' formatNumberScale='0'>";
	$x = 0; for($i=0; $i < 7; $i++) {
	$q = mysqli_query("SELECT `file_size` FROM `downloads` WHERE(`date`='{$date} {$month} {$year}') AND (`owner_id`={$userid})") or die(mysqli_error());
	if(mysqli_num_rows($q)) { $n = 0;
	while($row = mysqli_fetch_array($q)) {
		$n = $n + $row['file_size'];
	}
	if($n != 0) { $n = ceil($n / 1024); $x = $x + $n;}
	} else { $n = 0; }
    $data_2 .= "<set name='{$date} {$month}' value='{$n}' color='3b5988' />";
	if($date == 1) { $date = $last;
		if($month == "Jan") { $month = $previous; $year = $year - 1; }
		else { $month = $previous; }
	} else { $date = $date - 1; if($date < 10) { $date = "0".$date; } }
	} // End the loop after 7 days data
	$data_2 .= "</graph>";
	if($x == 0) { $data_2 = "<graph></graph>"; }
	echo $data_2;	
	}
	
	} // $_GET['t'] ends over ehre
	} // Sessions ends over here
	
?>