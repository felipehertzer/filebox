
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

/* Ajax for account settings */
 
function userInfo() {
  $('#main').html('<center><p id="loading"><img src="../images/working.gif" /></p></center>');
  $('#settings').removeClass(); $('#deleteaccount').removeClass(); $('#userinfo').addClass("selected");
  $('#main').load("content.php?v=info");
}

function userSettings() {
  $('#main').html('<center><p id="loading"><img src="../images/working.gif" /></p></center>');
  $('#userinfo').removeClass(); $('#deleteaccount').removeClass(); $('#settings').addClass("selected");
  $('#main').load("examples/_content.html");
}

function userClose() {
  $('#main').html('<center><p id="loading"><img src="../images/working.gif" /></p></center>');
  $('#userinfo').removeClass(); $('#settings').removeClass(); $('#deleteaccount').addClass("selected");
  $('#main').load("content.php?v=close");
}

/* Ends over here */

/* Ajax for the index page - dashboard version */
 
function userActivity() {
  $('#main').html('<center><p id="loading"><img src="images/working.gif" /></p></center>');
  $('#bandwidth').removeClass(); $('#premium').removeClass(); $('#points').removeClass();
  $('#graph').addClass("selected");
  $('#main').load("user/content.php?v=fileChart");
}

function userBandwidth() {
  $('#main').html('<center><p id="loading"><img src="images/working.gif" /></p></center>');
  $('#graph').removeClass(); $('#premium').removeClass(); $('#points').removeClass();
  $('#bandwidth').addClass("selected");
  $('#main').load("user/content.php?v=bandwidth");
}

function userMembership() {
  $('#main').html('<center><p id="loading"><img src="images/working.gif" /></p></center>');
  $('#bandwidth').removeClass(); $('#graph').removeClass(); $('#points').removeClass();
  $('#premium').addClass("selected");
  $('#main').load("user/content.php?v=membership");
}

function userPoints() {
  $('#main').html('<center><p id="loading"><img src="images/working.gif" /></p></center>');
  $('#bandwidth').removeClass(); $('#graph').removeClass(); $('#premium').removeClass(); 
  $('#points').addClass("selected");
  $('#main').load("user/content.php?v=points");
}

/* Ends over here */


/* Ajax for my files */

function createFolder() {
	$('#ajax').html('<center><p id="loading"><img src="../images/working.gif" /></p></center>');
	$('#movedoc').removeClass(); $('#delete').removeClass(); $('#bulk').removeClass(); $('#public').removeClass(); $('#secure').removeClass();
 	$('#folder').addClass("selected");
	$('#ajax').load("content.php?v=create");
}	

function moveFiles() {
	$('#ajax').html('<center><p id="loading"><img src="../images/working.gif" /></p></center>');
	$('#folder').removeClass(); $('#delete').removeClass(); $('#bulk').removeClass(); $('#public').removeClass(); $('#secure').removeClass();
	$('#movedoc').addClass("selected");
	$('#ajax').load("content.php?v=move");
}

function deleteFiles() {
	$('#ajax').html('<center><p id="loading"><img src="../images/working.gif" /></p></center>');
	$('#movedoc').removeClass(); $('#folder').removeClass(); $('#bulk').removeClass(); $('#public').removeClass(); $('#secure').removeClass();
	$('#delete').addClass("selected");
	$('#ajax').load("content.php?v=delete");
}

function bulkFiles() {
	$('#ajax').html('<center><p id="loading"><img src="../images/working.gif" /></p></center>');
	$('#movedoc').removeClass(); $('#folder').removeClass(); $('#delete').removeClass(); $('#public').removeClass(); $('#secure').removeClass();
	$('#bulk').addClass("selected");
	$('#ajax').load("content.php?v=bulk");
}

function publicFiles() {
	$('#ajax').html('<center><p id="loading"><img src="../images/working.gif" /></p></center>');
	$('#movedoc').removeClass(); $('#folder').removeClass(); $('#delete').removeClass(); $('#bulk').removeClass(); $('#secure').removeClass();
	$('#public').addClass("selected");
	$('#ajax').load("content.php?v=public");
}

function privateFiles() {
	$('#ajax').html('<center><p id="loading"><img src="../images/working.gif" /></p></center>');
	$('#movedoc').removeClass(); $('#folder').removeClass(); $('#delete').removeClass(); $('#bulk').removeClass(); $('#public').removeClass();
	$('#secure').addClass("selected");
	$('#ajax').load("content.php?v=private");
}

/* Ends over here */

function val(x) { if(x.checked == 1) { return true; }
  else { alert("You must agree to our TOS before you upload on the website."); return false; }
}

function anyCheck(form) {
var total = 0;
var max = form.cbox.length;
for (var idx = 0; idx < max; idx++) {
if (eval("document.playlist.cbox[" + idx + "].checked") == true) {
    total += 1;
   }
}
alert("You selected " + total + " boxes.");
}

function showItems(value) { var value; var change = "show"+value; var txt = "text"+value;
document.getElementById(change).style.display = 'block';
document.getElementById(txt).style.display = 'inline';
}

function hideItems(value) { var value; var change = "show"+value; var txt = "text"+value;
document.getElementById(change).style.display = 'none';
document.getElementById(txt).style.display = 'none';
}

function highlight(checkbox) {
   if (document.getElementById) {
      var tr = eval("document.getElementById(\"tr" + checkbox.value + "\")");
   } else {
      return;
   }
   if (tr.style) {
      if (checkbox.checked) {
         tr.style.backgroundColor = "#7dbee6";
      } else {
         tr.style.backgroundColor = "#fdfdfd";
      }
   }
}