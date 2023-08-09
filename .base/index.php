<?php //MiMFa aseqbase	http://aseqbase.ir
require_once(__DIR__."/initialize.php");
if(ACCESS()){
	if(isValid(\_::$REQUEST)){
        $request = ltrim(\_::$REQUEST," \r\n\t\v\0\\/");
		foreach (\_::$CONFIG->Handlers as $pat=>$handler)
            if(preg_match($pat, $request)
				&& VIEW($handler, $_REQUEST)) return;
		VIEW(\_::$CONFIG->ViewName??\_::$CONFIG->DefaultViewName??"main",$_REQUEST);
    } else VIEW(\_::$CONFIG->HomeViewName, $_REQUEST);
}
?>