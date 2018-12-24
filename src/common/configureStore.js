/**
 * Create the store with dynamic reducers
 */

import { createStore, applyMiddleware, compose } from 'redux';
import { fromJS } from 'immutable';
import createSagaMiddleware from 'redux-saga';
import createReducer from './reducers';

import dynamicMiddlewares from 'redux-dynamic-middlewares'


const sagaMiddleware = createSagaMiddleware()

export default function configureStore (initialState = {}) {
  // Create the store with two middlewares
  // 1. sagaMiddleware: Makes redux-sagas work
  // 2. routerMiddleware: Syncs the location/URL path to the state
  const middlewares = [sagaMiddleware, dynamicMiddlewares]

  let store = null;
  if(typeof window !== 'undefined'){
    /* eslint-disable no-underscore-dangle */
    store = createStore(
      createReducer(),
      fromJS(initialState),
      applyMiddleware(...middlewares),
      window.__REDUX_DEVTOOLS_EXTENSION__ && window.__REDUX_DEVTOOLS_EXTENSION__()
    )
    /* eslint-enable */
    // console.log("Redux DevTools extension doesn't display any state on my system");

  } else {

    store = createStore(
      createReducer(),
      fromJS(initialState),
      applyMiddleware(...middlewares)
    )
  }
  
  // Extensions
  store.runSaga = sagaMiddleware.run
  store.injectedReducers = {} // Reducer registry
  store.injectedSagas = {} // Saga registry
  /* istanbul ignore next */
  if (module.hot) {
    module.hot.accept('./reducers', () => {
      store.replaceReducer(createReducer(store.injectedReducers))
    })
  }

  return store
}
