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

$sesslife = false;
$userid = 0;
$premium = 0;
/* This page checks and validates whether the current session user is logged in to
the website or not */
var_dump($_SESSION);
function createRandomPassword()
{ /* For generating random passwords */
    $chars = "abcdefghijkmnopqrstuvwxyz023456789";
    srand((double)microtime() * 1000000);
    $i = 0;
    $pass = '';
    while ($i <= 7) {
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }
    return $pass;
}

/* The following function is to check whether a user has active facebook connection
or not and also to see whether he has given access to his graph api for the website */

$cookie = get_facebook_cookie(FACEBOOK_APP_ID, FACEBOOK_SECRET);

if ($cookie) {
    $access_date = date("d M Y");
    $sesslife = true;
    $temp_fb_id = $cookie['uid'];
    $username = json_decode(file_get_contents('https://graph.facebook.com/me?access_token=' . $cookie['access_token']))->name;
    $fb_email = json_decode(file_get_contents('https://graph.facebook.com/me?access_token=' . $cookie['access_token']))->email;
    /* Query the database and check whether the user information exists or not and if not
    then add the user information to the database with facebook ID */

    $fb_query = mysqli_query($link, "SELECT * FROM `members` WHERE(`email`='{$fb_email}')") or die(mysqli_error($link));

    if (mysqli_num_rows($fb_query)) {
        $fb_fetch = mysqli_fetch_array($fb_query);
        $userid = $fb_fetch['id'];
        $full_name = $fb_fetch['name'];
        $premium = $fb_fetch['premium'];
        $username = $fb_fetch['email'];
        $fb_update = mysqli_query($link, "UPDATE `members` SET `access`='{$access_date}', `fb_id`={$temp_fb_id} WHERE(`id`={$userid})") or die(mysqli_error($link));
    } else {
        /* Creating a random key for the user */
        $temp_r = rand();
        $temp_r_2 = rand();
        $temp_r_3 = rand();
        $temp_t = time();
        $temp_key = md5($temp_r . "_" . $temp_r_2 . "_" . $temp_r_3 . "_" . $temp_t);
        /* $key is the final highly random key */

        $temp_password = createRandomPassword();
        $fb_insert = mysqli_query($link, "INSERT INTO `members`(`name`, `password`, `email`, `key`, `join`, `access`, `fb_id`) VALUES('{$username}', '{$temp_password}', '{$fb_email}', '{$temp_key}', '{$access_date}', '{$access_date}', {$temp_fb_id})") or die(mysqli_error($link));

    }
} elseif (isset($_SESSION["user"])) {
    $sesslife = true;

    /* Validation in the database using the following two credentials */
    $email = $_SESSION["user"];
    $password = $_SESSION["pass"];

    $q = "SELECT * FROM `members` WHERE (email = '{$email}') and (password = '{$password}')";
    if (!($result_set = mysqli_query($link, $q))) {
        die(mysqli_error($link));
    }
    $n1 = mysqli_num_rows($result_set);

    if (!$n1) {
        $session->stop();
        $sesslife = false;
        $userid = 0;
    } else {
        $r = mysqli_fetch_array($result_set);

        /* The following information is fetched from the database for the current user */
        $userid = $r['id'];
        $userpass = $r['password'];
        $username = $r['email'];
        $premium = $r['premium'];
        $full_name = $r['name'];
    }
} else {
    /* The user is not logged in. Show the visitor page to him. */
    $sesslife = false;
    $userid = 0;
}

?>