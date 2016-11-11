/*Datalink - protocol for storing nodes memorized or cached on frontend and synchronizng with backend
(inspired by Meteor.js)

    Frontend stores data in available storage medium on client, be it...
            - IndexedDB (5-50MB, client dependent)
            - WebSQL
            - LocalStorage (2-10MB, client dependent)
    
    Structure of client-side databases - collections of data :

        - Calls: subscribtions to synchronization
            - id: {API_call_params}, {time_synced}, subscription true/false {subscription}
            - response: backend-sorted json array of node IDs
                        - response substructures include {language_id}, {line_id}, {state_pointer_time}

        - Node list
            Whenever a node list is fetched from API, it goes into cached or uncached sources, more permanent caching prevailing
            - id: {node_id}, {table_name}, {entry_id}, {language_id}, {line_id}, {state_pointer_time}
            - {time_synced}
            - data: node data array
                    - node's substructure includes requesting calls and subscriptions for callbacks

        - Outbound: API call stack, drafts stored locally, posts made offline in line for publishing
*/

/
var schema = {  version: auto,
                autoSchema: false };

var calls = {
    name: 'call',
    keyPath: 'call_id', //{object/function} or {function} _ {$.param(asc sorted list of parameters)}
                        //parameters + part
    unique: true,
    autoIncrement: true,
    indexes:
    //Pagination
      [{ keyPath: 'part' },     //part number: more recent > 1000000 (default) > older
       { keyPath: 'part_min' }, //first entry identifier (backend provided)
       { keyPath: 'part_max' }, //last entry identifier (backend provided)
       { keyPath: 'length' },   //nodes count
    //Call data
       { keyPath: 'call' },     //{object/function} or {function} _ {$.param(asc sorted list of parameters)}
       { keyPath: 'response' }, //sorted list of nodes
    //Sync and clear data
       { keyPath: 'time_fetched' }, //time of using data
       { keyPath: 'time_clear' },   //
       { keyPath: 'time_synchronized' }]
};

var nodes = {
    name: 'node',
    unique: false,
    keyPath: 'node_id', //{node_id}.{line_id}.{language_id}.{pointer_state_time}
                        //when rhizomatic structure isn't used, it's {table}.{entry_id}
    autoIncrement: true,
    indexes: //multi-field offline search: compound index needed
             //full text search
    //Clear data
     [{ keyPath: 'time_clear' }] //biggest time_clear is stored here
                                 //clear node from storage if call.time_clear == node.time_clear/*{ keyPath: 'line_id' },
      /*{ keyPath: 'language_id' },
      { keyPath: 'pointer_state_time' }, //TO-DO: frontend requests various states of data in time
      { keyPath: 'data' }, //differing datasets can be returned with various calls*/
};

