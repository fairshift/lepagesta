

import _ from 'lodash'
import lowdb from 'lowdb'


export default Both




export const Server = () => {


}


export const Client = () => {


}


export const Both = () => {


	dbTest__passed();

	getterObject.match


	return null
}




//
// Tests
//


const dbTest__passed = () => {


	//var fs = require('fs')
	var low = require('lowdb')
	//var FileSync = require('lowdb/adapters/FileSync')
	var Memory = require('lowdb/adapters/Memory')

	var db = low(
	  new Memory()
	)


	db.defaults({ 
		tokens: testDatasets.tokens, 
		customSortingTest: testDatasets.customSortingTest,
		alphanumSortTest: testDatasets.alphanumSortTest
	})
	.write()


	db._.mixin({
		within: (array, parsaBlocks) => {
			/*
			_.map(array, (key, obj) => {

			})

			_.filter(({within}) => 
				typeof within !== 'undefined' && 
				within.
			)
			*/
			return array
		},
		customSortingTest: (array) => {

			console.log(array)

			console.table(_.chain(array).map(function (i) {
			  return i.items.sort(function (a, b) {
			    return a.ordinal - b.ordinal;
			  });
			}).flatten().value());

		},
		alphanumSortTest: (array) => {
			console.log(alphanumSort(array))
		},
	  recent: function(array, limit = 1, orderBy = ['id'], order = 'desc') {

	    console.log(array)
			array = _.orderBy(array, orderBy, order)

	    return (limit == 1) ? _.head(array) : _.slice(array, 0, limit)
	  }
	})

/*
	var result = db
		.get('tokens')
		.within({
			std: [],
			parserName: ["list", "Of", "Expressions"]
		})
		.filter(

	  	({id, within}) => 
		  	id >= 1 && 
		  	(typeof within !== 'undefined' && within.indexOf("this") >= 0),

	  	({block}) => 
	  		block == 'Paragraph'
	  )
	  .recent(2, ['id'], 'desc')
	  .value()


	console.log(result)*/

	var result = db.get('customSortingTest').customSortingTest().value()

	db.get('alphanumSortTest').alphanumSortTest().value()

	return result
}



// Array sorting, supports combination of numbers and alphabet characters
// src: http://www.greywyvern.com/?post=362
const alphanumSort = function(array, caseInsensitive = true) {

  for (var z = 0, t; t = array[z]; z++) {
    array[z] = [];
    var x = 0, y = -1, n = 0, i, j;

    while (i = (j = t.charAt(x++)).charCodeAt(0)) {
      var m = (i == 46 || (i >=48 && i <= 57));
      if (m !== n) {
        array[z][++y] = "";
        n = m;
      }
      array[z][y] += j;
    }
  }
  /*
  array.sort(function(a, b) {
    for (var x = 0, aa, bb; (aa = a[x]) && (bb = b[x]); x++) {
      if (caseInsensitive) {
        aa = aa.toLowerCase();
        bb = bb.toLowerCase();
      }
      if (aa !== bb) {
        var c = Number(aa), d = Number(bb);
        if (c == aa && d == bb) {
          return c - d;
        } else return (aa > bb) ? 1 : -1;
      }
    }
    return a.length - b.length;
  });
	*/
  for (var z = 0; z < array.length; z++)
    array[z] = array[z].join("");


  return array
}


//
// Example datasets
// … tokens, customSortingTest
//


const testDatasets = {
	tokens: [
		{
			id: 1,
			block: "Section",
			within: ["this", "that"],
			parser: null
		},
		{
			id: 1,
			block: "Paragraph",
			within: ["this", "that", "those"],
			ref: 1
		},
		{
			id: 3,
			block: "orderedList",
			type: "numbered",
			ref: 2
		},
		{
			id: 4,
			expr: "numberedItem",
			ref: 3,
			val: "White rabbit jumps a landmine, which is calibrated to a weight of a larger rock …\r\nTo what end does this statement serve?"
		}
	],

	// https://www.sitepoint.com/sophisticated-sorting-in-javascript/
	// https://github.com/johngibbons/lexicographic-sort/blob/master/sort.js
	// http://www.davekoelle.com/alphanum.html
	// http://www.greywyvern.com/?post=362
	// http://jsfiddle.net/andymerts/kqa6yywj/
	customSortingTest: [{
	  name: "Test1",
	  items: [{
	    itemId: 1,
	    name: "item1",
	    ordinal: "a2",
	    active: true
	  }, {
	    itemId: 3,
	    name: "item3",
	    ordinal: "a13",
	    active: false
	  }]
	}, {
	  name: "Test2",
	  items: [{
	    itemId: 2,
	    name: "item2",
	    ordinal: "b1",
	    active: false
	  }, {
	    itemId: 4,
	    name: "item4",
	    ordinal: "a1b",
	    active: true
	  }]
	}],
	alphanumSortTest: ["a2", "a13", "b1", "a1b"]
}


const getterObject = {
	a: true,
	get match(){
		console.log("Getter: "+this.a)
	}
}