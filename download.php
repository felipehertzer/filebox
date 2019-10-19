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
 include("modules/charts/FusionCharts.php");
 if(isset($_GET['id'])) { $id = htmlspecialchars(trim($_GET['id'])); 
 $q = mysqli_query($link, "SELECT `name`, `location`, `extension` FROM `files` WHERE(`id`={$id})") or die(mysqli_error($link));
 if(mysqli_num_rows($q)) { $f = mysqli_fetch_array($q); $title = $f['name']; $tmp_extension = $f['extension']; $tmp_location = $f['location']; } else { $title = "File not found"; $tmp_extension = ""; }
 } else { $title = "Invalid page request"; }
 
 include("user/header.php"); $css = "<link type='text/css' rel='stylesheet' href='{$website}/css/colorbox.css' />";
 $js = "<script type=\"text/javascript\" src=\"{$website}/js/jquery.colorbox.js\"></script>
 <script>$(document).ready(function(){
 $(\"#emailFile\").colorbox({width:\"650px\", height:\"350px\", iframe:true, opacity:0.3});
 $(\"#fileStats\").colorbox({width:\"450px\", height:\"380px\", inline:true, href:\"#fileData\", opacity:0.3});
 $(\"#playmovie\").colorbox({width:\"580px\", height:\"420px\", iframe:true, opacity:0.3});
 });
 </script>";
 
 if($tmp_extension == "mp3") {
 $js .= "<script type='text/javascript' src='{$website}/js/swfobject.js'></script>
		 <script type='text/javascript'>

			var cacheBuster = \"?t=\" + Date.parse(new Date());					

			var stageW = 251;
			var stageH = 78;
			
			
			// ATTRIBUTES
		    var attributes = {};
		    attributes.id = 'mp3player';
		    attributes.name = attributes.id;
		    
			// PARAMS
			var params = {};
			params.wmode = \"transparent\";
			params.allowfullscreen = \"true\";
			params.allowScriptAccess = \"always\";			
			params.bgcolor = \"#ffffff\";
			

		    /* FLASH VARS */
			var flashvars = {};				
			
			flashvars.componentWidth = stageW;
			flashvars.componentHeight = stageH;
			
			flashvars.pathToFiles = \"\";
			flashvars.xmlPath = \"modules/player/xml/settings.xml\";
			
			// other vars
			flashvars.artistName = \" \";
			flashvars.songName = \" \";
			flashvars.songURL = \"{$tmp_location}\";
			swfobject.embedSWF(\"modules/player/preview.swf\"+cacheBuster, attributes.id, stageW, stageH, \"9.0.124\", \"modules/player/expressInstall.swf\", flashvars, params);
		</script>";
 }
 subheader($title, $css, $js, 'download'); ?>
 <script>
 <?php if($sesslife == true) {
 if($premium == 1) {
 echo "var limit=\"{$premium_timer}\";";
 } else { echo "var limit=\"{$normal_timer}\";"; }
 } else { echo "var limit=\"{$anon_timer}\";"; }
 ?>
 if(document.images){
 var parselimit=limit.split(":")
 parselimit=parselimit[0]*60+parselimit[1]*1
 }
 function begintimer(){
 document.getElementById('downloadbtn').style.display = 'none';
 document.getElementById('secondtxt').style.display = 'block';
 if(!document.images)
 return
 if(parselimit==1) {
 document.getElementById('secondtxt').style.display = 'none';
 <?php if(isset($_GET['id'])) { ?>
 window.location='<?php echo $website; ?>/fetch.php?id=<?php echo $id; ?>'; 
 <?php } ?>
 }
 else { 
 parselimit-=1
 curmin=Math.floor(parselimit/60)
 cursec=parselimit%60
 if (curmin!=0)
 curtime=curmin+" minutes and "+cursec+" seconds to go"
 else
 curtime=cursec+" seconds to go"
 document.getElementById('secondtxt').value=curtime
 setTimeout("begintimer()",1000)
 }
 }
 </script>
 
<?php echo "<div class='global'>";
 if(isset($_GET['id'])) { $id = htmlspecialchars(trim($_GET['id']));
 $d_query = mysqli_query($link, "SELECT * FROM `files` WHERE(`id`={$id})") or die(mysqli_error($link));
 if(mysqli_num_rows($d_query)) { $f_query = mysqli_fetch_array($d_query); 
 if($f_query['is_folder'] != 1) { ?> 
 
 
 <div class='infobox'><p>Download - <b><?php echo $title; ?></b></p></div>
 <div id='main'><div id='downleft'>
 <div id='downext'>
 <?php $ext = $f_query['extension'];
 
 if(($ext == "gif") || ($ext == "jpg") || ($ext == "jpeg") || 
	($ext == "png") || ($ext == "bmp") || ($ext == "pjpeg")) {
	
	$image = imagecreatefromunknown($f_query['location'], $ext);
    $mainWidth = imagesx($image);
    $mainHeight = imagesy($image);
              
     if($mainWidth > 250)
     { 
		$divider = $mainWidth / 250;
        $mainWidth = intval($mainWidth / $divider);
		$mainHeight = intval($mainHeight / $divider);
	 }
 if(!file_exists($f_query['location'])) { $f_query['location'] = "images/file_error.png"; }	 
	 echo "<img src='{$website}/{$f_query['location']}' width='{$mainWidth}' height='{$mainHeight}' />";
 
 }
 else { echo "<div id='mp3player'></div><img src='{$website}/showicon.php?id={$ext}&big=1' width='250px' height='250px' />"; } ?>
 
 </div>
 </div>
 <div id='downright'>
 
 <div class='suboptions'>
 <ul><li id='multi'><a href='<?php echo $website; ?>/user/upload.php'>Upload</a></li>
 <?php if(($ext == "gif") || ($ext == "jpg") || ($ext == "jpeg") || 
	($ext == "png") || ($ext == "bmp") || ($ext == "pjpeg")) { ?>
 <li id='bigger'><a href='<?php echo $website."/".$f_query['location']; ?>' target='_blank'>View Big</a></li>
 <?php } ?>
 <li id='download'><a href='#' onClick='begintimer();'>Download</a></li>
 <li id='email'><a href='<?php echo $website; ?>/email.php?id=<?php echo $id; ?>' id='emailFile'>Email File</a></li>
 <?php if($ext == "flv") { ?>
 <li id='play'><a href='<?php echo $website; ?>/play.php?id=<?php echo $id; ?>' id='playmovie'>Play</a></li>
 <?php } ?>
 <li id='stats'><a href='#' id='fileStats' href='<?php echo $website; ?>/filestatistics.php?id=<?php echo $id; ?>'>Statistics</a></li>
 </div>
 
 <?php $views = $f_query['views'] + 1; $views_update = mysqli_query($link, "UPDATE `files` SET `views`={$views} WHERE `id`={$id}") or die(mysqli_error($link)); ?>
 
 <div id='filedetails'>
 <div id='filedetailsheader'><p>File Information</p></div>
 <table>
 <tr><td class='first'>Name</td><td><?php echo $f_query['name']; ?></td></tr>
 <tr><td class='first'>Size</td><td><?php echo $f_query['size']; ?> KB</td></tr>
 <tr><td class='first'>Views</td><td><?php echo $views; ?></td></tr>
 <tr><td class='first'>Downloads</td><td><?php echo $f_query['downloads']; ?></td></tr>
 <tr><td class='first'>Uploaded</td><td><?php echo $f_query['date']; ?></td></tr>
 </table>
 
 <div id='downloadfile'><input type='button' name='downloadbtn' value='Download File' class='button' id='downloadbtn' onClick='begintimer();' />
 <center><input type='text' name='secondtxt' value='' id='secondtxt' disabled='disabled' /></center></div>
 </div>
 
 </div>
 </div> 
 
 <?php 
 
 echo "<div style='display:none;'><div id='fileData'>"; echo renderChartHTML("modules/charts/FCF_Line.swf", "filestatistics.php?id={$id}", "", "myFirst", 400, 300);
 echo "</div></div>";
 
 } else { echo "<meta http-equiv='Refresh' Content='0;URL={$website}/user/folder.php?id={$id}' />"; }
 } else { echo "<div class='infobox'><p><b>File not found</b></p></div><br/><br/><img src='{$website}/images/icons/exclamation.png' style='float:left;margin:8px 10px 10px 10px;' /><p class='smooth'>The file you are looking for has been removed either due to inactivity or for being uploaded illegaly. If you own this file and can provide proof for that then contact our customer support for the same.</p>
 <div class='options'><ul><li id='basic'><a href='{$website}/user/upload.php'>Basic Upload</a></li><li id='multi'><a href='{$website}/user/upload.php?v=multiupload'>Multi Upload</a></li><li id='dashboard'><a href='{$website}/'>Dashboard</a></li><li id='files'><a href='{$website}/user/myfiles.php'>My Files</a></li><li id='support'><a href='{$website}/static/support.php'>Contact Support</a></li></ul></div>"; }
 } else { echo "<div class='infobox'><p><b>Invalid page request</b></p></div><br/><br/><img src='{$website}/images/icons/exclamation.png' style='float:left;margin:8px 10px 10px 10px;' /><p class='smooth'>You are making a page request which is not complete. Please verify the source and try visiting the correct link. If you need any assistance then get in touch with our <span id='more'>support team</span>.</p>
 <div class='options'><ul><li id='basic'><a href='{$website}/user/upload.php'>Basic Upload</a></li><li id='multi'><a href='{$website}/user/upload.php?v=multiupload'>Multi Upload</a></li><li id='dashboard'><a href='{$website}/'>Dashboard</a></li><li id='files'><a href='{$website}/user/myfiles.php'>My Files</a></li><li id='support'><a href='{$website}/static/support.php'>Contact Support</a></li></ul></div>"; } ?>
 
 </div>
 
<?php include("user/footer.php"); ?>