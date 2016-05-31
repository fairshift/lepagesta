//Routing
	//setup crossroads
	crossroads.addRoute('foo/{id*}', function(id){alert(id);});
	crossroads.addRoute('lorem/ipsum');
	crossroads.routed.add(console.log, console); //log all routes
	 
	//setup hasher
	function parseHash(newHash, oldHash){
	  crossroads.parse(newHash);
	}
	hasher.initialized.add(parseHash); //parse initial hash
	hasher.changed.add(parseHash); //parse hash changes
	hasher.init(); //start listening for history change


//Foundation 5.5.2
	 $(document).foundation();

//Site config
    var domain = window.location.hostname;
    var site_id = 0;
    var api = "localhost/fairshift/lepagesta";
    attach = attachToCalls('o', domain);

//Data cache object
	var nodes;

//Authentication
  	var auth = 0;
	var urlAuth = getUrlParameter('auth'); //social login
	if(typeof urlAuth !== 'undefined'){
		if(urlAuth.length == '32'){
			auth = urlAuth;
			attachToCalls('auth', auth);
			authenticate();
		}
	}
	if(auth == 0){
		auth = $.cookie("auth");
		if(typeof auth === 'undefined' || auth.length != 32){
    		auth = 0;
		}
		attachToCalls('auth', auth);
    	authenticate();
  	}

//Language
	var language = $.cookie("language"); //default
	var site; //localization of site texts
  	if(typeof language !== 'undefined' || (typeof user !== 'undefined' && typeof user['language'] != 'undefined')){
  	} else {
    	language = 'en';
	    $.cookie("language", language, { expires : 365 });
  	}

//Authenticate user, get user data & site language
    function authenticate(){
      	$.get("http://"+api+"/api/main.php?"+attach+"&call=siteText&code="+language, function(data){

	        if(typeof data !== 'undefined'){
	        	if(data.length > 0){
	        		data = parseResponse(data);
		            user = nodes['profile'][data['user']['id']];
		            auth = data['auth'];
					$.cookie("auth", auth, { expires : 365 });

		            if(user['confirmed'] > 0){
		            	passport(data);
		            } else {
		            	landing();
		            }
	        	}
	        } else {
				landing();
	        }
     	});
    }

