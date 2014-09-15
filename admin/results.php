<?php
	$auth_required = true;
	include('../config.php');
	
	$totalVotes = 0;
	$htmlOutput = '<table class="result"><tr><th>Candidate Name</th><th>Votes</th></tr>';
	
	$query = mysqli_prepare($DB, "SELECT name, votes FROM `candidates`");
	mysqli_stmt_execute($query);
	mysqli_stmt_bind_result($query, $name, $votes);
	mysqli_stmt_store_result($query);
	while(mysqli_stmt_fetch($query)){
		$htmlOutput .= '<tr><td>'.$name.'</td><td>'.$votes.'</td></tr>';
		$totalVotes += $votes;
	}
	
	$htmlOutput .= '<tr class="total"><td>Total</td><td>'.$totalVotes.'</td></tr>';
	$htmlOutput .= '</table>';

	$htmlOutput .= "<br><br><br><br><a class='btn btn-green' href='".$base_url."admin/'>Go to Allow Voting</a>";
	
	include("../template.php");
