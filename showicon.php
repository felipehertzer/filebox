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
  
    if(isset($_GET["id"])) { $id = htmlspecialchars(trim($_GET["id"])); }
    else { showUnknown(); }
	
	if(isset($_GET["big"])) { $big = htmlspecialchars(trim($_GET["big"])); } else { $big = ""; }
	
	if($id == "") { showUnknown(); }
	else { 
	if($big == "1") { $show = "images/labels/big/".$id.".png"; }
	else { $show = "images/labels/".$id.".png"; }
	
	if(file_exists($show))
    {
      header("Content-type: image/png");  
      readFile($show);
    }
    else
      showUnknown();
	}
	
   function showUnknown() { global $big;
      header("Content-type: image/png"); 
	  if($big == "1") { readFile("images/labels/big/default.png"); }
	  else { readFile("images/labels/default.png"); }
   }

?>