(function($){
	var username = $("#user_login"),
        useremail = $("#user_email"),
        firstname = $("#first_name"),
        lastname = $("#last_name"),
        password = $("#password"),
	    repeatPassword = $("#repeat_password"),
        inviteCode = $("#invitation_code"),
        captcha = $("#mc-input"),
        inputSubmit = $("#wp-submit"),
	    userPass = $("#user_pass"),
        rememberMe = $("#rememberme");

	username.attr("tabindex", 1);
	userPass.attr("tabindex", 2);
	useremail.attr("tabindex", 2);
	firstname.attr("tabindex", 3);
	
	lastname.attr("tabindex", 4);
	password.attr("tabindex", 5);
	repeatPassword.attr("tabindex", 6);
	inviteCode.attr("tabindex", 7);
	captcha.attr("tabindex", 8);
	rememberMe.attr("tabindex", 9);
	inputSubmit.attr("tabindex", 9);
	if("" == username.val()) {
		username.focus();
	} else if("" == useremail.val()) {
		useremail.focus();
	} else if("" == password.val()) {
		password.focus();
	} else if("" == repeatPassword.val()) {
		repeatPassword.focus();
	} else if("" == inviteCode.val()) {
		inviteCode.focus();
	} else if("" == captcha.val()) {
		captcha.focus();
	}
	
	var registerForm = $("#registerform");
	registerForm.submit(function(event){
		var hasError = false;
		if("" == username.val()) {
			username.addClass("error");
			hasError = true;
		}
		if("" == useremail.val()) {
			useremail.addClass("error");
			hasError = true;
		}
		if(8 > password.val().length) {
			password.addClass("error");
			hasError = true;
		}
		if("" == repeatPassword.val() || password.val() != repeatPassword.val()) {
			repeatPassword.addClass("error");
			hasError = true;
		}
		if("" == inviteCode.val()) {
			inviteCode.addClass("error");
			hasError = true;
		}
		if("" == captcha.val()) {
			captcha.addClass("error");
			hasError = true;
		}
		if(hasError) {
			if(event.preventDefault) {
                event.preventDefault();
			} else {
                event.returnValue = false;
			}
		}
	});
})(jQuery);