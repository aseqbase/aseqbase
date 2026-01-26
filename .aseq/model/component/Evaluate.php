<?php use \MiMFa\Library\Struct;
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Address->StructDirectory,"Evaluate/Evaluate.js", optimize: true));
\_::$Front->Finals[] = Struct::Script("_(document).ready(function(){Evaluate.Url();});");
