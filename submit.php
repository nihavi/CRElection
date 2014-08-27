<?php
	//Submit Request
	require_once("config.php");

	if ( isset( $_POST["candidate_id"] ) ) {
		$query = mysqli_prepare($DB, "UPDATE `candidates` set votes = votes + 1 WHERE id=?");
		mysqli_stmt__bind_param($query, 'i', $_POST["candidate_id"]);
		if ( !mysqli_stmt_execute($query) ) {
			die("Some Error Occured. Response is not recorded. Contact Administrator.");
		}
	}
	else {
		die("No Candidate Selected");
	}
	header("Location: ".$base_url);
