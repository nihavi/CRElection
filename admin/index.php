<?php
	//Admin panel
	$auth_required = true;
	require_once("../config.php");

	$htmlOutput = "<form action='allow.php' method='post'><input type='hidden' name='allowed' value='true'>";

	$clients = get_clients();
	if ( count($clients) != 0 ) {
		foreach ( $clients as $id => $client ){
			$name = $client["name"];
			$ip = $client["ip"];
			//$htmlOutput .= "<li>".$name." ( ".$ip.") <button class='btn btn-remove' type='submit' name='client_ip' value='remove: ".$ip."'>Remove</button></li>";
			$htmlOutput .= "<button class='btn btn-green' type='submit' name='allowed' value='".$ip."'>Allow 1 Vote on ".$name." ( ".$ip.")</button><br>";
		}
	}
	else {
		$htmlOutput .= "None<br>";
	}
	$htmlOutput .= "</form>";

	$htmlOutput .= "<h1>OR</h1>";

	$htmlOutput .= "<a class='btn' href='candidates.php'>Go to Add candidate</a>";

	$htmlOutput .= "<h1>OR</h1>";

	$htmlOutput .= "<a class='btn' href='clients.php'>Go to Manage Clients</a>";

	$htmlOutput .= "<h1>OR</h1>";

	$htmlOutput .= "<a class='btn' href='results.php'>View Results</a>";

	include("../template.php");
