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

var schema = {  version: auto,
                autoSchema: false };

var calls = {
    name: 'call',
    keyPath: 'call_id', //{object/function} or {function} _ {$.param(asc sorted list of parameters)}
    unique: true,
    autoIncrement: true,
    indexes:
    Call data
       [{ keyPath: 'call_part' } //{call}&part={n}
       /*{ keyPath: 'response' }, //sorted list of nodes
    //Pagination
       { keyPath: 'part' },     //part number: more recent > 1000000 (default) > older
       { keyPath: 'part_min_id' }, //first entry identifier (backend provided)
       { keyPath: 'part_max_id' }, //last entry identifier (backend provided)
       { keyPath: 'length' },   //nodes count
    //Sync and clear data
       { keyPath: 'time_fetched' }, //time of using data
       { keyPath: 'time_clear' },   //
       { keyPath: 'time_synchronized' }*/]
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
     [{ keyPath: 'time_clear' }, //biggest time_clear is stored here
                                 //clear node from storage if call.time_clear == node.time_clear/*{ keyPath: 'line_id' },
    /*{ keyPath: 'line_id' },
      { keyPath: 'pointer_state_time' }, //TO-DO: frontend requests various states of data in time
      { keyPath: 'data' }, //differing datasets can be returned with various calls
      { keyPath: 'language_id' }*/]
};

//Function 1: store drafts locally (when offline or by intention)
//Function 2: store offline posts
var outbound = { 
    name: 'outbound',
    unique: false,
    autoIncrement: true,
    keyPath: 'call_id', //{object/function} or {function} & {$.param(asc sorted list of parameters)}
    indexes:
     [{ keyPath: 'time_updated' },
    /*{ keyPath: 'call' },   //{object/function} or {function}
      { keyPath: 'params' }
      { keyPath: 'data' },
      { keyPath: 'time_created' },
      { keyPath: 'time_publish' }, //Scheduled for posting (!!! explain local DB context to user)*/] 
};

var schema = { autoSchema: false, stores: [calls, inbound, nodes] };
var db = {}; //database object
var storage; //local storage object;
db.initateLocalDB = initiate_YDN_DB();

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
db.commit = commitCalls();   //process call stack array or a single call
                            //both get and post methods
                           //wraps getCall and getNode functions
                          //takes regard of fromLocalStorage and toLocalStorage settings
db.call = oneCall();     //checks with local DB first, only then from API
db.node = oneNode();    //checks with local DB first, only then adds to API call stack

//db.from.localDraft = getLocalDrafts();
//db.to.localDraft =   storeLocalDraft();
//db.publishDraft =    publishLocalDraf();
db.to.ApiCallStack =  toApiCallStack();   //put a call to apiStack and join calls (get and post)
db.releaseCallStack = releaseCallStack(); //release calls put to apiStack;

//Lower-level functions, included in the above
db.from.localDB = localGet();
db.to.localDB = localPut();

//Direct API calls, included in the above - wrap $.post methods, accept {call: {{call}}, data: {{data}}}
db.from.Api = Api();
db.to.Api = Api();

