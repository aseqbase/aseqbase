<?php
class _ {
	public static $SEQUENCES = null;
	public static $ASEQ = null;
	public static $BASE = null;

	public static $URL = null;
	public static $HOST = null;
	public static $PATH = null;
	public static $QUERY = null;

	public static $CONFIG = null;
	public static $INFO = null;
	public static $TEMPLATE = null;

	public static $ROOT = null;
	public static $DIR = null;

	public static $MODEL_DIR = null;
	public static $VIEW_DIR = null;
	public static $GLOBAL_DIR = null;
	public static $PRIVATE_DIR = null;
	public static $PUBLIC_DIR = null;
	public static $FILE_DIR = null;
	public static $STORAGE_DIR = null;

	public static $LIBRARY_DIR = null;
	public static $COMPONENT_DIR = null;
	public static $TEMPLATE_DIR = null;
	public static $MODULE_DIR = null;

	public static $PAGE_DIR = null;
	public static $REGION_DIR = null;
	public static $PART_DIR = null;
	public static $SCRIPT_DIR = null;
	public static $STYLE_DIR = null;

	public static $BASE_ROOT = null;
	public static $BASE_DIR = null;

	public static $BASE_MODEL_DIR = null;
	public static $BASE_VIEW_DIR = null;
	public static $BASE_GLOBAL_DIR = null;
	public static $BASE_PRIVATE_DIR = null;
	public static $BASE_PUBLIC_DIR = null;
	public static $BASE_FILE_DIR = null;
	public static $BASE_STORAGE_DIR = null;

	public static $BASE_LIBRARY_DIR = null;
	public static $BASE_COMPONENT_DIR = null;
	public static $BASE_TEMPLATE_DIR = null;
	public static $BASE_MODULE_DIR = null;

	public static $BASE_PAGE_DIR = null;
	public static $BASE_REGION_DIR = null;
	public static $BASE_PART_DIR = null;
	public static $BASE_SCRIPT_DIR = null;
	public static $BASE_STYLE_DIR = null;
}

_::$SEQUENCES = $GLOBALS["SEQUENCES"];
_::$ASEQ = $GLOBALS["ASEQ"];
_::$BASE = $GLOBALS["BASE"];

_::$URL = $GLOBALS["URL"] = GetUrl();
_::$HOST = $GLOBALS["HOST"] = GetHost();
_::$PATH = $GLOBALS["PATH"] = GetPath();
_::$QUERY = $GLOBALS["QUERY"] = GetQuery();


_::$BASE_ROOT = $GLOBALS["BASE_ROOT"];
_::$BASE_DIR = $GLOBALS["BASE_DIR"] = __DIR__."/../";

_::$BASE_MODEL_DIR = \_::$BASE_DIR."model/";
_::$BASE_VIEW_DIR = \_::$BASE_DIR."view/";
_::$BASE_GLOBAL_DIR = \_::$BASE_DIR."global/";
_::$BASE_PRIVATE_DIR = \_::$BASE_DIR."private/";
_::$BASE_PUBLIC_DIR = \_::$BASE_DIR."public/";
_::$BASE_STORAGE_DIR = \_::$BASE_DIR."storage/";
_::$BASE_FILE_DIR = \_::$BASE_DIR."file/";
_::$BASE_LIBRARY_DIR = \_::$BASE_MODEL_DIR."library/";
_::$BASE_COMPONENT_DIR = \_::$BASE_MODEL_DIR."component/";
_::$BASE_TEMPLATE_DIR = \_::$BASE_MODEL_DIR."template/";
_::$BASE_MODULE_DIR = \_::$BASE_MODEL_DIR."module/";
_::$BASE_REGION_DIR = \_::$BASE_VIEW_DIR."region/";
_::$BASE_PAGE_DIR = \_::$BASE_VIEW_DIR."page/";
_::$BASE_PART_DIR = \_::$BASE_VIEW_DIR."part/";
_::$BASE_SCRIPT_DIR = \_::$BASE_VIEW_DIR."script/";
_::$BASE_STYLE_DIR = \_::$BASE_VIEW_DIR."style/";


