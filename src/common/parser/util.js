import { path, join } from 'path'
import hashids from 'hashids'

import { parserList } from 'parserList'

const { readdir, stat } = require('fs').promises


/*

  Parser.js initiation utility functions

— Auxilliary functions to read predefined files on server below)

*/

export function initParserKeywords_byCategory(category, parsers = null){

  var invokeKeywords_perParser = [];
  var parsers = (_.isArray(parsers)) ? parsers : parserList[category];

  forEach(parserList[category], (parserName) => {
    invokeKeywords_perParser[ category ].push( fetchKeywords(parserName) );
  })
}

function fetchKeywords(moduleName){

  try {
    return require('./'+moduleName)(invokeWith)

  } catch (ex) {
    return null;
  }
}



/*

  Schema mapping functions

*/

export const generateId_hashids = (string, salt = [1,2,3]) => {
  var hashids = new Hashids(string);
  return hashids.encode(salt);
}

export const getBeforeFirstComma = (string) => {
  let arr = string.split(',');
  return arr[0];
}

export const passThroughRegex = (string, regexArray, returnBeforeNull = true) => {
  if( _.isArray(regexArray) && string.length ){

    var result = string;

    forEach(regexArray, (rule) => {
      var temp = result.Match(rule);

      if(temp){
        result = temp; // !!! revisit: temp[0] or temp[1]?
      } else {
        return result;
      }
    });
    
    return result;
  }

  return null;
}

export const regexParentheses = new RegExp('/\(([^)]+)\)/')
/* Breakdown:

    \( : match an opening parentheses
    ( : begin capturing group
    [^)]+: match one or more non ) characters
    ) : end capturing group
    \) : match closing parentheses
*/

export const regexSquareBrackets = new RegExp('\[(.*?)\]')



/*

  Local filebase functions

*/

// Async getDirs function
export const getDirs = function(rootDir, cb) { 
  fs.readdir(rootDir, function(err, files) { 
      var dirs = []; 
      for (var index = 0; index < files.length; ++index) { 
        var file = files[index]; 
        if (file[0] !== '.') { 
          var filePath = rootDir + '/' + file; 
          fs.stat(filePath, function(err, stat) {
            if (stat.isDirectory()) { 
              dirs.push(this.file); 
            } 
            if (files.length === (this.index + 1)) { 
              return cb(dirs); 
            } 
          }.bind({index: index, file: file})); 
        }
    }
  });
}

// Async getDirectories function
export const getDirectories = async path => {
  let dirs = []
  for (const file of await readdir(path)) {
    if ((await stat(join(path, file))).isDirectory()) {
      dirs = [...dirs, file]
    }
  }
  return dirs
}

export const fileExists = (path) => {
  return fs.existsSync(path)
}



// Additional code examples (to tidy up and tie into flow, if necessary)
/*

// 1: Get list of directories
path.resolve(__dirname, file)

const isDirectory = source => lstatSync(source).isDirectory()
const getDirectories = source =>
  readdirSync(source).map(name => join(source, name)).filter(isDirectory)

// 2: Read file
fs.readFile('JournalDEV.txt', 'utf8', readData);



// 3 & 4: Search objects in array by keys
// From:  stackoverflow.com/questions/35137774/extract-deeply-nested-child-objects-by-property-name-with-lodash

// 3: Plural, efficient (thus modified for searching multiple objects)
export function find_objects_by_name(obj, keys) {
  if( !(obj instanceof Array) ) return [];

  var res = [];
  forEach(keys, function(key){
    if (key in obj)
      res.push.apply(res, key)
  });
  if( res.length ){
    return res;
  }

  forEach(obj, function(v) {
    if ( typeof v == "object" && (v = find_obj_by_name(v, keys)).length )
      res.push.apply(res, v); // _.merge() might be correct in plural form?
  });
  return res;
}

// 4: Elegant (code-wise)
export function find_object_by_name(obj, key) {
    if( !(obj instanceof Array) ) return [];

    if (key in obj)
        return [obj[key]];

    return _.flatten(_.map(obj, function(v) {
        return typeof v == "object" ? find_obj_by_name(v, key) : [];
    }), true);

}*/