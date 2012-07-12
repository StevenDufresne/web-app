<?php

require_once 'includes/db.php';
require_once 'includes/users.php';
require_once 'includes/requests.php';

if (!user_is_signed_in()) {
	header('Location: index.php');
	exit;
}



$email= filter_input(INPUT_POST, 'addEmail', FILTER_SANITIZE_STRING);

$user_id = ($_SESSION['user-id']);

$user_info = get_username ($db, $user_id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

$errors = array();

	if(isset($email)) {
		$friend_id =  friend_email_check($db, $email);
	}

	if(!$friend_id){
		$errors['no-user'] = true;
	}

	if($friend_id) {

		$friendAlready = check_friend_id ($db, $user_id, $friend_id);
		if(!$friendAlready && $friendAlready !== $user_id){

		add_friend_id ($db, $user_id, $friend_id);
		
		header('Location: friends.php');
		exit;
	
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Add Friends &middot; I Have Plans</title>
	<link href="css/general.css" rel="stylesheet">
</head>
<body>
	<div class="container">	
	<?php if (user_is_signed_in()) : ?>
		<p class="sign-out"><a href="sign-out.php">Sign Out</a></p>
		<?php endif; ?>
		<div class="header-container">
			<header class="mainHead">
				<figure>
					<div id="imgWrap"><img src="<?php echo ("images/".$user_info['photo']);?>" alt="User Photo"></div>
					<figcaption>Welcome, <?php echo $user_info['username'];?></figcaption>
				</figure>	
				<nav>
					<ul>
						<li id="friends"><a href="friends.php">Friends</a></li>
						<li id="plans"><a href="add-event.php">Plans</a></li>
					</ul>
				</nav>
			</header>
		</div>
		<div class="content-container">
			<div class="eventAdd">
				<header class="contentHead">
					<h1>Add Friends</h1>
					<p><a href="calendar.php">Back to Calendar</a></p>
				</header>
				<div class="contentBody">
					<div class="friendBody clearfix">
						<ul class="friendsUl">
							<?php 

							$friend_ids = get_friend_ids($db, $user_id);

							foreach($friend_ids as $friends) {

							$current_friend = intval($friends['friend_id']);
				
							$friend_info = get_username ($db, $current_friend);
							$friend_email = get_friend_email ($db, $current_friend);
							
							echo '<li class="friendHolder"><div><img src="'.'images/'.$friend_info['photo'].'" alt=""></div><span>'.$friend_info['username'].'  (  '.$friend_email['email'].' )</span></li><a class="delete" href="delete.php?id='.$current_friend.'">Delete</a> ';
							 }; ?>
						</ul>
						<form id="addFriend" method="post" action="friends.php">
							<label for="addFriend">Add one of your friends</label>
							<input id="addEmail" name="addEmail" placeholder="ie. steve@partypooper.com" value="">
							<button id="addBtn" type="submit">Add</button>
							<?php if (isset($errors['no-user'])) { echo ('<em>No such user/email exists</em>');} ?>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<body>
</html>