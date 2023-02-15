<?php
	MODULE("PrePage");
	$module = new MiMFa\Module\PrePage();
	$module->Title = "Gallery";
	$module->Draw();

	$rd = "/product/";
	$rdd = "..".$rd;
	$items = array();
	$iterator = array_slice(scandir($rdd),2);
	$i = 0;
	foreach($iterator as $dir) if(is_dir($rdd.$dir)) { 
			$p_text = file_get_contents($rdd.$dir.'/Original.txt');
			array_push($items,array(
				"Image"=> $rdd.$dir.'/Original.png',
				"Name"=> GETVALUE($p_text,"NAME:")??"Mystery #".($i+1),
				"Description"=> GETVALUE($p_text,"MYSTERY:"),
				"Details"=> GETVALUE($p_text,"OWNER RIGHTS:"),
				"Link"=> GETVALUE($p_text,"COLLECT:")
			));
	}
	MODULE("Gallery");
	$module = new MiMFa\Module\Gallery();
	$module->Items = $items;
	$module->Draw();
?>

