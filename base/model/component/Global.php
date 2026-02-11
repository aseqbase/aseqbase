<?php namespace MiMFa\Component;
use MiMFa\Library\Struct;
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Address->GlobalScriptDirectory, 'global.js', optimize: true));
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Address->GlobalScriptDirectory, 'Math.js', optimize: true));
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Address->GlobalScriptDirectory, 'Array.js', optimize: true));
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Address->GlobalScriptDirectory, 'Struct.js', optimize: true));
