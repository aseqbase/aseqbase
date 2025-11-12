<?php use \MiMFa\Library\Struct;
\_::$Front->Libraries[] = Struct::Style(null, asset(\_::$Address->StyleDirectory, "View.css", optimize: true));
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Address->ScriptDirectory, "View.js", optimize: true));