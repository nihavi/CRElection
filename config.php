<?php
ini_set('display_errors','On');
error_reporting(E_ALL);

	define('DB_HOST', 'localhost');
	define('DB_USER', 'CRE');
	define('DB_PASS', 'cre12321');
	define('DB_NAME', 'CRE');
	
	$base_url = 'http://localhost/CRElection/';
	
	// Update the name of the election
	$electionName = 'CR Election';



//---------------------------------------------------------------------//

	// Database connection
	$DB = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if (mysqli_connect_errno()){
			die("Failed to connect to MySQL: " . mysqli_connect_error());
	}
	
	function auth(){
		header('WWW-Authenticate: Basic realm="Authentication Required"');
		header('HTTP/1.0 401 Unauthorized');
		echo '<h1>Access denied.</h1>';
		exit;
	}
	
	if (!empty($auth_required)){
		if (!isset($_SERVER['PHP_AUTH_USER'])) {
			auth();
		} else {
			$query = mysqli_prepare($DB, "SELECT meta_value FROM `meta` WHERE `meta_name`='admin_pass'");
			mysqli_stmt_execute($query);
			mysqli_stmt_bind_result($query, $pass);
			mysqli_stmt_store_result($query);
			if(mysqli_stmt_fetch($query)){
				if ($_SERVER['PHP_AUTH_USER'] == 'admin' && sha1($_SERVER['PHP_AUTH_PW']) != $pass){
					auth();
				}
			}
		}
	}
