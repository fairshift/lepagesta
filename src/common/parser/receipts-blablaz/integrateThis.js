
  createObjects: {

    // First line: 
    // main service provider's organization name (%1),
    // organization seat address (%2)
    'main_service_provider': {
      regex: new RegExp('/^((.*(?=d\.d|d\.o\.o|s\.p|d\.n\.o|k\.d|z\.o\.o).*), (.*\,.*))/m'),
      js: (line, line_number) => {
        return (line_number == 1) ? line : false;
      }
    },

    // Places (organized by main service provider): 
    // temporary ID (%1),
    // name with address (%2)
    'places': { regex: new RegExp('/^(((?:¹|²|³|⁴|⁵|⁶|⁷|⁸|⁹)) (.*\w.(?:\s*\[.*\]|))) *$/gm') },

    // Vendors, linked to main service provider: 
    // organization name (%1),
    // organization seat address (%2), 
    // list of places (%3)
    'vendors_places': {
      regex: new RegExp('/^((?:⁰ |)(.*(?=d\.d|d\.o\.o|s\.p|d\.n\.o|k\.d|z\.o\.o).*), (.*\,.*): (.*\s+\[.*\]|.*)) *$/gm'),
    },

    // Products and services as full entry of three possible formats (%1)
    'resources': {
      js: () => {

      }
    },

    // Receipts of transactions (%1)
    'receipts': new RegExp('^— \(.*')
  },

  parserConfig: {

    createTemporaryId: createTemporaryId_uidsafe,
    delimiters: ['|', ';', '⋅', '·', '·', '・'],
    trim: 'auto' // otherwise use .trim() on output in 'js' objects, and __trim on keys left to parser
  },

