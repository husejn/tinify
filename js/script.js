var input_url = document.getElementById('url');
var shorten_error = document.getElementById('shorten_error');

function input_url_keyup(event) {
    if (event.which == 13 || event.keyCode == 13){
        shorten();
        return true;
    }
}

function shorten(){
	var input_url_value = input_url.value;
	if(navigator.onLine){
		if(input_url_value == ""){
			shorten_add_text("Please enter a link to shorten.", false);
		}
		else{
			var http = 'http://';
			if (input_url_value.substr(0, http.length) !== http){
			    input_url_value = http + input_url_value;
			}	

			try{
				var url = new URL(input_url_value);
				if(url.hostname == t_long_url || url.hostname == t_long_url_www){
			   		shorten_add_text("That is already a shortened URL.", false);
				}
				else{
					shorten_request(encodeURIComponent(input_url.value));
				}
			} catch(error){
				shorten_add_text("URL is not valid.", false);
			}

		}
	}
	else{
		shorten_add_text("Please check your network connection and try again.", false);
	}
}

function shorten_add_text(message, success){
	shorten_error.style.opacity="0.5";
	setTimeout(function(){
		shorten_error.style.opacity="1";
		shorten_error.innerHTML = message;
		shorten_error.className = "";
		if(success){
			shorten_error.className = "green_text";
		}
	}, 300)
}

function add_recent_url(short_url, long_url){
	var html = '<div style="opacity:0" class="recently_url"><div class="recently_urls_side"><img src="/images/link_icon.png" alt="Link"/></div><div class="recently_urls_center"><p class="short_url"><a target="_blank" href="'+short_url+'">'+short_url+'</a></p><p class="long_url"><a target="_blank" href="'+long_url+'">'+long_url+'</a></p></div><div class="recently_urls_side recently_urls_side_right"><a href="' + short_url + '+"><img src="/images/stats_icon.png" alt="Statistics" title="View Statistics"/></a></div></div>';
	var recently_urls = document.getElementById('urls');	

	var recently_urls_array = document.getElementsByClassName('recently_url');

	try{
		recently_urls_array[0].style.marginTop = "71px";
	} catch(e){}
	if(document.getElementById('no_shortlinks') !== null){
		document.getElementById('no_shortlinks').style.height = "0px";
	}

	var recently_urls_html = html + recently_urls.innerHTML;

	setTimeout(function(){
		recently_urls.innerHTML = recently_urls_html;
		recently_urls_array = document.getElementsByClassName('recently_url');
		try{
			recently_urls_array[1].style.marginTop = "0";
		} catch(e){}
		setTimeout(function(){
			recently_urls_array[0].style.opacity = "1";
		}, 100);
	}, 600);

}

function shorten_request(url) {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if(xhttp.readyState == 4 && xhttp.status == 200) {
			var json = JSON.parse(xhttp.responseText);
			if(json.Error != undefined){
				shorten_add_text(json.Error, false);
			}
			else{
				var short_url = "http://" + t_long_url + "/" + json.Success.hash;
				var long_url = json.Success.url;
				input_url.focus();	
				input_url.value = short_url;
				input_url.select();
				shorten_add_text("Link has been shortened. Press <b>Ctrl+C</b> to copy.", true);
				add_recent_url(short_url, long_url);
			}
		}
  	};
  	xhttp.open("POST", "/api/shorten.php", true);
  	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  	xhttp.send("url=" + url + "&add_cookie=1");
}

function validateLogin(){

	var form = document.forms.login;

	var username = form.username;
	var password = form.password;


	var username_error = document.getElementById("username_error");
	var password_error = document.getElementById("password_error");

	var shouldSubmit = true;


	// Check Username

	if(username.value == ""){
		shouldSubmit = registerError(username, username_error, "Username cannot be empty.");
	}


	// Check Passowrd

	if(password.value == ""){
		shouldSubmit = registerError(password, password_error, "Password cannot be empty.");
	}

	
	return shouldSubmit;

}

