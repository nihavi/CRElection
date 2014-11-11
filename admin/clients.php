<?php
	$auth_required = true;
	require_once("../config.php");

	$htmlOutput = "";

	$htmlOutput .= "<h1>Clients</h1>";
	$htmlOutput .= "<form action='add_client.php' method='post' autocomplete='off'>";
	$clients = get_clients();
	if ( count($clients) != 0 ) {
		$htmlOutput .= "<ul>";
		foreach ( $clients as $id => $client ){
			$name = $client["name"];
			$ip = $client["ip"];
			$htmlOutput .= "<li>".$name." ( ".$ip.") <button class='btn btn-remove' type='submit' name='client_ip' value='remove: ".$ip."'>Remove</button></li>";
		}
		$htmlOutput .= "</ul>";
	}
	else {
		$htmlOutput .= "None<br>";
	}
	$htmlOutput .= "</form>";

	$htmlOutput .= "<br><hr><br>";
	$htmlOutput .= "<h3>Add a client</h3>";
	$htmlOutput .= "<form action='add_client.php' method='post' autocomplete='off'>
			Name <input type='text' name='client_name'><br>
			IP <input type='text' name='client_ip' required><br>
			<input class='btn' type='submit' value='Add Client'>";

	$htmlOutput .= "<script>document.body.onload=function(){document.getElementsByName('client_name')[0].focus()}</script>";

	$htmlOutput .= "<br><br><br><br><a class='btn btn-green' href='".$base_url."admin/'>Go to Allow Voting</a>";

	include("../template.php");
