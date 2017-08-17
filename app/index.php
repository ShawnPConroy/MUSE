<?php include "config.php"; ?>

<html>

<body>

<?php echo $_SESSION['errorMessage']; $_SESSION['errorMessage'] = ""; ?>
<h1>Login</h1>
<form action="view.php" method="post">
	<input type="text" name="username">
	<input type="password" name="password">
	<input type="submit" name="login" value="Log In">
</form>

<h1>Create an account</h1>
<form action="createUser.php" method="post">
	<input type="text" name="username">
	<input type="password" name="password">
	<input type="password" name="passwordCheck">
	<input type="submit" name="create" value="Create account">
</form>

</body>
</html>
