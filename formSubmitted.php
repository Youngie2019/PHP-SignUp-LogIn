<?php

//Upload successful 1

session_start();


//Send user to login.php if an attempt has been made to access this page directly
if ( !isset( $_SESSION[ 'fName' ] ) && !isset( $_SESSION[ 'lName' ] ) && !isset( $_SESSION[ "email" ] ) && !isset( $_SESSION[ "password" ] ) ) {

  header( "Location: http://nexuseventstestsite.com/index.php" );

} else {

  //Get user registration variables
  $firstName = $_SESSION[ 'fName' ];
  $lastName = $_SESSION[ 'lName' ];
  $email = $_SESSION[ "email" ];
  $passwordDB = $_SESSION[ "password" ];

  $message = "";

  //Hash password
  $options = array(
    'cost' => 11
  );
  $password_hash = password_hash( $passwordDB, PASSWORD_BCRYPT, $options );


  //Create email verification token
  $token = 'sometoken';
  $token = str_shuffle( $token );
  $token = substr( $token, 0, 10 );

  //Email verification function
  function emailVerification() {

    //Link to PHPMailer classes
    include_once "/home/sites/7a/2/2d0102eb62/public_html/phpmailer/PHPMailer.php";
    include_once "/home/sites/7a/2/2d0102eb62/public_html/phpmailer/Exception.php";

    //Create confirmaion email
    $mail = new PHPMailer\ PHPMailer\ PHPMailer();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "tls";
    $mail->Host = "somehost";
    $mail->Port = 465;
    $mail->Username = "someusername";
    $mail->Password = "somepassword";
    $mail->setFrom( 'test@nexuseventstestsite.com' );
    $mail->addAddress( $_SESSION[ "email" ] );
    $mail->Subject = "Please verify email";
    $mail->isHTML( true );
    $mail->Body =
      "
      Please click on the confirmation link below to confirm your email address:<br /><br />
      <a href='http://nexuseventstestsite.com/emailconfirmed.php?email=" . $GLOBALS[ 'email' ] . "&token=" . $GLOBALS[ 'token' ] . "'>Click here to confirm your email address</a>
      ";

    //Send confirmation email to user
    if ( $mail->send() ) {
      //If sent, promt user to go to their email client and click on the confirmation link
      return "Please verify your email address to complete registration by clicking on the link in the confirmation email we just sent you. You may need to check your junk folder.";

    } else {
      return "Op's. Something went wrong."; //To-do: Add resend message link

    }
  }

  //Create database access variables
  $servername = "someservername";
  $username = "someusername";
  $password = "somepassword";
  $dbname = "somedatabasename";

  //Create connection to myDatabase
  $conn = new mysqli( $servername, $username, $password, $dbname );
  // Check connection
  if ( $conn->connect_error ) {
    die( 'Connection failed:' . $conn->connect_error );
  }

  if ( $result = $conn->query( "SELECT * FROM users WHERE email = '$email'" ) ) {
    //Check email is unique
    if ( $result->num_rows == 0 ) {
      //Insert user input into database
      $sql = "INSERT INTO users (firstname, lastname, email, password, isEmailConfirmed, token) VALUES ('$firstName', '$lastName', '$email', '$password_hash', '0', '$token')";
      if ( $conn->query( $sql ) === TRUE ) {
        //Send verification email
        $message = emailVerification();
      } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
      }
    } else {
      $message = "Your email address is already registered. Please <a style='color:white' href='index.php' >click here</a> to log in.";
    }
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
		<div id='pagewrapper'>
			<p class="formSubmitted-Errmessage"><img class="image" src="NexusEventsOriginal.jpg"></br>
			</br>
			<?php echo $message; ?></p>
		</div>
	</body>
</html>
<?php
	session_unset();
	session_destroy();
?>
