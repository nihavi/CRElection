<?php
	//CR Election portal
	require_once("config.php");
	session_start();
	function get_candidates() {
		global $DB;
		$query = mysqli_prepare($DB, "SELECT id, name FROM `candidates`");
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $id, $name);
		mysqli_stmt_store_result($query);
		$results = array();
		while(mysqli_stmt_fetch($query)){
			$results[$id] = $name;
		}
		return $results;
	}

	$htmlOutput = '';

	if ( allowed() ) {
		if ( (empty($multiple_votes) || $max_votes <= 1) ) {
			$input_type = 'radio';
			$input_req = 'required';
		}
		else {
			$htmlOutput .= "
			<script>
				function checkVotes(form) {
					var allowed = ".$max_votes.";
					var inputs = form.getElementsByTagName('input');
					var voted = 0;
					for ( i=0; i<inputs.length; i++ ) {
						if ( inputs[i].name == 'candidate_id[]' && inputs[i].checked )
							++voted;
					}
					if ( voted != allowed ) {
						alert('Vote for exactly '+allowed+' candidates.');
						return false;
					}
					return true;
				}
			</script>
			";
			$input_type = 'checkbox';
			$input_req = '';
		}
		$htmlOutput .= "<form action='vote.php' method='post' onsubmit='return checkVotes(this)'>";
		$candidates = get_candidates();
		if ( count($candidates) ) {
			foreach ( $candidates as $id => $name ){
				$htmlOutput .= ("<label><input type='$input_type' name='candidate_id[]' value='$id' $input_req>$name</label><br>" );
			}
			$htmlOutput .= "<input class='btn' type='submit' value='Vote'></form>";
		}
		else {
			$htmlOutput .= "No Candidates in the list.";
		}
	}
	else {
		if ( isset( $_SESSION["done_voting"] ) && $_SESSION["done_voting"] ) {
			$htmlOutput .= "Your response has been recorded.<br><a class='btn' href=''>Refresh</a>";
			$htmlOutput .= "<script>document.body.onload=function(){setTimeout(function(){window.location=''}, 3000)}</script>";
			unset($_SESSION["done_voting"]);
		}
		else {
			$htmlOutput .= "<strong>Access denied.</strong> Please ask the administrator to allow you to vote. <br><a class='btn' href=''>Reload</a>";
			$htmlOutput .= "<script>document.body.onload=function(){setTimeout(function(){window.location=''}, 1000)}</script>";
		}
	}

	include('template.php');
