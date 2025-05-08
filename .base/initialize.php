<?php
require_once(__DIR__.DIRECTORY_SEPARATOR."global.php");

component("Component");
template("Template");
module("Module");

runAll("router");
runAll("customize");
run("revise");
?>