_::$ROOT = $GLOBALS["ROOT"];
_::$DIR = $GLOBALS["DIR"];

_::$MODEL_DIR = \_::$DIR."model/";
_::$VIEW_DIR = \_::$DIR."view/";
_::$GLOBAL_DIR = \_::$DIR."global/";
_::$PRIVATE_DIR = \_::$DIR."private/";
_::$PUBLIC_DIR = \_::$DIR."public/";
_::$FILE_DIR = \_::$DIR."file/";
_::$STORAGE_DIR = \_::$DIR."storage/";
_::$LIBRARY_DIR = \_::$MODEL_DIR."library/";
_::$COMPONENT_DIR = \_::$MODEL_DIR."component/";
_::$TEMPLATE_DIR = \_::$MODEL_DIR."template/";
_::$MODULE_DIR = \_::$MODEL_DIR."module/";
_::$PAGE_DIR = \_::$VIEW_DIR."page/";
_::$REGION_DIR = \_::$VIEW_DIR."region/";
_::$PART_DIR = \_::$VIEW_DIR."part/";
_::$SCRIPT_DIR = \_::$VIEW_DIR."script/";
_::$STYLE_DIR = \_::$VIEW_DIR."style/";


MODEL("Base");

LIBRARY("Local");
LIBRARY("Style");


RUN("global/ConfigurationBase");
RUN("global/Configuration");
_::$CONFIG = $GLOBALS["CONFIG"] = new Configuration();
ini_set('display_errors', \_::$CONFIG->DisplayError);
ini_set('display_startup_errors', \_::$CONFIG->DisplayStartupError);
error_reporting(\_::$CONFIG->ReportError);

RUN("global/InformationBase");
RUN("global/Information");
_::$INFO = $GLOBALS["INFO"] = new Information();

RUN("global/TemplateBase");
RUN("global/Template");
_::$TEMPLATE = $GLOBALS["TEMPLATE"] = new Template();


function ACCESS($access=0, $showPage = true){
	if(isValid(\_::$CONFIG->AccessMode)) {
		$ip = GetClientIP();
		$cip = false;
		foreach(\_::$CONFIG->AccessPatterns as $pat) if($cip = preg_match($pat, $ip)) break;
		if((\_::$CONFIG->AccessMode > 0 && !$cip) || (\_::$CONFIG->AccessMode < 0 && $cip)){
			if($showPage) VIEW(\_::$TEMPLATE->RestrictionViewName??"restriction",$_GET);
			return false;
		}
	}
	elseif(isValid(\_::$CONFIG->StatusMode)) {
		if($showPage) VIEW(\_::$CONFIG->StatusMode,$_GET)??VIEW(\_::$TEMPLATE->RestrictionViewName??"restriction",$_GET);
		return false;
	}
	return true ||
		\_::$INFO->User->Access($access) ||
		(isset($_SESSION["access"]) && $_SESSION["access"] == $access);
}

function SET($output = null){
	print trim($output);
	return $output;
}
function GET($input = null){
	return $input;
}

function INCLUDING($filePath, $variables = array(), $print = true){
	global $CONFIG, $TEMPLATE, $INFO, $ASEQ, $BASE, $DIR, $BASE_DIR, $ROOT, $BASE_ROOT, $URL, $HOST, $PATH, $QUERY;
	if(file_exists($filePath)){
		//if(count($variables) > 0) $filePath = rtrim($filePath,"/")."?". http_build_query($variables);
		if(count($variables) > 0) extract($variables);
		ob_start();
		include_once $filePath;
		$output = ob_get_clean();
		if ($print) return SET($output)??true;
		return $output??true;
	}
	return null;
}
function REQUIRING($filePath, $variables = array(), $print = true){
	global $CONFIG, $TEMPLATE, $INFO, $ASEQ, $BASE, $DIR, $BASE_DIR, $ROOT, $BASE_ROOT, $URL, $HOST, $PATH, $QUERY;
	if(file_exists($filePath)){
		if(count($variables) > 0) extract($variables);
		ob_start();
		require_once $filePath;
		$output = ob_get_clean();
		if ($print) return SET($output)??true;
		return $output??true;
	}
	return null;
}
function USING($dir,$name = null, $variables = array(), $print = true, $format = ".php"){
	if(empty($name))
		if(isFormat($dir, $format)) return INCLUDING($dir, $variables, $print);
		else return INCLUDING($dir.$format, $variables, $print);
	elseif(isFormat($name, $format)) return INCLUDING($dir.$name, $variables, $print);
	else return INCLUDING($dir.$name.$format, $variables, $print);
}

