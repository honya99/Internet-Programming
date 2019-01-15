<?php
ini_set('display_errors', '1');

session_start();
	   
require_once '/home/mail.php';
require_once '/home/dbInterface.php';
processPageRequest();


function addMovieToCart($movieID){
	$id = movieExistsInDB($movieID);
	if($id === 0){
		$movie = file_get_contents('http://www.omdbapi.com/?apikey=c37e4353&i=' . $moveID . '&type=movie&r=json');
		$array = json_decode($movie, true);
		$id = addMovie($array['imdbId'], $array['title'], $array['year'], $array['rating'], $array['runtime'], $array['genre'], $array['actors'], $array['director'], $array['writer'], $array['plot'], $array['poster']);
	}
	addMovieToShoppingCart($_SESSION["userId"], $id);
	displayCart();
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

function createMovieList($forEmail=false){
	$movieIds = isset($_SESSION['order']) ? getMoviesInCart($_SESSION['userId'], $_SESSION['order']) : getMoviesInCart($_SESSION['userId']);
	$string = '<table border="2" style="padding: 10px;">
	<thead>
	<tr>
	<th>Movie Image</th>
	<th>Movie title</th>
	<th>Remove Item</th>
	</tr>
	</thead>
	<tbody>';
	for ($i=0; $i < count($movieIds); $i++) {
		$movie = getMovieData($movieIds[i]);
		$title = str_replace("'", " ", $array["Title"]);
		$image = $array["Poster"];
		$year = $array["Year"];

		$string .= '<tr>';
		$string .= '<td><img src="'.$image.'" height="100px" /></td>';
		$string .= '<td><h3>'.$title.' ('.$year.')</h3></td>';
		$string .= $forEmail ? '' : '<a href="javascript:void(0);" onclick=\'displayMovieInformation([movie_id]);\'>View More Info</a>';
		$string .= $forEmail ? '' : "<td><a href='#' onclick='confirmRemove(\"".$title."\", \"".$movie['ID']."\")'>X</a></td>";
		$string .= '</tr>';
	}
	$string .='</tbody></table>';
	return $string;
}

function displayCart($forEmail = false){
	$movieCount = countMoviesInCart($_SESSION["userId"]);
	echo '
    <!DOCTYPE html>
    <html>
    <head>
			' . ($forEmail ? '' :
				'<title>Home - myXpress Movies!</title>
				<script src="script.js"></script>')
    . '</head>
    <body>
    <h1>myMovies Xpress!</h1>

    ' . ($forEmail ? '' : '<div style="text-align: left" class="">
    	<p>Welcome '.$_SESSION["name"].' (<a href="#" onclick="confirmLogout()">logout</a>)</p> </div>
	') ;

	if($movieCount > 0){
		$apiKey = "c37e4353";

		echo '<p>' . $movieCount . ' Movies in your shopping cart</p>
			<select id=\'select_order\' onchange=\'changeMovieDisplay();\'>
				<option value="0">Movie Title</option>
				<option value="1">Runtime (shortest -­> longest)</option>
				<option value="2">Runtime (longest ­-> shortest)</option>
				<option value="3">Year (old -­> new)</option>
				<option value="4">Year (new -­> old)</option>
			</select>
			<div id=\'shopping_cart\'>'. createMovieList($forEmail) . '</div>'; 
		$redirect = "window.location.href='./search.php'";
		echo '
			<br>
			' . ($forEmail ? '' : '<button onclick="'.$redirect.'">Add Movie</button><br><br>
			<button onclick="confirmCheckout()">Checkout</button>
    ');
	} else {
		$redirect = "window.location.href='./search.php'";
		echo'<p>Add Some Movies to Your Cart</p>
		<br>
		<button onclick="'.$redirect.'">Add Movie</button><br><br>
		';
	}
	echo ($forEmail ? '' : '<div id=\'modalWindow\' class=\'modal\'><div id=\'modalWindowContent\' class=\'modal-content\'></div></div>') . '</body></html>';
}

function processPageRequest(){
	if(!isset($_SESSION['name'])){
		header('logon.php');
		exit();
	}
	if(isset($_GET["action"])){
		switch($_GET["action"]){
			case "add":
				$movieID = $_GET["movie_id"];
				addMovieToCart($movieID);
				break;
			case "checkout":
				checkout($_SESSION["name"], $_SESSION["emailAddress"]);
				break;
			case 'remove':
				$movieID = $_GET["movie_id"];
				removeMovieFromCart($movieID);
				break;
			case 'update':
				$order = $_GET['order'];
				updateMovieListing($order);
				break;
			default:
				break;
		}
	} else {
		displayCart();
	}

}
function processPageRequest(){
	if(!isset($_SESSION["name"])){
		header('Location: ' . "logon.php");
	}
	else if(empty($_GET)){
		displayCart();
	}
	else{
			if($_GET["action"] == "add"){
				addMovieToCart($_GET["movieID"]);
			}
			else if($_GET["action"] == "checkout"){
				checkout($_SESSION["name"], $_SESSION["forEmail"]);
				displayCart();
			}
			else if($_GET["action"] == "remove"){
				removeMovieFromCart($_GET["movieID"]);
			}
			else if($_GET["action"] == "update"){
				updateMovieListing($_GET["order"]);
			}
		}
}

function removeMovieFromCart($movieID){
	removeMovieFromShoppingCart($_SESSION['userId'], $movieID);
	displayCart();
}

function updateMovieListing($order){
	$_SESSION['order'] = $order;
	echo createMovieList(true);
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
