
import {Triejs} from 'triejs'	// Generates a searchable trie data object
// … problems:
// — there's no predetermined way to import/export "trie" trees (caching)

import {Trie} from 'regexgen'	// Generates RegExp from trie data structure
// … problems:
// — regex doesn't return two results which overlap 
//	(at a range of column, line coordinates)
//	(resolve with a check & 2nd pass of suffixes of other keywords/phrases)
// — regexgen doesn't let \s or \S through from text to a RegExp
//	(requiring a check )
// — there's no predetermined way to import/export "trie" trees (caching)



export const test = () => {

	var trie = new Triejs();
	let t = new Trie;


	var obj = { 
		'lol': { data: "hmm" },
		'lolol': { data: "hmm" },
		'lol\n— [¹': { data: "hmm" },
		'lol\n— [²': { data: "hmm" },
		'test\s': [{ data: "hmm" }],
		'test': [{ data: "hmm1" }],
		'testest': [{ data: "hmm1" }],
		'lol\\S\\s\r\ntest': { data: "hmm" }
	}
	/*

	Please note that 'lol' doesn't have a very specific meaning 
	in case of this test of components.
	
	*/

	var copy = {}, partialMatch_afterNewLine = {}


	_.each(obj, (value, key) => {

		console.log(key)
		console.log(value)

		trie.add(key, value);
		t.add(key);
	})


	var regex = t.toRegExp(); // => /fooba[rz]/

	console.log(regex)
	console.log(trie.find('test'))

	return false
}