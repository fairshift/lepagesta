/*Datalink - protocol for storing nodes memorized or cached on frontend and synchronizng with backend
(inspired by Meteor.js)

    Frontend stores data in available storage medium on client, be it...
            - IndexedDB (5-50MB, client dependent)
            - WebSQL
            - LocalStorage (2-10MB, client dependent)
    
        If a data node is received with intents for persistent caching and no caching, more permanent caching intention prevails when storing

    Structure of client-side databases:

        a) Calls: subscribtions to synchronization, cached and uncached
            - id: {API_call_params}, {time_synced}, subscription true/false {subscription}
            - response: backend-sorted json array of node IDs
                        - response substructures include {language_id}, {line_id}, {state_pointer_time}

        b) Node list: cached and uncached
            Whenever a node list is fetched from API, it goes into cached or uncached sources, more permanent caching prevailing
            - id: {node_id}, {table_name}, {entry_id}, {language_id}, {line_id}, {state_pointer_time}
            - {time_synced}
            - data: node data array
                    - node's substructure includes requesting calls and subscriptions for callbacks
*/

var schema = {  version: auto,
                autoSchema: false };

var taskRow = { //Function: make more calls in one journey to API; store drafts and posts made offline
    name: 'task',
    unique: false,
    autoIncrement: true,
    indexes:
     [{ keyPath: 'data' }, //
      { keyPath: '' }, //
      /*{ keyPath: 'action' },*/
      { keyPath: 'time_created' },
      { keyPath: 'time_updated' },
      { keyPath: 'time_publish' }, //when to publish? explain local DB context to user
      { keyPath: 'function' }, //which function accepts this data?
};

var calls = {
    name: 'call',
    unique: false,
    autoIncrement: true,
    indexes:
      [{ keyPath: 'response' }, //sorted list of nodes
       { keyPath: 'part_min' }, //increments if response is paginated (recent last)
       { keyPath: 'part_max' }, //increments if response is paginated (recent last)
       { keyPath: 'reactive' }, //not reactive: null; reactive: array of functions that are currently handling this data
       { keyPath: 'time_fetched' },
       { keyPath: 'time_synchronized' },
       //{ keyPath: 'time_synchronize' },
       { keyPath: 'time_remove' }]
};

var nodes = {
    name: 'node',
    unique: false,
    autoIncrement: true,
    indexes: //To fulfill an online feature of full text search through cache, specific indexes would need to be set up.
     [{ keyPath: 'id' }, //{node_id}, when unavailable {table}.{entry_id}
      { keyPath: 'line_id' },
      { keyPath: 'language_id' },
      { keyPath: 'pointer_state_time' }, //TO-DO: frontend requests various states of data in time
      { keyPath: 'data' }, //differing datasets can be returned with various calls
      { keyPath: 'time_fetched' },
      { keyPath: 'time_synchronized' },
      { keyPath: 'time_remove' }] //can be accessed by various functions, which set it's date of 
};

var schema = { autoSchema: false, stores: [calls, nodes] };
var storage;
if(!!window.Worker){ //Web workers available in current browser, set up a parallel process
    storage = new Worker('dataworker.js');
    storage.postMessage({function: 'initLocalDB', schema: schema});
} else {
    storage = initLocalDB({schemas: schemas});
}

/* 
Functions for communications among client and backend 
    - frontend maintains API call parameters it fetched with it's functions
    - communication consists of list of node_ids including language_ids, line_ids, time_state_pointers

    fetch data call subparts (&{Object}/{Function}, {Function}, ...) 
        - each subpart (GET, or POST list of nodes)
        - frontend requests data and submits list of nodes it has cached
        - backend submits 

    post data call (&call=post{Object} or &call={Object}/{Function} & params) (POST form data) 
        - 

Backend functions cache data resulting from API calls, and compiled nodes are stored.
Thus to sync cached data on frontend, it makes sense to request:

    &repeated_params

    &call=route
*/

if(navigator.onLine) {
    storageSync();
}
clearStorage();
storageClear = setInterval(function(){
    if(navigator.onLine) {
        storageSync();
    }
    clearStorage();
}, 360000); //every 6 minutes
function storageSync(){

}

function getData(params, part, reactive, cacheLevel, row){
    part = typeof part !== 'undefined' ? part : 1000; //1000 is arbitrary for current; more recent entries > 1000 > earlier entries
    reactive = typeof reactive !== 'undefined' ? reactive : null;
    cacheLevel = typeof cacheLevel !== 'undefined' ? cacheLevel : null;

    params.concat(attach);
    params.sort();
    var call = params;

    var buffer = localGet({params: params, part: part, cacheLevel: cacheLevel}); //first check if call is cached in local storage
    /*Response consists of several arrays:
                           - response
                           - nodes (in sync, complete node list)
                           - status_code*/

    if($.inArray('incomplete', buffer['status_code'])){ //nodes or parts of nodes are missing
        if(navigator.onLine) {
            var apiResponse = apiGet({params: params, cacheLevel: cacheLevel});
            if(apiResponse[])
                localPut(); //append to nodes that are incomplete
                delete buffer['status_code']['incomplete'];

        } else {
            buffer['status_code'].push('client_offline');
        }

    } else if($.inArray('unsynced', buffer['status_code'])){ //stored data is older than sync_interval
        //add to inmemory storage
        if(navigator.onLine) {
            var apiResponse = apiGet(params, cacheLevel);
            //add to nodes that are out of sync
            //overwrite nodes that are out of sync
            localPut();
        } else {
            buffer['status_code'].push('client_offline');
        }

    } else {

    }

    if(data.length){
        data = $.parseJSON(data);
        var status = data['status'];
    }
}

function localGet(params, cacheLevel){
    var response;

    if(!!window.Worker){
        response = storage.postMessage({function: 'getLocalData', params: params});
    } else {
        response = getLocalData({params: params});
    }
    return response;
}

function localPut(params, part, reactive, cacheLevel){

}

function apiGet(params){
    return $.get(api+"?"+params, function(response){
        return response;
    }
}

function postData(params, transactionRow){

}

function post