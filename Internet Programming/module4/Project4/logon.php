<?php

processPageRequest();

function authenticateUser($username, $password){
	global $record;
	$filename = "./data/credentials.db";
	$record = array();
	 $file = fopen( $filename, "r" );
         
    if( $file == false ) {
		echo ( "Error in opening file" );
        exit();
    }
         
    $filesize = filesize( $filename );  
	if($filesize !== 0){ 
		while(!feof($file))
		  {  
			$row = fgets($file);
			$data = explode(",", $row);
			foreach ($data as $value) {
				array_push($record, $value);
			}
		  }
	}
	print_r($record);

    fclose($file);  
	
    if(trim($record[0]) == trim($username) && trim($record[1]) == trim($password)){
    	session_start();
    	$_SESSION["name"] = $record[2];
    	$_SESSION["email"] = $record[3];
    	header("Location: index.php"); 
    } else {
    	$error = "Username or Password is incorrect.";
    	displayLoginForm($error);
    }
}

function displayLoginForm($message=""){

	echo'
	<!DOCTYPE html>
	<html>
	<head>
		<title>Login - myMovies Xpress!</title>
	</head>
	<body>
		
	<h1>myMovies Xpress!</h1>
	
	<p style="color: red; font-weight: bolder;">'.$message.'</p>
	<form action="./logon.php" method="post">

	<p><label>Username: </label>
	<input type="text" name="username" /></p>

	<p><label>Password: </label>
	<input type="password" name="password" /></p>

	<p><input type="reset" name="reset" value="Clear"></p>
	<p><input type="submit" name="login" value="Login"></p>

	</form>

    <footer style="padding-top: 20px;">
    <div><a href="../../index.html"><span class="hover">ePortfolio</span></a></div>

	</footer>

	</body>
	</html>';

}

function processPageRequest(){
	
	 session_start();
	 session_destroy();

	 if(isset($_POST["login"])){
	 	$username = $_POST["username"];
	 	$password = $_POST["password"];
	 	authenticateUser($username, $password);

	 } else { 
	 	displayLoginForm();
	 }
}
 

 

?>