//Function 1: API call stack - avoid multiple calls to API by adding them to outbound DB stack
//Function 2: store drafts locally
//Function 3: offline posts
var outbound = { 
    name: 'outbound',
    unique: false,
    autoIncrement: true,
    keyPath: 'call_id', //{object/function} or {function} & {$.param(asc sorted list of parameters)}
    indexes:
    //1, 2, 3
     [{ keyPath: 'time_updated' },
      /*{ keyPath: 'time_created' },
      { keyPath: 'call' },   //{object/function} or {function}
      { keyPath: 'params' }, //array of params
      { keyPath: 'data' },
    //Scheduled 2 - Drafts and 3 - Offline posts have time to publish set sometimes
      { keyPath: 'time_publish' }] //(!!! explain local DB context to user)*/

var schema = { autoSchema: false, stores: [calls, inbound, nodes] };
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

storageSyncInterval = setInterval(function(){
    if(navigator.onLine) {
        syncStorage();
    }
}, storageSyncInterval); //default: 6 minutes



function callDB(args){  //get data from local storage, continue to API
                        //should handle multiple API calls in one

    if(typeof args.call !== 'undefined'){

    //API call params
        args.params = typeof args.params !== 'undefined' ? args.params : []; //pagination. default: 10000 is arbitrary for current; more recent data > 10000 > earlier entries
        //What if ex. language_id is different from call to call? Attachment needs to be call-specific
        args.params = $.merge(args.params, attachCallParamsArray);
        if(typeof args.part_min !== 'undefined'){
            args.params['part_min'] = args.part_min;
        }
        if(typeof args.part_min !== 'undefined'){
            args.params['part_max'] = args.part_max;
        }
        args.params.sort();

    //Local DB select query args - initiative vars
        //Part (ex. pagination): if part undefined {where: call_id, orderBy: part, asc:, limit: 1}
        var part = null;
        if(typeof args.part == 'undefined'){
            args.field = 'call';
        } else {
            args.field = 'call_id';
            call_id = attachToCall("part="+args.part, args.params);
        }
        var call_id = call + $.param(params);
        args.store = 'call';
        args.orderBy = null; //
        args.desc = null;
        args.limit = 1;
        args.where = new ydn.db.KeyRange.only(call_id);

        //Query var where = new ydn.db.KeyRange.only(call_id);

        args.buffer = localGet(args); //first check if call is cached in local storage
        /*Response consists of several arrays:
                               - response
                               - nodes (in sync, complete node list)
                               - status_code*/

        if($.inArray('incomplete', args.buffer['status_code']) //nodes or parts of nodes are missing
        || $.inArray('unsynced', argsbuffer['status_code'])){
            if(navigator.onLine && (typeof args.toApiStack === 'undefined' || args.toApiStack == false)) {

                if(args.toApiStack == true){
                    localPut();
                } else {
                    var apiResponse = callApi(args);
                    if(apiResponse[])
                        localPut(); //append to nodes that are incomplete
                        delete buffer['status_code']['incomplete'];   
                }


            } else {
                localPut();
                buffer['status_code'].push('client_offline');
            }

        } else if($.inArray('unsynced', buffer['status_code'])){ //stored data is older than sync_interval
            //add to inmemory storage
            if(navigator.onLine) {
                if(args.toApiStack == true){

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
}

function callApi(args){
    return $.post(api+"?"+params, data function(response){
        return response;
    }
}

var apiCallStack = [];
function toApiStack(args){
    args.push(array('toApiStack' => true));
    return callDB(args);
}

function releaseApiStack(args){

}

function clearApiStack(){

}



function localGet(args){

    var response = [];

    if(typeof args.collection !== 'undefined' && 
        ((typeof args.field !== 'undefined' && typeof args.where !== 'undefined') 
            || typeof args.field === 'undefined')){

        if(!!window.Worker){
            response = storage.postMessage({function: 'getLocalData', args: args});
        } else {
            response = getLocalData({params: params});
        }
    }
    return response;
}

function localPut(args){

}

function syncBindings(){
    //if apiCallStack.length = 0, calls API right away
    $.each(onChangeBindings, function(call, args){
        if(apiCallStack.length = 0){
            callDB({call: call, });
        } else {

        }
    });
}
function syncStorage(){
    /*Disabled for now*/
        //syncing outdated calls / nodes goes to API stack
        //happens in parts, every 6 minutes by default

    clearStorage(dataClearWindow);
}

function clearStorage(){

    var date = new Date();
    var time = date.getTime() + synced_time_difference;

    var args = {};
    args.collection = 'call';
    args.field = 'time_clear';
    args.where = new ydn.db.KeyRange.upperBound(time, true);

    if(!!window.Worker){
        response = storage.postMessage({function: 'removeLocalData', args: args});
    } else {
        response = removeLocalData({args: args});
    }

    args.collection = 'node';

    if(!!window.Worker){
        response = $.merge(response, storage.postMessage({function: 'removeLocalData', args: args}));
    } else {
        response = $.merge(response, removeLocalData({args: args}));
    }

    return response;
}

/*Persistent call attachment - additional params*/
var attachCallParams = [];
var attachCallParamsArray = [];
function attachToCalls(pair){ //persistent attachment

    var keyValuePair = pair.split('=');
    attachCallParamsArray[keyValuePair[0]] = encodeURIComponent(keyValuePair[1]);
    attachCallParamsArray.sort();
    var attachment = renderAttachment(attachCallParamsArray);

    return attachment;
}
function attachToCall(pair, tempAttachParamsArray){ //one-time attachment

    var keyValuePair = pair.split('=');
    tempAttachParamsArray[keyValuePair[0]] = encodeURIComponent(keyValuePair[1]);
    tempAttachParamsArray.sort();
    var attachment = renderAttachment(tempAttachParamsArray);

    return attachment;
}
function detachFromCalls(key){ //detach

    delete attachArray(key);
    var attachment attach = renderAttachment(attachArray);
    return attachment;
}
function renderAttachment(tempAttachParamsArray){

    var attachment = $.param(tempAttachParamsArray);
    return attachment;
}