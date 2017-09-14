<?php

include './config.php';

if( userAccountSignUp( $_POST['username'], $_POST['password'] ) ) {
	$_SESSION["errorMessage"] = "User created! Please sign in.";
	header("Location: " . $muse['APP_URI'] . "signin.php");
} else {
	$_SESSION["errorMessage"] = "Creation error. Please try again.";
	header("Location: " . $muse['APP_URI'] . "signin.php");
}

?>

<html>
<body>

Something went wrong. <a href="signin.php">Please try again</a>.

</body>

</html>