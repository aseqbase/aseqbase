<?php use \MiMFa\Library\Html;
\_::$Front->Libraries[] = Html::Style(null, asset(\_::$Base->StyleDirectory, "View.css", optimize: true));
\_::$Front->Libraries[] = Html::Script(null, asset(\_::$Base->ScriptDirectory, "View.js", optimize: true));