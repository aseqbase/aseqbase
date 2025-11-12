<?php namespace MiMFa\Component;
use MiMFa\Library\Struct;
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Address->ScriptDirectory, 'Math.js', optimize: true));
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Address->ScriptDirectory, 'Array.js', optimize: true));
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Address->ScriptDirectory, 'Struct.js', optimize: true));
//\_::$Front->Libraries[] = \MiMFa\Library\Struct::Script( null, forceFullUrl(asset(\_::$Address->ScriptDirectory,'Live.js')));
