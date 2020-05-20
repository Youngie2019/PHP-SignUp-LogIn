<?php
//Upload successful
session_start();
//'register' variable is initiallised from the home page. Ensures user can't access this page unless specifically requested from the home page
if ( isset( $_POST[ 'register' ] ) ) {
  $_SESSION[ 'register' ] = $_POST[ 'register' ];
  echo $_SESSION[ 'register' ];
}
//If variable is not set direct user to home page
if ( !isset( $_SESSION[ "register" ] ) ) {
  header( "Location: http://nexuseventstestsite.com/index.php" );
} else {


  $firstName = "";
  $lastName = "";
  $email = "";
  $pword = "";

  $firstNameErr = "";
  $lastNameErr = "";
  $emailErr = "";
  $passwordErr = "";

  $error1 = true;
  $error2 = true;
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

    if ( empty( $_POST[ "fName" ] ) ) {
      $firstNameErr = "*First name";
      $error1 = true;
    } else {
      test_input( $firstName = $_POST[ "fName" ] );
      if ( !preg_match( "/^[a-zA-Z ]*$/", $firstName ) ) {
        $firstNameErr = "Only letters and white space allowed";
        $error1 = true;
      } else {
        $error1 = false;
      }
    }

    if ( empty( $_POST[ "lName" ] ) ) {
      $lastNameErr = "*Last name";
      $error2 = true;
    } else {
      $lastName = test_input( $_POST[ "lName" ] );
      if ( !preg_match( "/^[a-zA-Z ]*$/", $lastName ) ) {
        $lastNameErr = "Only letters and white space allowed";
        $error2 = true;
      } else {
        $error2 = false;
      }
    }

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

  if ( !$error1 && !$error2 && !$error3 && !$error4 ) {
    unset( $_SESSION[ 'register' ] );
    $_SESSION[ "fName" ] = $firstName;
    $_SESSION[ "lName" ] = $lastName;
    $_SESSION[ "email" ] = $email;
    $_SESSION[ "password" ] = $pword;
    header( "Location: http://nexuseventstestsite.com/formSubmitted.php" );
  }

}

?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="nexuseventsstyles.css">
	</head>
	<body>
		<div id='pagewrapper'>
		  <form  class='form vertical-center' method="POST" novalidate action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<img class="image" src="NexusEventsOriginal.jpg" > <span class="error"><?php echo $firstNameErr;?></span>
			<input  class='formfield input-style'  type="text"   name="fName"  placeholder="Please enter your first name"  value="<?php echo $firstName;?>">
			</br>
			<br />
			<span class="error"><?php echo $lastNameErr;?></span>
			<input  class='formfield input-style'  type="text"   name="lName"  placeholder="Please enter your last name"   value="<?php echo $lastName;?>">
			<br />
			<br />
			<span class="error"><?php echo $emailErr;?></span>
			<input  class='formfield input-style'  type="email"  name="email"  placeholder="Please enter your email"   value="<?php echo $email;?>">
			<br />
			<br />
			<span class="error"><?php echo $passwordErr;?></span>
			<input  class='formfield input-style'  type="password"   name="password"  placeholder="Please enter a password"  value="<?php echo $pword;?>">
			</br>
			<br />
			<button class='formfield button-style'  class='boxsizing' type="submit">
			Register
			</button>
		  </form>
		  <br />
		  <br />
		  <br />
		</div>
	</body>
</html>