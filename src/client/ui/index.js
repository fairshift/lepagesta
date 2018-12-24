
// Background image: stockarch.com/images/abstract/patterns/red-twister-mesh-2139

import { screenResized } from '../../common/containers/App/actions'

export const getScreenWidth = (store) => {

	window.addEventListener('resize', (store) => {
		console.log(window.innerWidth);
	  store.dispatch(screenResized(window.innerWidth));
	});
}