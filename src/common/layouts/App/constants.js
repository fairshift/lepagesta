/*
 * AppConstants
 * Each action has a corresponding type, which the reducer knows and picks up on.
 * To avoid weird typos between the reducer and the actions, we save them as
 * constants here. We prefix them with 'yourproject/YourComponent' so we avoid
 * reducers accidentally picking up actions they shouldn't.
 *
 * Follow this format:
 * export const YOUR_ACTION_CONSTANT = 'yourproject/YourContainer/YOUR_ACTION_CONSTANT';
 */

export const IS_ONLINE = 'boilerplate/App/IS_ONLINE';
export const AUTH = 'boilerplate/App/AUTH';
export const AUTH_SET_FROM_COOKIE = 'boilerplate/App/AUTH_SET_FROM_COOKIE';
export const AUTH_SUCCESS_REGISTERED = 'boilerplate/App/AUTH_SUCCESS_REGISTERED';
export const AUTH_SUCCESS_ANONYMOUS = 'boilerplate/App/AUTH_SUCCESS_ANONYMOUS';
export const AUTH_ERROR_ONLINE = 'boilerplate/App/AUTH_ERROR_OFFLINE';
export const AUTH_ERROR_OFFLINE = 'boilerplate/App/AUTH_ERROR_OFFLINE';
export const APP_INTERVAL_RUN_FUNCTIONS = 'boilerplate/App/APP_INTERVAL_RUN_FUNCTIONS';
export const APP_PAUSE_INTERVAL_FNS = 'boilerplate/App/APP_PAUSE_INTERVAL_FNS';
export const APP_RESUME_INTERVAL_FNS = 'boilerplate/App/APP_RESUME_INTERVAL_FNS';
export const APP_CHANGE_DEFAULT_INTERVAL = 'boilerplate/App/APP_CHANGE_DEFAULT_INTERVAL';