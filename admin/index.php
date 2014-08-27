<?php
	//Admin panel
	require_once("../config.php");

	$htmlOutput = "<form action='allow.php' method='post'><input type='hidden' name='allowed' value='true'>
			<input type='submit' value='Allow 1 Vote'>";
