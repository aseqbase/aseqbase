<?php
class _ {
	/**
     * The top domain name
     * @var string
     */
	public static string|null $ASEQ = null;
	/**
	 * All sequences between $ASEQBASE and $BASE
	 * @var array
	 */
	public static array|null $SEQUENCES = null;
	/**
	 * The base directory and the end point of all sequences
	 * @var string|null
     */
	public static string|null $BASE = null;
	/**
	 * Number of subdomains nestings
     * @var int|null
	 */
	public static int|null $NEST = null;
	/**
	 * The version of aseqbase framework
     * Generation	.	Major	Minor	0:basic/1:alpha/2:beta/5:stable
     * X		.	xx	xx	x
	 * @var float
	 */
	public static float $VERSION = 1.00001;

	public static string|null $URL = null;
	public static string|null $HOST = null;
	public static string|null $PATH = null;
	public static string|null $QUERY = null;

	public static ConfigurationBase|null $CONFIG = null;
	public static InformationBase|null $INFO = null;
	public static TemplateBase|null $TEMPLATE = null;

	public static string|null $ROOT = null;
	public static string|null $DIR = null;

	public static string|null $MODEL_DIR = null;
	public static string|null $VIEW_DIR = null;
	public static string|null $PRIVATE_DIR = null;
	public static string|null $PUBLIC_DIR = null;
	public static string|null $FILE_DIR = null;
	public static string|null $STORAGE_DIR = null;
	public static string|null $TMP_DIR = null;
	public static string|null $LOG_DIR = null;

	public static string|null $LIBRARY_DIR = null;
	public static string|null $COMPONENT_DIR = null;
	public static string|null $TEMPLATE_DIR = null;
	public static string|null $MODULE_DIR = null;

	public static string|null $PAGE_DIR = null;
	public static string|null $REGION_DIR = null;
	public static string|null $PART_DIR = null;
	public static string|null $SCRIPT_DIR = null;
	public static string|null $STYLE_DIR = null;

	public static string|null $BASE_ROOT = null;
	public static string|null $BASE_DIR = null;

	public static string|null $BASE_MODEL_DIR = null;
	public static string|null $BASE_VIEW_DIR = null;
	public static string|null $BASE_PRIVATE_DIR = null;
	public static string|null $BASE_PUBLIC_DIR = null;
	public static string|null $BASE_FILE_DIR = null;
	public static string|null $BASE_STORAGE_DIR = null;

	public static string|null $BASE_LIBRARY_DIR = null;
	public static string|null $BASE_COMPONENT_DIR = null;
	public static string|null $BASE_TEMPLATE_DIR = null;
	public static string|null $BASE_MODULE_DIR = null;

	public static string|null $BASE_PAGE_DIR = null;
	public static string|null $BASE_REGION_DIR = null;
	public static string|null $BASE_PART_DIR = null;
	public static string|null $BASE_SCRIPT_DIR = null;
	public static string|null $BASE_STYLE_DIR = null;

}

_::$SEQUENCES = $GLOBALS["SEQUENCES"];
_::$ASEQ = $GLOBALS["ASEQBASE"];
_::$BASE = $GLOBALS["BASE"];
_::$NEST = $GLOBALS["NEST"];

_::$URL = $GLOBALS["URL"] = GetUrl();
_::$HOST = $GLOBALS["HOST"] = GetHost();
_::$PATH = $GLOBALS["PATH"] = GetPath();
_::$QUERY = $GLOBALS["QUERY"] = GetQuery();


_::$BASE_ROOT = $GLOBALS["BASE_ROOT"];
_::$BASE_DIR = $GLOBALS["BASE_DIR"] = __DIR__."/";

_::$BASE_MODEL_DIR = \_::$BASE_DIR."model/";
_::$BASE_VIEW_DIR = \_::$BASE_DIR."view/";
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
_::$PRIVATE_DIR = \_::$DIR."private/";
_::$PUBLIC_DIR = \_::$DIR."public/";
_::$FILE_DIR = \_::$DIR."file/";
_::$STORAGE_DIR = \_::$DIR."storage/";
_::$TMP_DIR = \_::$DIR."tmp/";
_::$LOG_DIR = \_::$DIR."log/";
_::$LIBRARY_DIR = \_::$MODEL_DIR."library/";
_::$COMPONENT_DIR = \_::$MODEL_DIR."component/";
_::$TEMPLATE_DIR = \_::$MODEL_DIR."template/";
_::$MODULE_DIR = \_::$MODEL_DIR."module/";
_::$PAGE_DIR = \_::$VIEW_DIR."page/";
_::$REGION_DIR = \_::$VIEW_DIR."region/";
_::$PART_DIR = \_::$VIEW_DIR."part/";
_::$SCRIPT_DIR = \_::$VIEW_DIR."script/";
_::$STYLE_DIR = \_::$VIEW_DIR."style/";

