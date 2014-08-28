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
