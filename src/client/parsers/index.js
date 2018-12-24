import parser from './parser'
import {parserList} from './parserList'

import {fileExists, getDirs, getDirectories} from './util'

// Dummy data sets — root folder
const dataRoot = './datasets'


const parserProcessText = (parser, caption, text, diff = '') => {

  return jsonObjects
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


export { parserProcessText, parserProcessFile, parserProcessFolder }