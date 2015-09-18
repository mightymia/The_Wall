<?php
	session_start();
	require('new-connection.php');

//---------- Site Functionality ------------

if (isset($_POST['action']) && $_POST['action'] == 'register') 
{
	register_user($_POST);
}
elseif (isset($_POST['action']) && $_POST['action'] == 'login') 
{
	login_user($_POST);
}
if (isset($_POST['action']) && $_POST['action'] == 'message') 
{
	insert_message($_POST);
}
if (isset($_POST['action']) && $_POST['action'] == 'comment') 
{
	insert_comment($_POST);
}
else //also does log off of wall.php
{
	session_destroy();
	header('location:index.php');
	die();
}
//--------- login functionality -----------------------


function register_user($post)
{
//--------- VALIDATIONS -----------------	
	$_SESSION['errors'] = array();

	if (empty($post['first_name'])) 
	{
		$_SESSION['errors'][] = "First name can't be blank";
	}
	if (empty($post['last_name'])) 
	{
		$_SESSION['errors'][] = "Last name can't be blank";
	}
	if (empty($post['password'])) 
	{
		$_SESSION['errors'][] = "Password is required";
	}
	if (! filter_var($post['email'], FILTER_VALIDATE_EMAIL)) 
	{
		$_SESSION['errors'][] = "Must use valid email address";
	}
	if ($post['password'] != $post['confirm_password']) 
	{
		$_SESSION['errors'][] = "Passwords must match";
	}
//------------ END VALIDATIONS -------------------

	if (count($_SESSION['errors']) > 0) 
	{
		header('location: index.php');
		die();
	}
	else
	{
		global $connection;
		$esc_reg_email = mysqli_real_escape_string($connection, $post['email']);
		$esc_reg_password = mysqli_real_escape_string($connection, $post['password']);
		$esc_reg_first_name = mysqli_real_escape_string($connection, $post['first_name']);
		$esc_reg_last_name = mysqli_real_escape_string($connection, $post['last_name']);
		$query = "INSERT INTO users(first_name, last_name, email, password, inserted_at, updated_at) VALUES ('{$esc_reg_first_name}', '{$esc_reg_last_name}', '{$esc_reg_email}', '{$esc_reg_password}', NOW(), NOW())";
		run_mysql_query($query);

		$user_query = "SELECT * FROM users WHERE users.password = '{$esc_reg_password}' AND users.email = '{$esc_reg_email}'";
		$user = fetch($user_query);
		if (count($user) > 0) 
		{
		$_SESSION['user_id'] = $user[0]['id'];
		$_SESSION['first_name'] = $user[0]['first_name'];
		$_SESSION['last_name'] = $user[0]['last_name'];
		$_SESSION['logged_in'] = TRUE;

		header('location: wall.php');
		die();
		}
	}
}

function login_user($post)
{
//--------- VALIDATIONS -------------	
	if (! filter_var($post['email'], FILTER_VALIDATE_EMAIL)) 
	{
		$_SESSION['errors'][] = "Must use valid email address";
	}
	if (empty($post['password'])) 
	{
		$_SESSION['errors'][] = "Password is required";
	}
//-------- END of VALIDATIONS -----------------

	global $connection;
	$esc_reg_email = mysqli_real_escape_string($connection, $post['email']);
	$esc_reg_password = mysqli_real_escape_string($connection, $post['password']);
	$query = "SELECT * FROM users WHERE users.password = '{$esc_reg_password}' AND users.email = '{$esc_reg_email}'";
	$user = fetch($query);
	if (count($user) > 0) 
	{
		$_SESSION['user_id'] = $user[0]['id'];
		$_SESSION['first_name'] = $user[0]['first_name'];
		$_SESSION['last_name'] = $user[0]['last_name'];
		$_SESSION['logged_in'] = TRUE;
		header('location: wall.php');
		die();
	}
	else
	{
		$_SESSION['errors'][] = "Can't find user with those credentials";
		header('location: index.php');
		die();
	}
}

//--------------- The Wall Functionality --------------

function insert_message($post)
{
	$_SESSION['errors'] = array();
//--------- VALIDATIONS ------------
	if (empty($post['message_text']))
	{
		$_SESSION['errors'][] = "Message can not be blank";
	}
//-----------END OF VALIDATION --------------

	if (count($_SESSION['errors']) > 0) 
	{
		header('location: wall.php');
		die();
	}
	else
	{
		global $connection;
		$esc_message = mysqli_real_escape_string($connection, $post['message_text']);
		$query = "INSERT INTO messages(message, created_at, updated_at, user_id) VALUES ('{$esc_message}', NOW(), NOW(), {$_SESSION['user_id']})";
		run_mysql_query($query);
		header('location: wall.php');
		die();
	}
}

function insert_comment($post)
{
	$_SESSION['errors'] = array();
	$esc_comment_text = mysqli_real_escape_string($connection, $post['comment_text']);
//--------- VALIDATIONS ------------
	if (empty($post['comment_text']))
	{
		$_SESSION['errors'][] = "Comment can not be blank";
	}
//-----------END OF VALIDATION --------------

	if (count($_SESSION['errors']) > 0) 
	{
		header('location: wall.php');
		die();
	}
	else
	{
		global $connection;
		$esc_comment = mysqli_real_escape_string($connection, $post['comment_text']);
		$query = "INSERT INTO comments(comment, created_at, updated_at, user_id, message_id) VALUES ('{$esc_comment}', NOW(), NOW(), {$_SESSION['user_id']}, {$post['m_id']})";
		run_mysql_query($query);
		header('location: wall.php');
		die();
	}
}

?>