/*
  Start of data structure mappings
*/

  service_providers: {
    createArrayOfObjects: ['main_service_provider', 'vendors_places'],

    id__createTemporaryId: ['main_service_provider.1', 'vendors_places.1'],
    organization: ['main_service_provider.1', 'vendors_places.1'],
    city: { 
      receiveProps: ['main_service_provider.2', 'vendors_places.2'],
      passThrough__returnBeforeNull: [
        new RegExp('/^.*, (.*) *$/img'),    // take the string after last comma
        new RegExp('/^.*[0-9]* (.*) *$/m')  // strip postal code
      ]
    },
    address: ['main_service_provider.2', 'vendors_places.2'],

    databaseTasks: {
      transcode: ['address'],
      findDuplicates: {
        matchBy: ['organization', 'address'],
        matchTolerancy: 0.05,
        onMatch_autoSelect: ['name'],
        onMatch_resolveManually: ['address'],
        onMatch_overwritePropsOnDiff: ['name', 'city', 'address'],
        confirmActions: ['autoSelect', 'overwritePropsOnDiff']
      }
    }
  },


  places: {
    createArrayOfObjects: {
      receiveObjects: ['places', 'vendors_places'],
      objectsCreateByDelimiters: [ {'vendors_places.3': ['|']} ],
      context: ['main_service_provider.1', 'vendors_places.1'],

      js: (object, context, config) => {

////////////////////////////////////////

  var tId = null;

  if( typeof object['vendors_places.3'] !== 'undefined' ){
    var temp = new RegExp('/^(?:¹|²|³|⁴|⁵|⁶|⁷|⁸|⁹) (.*) *$/gm').Match( object['vendors_places.3'] );
    object['vendors_places.3.1'] = temp[1];
    object['vendors_places.3.2'] = temp[2];

    tId = createTemporaryId_uidsafe(temp[1]);
  }

  if( typeof object['places.1'] !== 'undefined' ){
    tId = createTemporaryId_uidsafe(object['places.1'])
  }

  return {
    id__temporary: getFirstWord(object, ['places.1', 'vendors_places.3.1'], config),
    company_id__temporary: tId,
    name: getBeforeFirstComma(object, ['places.2', 'vendors_places.3.2'], config),
    city: 
    passField({ 
      object: object,
      keys: ['places.2', 'vendors_places.3.'],
      passThrough__returnBeforeNull: [
        new RegExp('/^.*, (.*) *$/img'),    // take the string after last comma
        new RegExp('/^.*[0-9]* (.*) *$/m')  // strip postal code
      ],
      config: config
    }),
    address: passField({ 
      object: object
      keys: ['places.2', 'vendors_places.3.'],
      config: config
    })
  }

////////////////////////////////////////

      },
    },

    databaseTasks: { 
      findDuplicates: {
        matchBy: ['name', 'address'],
        matchTolerancy: 0.05,
        onMatch_autoSelect: ['name'],
        onMatch_resolveManually: ['address'],
        onMatch_overwritePropsOnDiff: ['name', 'city', 'address'],
        confirmActions: ['autoSelect', 'overwritePropsOnDiff']
      }
    }
  },


  resources_bundles: {
    createArrayOfObjects: { 
      receiveObjectsFromParser: ['resources'],
      
      js: (object, context, config) => {

////////////////////////////////////////

  var objects = {};

  // match bundle, get quantity/quantities (%1), get items and expenses
  var bundle = new RegExp('/^— \[(.*)\] (.*(?:\s+\+.*)+)(?:\s\:)(.*(?:\s\:.*)+).*/gm').Match(object);
  if( bundle ){
    var items = bundle[2].replace('/(\r\n|\n|\r)/gm').split('+');
    var items_quantities = bundle[1].split(',');
    var items_expenses = bundle[3].replace('/(\r\n|\n|\r)/gm').split(':');
    
    if( (items_quantities.length == items_additions.length) && 
        (items_additions.length == items_expenses.length) ){
      for(i = 1; i <= items_quantities.length; i++){

        return {
          objects: null
        }
      }
    }
  }

  // match item in one line with costs from one or multiple stores listed
  var item = new RegExp('/^— \[([0-9]+(?:\,[ ])*)\] (.*)(?:\: |\:\s)(.*(?:(?:\s+¹|\s+²|\s+³|\s+⁴|\s+⁵|\s+⁶|\s+⁷|\s+⁸|\s+⁹)+.*)*)/gm').Match(object);
  if(1 == 1){

  }

  // match one row entry
  if(1 == 1){

  }

  // get addition of services or products

    '+' //delimiting operation, creating more objects
    //output
    //item: replaced [%1] with "."
    //item_expanded: removed "[" and "]"

  // get first line: quantity/quantities (%1) of item/+s (%2)
  — \[([0-9]*)\] (.*.).* *$
    // get addition lines
    ^\s\+(.*)$

    // summary of expenses in a bulk
    ^\s\:((?:¹|²|³|⁴|⁵|⁶|⁷|⁸|⁹).*) \(([0-9]*.*)\)




  return {
    id__temporary: getFirstWord(object, ['places.1', 'vendors_places.3.1'], config),
    company_id__temporary: tId,
    name: getBeforeFirstComma(object, ['places.2', 'vendors_places.3.2'], config),
    city: 
    passField({ 
      object: object,
      keys: ['places.2', 'vendors_places.3.'],
      passThrough__returnBeforeNull: [
        new RegExp('/^.*, (.*) *$/img'),    // take the string after last comma
        new RegExp('/^.*[0-9]* (.*) *$/m')  // strip postal code
      ],
      config: config
    }),
    address: passField({ 
      object: object
      keys: ['places.2', 'vendors_places.3.'],
      config: config
    }),

    parserPreparsed: ['resources', items]
  }

////////////////////////////////////////

      },
    },

    id__createTemporaryId: ['main_service_provider.1', 'vendors_places.1'],
    place_id__createTemporaryId: []
    databaseTasks: { 
      findDuplicates: {
        matchBy: ['name', 'city', 'address'],
        matchAutoSelect: ['name'],
        matchResolveManually: ['address'],
        matchTolerancy: 0.05,
        overwriteOnDiff: ['name', 'city', 'address'],
        confirmActions: ['matchAutoSelect', 'overwriteOnDiff']
      }
    }

  },


  resources {
    createArrayOfObjects: { 
      receiveObjectsFromParser: ['resources'],
      
      js: (object, context, config) => {

////////////////////////////////////////

    ^— \[([0-9]+(?:\,[ ])*)\] (.*)(?:\: |\:\s)(.*(?:(?:\s+¹|\s+²|\s+³|\s+⁴|\s+⁵|\s+⁶|\s+⁷|\s+⁸|\s+⁹)+.*)*)

////////////////////////////////////////

      }
  },

  receipts: {
    createArrayOfObjects: ['resources', 'vendors_places'],
    id__createTemporaryId: ['main_service_provider.1', 'vendors_places.1'],
    organization: ['main_service_provider.1', 'vendors_places.1'],
    city: { 
      receivePropFromParser: ['main_service_provider.2', 'vendors_places.2'],
      passThrough__returnBeforeNull: [
        new RegExp('/^.*, (.*) *$/img'),    // take the string after last comma
        new RegExp('/^.*[0-9]* (.*) *$/m')  // strip postal code
      ]
    },
    address: ['main_service_provider.2', 'vendors_places.2'],
    databaseTasks: [{
      checkForDuplicates: { levenshtein: 2 }
    }]








,


  resources_bundles: {
    createArrayOfObjects: { 
      receiveObjectsFromParser: ['resources'],
      
      js: (object, context, config) => {

////////////////////////////////////////

  var objects = {};

  // match bundle, get quantity/quantities (%1), get items and expenses
  var bundle = new RegExp('/^— \[(.*)\] (.*(?:\s+\+.*)+)(?:\s\:)(.*(?:\s\:.*)+).*/gm').Match(object);
  if( bundle ){
    var items = bundle[2].replace('/(\r\n|\n|\r)/gm').split('+');
    var items_quantities = bundle[1].split(',');
    var items_expenses = bundle[3].replace('/(\r\n|\n|\r)/gm').split(':');
    
    if( (items_quantities.length == items_additions.length) && 
        (items_additions.length == items_expenses.length) ){
      for(i = 1; i <= items_quantities.length; i++){

        return {
          objects: null
        }
      }
    }
  }

  // match item in one line with costs from one or multiple stores listed
  var item = new RegExp('/^— \[([0-9]+(?:\,[ ])*)\] (.*)(?:\: |\:\s)(.*(?:(?:\s+¹|\s+²|\s+³|\s+⁴|\s+⁵|\s+⁶|\s+⁷|\s+⁸|\s+⁹)+.*)*)/gm').Match(object);
  if(1 == 1){

  }

  // match one row entry
  if(1 == 1){

  }

  // get addition of services or products

    '+' //delimiting operation, creating more objects
    //output
    //item: replaced [%1] with "."
    //item_expanded: removed "[" and "]"

  // get first line: quantity/quantities (%1) of item/+s (%2)
  — \[([0-9]*)\] (.*.).* *$
    // get addition lines
    ^\s\+(.*)$

    // summary of expenses in a bulk
    ^\s\:((?:¹|²|³|⁴|⁵|⁶|⁷|⁸|⁹).*) \(([0-9]*.*)\)




  return {
    id__temporary: getFirstWord(object, ['places.1', 'vendors_places.3.1'], config),
    company_id__temporary: tId,
    name: getBeforeFirstComma(object, ['places.2', 'vendors_places.3.2'], config),
    city: 
    passField({ 
      object: object,
      keys: ['places.2', 'vendors_places.3.'],
      passThroughRegex__returnBeforeNull: [
        new RegExp('/^.*, (.*) *$/img'),    // take the string after last comma
        new RegExp('/^.*[0-9]* (.*) *$/m')  // strip postal code
      ],
      config: config
    }),
    address: passField({ 
      object: object
      keys: ['places.2', 'vendors_places.3.'],
      config: config
    }),

    parserPreparsed: ['resources', items]
  }

////////////////////////////////////////

      },
    },

    id__createTemporaryId: ['main_service_provider.1', 'vendors_places.1'],
    place_id__createTemporaryId: []
    databaseTasks: { 
      findDuplicates: {
        matchBy: ['name', 'city', 'address'],
        matchAutoSelect: ['name'],
        matchResolveManually: ['address'],
        matchTolerancy: 0.05,
        overwriteOnDiff: ['name', 'city', 'address'],
        confirmActions: ['matchAutoSelect', 'overwriteOnDiff']
      }
    }

  },


  resources {
    createArrayOfObjects: { 
      receiveObjectsFromParser: ['resources'],
      
      js: (object, context, config) => {

////////////////////////////////////////

    ^— \[([0-9]+(?:\,[ ])*)\] (.*)(?:\: |\:\s)(.*(?:(?:\s+¹|\s+²|\s+³|\s+⁴|\s+⁵|\s+⁶|\s+⁷|\s+⁸|\s+⁹)+.*)*)

////////////////////////////////////////

      }
  },

  receipts: {
    createArrayOfObjects: ['resources', 'vendors_places'],
    id__createTemporaryId: ['main_service_provider.1', 'vendors_places.1'],
    organization: ['main_service_provider.1', 'vendors_places.1'],
    city: { 
      receivePropFromParser: ['main_service_provider.2', 'vendors_places.2'],
      passThroughRegex__returnBeforeNull: [
        new RegExp('/^.*, (.*) *$/img'),    // take the string after last comma
        new RegExp('/^.*[0-9]* (.*) *$/m')  // strip postal code
      ]
    },
    address: ['main_service_provider.2', 'vendors_places.2'],
    databaseTasks: [{
      checkForDuplicates: { levenshtein: 2 }
    }]
  },
}


