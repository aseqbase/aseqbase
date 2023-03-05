<?php
use MiMFa\Library\DataBase;

MODULE("Gallery");
$module = new MiMFa\Module\Gallery();
$module->BlurSize = "1px";
$module->Items = array();
$arr = DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."Feature");
if(isValid($arr))
    foreach ($arr as $k=>$value){
        $item = array();
        if(isValid($value,"Image")) $item["Image"] = $value["Image"];
        if(isValid($value,"Name")) $item["Name"] = $value["Name"];
        if(isValid($value,"Title")) $item["Title"] = $value["Title"];
        if(isValid($value,"Path")) $item["Path"] = $value["Path"];
        if(isValid($value,"Content")) $item["Content"] = $value["Content"];
        if(isValid($value,"Description")) $item["Description"] = $value["Description"];
        array_push($module->Items, $item);
    }
$module->Draw();
?>