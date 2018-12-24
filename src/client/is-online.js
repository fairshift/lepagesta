//

import { check, watch } from 'is-offline';

export const onlineStatusChange = bool => {
	console.log('Am I offline?', bool);
	store.dispatch({ 
	  type: 'boilerplate/App/IS_ONLINE', 
	  online: !bool
	})
}

// Check if currently offline
//check().then(foobar);

// Setup a "watcher" to respond to all online/offline changes
export default watch(onlineStatusChange);