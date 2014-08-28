<?php
	//Admin panel
	$auth_required = true;
	require_once("../config.php");

	$htmlOutput = "<form action='allow.php' method='post'><input type='hidden' name='allowed' value='true'>
			<input type='submit' value='Allow 1 Vote'>";
	
	$htmlOutput .= "<br><br><a href='candidates.php'>Go to Add candidate</a>";
	
	include("../template.php");