//Confirmation code
	if(typeof getUrlParameter('code') !== 'undefined'){
		if(getUrlParameter('code').length == '32'){

			passportStatus = 'confirm';
			$.get("http://"+api+"/api/main.php?"+attach+"&call=confirm&code="+getUrlParameter('code'), function(data){

		        if(typeof data !== 'undefined'){
		        	if(data.length > 0){
						data = parseResponse(data);
						if(data['status'] == 'welcome'){
							passport(data);

						}
					}
				}

			});
		}
	}

	var passportStatus = 0;
	var passportForm = "#form-enter";
	function enter(){
      	$.get("http://"+api+"/api/main.php?"+attach+"&call=passport", $( passportForm ).serialize(), function(data){

	        data = parseResponse(data);
	        console.log(data);
	        passportStatus = data['status'];

	        if(typeof passportStatus !== 'undefined'){
	          if(passportStatus == 'register'){
	          	registerPerson();
	          }
	          if(passportStatus == 'confirm'){
	          	confirmPerson();
	          }
	          if(passportStatus == 'signin'){
	          	signinPerson();
	          }
	          if(passportStatus == 'welcome'){
	          	passport(data);
	          }
	        } else {
	          //well, something's not working on the backend...
	        }
      	});
	};

  	function landing(){

		if(typeof getUrlParameter('code') !== 'undefined'){
			if(getUrlParameter('code').length == '32'){

				passportStatus = 'confirm';
				$.get("http://"+api+"/api/main.php?"+attach+"&call=confirm&code="+getUrlParameter('code'), function(data){

			        if(typeof data !== 'undefined'){
			        	if(data.length > 0){
							data = parseResponse(data);
							if(data['status'] == 'welcome'){
								passport(data);
							}
						}
					}
				});
			}
		} else {
			$.get( "tpl/header/not-logged-in.html", function( data ) {
		    	$("#header").html(Mustache.render(data, siteText));
			});
			$.get( "tpl/passport/landing.html", function( data ) {
		    	$.when(
		    		$("#flow").html(Mustache.render(data, siteText)),
		    		$.Deferred(function( deferred ){
					        $( deferred.resolve );
					    })
				).done(function(){

			            if(typeof user['email'] !== 'undefined' && user['email'] == null){
			 				/*$("#enter-title").html(translate('enter_title'));
			 				$("#enter-email").attr("placeholder", translate('enter_title_input'));
			 				$("#enter-continue").attr("value", translate('enter_continue'));*/

							// validate signup form on keyup and submit
							var validator = $("#form-enter").validate({
								onkeyup: false,
								rules: {
									email: {
										required: true,
										email: true
									}
								},
								messages: {
									firstname: siteText['email_input_invalid']
								},

								// the errorPlacement has to take the table layout into account
								errorPlacement: function(error, element) {
									if (element.is(":radio"))
										error.appendTo(element.next());
									else if (element.is(":checkbox"))
										error.appendTo(element.next());
									else
										error.appendTo(element.next());
								},
								// specifying a submitHandler prevents the default submit, good for the demo
								submitHandler: function() {
									enter();
								},
								// set this class to error-labels to indicate valid fields
								success: function(label) {
									// set &nbsp; as text for IE
									//label.html("&nbsp;").addClass("checked");
								},
								highlight: function(element, errorClass) {
									$(element).next().find("." + errorClass).removeClass("checked");
								}
							});

							$(".loginFacebook").click(function(){
						      	$.get("http://"+api+"/api/main.php?"+attach+"&call=loginFacebook", function(data){
						      		
						      		data = parseResponse(data);
						      		if(typeof data['user'] !== 'undefined' && data['user'] != null){
						      			auth = data['user']['auth'];
						      			user = data['user'];
						      		} else {
						      			console.log(data['login_url']);
						      			window.location.href = decodeURIComponent(data['login_url']);
						      		}
						      	});
							});
							$(".loginTwitter").click(function(){
						      	$.get("http://"+api+"/api/main.php?"+attach+"&call=loginTwitter", function(data){

						      		data = parseResponse(data);
						      		if(parseInt(data['status_code']) == 200){
						      			window.location.href = data['login_url'];
						      		}
						      	});
							});
						}
				});
			});
		}
	}



	function registerPerson(){

		var email = $(".t-enter_email_input").val();
		var validatedOnce = false;
		$.get( "tpl/passport/register.html", function( data ) {
	    	$.when(
	    		$("#flow").html(Mustache.render(data, siteText)),
	    		$.Deferred(function( deferred ){
				        $( deferred.resolve );
				    })
			).done(function(){

					passportForm = "#form-register";

					$("#register-email").val(email);
					$(".t-enter_title_register").html(translate('enter_title_register', {"email": email}));

					registerSubmit();

					$(".t-enter_register_username_input").keyup(function(){
						if($(".t-enter_register_username_input").val().length >= 3){

							$.get("http://"+api+"/api/main.php?"+attach+"&call=checkUsername&username="+encodeURIComponent($(".t-enter_register_username_input").val()), function(data){

								data = parseResponse(data);
								status = data['status'];
								if(status == 'available'){
									$(".t-enter_register_username_invalid").html(translate('enter_register_username_available', {'username': $(".t-enter_register_username_input").val()}));
									$(".t-enter_register_username_invalid").addClass("success");
									$(".t-enter_register_username-invalid").removeClass("error");
								} else {
									$(".t-enter_register_username_invalid").html(translate('enter_register_username_taken', {'username': $(".t-enter_register_username_input").val()}));
									$(".t-enter_register_username_invalid").addClass("error");
									$(".t-enter_register_username_invalid").removeClass("success");
								}
							});
						}
					});

					// validate signup form on keyup and submit
					var validator1 = $("#form-register").validate({
						onkeyup: function(element) {$(element).valid()},
						rules: {
							password: {
								required: true,
								minlength: 6
							},
							password_confirm: {
								required: true,
								equalTo: ".t-register_password"
							},
							username: {
								required: true,
								minlength: 3
							}
						},
						messages: {
							username: siteText['input_tooshort'],
							password: siteText['input_tooshort'],
							password_confirm: siteText['input_password_nomatch']
						},
						// the errorPlacement has to take the table layout into account
						errorPlacement: function(error, element) {
							if($(element).prop("tagName") == 'INPUT'){
									error.appendTo(element.next().next());
							} else {
								if (element.is(":radio"))
									error.appendTo(element.next());
								else if (element.is(":checkbox"))
									error.appendTo(element.next());
								else
									error.appendTo(element.next());
							}
						},
						// specifying a submitHandler prevents the default submit, good for the demo
						submitHandler: function() {
							alert("lol");
							enter();
						},
						// set this class to error-labels to indicate valid fields
						success: function(label) {
							// set &nbsp; as text for IE
							//label.html("&nbsp;").addClass("checked");
						},
						highlight: function(element, errorClass) {
							$(element).next().find("." + errorClass).removeClass("checked");
						}
					});
			});
		});
	}

	function registerSubmit(){
 		$(".t-enter_continue_register").click(function(){
 			/*alert($(".t-enter_register_username_input").val().length > 3);
 			alert($(".t-enter_register_username_invalid").hasClass("success"));
 			alert($(".t-enter_register_password_input").val().length > 6);
 			alert($(".t-enter_register_password_input").val() == $(".t-enter_register_confirm_input").val());*/

 			if($(".t-enter_register_username_invalid").hasClass("success") && $(".t-enter_register_password_input").val().length > 6 
 				&& $(".t-enter_register_password_input").val() == $(".t-enter_register_confirm_input").val()){

		      	$.get("http://"+api+"/api/main.php?"+attach+"&call=passport", $( passportForm ).serialize(), function(data){

			        data = parseResponse(data);
			        console.log(data);
			        passportStatus = data['status'];

			        if(typeof passportStatus !== 'undefined'){
			          if(passportStatus == 'register'){
			          	registerPerson();
			          }
			          if(passportStatus == 'confirm'){
			          	confirmPerson();
			          }
			        } else {
			          //well, something's not working...
			        }
		      	});
			}
		});
	}

	function confirmPerson(){

		var email = $(".t-enter_email_input").val();
		console.log(email);

		$.get( "tpl/passport/confirm.html", function( data ) {
	    	$.when(
	    		$("#flow").html(Mustache.render(data, siteText)),
	    		$.Deferred(function( deferred ){
				        $( deferred.resolve );
				    })
			).done(function(){

				$(".t-enter_title_confirm").html(translate('enter_title_confirm', {"email": email}));
	     		$(".t-confirm_send_again").click(function(){
					$.get("http://"+api+"/api/main.php?"+attach+"&call=resendConfirmation&email="+email, function(data){
						$(".t-confirm_send_again").attr("disabled", "true");
		     		});
				});
			});
		});
	}

	function signinPerson(){

		var email = $(".t-enter_email_input").val();
		$.get( "tpl/passport/signin.html", function( data ) {
	    	$.when(
	    		$("#flow").html(Mustache.render(data, siteText)),
	    		$.Deferred(function( deferred ){
				        $( deferred.resolve );
				    })
			).done(function(){

	     		passportForm = "#form-signin";

				$(".t-signin_title").html(translate('enter_title_signin', {'email': email}));
				$(".t-enter_email_input").val(email);
	     		$(".t-signin_password").focus();

				// validate signup form on keyup and submit
				var validator = $("#form-signin").validate({
					onkeyup: function(element) {$(element).valid()},
					rules: {
						password: {
							required: true,
							minlength: 6
						}
					},
					messages: {
						password: translate('input_tooshort')
					},
					// the errorPlacement has to take the table layout into account
					errorPlacement: function(error, element) {
						if($(element).hasClass('t-enter_register_password')){
								error.appendTo(element.next().next());
							} else {
							if (element.is(":radio"))
								error.appendTo(element.next());
							else if (element.is(":checkbox"))
								error.appendTo(element.next());
							else
								error.appendTo(element.next());
						}
					},
					// specifying a submitHandler prevents the default submit, good for the demo
					submitHandler: function() {
	     				$(".t-signin_continue").attr("disabled", 'true');
						enter();
					},
					// set this class to error-labels to indicate valid fields
					success: function(label) {
						// set &nbsp; as text for IE
						//label.html("&nbsp;").addClass("checked");
					},
					highlight: function(element, errorClass) {
						$(element).next().find("." + errorClass).removeClass("checked");
					}
				});
			});
		});
	}

