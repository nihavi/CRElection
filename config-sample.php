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

	$multiple_votes = false;
	$max_votes = 1;

	$negative_votes = false;
	$max_n_votes = 1;

	$is_stv = false;
//---------------------------------------------------------------------//
// Set default values for config option

	if ( !(isset($multiple_votes) && $multiple_votes === true) )
		$multiple_votes = false;

	if ( !isset($max_votes) )
		$max_votes = 1;

	if ( !(isset($negative_votes) && $negative_votes === true) ) {
		$negative_votes = false;
		$max_n_votes = 0;
	}

	if ( !isset($max_n_votes) )
		$max_n_votes = 1;

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
				if ($_SERVER['PHP_AUTH_USER'] != 'admin' || sha1($_SERVER['PHP_AUTH_PW']) != $pass){
					auth();
				}
			}
		}
	}

//---------------------------------------------------------------------//
//Common functions

	$IP = $_SERVER['REMOTE_ADDR'];

	function allowed() {
		global $DB, $IP;
		$query = mysqli_prepare($DB, "SELECT allow_vote FROM `clients` WHERE ip = ?");
		mysqli_stmt_bind_param($query, 's', $IP);
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $allowed);
		mysqli_stmt_store_result($query);
		mysqli_stmt_fetch($query);
		return $allowed == 1 ? true : false ;
	}

	function get_clients() {
		global $DB;
		$query = mysqli_prepare($DB, "SELECT id, name, ip FROM `clients`");
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $id, $name, $ip);
		mysqli_stmt_store_result($query);
		$results = array();
		while(mysqli_stmt_fetch($query)){
			$results[$id] = array(
				"name" => $name,
				"ip" => $ip
			);
		}
		return $results;
	}
