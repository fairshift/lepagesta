function renderTemplate(template, data){

    //Unrendered templates
    $.get( template, function( data ) {
        $.when(
            $("#flow").html(Mustache.render( data ),
            $.Deferred(function( deferred ){
                    $( deferred.resolve );
                })
            ).done(function(){ 
                //signal(); 
            });
        );
    }
}

function translate(field, replaceArray){

    replaceArray = typeof replaceArray !== 'undefined' ? replaceArray : '0';
    var string = siteText[field];

    if(replaceArray != 0){
        $.each(replaceArray, function( index, element ) {
            string = string.replace("%"+index+"%", element);

        });
    }

    return string;

    //idea: bind translation to id, class & tag attribute to simplify
    //frontend or backend matching of shape and content? UX & efficiency
}

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

//http://stackoverflow.com/questions/7146217/merge-2-arrays-of-objects
function mergeLeftPrevail(arr2, arr1){
    var arr3 = [];
    for(var i in arr1){
       var shared = false;
       for (var j in arr2)
           if (arr2[j].name == arr1[i].name) {
               shared = true;
               break;
           }
       if(!shared) arr3.push(arr1[i])
    }
    return arr3.concat(arr2);
}
/*function arrayUnique(array) {
    for(var i=0; i<array.length; ++i) {
        for(var j=i+1; j<array.length; ++j) {
            if(array[i] === aray[j])
                array.splice(j--, 1);
        }
    }

    return a;
};*/