function validateRegister(){

	var form = document.forms.register;

	var name = form.name;
	var email = form.email;
	var username = form.username;
	var password = form.password;
	var repeat_password = form.repeat_password;

	var name_error = document.getElementById("name_error");
	var email_error = document.getElementById("email_error");
	var username_error = document.getElementById("username_error");
	var password_error = document.getElementById("password_error");
	var repeat_password_error = document.getElementById("repeat_password_error");
	var captcha_error = document.getElementById("captcha_error");

	var shouldSubmit = true;

	// Check Name

	if(name.value != ""){
		if(name.value.length >= 3){
			if(name.value.length <= 64){
				registerSuccess(name, name_error);
			}
			else{
				shouldSubmit = registerError(name, name_error, "Name must be shorter than 64 characters.");
			}
		}
		else{
			shouldSubmit = registerError(name, name_error, "Name must be at least 3 characters.");
		}
	}
	else{
		shouldSubmit = registerError(name, name_error, "Name cannot be empty.");
	}

	// Check Username

	if(username.value != ""){
		if(username.value.length >= 3){
			if(username.value.length <= 64){
				if(alphaNumeric(username.value)){
					registerSuccess(username, username_error);
				}
				else{
					shouldSubmit = registerError(username, username_error, "Username cannot contain special characters.");
				}		
			}
			else{
				shouldSubmit = registerError(username, username_error, "Username must be shorter than 64 characters.");
			}
		}
		else{
			shouldSubmit = registerError(username, username_error, "Username must be at least 3 characters.");
		}
	}
	else{
		shouldSubmit = registerError(username, username_error, "Username cannot be empty.");
	}

	// Check Email

	if(email.value != ""){
		if(validateEmail(email.value)){
			registerSuccess(email, email_error);
		}
		else{
			shouldSubmit = registerError(email, email_error, "Email is not valid.");
		}
	}
	else{
		shouldSubmit = registerError(email, email_error, "Email cannot be empty.");
	}

	// Check Passowrd

	if(password.value != ""){
		if(password.value.length >= 6){
			if(password.value.length <= 32){
				registerSuccess(password, password_error);
			}
			else{
				shouldSubmit = registerError(password, password_error, "Password must be shorter than 32 characters.");
			}
		}
		else{
			shouldSubmit = registerError(password, password_error, "Password must be at least 6 characters.");
		}
	}
	else{
		shouldSubmit = registerError(password, password_error, "Password cannot be empty.");
	}

	// Check Confirm Password

	if(repeat_password.value != ""){
		if(password.value == repeat_password.value){
			registerSuccess(repeat_password, repeat_password_error);
		}
		else{
			shouldSubmit = registerError(repeat_password, repeat_password_error, "Password does not match.");
		}
	}
	else{
		shouldSubmit = registerError(repeat_password, repeat_password_error, "Confirm password cannot be empty.");
	}

	// Verify Captcha

	if(grecaptcha.getResponse().length != 0){
		captcha_error.innerHTML = "&nbsp;";
	}
	else{
		captcha_error.innerHTML = "Please complete captcha verification.";
		shouldSubmit = false;
	}

	return shouldSubmit;

}

function validateContact(){

	var shouldSubmit = true;

	var form = document.forms.contact;

	var email = form.email;
	var message = form.message;

	var email_error = document.getElementById("email_error");
	var message_error = document.getElementById("message_error");

	if(email.value != ""){
		if(validateEmail(email.value)){
			registerSuccess(email, email_error);
		}
		else{
			shouldSubmit = registerError(email, email_error, "Email is not valid.");
		}
	}
	else{
		shouldSubmit = registerError(email, email_error, "Email cannot be empty.");
	}

	if(message.value != ""){
		if(message.value.length >= 12){
			if(message.value.length <= 1024){
				registerSuccess(message, message_error);
			}
			else{
				shouldSubmit = registerError(message, message_error, "Message must be shorter than 1024 characters.");
			}
		}
		else{
			shouldSubmit = registerError(message, message_error, "Message must be at least 12 characters.");
		}
	}
	else{
		shouldSubmit = registerError(message, message_error, "Message cannot be empty.");
	}

	return shouldSubmit;

}

function geoLocation(){
	if(navigator.geolocation){

		navigator.geolocation.getCurrentPosition(function(position){
			var latitude = position.coords.latitude;
			var longitude = position.coords.longitude;
			var response = httpGet("https://maps.googleapis.com/maps/api/geocode/json?address=" + latitude + "," + longitude + "&sensor=true");
			var json = JSON.parse(response);
			var short_code = json.results[json.results.length-1]["address_components"][0]["short_name"]
			changeCountry(short_code);
		});		
	}
}

function changeCountry(countryCode){
	document.querySelector("option[value="+countryCode+"]").setAttribute("selected", "selected")
}

function registerError(input, input_error, error){
	input_error.innerHTML = error;
	input.parentElement.parentElement.style.border = "1px solid red";
	return false;
}

function registerSuccess(input, input_error){
	input_error.innerHTML = "&nbsp;";
	input.parentElement.parentElement.style.border = "1px solid #d8dbdd";
}

function alphaNumeric(string){
    if(/[^a-zA-Z0-9]/.test(string)) {
       return false;
    }
    return true;     
}

function validateEmail(email){
    var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function httpGet(theUrl){
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", theUrl, false );
    xmlHttp.send( null );
    return xmlHttp.responseText;
}