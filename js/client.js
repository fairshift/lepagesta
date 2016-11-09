//Authentication
    var urlAuth = getUrlParameter('auth'); //social media integration point
    if(typeof urlAuth !== 'undefined'){
        if(urlAuth.length == '32'){
            auth = urlAuth;
            attachToCalls('auth='+auth);
            authenticate();
        }
    }
    if(auth == 0){
        auth = $.cookie("auth");
        if(typeof auth === 'undefined' || auth.length != 32){
            auth = 0;
        }
        attachToCalls('auth='+auth);
        authenticate();
    }

//Confirmation code
    if(typeof getUrlParameter('code') !== 'undefined'){
        if(getUrlParameter('code').length == '32'){
            var response = getData("confirm&code="+getUrlParameter('code'), attach, false);
        }
    }

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