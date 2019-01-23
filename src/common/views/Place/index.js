import React from 'react'
import Leaf from '../../components/Leaf'
import Matrix from '../../components/Matrix'


const texts = {
  "R_0": "This is what?",
  "R_1": "This is what,then?",
  "L_1A": "Hey"
}

class Place extends React.Component {

  state = {
    width: 'auto'
  }

  constructor(props) {
    super(props);
    this.container = React.createRef();
  }

  componentDidRender(){

    this.setState({
      width: this.container.current.offsetWidth
    })
  }

  render() {

    if( typeof window !== 'undefined' ){

      // !!! revisit for setting 
      return (
        <div ref={this.container} style={{ width: '100%', height: 'auto' }}  >
          <Leaf texts={texts} className="Leaf" width={this.state.width} height={this.state.width} />
          <Matrix className="Matrix" width={this.state.width} height={this.state.width} />
        </div>
      );
    } else {

      return ( <div></div> )
    }
  }
}


export default Place;