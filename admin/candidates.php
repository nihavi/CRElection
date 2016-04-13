<?php
	$auth_required = true;
	require_once("../config.php");

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
			<input class='btn' type='submit' value='Add Candidate'>";

	$htmlOutput .= "<script>document.body.onload=function(){document.getElementsByName('candidate_name')[0].focus()}</script>";

	$htmlOutput .= "<br><br><br><br><a class='btn btn-green' href='".$base_url."admin/'>Go to Allow Voting</a>";

	include("../template.php");
