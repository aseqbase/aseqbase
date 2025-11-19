<?php use \MiMFa\Library\Struct;
\_::$Front->Libraries[] = Struct::Style(null, asset(\_::$Address->PackageDirectory, "DataTable/Style.css", optimize: false));
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Address->PackageDirectory, "DataTable/Script.js", optimize: false));
// \_::$Front->Libraries[] = Struct::Style(null, 'https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css');
// \_::$Front->Libraries[] = Struct::Script(null, 'https://cdn.datatables.net/2.0.3/js/dataTables.min.js');