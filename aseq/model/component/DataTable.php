<?php use \MiMFa\Library\Struct;
if (get($data, "CDN")) {
    \_::$Front->Libraries[] = Struct::Style(null, 'https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css');
    \_::$Front->Libraries[] = Struct::Script(null, 'https://cdn.datatables.net/2.0.3/js/dataTables.min.js');
} else {
    \_::$Front->Libraries[] = Struct::Style(null, asset(\_::$Address->GlobalStructDirectory,"DataTable/DataTable.css", optimize: false));
    \_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Address->GlobalStructDirectory,"DataTable/DataTable.js", optimize: false));
}