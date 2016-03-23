function loadSlider(){
	/*http://stackoverflow.com/questions/30143082/how-to-get-color-value-from-gradient-by-percentage-with-javascript thanks*/
	var gradient = [
	    [
	        0,
	        [255,255,255]
	    ],
	    [
	        100,
	        [255,0,0]
	    ]
	    //color coding - for what should colors be reserved?
	    	//should each value have a different colour?
	];

	var reflectingElement;
	var slider = document.getElementById("slider");
	noUiSlider.create(slider, {
		start: 50,
		orientation: 'vertical', // Orient the slider vertically
		range: {
			'min': 0,
			'max': 100
		}
	});
	$("#slider").hide();

	$(".addKeyword").click(function(){
		$("#addKeyword").css("display", "inline");
	});
	$("#confirmKeyword").click(function(){
		if($("#keyword").val().length > 1){
			$("#newKeywords").append(", <a class='reflect'>"+$("#keyword").val()+"</a>");
		      setupreflections();
			$("#newKeywords .reflect").click(function(){

				$("#slider").show();

				reflectingElement = $(this);
				slider.noUiSlider.set(50);

				slider.noUiSlider.on('change', function(){
	  
			        //Get the two closest colors
			        var firstcolor = [255,0,0];
			        var secondcolor = [255,255,255];
			        
			        //Get the color with pickHex(thx, less.js's mix function!)
			        //var result = pickHex( secondcolor,firstcolor, ratio );
			        var result = pickHex( secondcolor,firstcolor, slider.noUiSlider.get()/100 );
		        	reflectingElement.css("color", 'rgb('+result.join()+')');
					$("#slider").hide();
				});

				$("#slider").position({
				    my:        "left+5px center",
				    at:        "right center",
				    of:        this, // or $("#otherdiv)
				    collision: "fit"
				});

			});
			$("#addKeyword").hide();
			$("#keyword").val("");
		} else {
			alert("Too short!");
		}
	});
	$("#cancelKeyword").click(function(){
		$("#addKeyword").hide();
		$("#keyword").val("");
	});

	$(".reflect").click(function(){

		$("#slider").show();

		reflectingElement = $(this);
		slider.noUiSlider.set(50);

		slider.noUiSlider.on('change', function(){

	        //Get the two closest colors
	        var firstcolor = [255,0,0];
	        var secondcolor = [255,255,255];
	        
	        //Get the color with pickHex(thx, less.js's mix function!)
	        //var result = pickHex( secondcolor,firstcolor, ratio );
	        var result = pickHex( secondcolor,firstcolor, slider.noUiSlider.get()/100 );
        	reflectingElement.css("color", 'rgb('+result.join()+')');
			$("#slider").hide();

			setupreflections();
		});

		$("#slider").position({
		    my:        "left+5px center",
		    at:        "right center",
		    of:        this, // or $("#otherdiv)
		    collision: "fit"
		});

	});
}

function pickHex(color1, color2, weight) {
    var p = weight;
    var w = p * 2 - 1;
    var w1 = (w/1+1) / 2;
    var w2 = 1 - w1;
    var rgb = [Math.round(color1[0] * w1 + color2[0] * w2),
        Math.round(color1[1] * w1 + color2[1] * w2),
        Math.round(color1[2] * w1 + color2[2] * w2)];
    return rgb;
}