//when user logged in successfully
	function passport(data){

		//set person's profile and load page
		$.get( "tpl/passport/profile-organization.html", function( data ) {
	    	$("#flow").html(Mustache.render(data, siteText));
		});

		auth = data['user']['auth'];
		user = data['user'];
	    $.cookie("auth", auth, { expires : 30 });

	    //$("#header").html(getTemplate("header/logged-in", siteText));
	    //console.log(siteText);

		$.get( "tpl/header/logged-in.html", function( data ) {
	    	$.when(
	    		$("#header").html(Mustache.render(data, siteText)),
	    		$.Deferred(function( deferred ){
				        $( deferred.resolve );
				    })
			).done(function(){
				$.get( "tpl/header/spoken-languages.html", function( data ) {
			    	$("#spoken_languages").html(Mustache.render(data, siteText));
				});
			});
		});

		//$.when(
		    /*$.getScript( "js/jquery/jquery-ui.js" ),
		    $.getScript( "js/nouislider/nouislider.min.js" ),
		    $.getScript( "tpl/fairshift/slider.js" ),
		    $('<link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui.css">').appendTo("head"),
		    $('<link rel="stylesheet" type="text/css" href="js/nouislider/nouislider.min.css">').appendTo("head"),
		    $.getScript( "js/jquery/jquery-ui.js" ),
		    $.getScript( "js/jquery/jquery-ui.js" ),
		    $.Deferred(function( deferred ){
		        $( deferred.resolve );
		    })
		).done(function(this){
		    //place your code here, the scripts are all loaded
		});
	    $("#reflection-section").show();
	    setupreflections();
	    loadSlider();*/
	    alert("okay");
	}
	$("#profile-open").click(function(){
		$("#profile-section").show();
	    $("#reflection-section").hide();
	});