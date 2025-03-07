<?php
$dbuser = "root"; /* Database Username */
$dbpass = ""; /* Database Username Password */
$host = "localhost"; /* Database Host */
$db = "chama_management_system";  /* Database Name */
$mysqli = new mysqli($host, $dbuser, $dbpass, $db); /* Connection Function */

date_default_timezone_set("Africa/Nairobi");/* Default Time Zone */

/* Greetings */
$dat = new DateTime('now', new DateTimeZone('Africa/Nairobi'));
$date = $dat->format('H');
if ($date < 12)
    $greeting  =   "Good Morning";
else if ($date < 17)
    $greeting =  "Good Afternoon";
else if ($date < 20)
    $greeting =  "Good Evening";
else
    $greeting =  "Good Evening";


/* Determine if its running on HTTP */
if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
    $uri = 'https://';
} else {
    $uri = 'http://';
}
$uri .= $_SERVER['HTTP_HOST'];

/* Global directory */
$base_dir = $uri . "/COMS/views/";
global $base_dir;
/* API key */
define('AT_API_KEY', 'atsk_2c6401ca0a934798f8ef906f6ad278d166a01112fa1d61a4a50e7defccdf058465ac7ed1');
