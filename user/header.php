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
	
	function subheader($title, $css="", $js="", $cl="")
	{
	
		global $webtitle;
		global $website;
		global $lang;
		global $sesslife;
		global $username;
		global $description;
		global $keywords;
		global $premium;
		global $userid;
	
	/* Checking the user premium membership status and if found that the membership has
	expired then degrade the user as normal member and send him a membership expiry mail */
	
	if($sesslife == true) {
		if($premium == 1) {
			$current_time = time();
			$query_time = mysqli_query("SELECT * FROM `transactions` WHERE(`userid`={$userid}) ORDER BY `transaction_id` DESC LIMIT 1") or die(mysqli_error());
			if(mysqli_num_rows($query_time)) {
				$fetch_time = mysqli_fetch_array($query_time);
				if($fetch_time['expires_on'] < $current_time) {
					$update_membership = mysqli_query("UPDATE `members` SET `premium`=0 WHERE(`id`={$userid})") or die(mysqli_error());
					// Function for sending mail to the user will come over here.
					$premium = 0;
				}
			} else { $premium = 0; }
		}
	}
	?>
	
	<!DOCTYPE html>
	<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
	<head>
	<title><?php echo $webtitle; ?> - <?php echo $title; ?></title>
	<META NAME="Description" CONTENT="<?php echo $description; ?>">
	<META NAME="Keywords" CONTENT="<?php echo $keywords; ?>">
	<META NAME="Author" CONTENT="Akshit Sethi">
	<link type="text/css" rel="stylesheet" href="<?php echo $website; ?>/css/default.css" />
	<?php echo $css; // Additional CSS files for specific pages ?>
	<script type="text/javascript" src="<?php echo $website; ?>/js/core-base.js"></script>
	<script type="text/javascript" src="<?php echo $website; ?>/js/jquery.min.js"></script>
	<?php echo $js; // Additional JS files for specific pages ?>
	</head>
	<body>
	<div id="fb-root"></div>
    <script src="http://connect.facebook.net/en_US/all.js"></script>
    <script>
	window.fbAsyncInit = function() {
    FB.init({
      appId  : 'f8c226475ed8a00773a0f44083ed9443',
      status : true, // check login status
      cookie : true, // enable cookies to allow the server to access the session
      xfbml  : true  // parse XFBML
    });
	};

	(function() {
    var e = document.createElement('script');
    e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
    e.async = true;
    document.getElementById('fb-root').appendChild(e);
	}());
	</script>
	<script>
          //your fb login function
          function fblogin() {
            FB.login(function(response) {
			window.location.reload();
            }, {perms:'email'});
          }
    </script>
	<center><div class="container">
	<div id="header">
	<div id="limitheader">
	<a href="<?php echo $website; ?>"><img src="<?php echo $website; ?>/images/logo.png" id="logo" /></a>
	<div id="subheader"><ul class="header">

	<?php
	if($sesslife == false)
	{ ?>
		<li<?php if($cl=='home') { echo " class=\"selected\""; }?>><a href="<?php echo $website; ?>/">Home</a></li>
	<?php }
	else
	{ ?>
		<li<?php if($cl=='home') { echo " class=\"selected\""; }?>><a href="<?php echo $website; ?>/">Dashboard</a></li>
	<?php } ?>

		<li<?php if($cl=='files') { echo " class=\"selected\""; }?>><a href="<?php echo $website; ?>/user/myfiles.php">My Files</a></li>
	
	<?php
	if($sesslife == true)
	{ ?>
		<li<?php if($cl=='upload') { echo " class=\"selected\""; }?>><a href="<?php echo $website;?>/user/upload.php">Upload</a></li>
	<?php }
	else
	{ ?>
		<li<?php if($cl=='tour') { echo " class=\"selected\""; }?>><a href="<?php echo $website; ?>/static/tour.php">Tour</a></li>
	<?php } ?>
	
	</ul>
	
	<ul class="rightheader">
	
	<?php if($sesslife == false)
	{ ?>
		<li><a href="#" onClick="fblogin();"><img src="<?php echo $website; ?>/images/fb_connect.gif" style="vertical-align:middle;" /></a></li>
		<li<?php if($cl=='login') { echo " class=\"selected\""; }?>><a href="<?php echo $website; ?>/user/login.php">Login</a></li>
		<li<?php if($cl=='register') { echo " class=\"selected\""; }?>><a href="<?php echo $website; ?>/user/register.php">Register</a></li>
	<?php }
	else
	{ ?>
	<li>Welcome <b><?php echo $username; ?></b>,
	
	<?php
	if($premium != 1)
	{
		echo "<small>(<a href=\"{$website}/user/upgrade.php\" style=\"font-weight:100;\">Go Premium</a>)</small>";
	} ?>
	
	</li>
	<li<?php if($cl=='account') { echo " class=\"selected\""; }?>><a href="<?php echo $website; ?>/user/account.php">Account</a></li>
	<li><a href="<?php echo $website; ?>/user/logout.php">Logout</a></li>
	<?php } ?>
	
	</ul>
	</div>
	</div>
	</div>
	<div class="sessionfalse">
	
<?php } ?>