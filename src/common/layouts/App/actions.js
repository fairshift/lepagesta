/*
 * App Actions
 *
 * Actions change things in your application
 * Since this boilerplate uses a uni-directional data flow, specifically redux,
 * we have these actions which are the only way your application interacts with
 * your application state. This guarantees that your state is up to date and nobody
 * messes it up weirdly somewhere.
 *
 * To add a new Action:
 * 1) Import your constant
 * 2) Add a function like this:
 *    export function yourAction(var) {
 *        return { type: YOUR_ACTION_CONSTANT, var: var }
 *    }
 */

import gql from 'graphql-tag';


import { IS_ONLINE, 
  
         AUTH, AUTH_SET_FROM_COOKIE, 
         AUTH_SUCCESS_REGISTERED, AUTH_SUCCESS_ANONYMOUS, 
         AUTH_ERROR_ONLINE, AUTH_ERROR_OFFLINE,

         APP_INTERVAL_RUN_FUNCTIONS,
         APP_PAUSE_INTERVAL_FNS, APP_RESUME_INTERVAL_FNS,
         APP_CHANGE_DEFAULT_INTERVAL } from './constants';


export function setAuthFromCookie(token) {
  return {
    type: AUTH_SET_FROM_COOKIE,
    token
  };
}



export function setIntervalFunctions(intervalFunctionsList) {

  return {
    type: APP_INTERVAL_RUN_FUNCTIONS,
    intervalFunctionsList
  };
}

export function changeDefaultInterval(interval) {
  return {
    type: APP_CHANGE_DEFAULT_INTERVAL,
    interval
  };
}

export function pauseInterval(pause = true) {
  return {
    type: APP_PAUSE_INTERVAL_FNS,
    pause
  };
}

export function resumeInterval(pause = false) {
  return {
    type: APP_RESUME_INTERVAL_FNS,
    pause
  };
}

/*
const authQuery = gql`
query AuthClient($session_id: String!){
            auth(session_id: $session_id) {
              new_auth
              username
            }
          }`

      const result = apolloClient.query({
        variables: { text: "hello" },
        query: authQuery        
      })
      .then(result => { 
        console.log(result)

      })
      .catch(error => { console.log(error) });
*/