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
 
 include("../init.php"); $upload_q = mysqli_query("SELECT `id` FROM `uploads` ORDER BY `id` DESC LIMIT 1") or die(mysqli_error());
 	 
	 // Maximum file size in megabytes for the uploaded files.
	 if($sesslife == true) {
		if($premium == 1) {
			$maxFileSize = $premium_maxFile;
		}
		else {
			$maxFileSize = $normal_maxFile;
		}
	 } else {
		$maxFileSize = $anon_maxFile;
	 }
	 
	 
  $date = date("d M Y");$ip = $_SERVER['REMOTE_ADDR'];
  if(mysqli_num_rows($upload_q)) { $upload_f = mysqli_fetch_array($upload_q);
  $multicode = $upload_f['id'] + 1;
  $update_mc = mysqli_query("INSERT INTO `uploads`(`date`,`ip_address`,`userid`) VALUES('{$date}', '{$ip}', '{$userid}')") or die(mysqli_error());
  } else { $multicode = 1; $update_mc = mysqli_query("INSERT INTO `uploads`(`date`,`ip_address`,`userid`) VALUES('{$date}', '{$ip}', '{$userid}')") or die(mysqli_error()); }
 $css = "<link type='text/css' rel='stylesheet' href='{$website}/css/plupload.queue.css' />";
 $js = "<script type=\"text/javascript\" src=\"{$website}/js/plupload.full.min.js\"></script>
 <script type=\"text/javascript\" src=\"{$website}/js/jquery.plupload.queue.min.js\"></script>
 <script type=\"text/javascript\" src=\"{$website}/js/plupload.flash.min.js\"></script>
 <script type=\"text/javascript\">
 // Convert divs to queue widgets when the DOM is ready
 $(function() {
	// Setup flash version
	$(\"#flash_uploader\").pluploadQueue({
		// General settings
		runtimes : 'flash',
		url : '../upload.php',
		max_file_size : '{$maxFileSize}mb',
		preinit: attachCallbacks,
		multipart: true,
        multipart_params : {multicode: '{$multicode}', userid: '{$userid}', fid: '0'},
		unique_names : false,

		// Flash settings
		flash_swf_url : '{$website}/modules/uploader/uploader.swf'
	});
	}); 
	
	function attachCallbacks(Uploader) {
	Uploader.bind('FileUploaded', function(Up, File, Response) {
	if( (Uploader.total.uploaded + 1) == Uploader.files.length) {
    document.multiUpload.submit(); }
	}); }
	</script>";
 include("header.php");
 subheader('Upload', $css, $js);
 
 if($sesslife == true) { if(isset($_GET['v'])) { $v = htmlspecialchars(trim($_GET['v'])); } else { $v = ""; }
	
	// Check if the disk space has already been exceeded, then don't allow the uploads.
	disk_usage($userid);
	
	if($percent < 100) {
	/* If the disk space has not been exceeded then allow the uploads via basic as well as
	multi uploader */
	
	if($v == "multiupload") { echo "<div class='global'>"; ?>
	<div class='errorbox'><p>The all new <b>Multi Uploader</b>. Select all files at once and upload all of them with a single click.</p></div>
	<table id='uploader'><tr><td style='vertical-align:top;'><div id="flash_uploader"><p>Your browser does not seem to have Flash installed. Please install Flash Player for your browser to use <b>Multi Uploader</b>.</p></div></td>
	</tr></table>
	<form method="POST" action="<?php echo $website; ?>/process.php" name="multiUpload">
	<input type='hidden' value='<?php echo $multicode; ?>' name='multicode' />
	</form>
	</div>
	
	<div class='sidebar'>
	<a href='<?php echo $website; ?>/user/upload.php'><p id='basic'>Basic Upload</p></a>
	<a href='<?php echo $website; ?>/user/upload.php?v=multiupload'><p id='multi' class='selected'>Multi Upload</p></a>
	</div>
	
	
	<?php } else { ?>
	
	<div class='global'>
	<div class='infobox'><p>You can upload upto 10 files at one go!</p></div>
	<form method="POST" action="<?php echo $website; ?>/process.php" enctype="multipart/form-data" name="uploadForm" onsubmit="return ContentSelected(4);">
	<table id='uploader'><tr><td style='width:319px;'>
	<table id="upload1" class="basic-upload">
    <tr>
    <td><label id="title">01:</label></td> <td> <input type="file" name="file1" size='32'></td>
	</tr>
	</table> 

	<table id="upload2" class="basic-upload">
	<tr>
    <td><label id="title">02:</label></td><td><input type="file" name="file2" size='32'></td>
	</tr>
	</table> 
  
	<table id="upload3" class="basic-upload">
	<tr>
    <td><label id="title">03:</label></td> <td><input type="file" name="file3" size='32'></td>
	</tr>
	</table> 

	<table id="upload4" class="basic-upload">
	<tr>
    <td><label id="title">04:</label></td> <td><input type="file" name="file4" size='32'></td>
	</tr>
	</table>
	
	<table id="upload5" class="basic-upload">
	<tr>
    <td><label id="title">05:</label></td> <td><input type="file" name="file5" size='32'></td>
	</tr>
	</table>
	</td>
	<td style='width:319px;'>
	
	<table id="upload6" class="basic-upload">
	<tr>
    <td><label id="title">06:</label></td> <td><input type="file" name="file6" size='32'></td>
	</tr>
	</table>

	<table id="upload7" class="basic-upload">
	<tr>
    <td><label id="title">07:</label></td> <td><input type="file" name="file7" size='32'></td>
	</tr>
	</table>

	<table id="upload8" class="basic-upload">
	<tr>
    <td><label id="title">08:</label></td> <td><input type="file" name="file8" size='32'></td>
	</tr>
	</table>

	<table id="upload9" class="basic-upload">
	<tr>
    <td><label id="title">09:</label></td> <td><input type="file" name="file9" size='32'></td>
	</tr>
	</table>

	<table id="upload10" class="basic-upload">
	<tr>
    <td><label id="title">10:</label></td> <td><input type="file" name="file10" size='32'></td>
	</tr>
	</table>	
	</td></tr>
	</table>
	<table id='content'>
	<td><a href='#' onClick='document.uploadForm.submit();' class='button'>Upload</a><input type='hidden' name='formUpload' /></td></tr>
	</table>
	</form>
	</div>
	
	<div class='sidebar'>
	<a href='<?php echo $website; ?>/user/upload.php'><p id='basic' class='selected'>Basic Upload</p></a>
	<a href='<?php echo $website; ?>/user/upload.php?v=multiupload'><p id='multi'>Multi Upload</p></a>
	</div>
	
	<?php }
	} else { 
	echo "<div class='global'>"; 
	if($premium != 1) {
		echo "<div class='errorbox'><p><b>Disk Space Full</b><br/><small>You have exceeded the amount of disk space you can use. Please upgrade your account to have more disk space and enjoy more benefits over a regular account.</small></p></div>";
	} else {
		echo "<div class='errorbox'><p><b>Disk Space Full</b><br/><small>You have exceeded the amount of disk space you can use. Please delete few files and free some space for uploading new files.</small></p></div>";
	}
	echo "</div>"; 
	}
	} else { echo "<meta http-equiv='Refresh' Content='0;URL={$website}/user/login.php' />"; }
 
 include("footer.php");
 
?>