function forceUSING($nodeDir, $baseDir, $name, $variables = array(), $print = true){
	if(($seq = USING($nodeDir,$name, $variables, $print)) !== null) return $seq;
	if(count(\_::$SEQUENCES) > 0){
		$dir = substr($nodeDir, strlen(\_::$DIR));
		foreach(\_::$SEQUENCES as $aseq=>$root)
			if(($seq = USING($aseq.$dir,$name, $variables, $print)) !== null)
				return $seq;
	}
	if(($seq = USING($baseDir,$name, $variables, $print)) !== null) return $seq;
	return null;
}

function RUN($name, $variables = array(), $print = true){
	return forceUSING(\_::$DIR, \_::$BASE_DIR, $name, $variables, $print);
}
function MODEL($name, $variables = array(), $print = true){
	return forceUSING(\_::$MODEL_DIR, \_::$BASE_MODEL_DIR, $name, $variables, $print);
}
function VIEW($name, $variables = array(), $print = true){
	$output = forceUSING(\_::$VIEW_DIR, \_::$BASE_VIEW_DIR, $name, $variables, false);
	if($print) return SET(ReduceSize($output));
	else return $output;
}
function LIBRARY($name, $variables = array(), $print = true){
	return forceUSING(\_::$LIBRARY_DIR, \_::$BASE_LIBRARY_DIR, $name, $variables, $print);
}
function COMPONENT($name, $variables = array(), $print = true){
	return forceUSING(\_::$COMPONENT_DIR, \_::$BASE_COMPONENT_DIR, $name, $variables, $print);
}
function MODULE($name, $variables = array(), $print = true){
	return forceUSING(\_::$MODULE_DIR, \_::$BASE_MODULE_DIR, $name, $variables, $print);
}
function TEMPLATE($name, $variables = array(), $print = true){
	return forceUSING(\_::$TEMPLATE_DIR, \_::$BASE_TEMPLATE_DIR, $name, $variables, $print);
}
function PAGE($name, $variables = array(), $print = true){
	return forceUSING(\_::$PAGE_DIR, \_::$BASE_PAGE_DIR, $name, $variables, $print);
}
function REGION($name, $variables = array(), $print = true){
	return forceUSING(\_::$REGION_DIR, \_::$BASE_REGION_DIR, $name, $variables, $print);
}
function PART($name, $variables = array(), $print = true){
	return forceUSING(\_::$PART_DIR, \_::$BASE_PART_DIR, $name, $variables, $print);
}

function __($text,$translate = true, $styling = true){
	if($styling) $text = \MiMFa\Library\Style::DoStrong($text);
	return $text;
}

function startsWith($haystack, $needle) {
	return substr_compare($haystack, $needle, 0, strlen($needle)) === 0;
}
function endsWith($haystack, $needle) {
	return substr_compare($haystack, $needle, -strlen($needle)) === 0;
}

function forceUrl($path){
	return \MiMFa\Library\Local::GetUrl($path);
}
function forceUrls($items){
	$c = count($items);
	if($c > 0)
		if(is_array($items[0])){
			for($i = 0; $i < $c; $i++){
				if(isset($items[$i]["Source"])) $items[$i]["Source"] = \MiMFa\Library\Local::GetUrl($items[$i]["Source"]);
				if(isset($items[$i]["Image"])) $items[$i]["Image"] = \MiMFa\Library\Local::GetUrl($items[$i]["Image"]);
				if(isset($items[$i]["Url"])) $items[$i]["Url"] = \MiMFa\Library\Local::GetUrl($items[$i]["Url"]);
			}
		}
		else for($i = 0; $i < $c; $i++) $items[$i] = \MiMFa\Library\Local::GetUrl($items[$i]);
	return $items;
}
function forcePath($path){
	return \MiMFa\Library\Local::GetPath($path);
}

