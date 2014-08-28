<?php
	include('../config.php');
	
	$htmlOutput = '<table class="result"><tr><th>Candidate Name</th><th>Votes</th></tr>';
	
	$query = mysqli_prepare($DB, "SELECT name, votes FROM `candidates`");
	mysqli_stmt_execute($query);
	mysqli_stmt_bind_result($query, $name, $votes);
	mysqli_stmt_store_result($query);
	while(mysqli_stmt_fetch($query)){
		$htmlOutput .= '<tr><td>'.$name.'</td><td>'.$votes.'</td></tr>';
	}
	
	include("../template.php");
