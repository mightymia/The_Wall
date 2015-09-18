<?php 
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>The Wall-Login</title>
	<meta charset='utf-8'>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<h1>Welcome to the Wall</h1>
	<h2>Registration</h2>
	<form action='process.php' method='post'>
		<input type='hidden' name='action' value='register'>
		First Name: <input type='text' name='first_name'><br>
		Last Name: <input type='text' name='last_name'><br>
		Email address: <input type='text' name='email'><br>
		Password: <input type='password' name='password'><br>
		Confirm password: <input type='password' name='confirm_password'><br>
		<input type='submit' value='Register'>
	</form>
	
	<h2>Login</h2>
	<form action='process.php' method='post'>
		<input type='hidden' name='action' value='login'>
		Email address: <input type='text' name='email'><br>
		Password: <input type='password' name='password'><br>
		<input type='submit' value='Login'>
	</form>
	<?php
		if (isset($_SESSION['errors'])) 
		{
			foreach ($_SESSION['errors'] as $error) 
			{
				echo "<p class='error'>{$error}</p>";
			}
			unset($_SESSION['errors']);
		}
	?>
</body>
</html>