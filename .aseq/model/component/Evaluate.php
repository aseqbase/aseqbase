<?php use \MiMFa\Library\Struct;
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Address->PackageDirectory, "Evaluate/Evaluate.js", optimize: true));
\_::$Front->Finals[] = Struct::Script("_(document).ready(function(){Evaluate.Url();});");
