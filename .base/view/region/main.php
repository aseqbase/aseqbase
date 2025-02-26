	</head>
	<?php echo "<body".
		(\_::$Config->AllowContextMenu?"":" oncontextmenu='return false;'").
		(\_::$Config->AllowSelecting?"":" unselectable='on' onselectstart='return false;' onmousedown='return false;''").
		">"; ?>
		<?php echo \_::$Front->GetMain(); ?>