<?php
	//Add Client
	$auth_required = true;
	require_once("../config.php");

	if ( isset($_POST["client_ip"]) ) {
		$name = isset($_POST["client_name"]) ? $_POST["client_name"] : '';
		$ip = $_POST["client_ip"];
		if ( strpos($ip, 'remove:') !== 0 ) {
			$query = mysqli_prepare($DB, "INSERT INTO `clients` (name, ip) VALUES (?, ?)");
			mysqli_stmt_bind_param($query, 'ss', $name, $ip);
			if ( !mysqli_stmt_execute($query) ) {
				die("Some error occured. Coundn't add client. Contact Administrator.");
			}
		}
		else {
			$ip = str_replace('remove:','',$ip);
			$ip = trim($ip);
			$query = mysqli_prepare($DB, "DELETE FROM `clients` WHERE ip = ?");
			mysqli_stmt_bind_param($query, 's', $ip);
			if ( !mysqli_stmt_execute($query) ) {
				die("Some Error Occured. Coundn't remove client. Contact Administrator.");
			}
		}
	}
	else {
		die("IP Can't be empty");
	}

	header("Location: ".$base_url."admin/clients.php");
