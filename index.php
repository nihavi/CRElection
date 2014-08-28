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
	function allowed() {
		global $DB;
		$query = mysqli_prepare($DB, "SELECT meta_value FROM `meta` WHERE meta_name='vote_allowed'");
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $allowed);
		mysqli_stmt_store_result($query);
		mysqli_stmt_fetch($query);
		return $allowed == "1" ? true : false ;
	}
	
	$htmlOutput = '';
	
	if ( allowed() ) {
		$htmlOutput .= "<form action='vote.php' method='post'>";
		$candidates = get_candidates();
		if ( count($candidates) ) { 
			foreach ( $candidates as $id => $name ){
				$htmlOutput .= ("<label><input type='radio' name='candidate_id' value='".$id."' required >".$name."</label><br>" );
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
			unset($_SESSION["done_voting"]);
		}
		else {
			$htmlOutput .= "<strong>Access denied.</strong> Please ask the administrator to allow you to vote. <br><a class='btn' href=''>Reload</a>";
			$htmlOutput .= "<script>document.body.onload=function(){setTimeout(function(){window.location=''}, 1000)}</script>";
		}
	}
	
	include('template.php');
