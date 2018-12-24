/*
 * AppReducer
 *
 * The reducer takes care of our data. Using actions, we can change our
 * application state.
 * To add a new action, add it to the switch statement in the reducer function
 *
 * Example:
 * case YOUR_ACTION_CONSTANT:
 *   return state.set('yourStateVariable', true);
 */

import { fromJS } from 'immutable';

import { IS_ONLINE, 
  
         AUTH, AUTH_SET_FROM_COOKIE, 
         AUTH_SUCCESS_REGISTERED, AUTH_SUCCESS_ANONYMOUS, 
         AUTH_ERROR_ONLINE, AUTH_ERROR_OFFLINE,

         APP_INTERVAL_RUN_FUNCTIONS,
         APP_PAUSE_INTERVAL_FNS, APP_RESUME_INTERVAL_FNS,
         APP_CHANGE_DEFAULT_INTERVAL } from './constants';



// The initial state of the App
const initialState = fromJS({
  auth: {
    token: '',
    logged_in: false,
    anonymous: true,
    authenticating: false,
    authenticated_since: ''
  },
  userData: {
    userId: '',
    username: '',
    display_name: '',
    network: ''
  },
  online: {
    status: true,
    offline_since: ''
  },
  codeExec: {
    intervalFunctionsList: null,
    defaultInterval: 5000,
    paused: false
  },
  error: false
});

function appReducer(state = initialState, action) {
  switch (action.type) {

    case IS_ONLINE:
      return state
        .setIn(['online', 'status'], action.online)
        .setIn(['online', 'offline_since'], action.offline_since)

    case AUTH_SET_FROM_COOKIE:
      return state
        .setIn(['auth', 'token'], action.token)

    case AUTH:
      return state
        .setIn(['auth', 'authenticating'], true)
        .set('error', false)

    case AUTH_SUCCESS_REGISTERED:
      return state
        .setIn(['auth', 'token'], action.token)
        .setIn(['auth', 'logged_in'], true)
        .setIn(['auth', 'anonymous'], false)
        .setIn(['auth', 'authenticating'], false)
        .setIn(['userData', 'userId'], action.userId)
        .setIn(['userData', 'username'], action.username)
        .setIn(['userData', 'display_name'], action.display_name)
        .setIn(['userData', 'network'], action.network)
        .set('error', false)

    case AUTH_SUCCESS_ANONYMOUS:
      return state
        .setIn(['auth', 'token'], action.token)
        .setIn(['auth', 'logged_in'], true)
        .setIn(['auth', 'anonymous'], true)
        .setIn(['auth', 'authenticating'], false)
        .setIn(['userData', 'userId'], '')
        .setIn(['userData', 'username'], '')
        .setIn(['userData', 'display_name'], '')
        .setIn(['userData', 'network'], '')
        .set('error', false)

    case AUTH_ERROR_ONLINE:
      return state
        .setIn(['auth', 'token'], action.token)
        .setIn(['auth', 'logged_in'], true)
        .setIn(['auth', 'anonymous'], true)
        .setIn(['auth', 'authenticating'], false)
        .setIn(['userData', 'userId'], '')
        .setIn(['userData', 'username'], '')
        .setIn(['userData', 'display_name'], '')
        .setIn(['userData', 'network'], '')

    case APP_INTERVAL_RUN_FUNCTIONS:
      return state
        .setIn(['codeExec', 'intervalFunctionsList'], action.intervalFunctionsList)

    case APP_CHANGE_DEFAULT_INTERVAL:
      return state
        .setIn(['codeExec', 'defaultInterval'], action.defaultInterval)

    case APP_PAUSE_INTERVAL_FNS:
      return state
        .setIn(['codeExec', 'paused'], true)

    case APP_RESUME_INTERVAL_FNS:
      return state
        .setIn(['codeExec', 'paused'], false)

    default:
      return state;
  }
}

export default appReducer;
