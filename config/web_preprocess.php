<?php
	/* Set the path prefix */
	if (!isset($prefix)) {
		$prefix = "../";
	}
	
	/* require the database setting */
	require_once $prefix."config/database_config.php";
	
	/* Initialize the cookie setting */
	//ini_set("session.cookie_secure", 1);
	ini_set("session.cookie_httponly", true);
	ini_set("session.use_strict_mode", true);
	
	/* Initialize session */
	session_start();
	
	/* Initialize the cookie setting */
	//ini_set("session.cookie_domain", WEB_DOMAIN_NAME);
	
	/* require the website setting */
	require_once $prefix."config/web_function.php";
	require_once $prefix."config/web_view.php";
	require_once $prefix."config/class/db.class.php";
	
	/* require the PHP class files */
	if (!empty($classFile = classFileLoader($prefix."config/class/"))) {
		foreach ($classFile as $tmp) {
			require_once $prefix."config/class/".$tmp;
		}
	}
	
	/* Initialize the header setting */
	header("X-XSS-Protection: 1; mode=block");
	header("X-Frame-Options: ".WEB_DOMAIN_NAME);
	header("Cache-Control: no-cache, must-revalidate");
	date_default_timezone_set("Asia/Taipei");
	
	/* Record the user IP */
	$ip_client = (!empty($_SERVER['HTTP_CLIENT_IP'])) ? $_SERVER['HTTP_CLIENT_IP'] : null;
	$ip_forwarded = (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;
	$ip_remote = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : null;
	
	/* Set the current time */
	$current_time = time();
	
	if (isset($_COOKIE['rem_user']) and isset($_COOKIE['rem_verify']) and !isset($_SESSION['uid'])) {
		$check_login = new account('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
		$check_login->check_login();
	}
?>