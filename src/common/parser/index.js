import parser from './parser'
import {parserList} from './parserList'

import {fileExists, getDirs, getDirectories} from './util'

import _ from 'lodash'

// Dummy data sets — root folder
const dataRoot = './datasets'


const exposeParsables = () => {

  var keywords = _.map(parserList, function(category, item){ 
    return _.map(item, function(parser){

      var obj = {};
      var parserModule = category+' '+parserName;
      var invokeKeywords = '';

      try {
        invokeKeywords = require(parserModule)(invokeKeywords)
        obj[category+' '+parser] = require('./receipts-blablaz').parserMappings
        return obj

      } catch (ex) {
        invokeKeywords = '';
      }

    });
  });
}


const argsDefault = {
  caption: '',
  text: '',
  diff = ''
}
const parserProcessText = (parserName, args = argsDefault) => {

  var n = parserName.split('-');

  if(parserList[n[0]].indexOf(n[1]) > -1){

    return parser( parserName, args );
  }

  return false
}

const parserProcessFile = (parser, pathToFile) => {

  /*if(fileExists(pathToFile)){
    fs.readFile(pathToFile, 'utf8', readData);
  }

  return jsonObjects*/
  return true
}

const parserProcessFolder = (parser, pathToFolder) => {

  /*if(typeof args.pathToFolder !== 'undefined'){
    args.
  }

  return jsonObjects*/
  return true
}


export { exposeParsables, parserProcessText, parserProcessFile, parserProcessFolder }