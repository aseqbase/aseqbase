<?php
if (($_SERVER['REQUEST_METHOD'] ?? null) === 'OPTIONS')
    deliver("", 200);// Respond to preflight quickly

\_::$Router->On("(\S|\s)*")->Internal(fn()=>MiMFa\Library\Internal::Render());
        // deliver([\_::$Router->DefaultMethodName,getMethodName()]);