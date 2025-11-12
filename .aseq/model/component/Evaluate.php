<?php use \MiMFa\Library\Struct;
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Address->ScriptDirectory, "Evaluate.js", optimize: true));
\_::$Front->Finals[] = Struct::Script("$(document).ready(function(){Evaluate.Url();});");
