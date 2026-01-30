<?php use \MiMFa\Library\Struct;
\_::$Front->Libraries[] = Struct::Style(null, asset(\_::$Address->StructDirectory, "View/View.css", optimize: true));
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Address->StructDirectory,"View/View.js", optimize: true));