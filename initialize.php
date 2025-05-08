<?php
/* Don't change the codes below: */
if(realpath(__DIR__.DIRECTORY_SEPARATOR."global.php")) include_once __DIR__.DIRECTORY_SEPARATOR."global.php";
$GLOBALS["NEST"] = empty($GLOBALS["ASEQ"])?0:preg_match_all("/(?<=\S|\s)\.(?=\S|\s)/",$ASEQ)+1;
if(!isset($GLOBALS["HOST"])){
	$GLOBALS["HOST"] = (isset($_SERVER['HTTPS'])?"https://":"http://");
	if($NEST > 0){
		if(preg_match("/(\d+\.)+$/",$_SERVER["HTTP_HOST"]))
			$host_parts = explode(".", strtolower(trim($_SERVER["HTTP_HOST"])));
		elseif(preg_match("/localhost$/", $_SERVER["HTTP_HOST"]))
			$host_parts = [...explode(".", strtolower(trim($_SERVER["HTTP_HOST"]))), ""];
		else $host_parts = explode(".", strtolower(trim($_SERVER["HTTP_HOST"])));
		$hpc = count($host_parts);
		$GLOBALS["HOST"] .= $host_parts[$hpc-(1+$NEST)];
		for ($i = $NEST; $i > 0; $i--) $GLOBALS["HOST"] .= ".".$host_parts[$hpc-$i];
    }
	else $GLOBALS["HOST"] .= strtolower(trim($_SERVER["HTTP_HOST"]));
}

$directory = __DIR__.DIRECTORY_SEPARATOR;

$GLOBALS["BASE_ROOT"] = $GLOBALS["HOST"]."/".$GLOBALS["BASE"]."/";
$GLOBALS["BASE_DIR"] = $directory;
for ($i = $NEST; $i > 0; $i--) $GLOBALS["BASE_DIR"] .= "..".DIRECTORY_SEPARATOR;
$GLOBALS["BASE_DIR"] .= $GLOBALS["BASE"].DIRECTORY_SEPARATOR;

/* Convert sub domains to sub directories */
$aseq = $GLOBALS["NEST"]>0?preg_replace("/(?<=\S|\s)\.(?=\S|\s)/", "/", $GLOBALS["ASEQ"])."/":"";
if(isset($GLOBALS["DIR"])) $GLOBALS["SEQUENCES"][$directory] = $GLOBALS["HOST"]."/".$aseq;
else {
	$GLOBALS["ASEQBASE"] = $GLOBALS["ASEQ"];
	$GLOBALS["SEQUENCES"] = array();
	$GLOBALS["ROOT"] = $GLOBALS["HOST"]."/".$aseq;
	$GLOBALS["DIR"] = $directory;
}

/* Filter the sequences */
if(count($GLOBALS["SEQUENCES_PATCH"]) > 0){
	$sequences = $GLOBALS["SEQUENCES"];
	foreach($GLOBALS["SEQUENCES_PATCH"] as $newdir=>$newaseq) {
		$notFind = true;
		foreach($sequences as $dir => $aseq)
			if($newdir === $dir) {
				$notFind = false;
				if(empty($newaseq)) unset($GLOBALS["SEQUENCES"][$newdir]);
				else $GLOBALS["SEQUENCES"][$newdir] = $newaseq;
			}
		if($notFind) $GLOBALS["SEQUENCES"][$newdir] = $newaseq;
	}
}
?>