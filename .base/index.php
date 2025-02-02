<?php //MiMFa aseqbase	http://aseqbase.ir
require_once(__DIR__."/initialize.php");
if(
    startsWith(DIRECTORY_SEPARATOR.\_::$DIRECTION, MiMFa\Library\User::$HandlerPath) ||
	ACCESS(\_::$CONFIG->VisitAccess, assign:true, die:true)) {
	if(isValid(\_::$REQUEST))
		if(isset($_REQUEST[\_::$CONFIG->ViewHandlerKey]))
            VIEW($_REQUEST[\_::$CONFIG->ViewHandlerKey], variables:$_REQUEST);
        else {
			$request = \_::$REQUEST;
            if(\_::$CONFIG->Router->Handle($request)) return;
            foreach (\_::$CONFIG->Handlers as $pat=>$handler)
                if(preg_match($pat, $request) && \_::$CONFIG->Router->View($handler))
                    return;
            VIEW(\_::$CONFIG->ViewName??\_::$CONFIG->DefaultViewName??"main", variables:$_REQUEST);
        }
    else VIEW(\_::$CONFIG->HomeViewName, variables:$_REQUEST);
}
?>