var states = "State: <select name=state id=state required> <option selected=selected value=SelectState>Select State</option> <option value=AL>Alabama</option> <option value=AK>Alaska</option> <option value=AZ>Arizona</option> <option value=AR>Arkansas</option> <option value=CA>California</option> <option value=CO>Colorado</option> <option value=CT>Connecticut</option> <option value=DE>Delaware</option> <option value=DC>District Of Columbia</option> <option value=FL>Florida</option> <option value=GA>Georgia</option> <option value=HI>Hawaii</option> <option value=ID>Idaho</option> <option value=IL>Illinois</option> <option value=IN>Indiana</option> <option value=IA>Iowa</option> <option value=KS>Kansas</option> <option value=KY>Kentucky</option> <option value=LA>Louisiana</option> <option value=ME>Maine</option> <option value=MD>Maryland</option> <option value=MA>Massachusetts</option> <option value=MI>Michigan</option> <option value=MN>Minnesota</option> <option value=MS>Mississippi</option> <option value=MO>Missouri</option> <option value=MT>Montana</option> <option value=NE>Nebraska</option> <option value=NV>Nevada</option> <option value=NH>New Hampshire</option> <option value=NJ>New Jersey</option> <option value=NM>New Mexico</option> <option value=NY>New York</option> <option value=NC>North Carolina</option> <option value=ND>North Dakota</option> <option value=OH>Ohio</option> <option value=OK>Oklahoma</option> <option value=OR>Oregon</option> <option value=PA>Pennsylvania</option> <option value=RI>Rhode Island</option> <option value=SC>South Carolina</option> <option value=SD>South Dakota</option> <option value=TN>Tennessee</option> <option value=TX>Texas</option> <option value=UT>Utah</option> <option value=VT>Vermont</option> <option value=VA>Virginia</option> <option value=WA>Washington</option> <option value=WV>West Virginia</option> <option value=WI>Wisconsin</option> <option value=WY>Wyoming</option> </select><br><br>";

var cc = "First Name:<input type=text name=firstname id=fname required><br><br>\
Last Name:<input type=text name=lastname id=lname required><br><br>\
Address:<input type=text name=address id=address required><br><br>\
City:<input type=text name=city id=city required><br><br>\
" + states +"\
Zip Code:<input type=text name=zip id=zip required><br><br>\
Email:<input type=text name=email id=email required><br><br>\
Name on Card:<input type=text name=namecard id=namecard required><br><br>\
Card Number:<input type=text name=cardnumber id=cardnumber required><br><br>\
CVV2/CVC:<input type=text name=cvv id=cvv a href='https://en.wikipedia.org/wiki/Card_security_code' target=_blank><br><br>\
Expiration Date:<input type=month value=2017-12 min=2017-01 max=2020-12 name=exDate id=exDate required><br></form><br><br>";

var pp = "PayPal Email:<input type=email name=paypalemail id=paypalemail ><br><br>\
Password:<input type=password name=password id=password><br><br>";

function pageLoad(){
    document.getElementById("input").innerHTML = cc;
    document.getElementById("cc").checked = true;
    document.getElementById("pp").checked = false;
}

function validateForm(){
    if(document.getElementById("cc").checked == true){
        var zip = document.getElementById("zip");
        var cvv = document.getElementById("cvv");
        var ccValidate = validateCreditCard(document.getElementById("cardnumber").value);
        var sValidate = validateState(document.getElementById("state").value);
        var zValidate = validateControl(zip.value, zip.name, 5);
        var cValidate = validateControl(cvv.value, cvv.name, 3);
        var emailValidate = validateEmail(document.getElementById("email").value);
        var exValidate = validateDate(document.getElementById("exDate").value);
    if(sValidate == true && cValidate == true && zValidate == true && ccValidate == true && emailValidate == true && exValidate == true){
        alert("Payment was successful");
    }
    } else if(document.getElementById("pp").checked == true){
        var ppValidate = validateEmail(document.getElementById("paypalemail").value);
        var pwValidate = validatePassword(document.getElementById("password").value);
        if(ppValidate == true && pwValidate == true){
            alert("Payment was successful");
        }
    }
}

