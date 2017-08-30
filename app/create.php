<?php

include './config.php';

if( userAccountCreate( $_POST['username'], $_POST['password'] ) ) {
	$_SESSION["errorMessage"] = "User created! Please login.";
	header("Location: " . $muse['APP_URI'] . "index.php");
} else {
	$_SESSION["errorMessage"] = "Creation error. Please try again.";
	header("Location: " . $muse['APP_URI'] . "index.php");
}

?>

<html>
<body>

User is created. 

<h1>Login</h1>
<form action="view.php" method="post">
<input type="text" name="name" value="<?php echo $_POST[name] ?>">
<input type="submit">
</form>

</body>

</html>