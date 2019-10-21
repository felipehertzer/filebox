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


define('FACEBOOK_APP_ID', '1957805037655793');
define('FACEBOOK_SECRET', 'b0d5f0d593dca314751e0af91179871d');

function get_facebook_cookie($app_id, $application_secret)
{
    if(isset($_COOKIE['fbs_' . $app_id]))
    {
        $args = array();
        parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
        ksort($args);
        $payload = '';

        foreach ($args as $key => $value) {
            if ($key != 'sig') {
                $payload .= $key . '=' . $value;
            }
        }

        if (md5($payload . $application_secret) != $args['sig']) {
            return null;
        }

        return $args;
    }
    return false;
}

function advert()
{
    global $website;
    global $link;
    $ad_q = mysqli_query($link, "SELECT * FROM `ads` ORDER BY RAND() LIMIT 1") or die(mysqli_error($link));
    if (mysqli_num_rows($ad_q)) {
        $ad_f = mysqli_fetch_array($ad_q);
        echo "<div class='advert'><div id='advert-header'><p><img src='{$website}/images/spon-warrow.gif' /></p></div>{$ad_f['source']}</div>";
    }
    else {
        echo "<div class='advert'><div id='advert-header'><p><img src='{$website}/images/spon-warrow.gif' /></p></div><br/><center>No ads available for your region.</center></div>";
    }
}

function mainCounter()
{
    global $material;
    global $webtitle;
    global $link;
    $q_files = mysqli_query($link, "SELECT COUNT(*) as count FROM `files`") or die(mysqli_error($link));
    $r_files = mysqli_fetch_assoc($q_files)['count'];
    $q_images = mysqli_query($link, "SELECT COUNT(*) as count FROM `files` WHERE(`extension`='png') OR (`extension`='gif') OR (`extension`='jpg') OR (`extension`='jpeg') OR (`extension`='bmp') OR (`extension`='pjpeg')") or die(mysqli_error($link));
    $r_images = mysqli_fetch_assoc($q_images)['count'];
    $a_files = $r_files - $r_images;
    echo "<div class='infobox'><p><span id='more'>{$a_files}</span> files & <span id='more'>{$r_images}</span> images hosted and shared via <b>{$webtitle}</b>
	<br/><small>Multi uploaders, user folders, one-click social sharing, privacy options, and <span id='more'>much more</span>. You are just going to love us for this.</small></p></div>";
}

