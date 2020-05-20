<?php

$message = "";

if ( !isset( $_GET[ "email" ] ) && !isset( $_GET[ "token" ] ) ) {

  echo "<span style='color:red'>Please complete registration by clicking on the link in the confirmation email we sent you. Otherwise, login or sign up </span><a href='form.php' >here!</a> ";

} else {

  $email = $_GET[ "email" ];
  $token = $_GET[ "token" ];


  //Database access information
  $servername = "someservername";
  $username = "someusername";
  $password = "somepassword";
  $dbname = "somedatabasename";

  // Create connection
  $conn = new mysqli( $servername, $username, $password, $dbname );

  // Check connection
  if ( $conn->connect_errno ) {
    $message = "Failed to connect to MySQL: " . $conn->connect_error;
    exit();
  }

  // Perform query
  if ( $result = $conn->query( "SELECT * FROM users WHERE email = '$email'" ) ) {
    //Check email is unique
    if ( $result->num_rows == 1 ) {
      //Extract table row values
      $row = mysqli_fetch_array( $result );
      //Compare email and token between URL and table $row are the same
      if ( $row[ "email" ] == $email && $row[ "token" ] == $token ) {
        //Confirm email to the user
        $message = "Your email has been verified. Please <a href='index.php'>login here!</a>";
        //Update isEmailConfirmed field
        $id = $row[ 'id' ];
        $sql = "UPDATE users SET isEmailConfirmed='1' WHERE id='$id'";
        $conn->query( $sql );
      } else {
        //May indicate admin/code error. Should never happen.
        $message = "Access denied";
      }
    } else {
      //May indicate admin/code error. Should never happen
      $message = "Access denied. Number of rows returned: " . $result->num_rows;
    }

    // Free result set
    $result->free_result();
  }

  $conn->close();

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
	<div id='pagewrapper'> <img class="image" src="NexusEventsOriginal.jpg"> <span class="confirmedMessage"><?php echo $message;?></span> </div>
	<br />
	<br />
	<br />
</body>
</html>
