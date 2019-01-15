<?php
 
session_start(); // Connect to the existing session 
processPageRequest(); 

function displaySearchResults($searchString){
	$apiKey = "c37e4353";
	if(isset($_POST["search"])){
		$s = $_POST["s"];
		$results = file_get_contents('http://www.omdbapi.com/?apikey='.$apiKey.'&s='.urlencode("'.$s.'").'&type=movie&r=json');
		$arr = json_decode($results, true)["Search"]; 
		$count = count($arr);
	if($count > 0){
		echo '
	    <!DOCTYPE html>
	    <html>
	    <head>
	    	<title>Searching - myXpress Movies!</title>
	    	<script src="script.js"></script>
	    </head>
	    <body>

	   <h1>myMovies Xpress!</h1>

	    <div style="text-align: left">
	    	<p>Welcome, '.$_SESSION["name"].' </p>
	    	<p><a href="#" onclick="confirmLogout()">Logout</a></p>
	    </div>';

	    echo '
			<table border="2" style="padding: 10px;">
			<thead>
			<tr>
			<th>Movie Image</th>
			<th>Movie title</th>
			<th>Add Item</th>
			</tr>
			</thead>
			<tbody>'; 
		$i = 0;
		while ($i < $count) { 

			$movieID = trim($arr[$i]["imdbID"]);  
			$img = $arr[$i]["Poster"]; 
			$year = $arr[$i]["Year"];
			$title = $arr[$i]["Title"];  
 
			$url = "https://www.imdb.com/title/$movieID/";
			echo '<tr>'; 
			echo '<td><img src="'.$img.'" height="100px" /></td>';
			echo '<td><h3><a href="'.$url.'" targer="_blank">'.$title.' : '.$year.'</a></h3></td>';
			echo "<td><a href='#' onclick='addMovie(\"".$movieID."\")'>+</a></td>";

			echo'</tr>';
			
			$i++;
		}
		
		$redirect = "window.location.href='./index.php'";
		echo '
			</tbody>
			</table>
			<br>
			<button onclick="'.$redirect.'">Home</button><br/><br/>
	</body>
    </html>';
	}
	

	}

}

function displaySearchForm(){
	$redirect = "location='./index.php'";
	echo '
    <!DOCTYPE html>
    <html>
    <head>
    	<title>Mainpage</title>
    	<script src="script.js"></script>
    </head>
    <body>
	</style>
    <h1>myMovies Xpress!</h1>

    <div style="text-align: left" class="">
    	<p>Welcome, '.$_SESSION["name"].' </p>
    	<p><a href="#" onclick="confirmLogout()">Logout</a></p>
    </div>

    <form action="./search.php" method="post">

	<p><label>Search: </label>
	<input type="text" name="s" required/></p> 

	<p><a href="#" onclick="'.$redirect.'">Home</a></p>
	<p><input type="reset" name="reset" value="Clear"></p>
	<p><input type="submit" name="search" value="Search"></p>

	</form>
';
}

function processPageRequest(){
	if(!isset($_POST["search"])){ 
		displaySearchForm();
	} else{
		if(isset($_POST["s"])) displaySearchResults($_POST["s"]); 
	}
}



?>