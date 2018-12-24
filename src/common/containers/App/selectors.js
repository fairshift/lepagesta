/**
 * The global state selectors
 */

import { createSelector } from 'reselect';

const selectClient = state => state.get('client');//ž

const makeSelectScreenWidth = () => 
	createSelector(selectClient, state => state.getIn(['ui', 'screenWidth']));

/*() =>
  createSelector(selectClient, state => {
  	if( typeof state !== 'undefined' ){

  		return state.ui.screenWidth
  	} } )*/

const selectRoute = state => state.get('route');

const makeSelectLocation = () =>
  createSelector(selectRoute, routeState => routeState.get('location').toJS());

export {
  selectClient,
  makeSelectScreenWidth,
  makeSelectLocation,
};
