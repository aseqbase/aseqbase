	</head>
	<?php echo "<body".
		(\_::$User->AllowContextMenu?"":" oncontextmenu='return false;'").
		(\_::$User->AllowSelecting?"":" unselectable='on' onselectstart='return false;' onmousedown='return false;''").
		">".\_::$Front->GetMain();