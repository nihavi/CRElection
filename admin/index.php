<?php
	//Admin panel
	$auth_required = true;
	require_once("../config.php");

	$htmlOutput = "<script>
		var client_status = [];
		function req() {
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onload = function() {
				if (xmlhttp.status == 200){
					var status = JSON.parse(xmlhttp.responseText);
					for (var i in status) {
						if (status[i].ip in client_status) {
							if (client_status[status[i].ip] != status[i].allowed) {
								if (status[i].allowed == false) {
									console.log('voted on ' + status[i].name);
								}
							}
						}
						if (status[i].allowed == true) {
							document.getElementById(status[i].ip).disabled = true;
						}
						else {
							document.getElementById(status[i].ip).disabled = false;
						}
						client_status[status[i].ip] = status[i].allowed;
					}
				}
				setTimeout(req, 500);
			}
			xmlhttp.onerror = function() {
				setTimeout(req, 10);
			}
			xmlhttp.open('GET','status_query.php',true);
			xmlhttp.send();
		}
		window.addEventListener('load', req);
	</script>";

	$htmlOutput .= "<form action='allow.php' method='post'><input type='hidden' name='allowed' value='true'>";

	$clients = get_clients();
	if ( count($clients) != 0 ) {
		foreach ( $clients as $id => $client ){
			$name = $client["name"];
			$ip = $client["ip"];
			//$htmlOutput .= "<li>".$name." ( ".$ip.") <button class='btn btn-remove' type='submit' name='client_ip' value='remove: ".$ip."'>Remove</button></li>";
			$htmlOutput .= "<button class='btn btn-green' type='submit' name='allowed' id='".$ip."' value='".$ip."'>Allow 1 Vote on ".$name." (".$ip.")</button><br>";
		}
	}
	else {
		$htmlOutput .= "There are no client added<br>";
	}
	$htmlOutput .= "</form>";

	$htmlOutput .= "<h1>OR</h1>";

	$htmlOutput .= "<a class='btn' href='candidates.php'>Go to Add candidate</a>";

	$htmlOutput .= "<h1>OR</h1>";

	$htmlOutput .= "<a class='btn' href='clients.php'>Go to Manage Clients</a>";

	$htmlOutput .= "<h1>OR</h1>";

	$htmlOutput .= "<a class='btn' href='results.php'>View Results</a>";

	include("../template.php");
