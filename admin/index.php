<?php
	//Admin panel
	$auth_required = true;
	require_once("../config.php");

	$htmlOutput = "<form action='allow.php' method='post'><input type='hidden' name='allowed' value='true'>
			<input class='btn btn-green' type='submit' value='Allow 1 Vote'>";
	
    $htmlOutput .= "<h1>OR</h1>";
    
	$htmlOutput .= "<a class='btn' href='candidates.php'>Go to Add candidate</a>";
	
	include("../template.php");
