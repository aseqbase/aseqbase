<?php //MiMFa aseqbase	http://aseqbase.ir
require_once(__DIR__."/initialize.php");
if("/".\_::$DIRECTION == MiMFa\Library\User::$InHandlerPath ||
	ACCESS(\_::$CONFIG->VisitAccess, assign:true, die:true)){
	if(isValid(\_::$REQUEST))
		if(isset($_REQUEST[\_::$CONFIG->ViewHandlerKey]))
            VIEW($_REQUEST[\_::$CONFIG->ViewHandlerKey],$_REQUEST);
        else{
			$request = ltrim(\_::$REQUEST," \r\n\t\v\f\\/");
            foreach (\_::$CONFIG->Handlers as $pat=>$handler)
                if(preg_match($pat, $request)
                    && VIEW($handler, $_REQUEST)) return;
            VIEW(\_::$CONFIG->ViewName??\_::$CONFIG->DefaultViewName??"main",$_REQUEST);
        }
    else VIEW(\_::$CONFIG->HomeViewName, $_REQUEST);
}
?>