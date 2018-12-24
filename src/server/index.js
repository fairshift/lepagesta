import express from 'express'
import cookieParser from 'cookie-parser'
import proxy from 'http-proxy-middleware'

import React from 'react'
import { renderToString } from 'react-dom/server'
import serialize from 'serialize-javascript'

import LanguageProvider from '../common/containers/LanguageProvider' // i18n provider
import { translationMessages } from '../common/i18n' // internationalization messages

// Revisit: store on SSR server for easier coding only? (Dummy variables, no persistent state)
import { compose } from 'redux'
import { Provider } from 'react-redux'
import configureStore from "../common/configureStore"
import createReducer from '../common/reducers'
import { addMiddleware } from 'redux-dynamic-middlewares'

import { createReduxHistoryContext, reachify } from "redux-first-history"
import createHistory from "history/createMemoryHistory"

// Data sources
import { clientInit, ApolloProvider, renderToStringWithData } from './graphqlApollo' 
import gql from 'graphql-tag'

import Root from '../common/app'
import { ServerLocation } from '@reach/router'

import { setAuthData } from './authCookie' // Get auth cookie and update redux store state (if necessary)



const API_HOST = 'http://localhost:4000';
const apiProxy = proxy({ target: API_HOST, changeOrigin: true });


const {  createReduxHistory,  routerMiddleware, routerReducer } = createReduxHistoryContext({ 
  history: createHistory()
});

const assets = require(process.env.RAZZLE_ASSETS_MANIFEST)

const server = express()
server.use(cookieParser())
server.use('/oauth', apiProxy);

server
  .disable('x-powered-by')
  .use(express.static(process.env.RAZZLE_PUBLIC_DIR))
  .get('/*', (req, res) => {

    // Create a new Redux store instance
    const store = configureStore()
      addMiddleware(routerMiddleware)
      store.replaceReducer(createReducer(store.routerReducer))
      const reachHistory = reachify(createReduxHistory(store))

    const client = clientInit()

    setAuthData(req, store)

    // Render the component to a string
    const markup = renderToString(
      <ApolloProvider client={client}>
        <Provider store={store}>
          <LanguageProvider history={reachHistory} messages={translationMessages}>
            <ServerLocation url={req.url}>
              <Root server={true}/>
            </ServerLocation>
          </LanguageProvider>
        </Provider>
      </ApolloProvider>
    )

    // Grab the initial state from our Redux store
    const finalState = store.getState()
    const graphqlState = client.extract()

    res.send(`<!doctype html>
    <html lang="">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta charSet='utf-8' />
        <title>Razzle Redux Example</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        ${
  assets.client.css
    ? `<link rel="stylesheet" href="${assets.client.css}">`
    : ''
}
          ${
  process.env.NODE_ENV === 'production'
    ? `<script src="${assets.client.js}" defer></script>`
    : `<script src="${assets.client.js}" defer crossorigin></script>`
}
    </head>
    <body>
        <div id="root">${markup}</div>
        <script>
          window.__PRELOADED_STATE__ = ${serialize(finalState)}
          window.__APOLLO_STATE__ = ${serialize(graphqlState)}
        </script>
    </body>
</html>`)
  })

export default server
