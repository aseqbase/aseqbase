<?php //MiMFa aseqbase	http://aseqbase.ir
require_once(__DIR__."/initialize.php");
if(ACCESS()){
	if(isValid(\_::$DIRECTION)){
		foreach (\_::$CONFIG->Handlers as $pat=>$handler)
            if(preg_match($pat, \_::$DIRECTION)
				&& VIEW($handler, $_REQUEST)) return;
		VIEW(\_::$CONFIG->ViewName??\_::$CONFIG->DefaultViewName??"main",$_REQUEST);
    } else VIEW(\_::$CONFIG->HomeViewName);
}
?>