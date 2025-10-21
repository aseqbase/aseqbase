<?php use \MiMFa\Library\Html;
\_::$Front->Libraries = [
	Html::Script(null, 'https://code.jquery.com/jquery-3.7.1.min.js'),
	Html::Script("
			if(!window.jQuery) {
				Html.script.load(null, '" . asset(\_::$Address->ScriptDirectory, "JQuery.js", optimize: true) . "');
				Html.style.load(null, '" . asset(\_::$Address->StyleDirectory, "DataTable.css", optimize: true) . "');
				Html.script.load(null, '" . asset(\_::$Address->ScriptDirectory, "DataTable.js", optimize: true) . "');
				Html.script.load(null, '" . asset(\_::$Address->ScriptDirectory, "Popper.js", optimize: true) . "');
			}
	"),
	Html::Style(null, 'https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css'),
	Html::Script(null, 'https://cdn.datatables.net/2.0.3/js/dataTables.min.js'),
	Html::Script(null, 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js'),
	...\_::$Front->Libraries
];