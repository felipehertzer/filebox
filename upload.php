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
include("modules/class.upload.php");

$date = date("d M Y");
$ip = $_SERVER['REMOTE_ADDR'];
$zY = date("zY");
$ffolder = "uploads/files/" . $zY;
$pfolder = "uploads/photos/" . $zY;
$sfolder = "uploads/photos/" . $zY . "/small";
$Time = date('YmdHis');

// 5 minutes execution time
@set_time_limit(5 * 60);

// CREATE USER FOLDER IF THEY DON'T EXIST
if (!is_dir($ffolder)) {
    mkdir("uploads/files/{$zY}", 0755);
}
if (!is_dir($pfolder)) {
    mkdir("uploads/photos/{$zY}", 0755);
}
if (!is_dir($sfolder)) {
    mkdir("uploads/photos/{$zY}/small", 0755);
}

// Get parameters
$chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
$chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

$fid = htmlspecialchars(trim($_POST['fid']));
$multicode = htmlspecialchars(trim($_POST['multicode']));
$uid = htmlspecialchars(trim($_POST['userid']));

$handle = new Upload($_FILES["file"]);
if ($handle->uploaded) {
    $extension = $handle->file_src_name_ext;
    $rand_1 = rand(1000, 100000);
    $rand_2 = rand(100000, 100000000);
    $rand_3 = rand();


    // IF UPLOADED FILETYPE IS AN IMAGE, THEN UPLOAD THE FILE AND CREATE THUMBNAILS.
    if (($extension == "gif") || ($extension == "jpg") || ($extension == "jpeg") ||
        ($extension == "png") || ($extension == "bmp") || ($extension == "pjpeg")
    ) {
        $fileName = $Time . '_' . $rand_1 . '_' . $rand_2 . '_' . $rand_3 . '_' . $handle->file_src_name_body;
        $handle->file_new_name_body = $fileName;
        $handle->Process($pfolder);

        $handle->image_resize = true;
        $handle->image_ratio = false;
        $handle->image_y = 48;
        $handle->image_x = 48;
        $handle->file_new_name_body = $fileName;
        $handle->Process($sfolder);

        if ($handle->processed) {
            $fileLocation = $Time . '_' . $rand_1 . '_' . $rand_2 . '_' . $rand_3 . '_' . $handle->file_src_name_body . '.' . $handle->file_src_name_ext;
            $name_file = $handle->file_src_name;
            $location = $pfolder . "/" . $fileLocation;
            $small_location = $sfolder . "/" . $fileLocation;
            $size = ceil($handle->file_src_size / 1024);
            $sql = mysqli_query($link, "INSERT INTO `files`(`name`, `location`, `small_location`, `extension`, `size`, `ip`, `date`, `userid`, `multicode`) VALUES('{$name_file}', '{$location}', '{$small_location}', '{$extension}', '{$size}', '{$ip}', '{$date}', '{$uid}', {$multicode})") or die(mysqli_error($link));
        }
    }
    else { // PROCESS REST OF THE FILES OVER HERE.
        $fileName = $Time . '_' . $rand_1 . '_' . $rand_2 . '_' . $rand_3 . '_' . $handle->file_src_name_body;
        $handle->file_new_name_body = $fileName;
        $handle->Process($ffolder);
        if ($handle->processed) {
            $fileLocation = $Time . '_' . $rand_1 . '_' . $rand_2 . '_' . $rand_3 . '_' . $handle->file_src_name_body . '.' . $handle->file_src_name_ext;
            $location = $ffolder . '/' . $fileLocation;
            $size = ceil($handle->file_src_size / 1024);
            $name_file = $handle->file_src_name;
            $sql = mysqli_query($link, "INSERT INTO `files`(`name`, `location`, `extension`, `size`, `ip`, `date`, `userid`, `multicode`) VALUES('{$name_file}', '{$location}', '{$extension}', '{$size}', '{$ip}', '{$date}', '{$uid}', {$multicode})") or die(mysqli_error($link));
        }
    }
}

// Return JSON-RPC response
die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');

?>