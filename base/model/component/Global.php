<?php namespace MiMFa\Component;
use MiMFa\Library\Struct;
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Address->ScriptRootDirectory, 'global.js', optimize: true));
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Address->ScriptRootDirectory, 'Math.js', optimize: true));
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Address->ScriptRootDirectory, 'Array.js', optimize: true));
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Address->ScriptRootDirectory, 'Struct.js', optimize: true));
