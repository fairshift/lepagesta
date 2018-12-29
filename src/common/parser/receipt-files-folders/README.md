```javascript

//
// 	Inputs (processed by parser package): 
// — receipts (text transcripts): string or an array of files' or folders' paths
// — additional arguments: 

//
//	Output: JSON array of: "receipts", "providers", "resources" (instead of distinguished goods and services)



//
//  Folder structure (tree elements)
/*
 —  Root folder
  - Subfolder named as: Year number (full number, since AD) ‹ contains files, only
*/


//
//  Form of a single file (which contains a month of receipts)
// — example in "(11.'18) [mall] Mercator.txt"
/*  

    Filename: "(11.'18) [pub, restaurant] Place (disambiguation).txt"
    — (First_parentheses): Month and year of transcript
     - First_number.: [Number of month in a year].
     - 'Second_number: '[Year after 2000 (20 omitted / 2000 substracted)]
     or Second_number: [Year after 0 AD / 0 BC]
    — [square_brackets]: Types of services provided, comma separated tags (in camelCase or low_dash)
    — What isn't enclosed in any brackets: Name of place
    — (Second_parentheses): Declaration of disambiguation (eg. city, ) — optional

*/

//
//  Contents of a typical file (containing receipts of one specific month)
/*  

    Service provider: first line
    & Stores & address: line starts with "¹", "²", "³", ...

    Resources used: line starts with "— [", 
                    followed with [item_quantity] item_description: price_per_stores¹²³

    Receipts: line starts with 
*/
```