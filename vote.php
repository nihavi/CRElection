<?php
	//Submit Request
	require_once("config.php");
	session_start();

	function block_voting() {
		global $DB;
		$query = mysqli_prepare($DB, "UPDATE `meta` set meta_value = 0 WHERE meta_name='vote_allowed' ");
		if ( !mysqli_stmt_execute($query) ) {
			die("Some Error Occured. Contact Administrator.");
		}
	}

	if ( !allowed() ) {
		header("Location: ".$base_url);
		die();
	}
	if ( isset( $_POST["candidate_id"] ) && count($_POST["candidate_id"]) ) {
		$candidates = $_POST["candidate_id"];
		if ( ( empty($multiple_votes) || empty($max_votes) ) && count($candidates) != 1) {
			block_voting();
			die("Something is wrong");
		}
		else if ( count($candidates) != $max_votes ) {
			block_voting();
			die("Something is wrong");
		}
		foreach ( $candidates as $candidate ) {
			$query = mysqli_prepare($DB, "UPDATE `candidates` set votes = votes + 1 WHERE id=?");
			mysqli_stmt_bind_param($query, 'i', $candidate);
			if ( !mysqli_stmt_execute($query) ) {
				die("Some Error Occured. Response is not recorded. Contact Administrator.");
			}
		}
		block_voting();
	}
	else {
		block_voting();
		die("No Candidate Selected");
	}
	$_SESSION["done_voting"] = true;
	header("Location: ".$base_url);
