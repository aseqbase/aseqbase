<?php //MiMFa aseqbase	http://aseqbase.ir
require_once(__DIR__."/initialize.php");
if(ACCESS()){
	if(isset($_GET[\_::$CONFIG->PathKey])){
		foreach (\_::$CONFIG->Handlers as $pat=>$handler)
            if(preg_match($pat, $_GET[\_::$CONFIG->PathKey])
				&& VIEW($handler, $_GET)) return;
		VIEW(\_::$CONFIG->ViewName??\_::$CONFIG->DefaultViewName??"main",$_GET);
    } else VIEW(\_::$CONFIG->HomeViewName);
}
?>