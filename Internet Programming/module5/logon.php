<?php
ini_set('display_errors', '1');
require_once('/home/dbInterface.php');
require_once('/home/mail.php');
processPageRequest();

function authenticateUser($username, $password){
	$user = validateUser($username, $password);
	if($user !== null){
		session_start();
		$_SESSION["userId"] = $user["userId"];
		$_SESSION["name"] = $user["name"];
		$_SESSION["email"] = $user["email"];
		header("Location: ./index.php");
		exit();
	} else {
		displayLoginForm("No account exists with those credentials");
	}
}

function createAccount($username, $password, $name, $email){
	$result = addUser($username, $password, $name, $email);
	if($result === 0){
		displayLoginForm("Account already exists with that username");
	} else {
		sendValidationEmail($result, $name, $email);
	}
}

function resetPassword($userId, $password){
	$result = resetUserPassword($userId, $password);
	displayLoginForm($result ? "Password successfully reset" : "Password reset was unsucccessful");
}

function sendForgotPasswordEmail($username){
	$user = getUserData($username);
	$message = '
		<html>
			<head>
				<title>Password Reset</title>
			</head>
			<body>
				<h1>myMovies Xpress Password Reset</h1>
				<p>'. $user['name'] . '</p>
				<p>Please head over to the following URL to complete your password reset on your account</p>
				<p><a href="http://192.168.100.86/~eh504944/module5/logon.php?form=reset&user_id=' . $user['id'] . '">Reset Password</a></p>
			</body>
		</html>
	';
	$result = sendMail('576562794', $user['id'], $user['name'], 'Password Reset for myMovies Xpress', $message);
}

function sendValidationEmail($userId, $name, $emailAddress){
	$message = '
	<html>
		<head>
			<title>Validate Account</title>
		</head>
		<body>
			<h1>myMovies Xpress Account Validation</h1>
			<p>'. $name . '</p>
			<p>Please head over to the following URL to validate your new account</p>
			<p><a href="http://192.168.100.86/~eh504944/module5/logon.php?action=validate&user_id=' . $userId . '">Validate Email</a></p>
		</body>
	</html>
';
$result = sendMail('576562794', $emailAddress, $name, 'Validate Account', $message);
var_dump($result);
}

function validateAccount($userId){
	if(activateAccount($userId)){
		displayLoginForm("New account activated successfully");
	} else{
		displayLoginForm("Account activation failure");
	}

}

function displayCreateAccountForm(){
	echo'
	<!DOCTYPE html>
	<html>
		<head>
			<title>Create Account - myMovies Xpress!</title>
		</head>
		<body>
		<h1>myMovies Xpress Create Account Page!</h1>
		<form action="./logon.php" onsubmit="return validateCreateAccountForm();" method="post">
			<p><label for="name">Display Name: </label>
			<input type="text" id="name" name="name" required/></p>

			<p><label for="username">Username: </label>
			<input type="text" id="username" name="username" required/></p>

			<p><label for="emailAddress">Email Address: </label>
			<input type="text" id="emailAddress" name="emailAddress" required/></p>

			<p><label for="confirmEmailAddress">Confirm email Address: </label>
			<input type="text" id="confirmEmailAddress" name="confirmEmailAddress" required/></p>

			<p><label for="password">Password: </label>
			<input type="password" id="password" name="password" required/></p>

			<p><label for="confirmPassword">Confirm Password: </label>
			<input type="password" id="confirmPassword" name="confirmPassword" required/></p>

			<input type="hidden" name="action" value="create" />

			<p><button type="button" name="cancel" value="Cancel" onClick="confirmCancel(\'create\')"></button></p>
			<p><input type="reset" name="reset" value="Clear"></p>
			<p><input type="submit" name="createAccount" value="Create Account"></p>
		</form>
		<footer style="padding-top: 20px;"></footer>
		</body>
	</html>';
}

function displayForgotPasswordForm() {
	echo'
	<!DOCTYPE html>
	<html>
		<head>
			<title>Login - myMovies Xpress!</title>
		</head>
		<body
			<h1>myMovies Xpress Forgot Password Page!</h1>
			<form action="./logon.php" method="post">
				<p><label for="username">Username: </label>
				<input type="text" name="username" required/></p>

				<input type="hidden" name="action" value="forgot" />

				<p><button type="button" name="cancel" value="Cancel" onClick="confirmCancel(\'forgot\')"></button></p>
				<p><input type="reset" name="reset" value="Clear"></p>
				<p><input type="submit" name="forgotPassword" value="Submit"></p>
			</form>
			<footer style="padding-top: 20px;"></footer>
		</body>
	</html>';
}

function displayResetPasswordForm($userId){
	echo'
	<!DOCTYPE html>
	<html>
		<head>
			<title>Login - myMovies Xpress!</title>
		</head>
		<body
			<h1>myMovies Xpress Reset Password Page!</h1>
			<form action="./logon.php" method="post">
				<p><label for="password">Password: </label>
				<input type="password" id="password" name="password" required/></p>

				<p><label for="confirmPassword">Confirm Password: </label>
				<input type="password" id="confirmPassword" name="confirmPassword" required/></p>

				<input type="hidden" name="action" value="reset" />
				<input type="hidden" name="user_id" value="' . $userId . '" />

				<p><button type="button" name="cancel" value="Cancel" onClick="confirmCancel(\'reset\')"></button></p>
				<p><input type="reset" name="reset" value="Clear"></p>
				<p><input type="submit" name="resetPassword" value="Reset Password"></p>
			</form>
			<footer style="padding-top: 20px;"></footer>
		</body>
	</html>';
}

function displayLoginForm($message=""){

	echo'
	<!DOCTYPE html>
	<html>
		<head>
			<title>Login - myMovies Xpress!</title>
		</head>
		<body
			<h1>myMovies Xpress Login Page!</h1>
			<p style="color: red; font-weight: bolder;">'.$message.'</p>
			<form action="./logon.php" method="post">
				<p><label for="username">Username: </label>
				<input type="text" name="username" required/></p>

				<p><label for="password">Password: </label>
				<input type="password" name="password" required/></p>

				<input type="hidden" name="action" value="login" />

				<p><input type="reset" name="reset" value="Clear"></p>
				<p><input type="submit" name="login" value="Login"></p>

				<a href="./logon.php?form=create">Create Account</a>
				<a href="./logon.php?form=forgot">Forgot Password</a>
			</form>
			<footer style="padding-top: 20px;"></footer>
		</body>
	</html>';
}

function processPageRequest(){
	if(!empty($_POST)){
		if(isset($_POST['action'])){
			switch($_POST['action']){
				case 'create':
					createAccount($_POST['username'], $_POST['password'], $_POST['name'], $_POST['emailAddress']);
					break;
				case 'forgot':
					sendForgotPasswordEmail($_POST['username']);
					break;
				case 'login':
					authenticateUser($_POST['username'], $_POST['password']);
					break;
				case 'reset':
					resetPassword($_POST['userId'], $_POST['password']);
					break;
				default:
					break;
			}
		}
	} else if(!empty($_GET)){
		if(isset($_GET['action'])){
			validateAccount($_GET['user_id']);
		} else if(isset($_GET['form'])){
			switch($_GET['form']){
				case 'create':
					displayCreateAccountForm();
					break;
				case 'forgot':
					displayForgotPasswordForm();
					break;
				case 'reset':
					displayResetPasswordForm($_GET['userId']);
					break;
				default:
					break;
			}
		}
	} else {
		displayLoginForm();
	}
}




?>