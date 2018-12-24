import React from 'react';

import { connect } from 'react-redux'
import { compose } from 'redux'
import { createStructuredSelector } from 'reselect'
/*import injectReducer from '../../utils/injectReducer'
import reducer from '../../../client/ui/reducer'*/
import { makeSelectScreenWidth } from '../../containers/App/selectors'
import { screenResized } from '../../containers/App/actions'

import { Link } from "@reach/router";

import { FormattedMessage } from 'react-intl';
import messagesMenu from './messages';
import messagesMenuAuth from '../../containers/SignIn/messages';

import {
  NavItem,
  NavLink,
  UncontrolledDropdown,
  DropdownToggle,
  DropdownMenu,
  DropdownItem } from 'reactstrap';

import Navbar from '../../components/Menu/Navbar'
import SignIn from '../../containers/SignIn/InMenu'


export class NavigationBar extends React.Component {

  state = {
    largeScreen: false
  }

  render() {

    const { screenWidth, dispatch } = this.props;
    /*if(typeof window !== 'undefined'){
      dispatch(screenResized(window.innerWidth))
    }*/

    let largeScreen;
    if(typeof screenWidth !== 'undefined'){
      if(screenWidth > 767){
        largeScreen = true
      } else if(screenWidth <= 767){
        largeScreen = false
      }
    } else {
      largeScreen: this.state.largeScreen
    }

    return (
      <Navbar brand={this.props.brand}>

        <NavItem>
          <NavLink href="https://github.com/reactstrap/reactstrap">GitHub</NavLink>
        </NavItem>
        <NavItem>
          <NavLink tag={Link} to="/">
            <FormattedMessage {...messagesMenu.home} />
          </NavLink>
        </NavItem>
        <NavItem>
          <NavLink tag={Link} to="/parser">
            Parser
          </NavLink>
        </NavItem>
        <NavItem>
          <NavLink tag={Link} to="/boilerplate">
            <FormattedMessage {...messagesMenu.features} />
          </NavLink>
        </NavItem>
        <NavItem className={ (largeScreen) ? "Navbar-SignIn hidden" : "Navbar-SignIn visible" }>
          <NavLink tag={Link} to="/signin">
            <FormattedMessage {...messagesMenuAuth.signin} />
          </NavLink>
        </NavItem>
        <UncontrolledDropdown nav inNavbar 
          className={ (!largeScreen) ? "Navbar-SignIn hidden" : "Navbar-SignIn visible" }
        >
          <DropdownToggle nav caret>
            <FormattedMessage {...messagesMenuAuth.signin} />
          </DropdownToggle>
          <DropdownMenu right>
            <SignIn />
          </DropdownMenu>
        </UncontrolledDropdown>

      </Navbar>
    );
  }
}



const mapStateToProps = createStructuredSelector({
  screenWidth: makeSelectScreenWidth(),
})

const withConnect = connect(
  mapStateToProps,
)

export default compose(
  withConnect
)(NavigationBar)

