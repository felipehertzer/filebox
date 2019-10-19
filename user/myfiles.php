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
include("../modules/CreateZipFile.inc.php");
if ($sesslife == true) {
    $err = "";
    if (isset($_POST['folderc'])) {
        $folder = htmlspecialchars(trim($_POST['foldername']));
        $parent = htmlspecialchars(trim($_POST['folders']));
        $created_on = date("d M Y");
        $ip = $_SERVER['REMOTE_ADDR'];
        if ($folder != "") {
            $sql_create = mysqli_query($link, "INSERT INTO `files`(`name`, `ip`, `userid`, `date`, `parent`, `is_folder`) VALUES('{$folder}', '{$ip}', {$userid}, '{$created_on}', {$parent}, 1)") or die(mysqli_error($link));
            $err = "<div class='infobox'><p>Folder Created<br/><small>Your new folder has been created and is now available under the parent folder you chose for.</small></p></div>";
        } else {
            $err = "<div class='errorbox'><p>Folder name cannot be blank<br/><small>You must enter a name to create a folder.</small></p></div>";
        }
    }

    if (isset($_POST['movefiles'])) {
        $folder = htmlspecialchars(trim($_POST['folders']));
        if (isset($_POST["files"])) {
            $files = $_POST["files"];
            while (list($index, $id) = each($files)) {
                movetofolder($id, $folder);
            }
            $err = "<div class='infobox'><p>Selected files moved to folder.<br/><small>The files have been moved to the selected folder. View the folder by <a href='{$website}/user/folder.php?id={$folder}'>clicking here</a>.</small></p></div>";
        } else {
            $err = "<div class='errorbox'><p>Select some files first.<br/><small>Select files which you want to move to folder and then press the <i>Move Files</i> button.</small></p></div>";
        }
    }

    if (isset($_POST['deletefiles'])) {
        if (isset($_POST["files"])) {
            $files = $_POST["files"];
            while (list($index, $id) = each($files)) {
                deleteFile($id);
            }
            $err = "<div class='infobox'><p>Selected files deleted.<br/><small>The files which you selected has been deleted. That was simple. Right?</small></p></div>";
        } else {
            $err = "<div class='errorbox'><p>Select some files first.<br/><small>Select files which you want to delete and then press the <i>Delete</i> button.</small></p></div>";
        }
    }

    if (isset($_POST['bulkdownload'])) {
        $rand_1 = rand(1000, 100000);
        $rand_2 = rand(100000, 100000000);
        $rand_3 = rand();
        $rand_4 = md5(microtime() . rand(0, 999999));
        $zipName = "../uploads/zip/";
        $zipName .= "random_" . $rand_1 . "_" . $rand_2 . "_" . $rand_3 . "_" . $rand_4 . ".zip";
        $createZipFile = new CreateZipFile;
        $ip = $_SERVER['REMOTE_ADDR'];
        $date_bd = date("d M Y");

        if (isset($_POST["files"])) {
            $files = $_POST["files"];
            while (list($index, $id) = each($files)) {
                $q = mysqli_query($link, "SELECT * FROM `files` WHERE(`id`={$id})") or die(mysqli_error($link));
                if (mysqli_num_rows($q)) {
                    $f = mysqli_fetch_array($q);
                    $location = "../" . $f['location'];
                    $is_folder = $f['is_folder'];
                    $fileSize = $f['size'];
                    $owner = $f['userid'];
                    $dl = $f['downloads'] + 1;

                    if ($is_folder != 1) {
                        //Added for download information
                        $dl_record = mysqli_query($link, "INSERT INTO `downloads`(`ip_address`, `file_id`, `file_size`, `date`, `owner_id`, `dl_user`) VALUES('{$ip}', {$id}, {$fileSize}, '{$date_bd}', {$owner}, {$userid})") or die(mysqli_error($link));
                        $dl_update = mysqli_query($link, "UPDATE `files` SET `downloads`={$dl} WHERE(`id`={$id})") or die(mysqli_error($link));
                        if ($owner != 0) {
                            $bw_get = mysqli_query($link, "SELECT `bandwidth_used` FROM `members` WHERE(`id`={$owner})") or die(mysqli_error($link));
                            if (mysqli_num_rows($bw_get)) {
                                $bw_fetch = mysqli_fetch_array($bw_get);
                                $bw_used = $bw_fetch['bandwidth_used'] + $fileSize;
                                $bw_update = mysqli_query($link, "UPDATE `members` SET `bandwidth_used`={$bw_used} WHERE(`id`={$owner})") or die(mysqli_error($link));
                            }
                            /* Getting the country of the user using the IP address and making use of that for the
                            points system */

                            tier_points();

                            $temp_month = date("M");
                            $last_action = date("d M Y H:i");
                            $temp_year = date("Y");
                            $point_query = mysqli_query($link, "SELECT * FROM `points` WHERE(`userid`={$owner}) AND (`month`='{$temp_month}') AND (`year`='{$temp_year}')") or die(mysqli_error($link));

                            if (mysqli_num_rows($point_query)) {
                                $point_fetch = mysqli_fetch_array($point_query);
                                $owner_points = $point_fetch['points'] + $points;
                                $point_update = mysqli_query($link, "UPDATE `points` SET `points`={$owner_points}, `last_action`='{$last_action}' WHERE(`userid`={$owner}) AND (`month`='{$temp_month}') AND (`year`='{$temp_year}')") or die(mysqli_error($link));
                            } else {
                                $point_insert = mysqli_query($link, "INSERT INTO `points`(`userid`, `month`, `year`, `points`, `last_action`) VALUES({$owner}, '{$temp_month}', '{$temp_year}', {$points}, '{$last_action}')") or die(mysqli_error($link));
                            }
                        } /* Download information ends over here */

                        $fileContents = file_get_contents($location);
                        $createZipFile->addFile($fileContents, $f['name']);
                    }
                }
            }
            $fd = fopen($zipName, "wb");
            $out = fwrite($fd, $createZipFile->getZippedfile());
            fclose($fd);
            $createZipFile->forceDownload($zipName);
            @unlink($zipName);
        } else {
            $err = "<div class='errorbox'><p>Select some files first.<br/><small>Select files which you want to download and then press the <i>Start Download</i> button.</small></p></div>";
        }
    }

    if (isset($_POST['markpublic'])) {
        if (isset($_POST["files"])) {
            $files = $_POST["files"];
            while (list($index, $id) = each($files)) {
                $q = mysqli_query($link, "UPDATE `files` SET `filter`=0 WHERE(`id`={$id}) AND (`userid`={$userid}) AND (`is_folder`!=1)") or die(mysqli_error($link));
            }
            $err = "<div class='infobox'><p>Selected files have been marked public.<br/><small>The files which you selected has been marked as public.</small></p></div>";
        } else {
            $err = "<div class='errorbox'><p>Select some files first.<br/><small>Select files which you want to mark as public and then press the <i>Mark Public</i> button.</small></p></div>";
        }
    }

    if (isset($_POST['markprivate'])) {
        if (isset($_POST["files"])) {
            $files = $_POST["files"];
            while (list($index, $id) = each($files)) {
                $q = mysqli_query($link, "UPDATE `files` SET `filter`=1 WHERE(`id`={$id}) AND (`userid`={$userid}) AND (`is_folder`!=1)") or die(mysqli_error($link));
            }
            $err = "<div class='infobox'><p>Selected files have been secured.<br/><small>The files which you selected has been marked as private.</small></p></div>";
        } else {
            $err = "<div class='errorbox'><p>Select some files first.<br/><small>Select files which you want to mark as private and then press the <i>Secure Files</i> button.</small></p></div>";
        }
    }

} // SESSION ENDING OVER HERE
include("header.php");
$css = "<link type='text/css' rel='stylesheet' href='{$website}/css/pagination.css' />
 <link type='text/css' rel='stylesheet' href='{$website}/css/colorbox.css' />";
