<?php use \MiMFa\Library\Struct;
\_::$Front->Libraries[] = Struct::Style(null, asset(\_::$Address->PackageDirectory, "View/Style.css", optimize: true));
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Address->PackageDirectory, "View/Script.js", optimize: true));