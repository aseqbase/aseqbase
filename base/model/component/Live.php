<?php use \MiMFa\Library\Struct;
\_::$Front->Libraries = [
    ...\_::$Front->Libraries,
    // Adding Polyfill for older browsers
    Struct::Script(null, asset(\_::$Address->StructRootDirectory, "live/Polyfill.js", optimize: true)),
    Struct::Script("
        if (!window.Proxy) {
            window.Proxy = function(obj, handler) {
                return obj;
            };
        }
    "),// To support older browsers that do not have Proxy
    Struct::Script(null, asset(\_::$Address->StructRootDirectory, "live/Live.js", optimize: true))
];