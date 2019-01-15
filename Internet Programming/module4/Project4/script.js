

function addMovie(movieID) {
	window.location.replace("./index.php?action=add&movie_id="+movieID);
	console.log("true");
	return true;
}

function confirmCheckout(){
	var option = confirm("Do you wish to checkout from myMovies Xpress! ?");
	if(option == true){
		window.location.replace("./index.php?action=checkout");
		console.log("true");
		return true;
	} else {
		console.log("false");
		return false;
	}
}

function confirmRemove(title, movieID) {
	var option = confirm("Do you wish to remove the selected movie "+title+"?");
	if(option == true){
		window.location.replace("./index.php?action=remove&movie_id="+movieID);
		console.log("true");
		return true;
	} else {
		console.log("false");
		return false;
	}
}

function confirmLogout(){
	 
	var option = confirm("Do you wish to logout of myMovies?");
	if(option == true){
		window.location.replace("./logon.php?action=logoff");
		console.log("true");
		return true;
	} else {
		console.log("false");
		return false;
	}
}

function confirmRemove(title, movieID) {
	var option = confirm("Do you wish to remove the selected movie?");
	if(option == true){
		window.location.replace("./index.php?action=remove&movie_id="+movieID);
		console.log("true");
		return true;
	} else {
		console.log("false");
		return false;
	}
}

	