<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Password Reset</h2>

		<div>
			To reset your password, complete this form: <?php echo URL::action('AccountController@reset', $token) ?>.<br/>
			This link will expire in <?php echo Config::get('auth.reminder.expire', 60) ?> minutes.
		</div>
	</body>
</html>
