self.addEventListener('message', function(e) {
  var args = e.args;
  if(args['function'].length){
    var fnc = args['function'];
    delete args['function'];

    if(typeof self['storage'] === 'undefined'){
        self['getLocalData'] = getLocalData();
        self['putLocalData'] = putLocalData();
        self['clearLocalStorage'] = clearLocalStorage();
    }

    if(fnc = 'initLocalDB'){
        self['storage'] = self[fnc].apply(null, args);
    } else {
        self[fnc].apply(null, args);
    }
  }
}, false);

function initLocalDB(args){
    var dbObject = {};

    if(args['schema'].length){
        dbObject = new ydn.db.Storage('db-local', args.schema);
    }
    return dbObject;
}

function getLocalData(args){

    if(!!window.Worker){
        var storage = self['storage'];
    }

    var store = args.collection;
    if(store){
        var field = typeof args.field !== 'undefined' ? args.field : null;
        var where = typeof args.where !== 'undefined' ? where : null;
        //var orderBy - secondary index "call" selected
        var desc = typeof args.desc !== 'undefined' ? args.desc : false;
        var limit = typeof args.limit !== 'undefined' ? args.limit : 1;

        var query,
            output = [],
            count;

        if(where && field){
            output['count'] = storage.count(store, field);
        } else {
            output['count'] = storage.count(store);
        }

        if(args.count || output['count'] == 0){
            return output;
        } else {
            if(count == 0){
                return output['count']; 
            }
            for(i = 0; i < ceil(count / limit); i++){
                if(where){
                    query = storage.values(new ydn.db.IndexValueIterator(store, field, where, limit, i * ceil(count / limit)), desc);
                } else {
                    query = storage.values(new ydn.db.IndexValueIterator(store, field, null, limit, i * ceil(count / limit)), desc);
                }
                output['data'][] = query.done(function(records){
                    return records;
                  }, function(e) {
                    console.error(e);
                    return array('status_code' => array('localdb_error'));
                });
            }
        }
        
        if(!$.inArray('localdb_error')){
            response['status_code'].push(array('localdb_get_success'));
        }
        return output;
        /* Other forms of querying with YDN-DB
        response = storage.from('list').where('id', '=', call).order('part', desc).done(function(results){
            return results;
        });*/
    }
}

function putLocalData(collection, data){

    if(!!window.Worker){
        var storage = self.storage;
    }
    storage.put(collection, data).fail(function(e) {
        console.error(e);
        return array('status_code' => ['localdb_error' => e]);
    });
    return array('status_code' => ['localdb_put_success' => e]);
}

function removeLocalData(args){

    if(!!window.Worker){
        var storage = self.storage;
    }

    var store = args.collection;
    var field = typeof args.field !== 'undefined' ? args.field : null;
    var where = typeof args.where !== 'undefined' ? args.where : null;
    if(store && field && where){
        storage.remove(store, field, where).done(function(){
            return array('status_code' => array('localdb_removed_success')]);
        }, function(e) {
            console.error(e);
            return array('status_code' => array('localdb_error' => e));
        });
    }
}