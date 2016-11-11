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
    var storage = {};

    if(args['schema'].length){
        storage = new ydn.db.Storage('db-local', args.schemas.inMemory);
    }
    return storage;
}

function getLocalData(args){

    if(!!window.Worker){
        var storage = self.db;
    }

    var store = args.collection;
    if(store){
        var field = typeof args.field !== 'undefined' ? args.field : null;
        var where = typeof args.where !== 'undefined' ? where : null;
        //var orderBy - secondary index "call" selected
        var desc = typeof args.desc !== 'undefined' ? args.desc : false;

        var query,
            response = [],
            count;

        if(where && field){
            count = storage.count(store, field);
        } else {
            count = storage.count(store);
        }

        if(args.count){
            return count;
        } else {
            for(i = 0; i < ceil(count / 100); i++){
                if(where){
                    query = storage.values(new ydn.db.IndexValueIterator(store, field, where, 100, i * 100));
                } else {
                    query = storage.values(new ydn.db.IndexValueIterator(store, field, null, 100, i * 100);
                }
                response = $.merge(response, query.done(function(records) {
                    return records;
                  }, function(e) {
                    console.error(e);
                    return array('status_code' => ['localdb_error' => e]);
                });
            }
        }
        
        response['count'] = count;
        response['status_code'].push(array('localdb_get_success'));
        /* Other form of response
        response = storage.from('list').where('id', '=', call).order('part', desc).done(function(results){
            return results;
        });*/
    }
}

function putLocalData(table, data){

    if(!!window.Worker){
        var storage = self.storage;
    }
    storage.put(table, data).fail(function(e) {
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