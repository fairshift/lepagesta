
// Layout components
import React from "react"
import ReactDOM from "react-dom"
import { Provider } from "react-redux"


// Application state (centralized data store) — managing components
import configureStore from "../common/configureStore"
import { createReduxHistoryContext, reachify } from "redux-first-history"
import createReducer from '../common/reducers'
import { addMiddleware } from 'redux-dynamic-middlewares'
import { compose } from 'redux'

// Application state (centralized data store) — modifying components
import createHistory from "history/createMemoryHistory"
import { screenResized } from '../common/containers/App/actions'


// Data sources — database link with backend GraphQL server
import ApolloClient from 'apollo-client';
import { ApolloLink } from 'apollo-link';
import { InMemoryCache } from 'apollo-cache-inmemory'
import { links } from './graphqlApollo'
import { ApolloProvider } from 'react-apollo'

// Data sources — local database (client-side or in Node.js)
import { db, databaseContext } from './dbLocalStorage'


// Internationalization & messages providers
import LanguageProvider from "../common/containers/LanguageProvider"
import { translationMessages } from "../common/i18n"


// Main application wrapper (using the above components)
import Root from "../common/app"



// require('codemirror/lib/codemirror.css');

// Router history
const { createReduxHistory, routerMiddleware, routerReducer } = createReduxHistoryContext({ 
  history: createHistory()
})

// Create a new Redux store instance
const store = configureStore(window.__PRELOADED_STATE__);
    addMiddleware(routerMiddleware)
    store.replaceReducer(createReducer(store.routerReducer))
    const reachHistory = reachify(createReduxHistory(store))

window.addEventListener('resize', () => {
  console.log(window.innerWidth);
  store.dispatch(screenResized(window.innerWidth));
});

// Apollo GraphQL client
const client = new ApolloClient({
  ssrForceFetchDelay: 100,
  link: ApolloLink.from(links),
  connectToDevTools: true,
  // here we're initializing the cache with the data from the server's cache
  cache: new InMemoryCache(),
});

const MOUNT_NODE = document.getElementById("root");

const hydrate = messages => {
  ReactDOM.hydrate(
    <ApolloProvider client={client}>
      <databaseContext.Provider value={db}>
        <Provider store={store}>
          <LanguageProvider messages={messages}>
            <Root client={true}/>
          </LanguageProvider>
        </Provider>
      </databaseContext.Provider>
    </ApolloProvider>,
    document.getElementById("root")
  );
};

if (module.hot) {
  module.hot.accept("../common/app", () => {
    ReactDOM.hydrate(
      <ApolloProvider client={client}>
        <databaseContext.Provider value={db}>
          <Provider store={store}>
            <LanguageProvider messages={translationMessages}>
              <Root />
            </LanguageProvider>
          </Provider>
        </databaseContext.Provider>
      </ApolloProvider>,
      document.getElementById("root")
    );
  });
}

if (module.hot) {
  // Hot reloadable React components and translation json files
  // modules.hot.accept does not accept dynamic dependencies,
  // have to be constants at compile-time
  module.hot.accept(["../common/i18n", "../common/app"], () => {
    ReactDOM.unmountComponentAtNode(MOUNT_NODE);
    hydrate(translationMessages);
  });
}

if (!window.Intl) {
  new Promise(resolve => {
    resolve(import("intl"));
  })
    .then(() =>
      Promise.all([
        import("intl/locale-data/jsonp/en.js"),
        import("intl/locale-data/jsonp/de.js")
      ])
    )
    .then(() => hydrate(translationMessages))
    .catch(err => {
      throw err;
    });
} else {
  hydrate(translationMessages);
}
