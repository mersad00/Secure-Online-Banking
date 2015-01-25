<?php

define("ASSETS_URL", "/ws14secure");
define("PHP_URL", "/ws14secure/php/");
define("RESUBMIT", "Your request has expired, please resubmit!");
define("BASE_URL", "https://".$_SERVER['SERVER_ADDR']."/ws14secure/php");


// Error messages
define("WRONG_CREDENTIALS", "Username or Password is invalid.");
define("EMPTY_INPUT", "Please fill in the required fields.");
define("NOT_ACTIVE", "User is not activated yet!");
define("ACCOUNT_LOCKED", "Your account is locked due to many attempts. Please <a href=\"mailto:g16.banking@gmail.com?Subject=Account Blocked\">contact</a> an administrator.");
define("NO_USER", "User does not exist!");
define("USER_EXISTS", "Please try a different username or email");
define("RESETLINK_SENT", "Your password reset link is sent to your e-mail address.");
define("INVALID_EMAIL", "Invalid email address. Please type a valid email!");
define("NOT_ROBOT", "Are you a human or a robot? Let us find out :)");
define("INVALID_KEY", "Invalid key or the reset request expired! Please try again. <a href=\"https://localhost/ws14secure/php/forgot_password.php\"><span class=\"small\">Forget Password?</span></a>");
define("PASSWORD_CHANGED", "Your password changed sucessfully!<br/><a href='index.php'><span class='small'>Login</span></a>.");
define("INVALID_TOKEN", "Token is invalid. Please try again!");
// reCaptcha
define("SITE_KEY", "6Lf3fv4SAAAAAJ2RXwpoxSrGUpLrmnpfHoVaQws-");
define("SECRET_KEY", "6LdXvAATAAAAAJ-IDSYbhG-Afey_VU0AZfb7Ya5j");
define("LANGUAGE", "en");


?>
