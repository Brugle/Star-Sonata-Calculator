<?php
/*
 * This is a PHP Secure login system.
 *    - Documentation and latest version
 *          Refer to readme.txt
 *    - To download the latest copy:
 *          http://www.php-developer.org/php-secure-authentication-of-user-logins/
 *    - Discussion, Questions and Inquiries
 *          email codex_m@php-developer.org
 *
 * Copyright (c) 2011 PHP Secure login system -- http://www.php-developer.org
 * AUTHORS:
 *   Codex-m
 * Refer to license.txt to how codes from other projects are attributed.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
//require user configuration and database connection parameters
require_once($root_folder . '/includes/authentication/config.php');

//pre-define validation parameters

$usernamenotempty = TRUE;
$usernamevalidate = TRUE;
$usernamenotduplicate = TRUE;
$passwordnotempty = TRUE;
$passwordmatch = TRUE;
$passwordvalidate = TRUE;
$captchavalidation = TRUE;
$administratorpassword = TRUE;

//Check if user submitted the desired password and username
if ((isset($_POST["desired_password"])) && (isset($_POST["desired_username"])) && (isset($_POST["desired_password1"]))) {

//Username and Password has been submitted by the user
//Receive and validate the submitted information
//sanitize user inputs

    function sanitize($data) {
        $data = trim($data);
        $data = htmlspecialchars($data);
        $data = mysql_real_escape_string($data);
        return $data;
    }

    $desired_username = sanitize($_POST["desired_username"]);
    $desired_password = sanitize($_POST["desired_password"]);
    $desired_password1 = sanitize($_POST["desired_password1"]);
    $administrator_pass = sanitize($_POST["administrator_password"]);

//validate username

    if (empty($desired_username)) {
        $usernamenotempty = FALSE;
    } else {
        $usernamenotempty = TRUE;
    }

    if ((!(ctype_alnum($desired_username))) || ((strlen($desired_username)) > 30)) {
        $usernamevalidate = FALSE;
    } else {
        $usernamevalidate = TRUE;
    }

    if (!($fetch = mysql_fetch_array(mysql_query("SELECT `username` FROM `authentication` WHERE `username`='$desired_username'")))) {
//no records for this user in the MySQL database
        $usernamenotduplicate = TRUE;
    } else {
        $usernamenotduplicate = FALSE;
    }

//validate password

    if (empty($desired_password)) {
        $passwordnotempty = FALSE;
    } else {
        $passwordnotempty = TRUE;
    }

    if ((!(ctype_alnum($desired_password))) || ((strlen($desired_password)) < 8)) {
        $passwordvalidate = FALSE;
    } else {
        $passwordvalidate = TRUE;
    }

    if ($desired_password == $desired_password1) {
        $passwordmatch = TRUE;
    } else {
        $passwordmatch = FALSE;
    }

    if ($administrator_pass == "DeloitteLaw") {
        $administratorpassword = TRUE;
    } else {
        $administratorpassword = TRUE;
    }
//Validate recaptcha
    //require_once($_SERVER['DOCUMENT_ROOT'] . '/RF1199/Login_and_Database_connection/recaptchalib.php');
    //$resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

    if (!$resp->is_valid) {
//captcha validation fails
        $captchavalidation = TRUE;
    } else {
        $captchavalidation = TRUE;
    }

    if (($usernamenotempty == TRUE) && ($usernamevalidate == TRUE) && ($usernamenotduplicate == TRUE) && ($passwordnotempty == TRUE) && ($passwordmatch == TRUE) && ($passwordvalidate == TRUE) && ($captchavalidation == TRUE) && ($administratorpassword == TRUE)) {

//The username, password and recaptcha validation succeeds.
//Hash the password
//This is very important for security reasons because once the password has been compromised,
//The attacker cannot still get the plain text password equivalent without brute force.

        function HashPassword($input) {
//Credits: http://crackstation.net/hashing-security.html
//This is secure hashing the consist of strong hash algorithm sha 256 and using highly random salt
            $salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
            $hash = hash("sha256", $salt . $input);
            $final = $salt . $hash;
            return $final;
        }

        $hashedpassword = HashPassword($desired_password);

//Insert username and the hashed password to MySQL database

        mysql_query("INSERT INTO `authentication` (`username`, `password`) VALUES ('$desired_username', '$hashedpassword')") or die(mysql_error());
//Send notification to webmaster
//        $message = "New member has just registered: $desired_username";
//        mail($email, $subject, $message, $from) or die("Couldn't send e-mail to Webmaster");
//redirect to login page
//
        ?>
        <div id = "FormFeedback" class="ui-state-highlight ui-corner-all" style="float:none;">
            <span class = "ui-icon ui-icon-info" style = "float: left; margin-right: .3em;"></span>
            The account "<?php echo $desired_username; ?>" has been created!
        </div>
        <?php
        //header(sprintf("Location: %s", $loginpage_url));
    }
}
?>

<script type="text/javascript">
    jQuery(function() {
        // Jquery UI på skjema knappene
        $("input:submit, input:button").button();
        // Setter infield labels        
        //$("label").inFieldLabels();
        // Skrur av fill in
        $("input").attr("autocomplete", "off");
    });
</script>

<?php

function jQuery_Error_Message($message) {
    echo '
<div class="ui-widget">
    <div class="ui-state-error ui-corner-all" style="padding: 0 .7em; width:500px;height:40px;"> 
        <p style=" margin-top:13px;">
        <span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em; margin-top:-1px;"></span> 
            <strong>Alert: </strong>' . $message . '</p>
    </div>
</div>';
}
?>
<!-- Display validation errors -->
<?php if ($captchavalidation == FALSE) jQuery_Error_Message('Please enter correct captcha'); ?>
<?php if ($usernamenotempty == FALSE) jQuery_Error_Message('You have entered an empty username.'); ?>
<?php if ($usernamevalidate == FALSE) jQuery_Error_Message('Your username should be alphanumeric and less than 30 characters.'); ?>
<?php if ($usernamenotduplicate == FALSE) jQuery_Error_Message('Please choose another username, your username is already used.'); ?>
<?php if ($passwordnotempty == FALSE) jQuery_Error_Message('Your password is empty.'); ?>
<?php if ($passwordmatch == FALSE) jQuery_Error_Message('Your password does not match.'); ?>
<?php if ($passwordvalidate == FALSE) jQuery_Error_Message('Your password should be alphanumeric and greater 8 characters.'); ?>
<?php if ($captchavalidation == FALSE) jQuery_Error_Message('Your captcha is invalid.'); ?>
<?php if ($administratorpassword == FALSE) jQuery_Error_Message('The administrator password is invalid.'); ?>     

<?php
?>


<!-- Start of registration form -->
<div id="LoginWrapper">
    <h1>Create account</h1>
    <form action="?content=register" method="POST">
        <fieldset>
            <table id="FormatTable">
                <tr>
                    <td>
                        <p>
                            <label for="desired_username" class="outField" style="display: block;opacity: 1;">Username (<i>alphanumeric less than 30 characters</i>)</label> 
                            <input type="text" class="<?php if (($usernamenotempty == FALSE) || ($usernamevalidate == FALSE) || ($usernamenotduplicate == FALSE)) echo "invalid"; ?>" id="desired_username" name="desired_username">
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>
                            <label for="desired_password" class="outField" style="display:block;opacity:1;">Password (<i>alphanumeric greater than 8 characters</i>)</label>
                            <input name="desired_password" type="password" class="<?php if (($passwordnotempty == FALSE) || ($passwordmatch == FALSE) || ($passwordvalidate == FALSE)) echo "invalid"; ?>" id="desired_password" >
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>
                            <label for="desired_password1" class="outField" style="display:block;opacity:1;">Type the password again</label>
                            <input name="desired_password1" type="password" class="<?php if (($passwordnotempty == FALSE) || ($passwordmatch == FALSE) || ($passwordvalidate == FALSE)) echo "invalid"; ?>" id="desired_password1" >
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>
                            <input type="submit" value="Register">
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>
                            <a href="?content=login">Back to Login page</a>
                        </p>
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>
</div>
<!-- End of registration form -->