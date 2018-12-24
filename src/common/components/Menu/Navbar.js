import React from 'react';
import {
  Collapse,
  Navbar as NavbarReactstrap,
  NavbarToggler,
  NavbarBrand,
  Nav
} from 'reactstrap';
import { Link } from "@reach/router";


class Navbar extends React.Component {

  constructor(props) {
    super(props);

    this.toggle = this.toggle.bind(this);
    this.state = {
      isOpen: false
    };
  }

  toggle() {
    this.setState({
      isOpen: !this.state.isOpen
    });
  }

  render() {
    return (
        <NavbarReactstrap color="dark" dark expand="md">
          <NavbarBrand href="/">reactstrap</NavbarBrand>
          <NavbarToggler onClick={this.toggle} />
          <Collapse isOpen={this.state.isOpen} navbar>
            <Nav className="ml-auto" navbar>

              {this.props.children}

            </Nav>
          </Collapse>
        </NavbarReactstrap>
       );
  }
}

export default Navbar