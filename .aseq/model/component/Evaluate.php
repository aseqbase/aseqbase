<?php use \MiMFa\Library\Html;
\_::$Front->Libraries[] = Html::Script(null, asset(\_::$Address->ScriptDirectory, "Evaluate.js", optimize: true));
\_::$Front->Finals[] = Html::Script("$(document).ready(function(){Evaluate.URL();});");
