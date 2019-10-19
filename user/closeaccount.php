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
include("header.php");
subheader('Close Account');

if ($sesslife == true) {
    echo "<center><div class='logindiv'><div id='logindiv-header'><p>Account Closure - Final Step</p></div><br/>";
    if (isset($_POST['confirmdelete'])) {
        $sql = mysqli_query($link, "DELETE FROM `files` WHERE(`userid`={$userid})") or die(mysqli_error($link));
        $sql_2 = mysqli_query($link, "DELETE FROM `members` WHERE(`id`={$userid})") or die(mysqli_error($link));
        $sql_3 = mysqli_query($link, "DELETE FROM `transactions` WHERE(`userid`={$userid})") or die(mysqli_error($link));
        $session->stop();
        echo "<meta http-equiv='Refresh' Content='1;URL={$website}/' />";
    } else {
        echo "<form method='POST' action='{$website}/user/closeaccount.php'><p id='forall'>You are about to close your account on {$webtitle}. Click on the button below will <b>log you out</b> of the website, remove all your <b>information</b> & <b>files</b> from our servers and it is an irreversible process. So think twice before you proceed.</p><br/><input type='submit' class='button' name='confirmdelete' value='I confirm my account deletion' /></form>";
    }
    echo "<br/></div></center>";
} else {
    $err = "<div class='infobox'>Not Logged Version<br/><small>You does not seem to be logged in to the website. Please login in order to view this page.</small></div>";
    ft_showLogin();
}

include("footer.php");

?>