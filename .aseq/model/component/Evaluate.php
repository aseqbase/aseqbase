<?php use \MiMFa\Library\Struct;
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Address->PackageDirectory, "Evaluate/Script.js", optimize: true));
\_::$Front->Finals[] = Struct::Script("$(document).ready(function(){Evaluate.Url();});");
