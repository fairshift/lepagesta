/*Datalink - protocol for storing nodes memorized or cached on frontend and synchronizng with backend
(inspired by Meteor.js)

    Frontend stores data in various mediums:

        1) data nodes persistently cached in available storage medium on client, be it...
            - IndexedDB (5-50MB, client dependent)
            - WebSQL
            - LocalStorage (2-10MB, client dependent)

        2) inmemory cached data nodes stored in memory, in javascript arrays (ex. used only when designated template is active)
    
        If a data node is received with intents for persistent caching and no caching, more permanent caching intention prevails

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
var calls = {
    name: 'call',
    keyPath: 'id', //call's {$.param(params)};
    unique: true,
    autoIncrement: false,
    indexes:
      [{
        keyPath: 'part' //increments if response is paginated (recent last)
      },{
        keyPath: 'reactive' //not reactive: null; reactive: array of functions that are currently handling this data
      },{
        keyPath: 'time_synchronized'
      }/*,{
        keyPath: 'sync_interval' //set in functions that handle this data
      },{
        keyPath: 'response',
      }*/]
    };

var nodes = {
    name: 'node',
    keyPath: 'id', //{node_id}, when unavailable {table}.{entry_id}
    unique: false,
    autoIncrement: false,
    indexes: //To fulfill an online feature of full text search through cache, specific indexes would need to be set up.
      [{
        keyPath: 'language_id',
      },{
        keyPath: 'line_id',
      },{
        keyPath: 'data', //differing datasets can be returned with various calls, TO-DO frontend merging function is required
      }/*,{ keyPath: 'node_id',
      },{
        keyPath: 'table',
      },{
        keyPath: 'entry_id',
      },{
        keyPath: 'pointer_state_time', //TO-DO: frontend requests various states of data in time
      },*/]
    };

var options = { mechanisms: 'memory' };

var inMemory =  schema.push({stores: [{calls, nodes}]});
                inMemory.push({options: options});
var persistent =  schema.push({stores: [{calls, nodes}]});
var schemas = { inMemory: inMemory },
                persistent: persistent };

console.log(schemas);

var storage;
if(!!window.Worker){ //Web workers available in current browser, set up a parallel process
    storage = new Worker('dataworker.js');
    storage.postMessage({function: 'initLocalDB', schemas: schemas});
} else {
    storage = initLocalDB({schemas: schemas});
}

/* 
Functions for communications among client and backend 
    - frontend maintains API call parameters it fetched with it's functions
    - communication consists of list of node_ids including language_ids, line_ids, time_state_pointers

    get (&call=get{Object} or &call={Object}/{Function} & params) (GET, or POST list of nodes) 
        - frontend requests data and submits list of nodes it has cached
        - backend submits missing pieces

    post (&call=post{Object} or &call={Object}/{Function} & params) (POST form data) 
        - 

Backend functions cache data resulting from API calls, and compiled nodes are stored.
Thus to sync cached data on frontend, it makes sense to request:

    &repeated_params

    &call=route
*/

function getData(params, part, reactive, cacheLevel){
    part = typeof part !== 'undefined' ? part : 1000; //1000 is arbitrary for current; more recent entries > 1000 > earlier entries
    reactive = typeof reactive !== 'undefined' ? reactive : null;
    cacheLevel = typeof cacheLevel !== 'undefined' ? cacheLevel : null;

    params.concat(attach);
    params.sort();

    var response = localGet({params: params, part: part, cacheLevel: cacheLevel}); //first check if call is cached in local storage
    /*Response consists of several arrays:
                           - response
                           - nodes (in sync, complete node list)
                           - status_code*/

    if($.inArray('incomplete', response['status_code'])){ //nodes or parts of nodes are missing
        if(navigator.onLine) {
            var apiResponse = apiGet({params: params, cacheLevel: cacheLevel});
            if(cacheLevel == 'persistent'){
                localPut(); //append to nodes that are incomplete
            }

        } else {
            response['status_code'].push('client_offline');
        }

    } else if($.inArray('unsynced', response['status_code'])){ //stored data is older than sync_interval
        //add to inmemory storage
        if(navigator.onLine) {
            var apiResponse = apiGet(params, cacheLevel);
            //add to nodes that are out of sync
            if(cacheLevel == 'persistent'){
                localPut();
            } else {
                localPut(); 
            }
            //overwrite nodes that are out of sync
        } else {
            response['status_code'].push('client_offline');
        }
    }

    if(data.length){
        data = $.parseJSON(data);
        var status = data['status'];
        if()
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
      [{
        keyPath: 'part' //increments if response is paginated (recent last)
      },{
        keyPath: 'reactive' //not reactive: null; reactive: array of functions that are currently handling this data
      },{
        keyPath: 'time_synchronized'
      }/*,{
        keyPath: 'sync_interval' //set in functions that handle this data
      },{
        keyPath: 'response',*/
}

function syncLocal(){

}

function apiGet(params){
    $.get(api+"?"+params, function(response){

    }
}

function apiPost(params){

}