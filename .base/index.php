<?php //MiMFa aseqbase	http://aseqbase.ir
require_once(__DIR__."/initialize.php");
if("/".\_::$DIRECTION == MiMFa\Library\User::$InHandlerPath ||
	ACCESS(\_::$CONFIG->VisitAccess, assign:true, die:true)){
	if(isValid(\_::$REQUEST)){
        $request = ltrim(\_::$REQUEST," \r\n\t\v\0\f\\/");
		foreach (\_::$CONFIG->Handlers as $pat=>$handler)
            if(preg_match($pat, $request)
				&& VIEW($handler, $_REQUEST)) return;
		VIEW(\_::$CONFIG->ViewName??\_::$CONFIG->DefaultViewName??"main",$_REQUEST);
    } else VIEW(\_::$CONFIG->HomeViewName, $_REQUEST);
}
?>