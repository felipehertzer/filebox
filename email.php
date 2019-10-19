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

include("init.php"); ?>
<html>
<head>
<title><?php echo $webtitle; ?> - Share via Email</title>
<link rel="stylesheet" type="text/css" href="<?php echo $website; ?>/css/default.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $website; ?>/css/fcbk_multi.css" />
<script type="text/javascript" src="<?php echo $website; ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $website; ?>/js/fcbk.min.js"></script>
</head>
<body>
<center>
<?php $err = ""; if(isset($_GET['id'])) { $id = htmlspecialchars(trim($_GET['id'])); 
	  if(isset($_POST['sendEmail'])) {
		$folder_is = htmlspecialchars(trim($_POST['folder']));
	  if(isset($_POST['select1'])) { $select1 = $_POST['select1']; } 
	  else { $select1 = ""; }
		if(!empty($select1)) { $max = count($select1) + 1;
		$headers = "From: ".$adminemail. "\r\n";
		$headers .= "Reply-To: ".$adminemail. "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		if($folder_is == 1) {
		$body = "<h1 style='font-family:\"arial\",sans-serif;font-size:24px;color:#111111;background:#eee;padding:10px;'>{$webtitle} - Folder Share!</h1>
             <p style=\"font-family:\"arial\",sans-serif;padding:0 0 0 10px;font-size:13px;color:#333333;\">Hi, <br/><br/>A folder has been shared with you via <b>{$webtitle}</b> file hosting & sharing network. You can view and download files from the folder by clicking the link below.</p>
			 Link: <a href=\"{$website}/user/folder.php?id={$id}\">{$website}/user/folder.php?id={$id}</a>
             <p style=\"font-family:\"arial\",sans-serif;padding:0 0 0 10px;font-size:13px;color:#333333;\"><a href=\"{$website}\"><b>{$webtitle}</b></a> - File Hosting &amp; Sharing Network</p>";
		$subject = "{$webtitle} - Folder Share!";
		} else {
		$body = "<h1 style='font-family:\"arial\",sans-serif;font-size:24px;color:#111111;background:#eee;padding:10px;'>{$webtitle} - File Share!</h1>
             <p style=\"font-family:\"arial\",sans-serif;padding:0 0 0 10px;font-size:13px;color:#333333;\">Hi, <br/><br/>A file has been shared with you via <b>{$webtitle}</b> file hosting & sharing network. You can view and download the file by clicking the link below.</p>
			 Link: <a href=\"{$website}/download.php?id={$id}\">{$website}/download.php?id={$id}</a>
             <p style=\"font-family:\"arial\",sans-serif;padding:0 0 0 10px;font-size:13px;color:#333333;\"><a href=\"{$website}\"><b>{$webtitle}</b></a> - File Hosting &amp; Sharing Network</p>";
		$subject = "{$webtitle} - File Share!";
		}	 
		for($i=0; $i < $max; $i++) {
			mail($select1[$i], $subject, $body, $headers);
		}
		$err = "<div class='infobox'><p>Your mail has been sent.</p></div>"; emailForm();
		} else { $err = "<div class='errorbox'><p>You must fill in all the fields.</p></div>"; emailForm(); }
	  } else { emailForm(); }
}

function emailForm() { global $website; global $id; global $err;
		$q = mysqli_query($link, "SELECT * FROM `files` WHERE(`id`={$id})") or die(mysqli_error($link));
		if(mysqli_num_rows($q)) { $f = mysqli_fetch_array($q);
		$is_folder = $f['is_folder']; 
		if($is_folder == 1) { $src = "{$website}/images/labels/folder.png"; $type = "Folder"; }
		else { $ext = $f['extension']; 
			if(($ext == "gif") || ($ext == "jpg") || ($ext == "jpeg") || 
			($ext == "png") || ($ext == "bmp") || ($ext == "pjpeg")) {
				$src = "{$website}/small.php?id={$id}"; $type = "Photo"; 
			} else {	
				$src = "{$website}/showicon.php?id={$ext}"; $type = "File";
			}
		}
?>
		<div class='emaildiv' id='email'>
		<div id='emaildiv-header'><p><img src='<?php echo $src; ?>' /> Share <?php echo $type; ?> <small>(Share this <?php echo $type; ?> with all those who matter)</small></p></div>
		<?php echo $err; ?>
		<form action="<?php echo $website; ?>/email.php?id=<?php echo $id; ?>" method="POST" accept-charset="utf-8" id="compose">
		<table id='messagecompose'><tr><td class='first'>To:</td><td><ol>        
        <select id="select1" name="select1"></select>
		</td></tr>
		<tr><td></td><td><input type='hidden' name='folder' value='<?php echo $is_folder; ?>' /><input type='submit' class='button' value='Send' name='sendEmail' /></td></tr>
		</table>
		</form>  
		<script language="JavaScript">
        $(document).ready(function() 
        {        
          $("#select1").fcbkcomplete({
			cache: true,
			filter_case: false,
			filter_hide: true,
			firstselected: false,
			newel: true
		  });
        });
		</script>
		</div>
			
<?php } } ?>

</center>
</body>
</html>