<?php
session_start();

//Upload successful 1

$welcomeMsg = "";

if ( isset( $_SESSION[ 'email' ] ) ) {
  $welcomeMsg = "Welcome " . $_SESSION[ 'name' ] . ", you're in!";

} else {
  $welcomeMsg = "Please <a href='index.php' >click here</a> to login or sign up to access this page.";
}

?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<link rel="stylesheet" type="text/css" href="nexuseventsstyles.css">
	</head>
	<body>
		<div class="homeheader"> 
			<img class="home-image" src="NexusEventsOriginal.jpg" width="20%">
			<ul class="nav-list-wrapper">
				<li class="nav-element" >MUSIC TAKES YOU...</li>
				<li class="nav-element nav-pipe" >|</li>
				<li class="nav-element bring-it" >BRING IT</li>
				<li class="nav-element nav-pipe" >|</li>
				<li class="nav-element" >YOU'RE ON THE LIST</li>
			</ul>
		</div>
		<div id='pagewrapper'>
			<p id="welcome" ><?php echo $welcomeMsg; ?></p>
		</div>
	</body>
</html>
