<?php use \MiMFa\Library\Struct;
\_::$Front->Libraries[] = Struct::Style(null, asset(\_::$Address->StructRootDirectory, "view/View.css", optimize: true));
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Address->StructRootDirectory,"view/View.js", optimize: true));