function updateForm(control){
    if (control.id == "cc"){
        document.getElementById("input").innerHTML = cc;
    }
    else if (control.id == "pp"){
        document.getElementById("input").innerHTML = pp;
    }
}
    
    function testLength(value, length, exactLength){

        if(exactLength == true){
            if (value.length == length){
                exactLength = true;
                return exactLength;
            }
            else {
                return false;
            }
        }else if(exactLength == false){
        if (value.length >= length){
            exactLength = true;
            return exactLength;
        }
        else {
            return false;
        }
    }  
    } 

function testNumber(value){
    return(!isNaN(value));
}

function validateCreditCard(value){
var firstDigit = value.charAt(0);
var newCard = "";
var validCard = false;

if (firstDigit == '3'){
        newCard = value.replace(/\s/g,'');
        validCard = testLength(newCard, 15, true);
        if(validCard == true){
            validCard = testNumber(newCard);
            if(validCard == false){
                alert("Credit Card is not a number");
                return false;
            }else{
                return true;
            }
        }
        else{
            alert("Credit card length is invalid. Please enter 15 digits.");
            return false;
        }
}
if (firstDigit == '6'){
    newCard = value.replace(/\s/g,'');
    validCard = testLength(newCard, 16, true);
    if(validCard == true){
        validCard = testNumber(newCard);
        if(validCard == false){
            alert("Credit Card is invalid");
            return false;
        }else{
            return true;
        }
    }
    else{
        alert("Credit card length is invalid.");
        return false;
    }
}
if (firstDigit == '5'){
    newCard = value.replace(/\s/g,'');
    validCard = testLength(newCard, 16, true);
    if(validCard == true){
        validCard = testNumber(newCard);
        if(validCard == false){
            alert("Credit Card is invalid");
            return false;
        }else{
            return true;
        }
    }
    else{
        alert("Credit card length is invalid.");
        return false;
    }
}
if (firstDigit == '4'){
    newCard = value.replace(/\s/g,'');
    validCard = testLength(newCard, 16, true);
    if(validCard == true){
        validCard = testNumber(newCard);
        if(validCard == false){
            alert("Credit Card is invalid");
            return false;
        }else{
            return true;
        }
    }
    else{
        alert("Credit card length is invalid.");
        return false;
    }
    }

else{
    alert("Invalid credit card");
    return false;
}
}

function validateEmail(value){
var regX = RegExp ("[a-zA-Z0-9_\.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9\.]{2,5}$");
var validEmail = regX.test(value);

if (!validEmail){
    alert("Invalid Email");
}
    return(validEmail);
}

function validateState(){
var state = document.getElementById("state");
var selectedValue = state.options[state.selectedIndex].value;
    if(selectedValue == 'SelectState'){
        alert("Please select a state");
        return false;
    }
    return true;
}

 function validatePassword(value, minLength){
     var validPassword = false;
     validPassword = testLength(value, 8, false);
     if(validPassword == true){
         return validPassword;
     }
     else{
         alert("Password is invalid. Must be 8 characters long");
         return validPassword;
     }
     }

     function validateDate(value){
     var date = document.getElementById("exDate").value;
     var varDate = new Date(date); //dd-mm-yyyy
     var today = new Date();
     var timestamp = new Date().getTime() + (30 * 24 * 60 * 60 * 1000);
     today.setHours(0,0,0,0);
     
    /* if(varDate > today) {
        alert("Expiration cannot be greater than today's date");
        return false;
     }*/
     if (timestamp < varDate){
        alert("Expiration cant be greater than today's date");
        return false;
     }
     else if (timestamp < varDate){
            return true;
     }
    }

function validateControl(control,name,length){
        var lengthCheck = testLength(control, length, true);
        if(lengthCheck == false){
            alert(name + " is not the appropriate length");
            return false;
        }
        var numCheck = testNumber(control);
        if(numCheck == false)
        {
        alert("This is not numerical");
        return false;
        }
        if(lengthCheck == true && numCheck == true){
            return true;
        }
        return true;
    }