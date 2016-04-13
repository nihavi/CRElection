<?php
	//Allow one vote
	$auth_required = true;
	require_once("../config.php");

	if ( isset( $_POST["allowed"]) ) {
		$query = mysqli_prepare($DB, "UPDATE `clients` set allow_vote = 1 WHERE ip = ? ");
		mysqli_stmt_bind_param($query, 's', $_POST["allowed"]);
		if ( !mysqli_stmt_execute($query) ) {
			die("Some error occurred. Couldn't allow voting. Please contact Administrator.");
		}
	}
	if ( empty($_GET['noRed']) ) {
		header("Location:".$base_url."admin");
	}
