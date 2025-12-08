<?php use \MiMFa\Library\Struct;
\_::$Front->Libraries = [
	Struct::Script(null, asset(\_::$Address->PackageDirectory, "JQuery/Script.js")),
	Struct::Script(null, asset(\_::$Address->PackageDirectory, "JQuery/Popper.js")),
	// Struct::Script(null, 'https://code.jquery.com/jquery-3.7.1.min.js'),
	// Struct::Script("
	// 		if(!window.jQuery) {
	// 			Struct.script.load(null, '" . asset(\_::$Address->PackageDirectory, "JQuery/Script.js", optimize: true) . "');
	// 			Struct.script.load(null, '" . asset(\_::$Address->PackageDirectory, "JQuery/Popper.js", optimize: true) . "');
	// 		}
	// "),
	// Struct::Script(null, 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js'),
	...\_::$Front->Libraries
];