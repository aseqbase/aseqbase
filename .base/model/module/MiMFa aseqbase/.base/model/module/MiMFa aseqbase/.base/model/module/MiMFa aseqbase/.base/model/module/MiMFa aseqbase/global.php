<?php
//Convert sub domains to sub directories
$aseq = $GLOBALS["NEST"]>0?preg_replace("/(?<=\S|\s)\.(?=\S|\s)/", "/", $GLOBALS["ASEQ"])."/":"";
//Don't change the codes below:
if(isset($GLOBALS["DIR"])) $GLOBALS["SEQUENCES"][__DIR__."/"] = $GLOBALS["HOST"]."/".$aseq;
else {
	$GLOBALS["ASEQBASE"] = $GLOBALS["ASEQ"];
	$GLOBALS["SEQUENCES"] = array();
	$GLOBALS["ROOT"] = $GLOBALS["HOST"]."/".$aseq;
	$GLOBALS["DIR"] = __DIR__."/";
}

//Filter the sequences
if($GLOBALS["ASEQ_Limitation"] !== 0){
	$sequences = $GLOBALS["SEQUENCES"];
	foreach($sequences as $dir => $aseq){
		$b = false;
		foreach($GLOBALS["ASEQ_Patterns"] as $pat)
			if($b = preg_match($pat, $aseq)) break;
		if((!$b && $GLOBALS["ASEQ_Limitation"] > 0) || ($b && $GLOBALS["ASEQ_Limitation"] < 0))
			unset($GLOBALS["SEQUENCES"][$dir]);
	}
}
?>