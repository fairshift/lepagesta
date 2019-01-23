/**
 * app.js
 *
 * This is the entry file for the application, only setup and boilerplate
 * code.
 */

// Import all the third party stuff
import React from 'react';
import { Router } from '@reach/router'

// Import root App component
import App from './containers/App'

// List of pages (available in offline-first mode)
import Features from './containers/FeatureTree'
import ParserPage from './views/Parser'
import SignIn from './views/SignIn'

import PlaceLeaf from './views/Place'

// Async pages (not readily available in offline-first mode)
import BoilerplateHome from './views/BoilerplatePage/Loadable' // "react-boilerplate" project page


// Import CSS reset and Global Styles - will receive stripping of excessive tags later on
import './css-styles/global-styles'
import 'antd/dist/antd.css'
import 'bootstrap/dist/css/bootstrap.css'



const Root = () => (
  <Router>
    <App path='/'>
      <Features path='/' />
      <ParserPage path='/parser' />
      <BoilerplateHome path='/boilerplate' />
      <PlaceLeaf path="/place" />
    </App>
  </Router>
)

export default Root