RUN("global/Base.php");

LIBRARY("Local");
LIBRARY("DataBase");
LIBRARY("Style");

RUN("global/ConfigurationBase.php");
RUN("Configuration.php");
_::$CONFIG = new Configuration();
ini_set('display_errors', \_::$CONFIG->DisplayError);
ini_set('display_startup_errors', \_::$CONFIG->DisplayStartupError);
error_reporting(\_::$CONFIG->ReportError);

RUN("global/InformationBase.php");
RUN("Information.php");
_::$INFO = new Information();

RUN("global/TemplateBase.php");
RUN("Template.php");
_::$TEMPLATE = new Template();

\MiMFa\Library\Local::CreateDirectory(\_::$TMP_DIR);
\MiMFa\Library\Local::CreateDirectory(\_::$LOG_DIR);

function ACCESS($access=0, bool $showPage = true):bool{
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
	if(is_string($input) && isAbsoluteUrl($input)){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $input);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        return curl_exec($ch);
    }
	return $input;
}

function INCLUDING(string|null $filePath, array $variables = array(), bool $print = true){
	global $ASEQ, $BASE, $DIR, $BASE_DIR, $ROOT, $BASE_ROOT, $URL, $HOST, $PATH, $QUERY;
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
function REQUIRING(string|null $filePath, array $variables = array(), bool $print = true){
	global $ASEQ, $BASE, $DIR, $BASE_DIR, $ROOT, $BASE_ROOT, $URL, $HOST, $PATH, $QUERY;
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
function USING(string|null $dir, string|null $name = null, array $variables = array(), bool $print = true, string|null $format = ".php"){
	if(empty($name))
		if(isFormat($dir, $format)) return INCLUDING($dir, $variables, $print);
		else return INCLUDING($dir.$format, $variables, $print);
	elseif(isFormat($name, $format)) return INCLUDING($dir.$name, $variables, $print);
	else return INCLUDING($dir.$name.$format, $variables, $print);
}

function forceUSING(string|null $nodeDir, string|null $baseDir, string|null $name, array $variables = array(), bool $print = true){
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

function RUN(string|null $name, array $variables = array(), bool $print = true){
	return forceUSING(\_::$DIR, \_::$BASE_DIR, $name, $variables, $print);
}
function MODEL(string|null $name, array $variables = array(), bool $print = true){
	return forceUSING(\_::$MODEL_DIR, \_::$BASE_MODEL_DIR, $name, $variables, $print);
}
function VIEW(string|null $name, array $variables = array(), bool $print = true){
	$output = forceUSING(\_::$VIEW_DIR, \_::$BASE_VIEW_DIR, $name, $variables, false);
	if($print) return SET(ReduceSize($output));
	else return $output;
}
function LIBRARY(string|null $name, array $variables = array(), bool $print = true){
	return forceUSING(\_::$LIBRARY_DIR, \_::$BASE_LIBRARY_DIR, $name, $variables, $print);
}
function COMPONENT(string|null $name, array $variables = array(), bool $print = true){
	return forceUSING(\_::$COMPONENT_DIR, \_::$BASE_COMPONENT_DIR, $name, $variables, $print);
}
function MODULE(string|null $name, array $variables = array(), bool $print = true){
	return forceUSING(\_::$MODULE_DIR, \_::$BASE_MODULE_DIR, $name, $variables, $print);
}
function TEMPLATE(string|null $name, array $variables = array(), bool $print = true){
	return forceUSING(\_::$TEMPLATE_DIR, \_::$BASE_TEMPLATE_DIR, $name, $variables, $print);
}
function PAGE(string|null $name, array $variables = array(), bool $print = true){
	return forceUSING(\_::$PAGE_DIR, \_::$BASE_PAGE_DIR, $name, $variables, $print);
}
function REGION(string|null $name, array $variables = array(), bool $print = true){
	return forceUSING(\_::$REGION_DIR, \_::$BASE_REGION_DIR, $name, $variables, $print);
}
function PART(string|null $name, array $variables = array(), bool $print = true){
	return forceUSING(\_::$PART_DIR, \_::$BASE_PART_DIR, $name, $variables, $print);
}

function __(string|null $text, bool $translate = true, bool $styling = true):string|null {
	if($styling) $text = \MiMFa\Library\Style::DoStrong($text);
	return $text;
}

function startsWith(string|null $haystack, string|null $needle):bool {
	return substr_compare($haystack, $needle, 0, strlen($needle)) === 0;
}
function endsWith(string|null $haystack, string|null $needle):bool {
	return substr_compare($haystack, $needle, -strlen($needle)) === 0;
}

function forceUrl(string|null $path){
	return \MiMFa\Library\Local::GetUrl($path);
}
function forceUrls($items):array{
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
function forcePath(string|null $path):string|null{
	return \MiMFa\Library\Local::GetPath($path);
}

function getId():int
{
	list($usec, $sec) = explode(" ", microtime());
	return (int)($sec*10000000+$usec*10000000);
}

function getDomainUrl():string|null
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

function getFullUrl(string|null $path = null):string|null{
	if($path == null) $path = getUrl();
	if(\_::$CONFIG->AllowCache || strpos($path,"?")>0) return $path;
	else return $path."?v=".date(\_::$CONFIG->CachePeriod);
}
function getUrl(string|null $path = null):string|null{
	if($path == null) $path = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"";
	return preg_replace("/^[\/\\\]/",rtrim(GetHost(),"/\\")."$1",$path);
}
function getHost(string|null $path = null):string|null{
	$pat = "/^\w+\:\/*[^\/]+/";
	if($path == null || !preg_match($pat,$path)) $path = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'];
	return PREG_Find($pat, $path);
}
function getDirection(string|null $path = null):string|null{
	if($path == null) $path = "/";
	if(startsWith($path,\_::$BASE_DIR)) return substr($path, strlen(\_::$BASE_DIR));
	return PREG_Replace("/^\w+\:\/*[^\/]+/","", $path);
}
function getPath(string|null $path = null):string|null{
	return PREG_Find("/(^[^\?]*)/", $path??getUrl());
}
function getQuery(string|null $path = null):string|null{
	return PREG_Find("/((?<=\?).*$)/", $path??getUrl());
}

function getClientIP():string|null{
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
	return null;
}

function getValue(string|null $source, string|null $key, bool $ismultiline = true){
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

function isASEQ(string|null $directory):bool{
	return !\MiMFa\Library\Local::FileExists($directory."global/ConfigurationBase.php")
		&& \MiMFa\Library\Local::FileExists($directory."global.php")
		&& \MiMFa\Library\Local::FileExists($directory."Information.php")
		&& \MiMFa\Library\Local::FileExists($directory."initialize.php")
		&& \MiMFa\Library\Local::FileExists($directory."static.php");
}
function isBASE(string|null $directory):bool{
	return \MiMFa\Library\Local::FileExists($directory."Configuration.php")
		&& \MiMFa\Library\Local::FileExists($directory."global/ConfigurationBase.php")
		&& \MiMFa\Library\Local::FileExists($directory."Information.php")
		&& \MiMFa\Library\Local::FileExists($directory."global/InformationBase.php")
		&& \MiMFa\Library\Local::FileExists($directory."Template.php")
		&& \MiMFa\Library\Local::FileExists($directory."global/TemplateBase.php")
		&& \MiMFa\Library\Local::FileExists($directory."global.php")
		&& \MiMFa\Library\Local::FileExists($directory."initialize.php")
		&& \MiMFa\Library\Local::FileExists($directory."static.php");
}
function isValid($obj, string|null $item = null):bool{
	if($item === null) return isset($obj) && $obj !== null && !empty($obj);
	else return isset($obj) && isset($item) && isset($obj[$item]) && $obj[$item] !== null && !empty($obj[$item]);
}
function isEmpty($text):bool{
	return !isset($text) || trim($text."") == "" || trim($text."","'\"") == "";
}
function isFormat(string|null $path, string|null $format):bool{
	return endsWith(getPath(strtolower($path)), $format);
}
function isAbsoluteUrl(string|null $path):bool{
	return $path != null && preg_match("/^\w+\:\/*[^\/]+/",$path);
}


/**
 * Remove all changeable command signs from a path (such as ../ or /./.)
 * @param string|null $path The source path
 * @return array|string|null
 */
function normalizePath(string|null $path):string|null{
	return preg_replace("/([\/\\\]\.+)|(\.+[\/\\\])/","",$path??getUrl());
}

/**
 * Create a random string|null with a custom length
 * @param int $length Custom length of destination string|null
 * @param string|null $chars Allowable characters
 * @return string|null
 */
function randomString(int $length = 10, string|null $chars = '_0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'):string|null {
	return substr(str_shuffle(str_repeat($chars, ceil($length/strlen($chars)) )),1,$length);
}

/**
 * Compress and reduce the size of document
 * @param string|null $page The source document
 * @return array|string|null
 */
function reduceSize(string|null $page):string|null{
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

/**
 * Regular Expression Find first match by pattern
 * @param mixed $pattern
 * @param string|null $text
 * @param string|null $def
 * @return mixed
 */
function preg_find($pattern,string|null $text,string|null $def = null):string|null{
	$matches = preg_find_All($pattern, $text);
	return isset($matches[0])?$matches[0]:$def;
}
/**
 * Regular Expression Find all matches by pattern
 * @param mixed $pattern
 * @param string|null $text
 * @return array|null
 */
function preg_find_all($pattern, string|null $text):array|null{
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
