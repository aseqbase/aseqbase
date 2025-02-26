<?php
\_::$Front->Libraries = ["
		<script src='https://code.jquery.com/jquery-3.7.1.min.js'></script>
		<script>
			if(window.jQuery)
				document.write(`
					<link rel='stylesheet' href='https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css'>
					<script src='https://cdn.datatables.net/2.0.3/js/dataTables.min.js'><\/script>
					<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js'><\/script>
				`);
			else 
				document.write(`
					<script src='".getUrl(\_::$Address->ScriptPath . "JQuery.js")."'><\/script>
					<link rel='stylesheet' href='".getUrl(\_::$Address->StylePath . "DataTable.css")."'>
					<script src='".getUrl(\_::$Address->ScriptPath . "DataTable.js")."'><\/script>
					<script src='".getUrl(\_::$Address->ScriptPath . "Popper.js")."'><\/script>
				`);
		</script>
",
...\_::$Front->Libraries];
?>