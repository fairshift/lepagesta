//Routes
    crossroads.addRoute('/', function(id){ //Landing page
        
    );
    //crossroads.routed.add(console.log, console); //log all routes
     
//Hasher setup
    function parseHash(newHash, oldHash){
      crossroads.parse(newHash);
    }
    function setHashSilently(hash){
      hasher.changed.active = false; //disable changed signal
      hasher.setHash(hash); //set hash without dispatching changed signal
      hasher.changed.active = true; //re-enable signal
    }
    hasher.initialized.add(parseHash); //parse initial hash
    hasher.changed.add(parseHash); //parse hash changes
    hasher.init(); //start listening for history change