function current_url()
{
    return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

function imagecreatefromunknown($path, $ext)
{
    switch ($ext) {
        case "jpg":
        case "jpeg":
            $img = imagecreatefromjpeg($path);
            break;
        case "gif":
            $img = imagecreatefromgif($path);
            break;
        case "png":
            $img = imagecreatefrompng($path);
            break;
        case "bmp":
            $img = imagecreatefrombmp($path);
            break;
    }
    return $img;
}

function newuser_email($email, $key)
{
    global $webtitle;
    global $website;
    global $adminemail;
    global $lang;
    $subject = "Email Verification - {$webtitle}";
    $headers = "From: " . $adminemail . "\r\n";
    $headers .= "Reply-To: " . $adminemail . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    $message = $lang[9] . " " . $webtitle . ". " . $lang[12];
    $message .= "{$website}/user/verify.php?k={$key}<br/><br/>{$lang[14]}<br/>------<br/>{$webtitle} {$lang[16]}<br/>{$website}<br/><br/>";
    $mailsent = mail($email, $subject, $message, $headers);
}

function forgotpass_email($email, $password)
{
    global $webtitle;
    global $website;
    global $adminemail;
    global $lang;
    $subject = "New Password - {$webtitle}";
    $headers = "From: " . $adminemail . "\r\n";
    $headers .= "Reply-To: " . $adminemail . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    $message = $lang[17] . " " . $webtitle . ". <br/><br/>{$lang[21]} {$password}<br/><br/>";
    $message .= "{$lang[22]} {$webtitle}.{$lang[23]}  {$webtitle}.<br/><br/>{$lang[14]}<br/>------<br/>{$webtitle} {$lang[16]}<br/>{$website}<br/><br/>";
    $mailsent = mail($email, $subject, $message, $headers);
}

function contact_admin($email, $subject, $message)
{
    global $webtitle;
    global $mailsent;
    global $adminemail;
    global $lang;
    $to = $adminemail;
    $subject = "New Message via Contact Form - {$webtitle}";
    $headers = "From: " . $email . "\r\n";
    $headers .= "Reply-To:" . $email . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    $m = "{$lang[24]} {$webtitle}. <br/><br/>{$lang[25]}<br/><br/>";
    $m .= "<table style='font-size:13px;padding:10px;'><tr><td style='padding:4px;'>{$lang[3]}</td><td style='padding:4px;'>{$email}</td></tr>
			 <tr><td style='padding:4px;'>{$lang[18]}</td><td style='padding:4px;'>{$subject}</td></tr>
			 <tr><td style='padding:4px;'>{$lang[19]}</td><td style='padding:4px;'>{$message}</td></tr></table><br/><br/><br/>{$lang[14]}<br/>------<br/>{$lang[26]} {$webtitle} {$lang[27]}";
    $mailsent = mail($to, $subject, $m, $headers);
}

function isValidEmail($email)
{
    return filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
}

function am_showLogin()
{
    global $website;
    global $webtitle;
    global $err;
    global $lang; ?>
    <center>
        <form method="POST" action="<?php echo $website; ?>/user/login.php?r=reg" name="myForm">
            <div class='logindiv'>
                <div id='logindiv-header'><p><?php echo $webtitle; ?><?php echo $lang[105]; ?></p></div>
                <?php echo $err; ?>
                <table>
                    <tr>
                        <td class='first'><label><?php echo $lang[3]; ?>: </label></td>
                        <td><input type="text" name="username"></td>
                    </tr>
                    <tr>
                        <td class='first'><label><?php echo $lang[4]; ?>: </label></td>
                        <td><input type="password" name="password"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <div id='fbconnect'><p><a
                                            href='<?php echo $website; ?>/user/forgot.php'><?php echo $lang[35]; ?>?</a>
                                </p>
                                <p><a href='<?php echo $website; ?>/user/resend.php'><?php echo $lang[28]; ?></a></p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><input type='submit' name='login' value='<?php echo $lang[105]; ?>' class='button'/></td>
                    </tr>
                </table>
            </div>
        </form>
    </center>
<?php }

function am_showSmallLogin()
{
    global $lang;
    global $website;
    global $webtitle; ?>
    <form method="POST" action="<?php echo $website; ?>/user/login.php?r=reg" name="myForm">
        <div class='logindiv' style='width:370px;'>
            <div id='logindiv-header'><p><?php echo $webtitle; ?><?php echo $lang[105]; ?></p></div>
            <table>
                <tr>
                    <td class='first'><label><?php echo $lang[3]; ?>: </label></td>
                    <td><input type="text" name="username"></td>
                </tr>
                <tr>
                    <td class='first'><label><?php echo $lang[4]; ?>: </label></td>
                    <td><input type="password" name="password"></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <div id='fbconnect'><p><a
                                        href='<?php echo $website; ?>/user/forgot.php'><?php echo $lang[35]; ?>?</a></p>
                            <p><a href='<?php echo $website; ?>/user/resend.php'><?php echo $lang[28]; ?></a></p></div>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type='submit' name='login' value='<?php echo $lang[105]; ?>' class='button'/></td>
                </tr>
            </table>
        </div>
    </form>

<?php }

function ft_showinfo($userid)
{
    global $website;
    global $link;

    $q = mysqli_query($link, "SELECT * FROM `members` WHERE(`id`={$userid})") or die(mysqli_error($link));
    if (mysqli_num_rows($q)) {
        $f = mysqli_fetch_array($q);
        echo "<table id='userinfo'><tr><td class='first'>Name</td><td>{$f['name']}</td></tr>
  <tr><td class='first'>Email</td><td>{$f['email']}</td></tr>
  <tr><td class='first'>Joined On</td><td>{$f['join']}</td></tr>
  <tr><td class='first'>Last Access</td><td>{$f['access']}</td></tr>
  <tr><td class='first'>Account Key</td><td>{$f['key']}</td></tr>
  <tr><td class='first'></td><td><a href='{$website}/user/password.php'>Click here</a> to change your password.</td></tr>";
        echo "</table>";
    }
}

function movetofolder($id, $folder)
{
    global $userid;
    global $link;

    $query = mysqli_query($link,"SELECT * FROM `files` WHERE (`id`={$id}) AND (`userid`={$userid})");
    $number = mysqli_num_rows($query);
    if ($number) {
        $q = mysqli_query($link,"UPDATE `files` SET `parent`={$folder} WHERE (`id`={$id}) AND (`userid`={$userid})");
    }
}

function deleteFile($id)
{
    global $userid;
    global $website;
    global $link;
    $result = mysqli_query($link,"SELECT * FROM `files` WHERE (`id`={$id}) AND (`userid`={$userid})");
    $number = mysqli_num_rows($result);
    if ($number) {
        $row = mysqli_fetch_array($result);
        $is_folder = $row['is_folder'];

        // IF THE DELETED ITEM IS A FOLDER
        if ($is_folder == 1) {
            $file_q = mysqli_query($link,"SELECT * FROM `files` WHERE(`parent`={$id}) AND (`userid`={$userid})") or die(mysqli_error($link));
            if (mysqli_num_rows($file_q)) {
                while ($unlink = mysqli_fetch_array($file_q)) {
                    $ext = $unlink['extension'];
                    $unlink_url = "../" . $unlink['location'];
                    unlink($unlink_url);
                    if (($ext == "gif") || ($ext == "jpg") || ($ext == "jpeg") ||
                        ($ext == "png") || ($ext == "bmp") || ($ext == "pjpeg")
                    ) {
                        $photo_unlink = "../" . $unlink['small_location'];
                        unlink($photo_unlink);
                    }
                }
            }
            $del_q = mysqli_query($link,"DELETE FROM `files` WHERE(`parent`={$id}) AND (`userid`={$userid})") or die(mysqli_error($link));
        }
        else {

            $ext = $row['extension'];
            $file = "../" . $row['location'];
            unlink($file);

            // IF THIS IS AN IMAGE
            if (($ext == "gif") || ($ext == "jpg") || ($ext == "jpeg") ||
                ($ext == "png") || ($ext == "bmp") || ($ext == "pjpeg")
            ) {
                $photo_unlink = "../" . $row['small_location'];
                unlink($photo_unlink);
            }
        }
        $q = mysqli_query($link,"DELETE FROM `files` WHERE (`id`={$id}) AND (`userid`={$userid})");
    }
}

function user_files($userid)
{
    global $link;
    $q = mysqli_query($link,"SELECT COUNT(*) as count FROM `files` WHERE(`userid`={$userid}) AND (`is_folder`!=1)") or die(mysqli_error($link));
    $n = mysqli_fetch_assoc($q)['count'];
    echo $n;
}

function user_space($userid)
{
    global $link;
    $q = mysqli_query($link, "SELECT `size` FROM `files` WHERE(`userid`={$userid}) AND (`is_folder`!=1)") or die(mysqli_error($link));
    if (mysqli_num_rows($q)) {
        $space = 0.0;
        while ($f = mysqli_fetch_array($q)) {
            $space = $space + $f['size'];
        }
        $space = ceil($space / 1024);
    }
    else {
        $space = 0.0;
    }
    echo $space;
}

function user_bandwidth($userid)
{
    global $link;
    $day = date("d M Y");
    $q = mysqli_query($link, "SELECT `file_size` FROM `downloads` WHERE(`date`='{$day}') AND (`owner_id`={$userid})") or die(mysqli_error($link));
    if (mysqli_num_rows($q)) {
        $n = 0;
        while ($row = mysqli_fetch_array($q)) {
            $n = $n + $row['file_size'];
        }
        $n = ceil($n / 1024);
    }
    else {
        $n = 0;
    }
    echo $n;
}

function total_bandwidth($userid)
{
    global $link;

    $q = mysqli_query($link, "SELECT `bandwidth_used` FROM `members` WHERE(`id`={$userid})") or die(mysqli_error($link));
    if (mysqli_num_rows($q)) {
        $f = mysqli_fetch_array($q);
        $total_bw = $f['bandwidth_used'];
        $total_bw = ceil($total_bw / 1024);
    }
    else {
        $total_bw = 0;
    }
    echo $total_bw;
}

function file_downloads($userid)
{
    global $link;
    $q = mysqli_query($link, "SELECT COUNT(*) as count FROM `downloads` WHERE(`owner_id`={$userid})") or die(mysqli_error($link));
    $r = mysqli_fetch_assoc($q)['count'];
    echo $r;
}

function point_filedownloads($month, $year, $userid)
{
    global $month_total;
    global $link;

    switch ($month) {
        case "Jan":
        case "Mar":
        case "Oct":
        case "Dec":
        case "Jul":
        case "Aug":
        case "May":
            $days = 31;
            break;
        case "Feb":
            if ($year % 4 == 0) {
                $days = 29;
            }
            else {
                $days = 28;
            }
            break;
        case "Apr":
        case "Jun":
        case "Nov":
        case "Sep":
            $days = 30;
            break;

        default:
            $days = 30;
    } /* Switch ends over here */

    $month_total = 0;
    for ($i = 1; $i < $days + 1; $i++) {
        if ($i < 10) {
            $i = "0" . $i;
        }
        $full_date = $i . " " . $month . " " . $year;
        $q = mysqli_query($link, "SELECT COUNT(*) as count FROM `downloads` WHERE(`date`='{$full_date}')") or die(mysqli_error($link));
        $result = mysqli_fetch_assoc($q)['count'];
        $month_total = $month_total + $result;
    }
    return $month_total;
}

function disk_usage($userid)
{
    global $space;
    global $percent;
    global $premium;
    global $premium_space;
    global $normal_space;
    global $link;

    $q = mysqli_query($link, "SELECT `size` FROM `files` WHERE(`userid`={$userid}) AND (`is_folder`!=1)") or die(mysqli_error($link));
    if (mysqli_num_rows($q)) {
        $space = 0.0;
        while ($f = mysqli_fetch_array($q)) {
            $space = $space + $f['size'];
        }
        $space = ceil($space / 1024);
    }
    else {
        $space = 0.0;
    }

    if ($premium == 1) {
        $percent = $space * 100 / $premium_space;
    }
    else {
        $percent = $space * 100 / $normal_space;
    }
    return $space;
    return $percent;
}

function tier_points()
{
    global $points;
    global $tier1_points;
    global $tier2_points;
    global $tier3_points;
    global $tier4_points;
    global $link;

    $sql = 'SELECT c.code FROM ip2nationCountries c, ip2nation i WHERE i.ip < INET_ATON("' . $_SERVER['REMOTE_ADDR'] . '") AND c.code = i.country ORDER BY i.ip DESC LIMIT 0,1';
    list($countryCode) = mysqli_fetch_row(mysqli_query($link, $sql));

    if (($countryCode == "us") || ($countryCode == "uk")) {
        $points = $tier1_points;
    }
    elseif (($countryCode == "at") || ($countryCode == "au") || ($countryCode == "be")
        || ($countryCode == "ca") || ($countryCode == "dk") || ($countryCode == "fi") ||
        ($countryCode == "fr") || ($countryCode == "ie") || ($countryCode == "it") ||
        ($countryCode == "nl") || ($countryCode == "nz") || ($countryCode == "no") ||
        ($countryCode == "sa") || ($countryCode == "se") || ($countryCode == "ae")
    ) {
        $points = $tier2_points;
    }
    elseif (($countryCode == "br") || ($countryCode == "bg") || ($countryCode == "cy") ||
        ($countryCode == "cz") || ($countryCode == "gr") || ($countryCode == "hu") ||
        ($countryCode == "ir") || ($countryCode == "jp") || ($countryCode == "kw") ||
        ($countryCode == "lv") || ($countryCode == "lt") || ($countryCode == "lu") ||
        ($countryCode == "pl") || ($countryCode == "pt") || ($countryCode == "qa") ||
        ($countryCode == "ro") || ($countryCode == "ru") || ($countryCode == "es") ||
        ($countryCode == "za")
    ) {
        $points = $tier3_points;
    }
    else {
        $points = $tier4_points;
    }
}

?>