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
 include("../modules/charts/FusionCharts.php");
 
 if(isset($_GET['v'])) { $v = htmlspecialchars(trim($_GET['v']));
 if($sesslife == true) {
	
	if($v == 'info') {	
		echo "<div id='account-header'><p>User Information</p></div><br/><small class='margin'>Information related to your account on {$webtitle} is displayed over here. To edit them just click on the info you want to change.</small>";
		ft_showInfo($userid);
		}
		
	if($v == 'close') {	
		echo "<div id='account-header'><p>Close Account</p></div>";
		echo "<form method='POST' action='{$website}/user/closeaccount.php'><table id='userinfo'><tr><td>You are about to delete your account on <b>{$webtitle}</b>. Proceeding with this will delete all your information & files we have with us and you wont be able to recover that.</td></tr><tr><td><b>Are you sure you want to do this?</b></td></tr>
		<tr><td><input type='submit' name='deleteaccount' class='button' value='Close Account' /></td></tr></table></form>";
		}
		
	if($v == 'create') {
		echo "<div class='onlyborder'>";
		$sql_fq = mysqli_query("SELECT * FROM `files` WHERE(`userid`={$userid}) AND (`is_folder`=1)") or die(mysqli_error());
		echo "<center><table><tr><td id='first'>Create New Folder: </td><td><input type='text' id='foldername' name='foldername' /></td><td><b>under</b></td>";
		
		if(mysqli_num_rows($sql_fq)) { echo "<td><select id='folders' name='folders'>"; echo "<option value='0'>My Files</option>";
		while($sql_fetch = mysqli_fetch_array($sql_fq)) { echo "<option value='{$sql_fetch['id']}'>{$sql_fetch['name']}</option>"; }
		echo "</select></td>"; }
			else { echo "<td><select id='folders' name='folders'><option value='0'>My Files</option></select></td>"; }
		echo "<td><input type='submit' name='folderc' class='button' value='Create Folder' /></td></tr></table></center></div>";
			}
		
	if($v == 'move') {
		echo "<div class='onlyborder'>";
		$sql_fq = mysqli_query("SELECT * FROM `files` WHERE(`userid`={$userid}) AND (`is_folder`=1)") or die(mysqli_error());
		echo "<center><table><tr><td id='first'>Move selected files to folder: </td>";
		if(mysqli_num_rows($sql_fq)) { echo "<td><select id='folders' name='folders'>"; echo "<option value='0'>My Files</option>";
		while($sql_fetch = mysqli_fetch_array($sql_fq)) { echo "<option value='{$sql_fetch['id']}'>{$sql_fetch['name']}</option>";
		}
		echo "</select></td>"; }
			else { echo "<td><select id='folders' name='folders'><option value='0'>My Files</option></select></td>"; }
		echo "<td><input type='submit' name='movefiles' class='button' value='Move Files' /></td></tr></table></center></div>";
	}
	
	if($v == 'delete') {
		echo "<div class='onlyborder'>";
		echo "<center><table><tr><td>Are you sure you want to delete these files?</td><td><input type='submit' name='deletefiles' class='button' value='Delete Files' /></td></tr></table></center></div>";
	}
	
	if($v == 'public') {
		echo "<div class='onlyborder'>";
		echo "<center><table><tr><td>Mark selected files as Public?</td><td><input type='submit' name='markpublic' class='button' value='Mark Public' /></td></tr></table></center></div>";
	}
	
	if($v == 'private') {
		echo "<div class='onlyborder'>";
		echo "<center><table><tr><td>Secure selected files?</td><td><input type='submit' name='markprivate' class='button' value='Secure Files' /></td></tr></table></center></div>";
	}
	
	if($v == 'fileChart') {
	echo "<table id='charts'><tr><td class='first'>";
	echo renderChartHTML("modules/charts/FCF_Column3D.swf", "data.php?t=fileuploads", "", "myFirst", 400, 300);
	echo "</td><td class='second'><div class='suboptions'><p class='header'>{$webtitle} Activity</p>
	<p class='impInfo'><span class='head'>Total Files</span>: "; user_files($userid); echo "</p>
	<p class='impInfo'><span class='head'>Disk Space Used</span>: "; user_space($userid); echo " MB <small>(Approx.)</small></p></div></td></tr></table>";
	}
	
	if($v == 'bandwidth') {
	echo "<table id='charts'><tr><td class='first'>";
	echo renderChartHTML("modules/charts/FCF_Line.swf", "data.php?t=bandwidth", "", "myFirst", 400, 300);
	echo "</td><td class='second'><div class='suboptions'><p class='header'>Bandwidth Usage</p>
	<p class='impInfo'><span class='head'>Bandwidth Used Today</span>: "; user_bandwidth($userid); echo " MB</p>
	<p class='impInfo'><span class='head'>Total Bandwidth Consumption</span>: "; total_bandwidth($userid); echo " MB <small>(Approx.)</small></p>
	<p class='impInfo'><span class='head'>File Downloads</span>: "; file_downloads($userid); echo "</p></div></td></tr></table>";
	}
	
	if($v == 'membership') {
	echo "<div id='account-header'><p>Membership Details</p></div>";
	$q = mysqli_query("SELECT * FROM `transactions` WHERE(`userid`={$userid}) ORDER BY `transaction_id` DESC") or die(mysqli_error());
	if(mysqli_num_rows($q)) {
	echo "<div id='transaction-header'><table class='transactions' style='margin:0;'><tr><td>Transaction ID</td><td>Amount</td><td>Started</td><td>Expiry Date</td><td>Status</td><td></td></tr></table></div>";
		while($row = mysqli_fetch_array($q)) {
			if($row['expires_on'] < time()) { $status = "Expired"; } else { $status = "Active"; }
			echo "<table class='transactions'><tr><td>{$row['transaction_id']}</td><td>USD {$row['amount']}.00</td><td>".date('d M Y H:i', $row['date'])."</td><td>".date('d M Y H:i', $row['expires_on'])."</td><td>{$status}</td><td></td></tr></table>";
		}
	} else {
	echo "<div class='errorbox'><p>No premium membership details found.</p></div><br/><p class='margin'>You have never been a premium member on <span id='more'>{$webtitle}</span>. Premium membership offers a lot of benefits and useful features. Go ahead and become a premium member by clicking <a href='{$website}/user/upgrade.php'>over here</a>.</p>";
	}
	}

	if($v == 'points') {
	echo "<div id='account-header'><p>Activity Points</p></div>";
	$q = mysqli_query("SELECT * FROM `points` WHERE(`userid`={$userid}) ORDER BY `id` DESC") or die(mysqli_error());
	if(mysqli_num_rows($q)) {
	echo "<div id='transaction-header'><table class='transactions' style='margin:0;'><tr><td>Points ID</td><td>Total Points</td><td>Period</td><td>File Downloads</td><td>Last Download</td></tr></table></div>";
		while($row = mysqli_fetch_array($q)) {
			point_filedownloads($row['month'], $row['year'], $userid);
			echo "<table class='transactions'><tr><td>{$row['id']}</td><td>{$row['points']}</td><td>{$row['month']} {$row['year']}</td><td>{$month_total}</td><td>{$row['last_action']}</td></tr></table>";
		}
	} else {
	echo "<div class='errorbox'><p>You have zero(0) activity points.</p></div><br/><p class='margin'>You have no points whatsoever till now. You need to be more active on <span id='more'>{$webtitle}</span> by uploading and sharing files. You get points whenever your file gets downloaded by someone. You can start uploading files by clicking <a href='{$website}/user/upload.php'>over here</a>.</p>";
	}
	}
		
	} // Session ends over here
	
	if($v == 'bulk') {
		echo "<div class='onlyborder'>";
		echo "<center><table><tr><td>Download selected files via single zip file?</td><td><input type='submit' name='bulkdownload' class='button' value='Start Download' /></td></tr></table></center></div>";
	}
 } // End of $_GET['v']
 
?>