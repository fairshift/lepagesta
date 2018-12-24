/**
 * Combine all reducers in this file and export the combined reducers.
 */

import { combineReducers } from 'redux-immutable'

import clientReducer from './containers/App/reducer'
import languageProviderReducer from './containers/LanguageProvider/reducer'
import repositoriesReducer from './views/BoilerplatePage/reducer'

// Async reducers state
// import { initialState as uiState } from '../client/ui/reducer'

/**
 * Creates the main reducer with the dynamically injected ones
 */
export default function createReducer(injectedReducers) {
  return combineReducers({
    client: clientReducer,
    language: languageProviderReducer,
    repositories: repositoriesReducer,
    ...injectedReducers
  });
}

export function injectAsyncReducer(store, name, asyncReducer) {
  store.injectedReducers[name] = asyncReducer;
  store.replaceReducer(createReducer(store.injectedReducers));
}