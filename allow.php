<?php
	//Allow one vote
	require_once("../config.php");

	if ( isset( $_POST["allowed"] ) {
		$query = mysqli_prepare($DB, "UPDATE `meta` set meta_value = 1 WHERE meta_name='vote_allowed' ");
		if ( !mysqli_stmt_execute($query) ) { 
			die("Some Error Occured. Coundn't allow voting. Contact Administrator.");
		}

	}
	header("Location:".$base_url."admin");
