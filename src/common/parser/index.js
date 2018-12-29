import parser from './parser'
import { parserList } from './parserList'

import { initParserKeywords_byCategory, fileExists, getDirs, getDirectories } from './util'

import _ from 'lodash'

// Dummy data sets — root folder
const dataRoot = './datasets'


const exposeParsables = (args = {}) => {

/* Input params
 — args: {
    categories: ['categoryName1', 'categoryName2', ...],
    parsers: {
      'categoryName1': ['parserName1', 'parserName2', ...]
    }
   }
*/

  var retrieveKeywords = {}; // Function output

  // Loop and return categories and parsers 
  forEach(parserList, function(parserList_category){

    // Specified category
    var thisCategoryIsIn = false;
    if( typeof args.categories !== 'undefined' ){
      if( parserList_category in args.categories ){

        retrieveKeywords[ parserList_category ].push( initParserKeywords_byCategory(parserList_category) );
        thisCategoryIsIn = true;
      }
    }

    // Specified parsers
    if( thisCategoryIsIn == false && typeof args.parsers !== 'undefined' ){

      var match = _.intersection(parserList_category, args.parsers[parserList_category]);
      if( !_.isEmpty(match) == true ){

        retrieveKeywords[ parserList_category ].push( initParserKeywords_byCategory(parserList_category, match) );
      }
    }

    if( typeof args.categories === 'undefined' && typeof args.parsers === 'undefined' ){

      forEach(parserList_category, function(categoryName, parsers){
        retrieveKeywords[ parserList_category ].push( initParserKeywords_byCategory(parserList_category) );
      });
    }
  }

  return retrieveKeywords;
}
// Search loops scenario in this function (efficiency being main concern)
// Better to use native JavaScript forEach function than lodash's _.map or _.merge, which are not aware of steps taken priorly
/*

Function "exposeParsables": loops through parserList array (count: ¹n)
  * ( = multiply by nested array items listed below)
        Loop through categories array (count: ²n), find match among both keys (steps: ²n * ¹n)
        Loop through parsers array (count: ³n)
        * Loop through each parser module name value in array (count: ⁴n) to find a match (steps: ³n * ¹n * ⁴n)

Test maths: {'¹n': 3, '²n': 4, '³n': 5, '⁴n': 6} ‹ [²n, ³n, ⁴n]: average counts of items in arrays
12 + 90 = 102 steps

*/



const parserArgs_schema = {
  caption: '',
  text: '',
  diff = ''
}

const parserProcessText = (categoryName, parserName, args = parserArgs_schema) => {

  var n = parserName.split('-');

  if( typeof parserList[ categoryName ] !== 'undefined' ){
    if( typeof parserList[ categorName ][ parserName ] !== 'undefined' )
      return parser( categoryName, parserName, args );
  }

  return false
}

const parserProcessFile = (parserName, pathToFile) => {

  /*if(fileExists(pathToFile)){
    fs.readFile(pathToFile, 'utf8', readData);
  }

  return jsonObjects*/
  return true
}

const parserProcessFolder = (parserName, pathToFolder) => {

  /*if(typeof args.pathToFolder !== 'undefined'){
    args.
  }

  return jsonObjects*/
  return true
}


export { exposeParsables, parserProcessText, parserProcessFile, parserProcessFolder }