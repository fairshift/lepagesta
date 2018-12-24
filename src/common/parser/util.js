import { path, join } from 'path'
import hashids from 'hashids'

const { readdir, stat } = require('fs').promises



export const getBeforeFirstComma = (string) => {
  let arr = string.split(',');
  return arr[0];
}

export const generateId_hashids = (string, salt = [1,2,3]) => {
  var hashids = new Hashids(string);
  return hashids.encode(salt);
}

// !!!
export passThroughRegex = (string, regexArray, returnBeforeNull = true){
  return true
}


// async getDirs function
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

// async getDirectories function
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


/*
path.resolve(__dirname, file)

const isDirectory = source => lstatSync(source).isDirectory()
const getDirectories = source =>
  readdirSync(source).map(name => join(source, name)).filter(isDirectory)
*/






export const regexParentheses = new RegExp('/\(([^)]+)\)/')
/* Breakdown:

    \( : match an opening parentheses
    ( : begin capturing group
    [^)]+: match one or more non ) characters
    ) : end capturing group
    \) : match closing parentheses
*/

export const regexSquareBrackets = new RegExp('\[(.*?)\]')



/*const 
fs.readFile('JournalDEV.txt', 'utf8', readData);


function readData(err, data) {
  
}

function read*/