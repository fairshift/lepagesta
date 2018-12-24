/**
 * Homepage selectors
 */

import { createSelector } from 'reselect';
import { initialState } from './reducer';

/*
const selectHome = state => state.get('home', initialState);
*/

const selectRepositories = state => state.get('repositories');

const makeSelectUsername = () =>
  createSelector(selectRepositories, homeState => homeState.get('username'));

const makeSelectCurrentUser = () =>
  createSelector(selectRepositories, homeState => homeState.get('currentUser'));

const makeSelectLoading = () =>
  createSelector(selectRepositories, homeState => homeState.get('loading'));

const makeSelectError = () =>
  createSelector(selectRepositories, homeState => homeState.get('error'));

const makeSelectRepos = () =>
  createSelector(selectRepositories, homeState =>
    homeState.getIn(['userData', 'repositories']),
  );

export {
  selectRepositories,
  makeSelectUsername,
  makeSelectCurrentUser,
  makeSelectLoading,
  makeSelectError,
  makeSelectRepos,
};