$js = "<script type=\"text/javascript\" src=\"{$website}/js/jquery.colorbox.js\"></script>
 <script>$(document).ready(function(){
 $(\".edit\").colorbox({width:\"650px\", height:\"460px\", iframe:true, opacity:0.3});
 $(\".email\").colorbox({width:\"650px\", height:\"350px\", iframe:true, opacity:0.3});
 });
 </script>";
subheader('My Files', $css, $js, 'files'); ?>

    <script type='text/javascript'>
        function checkUncheckAll(theElement) {
            var theForm = theElement.form, z = 0;
            for (z = 0; z < theForm.length; z++) {
                if (theForm[z].type == 'checkbox' && theForm[z].name != 'checkall') {
                    theForm[z].checked = theElement.checked;
                    var tr = "tr" + theForm[z].value;
                    if (theForm[z].checked) {
                        document.getElementById(tr).style.background = "#7dbee6";
                    } else {
                        document.getElementById(tr).style.background = "#fdfdfd";
                    }
                }
            }
        }
    </script>

<?php if ($sesslife == true) {
    $currentUrl = current_url();

    if (empty($_SESSION['order'])) {
        $_SESSION['order'] = "ORDER BY `id`";
    }
    if (isset($_GET['o'])) {
        $o = htmlspecialchars(trim($_GET['o']));
        switch ($o) {

            case "name":
                $_SESSION['order'] = "ORDER BY `name`";
                break;

            case "date":
                $_SESSION['order'] = "ORDER BY `date`";
                break;

            case "size":
                $_SESSION['order'] = "ORDER BY `size`";
                break;

            case "downloads":
                $_SESSION['order'] = "ORDER BY `downloads`";
                break;

            case "id":
                $_SESSION['order'] = "ORDER BY `id`";
                break;

            default:
                $_SESSION['order'] = "ORDER BY `id`";
                break;

        }
    }

    echo "<div class='global'>";

    if (isset($_GET["p"])) {
        $page = htmlspecialchars(trim($_GET["p"]));
    } else {
        $page = 1;
    }
    $max_show = 10;

    $sql = mysqli_query($link, "SELECT * FROM `files` WHERE(`userid`={$userid}) AND (`parent`=0) {$_SESSION['order']} DESC") or die(mysqli_error($link));
    $sql_n = mysqli_num_rows($sql);

    if ($sql_n != 0) {

        $p = new pagination;
        $p->items($sql_n);
        $p->limit(10);
        $p->currentPage($page);
        $p->parameterName("p");
        $p->target("myfiles.php");

        $from2 = $page * $max_show;
        if ($from2 > $sql_n) {
            $diff = $sql_n % $max_show;
            $from2 = $sql_n;
            $from1 = $from2 - $diff;
        } else $from1 = $from2 - $max_show;

        while ($sql_f = mysqli_fetch_array($sql)) {
            $unique_id[] = $sql_f['id'];
            $extension[] = $sql_f['extension'];
            $date[] = $sql_f['date'];
            $filter[] = $sql_f['filter'];
            $downloads[] = $sql_f['downloads'];
            $size[] = $sql_f['size'];
            $fname[] = $sql_f['name'];
            $is_folder[] = $sql_f['is_folder'];
            $views[] = $sql_f['views'];
        }

        // For Ajax requests - Create folder, move to folder etc
        echo "<form name='files' method='POST' action='{$currentUrl}'>"; ?>

        <div id='sort'>
            <ul>
                <li id='input'><input type='checkbox' name='checkall' onclick='checkUncheckAll(this);'/></li>
                <li>Sort By:</li>
                <a href='?o=id'>
                    <li<?php if ($_SESSION['order'] == "ORDER BY `id`") {
                        echo " class='selected'";
                    } ?>>Recent
                    </li>
                </a>
                <a href='?o=name'>
                    <li<?php if ($_SESSION['order'] == "ORDER BY `name`") {
                        echo " class='selected'";
                    } ?>>Name
                    </li>
                </a>
                <a href='?o=date'>
                    <li<?php if ($_SESSION['order'] == "ORDER BY `date`") {
                        echo " class='selected'";
                    } ?>>Date
                    </li>
                </a>
                <a href='?o=size'>
                    <li<?php if ($_SESSION['order'] == "ORDER BY `size`") {
                        echo " class='selected'";
                    } ?>>Size
                    </li>
                </a>
                <a href='?o=downloads'>
                    <li<?php if ($_SESSION['order'] == "ORDER BY `downloads`") {
                        echo " class='selected'";
                    } ?>>Downloads
                    </li>
                </a>
            </ul>
        </div>

        <?php echo "<div id='ajax'></div>";
        if ($sql_n > 10) {
            $p->show();
        }
        echo $err;
        echo "<table id='myfiles'>";

        for ($i = $from1; $i < $from2; $i++) {
            $fid = $unique_id[$i];
            $ext = $extension[$i];
            $d = $date[$i];
            $downlds = $downloads[$i];
            $s = $size[$i];
            $file_name = $fname[$i];
            $ff = $filter[$i];
            $is_f = $is_folder[$i];
            $v = $views[$i];

            if ($ff == 0) {
                $already = "Public";
                $make = "Private";
                $class_red = "plain";
                $change_filter = 1;
            } else {
                $already = "Private";
                $make = "Public";
                $class_red = "red";
                $change_filter = 0;
            }

            if ($is_f == 1) {
                $fc_query = mysqli_query($link, "SELECT count(*) as count AS count FROM `files` WHERE(`userid`={$userid}) AND (`is_folder`=1) AND (`parent`={$fid})");
                $folder_count = mysqli_fetch_assoc($fc_query)['count'];

                $filec_query = mysqli_query($link, "SELECT count(*) as count AS count FROM `files` WHERE(`userid`={$userid}) AND (`is_folder`!=1) AND (`parent`={$fid})");
                $file_count = mysqli_fetch_assoc($filec_query)['count'];

                echo "<tr onmouseover=\"showItems('{$fid}');\" onmouseout=\"hideItems('{$fid}');\" id='tr{$fid}'><td class='checkbox'><input type='checkbox' name='files[]' value='{$fid}' onclick='highlight(this);' /></td>";
                echo "<td class='image'><img src='{$website}/images/labels/folder.png' /></td>";
                echo "<td class='fileinfo'><p><b><a href='{$website}/user/folder.php?id={$fid}'>{$file_name}</a></b></p><div class='fileinfo'><small>{$folder_count} Folders &nbsp;|&nbsp; {$file_count} Files<span id='text{$fid}' class='noshow'></span></small><div class='hide' id='show{$fid}'><a href='http://twitthis.com/twit?url={$website}/folder.php?id={$fid}' target='_blank'><img src='{$website}/images/icons/twitter_16.png' /></a><a href='http://www.facebook.com/sharer.php?u={$website}/folder.php?id={$fid}&t=Folder Share via {$webtitle}' target='_blank'><img src='{$website}/images/icons/facebook_16.png' /></a><a href='{$website}/email.php?id={$fid}' class='email'><img src='{$website}/images/icons/email.png' /></a></div></div><br/><small>{$d}</small></td></tr>";
            } else {
                echo "<tr onmouseover=\"showItems('{$fid}');\" onmouseout=\"hideItems('{$fid}');\" id='tr{$fid}'>
	<td class='checkbox'><input type='checkbox' name='files[]' value='{$fid}' onclick='highlight(this);' /></td>";
                echo "<td class='image'>";

                // DISPLAY THUMBNAILS FOR IMAGE FILES AND EXTENSION FOR THE OTHER FILES
                if (($ext == "gif") || ($ext == "jpg") || ($ext == "jpeg") ||
                    ($ext == "png") || ($ext == "bmp") || ($ext == "pjpeg")) {
                    echo "<img src='{$website}/small.php?id={$fid}' />";
                } else {
                    echo "<img src='{$website}/showicon.php?id={$ext}' />";
                }

                echo "</td>
	<td class='fileinfo'>
	<p class='{$class_red}'><b><a href='{$website}/download.php?id={$fid}'>{$file_name}</a></b></p>
	<div class='fileinfo'>
	<small class='{$class_red}'>{$s} KB &nbsp;|&nbsp; {$v} Views &nbsp;|&nbsp; {$downlds} Downloads
	<span id='text{$fid}' class='noshow'>&nbsp;|&nbsp;
	<a href='{$website}/user/editinfo.php?id={$fid}' class='edit'>Edit File Info</a>
	&nbsp;|&nbsp; {$already}
	</span></small>
	<div class='hide' id='show{$fid}'>
	<a href='http://twitthis.com/twit?url={$website}/download.php?id={$fid}' target='_blank'>
	<img src='{$website}/images/icons/twitter_16.png' /></a>
	<a href='http://www.facebook.com/sharer.php?u={$website}/download.php?id={$fid}&t=File Share via {$webtitle}' target='_blank'><img src='{$website}/images/icons/facebook_16.png' /></a>
	<a href='{$website}/user/editinfo.php?id={$fid}' class='edit'><img src='{$website}/images/icons/page_white_edit.png' /></a>
	<a href='{$website}/email.php?id={$fid}' class='email'><img src='{$website}/images/icons/email.png' /></a>
	<a href='{$website}/download.php?id={$fid}'><p class='downloadfile'>Download</p></a>
	</div></div></div><br/><small class='{$class_red}'>{$d}</small></td></tr>";
            }
        }
        echo "</table></form>";
    } else {
        echo "<form name='files' method='POST' action='{$currentUrl}'><p style='margin:20px 10px 10px 10px;font-size:11px;color:#333333;line-height:17px;'>You do not have any files on <b>{$webtitle}</b>. Start by uploading few files using <a href='{$website}/user/upload.php'>this page</a> and then come back to <i><b>My Files</b></i> section to manage files over here. It is really that simple.</p><div id='ajax'></div>{$err}</form>";
    }

    echo "</div>";
    echo "<div class='sidebar'><a href='#' onClick='createFolder();'><p id='folder'>Create New Folder</p></a><a href='#' onClick='moveFiles();'><p id='movedoc'>Move Selected</p></a>
 <a href='#' onClick='deleteFiles();'><p id='delete'>Delete Selected</p></a><a href='#' onClick='bulkFiles();'><p id='bulk'>Bulk Download Selected</p></a>
 <a href='#' onClick='publicFiles();'><p id='public'>Mark Public</p></a><a href='#' onClick='privateFiles();'><p id='secure'>Secure Files</p></a></div>";

} else {
    $err = "<div class='infobox'><p>Login to continue<br/><small>You does not seem to be logged in to the website. Please login in order to view this page.</small></p></div>";
    am_showLogin();
}

include("footer.php");

?>