<?php //MiMFa aseqbase	http://aseqbase.ir
require_once(__DIR__."/initialize.php");
if(ACCESS()){
	if(isset($_REQUEST[\_::$CONFIG->PathKey])){
		foreach (\_::$CONFIG->Handlers as $pat=>$handler)
            if(preg_match($pat, $_REQUEST[\_::$CONFIG->PathKey])
				&& VIEW($handler, $_REQUEST)) return;
		VIEW(\_::$CONFIG->ViewName??\_::$CONFIG->DefaultViewName??"main",$_REQUEST);
    } else VIEW(\_::$CONFIG->HomeViewName);
}
?>