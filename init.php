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
	
	/* Error reporting for the application. Set to error_reporting(E_ALL) for debugging. */
	error_reporting(0);
	
	/* Set the default timezone for the script. */
	date_default_timezone_set('America/Sao_Paulo');
	
	@ini_set('display_errors', '0');
	@ini_set('memory_limit', '128M');
	
	/* Database inclusion and initialization for the application */
	include("user/database.php");
	$link = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD);
	if(!mysqli_select_db($link, DB_NAME)) die("<br/><center><h1 style='font-size:15px;font-weight:100;font-family:arial;'><b>FileBox</b> does not seem to be installed. Please run Install wizard for the same.</h1></center>");

	/* Initializing sessions for the application. Uses database for session storage which means more security
	and prevention from session hijacking attacks */
	include("modules/class.dbsession.php");
	$session = new dbsession($link);

	/* Inclusion of the classes which form the base of the application */
	include("user/loadsettings.inc.php");
	include("user/functions.php");
	include("user/session.inc.php");
	include("modules/pagination.class.php");
	include("languages/english.php");
	
	/* Setting the max upload filesize upto what is allowed for the premium members.
	This will allow for uploading larger files without timeout issues. */
	
	$ini_max_value = $premium_maxFile."M";
	@ini_set('post_max_size', $ini_max_value);
	@ini_set('upload_max_filesize', $ini_max_value);

?>