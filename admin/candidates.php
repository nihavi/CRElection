<?php
	require_once('../config.php');
	
	$query = mysqli_prepare($DB, "SELECT id, name FROM `candidates`");
	mysqli_stmt_execute($query);
	mysqli_stmt_bind_result($query, $id, $name);
	mysqli_stmt_store_result($query);
	while(mysqli_stmt_fetch($query)){
		echo $id.'-'.$name;
	}
