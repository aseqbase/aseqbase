<?php use \MiMFa\Library\Struct;
\_::$Front->Libraries = [
	Struct::Script(null, 'https://code.jquery.com/jquery-3.7.1.min.js'),
	Struct::Script("
			if(!window.jQuery) {
				Struct.script.load(null, '" . asset(\_::$Address->ScriptDirectory, "JQuery.js", optimize: true) . "');
				Struct.style.load(null, '" . asset(\_::$Address->StyleDirectory, "DataTable.css", optimize: true) . "');
				Struct.script.load(null, '" . asset(\_::$Address->ScriptDirectory, "DataTable.js", optimize: true) . "');
				Struct.script.load(null, '" . asset(\_::$Address->ScriptDirectory, "Popper.js", optimize: true) . "');
			}
	"),
	Struct::Style(null, 'https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css'),
	Struct::Script(null, 'https://cdn.datatables.net/2.0.3/js/dataTables.min.js'),
	Struct::Script(null, 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js'),
	...\_::$Front->Libraries
];