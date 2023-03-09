<?php
use \MiMFa\Library\DataBase;

MODULE("Gallery");
$module = new MiMFa\Module\Gallery();
$module->BlurSize = "1px";
$module->Items = array();
$module->Items = DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."Feature");
$module->Draw();
?>