<?php /* MiMFa aseqbase	http://aseqbase.ir */
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//ini_set('display_startup_errors', E_ALL);

/*
 * Change the value, to the current subdomains sequence (like [my-subdomain-name])
 * or if this file is in the root address, leave null for that
 */
$directories = preg_split("/[\/\\\]/", trim(__DIR__, "/\\"));
$GLOBALS["ASEQ"] = end($directories);/* Change it to null if the file is in the root directory */
$GLOBALS["BASE"] = ".base";/* Change it to the parent directory if deferents */

/*
    Change \_::$Sequences
	newdirectory, newaseq;// Add new directory to the \_::$Sequences
    directory, newaseq;// Update directory in the \_::$Sequences
    directory, null;// Remove thw directory from the \_::$Sequences
*/
$GLOBALS["SEQUENCES_PATCH"] = array();

/* Don't change the codes below: */
$directory = __DIR__.DIRECTORY_SEPARATOR;
if(realpath($directory."global.php")) include_once $directory."global.php";
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

require_once($GLOBALS["BASE_DIR"]."index.php");