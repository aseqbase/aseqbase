	</head>
	<?php echo "<body".
		(\_::$Back->AllowContextMenu?"":" oncontextmenu='return false;'").
		(\_::$Back->AllowSelecting?"":" unselectable='on' onselectstart='return false;' onmousedown='return false;''").
		">".\_::$Front->GetMain();