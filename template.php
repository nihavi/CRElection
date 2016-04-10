<!DOCTYPE html>
<html>
	<head>
		<title>Election Portal</title>
		<link href='<?php echo $base_url; ?>css/normalize.css' rel='stylesheet'>
		<link href='<?php echo $base_url; ?>css/main.css' rel='stylesheet'>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="<?php echo $base_url; ?>jquery.js"></script>
	</head>
	<body>
		<div class='navbar'>
			<div class='nav left'><?php echo $electionName; ?></div>
			<div id="messages-container">
				<div class="message" id="message-example"></div>
			</div>
		</div>
		<div class='container'>
			<?php
				echo $htmlOutput;
			?>
		</div>
	</body>
</html>
