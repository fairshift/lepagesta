
import React from 'react'
import _ from 'lodash'


// How to... https://css-tricks.com/scale-svg/

const TextPaths = (props) => {
  if( typeof props.texts === 'object' ){

    props.texts.map((index, string) => {
      let htmlIndex = index.replace('_', '-')
      return <text width="500"><textPath alignment-baseline="top" xlinkHref="#Branch_{htmlIndex}">{string}</textPath></text>
    })
  }
}


export class Leaf extends React.Component {

  state = {
    stroke_width: "0",
    stroke_color: "none",
    viewBox: "0 0 1000 1000"
  }


  // Get text bounding box width
  /*
    var bbox = textElement.getBBox();
    var width = bbox.width;
    var height = bbox.height;
  */
  // Change font size
  // document.getElementById('Subtitle').style.fontSize = "25px";


  onComponentDidMount(){

  }

  render (){

    const { height, width, texts } = this.props

    let viewBox = this.state.viewBox
    if( typeof width !== 'undefined' ){

      viewBox = "0 0 "+ width +" "+width
    } else {
      if( typeof height !== 'undefined' ){

        viewBox = "0 0 "+ height +" "+height
      }
    }


    let stroke_width = ( typeof this.props.stroke_width === undefined ) ? this.state.stroke_width : this.props.stroke_width;
    let stroke_color = ( typeof this.props.stroke_color === undefined ) ? this.state.stroke_color : this.props.stroke_color


    return (

<svg xmlns="http://www.w3.org/2000/svg"
     width="6.94444in" height="6.94444in"
     viewBox={viewBox}>

  <TextPaths texts={texts} />

  <path id="Branch_L-5"
        fill="none" stroke={stroke_color} stroke-width={stroke_width}
        d="M 642.50,198.57
           C 645.36,130.00 604.29,140.36 613.93,42.86" />

  <path id="Branch_L-4"
        fill="none" stroke={stroke_color} stroke-width={stroke_width}
        d="M 549.29,293.93
           C 549.29,293.93 600.71,246.79 545.00,136.43
             489.29,26.07 506.79,30.00 506.79,30.00" />

  <path id="Branch_L-3"
        fill="none" stroke={stroke_color} stroke-width={stroke_width}
        d="M 516.79,324.29
           C 387.86,206.79 436.79,82.86 333.93,50.00" />

  <path id="Branch_L-2-1"
        fill="none" stroke={stroke_color} stroke-width={stroke_width}
        d="M 391.07,306.43
           C 391.07,306.43 150.36,229.29 138.21,63.57" />

  <path id="Branch_L-2"
        fill="none" stroke={stroke_color} stroke-width={stroke_width}
        d="M 432.50,458.57
           C 432.50,458.57 445.89,356.61 396.07,309.46
             346.25,262.32 219.64,167.14 232.50,30.00" />

  <path id="Branch_L-1-1"
        fill="none" stroke={stroke_color} stroke-width={stroke_width}
        d="M 62.50,438.57
           C 118.21,441.43 163.93,365.71 289.64,428.57" />

  <path id="Branch_L-1"
        fill="none" stroke={stroke_color} stroke-width={stroke_width}
        d="M 392.50,548.57
           C 306.79,378.57 67.86,341.07 58.57,130.00" />

  <path id="Branch_L-0"
        fill="none" stroke={stroke_color} stroke-width={stroke_width}
        d="M 355.36,727.14
           C 355.36,727.14 308.21,642.86 263.93,604.29
             219.64,565.71 122.50,560.00 75.36,508.57" />


  <path id="Branch_R-6"
        fill="none" stroke={stroke_color} stroke-width={stroke_width}
        d="M 732.50,104.29
           C 747.60,81.41 757.61,82.50 815.00,67.86
             878.50,54.57 867.50,55.00 931.07,32.86" />

  <path id="Branch_R-5"
        fill="none" stroke={stroke_color} stroke-width={stroke_width}
        d="M 663.21,177.86
           C 732.50,96.79 825.00,190.36 961.07,127.14" />

  <path id="Branch_R-4"
        fill="none" stroke={stroke_color} stroke-width={stroke_width}
        d="M 586.79,260.71
           C 662.50,162.14 782.86,265.00 968.21,209.29" />

  <path id="Branch_R-3-1"
        fill="none" stroke={stroke_color} stroke-width={stroke_width}
        d="M 575.36,367.14
           C 721.07,338.57 651.07,310.00 776.79,291.43" />

  <path id="Branch_R-3"
        fill="none" stroke={stroke_color} stroke-width={stroke_width}
        d="M 521.43,330.36
           C 728.21,478.57 683.57,375.00 956.79,287.14" />

  <path id="Branch_R-2-1"
        fill="none" stroke={stroke_color} stroke-width={stroke_width}
        d="M 525.18,575.18
           C 525.18,575.18 704.64,530.18 795.36,421.43" />

  <path id="Branch_R-2"
        fill="none" stroke={stroke_color} stroke-width={stroke_width}
        d="M 408.93,510.36
           C 683.93,714.29 801.62,466.53 951.07,394.29" />

  <path id="Branch_R-1B"
        fill="none" stroke={stroke_color} stroke-width={stroke_width}
        d="M 381.07,590.71
           C 405.44,643.94 496.07,603.21 579.64,638.57" />

  <path id="Branch_R-1A-1"
        fill="none" stroke={stroke_color} stroke-width={stroke_width}
        d="M 960.71,456.79
           C 960.71,456.79 933.93,588.57 767.86,640.00
             601.79,691.43 538.57,735.36 538.57,735.36" />

  <path id="Branch_R-1A"
        fill="none" stroke={stroke_color} stroke-width={stroke_width}
        d="M 381.07,590.71
           C 405.44,643.94 457.14,696.79 540.71,732.14
             673.57,778.21 728.21,678.57 912.50,704.29" />

</svg>

)
}
}


export default Leaf;