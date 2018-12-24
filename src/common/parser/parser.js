
import _ from 'lodash'



const parser = (parserName, args) => {

	var config = {};
	var context = {};
	var objectsMap = {};
	var objectsCache = {};
	var jsonDataObject = {};

	// args: caption, text, ...
	// diff... !!!
	if(typeof args.text === 'undefined' || !args.text){
		return null
	}

	var reserved = ['parserConfig', 'createContext', 'createObjects'];

  try {
    schema = require('./'+parserName)(parserMappings)

    config = schema.createConfig;
   	context.ext = _.map(schema.createContext, function(key, value){

   		if(typeof args[key] !== 'undefined'){

   			var obj[key] = args[key];
   			return obj;
   		}
    });
  	
  	objectsMap = _.map(schema.createObjects, function(collection, Regex){

  		var obj[collection] = Regex.Match(args.text);
  		return obj;
  	});

  	var parseCollection_steps = [
  		'createArrayOfObjects', 
	  			'receiveObjectsFromParser', 'objectsRecreateByDelimiters', 
	  			'receivePropsFromParser', 'addToContext', 
  			'js'
	  		'id__createTemporaryId', '*__temporary',
	  		'*', 
  		'databaseTasks'
  	];

		objectsCache = _.map(schema, function(collection, toRender){ // !!!
			if(reserved.indexOf(collection) == -1){

				var cache = {};
				var currentObject = {};

				_.forEach(parseCollection_steps, (step) => {
				  switch (step) {

				    case (step == 'createArrayOfObjects'):
				      cache[collection].push( createArrayOfObjects(objectsMap, collection, toRender[step]) )
				      return

				    case (step == 'id__createTemporaryId'):
				    	// cache[collection].push( createTemporaryId(objectsMap, toRender[step], config.createTemporaryId) )
				    	return

				    case (step == 'databaseTasks' && toRender.indexOf(step) > -1)
				    	cache[step] = mergeDatabaseTasks(cache[step], collection, toRender[step])
				    	return

				    default:
				    	cache[collection].push( );
				    	return
					}
				})

				return buildCollections;
			}
  	})

  } catch(ex){
  	return null
  }
}


const createArrayOfObjects_steps = [
	'receiveObjectsFromParser', 
	'objectsRecreateByDelimiters', 
	'receivePropsFromParser', 
	'js',
	'*'
];

export const createArrayOfObjects = (objectsMap, collection, value) => {

	_.map(value, (key, arr) => {

		var obj = {};


	  switch (key) {

	    case 'receiveObjectsFromParser':


	    case 'objectsRecreateByDelimiters':
	    	return true

	    case 'receivePropsFromParser':
	    	return true

	    case 'js':
	    	return true

	    default:
	    	return true
		}
	});

	return true
}

export const receiveObjectsFromParser = (objectsMap, keys) => {

	if(keys.isArray()){

		return _.map(keys, (key) => {

			var obj = {}
			obj[key] =  objectsMap[key]
			return obj;
		})

	} else {
		return null
	}
}

export const receivePropsFromParser = (objectsMap, keys, context = null) => {
	return true
}

export const objectsRecreateByDelimiters = (value, delimiters) => {
	return true
}

export const createTemporaryId = (objectsMap, keys, fn) => {
	return true
}

export const passField = (args) => {

}

export const mergeDatabaseTasks = (dbTasksCache, collection, tasks) => {
	var obj = {};
	obj[collection] = tasks;
	_.merge(dbTasksCache, {});
}



export default parser