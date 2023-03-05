<?php
$iterator = MiMFa\Library\DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."Gallery");
if(isValid($iterator)){
	MODULE("Navigation");
	$nav = new MiMFa\Module\Navigation($iterator);
	$nav->Draw();

	$items = array();
	foreach($nav->GetItems() as $k=>$value) {
		$item = array();
		if(isValid($value,"Image")) $item["Image"] = $value["Image"];
		if(isValid($value,"Name")) $item["Name"] = $value["Name"];
		if(isValid($value,"Title")) $item["Title"] = $value["Title"];
		if(isValid($value,"Description")) $item["Description"] = $value["Description"];
		if(isValid($value,"Content")) $item["Content"] = $value["Content"];
		if(isValid($value,"Path")) $item["Path"] = $value["Path"];
		array_push($items, $item);
    }
	MODULE("Gallery");
	$module = new MiMFa\Module\Gallery();
	$module->Items = $items;
	$module->Draw();

	$nav->Draw();
}
?>

