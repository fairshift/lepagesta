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
	//frontend or backend merging of shape and content? UX & efficiency
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

var ajaxAttachments = [];
var ajaxAttach = "";
function attachToCalls(parameter, value){
	ajaxAttachments[parameter] = value;
	return renderAjaxAttachments();
}
function detachFromCalls(parameter){
	delete(ajaxAttachments[parameter]);
	return renderAjaxAttachments();
}
function renderAjaxAttachments(){
	if(typeof ajaxAttachments !== 'undefined'){
		$i = 0;
		for (var key in ajaxAttachments) {
			if($i == 0){
				ajaxAttach = key + "=" + ajaxAttachments[key];
			} else {
				ajaxAttach = ajaxAttach + "&" + key + "=" + ajaxAttachments[key];
			}
		  	$i++;
		}
	}
	return ajaxAttach;
}

function parseResponse(data){
	if(data.length > 0){
		data = $.parseJSON(data);
		nodes = 

		/*
		backend will store data nodes it sends out for currnt 
		some queried nodes could've changed in the meanwhile and will be returned (overwrite)
		a single node isn't complete as it's queried from the database (history of changes, lines of content get loaded on demand)
		*/
	}
}