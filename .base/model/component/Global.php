<?php namespace MiMFa\Component;
use MiMFa\Library\Html;
\_::$Front->Libraries[] = Html::Script(null, asset(\_::$Address->ScriptDirectory, 'Math.js', optimize: true));
\_::$Front->Libraries[] = Html::Script(null, asset(\_::$Address->ScriptDirectory, 'Array.js', optimize: true));
\_::$Front->Libraries[] = Html::Script(null, asset(\_::$Address->ScriptDirectory, 'Html.js', optimize: true));
//\_::$Front->Libraries[] = \MiMFa\Library\Html::Script( null, forceFullUrl(asset(\_::$Address->ScriptDirectory,'Live.js')));