function getId()
{
	list($usec, $sec) = explode(" ", microtime());
	return (int)($sec*10000000+$usec*10000000);
}

function getDomainUrl()
{
	// server protocol
	$protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';
	// domain name
	$domain = $_SERVER['SERVER_NAME'];

	// server port
	$port = $_SERVER['SERVER_PORT'];
	$disp_port = ($protocol == 'http' && $port == 80 || $protocol == 'https' && $port == 443) ? '' : ":$port";

	// put em all together to get the complete base URL
	return $protocol."://".$domain.$disp_port;
}

function getFullUrl($path = null){
	if($path == null) $path = getUrl();
	if(\_::$CONFIG->AllowCache || strpos($path,"?")>0) return $path;
	else return $path."?v=".date(\_::$CONFIG->CachePeriod);
}
function getUrl($path = null){
	if($path == null) $path = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"";
	return preg_replace("/^[\/\\\]/",rtrim(GetHost(),"/\\")."$1",$path);
}
function getHost($path = null){
	$pat = "/^\w+\:\/*[^\/]+/";
	if($path == null || !preg_match($pat,$path)) $path = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'];
	return PREG_Find($pat, $path);
}
function getDirection($path = null){
	if($path == null) $path = "/";
	if(startsWith($path,\_::$BASE_DIR)) return substr($path, strlen(\_::$BASE_DIR));
	return PREG_Replace("/^\w+\:\/*[^\/]+/","", $path);
}
function getPath($path = null){
	return PREG_Find("/(^[^\?]*)/", $path??getUrl());
}
function getQuery($path = null){
	return PREG_Find("/((?<=\?).*$)/", $path??getUrl());
}

function getClientIP(){
	foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
		if (array_key_exists($key, $_SERVER) === true){
			foreach (explode(',', $_SERVER[$key]) as $ip){
				$ip = trim($ip); // just to be safe
				if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
					return $ip;
				}
			}
		}
	}
}