/*Calls & Nodes functions*/
//https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/this
//http://stackoverflow.com/questions/111102/how-do-javascript-closures-work?rq=1
    function commitCalls(args, callback){  //get/put data from/to local storage, continue to API 

        var calls = [];
        callback = typeof callback !== 'undefined' ? callback : null;

        if(typeof args.call !== 'undefined'){ //multiple calls
            calls = args.call.map(function(call){
                var output = [];
                if(output['call_part']){
                    output[output['call_part']] = oneCall(call, callback);
                } else {
                    output[output['call_id']] = oneCall(call, callback);
                }
                return output;
            });
        } else {                                  //one call
            calls = oneCall(args, callback);
        }

        return calls;
    }

    function oneCall(args, callback){

        var response = [];
        if(typeof args.call === 'undefined'){
            return response['status_code'].push(array('call_empty'));
        }
        var call = args.call;
        var response['call_id'] = call_id = call + renderAttachment(args.params);

        args.fromLocalStorage = fromLocalStorage == false ? false : (typeof args.fromLocalStorage !== 'undefined' ? args.fromLocalStorage : true);
        args.toLocalStorage = toLocalStorage == false ? false : (typeof args.toLocalStorage !== 'undefined' ? args.toLocalStorage : true);
        /*What about fromApi and toApi?*/
        args.apiapiStackCode = typeof args.apiapiStackCode !== 'undefined' ? args.apiapiStackCode : null;

    //Local DB select query args - initiative vars
        if(args.fromLocalStorage == true || args.toLocalStorage == true){
            args.collection = 'call';
            //Part (for pagination)
            if(typeof args.part === 'undefined'){ //(if part undefined {where: call_id, desc: bool, limit: 1}
                args.field = 'call_id';
                args.where = new ydn.db.KeyRange.only(call_id);
            } else {
                args.field = 'call_part'; //default: 1000000 for first call; more recent data > 10000 > earlier entries
                args.call_part = call + renderAttachment("part="+args.part, args.params);
                args.where = new ydn.db.KeyRange.only(args.call_part);
            }
            //Sort by secondary key - YDN-DB sorts by secondary index (args.orderBy = null)
            args.desc = typeof args.desc !== 'undefined' ? args.desc : true;
            args.limit = 1;
        }

    //API call params
        args.params = $.merge(args.params, attachParamsToCallsArray); //What if eg. language_id is different from call to call?
        args.params['languages'] = typeof args.languages !== 'undefined' ? args.languages : user_languages;
        args.params['datasets'] = typeof args.datasets !== 'undefined' ? args.datasets : '*';
        args.params['desc'] = typeof args.desc !== 'undefined' ? args.desc : true;
        if(typeof args.orderBy !== 'undefined'){
            args.params['orderBy'] = args.orderBy;
        }
        if(typeof args.part_min !== 'undefined'){
            args.params['part_min_id'] = args.part_min_id;
        }
        if(typeof args.part_min !== 'undefined'){
            args.params['part_max_id'] = args.part_max_id;
        }
        args.params.sort();
    }

    //Local DB query
        if(args.fromLocalStorage == true){
            var output = db.from.localDB(args); //first check if call is cached in local storage
                response['status_code'] = status_code;
            /*Returned data consists of several arrays:
               - data -> Calls collection
               - count
               - status_code*/

            if($.inArray('localdb_get_success', output['status_code'])){

                $.each(output['data'], function(part){
                    response = $.merge(response, $.parseJSON(part));
                });
            }
        }

    //Call API query
        if((!args.fromLocalStorage 
            || $.inArray('localdb_error', output['status_code'])
            || output['count'] == 0)
            && navigator.onLine && ){ { //count = 0 or localdb_error

            //API query
            call = db.from.Api(args.call, {params: args.params, data: args.data});

            //Update and callback
            updateCall(call, args, callback);
        } else {
            if(apiStackCode){
                toApiCallStack(apiStackCode, args.call, {params: args.params, data: args.data}, callback});
                call['status_code'].push(array('api_instack'));
            } else {
                call['status_code'].push(array('call_noconnection'));
            }
        }

        response['call_id'] = call_id;
        response['call_part'] = typeof args.call_part !== 'undefined' ? args.call_part : null;
        response['status_code'] = $.merge(output['status_code'], call['status_code']);
        response.args = response

        return response;
    }

    function oneNode(args, callback){
        if(typeof args !== 'undefined'){

            args.fromLocalStorage = fromLocalStorage == false ? false : (typeof args.fromLocalStorage !== 'undefined' ? args.fromLocalStorage : true);
            args.toLocalStorage = toLocalStorage == false ? false : (typeof args.toLocalStorage !== 'undefined' ? args.toLocalStorage : true);
            /*What about fromApi and toApi?*/
            args.apiapiStackCode = typeof args.apiapiStackCode !== 'undefined' ? args.apiapiStackCode : null;

            if(typeof args. !== 'undefined' && args.){



            if(typeof args.response !== 'undefined'){

                node_args.datasets = args.datasets;     

            } else {

                node_args.table_name = args.table_name;
                node_args.entry_id = args.entry_id;
                //Rhizomatic
                if(typeof data['node_id'] !== 'undefined'){
                    node_args.node_id = row.value['node_id'];
                    node_args.line_id = row.value['line_id'];
                    node_args.languages = args.languages;
                }
                toApiCallStack();
            }

            if(typeof callback !== 'undefined'){
                //!!!
            }

            return output['response'];
        }
    }

/*Update local database on receive functions*/
    function updateCall(call, args, callback){
        //time_clear

    }

    function updateNode(args){
        //time_clear
    }

/*API touchpoint functions*/
    var apiCallStack = [];

    function toApiCallStack(apiStackCode, call, callback){
        if(typeof call_id === 'undefined' || typeof calls === 'undefined'){
            callback = typeof callback !== 'undefined' ? callback : [];
            apiCallStack[apiStackCode].push(array('call' => call, 'callback' => callback));
        }
        return call_id;
    }

    function release(apiStackCode){ //Compiles a request and sends it to API
        var result = [];
        if(result = ){
            
        }
    }

    function api(args){
        args.call = typeof args.call !== 'undefined' ? args.call : null;
        args.data = typeof args.data !== 'undefined' ? args.data : null;

        if(args.call && args.data){
            return $.post(api+"?"+args.call, args.data){function(response){
                return response;
            }
        }
    }

/*Local draft functions*/
    function getLocalDrafts(args){

    }

    function storeLocalDraft(args){ //Function handles inserting offline drafts

    }

/*Sync bindings - reactive data nodes currently in use and functions that refresh them onSync*/
/*7% of world's population pays 50% of social taxes*/
    function onSync(data_id, args){
        $.each(onChangeBindings, function(call, args){
            db.to.apiStack()
        });
    }
    function syncBindings(args){
        //if apiCallStack.length = 0, calls API right away
        $.each(onChangeBindings, function(call, args){
            db.to.apiStack();
        });
    }

/*Sync storage*/
    storageSyncInterval = setInterval(function(){
        if(navigator.onLine) {
            syncStorage();
        }
    }, storageSyncInterval); //default: 6 minutes

    function syncStorage(){
        /*Scheduled for later development
            Happens in parts, every 6 minutes by default, 10 at once, of each: scheduled drafts, posts, calls and data nodes 
            db.to.ApiStack...
            - outdated calls / nodes goes to API stack
            - send offline posts
            - happens in parts, every 6 minutes by default*/

        clearStorage();
    }

    function clearStorage(){

        var date = new Date();
        var time = date.getTime() + synced_time_difference;

        var args = {};
        args.collection = 'call';
        args.field = 'time_clear';
        args.where = new ydn.db.KeyRange.upperBound(time, true);

        response = localRemove({args: args});

        args.collection = 'node';

        response = $.merge(response, localRemove({args: args}));

        return response;
    }

/*Local DB operations*/
    function initiate_YDN_DB(){
        if(!!window.Worker){ //Web workers available in current browser, set up a parallel process
            storage = new Worker('dataworker.js');
            storage.postMessage({function: 'initLocalDB', schema: schema});
        } else {
            storage = initLocalDB({schemas: schemas});
        }
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

        var response = [];

        if(typeof args.collection !== 'undefined' && 
           typeof args.data !== 'undefined'){        

            if(!!window.Worker){
                response = storage.postMessage({function: 'putLocalData', args: args});
            } else {
                response = putLocalData({args: args});
            }
        }
        return response;
    }

    function localRemove(args){

        if(typeof args.collection !== 'undefined' 
            && typeof args.field !== 'undefined' 
            && typeof args.where !== 'undefined'){

            if(!!window.Worker){
                response = storage.postMessage({function: 'removeLocalData', args: args});
            } else {
                response = removeLocalData({args: args});
            }
        }
    }

/*Persistent call & url attachments - additional params*/
    var attachParamsCalls;
    var attachParamsCallsArray = [];
    var attachParamsUrls;
    var attachParamsUrlsArray = [];
    /*Call & url attachment functions*/
    function attachParamsToCalls(pair){ //persistent attachment

        var keyValuePair = pair.split('=');
        attachParamsCallArray[keyValuePair[0]] = encodeURIComponent(keyValuePair[1]);
        attachParamsCallArray.sort();
        attachParamsUrl = $.param(attachParamsCallArray);

        return attachParamsUrl;
    }
    function attachParamsToUrls(pair){ //persistent attachment

        var keyValuePair = pair.split('=');
        attachParamsUrlsArray[keyValuePair[0]] = encodeURIComponent(keyValuePair[1]);
        attachParamsUrlsArray.sort();
        attachParamsUrls = $.param(attachParamsUrlsArray);

        return attachParamsUrl;
    }
    function renderAttachment(pair, tempAttachParamsArray){ //one-time attachment, returned

        var keyValuePair = pair.split('=');
        tempAttachParamsArray[keyValuePair[0]] = encodeURIComponent(keyValuePair[1]);
        tempAttachParamsArray.sort();

        return $.param(tempAttachParamsArray);
    }
    function detachFromCalls(key){ //detach

        delete attachParamsCallsArray(key);
        attachParamsCalls = $.param(attachArray);
        return attachParamsCalls;
    }
    function detachFromUrls(key){ //detach

        delete attachParamsCallsArray(key);
        attachParamsUrls = $.param(attachArray);
        return attachParamsUrls;
    }

/*Initiate local storage*/