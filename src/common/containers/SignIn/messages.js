import { defineMessages } from 'react-intl';

export const msgsJson = {
  login: {
    id: 'boilerplate.components.Auth.login',
    defaultMessage: 'Log in',
  },
  register: {
    id: 'boilerplate.components.Auth.register',
    defaultMessage: 'Register',
  },
  signin: {
    id: 'boilerplate.components.Auth.signin',
    defaultMessage: 'Sign in',
  },
  signup: {
    id: 'boilerplate.components.Auth.signup',
    defaultMessage: 'Sign up',
  },
  external_auth: {
    id: 'boilerplate.components.Auth.external_auth',
    defaultMessage: 'Authenticate with an external service',
  },
  signin_facebook: {
    id: 'boilerplate.components.Auth.signin_facebook',
    defaultMessage: 'Sign in with Facebook',
  },
  signin_twitter: {
    id: 'boilerplate.components.Auth.signin_twitter',
    defaultMessage: 'Sign in with Twitter',
  },
  signin_gmail: {
    id: 'boilerplate.components.Auth.signin_gmail',
    defaultMessage: 'Sign in with Gmail',
  },
  not_registered_email: {
    id: 'boilerplate.components.Auth.not_registered_email',
    defaultMessage: 'Not registered with your e-mail yet?'
  },
  user: {
    id: 'boilerplate.components.Auth.user',
    defaultMessage: 'E-mail or username',
  },
  user_email_err: {
    id: 'boilerplate.components.Auth.user_email_err',
    defaultMessage: 'E-mail is not in the right shape',
  },
  username_err_length: {
    id: 'boilerplate.components.Auth.username_err_length',
    defaultMessage: 'Please log in with your e-mail or username',
  },
  username_err_invalid_chars: {
    id: 'boilerplate.components.Auth.username_err_invalid_chars',
    defaultMessage: 'Username contains invalid characters',
  },
  password: {
    id: 'boilerplate.components.Auth.password',
    defaultMessage: 'Password',
  },
  password_confirm: {
    id: 'boilerplate.components.Auth.password_confirm',
    defaultMessage: 'Repeat password to confirm',
  },
  password_err_length: {
    id: 'boilerplate.components.Auth.password_err_length',
    defaultMessage: 'Password is too short ( > 8 characters )',
  },
  password_err_mismatch: {
    id: 'boilerplate.components.Auth.password_err_mismatch',
    defaultMessage: 'Passwords don\'t match',
  }
}

export default defineMessages(msgsJson);  
