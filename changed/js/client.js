//Authenticate user, get user data & site language
    function authenticate(){
        $.get(api+"?"+attach, function(data){

            if(typeof data !== 'undefined'){
                if(data.length > 0){
                    data = $.parseJSON(data);
                    user = data['profile'];
                    $.cookie("auth", auth, { expires : 365 });

                    //language
//A feature scheduled for development - localization of site texts
                    /*$.get(api+"?call=localization&code="+language+"&"+attach, function(dataText){
                        if(typeof dataText !== 'undefined'){
                            if(dataText.length > 0){
                                siteTexts = $.parseJSON(dataText);*/

                                if(user['confirmed'] > 0){
                                    passport(data);
                                } else {
                                    landing();
                                }
                            /*}
                        }

                    });*/
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
            $.get(api+"?call=confirm&code="+getUrlParameter('code')+"&"+attach, function(data){

                if(typeof data !== 'undefined'){
                    if(data.length > 0){
                        data = $.parseJSON(data);
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
        $.get(api+"?call=passport&"+attach, $( passportForm ).serialize(), function(data){

            data = $.parseJSON(data);
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
                $.get(api+"?call=confirm&code="+getUrlParameter('code')+"&"+attach, function(data){

                    if(typeof data !== 'undefined'){
                        if(data.length > 0){
                            data = $.parseJSON(data);
                            if(data['status'] == 'welcome'){
                                passport(data);
                            }
                        }
                    }
                });
            }
        } else {
            $.get( "before-meteor/tpl/header/not-logged-in.html", function( data ) {
                $("#header").html(Mustache.render(data, siteTexts));
            });
            $.get( "before-meteor/tpl/passport/landing.html", function( data ) {
                $.when(
                    $("#flow").html(Mustache.render(data, siteTexts)),
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
                                    firstname: siteTexts['email_input_invalid']
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

                                $.get(api+"?call=loginFacebook&"+attach, function(data){
                                    
                                    data = $.parseJSON(data);
                                    if(typeof data['profile'] !== 'undefined' && data['profile'] != null){
                                        auth = data['profile']['auth'];
                                        user = data['profile'];
                                    } else {
                                        window.location.href = decodeURIComponent(data['login_url']);
                                    }
                                });
                            });
                            $(".loginTwitter").click(function(){
                                $.get(api+"?call=loginTwitter&"+attach, function(data){

                                    data = $.parseJSON(data);
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

    function translate(field, replaceArray){

        replaceArray = typeof replaceArray !== 'undefined' ? replaceArray : '0';
        var string = siteTexts[field];

        if(replaceArray != 0){
            $.each(replaceArray, function( index, element ) {
                string = string.replace("%"+index+"%", element);

            });
        }

        return string;

        //idea: bind translation to id, class & tag attribute to simplify
        //frontend or backend merging of shape and content? UX & efficiency
    }

    function registerPerson(){

        var email = $(".t-enter_email_input").val();
        var validatedOnce = false;
        $.get( "before-meteor/tpl/passport/register.html", function( data ) {
            $.when(
                $("#flow").html(Mustache.render(data, siteTexts)),
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

                            $.get(api+"?call=checkUsername&username="+encodeURIComponent($(".t-enter_register_username_input").val())+"&"+attach, function(data){

                                data = $.parseJSON(data);
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
                            username: siteTexts['input_tooshort'],
                            password: siteTexts['input_tooshort'],
                            password_confirm: siteTexts['input_password_nomatch']
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

                $.get(api+"?call=passport&"+attach, $( passportForm ).serialize(), function(data){

                    data = $.parseJSON(data);
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

        $.get( "before-meteor/tpl/passport/confirm.html", function( data ) {
            $.when(
                $("#flow").html(Mustache.render(data, siteTexts)),
                $.Deferred(function( deferred ){
                        $( deferred.resolve );
                    })
            ).done(function(){

                $(".t-enter_title_confirm").html(translate('enter_title_confirm', {"email": email}));
                $(".t-confirm_send_again").click(function(){
                    $.get(api+"?call=resendConfirmation&email="+email+"&"+attach, function(data){
                        $(".t-confirm_send_again").attr("disabled", "true");
                    });
                });
            });
        });
    }

    function signinPerson(){

        var email = $(".t-enter_email_input").val();
        $.get( "before-meteor/tpl/passport/signin.html", function( data ) {
            $.when(
                $("#flow").html(Mustache.render(data, siteTexts)),
                $.Deferred(function( deferred ){
                        $( deferred.resolve );
                    })
            ).done(function(){

                passportForm = "#form-signin";

                $(".t-signin_title").html(translate('enter_title_signin', {'email': email}));
                $(".t-enter_email_input").val(email);
                $(".t-signin_password").focus();

                // validate signup form on keyup and submit
                var validator = $(passportForm).validate({
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

//when user is logged in
    function passport(data){

        //set person's profile and load page
        $.get( "before-meteor/tpl/passport/profile-organization.html", function( data ) {
            $("#flow").html(Mustache.render(data, siteTexts));
        });

        auth = data['profile']['auth'];
        user = data['profile'];
        $.cookie("auth", auth, { expires : 30 });

        //$("#header").html(getTemplate("header/logged-in", siteTexts));
        //console.log(siteTexts);

        $.get( "before-meteor/tpl/header/logged-in.html", function( data ) {
            $.when(
                $("#header").html(Mustache.render(data, siteTexts)),
                $.Deferred(function( deferred ){
                        $( deferred.resolve );
                    })
            ).done(function(){
                $.get( "before-meteor/tpl/header/spoken-languages.html", function( data ) {
                    $("#spoken_languages").html(Mustache.render(data, siteTexts));
                });
            });
        });

        //$.when(
            /*$.getScript( "js/jquery/jquery-ui.js" ),
            $.getScript( "js/nouislider/nouislider.min.js" ),
            $.getScript( "before-meteor/tpl/fairshift/slider.js" ),
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

//Supplementary functions

    var attachArray = {};
    function attachToCalls(pair){
        console.log(pair);

        var keyValuePair = pair.split('=');
        attachArray[keyValuePair[0]] = encodeURIComponent(keyValuePair[1]);
        attach = renderAttachment(attachArray);

        console.log(attachArray);
        console.log(attach);

        return attach;
    }
    function detachFromCalls(key){
        delete attachArray(key);
        attach = renderAttachment(attachArray);
        return attach;
    }
    function renderAttachment(){
        attach = $.param(attachArray);
        return attach;
    }

    //Form input validation - now handled by jquery.validate
    function isValidEmailAddress(emailAddress) {

        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        return pattern.test(emailAddress);
    };

    function getUrlParameter(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    };