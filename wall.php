<?php
	session_start();
	require_once('new-connection.php');
	date_default_timezone_set('America/Los_Angeles');

	$comment_query = 'SELECT first_name, last_name, comments.created_at, comment, message_id FROM comments
							LEFT JOIN users
							ON comments.user_id = users.id
							LEFT JOIN messages
							ON comments.message_id = messages.id
							ORDER BY comments.updated_at ASC';
?>

<!DOCTYPE html>
<html>
<head>
	<title>The Wall</title>
	<meta charset='utf-8'>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id='header'>
		<h4>CodingDojo Wall</h4>
		<h5>Welcome <?=$_SESSION['first_name']?></h5>
		<a href="process.php">Log Off</a>
		<hr>
	</div>
	<form action='process.php' method='post'>
		<input type='hidden' name='action' value='message'>
		Post a message
		<textarea rows='3' cols='90' name='message_text'></textarea>
		<input id='message_submit' type='submit' value='Post a message'>
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
	<div id='message list'>
	<?php
	$query = 'SELECT first_name, last_name, created_at, message, messages.id FROM messages
				LEFT JOIN users
				ON messages.user_id = users.id
				ORDER BY messages.updated_at DESC';

	$message_list = fetch($query);
	
	foreach ($message_list as $message) 
	{
		$date = date_create($message['created_at']);
		$dateformat = date_format($date, "F jS Y");
		echo "<p class='user'>{$message['first_name']} {$message['last_name']} - {$dateformat}</p>";
		echo "<p class='message_style'> {$message['message']}</p>"; 

		$comment_list = fetch($comment_query);

		foreach ($comment_list as $comment) 
		{
			$date = date_create($comment['created_at']);
			$dateformat = date_format($date, "F jS Y");
			if ($message['id'] == $comment['message_id']) 
			{
			echo "<p class='user_comment'>{$comment['first_name']} {$comment['last_name']} - {$dateformat}</p>";
			echo "<p class='comment_style'> {$comment['comment']}</p>"; 
			}
		} ?>
<!--  Comment Form  -->
		<form action='process.php' method='post'>
			<input type='hidden' name='action' value='comment'>
			<input type='hidden' name='m_id' value= '<?= $message['id']; ?>'>
			Post a comment
			<textarea rows='3' cols='90' name='comment_text'></textarea>
			<input id='comment_submit' type='submit' value='Post a comment'>
			<hr>
		</form>
	<?php  } ?>
	</div>

</body>
</html>

