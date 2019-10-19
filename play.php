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
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<script type="text/javascript" src="<?php echo $website; ?>/js/flowplayer-3.2.4.min.js"></script>
<title>Video Player</title>
</head>
<body>
<?php if(isset($_GET["id"])) {
			$id = htmlspecialchars(trim($_GET["id"]));
			$q = mysqli_query($link, "SELECT * FROM `files` WHERE(`id`={$id})") or die(mysqli_error($link));
				if(mysqli_num_rows($q)) {
					$f = mysqli_fetch_array($q);
					$ext = $f['extension'];
						if($ext == "flv") {
							$location = $f['location'];
?>
<a href="<?php echo $location; ?>" style="display:block;width:520px;height:330px" id="player"></a>
<script>flowplayer("player", "<?php echo $website; ?>/modules/video/flowplayer-3.2.5.swf");</script>
<?php } } } ?>
</body>
</html>