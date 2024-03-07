<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('display_startup_errors', E_ALL);

require_once(__DIR__."/global.php");

COMPONENT("Component");
TEMPLATE("Template");
MODULE("Module");

if(!is_null(\_::$INFO->User)){
    $metadata = json_decode(getValid(\_::$INFO->User->GetGroup(),"MetaData","[]"))??[];
    foreach ($metadata as $key=>$value)
        if(isset(\_::$INFO->$key)) \_::$INFO->$key = $value;
}

RUN("customize");
?>