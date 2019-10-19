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

define("RECAPTCHA_API_SERVER", "http://www.google.com/recaptcha/api");
define("RECAPTCHA_API_SECURE_SERVER", "https://www.google.com/recaptcha/api");
define("RECAPTCHA_VERIFY_SERVER", "https://www.google.com/recaptcha/api/siteverify");

/**
 * Encodes the given data into a query string format
 * @param $data - array of string elements to be encoded
 * @return string - encoded request
 */
function _recaptcha_qsencode($data)
{
    $req = "";
    foreach ($data as $key => $value)
        $req .= $key . '=' . urlencode(stripslashes($value)) . '&';

    // Cut the last '&'
    $req = substr($req, 0, strlen($req) - 1);
    return $req;
}


/**
 * Submits an HTTP POST to a reCAPTCHA server
 * @param string $host
 * @param string $path
 * @param array $data
 * @param int port
 * @return array response
 */
function _recaptcha_http_post($host, $path, $data)
{

    $req = _recaptcha_qsencode($data);

    $response = file_get_contents($host . $path . '?' . $req);
    $responseKeys = json_decode($response,true);
    return $responseKeys;
}


/**
 * Gets the challenge HTML (javascript and non-javascript version).
 * This is called from the browser, and the resulting reCAPTCHA HTML widget
 * is embedded within the HTML form it was called from.
 * @param string $pubkey A public key for reCAPTCHA
 * @param string $error The error given by reCAPTCHA (optional, default is null)
 * @param boolean $use_ssl Should the request be made over ssl? (optional, default is false)
 * @return string - The HTML to be embedded in the user's form.
 */
function recaptcha_get_html($pubkey, $error = null, $use_ssl = false)
{
    if ($pubkey == null || $pubkey == '') {
        die ("To use reCAPTCHA you must get an API key from <a href='http://recaptcha.net/api/getkey'>http://recaptcha.net/api/getkey</a>");
    }

    if ($use_ssl) {
        $server = RECAPTCHA_API_SECURE_SERVER;
    } else {
        $server = RECAPTCHA_API_SERVER;
    }

    $errorpart = "";
    if ($error) {
        $errorpart = "&amp;error=" . $error;
    }
    return '<script type="text/javascript" src="' . $server . '.js"></script>

        <div class="g-recaptcha" data-sitekey="'.$pubkey.'"></div>';
}


/**
 * A ReCaptchaResponse is returned from recaptcha_check_answer()
 */
class ReCaptchaResponse
{
    var $is_valid;
    var $error;
}


/**
 * Calls an HTTP POST function to verify if the user's guess was correct
 * @param string $privkey
 * @param string $remoteip
 * @param string $challenge
 * @param string $response
 * @param array $extra_params an array of extra variables to post to the server
 * @return ReCaptchaResponse
 */
function recaptcha_check_answer($privkey, $challenge, $extra_params = array())
{
    if ($privkey == null || $privkey == '') {
        die ("To use reCAPTCHA you must get an API key from <a href='http://recaptcha.net/api/getkey'>http://recaptcha.net/api/getkey</a>");
    }

    //discard spam submissions
    if ($challenge == null || strlen($challenge) == 0) {
        $recaptcha_response = new ReCaptchaResponse();
        $recaptcha_response->is_valid = false;
        $recaptcha_response->error = 'incorrect-captcha-sol';
        return $recaptcha_response;
    }

    $response = _recaptcha_http_post(RECAPTCHA_API_SECURE_SERVER, "/siteverify",
        array(
            'secret' => $privkey,
            'response' => $challenge
        ) + $extra_params
    );

    $answers = $response['success'];
    $recaptcha_response = new ReCaptchaResponse();

    if (trim($answers) == true) {
        $recaptcha_response->is_valid = true;
    } else {
        $recaptcha_response->is_valid = false;
        $recaptcha_response->error = $response['error-codes'][0];
    }
    return $recaptcha_response;

}

function _recaptcha_aes_pad($val)
{
    $block_size = 16;
    $numpad = $block_size - (strlen($val) % $block_size);
    return str_pad($val, strlen($val) + $numpad, chr($numpad));
}

/* Mailhide related code */

function _recaptcha_aes_encrypt($val, $ky)
{
    if (!function_exists("mcrypt_encrypt")) {
        die ("To use reCAPTCHA Mailhide, you need to have the mcrypt php module installed.");
    }
    $mode = MCRYPT_MODE_CBC;
    $enc = MCRYPT_RIJNDAEL_128;
    $val = _recaptcha_aes_pad($val);
    return mcrypt_encrypt($enc, $ky, $val, $mode, "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0");
}


function _recaptcha_mailhide_urlbase64($x)
{
    return strtr(base64_encode($x), '+/', '-_');
}

/* gets the reCAPTCHA Mailhide url for a given email, public key and private key */
function recaptcha_mailhide_url($pubkey, $privkey, $email)
{
    if ($pubkey == '' || $pubkey == null || $privkey == "" || $privkey == null) {
        die ("To use reCAPTCHA Mailhide, you have to sign up for a public and private key, " .
            "you can do so at <a href='http://mailhide.recaptcha.net/apikey'>http://mailhide.recaptcha.net/apikey</a>");
    }


    $ky = pack('H*', $privkey);
    $cryptmail = _recaptcha_aes_encrypt($email, $ky);

    return "http://mailhide.recaptcha.net/d?k=" . $pubkey . "&c=" . _recaptcha_mailhide_urlbase64($cryptmail);
}

/**
 * gets the parts of the email to expose to the user.
 * eg, given johndoe@example,com return ["john", "example.com"].
 * the email is then displayed as john...@example.com
 */
function _recaptcha_mailhide_email_parts($email)
{
    $arr = preg_split("/@/", $email);

    if (strlen($arr[0]) <= 4) {
        $arr[0] = substr($arr[0], 0, 1);
    } else if (strlen($arr[0]) <= 6) {
        $arr[0] = substr($arr[0], 0, 3);
    } else {
        $arr[0] = substr($arr[0], 0, 4);
    }
    return $arr;
}

/**
 * Gets html to display an email address given a public an private key.
 * to get a key, go to:
 *
 * http://mailhide.recaptcha.net/apikey
 */
function recaptcha_mailhide_html($pubkey, $privkey, $email)
{
    $emailparts = _recaptcha_mailhide_email_parts($email);
    $url = recaptcha_mailhide_url($pubkey, $privkey, $email);

    return htmlentities($emailparts[0]) . "<a href='" . htmlentities($url) .
        "' onclick=\"window.open('" . htmlentities($url) . "', '', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=300'); return false;\" title=\"Reveal this e-mail address\">...</a>@" . htmlentities($emailparts [1]);

}


?>
