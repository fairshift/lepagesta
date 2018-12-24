/*

Something like this in a browser worker would make sense...
- it makes the local database process faster,
- reduces memory load of Redux, using in-browser storage
etc...

Of course, done in a worker fashion, with messages from this to that component.
They say it's possible with some Webpack modules and modifications to configuration

*/

import db from 'indexeddbshim'

db.open({
    server: 'my-app',
    version: 1,
    schema: null
}).then(function(s){
    server = s;
    console.log("Database created/Server Opened");
}).then(function () {
    server.people.add({
        firstName: 'name',
        num: parseInt(Math.random() * 10) % 2
    }).then(function (res) {
        console.log('Add item: ' + JSON.stringify(res));
        return server.people.query('num').all().execute();
    }).then(function (results) {
        console.log(results);
        console.log('Query item: ' + JSON.stringify(results));
        return server.people.remove(1);
    }).then(function (item) {
        console.log('Remove item: ' + item);
    });
});

export default db;