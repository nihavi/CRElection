<?php
	//CR Election portal
	require_once("config.php");
	
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
	
	if ( true || allowed() ) {
		$htmlOutput .= "<form action='sumbit.php' method='post'>";
		$candidates = get_candidates();
		foreach ( $candidates as $id => $name ){
			$htmlOutput .= ("<input type='radio' name='candidate_id' value='".$id."' required >".$name."<br>" );
		}
		$htmlOutput .= "<input type='submit' value='Vote'></form>";
	}
	else {

	}
	
	include('template.php');
