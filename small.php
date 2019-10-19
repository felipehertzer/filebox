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

$str = file_get_contents("user/database.php");
$str = str_replace("<?php", "", $str);
$str = str_replace("?>", "", $str);

eval($str);

if (isset($_GET["id"])) {
    $id = htmlspecialchars(trim($_GET["id"]));
} else {
    showUnknown();
}

if (isset($_GET["big"])) {
    $big = htmlspecialchars(trim($_GET["big"]));
} else {
    $big = "";
}

if ($id == "") {
    showUnknown();
} else {
    $link = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD);
    if (!mysqli_select_db($link, DB_NAME)) die(mysqli_error($link));

    $q = "SELECT `location`, `small_location` FROM `files` WHERE (`id`={$id})";
    if (!($result_set = mysqli_query($link, $q))) die(mysqli_error($link));
    $number = mysqli_num_rows($result_set);

    if ($number) {
        $row = mysqli_fetch_array($result_set);
        if ($big == "1") {
            if (file_exists($row['location'])) {
                header("Content-type: image/jpeg");
                readFile($row['location']);
            } else {
                header("Content-type: image/png");
                readFile("images/file_error.png");
            }
        } else { // If small thumb needs to be displayed.
            if (file_exists($row['small_location'])) {
                header("Content-type: image/jpeg");
                readFile($row['small_location']);
            } else {
                header("Content-type: image/png");
                readFile("images/smallfile_error.png");
            }
        }
    } else {
        showUnknown();
    }
}

function showUnknown()
{
    header("Content-type: image/png");
    readFile("images/labels/default.png");
}

?>