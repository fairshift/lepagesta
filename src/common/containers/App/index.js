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

import Header from '../../components/Header';
import NavigationBar from '../AppMenu/Navbar';

import { FormattedMessage } from 'react-intl'
import LocaleToggle from '../../containers/LocaleToggle'
import messages from './messages'

import { AppConfig } from '../../../../local/config'
import Headroom from 'react-headroom'
import { StickyContainer, Sticky } from 'react-sticky'

import { Parallax, Background } from 'react-parallax';


export default function App (props) {
  return (
    <Parallax bgImage={'lepa-gesta-earthday-gb.png'} blur={{ min: 0, max: 0 }} strength={1000}>

      <Headroom>
        <Container className="headerWrapper">
          <Row>
            <Col>
              <NavigationBar 
                brand={AppConfig.brand} 
                color="#fff" />
            </Col>
          </Row>
        </Container>
      </Headroom>


      <div className="main-container">

        <Helmet
          defaultTitle={AppConfig.meta.defaultTitle}
          titleTemplate={AppConfig.meta.titleTemplate}
        >
          <meta name='description' content={AppConfig.meta.description} />
        </Helmet>

        <Container className="headerWrapper">
          <Row>
            <Col>
              <Header />
            </Col>
          </Row>
        </Container>

        {props.children}
        
      </div>

    </Parallax>
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
  
