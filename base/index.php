<?php //MiMFa aseqbase	http://aseqbase.ir
require_once(__DIR__."/initialize.php");
if(ACCESS()){
	if(isset($_GET[\_::$CONFIG->PathKey])) VIEW(\_::$TEMPLATE->ViewName??\_::$TEMPLATE->DefaultViewName??"main",$_GET);
	else VIEW(\_::$TEMPLATE->HomeViewName);
}
?>