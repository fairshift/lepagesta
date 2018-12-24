/**
 *
 * App
 *
 * This component is the skeleton around the actual pages, and should only
 * contain code that should be seen on all pages. (e.g. navigation bar)
 */

import React from 'react';
import { Helmet } from 'react-helmet';
import styled from 'styled-components';

import { Container, Row, Col } from 'reactstrap';
import A from '../../components/Link'

import { MenuNavbar, MenuSide, MenuDrawer } from '../../components/Menu';
import Header from '../../components/Header';
import NavigationBar from '../../components/Menu/HeaderNavbar';
import Drawer from '../../components/Drawer'

import { FormattedMessage } from 'react-intl'
import LocaleToggle from '../../containers/LocaleToggle'
import messages from './messages'

import { StickyContainer, Sticky } from 'react-sticky'
import { Headroom } from 'react-headroom'


export default function App (props) {
  return (
    <div>
      <Headeroom>
            <Container className="headerWrapper">
              <Row>
                <Col>
                  <Helmet
                    titleTemplate='%s - React.js Boilerplate'
                    defaultTitle='React.js Boilerplate'
                  >
                    <meta name='description' content='A React.js Boilerplate application' />
                  </Helmet>
                  <Header>
                    <NavigationBar 
                      brand="Leap fairshift.or ge.store (reactstrap antd)" 
                      color="#fff" />
                  </Header>
                </Col>
              </Row>
            </Container>
          )}
      </Headeroom>
      <Container className="wrapper">
        <Row>
          <Col>
            <TextArea rows={4} />
          </Col>
        </Row>
        <Row>
          <Col>
            {props.children}
          </Col>
        </Row>
      </Container>
      <Container className="footerWrapper">
        <Row>
          <Col>
            <FormattedMessage {...messages.licenseMessage} />
          </Col>
          <Col>
            <LocaleToggle />
          </Col>
          <Col>
            <FormattedMessage
              {...messages.authorMessage}
              values={{
                author: <A href="https://twitter.com/mxstbr">Max Stoiber</A>
              }}
            />
          </Col>
        </Row>
      </Container>
      <Drawer content={props.content} />
    </div>
  )
}





//import { watch } from '../../is-online'

/*export default class App extends React.Component {
  constructor(props) {
    super(props)
    this.state = {}

    //if(typeof)
    //this.onlineStatusWatcher = watch()
  }

  
  static getDerivedStateFromProps(props, state) {   // Called when component: is created; receives new props
    
    if(typeof window !== 'undefined'){
      const { watch } = require('../../../client/is-online')

      return {
        onlineStatusWatcher: watch
      }
    }

    if (props.currentRow !== state.lastRow) {
      return {
        isScrollingDown: props.currentRow > state.lastRow,
        lastRow: props.currentRow,
      };
    }

    // Return null to indicate no change to state.
    return props;
  }*/
  
