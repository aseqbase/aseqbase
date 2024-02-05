	</head>
	<?php echo "<body".
		(\_::$CONFIG->AllowContextMenu?"":" oncontextmenu='return false;'").
		(\_::$CONFIG->AllowSelecting?"":" unselectable='on' onselectstart='return false;' onmousedown='return false;''").
		">"; ?>
	<?php echo \_::$TEMPLATE->GetMain(); ?>