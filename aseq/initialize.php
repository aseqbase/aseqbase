<?php
$GLOBALS["ASEQ"] = end(explode("/", __DIR__));
$GLOBALS["BASE"] = "base";

if(!isset($GLOBALS["HOST"])){
	$host_parts = explode(".", strtolower(trim($_SERVER[HTTP_HOST])));
	$GLOBALS["HOST"] = (isset($_SERVER['HTTPS'])?"https://":"http://").$host_parts[count($host_parts)-2].".".$host_parts[count($host_parts)-1];
}

$GLOBALS["BASE_ROOT"] = $GLOBALS["HOST"]."/".$GLOBALS["BASE"]."/";
$GLOBALS["BASE_DIR"] = __DIR__."/../".$GLOBALS["BASE"]."/";

///$SequencesMode:
///	=1;//White Listed
///	=0;//Not Action
///	=-1;//Black Listed
$GLOBALS["ASEQ_Limitation"] = 0;
$GLOBALS["ASEQ_Patterns"] = array();


//Don't change the codes below:
require_once(__DIR__."/global/global.php");
?>