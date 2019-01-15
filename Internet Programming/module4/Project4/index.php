<?php

session_start();  
 
processPageRequest(); 

function addMovieToCart($movieID){
	global $show;
	$filename = "./data/cart.db";
	$show = array();
	  
    $file = fopen( $filename, "r" );
         
    if( $file == false ) {
		echo ( "Error in opening file" );
        exit();
    }
         
    $filesize = filesize( $filename ); 
	if($filesize !== 0){
		while(!feof($file))
		  {
			$line = fgets($file);
			$data = explode(",", $line);
	        foreach ($data as $text) {
	        	array_push($show, $text);
	        }
		  }
	} 

    fclose($file);
	
    $join = "";
	
    if(!empty($show)){
		
    	$fopen = fopen($filename, "wr");

    	foreach ($show as $text) { 
    		$join .= $text . ", ";
	    }
		
	    $join = $join . $movieID;
		
	    $join = substr($join, 0, strlen($join)); 
	    if($fopen){ 
	    	fwrite($fopen, $join);
	    }
	    fclose($fopen);
		
    } else {
		
    	$fopen = fopen($filename, "wr");
    	$join = $join . $movieID;
    	if($fopen){ 
	    	fwrite($fopen, $join);
	    }
		
	    fclose($fopen);
    } 
	
	header("Location: index.php");
    
    //displayCart();
    
}


function checkout($name, $address){  
	$subject = "Your Receipt from myMovies!";
	$message = mailMessage();
	$encode = urlencode($message);
	$message = "
	<h3>Your transaction was successful.</h3>
	<p>You have successful purchase the items below from our store</p>
	<p>$encode</p>
	<p>If you have any question or complaint, please reach out to us via the customer line. Thank you</p>";
	$header = "From:cse@test.com \r\n";
    $header .= "Cc:cse@test.com \r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-type: text/html\r\n";
	$result = mail($address, $subject, $message, $header);

	return $result;
}

function displayCart(){
	$filename = "./data/cart.db";
	$rowList;
	$rowList = array();
	
	$file = fopen( $filename, "r" );
         
    if( $file == false ) {
		echo ( "Error in opening file" );
        exit();
    }
         
    $filesize = filesize( $filename ); 
	if($filesize !== 0){
		while(!feof($file))
		  {
			 $line = fgets($file);
			$data = explode(",", $line);
			foreach ($data as $text) {
				array_push($rowList, $text);
			}
		  }
	} else {
		echo "null";
	}

    fclose($file); 
	
    echo '
    <!DOCTYPE html>
    <html>
    <head>
    	<title>Home - myXpress Movies!</title>
    	<script src="script.js"></script>
    </head>
    <body>
    <h1>myMovies Xpress!</h1>

    <div style="text-align: left" class="">
    	<p>Welcome, '.$_SESSION["name"].' </p>
    	<p><a href="#" onclick="confirmLogout()">Logout</a></p> 
    </div>
';

	if(count($rowList) > 0 && !empty($rowList[0]) && filesize("./data/cart.db") && !empty($rowList)){ 
		$apiKey = "c37e4353";
		
		echo '<table border="2" style="padding: 10px;">
			<thead>
			<tr>
			<th>Movie Image</th>
			<th>Movie title</th>
			<th>Remove Item</th>
			</tr>
			</thead>
			<tbody>'; 
		for ($i=0; $i < count($rowList); $i++) { 

			$movie_id = trim($rowList[$i]);
			$movie = file_get_contents("http://www.omdbapi.com/?i=$movie_id&apikey=$apiKey&type=movie&r=json");
			$array = json_decode($movie, true); 
			$title = str_replace("'", " ", $array["Title"]); 
			$image = $array["Poster"]; 
			$year = $array["Year"]; 

			$url = "https://www.imdb.com/title/$movie_id/";
			echo '<tr>'; 
			echo '<td><img src="'.$image.'" height="100px" /></td>';
			echo '<td><h3><a href="'.$url.'" targer="_blank">'.$title.' : '.$year.'</a></h3></td>';
			echo "<td><a href='#' onclick='confirmRemove(\"".$title."\", \"".$movie_id."\")'>X</a></td>";

			echo'</tr>';
		}

		$redirect = "window.location.href='./search.php'";
		echo '
			</tbody>
			</table>
			<br/>
			<button onclick="'.$redirect.'">Add Movie</button><br/><br/>
			<button onclick="confirmCheckout()">Checkout</button>

    </body>
    </html>
    ';  


	} else {
		$redirect = "window.location.href='./search.php'";
		echo'<p>Add Some Movies to Your Cart</p>
		<br/>
		<button onclick="'.$redirect.'">Add Movie</button><br/><br/>';
	}


} 

