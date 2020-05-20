<?php
//Upload successful
session_start();


unset( $_SESSION[ 'register' ] );


$email = "";
$pword = "";


$emailErr = "";
$passwordErr = "";


$error3 = true;
$error4 = true;

//TEST INPUT
function test_input( $data ) {
  $data = trim( $data );
  $data = stripslashes( $data );
  $data = htmlspecialchars( $data );
  $error = false;
  return $data;
}

if ( $_SERVER[ "REQUEST_METHOD" ] == "POST" ) {

  if ( empty( $_POST[ "email" ] ) ) {
    $emailErr = "*Email";
    $error3 = true;
  } else {
    $email = test_input( $_POST[ "email" ] );
    if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
      $emailErr = "Invalid email format";
      $error3 = true;
    } else {
      $error3 = false;
    }
  }

  if ( empty( $_POST[ "password" ] ) ) {
    $passwordErr = "*Password";
    $error4 = true;
  } else {
    $pword = test_input( $_POST[ "password" ] );
    if ( !preg_match( "/^[a-zA-Z ]*$/", $pword ) ) {
      $passwordErr = "Only letters and white space allowed";
      $error4 = true;
    } else {
      $error4 = false;
    }
  }
}


if ( !$error3 && !$error4 ) {

  //Database access information
  $servername = "someservername";
  $username = "someusername";
  $password = "somepassword";
  $dbname = "somedatabasename";

  // Create connection
  $conn = new mysqli( $servername, $username, $password, $dbname );

  // Check connection
  if ( $conn->connect_errno ) {
    echo "Failed to connect to MySQL: " . $conn->connect_error;
    exit();
  }

  // Perform query
  if ( $result = $conn->query( "SELECT * FROM users WHERE email = '$email'" ) ) {
    //Check email is unique
    if ( $result->num_rows == 1 ) {
      //Extract table row values
      $row = mysqli_fetch_array( $result );
      //
      $rowPassword_hash = $row[ "password" ];
      //Compare email and token between URL and table $row are the same
      if ( password_verify( $pword, $rowPassword_hash ) ) {
        //Confirm email to the user
        if ( $row[ "isEmailConfirmed" ] == 1 ) {
          //set cookie
          $_SESSION[ 'email' ] = $row[ "email" ];
          $_SESSION[ 'name' ] = $row[ "firstname" ];

          header( "Location: http://nexuseventstestsite.com/home.php" );
        } else {
          $emailErr = "In order to log in. Please verify your email address by clicking on the link in the confirmation email sent to the email address you privided during registration.";
        }
      } else {

        $passwordErr = "Your password is incorrect.";
      }
    } else {

      $emailErr = "This email address is not registered. Please check that you have entered the correct email or sign up below.";
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
	<script>
		function passData(){
			$.post("form.php",{register: "register"},
				   function(data,status){
					window.location.href = "form.php";
			});
		}
	</script>
	<div id='pagewrapper'>
	  <form  class='form' method="POST" novalidate action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" >
		<img class="image" src="NexusEventsOriginal.jpg"> <span class="error"><?php echo $emailErr ?></span>
		<input  class='formfield input-style'  type="email"  name="email"  placeholder="Please enter your email"   value="<?php echo $email ?>" >
		<br />
		<br />
		<span class="error"><?php echo $passwordErr ?></span>
		<input  class='formfield input-style'  type="password"   name="password"  placeholder="Please enter a password"  value="<?php echo $pword ?>" >
		</br>
		<br />
		<button class='formfield button-style'  class='boxsizing' type="submit">
		Login
		</button>
		<div> <br />
		  <br />
		  <span id='register' onclick="passData()"> Want in? - Sign up </span></div>
	  </form>
	  <br />
	  <br />
	  <br />
	</div>
	</body>
</html>
