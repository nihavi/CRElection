<?php
	//Submit Request
	require_once("config.php");
	session_start();

	function block_voting() {
		global $DB, $IP;
		$query = mysqli_prepare($DB, "UPDATE `clients` set allow_vote = 0 WHERE ip = ? ");
		mysqli_stmt_bind_param($query, 's', $IP);
		if ( !mysqli_stmt_execute($query) ) {
			die("Some error occurred. Contact Administrator.");
		}
	}

	if ( !allowed() ) {
		header("Location: ".$base_url);
		die();
	}

	if (!$is_stv) {
		if ( isset( $_POST["candidate_id"] ) && count($_POST["candidate_id"]) ) {
			if ( $negative_votes ) {
				// First also check for negative votes
				if ( isset( $_POST["n_candidate_id"] ) && count($_POST["n_candidate_id"]) ) {
					$n_candidates = $_POST["n_candidate_id"];
				}
				else {
					$n_candidates = array();
				}
			}
			// Process votes
			$candidates = $_POST["candidate_id"];
			if ( ( empty($multiple_votes) || empty($max_votes) ) && count($candidates) != 1) {
				block_voting();
				die("Something is wrong");
			}
			else if ( count($candidates) != $max_votes ) {
				block_voting();
				die("Something is wrong");
			}
			else if ( count($n_candidates) > $max_n_votes ) {
				block_voting();
				die("Something is wrong");
			}
			foreach ( $candidates as $candidate ) {
				$query = mysqli_prepare($DB, "UPDATE `candidates` set votes = votes + 1 WHERE id=?");
				mysqli_stmt_bind_param($query, 'i', $candidate);
				if ( !mysqli_stmt_execute($query) ) {
					die("Some error occurred. Response was not recorded. Contact Administrator.");
				}
			}
			foreach ( $n_candidates as $candidate ) {
				$query = mysqli_prepare($DB, "UPDATE `candidates` set n_votes = n_votes + 1 WHERE id=?");
				mysqli_stmt_bind_param($query, 'i', $candidate);
				if ( !mysqli_stmt_execute($query) ) {
					die("Some error occurred. Response was not recorded. Contact Administrator.");
				}
			}
			block_voting();
		}
		else {
			block_voting();
			die("No Candidate Selected");
		}
	} else {
		// is STV
		if ( isset( $_POST["candidates_string"] ) && strlen($_POST["candidates_string"]) ) {
			$candidates = explode(" ", $_POST["candidates_string"]);
			$candidates_string = $_POST["candidates_string"];

			$uniq_candidates = array_unique($candidates);

			if (count($uniq_candidates) != count($candidates)) {
				block_voting();
				die("Something is wrong");
			}

			$registered_candidates = get_candidates();

			if ( count($candidates) > count($registered_candidates) || count($candidates) < $delegation_size) {
				block_voting();
				die("Something is wrong");
			}

			mysqli_autocommit($DB, false);
			mysqli_begin_transaction($DB);

			$id_query = mysqli_query($DB, "SELECT id from `votes` WHERE vote_string IS NULL ORDER BY RAND() LIMIT 1");
			$id = mysqli_fetch_assoc($id_query)['id'];
			$query = mysqli_prepare($DB, "UPDATE `votes` SET vote_string = ? WHERE id = ?");
			mysqli_stmt_bind_param($query, 'si', $candidates_string, $id);

			if ( !mysqli_stmt_execute($query) ) {
				die("Some error occurred. Response was not recorded. Contact Administrator.");
			}
			mysqli_commit($DB);
			mysqli_autocommit($DB, true);
			block_voting();
		} else {
			block_voting();
			die("Some error occurred. Did not receive vote. Please contact administrator.");
		}
	}

	$_SESSION["done_voting"] = true;
	header("Location: ".$base_url);
