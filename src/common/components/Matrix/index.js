import React from 'react'


export class Matrix extends React.Component {

	state = {
		font_face: "arial",
		font_size: 10,
		fill_font: "#0F0",
		fill_bg: "rgba(0, 0, 0, 0.05)",
		drops: [],
		chars: "Lek؋$ƒ$ман$$p.BZ$$$bKMPлвR$$៛$$$¥$₡kn₱KčkrRD$$£$kr€£$¢£Q£$L$FtkrRp﷼£₪J$¥£лв₩₩лв₭Ls£$LtденRM₨$₮MT$₨ƒ$C$₦₩kr﷼₨B/.GsS/.₱zł﷼leiруб£﷼Дин.₨$$SR₩₨krCHF$£NT$฿TT$₤$₴£$$UлвBs₫﷼Z$123456789012345678901234567890123456789012345678901234567890",
		interval: null,
		width: 0,
		height: 0
	}

	// this.inputRef.current.${functionName}

	constructor(props) {
	  super(props);
	  this.wrapper = React.createRef();
	  this.c = React.createRef();
	}

	componentDidMount(){

		if( typeof window !== 'undefined' ){

			this.reload();
			window.addEventListener("resize", this.reload);
		}
	}

	reload = () => {

		console.log("reload")

		let c = this.c.current,
				ctx = c.getContext("2d");

		c.height = this.wrapper.offsetHeight;
		c.width = this.wrapper.offsetWidth;

		this.setState({
			height: this.wrapper.offsetHeight,
			width: this.wrapper.offsetWidth
		});


		
		var font_size = this.state.font_size;
		var columns = this.state.width/font_size; //number of columns for the rain
		
		//an array of drops - one per column
		var drops = []; 
		//x below is the x coordinate
		//1 = y co-ordinate of the drop(same for every drop initially)
		for(var x = 0; x < columns; x++)
			drops[x] = 1;



		clearInterval(this.state.interval);

		this.setState({
			interval: setInterval(this.draw(c, ctx), 33),
			drops: drops
		});
	}

	draw = (c, ctx) => {

		let columns,
				width = this.state.width,
				height = this.state.height,
				font_face = this.state.font_face,
				font_size = this.state.font_size,
				font_fill = this.state.fill_font,
				bg_fill = this.state.bg_fill,
				drops = this.state.drops;

		//Black BG for the canvas
		//translucent BG to show trail
		ctx.fillStyle = bg_fill;
		ctx.fillRect(0, 0, width, height);
		
		ctx.fillStyle = font_fill; //green text
		ctx.font = font_size + "px "+font_face;
		//looping over drops
		var chars = this.state.chars.split("");
		
		for(var i = 0; i < drops.length; i++)
		{
			//a random chinese character to print
			var text = chars[Math.floor(Math.random()*chars)];
			//x = i*font_size, y = value of drops[i]*font_size
			ctx.fillText(text, width - i*font_size, drops[i]*font_size);
			
			//sending the drop back to the top randomly after it has crossed the screen
			//adding a randomness to the reset to make the drops scattered on the Y axis
			if(drops[i]*font_size > height && Math.random() > 0.975)
				drops[i] = 0;
			
			//incrementing Y coordinate
			drops[i]++;
		}

		ctx.restore();
	}

	componentWillUnmount(){
		clearInterval(this.state.interval);
	}



	render(){

		if( typeof window !== 'undefined' ){

			return (
				<div className="matrixContainer" ref={this.wrapper}>
					<canvas ref={this.c} height={100} width={100} ></canvas>
					{this.props.childen}
				</div>
			)

		} else {

			return (<div className="matrixContainer"></div>)
		}
	}
}

export default Matrix