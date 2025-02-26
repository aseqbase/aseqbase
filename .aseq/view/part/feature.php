<?php
use \MiMFa\Library\DataBase;

module("Gallery");
$module = new MiMFa\Module\Gallery();
$module->BlurSize = "1px";
$module->Items = array();
$module->Items = table("Feature")->DoSelect();
$module->Render();
?>