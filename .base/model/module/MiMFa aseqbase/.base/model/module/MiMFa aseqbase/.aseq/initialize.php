<?php
$dirs = explode("/", __DIR__);
$GLOBALS["ASEQ"] = end($dirs);
$GLOBALS["BASE"] = ".base";

$GLOBALS["NEST"] = !empty($GLOBALS["ASEQ"])?preg_match_all("/(?<=\S|\s)\.(?=\S|\s)/",$ASEQ)+1:0;
if(!isset($GLOBALS["HOST"])){
	$GLOBALS["HOST"] = (isset($_SERVER['HTTPS'])?"https://":"http://");
	if($NEST > 0){
		$host_parts = explode(".", strtolower(trim($_SERVER["HTTP_HOST"])));
		$hpc = count($host_parts);
		$GLOBALS["HOST"] .= $host_parts[$hpc-(1+$NEST)];
		for ($i = $NEST; $i > 0; $i--) $GLOBALS["HOST"] .=".".$host_parts[$hpc-$i];
    }
	else $GLOBALS["HOST"] .= strtolower(trim($_SERVER["HTTP_HOST"]));
}

$GLOBALS["BASE_ROOT"] = $GLOBALS["HOST"]."/".$GLOBALS["BASE"]."/";
$GLOBALS["BASE_DIR"] = __DIR__."/";
for ($i = $NEST; $i > 0; $i--) $GLOBALS["BASE_DIR"] .= "../";
$GLOBALS["BASE_DIR"] .= $GLOBALS["BASE"]."/";

///$SequencesMode:
///	=1;//White Listed
///	=0;//Not Action
///	=-1;//Black Listed
$GLOBALS["ASEQ_Limitation"] = 0;
$GLOBALS["ASEQ_Patterns"] = array();


//Don't change the codes below:
require_once(__DIR__."/global.php");
?>