<?php use \MiMFa\Library\Html;
\_::$Front->Libraries[] = Html::Style(null, asset(\_::$Address->StyleDirectory, "View.css", optimize: true));
\_::$Front->Libraries[] = Html::Script(null, asset(\_::$Address->ScriptDirectory, "View.js", optimize: true));