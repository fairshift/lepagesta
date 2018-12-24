// 	Inputs: receipts as monthly text transcripts as an array of files' pathnames
//	Output: JSON array of: "receipts", "providers", "resources" (instead of distinguished goods and services)
//
//  Test (as if script was run from just outside the 'parsers' folder):	
/*

import { exportJSON } from './parsers'
import { parseFilesIn } from './parsers/receipts-blablaz'
exportJSON( parseFilesIn('./parsers/receipts-blablaz/(11.'18) [mall] Mercator.txt') )

*/

const outputDatasets = {
  receipts: [],
  providers: [],
  resources: []
}

const outputDatasetsFromRegex = {
  service_providers: {
    output: [],
    regex: new RegExp('/^(.*)$/m')
    company: ['^.*(?=d\.d\.|d\.o\.o)', 'sl_SL'],
    company_nested
    address: ['^.*(?=d\.d\.|d\.o\.o)', 'remove', 'sl_SL']
  },
  receipts: {

  },
  resources: []
}


//
//  Folder structure*
/*
— 	Root folder
	- Year number (full number, since AD): contains single files
*/


import _ from 'lodash'
import {  diff_match_patch as dmp } from 'diff_match_patch' /*

          To compare two strings for similarity:

          dmp.levenshtein( dmp.diff_main( diff_main(text1, text2) ) )
*/


//
//  Form of a single file (which contains a month of receipts)
/*	

		Filename: "(11.'18) [pub, restaurant] Place (disambiguation).txt"
		— "(First parentheses)": Month and year of transcript
			— "First number.": [Number of month in a year].
			— "'Second number": '[Number of a year after 2000 (20 omitted / 2000 substracted)]
		— "[square_brackets]": Types of services provided, comma separated tags (in camelCase or low_dash)
		— "What isn't enclosed in any brackets": Name of place
		— "(Second parentheses)" optional: Declaration of disambiguation (eg. city, )

*/


const regexParentheses = new RegExp('/\(([^)]+)\)/')
/* Breakdown:

    \( : match an opening parentheses
    ( : begin capturing group
    [^)]+: match one or more non ) characters
    ) : end capturing group
    \) : match closing parentheses
*/
const regexSquareBrackets = new RegExp('\[(.*?)\]') /*
if not (?:\[(\w)+\s+\]|\[\s(\w)+\]|\[(\w)\])  // with specific cases
and not \[([^]]+)\]                           // where new lines in square brackets are accepted, too
*/

const regexTrim = new RegExp('[^ ].*[^ ]') // trim empty space on beginning and end of a string


//
//  Content of file
/*  

    Service provider: first line
    & Stores & address: line starts with "¹", "²", "³", ...

    Resources used: line starts with "— [", 
                    followed with [item_quantity] item_description: price_per_stores¹²³

    Receipts: line starts with 
*/
const serviceProvider = new RegExp('/^(.*)$/m') // first line
const stores = new RegExp('^(?:¹|²|³|⁴|⁵|⁶|⁷|⁸|⁹)\s.* *$')
const resources = new RegExp('(?:^— \[.*(\s+\+.*)+\s.*|^— \[.*)')
const receipts = new RegExp('^— \(.*')

const 

export const parseReceiptFiles = (paths = {}) => {


}

export const parseSampleFile = () => {

}
