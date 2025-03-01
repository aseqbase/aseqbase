<?php
use \MiMFa\Library\Html;
\_::$Front->Libraries = [
	Html::Script(null, 'https://code.jquery.com/jquery-3.7.1.min.js'),
	Html::Script("
			if(!window.jQuery) {
				Html.script.load(null, '" . forceFullUrl(\_::$Address->ScriptPath . "JQuery.js") . "');
				Html.style.load(null, '" . forceFullUrl(\_::$Address->StylePath . "DataTable.css") . "');
				Html.script.load(null, '" . forceFullUrl(\_::$Address->ScriptPath . "DataTable.js") . "');
				Html.script.load(null, '" . forceFullUrl(\_::$Address->ScriptPath . "Popper.js") . "');
			}
	"),
	Html::Style(null, 'https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css'),
	Html::Script(null, 'https://cdn.datatables.net/2.0.3/js/dataTables.min.js'),
	Html::Script(null, 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js'),
	...\_::$Front->Libraries
];
?>