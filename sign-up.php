<?php

require_once 'includes/db.php';
require_once 'includes/users.php';
require_once 'includes/requests.php';
require_once 'includes/functions.php';

$errors = array();

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	$file_tmp = $_FILES['photo']['tmp_name'];


	//check to make sure the name doesn't already exist
	$name_check = friend_check($db, $username);



	if($name_check) {
		$errors['user-exists'] = true;
	}

	if (empty($username)) {
	$errors['username'] = true;
	}

	if (empty($password)) {
	$errors['password'] = true;
	}

	if (empty($email)) {
	$errors['email'] = true;
	}

	if (isset($_FILES['photo']['name'])) {
		
		$photo_name = stripslashes($_FILES['photo']['name']);

	}else{

		$photo = 0;
		
	}

	if (empty($errors)) {
		
		// create the user
		$id = user_create($db, $username, $password, $email, $photo_name);


		//resize and move the screenshot to the images folder
		image_resize_move($file_tmp, $photo_name);
		

		header('Location: index.php');
		exit;

	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>I Have Plans &middot; Login Page</title>
	<link href="css/general.css" rel="stylesheet">
</head>
<body>
	<div class="container">
		<div class="login-box">
			<form  enctype="multipart/form-data" id="login" method="post" action="sign-up.php">
				<input type="hidden" name="MAX_FILE_SIZE" value ="3247685">
				<h2>Sign Up</h2>
				<div class="panel">
					<div class="panel-info">
						<label for="username">Username:</label>
						<input id="username" name="username">	
						<strong class="user-available" data-status="unchecked">Available</strong>
					</div>
				</div>
				<div class="panel">
					<div class="panel-info">
						<label for="password">Password:</label>
						<input id="password" name="password" type="password">
					</div>
				</div>
				<div class="panel">
					<div class="panel-info">
						<label for="email">Email:</label>
						<input id="email" type="email" name="email" >
					</div>
				</div>
				<div class="panel">
					<div class="panel-info">
						<label for="file">Upload Photo:</label>
						<input id="photo" type="file" name="photo" >
					</div>
				</div>
				<div class="go-button">
					<button type="submit">Go</button>
					<?php if(isset($errors['user-exists'])) { echo ('That user already exists.');} ?>
				</div>
				<a href="index.php">Already a member</a>
			</form>
		</div>
	<div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="js/user-validation.js"></script>
</body>
</html>
