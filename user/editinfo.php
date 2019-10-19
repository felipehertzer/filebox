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
if ($sesslife == true) { ?>
    <html lang="pt-br">
    <head>
        <title><?php echo $webtitle; ?> - Edit Info</title>
        <link rel="stylesheet" type="text/css" href="<?php echo $website; ?>/css/default.css"/>
    </head>
    <body>
    <center>
        <?php $err = "";
        if (isset($_GET['id'])) {
            $id = htmlspecialchars(trim($_GET['id']));
            if (isset($_POST['editInfo'])) {
                $desc = htmlspecialchars(trim($_POST['description']));
                $filename = htmlspecialchars(trim($_POST['filename']));
                if (!empty($filename)) {
                    $q = mysqli_query($link, "UPDATE `files` SET `description`='{$desc}', `name`='{$filename}' WHERE(`id`={$id}) AND (`userid`={$userid})") or die(mysqli_error($link));
                    $err = "<div class='infobox'><p>File info has been saved.</p></div>";
                    editInfo_Form();
                } else {
                    $err = "<div class='errorbox'><p>You must give file a valid name.</p></div>";
                    editInfo_Form();
                }
            } else {
                editInfo_Form();
            }
        } ?>
    </center>
    </body>
    </html>

<?php }

function editInfo_Form()
{
    global $website;
    global $id;
    global $err;
    global $userid;
    global $link;
    $q = mysqli_query($link, "SELECT `name`, `description` FROM `files` WHERE(`id`={$id}) AND (`userid`={$userid})") or die(mysqli_error($link));
    if (mysqli_num_rows($q)) {
        $f = mysqli_fetch_array($q);
        $filename = $f['name'];
        $description = $f['description'];
        ?>
        <div class='emaildiv' id='email' style='text-align:left;'>
            <div id='emaildiv-header' style='height:40px;'><p><img src='<?php echo $website; ?>/images/icons/eraser.png'
                                                                   style='width:24px;height:24px;'/> File Information
                    <small>(Edit file information over here)</small></p></div>
            <?php echo $err; ?>
            <form action="<?php echo $website; ?>/user/editinfo.php?id=<?php echo $id; ?>" method="POST"
                  accept-charset="utf-8" id="compose">
                <table id='messagecompose'>
                    <tr>
                        <td class='first' style='text-align:left;'>File Name:</td>
                    </tr>
                    <tr>
                        <td>
                            <input type='text' name='filename' value='<?php echo $filename; ?>'
                                   class='filename'/><br/><small>(Must not be blank)</small>
                        </td>
                    </tr>
                    <tr>
                        <td class='first' style='text-align:left;'>Description:</td>
                    </tr>
                    <tr>
                        <td>
                            <textarea name='description'><?php echo $description; ?></textarea><br/><small>(Not more
                                than 2000 characters)</small>
                        </td>
                    </tr>
                    <tr>
                        <td><input type='submit' class='button' value='Edit Description' name='editInfo'/></td>
                    </tr>
                </table>
            </form>
        </div>

    <?php }
} ?>