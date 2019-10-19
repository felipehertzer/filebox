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
include("modules/charts/FusionCharts.php");
include("user/header.php");

$js = "<script type='text/javascript' src='{$website}/js/jquery.progressbar.min.js'></script>";

if ($sesslife == true) {
    disk_usage($userid);
    if ($percent <= 40) {
        $bar_Image = "{$website}/images/bar/progressbg_green.gif";
    }
    elseif ($percent > 40 && $percent <= 80) {
        $bar_Image = "{$website}/images/bar/progressbg_yellow.gif";
    }
    elseif ($percent > 80 && $percent <= 100) {
        $bar_Image = "{$website}/images/bar/progressbg_red.gif";
    }
    else {
        $bar_Image = "{$website}/images/bar/progressbg_red.gif";
    }
    $js .= "<script type=\"text/javascript\">
 $(document).ready(function() {
 $(\"#hardDisk\").progressBar({$percent}, { showText: false, barImage: '{$bar_Image}'} );
 }); </script>";
}
subheader('Home', '', $js, '');

if ($sesslife == false) {
    if (isset($_GET['v'])) {
        $v = htmlspecialchars(trim($_GET['v']));
    }
    else {
        $v = "";
    }

    if ($v == "multiupload") { ?>

        <div class='global'><?php mainCounter(); ?>
            <div class="options" style="height:40px;"><p id='register'>You must be registered on <span
                            id='more'><?php echo $webtitle; ?></span> to take advantage of <b>Multi Uploader</b> and
                    many more awesome features. <a href='<?php echo $website; ?>/user/register.php'>Click here</a> to
                    register yourself. It just takes few seconds.</p></div>
            <form method="POST" action="<?php echo $website; ?>/process.php" name="multiUpload">
                <input type='hidden' value='<?php echo $multicode; ?>' name='multicode'/>
            </form>
        </div>

        <div class='sidebar'>
            <a href='<?php echo $website; ?>/'><p id='basic'>Basic Upload</p></a>
            <a href='<?php echo $website; ?>/?v=multiupload'><p id='multi' class='selected'>Multi Upload</p></a>
        </div>

    <?php } else { ?>

        <div class='global'><?php mainCounter(); ?>
            <form method="POST" action="<?php echo $website; ?>/process.php" enctype="multipart/form-data"
                  name="uploadForm" onsubmit="return ContentSelected(4);">
                <table id='uploader'>
                    <tr>
                        <td style='width:319px;'>
                            <table id="upload1" class="basic-upload">
                                <tr>
                                    <td><label id="title">01:</label></td>
                                    <td><input type="file" name="file1" size='32'></td>
                                </tr>
                            </table>

                            <table id="upload2" class="basic-upload">
                                <tr>
                                    <td><label id="title">02:</label></td>
                                    <td><input type="file" name="file2" size='32'></td>
                                </tr>
                            </table>

                            <table id="upload3" class="basic-upload">
                                <tr>
                                    <td><label id="title">03:</label></td>
                                    <td><input type="file" name="file3" size='32'></td>
                                </tr>
                            </table>

                            <table id="upload4" class="basic-upload">
                                <tr>
                                    <td><label id="title">04:</label></td>
                                    <td><input type="file" name="file4" size='32'></td>
                                </tr>
                            </table>

                            <table id="upload5" class="basic-upload">
                                <tr>
                                    <td><label id="title">05:</label></td>
                                    <td><input type="file" name="file5" size='32'></td>
                                </tr>
                            </table>
                        </td>
                        <td style='width:319px;'>

                            <table id="upload6" class="basic-upload">
                                <tr>
                                    <td><label id="title">06:</label></td>
                                    <td><input type="file" name="file6" size='32'></td>
                                </tr>
                            </table>

                            <table id="upload7" class="basic-upload">
                                <tr>
                                    <td><label id="title">07:</label></td>
                                    <td><input type="file" name="file7" size='32'></td>
                                </tr>
                            </table>

                            <table id="upload8" class="basic-upload">
                                <tr>
                                    <td><label id="title">08:</label></td>
                                    <td><input type="file" name="file8" size='32'></td>
                                </tr>
                            </table>

                            <table id="upload9" class="basic-upload">
                                <tr>
                                    <td><label id="title">09:</label></td>
                                    <td><input type="file" name="file9" size='32'></td>
                                </tr>
                            </table>

                            <table id="upload10" class="basic-upload">
                                <tr>
                                    <td><label id="title">10:</label></td>
                                    <td><input type="file" name="file10" size='32'></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <table id='content'>
                    <td><a href='#' onClick='document.uploadForm.submit();' class='button'>Upload</a><input
                                type='hidden' name='formUpload'/></td>
                    </tr>
                </table>
            </form>
        </div>
        <div class='sidebar'><a href='<?php echo $website; ?>/'><p id='basic' class='selected'>Basic Upload</p></a>
            <a href='<?php echo $website; ?>/?v=multiupload'><p id='multi'>Multi Upload</p></a></div>

    <?php }
}
else {
    if ($premium == 1) {
        $space_limit = $premium_space;
    }
    else {
        $space_limit = $normal_space;
    }

    echo "<div class='global'><div class='progressBar'><p>Hard Disk Space ({$space} MB / {$space_limit} MB)</p>  <span id='hardDisk'></span></div><div id='main'>";
    echo "<table id='charts'><tr><td class='first'>";
    echo renderChartHTML("modules/charts/FCF_Column3D.swf", "data.php?t=fileuploads", "", "myFirst", 400, 300);
    echo "</td><td class='second'><div class='suboptions'><p class='header'>{$webtitle} Activity</p>
 <p class='impInfo'><span class='head'>Total Files</span>: ";
    user_files($userid);
    echo "</p>
 <p class='impInfo'><span class='head'>Disk Space Used</span>: ";
    user_space($userid);
    echo " MB <small>(Approx.)</small></p></div></td></tr></table>";
    echo "</div></div>";
    echo "<div class='sidebar'><a href='#' onClick='userActivity();'><p id='graph' class='selected'>User Activity</p></a><a href='#' onClick='userBandwidth();'><p id='bandwidth'>Bandwidth Usage</p></a><a href='#' onClick='userMembership();'><p id='premium'>Premium Membership</p></a><a href='#' onClick='userPoints();'><p id='points'>Activity Points</p></a></div>";
}

include("user/footer.php"); ?>