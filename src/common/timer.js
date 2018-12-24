/*

!!! Work in progress !!!

*/

import _ from 'lodash'

// Define functions to be called at an interval here
const loopedFunctions = {
	online: {
		interval: 60000
		fn: (params) => {
			console.log(params.hi, 60000);
		},
		params: {
			hi: "Hello timer"
		}
	}
}

const runIntervalFns = (list, defaultInterval = 5000, functions = loopedFunctions) => {

	// First sort functions by interval

	var runByInterval = map(functions, (name, fn) => {
		if( typeof list[name] !== 'undefined' ){
			var arr = [];

			if(typeof fn.interval !== 'undefined'){
				arr.interval = fn.interval
			} else {
				arr.interval = defaultInterval
			}

			run.fn[key](run.params);
		}
	})

}

export { runIntervalFns }


/*


Some know how here...


*/


// Function which runs when redux store changes
// store.subscribe(listener)


/* How Javascript timers work
 - johnresig.com/blog/how-javascript-timers-work/

	 For creating a full component...
 - stackoverflow.com/questions/34577012/creating-a-stopwatch-with-redux

   React version > 16.x
 - https://reactjs.org/blog/2018/03/27/update-on-async-rendering.html#adding-event-listeners-or-subscriptions
 - https://reactjs.org/docs/react-component.html#forceupdate
*/