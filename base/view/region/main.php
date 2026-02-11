	</head>
	<?php echo "<body".
		(\_::$Front->AllowContextMenu?"":" oncontextmenu='return false;'").
		(\_::$Front->AllowSelecting?"":" unselectable='on' onselectstart='return false;' onmousedown='return false;''").
		">".\_::$Front->GetMain();