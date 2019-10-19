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
require_once('../modules/recaptchalib.php');
include("header.php");
subheader($lang[104], '', '', 'register');

if ($sesslife == false) {
    if (isset($_POST['join'])) {

        $email = trim(htmlspecialchars($_POST['email']));
        $password = trim(htmlspecialchars($_POST['pass']));
        $name = trim(htmlspecialchars($_POST['name']));

        if (($email != "") && ($password != "") && ($name != "")) {
            $resp = recaptcha_check_answer($privatekey, $_POST["g-recaptcha-response"]);
            if ($resp->is_valid) {
                if (isValidEmail($email)) {
                    $q = mysqli_query($link, "SELECT * FROM `members` WHERE(email = '{$email}')") or die(mysqli_error($link));
                    $n = mysqli_num_rows($q);

                    if (!$n) {
                        $r = rand();
                        $r_2 = rand();
                        $r_3 = rand();
                        $t = time();
                        $key = md5($r . "_" . $r_2 . "_" . $r_3 . "_" . $t);
                        $verified = "0";
                        $join = date("d M Y");
                        $w = mysqli_query($link, "INSERT INTO `members` (`name`, `password` ,`email` ,`key` ,`verified` ,`join`)VALUES ('{$name}', '{$password}', '{$email}', '{$key}', '{$verified}', '{$join}')") or die(mysqli_error($link));
                        if ($w) {
                            newuser_email($email, $key);
                            $err = "<div class='infobox'><p>{$lang[76]} {$webtitle}<br/><small>{$lang[77]}</small></p></div>";
                            am_showRegister();
                        }
                    } else {
                        $err = "<div class='errorbox'><p>{$lang[78]}<br/><small><a href='{$website}/user/resend.php'>{$lang[72]}</a></small></p></div>";
                        am_showRegister();
                    }
                } else {
                    $err = "<div class='errorbox'><p>{$lang[79]}</p></div>";
                    am_showRegister();
                }
            } else {
                $err = "<div class='errorbox'><p>{$lang[54]}</p></div>";
                am_showRegister();
            }
        } else {
            $err = "<div class='errorbox'><p>{$lang[80]}</p></div>";
            am_showRegister();
        }
    } else {
        am_showRegister();
    }
} else {
    echo "<br/><center><div class='errorbox' style='width:960px;'><p>{$lang[40]}<br/><small>You cannot perform this action while you are logged in to the website.</small></p></div></center><br/><br/>";
}

function am_showRegister()
{
    global $lang;
    global $website;
    global $publickey;
    global $webtitle;
    global $err; ?>
    <center>
        <form method='POST' action='<?php echo $website; ?>/user/register.php'>
            <div class='logindiv'>
                <div id='logindiv-header'><p><?php echo $lang[43]; ?><?php echo $webtitle; ?></p></div>
                <?php echo $err; ?>
                <table>
                    <tr>
                        <td class='first'>Name:</td>
                        <td><input type='text' name='name' id='name' size='25'/></td>
                    </tr>
                    <tr>
                        <td class='first'><?php echo $lang[3]; ?>:</td>
                        <td><input type='text' name='email' id='email' size='25'/><br/><small>(<?php echo $lang[81]; ?>
                                )</small></td>
                    </tr>
                    <tr>
                        <td class='first'><?php echo $lang[4]; ?>:</td>
                        <td><input type='password' name='pass' id='pass'
                                   size='25'/><br/><small><?php echo $lang[82]; ?></small></td>
                    </tr>
                    <tr>
                        <td class='first'><?php echo $lang[51]; ?>:</td>
                        <td>
                            <?php echo recaptcha_get_html($publickey); ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type='submit' name='join' class='button' value='<?php echo $lang[5]; ?>'/>&nbsp;&nbsp;<small>or</small>&nbsp;&nbsp;<a
                                    href='#' onClick='fblogin();'><img
                                        src='<?php echo $website; ?>/images/fb_connect.gif'
                                        style='vertical-align:top;'/></a></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><small><?php echo $lang[10]; ?> <?php echo $webtitle; ?>? <a
                                        href='<?php echo $website; ?>/user/login.php'><?php echo $lang[105]; ?>
                            </small></a></td>
                    </tr>
                </table>
            </div>
        </form>
    </center><br/><br/>

<?php }

include("footer.php"); ?>