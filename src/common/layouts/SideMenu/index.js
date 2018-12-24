import React from 'react';
import { Container, Row, Col } from 'reactstrap';

export class WithSideMenu extends React.Component {

	render() {

		const menuClasses = (this.props.collapsed == false) ? 'menu open' : 'menu'

		// console.log(this.props.collapsed);

	  return (
	  	<Container className={"wrapper sideMenu"}>
        <Row>
          <Col>
          	<div className={menuClasses}>
			      	{this.props.SideMenu}
			      </div>
			      <div className="content">
			      	{this.props.children}
			      </div>
				 	</Col>
				</Row>
			</Container>
	  )
	}
}