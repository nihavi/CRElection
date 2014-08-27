<!DOCTYPE html>
<html>
	<head>
		<title>Election Portal</title>
		<link href='<?php echo $base_url; ?>css/normalize.css' rel='stylesheet'>
		<link href='<?php echo $base_url; ?>css/main.css' rel='stylesheet'>
	</head>
	<body>
		<div class='navbar'>
			<div class='nav left'><?php echo $electionName; ?></div>
		</div>
		<div class='main'>
			<?php
				echo $htmlOutput;
			?>
		</div>
	</body>
</html>
