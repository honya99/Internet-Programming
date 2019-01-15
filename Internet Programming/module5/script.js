
function showModalWindow() {
	var modal = document.getElementById('modalWindow');
	var span = document.getElementsByClassName("close")[0];
	span.onclick = function()
	{
		modal.style.display = "none";
	}
	window.onclick = function(event)
	{
		if (event.target == modal)
		{
			modal.style.display = "none";
		}
	}
	modal.style.display = "block";
}

function addMovie(movieID) {
	window.location.replace("./index.php?action=add&movie_id="+movieID);
	return true;
}

function confirmCancel(form) {
	var input = comfirm("Do you wish to cancel this " + form + " form?");
	if (input) {
		switch (form) {
			case 'create':
			case 'forgot':
			case 'reset':
				window.location.replace("./logon.php");
				break;
			case 'search':
				window.location.replace("./index.php");
				break;
			default:
				break;
		}
	}
	return input;
}

function changeMovieDisplay() {
	var option = document.getElementById('select_order').value;
	var obj = new XMLHttpRequest();
	obj.onreadystatechange = function (env) {
		document.getElementById("shopping_cart").innerHTML = this.responseText
	};
	obj.open("GET", "./index.php?action=update&order=" + option, true);
	obj.send();
}

function confirmCheckout(){
	var input = confirm("Do you wish to checkout from myMovies Xpress! ?");
	if(input){
		window.location.replace("./index.php?action=checkout");
	}
	return input;
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

	var input = confirm("Do you wish to logout of myMovies?");
	if(input){
		window.location.replace("./logon.php?action=logoff");
	}
	return input;
}

function confirmRemove(title, movieID) {
	var input = confirm("Do you wish to remove " + title);
	if(input){
		window.location.replace("./index.php?action=remove&movie_id="+movieID);
		return true;
	}
	return input;
}

function displayMovieInformation(movie_id) {
	var obj = new XMLHttpRequest();
	obj.onreadystatechange = function (env) {
		document.getElementById("modalWindowContent").innerHTML= this.responseText;
		showModalWindow();
	};
	obj.open("GET", "./movieinfo.php?movie_id=" + movie_id, true);
	obj.send();
}

function forgotPassworrd() {
	window.location.replace("./logon.php?action=forgot");
	return true;
}

function validateCreateAccountForm() {
	var name = document.getElementById("name").value
	var username = document.getElementById("username").value;
	var email = document.getElementById("emailAddress").value;
	var emailConfirm = document.getElementById("confirmEmailAddress").value;
	var password = document.getElementById("password").value;
	var passwordConfirm = document.getElementById("confirmPassword").value;
	if (name.length == 0) {
		alert("Display name cannot be empty");
		return false;
	}
	if (username.length == 0) {
		alert("Username cannot be empty");
		return false;
	}
	if (email.length == 0) {
		alert("Email cannot be empty");
		return false;
	}
	if (emailConfirm.length == 0) {
		alert("Email confirm cannot be empty");
		return false;
	}
	if (password.length == 0) {
		alert("Password cannot be empty");
		return false;
	}
	if (passwordConfirm.length == 0) {
		alert("Password confirm cannot be empty");
		return false;
	}

	if (username.includes(" ")) {
		alert("Username cannot have any spaces");
		return false;
	}
	if (email.includes(" ")) {
		alert("Email cannot have any spaces");
		return false;
	}
	if (emailConfirm.includes(" ")) {
		alert("Email cannot have any spaces");
		return false;
	}
	if (password.includes(" ")) {
		alert("Password cannot have any spaces");
		return false;
	}
	if (passwordConfirm.includes(" ")) {
		alert("Password confirm cannot have any spaces");
		return false;
	}

	if (email !== emailConfirm) {
		alert("Email confirmation failure");
		return false;
	}

	if (password !== passwordConfirm) {
		alert("Password confirmation failure");
		return false;
	}
	return true;
}

function validateResetPasswordForm() {
	var password = document.getElementById("password").value;
	var passwordConfirm = document.getElementById("confirmPassword").value;
	if (password.length == 0) {
		alert("Password cannot be empty");
		return false;
	}
	if (passwordConfirm.length == 0) {
		alert("Password confirm cannot be empty");
		return false;
	}
	if (password.includes(" ")) {
		alert("Password cannot have any spaces");
		return false;
	}
	if (passwordConfirm.includes(" ")) {
		alert("Password confirm cannot have any spaces");
		return false;
	}
	if (password !== passwordConfirm) {
		alert("Password confirmation failure");
		return false;
	}
	return true;
}

