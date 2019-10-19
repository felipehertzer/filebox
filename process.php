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
include("user/header.php");
subheader('Upload Details', '', '', 'upload'); ?>

<div class='global'>
    <?php if (isset($_POST['multicode'])) {
        echo "<div id='main'><div id='account-header'><p>Upload Details</p></div>";
        $multicode = htmlspecialchars(trim($_POST['multicode']));
        $date = date("d M Y");
        $ip = $_SERVER['REMOTE_ADDR'];
        $query_im = mysqli_query($link, "SELECT * FROM `files` WHERE(`multicode`={$multicode})") or die(mysqli_error($link));
        $counter = mysqli_num_rows($query_im);
        if (isset($_GET['fid'])) {
            $foldername = htmlspecialchars(trim($_POST['foldername']));
            if ($counter) {
                echo "<div class='infobar'><p>You uploaded {$counter} files to <i>\"{$foldername}\"</i> folder</p></div>";
                $x = 0;
                echo "<table id='mainimages'><tr>";
                while ($fetch_im = mysqli_fetch_array($query_im)) {
                    echo "<td><a href=\"{$website}/photo.php?id={$fetch_im['id']}\"><img src='{$website}/thumb.php?id={$fetch_im['id']}' /></a></td>";
                    $x++;
                    if (($x % 3) == 0) echo "</tr><tr>";
                }
                echo "</tr></table>";
            } else {
                echo "<div class='errorbox'><p>No files uploaded to the folder.</p></div>";
            }
        } else {
            if ($counter) {
                while ($fetch_im = mysqli_fetch_array($query_im)) {
                    echo "<table class='showlinks'><tr><td id='image'>";
                    if (($fetch_im['extension'] == "gif") || ($fetch_im['extension'] == "jpg") || ($fetch_im['extension'] == "jpeg") ||
                        ($fetch_im['extension'] == "png") || ($fetch_im['extension'] == "bmp") || ($fetch_im['extension'] == "pjpeg")) {
                        echo "<img src='{$website}/small.php?id={$fetch_im['id']}' />";
                    } else {
                        echo "<img src='{$website}/showicon.php?id={$fetch_im['extension']}' />";
                    }
                    echo "</td><td><p class='highlight'><b><a href='{$website}/download.php?id={$fetch_im['id']}'>{$fetch_im['name']}</a></b> has been uploaded</p>";
                    echo "<table><tr><td><b>Link</b></td><td><input type='text' onclick=\"this.select();\" value=\"<a href='{$website}/download.php?id={$fetch_im['id']}'>{$website}/download.php?id={$fetch_im['id']}</a>\"></td></tr>";
                    echo "<tr><td><b>BB Code</b></td><td><input type='text' onclick=\"this.select();\" value=\"[URL={$website}/download.php?id={$fetch_im['id']}] [IMG]{$website}/images/labels/{$fetch_im['extension']}.png[/IMG][/URL]\"></td></tr></table>";
                    echo "</td></tr></table>";
                }
            } else {
                echo "<div class='errorbox'><p>You must upload atleast one file.</p></div>";
            }
        }
        echo "</div>";
    } elseif (isset($_POST["formUpload"])) {

        echo "<div id='main'><div id='account-header'><p>Upload Details</p></div>";

        // MAX NUMBER OF FILES TO BE UPLOADED. Max file size of the uploaded file and other small stuff recorded over here.
        $max = 10;
        $total = 0;
        $date = date("d M Y");
        $ip = $_SERVER['REMOTE_ADDR'];
        $zY = date("zY");
        $ffolder = "uploads/files/" . $zY;
        $pfolder = "uploads/photos/" . $zY;
        $sfolder = "uploads/photos/" . $zY . "/small";
        $Time = date('YmdHis');

        // Maximum file size in bytes for the uploaded files.
        if ($sesslife == true) {
            if ($premium == 1) {
                $maxFileSize = $premium_maxFile;
            } else {
                $maxFileSize = $normal_maxFile;
            }
        } else {
            $maxFileSize = $anon_maxFile;
        }

        $maxFileSize = $maxFileSize * 1024 * 1024;

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

        for ($i = 1; $i < ($max + 1); $i++) { // BEGIN THE LOOP. WILL END ONCE THE LIMIT REACHES 10.
            $filter = htmlspecialchars(trim($_FILES["file" . $i]["name"]));
            $search = array("&amp;", "&quot;", "&#039;", "&lt;", "&gt;", " ", "(", ")", "?", "{", "}", "%", "$", "#", "+", ":");
            $secfile = str_replace($search, "", $filter);

            if ($secfile != "") {
                $total = $total + 1;
                $name = "file" . $i;
                if ($_FILES["file" . $i]["error"] != 4) {
                    $handle = new Upload($_FILES["file" . $i]);

                    $handle->file_max_size = $maxFileSize;

                    if ($handle->file_src_size < $maxFileSize) {
                        if ($handle->uploaded) {
                            $extension = $handle->file_src_name_ext;
                            $rand_1 = rand(1000, 100000);
                            $rand_2 = rand(100000, 100000000);
                            $rand_3 = rand();


                            // IF UPLOADED FILETYPE IS AN IMAGE, THEN UPLOAD THE FILE AND CREATE THUMBNAILS.
                            if (($extension == "gif") || ($extension == "jpg") || ($extension == "jpeg") ||
                                ($extension == "png") || ($extension == "bmp") || ($extension == "pjpeg")) {
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
                                    $name_file = $handle->file_src_name;
                                    $fileLocation = $Time . '_' . $rand_1 . '_' . $rand_2 . '_' . $rand_3 . '_' . $handle->file_src_name_body . '.' . $handle->file_src_name_ext;
                                    $location = $pfolder . "/" . $fileLocation;
                                    $small_location = $sfolder . "/" . $fileLocation;
                                    $size = ceil($handle->file_src_size / 1024);
                                    $sql = mysqli_query($link, "INSERT INTO `files`(`name`, `location`, `small_location`, `extension`, `size`, `ip`, `date`, `userid`) VALUES('{$name_file}', '{$location}', '{$small_location}', '{$extension}', '{$size}', '{$ip}', '{$date}', '{$userid}')") or die(mysqli_error($link));
                                    $sql_id = mysqli_insert_id();
                                    echo "<table class='showlinks'><tr><td id='image'><img src='{$website}/{$small_location}' /></td>";
                                    echo "<td><p class='highlight'><b><a href='{$website}/download.php?id={$sql_id}'>{$name_file}</a></b> has been uploaded</p>";
                                    echo "<table><tr><td><b>Link</b></td><td><input type='text' onclick=\"this.select();\" value=\"<a href='{$website}/download.php?id={$sql_id}'>{$website}/download.php?id={$sql_id}</a>\"></td></tr>";
                                    echo "<tr><td><b>BB Code</b></td><td><input type='text' onclick=\"this.select();\" value=\"[URL={$website}/download.php?id={$sql_id}] [IMG]{$website}/images/labels/{$extension}.png[/IMG][/URL]\"></td></tr></table>";
                                    echo "</td></tr></table>";
                                } else {
                                    echo "<div class='errorbox'><p>Not a valid format<br/><small><b>.{$extension}</b> files are not allowed to be uploaded on {$webtitle}</small></p></div>";
                                }
                            } else { // PROCESS REST OF THE FILES OVER HERE.
                                $fileName = $Time . '_' . $rand_1 . '_' . $rand_2 . '_' . $rand_3 . '_' . $handle->file_src_name_body;
                                $handle->file_new_name_body = $fileName;
                                $handle->Process($ffolder);
                                if ($handle->processed) {
                                    $fileLocation = $Time . '_' . $rand_1 . '_' . $rand_2 . '_' . $rand_3 . '_' . $handle->file_src_name_body . '.' . $handle->file_src_name_ext;
                                    $location = $ffolder . '/' . $fileLocation;
                                    $size = ceil($handle->file_src_size / 1024);
                                    $name_file = $handle->file_src_name;
                                    $sql = mysqli_query($link, "INSERT INTO `files`(`name`, `location`, `extension`, `size`, `ip`, `date`, `userid`) VALUES('{$name_file}', '{$location}', '{$extension}', '{$size}', '{$ip}', '{$date}', '{$userid}')") or die(mysqli_error($link));
                                    $sql_id = mysqli_insert_id();
                                    echo "<table class='showlinks'><tr><td id='image'><img src='{$website}/showicon.php?id={$extension}' /></td>";
                                    echo "<td><p class='highlight'><b><a href='{$website}/download.php?id={$sql_id}'>{$name_file}</a></b> has been uploaded</p>";
                                    echo "<table><tr><td><b>Link</b></td><td><input type='text' onclick=\"this.select();\" value=\"<a href='{$website}/download.php?id={$sql_id}'>{$website}/download.php?id={$sql_id}</a>\"></td></tr>";
                                    echo "<tr><td><b>BB Code</b></td><td><input type='text' onclick=\"this.select();\" value=\"[URL={$website}/download.php?id={$sql_id}] [IMG]{$website}/images/labels/{$extension}.png[/IMG][/URL]\"></td></tr></table>";
                                    echo "</td></tr></table>";
                                } else {
                                    echo "<div class='errorbox'><p>Not a valid format<br/><small><b>.{$extension}</b> files are not allowed to be uploaded on {$webtitle}</small></p></div>";
                                }
                            }
                        } else {
                            echo "<div class='errorbox'><p>An error occured<br/><small>" . $handle->error . "</small></p></div>";
                        }
                    } else {
                        echo "<div class='errorbox'><p>File too big<br/><small>" . $handle->error . "</small></p></div>";
                    }
                } else {
                    echo "<div class='errorbox'><p>Select a file to upload</p></div>";
                }
            } // END $SECFILE PROCESSING
        }

        // IF NO. OF FILES UPLOADED IS ZERO THEN PERFORM THE FOLLOWING
        if ($total == 0) {
            if (isset($_GET['fid'])) {
                echo "<div class='errorbox'><p>No files uploaded to the folder.</p></div>";
            } else {
                echo "<div class='errorbox'><p>You must upload atleast one file.</p></div>";
            }
        }
        if (isset($_GET['fid'])) {
            $foldername = htmlspecialchars(trim($_POST['foldername']));
            echo "<div class='infobar'><p>You added {$total} files to <i>\"{$albumname}\"</i> folder</p></div>";
            $x = 0;
            echo "<table id='mainimages'><tr>";
            for ($i = 0; $i < ($total); $i++) {
                echo "<td>" . $pics[$i] . "</td>";
                $x++;
                if (($x % 3) == 0) echo "</tr><tr>";
            }
            echo "</tr></table>";
        }
        //END HERE

        echo "</div>"; // DIV #MAIN IS CLOSED HERE.

    } else {
        echo "<div id='main'><div id='account-header'><p>Upload Details</p></div><div class='errorbox'><p>Please use the upload page for this action.</p></div></div>";
    } ?>

</div>
<div class='sidebar'>
    <a href='#'><p id='links' class='selected'>Upload Links</p></a>
</div>

<?php include("user/footer.php"); ?> 