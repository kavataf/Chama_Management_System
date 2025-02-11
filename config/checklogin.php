<?php
function check_login()
{
	if ((strlen($_SESSION['user_id'])=='0')) {
		$host = $_SERVER['HTTP_HOST'];
		$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra = "../views/login";
		$_SESSION["user_id"] = "";
		header("Location: http://$host$uri/$extra");
	}
}

$user_access_level = mysqli_real_escape_string($mysqli, $_SESSION['user_access_level']);
$user_id = mysqli_real_escape_string($mysqli, $_SESSION['user_id']);
$user_name  = mysqli_real_escape_string($mysqli, $_SESSION['user_name']);

global $user_access_level, $user_id, $user_name;

/* Invoke IT */
check_login();