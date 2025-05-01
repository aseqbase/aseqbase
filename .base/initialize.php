<?php
require_once(__DIR__."/global.php");

component("Component");
template("Template");
module("Module");

runAll("router");
runAll("customize");
run("revise");
?>