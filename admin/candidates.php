<?php
	require_once("../config.php");
	
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
	
	$htmlOutput = "";
	
	$htmlOutput .= "<h1>Candidates</h1>";
	$candidates = get_candidates();
	if ( count($candidates) != 0 ) {
		$htmlOutput .= "<ul>";
		foreach ( $candidates as $id => $name ){
			$htmlOutput .= "<li>".$name."</li>";
		}
		$htmlOutput .= "</ul>";
	}
	else {
		$htmlOutput .= "None<br>";
	}
	
	$htmlOutput .= "<br><hr><br>";
	$htmlOutput .= "<h3>Add a candidate</h3>";
	$htmlOutput .= "<form action='add_candidate.php' method='post' autocomplete='off'>
			Name <input type='text' name='candidate_name' required><br>
			<input type='submit' value='Add Candidate'>";
	
	$htmlOutput .= "<script>document.body.onload=function(){document.getElementsByName('candidate_name')[0].focus()}</script>";
	
	$htmlOutput .= "<br><br><br><br><br><br><a href='index.php'>Go to Allow Voting</a>";
	
	include("../template.php");