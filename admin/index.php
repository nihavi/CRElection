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
									popMsg('voted on ' + status[i].name);
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

		function popMsg(msg) {
			var orig = document.getElementById('message-example');
			var newMsg = orig.cloneNode(true);
			newMsg.id = '';
			newMsg.innerHTML = msg;
			document.getElementById('messages-container').appendChild(newMsg);
			newMsg.style.opacity = 0;
			newMsg.offsetHeight;
			newMsg.style.opacity = 1;
			setTimeout(function() {
				newMsg.style.opacity = 0;
				newMsg.addEventListener('transitionend', function() {
					document.getElementById('messages-container').removeChild(newMsg);
				})
			}, 2000);
		}

		function allow(ip) {
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.open('POST','allow.php?noRed=1',true);
			xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			var params = encodeURI('allowed=' + ip);
			xmlhttp.send(params);
		}

    function camelize(str) {
      return str.replace(/(?:^\w|[A-Z]|\b\w)/g, function(letter, index) {
        return index == 0 ? letter.toLowerCase() : letter.toUpperCase();
      }).replace(/\s+/g, '');
    }

    var admin_verified = false;
    
    function verify() {
      if (admin_verified) {
        admin_verified = false;
        document.getElementById('result_btn').setAttribute('disabled','');
        document.getElementById('result_btn').innerHTML = 'View Results';
        return true;
      }
      var str = Math.random().toString(36).substring(8) + ' ' + Math.random().toString(36).substring(5);
      var verify = camelize(str);
      var person = prompt('Please confirm that you are human, by typing the following string in camel case', str);
      if (person != null) {
        if (person === verify) {
          admin_verified=true;
          document.getElementById('result_btn').removeAttribute('disabled','');
          document.getElementById('result_btn').innerHTML = 'Click to see final result !!';
        }
      }
      return false;
};
	</script>";

	$htmlOutput .= "<form action='allow.php' method='post' onsubmit='return false'><input type='hidden' name='allowed' value='true'>";

	$clients = get_clients();
	if ( count($clients) != 0 ) {
		foreach ( $clients as $id => $client ){
			$name = $client["name"];
			$ip = $client["ip"];
			$htmlOutput .= "<button class='btn btn-green' type='submit' name='allowed' id='".$ip."' value='".$ip."' onclick='allow(\"".$ip."\")'>Allow 1 Vote on ".$name." (".$ip.")</button><br>";
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

	$htmlOutput .= "<a id='result_btn' class='btn' onclick='return verify()' href='results.php' target='_blank' disabled >View Results</a>";

	include("../template.php");
