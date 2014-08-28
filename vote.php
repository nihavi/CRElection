<?php
	//Submit Request
	require_once("config.php");
	session_start();
	if ( isset( $_POST["candidate_id"] ) ) {
		$query = mysqli_prepare($DB, "UPDATE `candidates` set votes = votes + 1 WHERE id=?");
		mysqli_stmt_bind_param($query, 'i', $_POST["candidate_id"]);
		if ( !mysqli_stmt_execute($query) ) {
			die("Some Error Occured. Response is not recorded. Contact Administrator.");
		}
		$query = mysqli_prepare($DB, "UPDATE `meta` set meta_value = 0 WHERE meta_name='vote_allowed' ");
		if ( !mysqli_stmt_execute($query) ) { 
			die("Some Error Occured. Contact Administrator.");
		}
	}
	else {
		die("No Candidate Selected");
	}
	$_SESSION["done_voting"] = true;
	header("Location: ".$base_url);
