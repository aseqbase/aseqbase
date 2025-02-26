<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('display_startup_errors', E_ALL);

require_once(__DIR__."/global.php");

component("Component");
template("Template");
module("Module");

runAll("customize");
runAll("router");
?>