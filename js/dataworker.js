self.addEventListener('message', function(e) {
  var data = e.data;
  if(data['function'].length){
    var fnc = data['function'];
    delete data['function'];

    if(typeof self['db'] === 'undefined'){
        self['db'] = initLocalDB();
        self['getLocalData'] = getLocalData();
        self['putLocalData'] = putLocalData();
    }

    if(fnc = 'initLocalDB'){
        self['db'] = self[fnc].apply(null, data);
    } else {
        self[fnc].apply(null, data);
    }
  }
}, false);

function initLocalDB(args){
    var db = {};
    db['inmemory'] = false;
    db['persistent'] = false;

    if(args['schemas']['inMemory'].length && args.schemas.persistent.length){
        db['inmemory'] = new ydn.db.Storage('inmemory', args.schemas.inMemory);
        db['persistent'] = new ydn.db.Storage('persistent', args.schemas.persistent);
    }
    return db;
}

function getLocalData(args){
    if(args.params.length){
        var call = $.param(params);
        var part = args.part;
        var reactive = args.reactive;
        var cacheLevel = args.cacheLevel;

        if(!!window.Worker){
            var storage = self.db;
        }

        var response = [];

        //Query local inmemory storage first
        var storageType = 'persistent';
        var query = storage['inmemory'].from('call').where('id', '=', call).order('time_synchronized', true); //order desc

        //Query local db storage second
        if(!query){
            console.log("getLocalData(): !query");
            var query = storage['persistent'].from('call').where('id', '=', call).order('part', true); //order desc
            storageType = 'persistent';
        }

        var data = [];
        var cursor;
        if(query.length){
            $.each(query, function(item){
                data = $.parseJSON(item.response);
                if(data['node_id']){
                    cursor = data['node_id'];
                } else {
                    cursor = data['table']+"."+data['entry_id'];
                }
                storage[storageType].from('node').where(cursor).done(function(node){
                    //stored data is older than sync_interval
                    //nodes or parts of nodes are missing
                    $.parseJSON(node);
                 });
            });
        });
            response['status_code'].push('localdb_'+storageType);
            response['status_code'].push('localdb_success');
        } else {
            response['status_code'].push('localdb_nodata');
        }
        }
    }
}

function putLocalData(){
    var call = $.param(params);
    var part = arguments['part'];
    var reactive = arguments['reactive'];
    var cacheLevel = arguments['cacheLevel'];
    if(!!window.Worker){
        var storage = self.db;
    }
    /*var data = {
        "text":todo.value,
        "timeStamp":new Date().getTime()
    };
    db.put('call', data).fail(function(e) {
        console.error(e);
    });
    db.put('node', data).fail(function(e) {
        console.error(e);
    });*/
}

if(!!window.Worker){
    self['initLocalDB'] = initLocalDB;
}