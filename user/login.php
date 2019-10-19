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
subheader($lang[32], '', '', 'login');

if (isset($_GET['r'])) {
    $r = htmlspecialchars(trim($_GET['r']));
}

if ($r == 'verify') {
    if (isset($_POST["login"])) {
        $username = htmlspecialchars(trim($_POST["username"]));
        $password = htmlspecialchars(trim($_POST["password"]));

        if (($username != "") && ($password != "")) {
            $resp = recaptcha_check_answer($privatekey, $_POST["g-recaptcha-response"]);
            if ($resp->is_valid) {
                $q = "SELECT * FROM `members` WHERE (email = '{$username}') and (password = '{$password}')";
                if (!($result_set = mysqli_query($link, $q))) {
                    die(mysqli_error($link));
                }
                $n2 = mysqli_num_rows($result_set);

                if (!$n2) {
                    $err = "<div class='errorbox'><p>{$lang[37]}</p></div>";
                    showcaptcha($username);
                } else {
                    $f = mysqli_fetch_array($result_set);
                    $verified = $f['verified'];
                    $banned = $f['banned'];

                    if ($verified == '0') {
                        $err = "<div class='errorbox'><p>{$lang[38]} <small><a href='{$website}/user/resend.php'>{$lang[72]}</a></small></p></div>";
                        showcaptcha($username);
                    } else {
                        if ($banned == 1) {
                            $err = "<div class='errorbox'><p>{$lang[48]}<br/><small>{$lang[57]}</small></p></div>";
                            showcaptcha($username);
                        } else {
                            $date = date("d M Y");
                            $q = mysqli_query($link, "UPDATE `members` SET access = '{$date}' WHERE email = '{$username}'");
                            $up = mysqli_query($link, "UPDATE `members` SET `login_attempt` = 0 WHERE `email`='{$username}' LIMIT 1") or die(mysqli_error($link));
                            $_SESSION["user"] = $username;
                            $_SESSION["pass"] = $password;
                            echo "<center><div id='loginmsg'><img src='{$website}/images/working.gif' /><br/>";
                            echo "<p>{$lang[41]}</p></div></center>";
                            echo "<meta http-equiv='Refresh' Content='5;URL={$website}/' />";
                        }
                    }
                }
            } else {
                $err = "<div class='errorbox'><p>{$lang[54]}</p></div>";
                showcaptcha($username);
            }
        } else {
            $err = "<div class='errorbox'><p>{$lang[39]}</p></div>";
            showcaptcha($username);
        }
    }
} elseif ($r == 'reg') {
    if (isset($_POST["login"])) {
        $username = htmlspecialchars(trim($_POST["username"]));
        $password = htmlspecialchars(trim($_POST["password"]));

        if (($username != "") && ($password != "")) {
            $a = mysqli_query($link, "SELECT login_attempt FROM `members` WHERE(email='{$username}')") or die(mysqli_error($link));
            $ac = mysqli_num_rows($a);
            if ($ac) {
                $f = mysqli_fetch_array($a);
                $login_attempt = $f['login_attempt'];
                if ($login_attempt > 4) {
                    $err = "<div class='errorbox'><p>{$lang[73]}</p></div>";
                    showcaptcha($username);
                } else {
                    $q = "SELECT * FROM `members` WHERE (email = '{$username}') and (password = '{$password}')";
                    if (!($result_set = mysqli_query($link, $q))) {
                        die(mysqli_error($link));
                    }
                    $n2 = mysqli_num_rows($result_set);

                    if (!$n2) {
                        $attempt = $login_attempt + 1;
                        $up = mysqli_query($link, "UPDATE `members` SET `login_attempt` = '{$attempt}' WHERE `email`='{$username}' LIMIT 1") or die(mysqli_error($link));
                        $err = "<div class='errorbox'><p>{$lang[37]}</p></div>";
                        am_showLogin();
                    } else {
                        $f = mysqli_fetch_array($result_set);
                        $verified = $f['verified'];
                        $banned = $f['banned'];

                        if ($verified == '0') {
                            $err = "<div class='errorbox'><p>{$lang[38]} <small><a href='{$website}/user/resend.php'>{$lang[72]}</a></small></p></div>";
                            am_showLogin();
                        } else {
                            if ($banned == 1) {
                                $err = "<div class='errorbox'><p>{$lang[48]}<br/><br/><small>{$lang[57]}</small></p></div>";
                                am_showLogin();
                            } else {
                                $date = date("d M Y");
                                $q = mysqli_query($link, "UPDATE `members` SET access = '{$date}' WHERE email = '{$username}'");
                                $up = mysqli_query($link, "UPDATE `members` SET `login_attempt` = 0 WHERE `email`='{$username}' LIMIT 1") or die(mysqli_error($link));
                                $_SESSION["user"] = $username;
                                $_SESSION["pass"] = $password;
                                echo "<center><div id='loginmsg'><img src='{$website}/images/working.gif' /><br/>";
                                echo "<p>{$lang[41]}</p></div></center>";
                                echo "<meta http-equiv='Refresh' Content='5;URL={$website}/' />";
                            }
                        }
                    }
                }
            } else {
                $err = "<div class='errorbox'><p>{$lang[37]}</p></div>";
                am_showLogin();
            }
        } else {
            $err = "<div class='errorbox'><p>{$lang[39]}</p></div>";
            am_showLogin();
        }
    }
} else {
    if ($sesslife == false) {
        am_showLogin();
    } else {
        echo "<br/><center><div class='errorbox' style='width:960px;'><p>{$lang[40]}<br/><small>You cannot perform this action while you are logged in to the website.</small></p></div></center><br/><br/>";
    }
}

function showcaptcha($username)
{
    global $lang;
    global $website;
    global $webtitle;
    global $err;
    global $publickey; ?>
    <center>
        <form method="POST" action="<?php echo $website; ?>/user/login.php?r=verify" name="myForm">
            <div class="logindiv">
                <div id="logindiv-header"><p><?php echo $lang[11]; ?></p></div>
                <?php echo $err; ?>
                <table>
                    <tr>
                        <td class="first"><label><?php echo $lang[3]; ?>: </label></td>
                        <td><input type="text" name="username" value="<?php echo $username; ?>"></td>
                    </tr>
                    <tr>
                        <td class="first"><label><?php echo $lang[4]; ?>: </label></td>
                        <td><input type="password" name="password"></td>
                    </tr>
                    <tr>
                        <td class="first"><label><?php echo $lang[51]; ?>: </label></td>
                        <td><?php echo recaptcha_get_html($publickey); ?></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><input type='submit' name='login' class='button' value='<?php echo $lang[105]; ?>'/></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <small><a href='<?php echo $website; ?>/user/forgot.php'><?php echo $lang[68]; ?></a> <?php echo $lang[74]; ?>
                                .</small></td>
                    </tr>
                </table>
            </div>
        </form>
    </center><br/><br/>

<?php }

include("footer.php"); ?>