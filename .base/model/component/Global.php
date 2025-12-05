<?php namespace MiMFa\Component;
use MiMFa\Library\Struct;
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Router->ScriptDirectory, 'Math.js', optimize: true));
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Router->ScriptDirectory, 'Array.js', optimize: true));
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Router->ScriptDirectory, 'Struct.js', optimize: true));
//\_::$Front->Libraries[] = \MiMFa\Library\Struct::Script( null, forceFullUrl(asset(\_::$Router->ScriptDirectory,'Live.js')));
