<?php include "config.php"; ?>

<html>
<head>
	<link type="text/css" rel="stylesheet" href="css/login.css" />
<body>

<div class="container">
<h1><?php echo $muse['APP_TITLE'] ?></h1>
<?php echo $_SESSION['errorMessage']; $_SESSION['errorMessage'] = ""; ?>

<div class="formContainer">
	<h2>Sign in</h2>
	<form action="view.php" method="post">
		<span class="signInLabels">Username</span><input type="text" name="username"><br/>
		<span class="signInLabels">Password</span><input type="password" name="password"><br/>
		<span class="signInLabels">&nbsp;</span>
		<input type="submit" name="signIn" value="Sign In"><br/>
	&nbsp;
</form>
</div>

<div class="formContainer">
	<h2>Sign up</h2>
	<form action="createUser.php" method="post">
		<span class="signUpLabels">Username</span><input type="text" name="username"><br/>
		<span class="signUpLabels">Password</span><input type="password" name="password"><br/>
		<span class="signUpLabels">Confirm password</span><input type="password" name="passwordCheck"><br/>
		<span class="signUpLabels">&nbsp;</span><input type="submit" name="create" value="Create account">
	</form>
</div>

</div>
</body>
</html>
