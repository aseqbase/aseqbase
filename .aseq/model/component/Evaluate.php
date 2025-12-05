<?php use \MiMFa\Library\Struct;
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Router->PackageDirectory, "Evaluate/Script.js", optimize: true));
\_::$Front->Finals[] = Struct::Script("$(document).ready(function(){Evaluate.Url();});");