function processPageRequest(){

	if(isset($_GET["action"])){
		if($_GET["action"] == "add"){
			$movieID = $_GET["movie_id"];
			addMovieToCart($movieID);
		} else if ($_GET["action"] == "checkout") {
			checkout($_SESSION["displayName"], $_SESSION["emailAddress"]);
		} else if ($_GET["action"] == "remove") {
			$movieID = $_GET["movie_id"];
			removeMovieFromCart($movieID);
		}
	} else {
		displayCart();
	}

}

function removeMovieFromCart($movieID){
	global $show3;
	$filename = "./data/cart.db";
	$show3 = array();
	
	  $file = fopen( $filename, "r" );
         
    if( $file == false ) {
		echo ( "Error in opening file" );
        exit();
    }
         
    $filesize = filesize( $filename ); 
	if($filesize !== 0){
		while(!feof($file))
		  {
			$line = fgets($file);
	        $data = explode(",", $line);
	        foreach ($data as $text) {
	        	array_push($show3, $text);
	        }
		  }
	} 

    fclose($file); 
	 
 
    $join = "";
    if(!empty($show3)){
    	$fopen = fopen($filename, "wr");

    	foreach ($show3 as $text) { 
    		if(trim($text) != trim($movieID)){
    			$join .= $text . ", ";
    		}
	    } 
	    $join = trim(substr($join, 0, strlen($join) - 2));
 
	    if($fopen){ 
	    	fwrite($fopen, $join);
	    }
	    fclose($fopen);
    } else {
    	$fopen = fopen($filename, "wr");
    	$join .= $movieID;
    	if($fopen){ 
	    	fwrite($fopen, $join);
	    }
	    fclose($fopen);
    } 

    displayCart();
}


function mailMessage(){ 
	$filename = "./data/cart.db";
	$encode = "";
	$rowList;
	$rowList = array();
	
	 $file = fopen( $filename, "r" );
         
    if( $file == false ) {
		echo ( "Error in opening file" );
        exit();
    }
         
    $filesize = filesize( $filename ); 
	if($filesize !== 0){
		while(!feof($file))
		  {
			$line = fgets($file);
			$data = explode(",", $line);
			foreach ($data as $text) {
				array_push($rowList, $text);
			}
		  }
	} 

    fclose($file);  
	
    $encode = $encode . '
    <!DOCTYPE html>
    <html>
    <head>
    	<title>Mainpage</title>
    	<script src="script.js"></script>
    </head>
    <body>
    <style>
		body{
			position: relative;
			max-width:1100px;
			margin: 0 auto;
		}
	</style>
    <h1>myMovies Xpress!</h1> 
';
	 
	if(count($rowList) > 0 && filesize($filename) && !empty($rowList)){ 
		$apiKey = "c37e4353";
		$encode = $encode . '
			<table border="1">
			<thead>
			<tr>
			<th>Movie Image</th>
			<th>Movie title</th>
			<th>Remove Item</th>
			</tr>
			</thead>
			<tbody>'; 
		for ($i=0; $i < count($rowList); $i++) { 

			$movie_id = trim($rowList[$i]);
			$movie = file_get_contents("http://www.omdbapi.com/?i=$movie_id&apikey=$apiKey&type=movie&r=json");
			$array = json_decode($movie, true); 
			$title = $array["Title"]; 
			$image = $array["Poster"]; 
			$year = $array["Year"]; 

			$url = "https://www.imdb.com/title/$movie_id/";
			$encode = $encode .  '<tr>'; 
			$encode = $encode .  '<td><img src="'.$image.'" height="100px" /></td>';
			$encode = $encode . '<td><h3><a href="'.$url.'" targer="_blank">'.$title.' : '.$year.'</a></h3></td>';
			$encode = $encode .  "<td><a href='#' onclick='confirmRemove(\"".$title."\", \"".$movie_id."\")'>X</a></td>";

			$encode = $encode . '</tr>';
		}

		$redirect = "window.location.href='./search.php'";
		$encode = $encode .  '
			</tbody>
			</table> 

    </body>
    </html>
    ';  
    return $encode;

	}  
} 

?>