function getValue($source, $key, $ismultiline = true){
	$arr = is_array($source)? $source : explode("
", $source);
	$f = false;
	$res = "";
	foreach($arr as $i => $line) {
		$line = trim($line);
		if(strpos($line,$key)===0){
			$res = trim(substr($line, strlen($key)));
			$f = $ismultiline;
			if(!$f) break;
		} elseif($f) {
			if(strpos($line,"	")===0) $res .= "
	".trim($line);
			else break;
		}
	}
	return trim($res);
}

function isValid($obj,$item = null){
	if($item === null) return isset($obj) && $obj !== null && !empty($obj);
	else return isset($obj) && isset($item) && isset($obj[$item]) && $obj[$item] !== null && !empty($obj[$item]);
}
function isEmpty($text){
	return !isset($text) || trim($text."") == "" || trim($text."","'\"") == "";
}
function isFormat($path, $format){
	return endsWith(getPath(strtolower($path)), $format);
}
function isAbsoluteUrl($path){
	return $path != null && preg_match("/^\w+\:\/*[^\/]+/",$path);
}

function normalizePath($path){
	return preg_replace("/([\/\\\]\.+)|(\.+[\/\\\])/","",$path??getUrl());
}

function randomString($length = 10, $chars = '_0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
	return substr(str_shuffle(str_repeat($chars, ceil($length/strlen($chars)) )),1,$length);
}

function reduceSize($page){
	$ls = array();
	$pat ="/\<\s*(style)[\s\S]*\>[\s\S]*\<\\/\s*\\1\s*\>/ixU";
	//$pat ="/\<\s*((style)|(script))[\s\S]*\>[\s\S]*\<\\/\s*\\1\s*\>/ixU";
	$matches = null;
	if(preg_match_all($pat, $page, $matches)){
		foreach($matches[0] as $item)
			if(!in_array($item, $ls)) array_push($ls, preg_replace("/\s+/im", " ", $item));
		//echo count($ls);
		$page = preg_replace($pat, "", $page);
		$page = preg_replace("/\<\\/\s*head\s*\>/im", implode(PHP_EOL, $ls).PHP_EOL."</head>", $page);
		//$page = preg_replace("/(?<!(\<\s*(script)[\s\S]*\>[\s\S]+))\s+(?!([\s\S]+\<\\/\s*\\2\s*\>))/ixU", " ", $page);
		return preg_replace("/\<\s*\\/\s*(style)\s*\>\s*\<\s*\\1\s*\>/ixU", PHP_EOL, $page);
	}
	return $page;
}

//Regular Expression
function preg_find($pattern, $text, $def = null){
	$matches = preg_find_All($pattern, $text);
	return isset($matches[0])?$matches[0]:$def;
}
function preg_find_all($pattern, $text){
	preg_match($pattern, $text, $matches);
	return $matches;
}

//Test Region
function echoDetails(){
	echo "<br>"."PHP_SELF: ".$_SERVER['PHP_SELF'];
	echo "<br>"."GATEWAY_INTERFACE: ".$_SERVER['GATEWAY_INTERFACE'];
	echo "<br>"."SERVER_ADDR: ".$_SERVER['SERVER_ADDR'];
	echo "<br>"."SERVER_NAME: ".$_SERVER['SERVER_NAME'];
	echo "<br>"."SERVER_SOFTWARE: ".$_SERVER['SERVER_SOFTWARE'];
	echo "<br>"."SERVER_PROTOCOL: ".$_SERVER['SERVER_PROTOCOL'];
	echo "<br>"."REQUEST_METHOD: ".$_SERVER['REQUEST_METHOD'];
	echo "<br>"."REQUEST_TIME: ".$_SERVER['REQUEST_TIME'];
	echo "<br>"."QUERY_STRING: ".$_SERVER['QUERY_STRING'];
	echo "<br>"."HTTP_ACCEPT: ".$_SERVER['HTTP_ACCEPT'];
	echo "<br>"."HTTP_ACCEPT_CHARSET: ".$_SERVER['HTTP_ACCEPT_CHARSET'];
	echo "<br>"."HTTP_HOST: ".$_SERVER['HTTP_HOST'];
	echo "<br>"."HTTP_REFERER: ".$_SERVER['HTTP_REFERER'];
	echo "<br>"."HTTPS: ".$_SERVER['HTTPS'];
	echo "<br>"."REMOTE_ADDR: ".$_SERVER['REMOTE_ADDR'];
	echo "<br>"."REMOTE_HOST: ".$_SERVER['REMOTE_HOST'];
	echo "<br>"."REMOTE_PORT: ".$_SERVER['REMOTE_PORT'];
	echo "<br>"."SCRIPT_FILENAME: ".$_SERVER['SCRIPT_FILENAME'];
	echo "<br>"."SERVER_ADMIN: ".$_SERVER['SERVER_ADMIN'];
	echo "<br>"."SERVER_PORT: ".$_SERVER['SERVER_PORT'];
	echo "<br>"."SERVER_SIGNATURE: ".$_SERVER['SERVER_SIGNATURE'];
	echo "<br>"."PATH_TRANSLATED: ".$_SERVER['PATH_TRANSLATED'];
	echo "<br>"."SCRIPT_NAME: ".$_SERVER['SCRIPT_NAME'];
	echo "<br>"."SCRIPT_URI: ".$_SERVER['SCRIPT_URI'];
}
function test($func,$res=null){
	$r = null;
	if(ACCESS(0, false)){
		if($r = $func()) echo "<b>TRUE: ".($r??$res)."</b><br>";
		else echo "FALSE: ".$res."<br>";
	}
}
//End Test Region
?>