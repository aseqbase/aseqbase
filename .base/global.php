<?php
/**
 * The Global Static Class
 * It contains the most useful objects along developments
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/mimfa/aseqbase
 *@link https://github.com/mimfa/aseqbase/wiki/Globals See the Documentation
 */
class _ {
	/**
	 * The version of aseqbase framework
     * Generation	.	Major	Minor	0:basic|1:alpha|2:beta|5<=9:stable
     * X		.	xx	xx	x
	 * @var float
	 */
	public static float $VERSION = 1.00006;

	/**
     * The top domain name
     * Example: "mimfa"
     * @var string
     */
	public static string|null $ASEQ = null;
	/**
	 * The base directory and the end point of all sequences
     * Example: ".base"
	 * @var string|null
     */
	public static string|null $BASE = null;
	
	/**
	 * All sequences
     * Example: "[
     *	'home/mimfa/mimfa/' => 'https://mimfa.net/',
     *	'home/mimfa/seq1/' => 'https://seq1.mimfa.net/',
     *	'home/mimfa/seq2/' => 'https://seq2.mimfa.net/',
     *	'home/mimfa/seq3/' => 'https://seq3.mimfa.net/',
     *	'home/mimfa/.base/' => 'https://mimfa.net/.base/',
     *]"
	 * @var array
	 */
	public static array|null $SERIES = null;
	/**
	 * All sequences between $ASEQ and $BASE
     * Example: "[
     *	'home/mimfa/seq1/' => 'https://seq1.mimfa.net/',
     *	'home/mimfa/seq2/' => 'https://seq2.mimfa.net/',
     *	'home/mimfa/seq3/' => 'https://seq3.mimfa.net/',
     *]"
	 * @var array
	 */
	public static array|null $SEQUENCES = null;
	/**
	 * Number of subdomains nestings
     * @var int|null
	 */
	public static int|null $NEST = null;

	/**
	 * Full part of the current url
     * Example: "https://mimfa.net/Category/mimfa/service/web?p=3&l=10#serp"
	 * @var string|null
	 */
	public static string|null $URL = null;
	/**
     * The path part of the current url
     * Example: "https://mimfa.net/Category/mimfa/service/web"
     * @var string|null
     */
	public static string|null $PATH = null;
	/**
     * The host part of the current url
     * Example: "https://mimfa.net"
     * @var string|null
     */
	public static string|null $HOST = null;
	/**
     * The host part of the current url
     * Example: "/Category/mimfa/service/web"
     * @var string|null
     */
	public static string|null $DIRECTION = null;
	/**
     * The query part of the current url
     * Example: "p=3&l=10"
     * @var string|null
     */
	public static string|null $QUERY = null;
	/**
     * The anchor part of the current url
     * Example: "#serp"
     * @var string|null
     */
	public static string|null $ANCHOR = null;
	/**
	 * The default email acount
     * Example: "info@mimfa.net"
	 * @var string|null
	 */
	public static string|null $EMAIL = null;

	public static ConfigurationBase|null $CONFIG = null;
	public static InformationBase|null $INFO = null;
	public static TemplateBase|null $TEMPLATE = null;
	
	/**
     * The current website url root
     * Example: "http://mimfa.net/"
     * @var string|null
     */
	public static string|null $ROOT = null;
	/**
     * The current website internal root directory
     * Example: "home/mimfa/public_html/"
     * @var string|null
     */
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
	
	/**
     * The base website url root
     * Example: "http://base.aseqbase.ir/"
     * @var string|null
     */
	public static string|null $BASE_ROOT = null;
	/**
     * The base internal root directory
     * Example: "home/base/public_html/"
     * @var string|null
     */
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

_::$ASEQ = $GLOBALS["ASEQBASE"];
_::$BASE = $GLOBALS["BASE"];

_::$SEQUENCES = $GLOBALS["SEQUENCES"];
_::$NEST = $GLOBALS["NEST"];

_::$URL = $GLOBALS["URL"] = getUrl();
_::$HOST = $GLOBALS["HOST"] = getHost();
_::$PATH = $GLOBALS["PATH"] = getPath();
_::$DIRECTION = $GLOBALS["DIRECTION"] = getDirection();
_::$QUERY = $GLOBALS["QUERY"] = getQuery();
_::$ANCHOR = $GLOBALS["ANCHOR"] = getAnchor();
_::$EMAIL = $GLOBALS["EMAIL"] = getEmail();

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

_::$SERIES = array_merge([\_::$DIR=>\_::$ROOT], $GLOBALS["SEQUENCES"], [\_::$BASE_DIR=>\_::$BASE_ROOT]);

RUN("global/Base.php");
RUN("global/EnumBase.php");

LIBRARY("Local");
LIBRARY("DataBase");
LIBRARY("Style");
LIBRARY("Translate");
LIBRARY("Session");
LIBRARY("Convert");
LIBRARY("Contact");
LIBRARY("User");

RUN("global/ConfigurationBase.php");
RUN("Configuration.php");
_::$CONFIG = new Configuration();
ini_set('display_errors', \_::$CONFIG->DisplayError);
ini_set('display_startup_errors', \_::$CONFIG->DisplayStartupError);
error_reporting(\_::$CONFIG->ReportError);

RUN("global/InformationBase.php");
RUN("Information.php");
_::$INFO = new Information();
_::$INFO->User = new \MiMFa\Library\User();

RUN("global/TemplateBase.php");
RUN("Template.php");
_::$TEMPLATE = new Template();


\MiMFa\Library\Local::CreateDirectory(\_::$TMP_DIR);
\MiMFa\Library\Local::CreateDirectory(\_::$LOG_DIR);

function ACCESS($access = 0, bool $showPage = true):bool{
	if(isValid(\_::$CONFIG->StatusMode)) {
		if($showPage) VIEW(\_::$CONFIG->StatusMode,$_GET)??VIEW(\_::$CONFIG->RestrictionViewName??"restriction",$_GET);
		return false;
	}
	elseif(isValid(\_::$CONFIG->AccessMode)) {
		$ip = getClientIP();
		$cip = false;
		foreach(\_::$CONFIG->AccessPatterns as $pat) if($cip = preg_match($pat, $ip)) break;
		if((\_::$CONFIG->AccessMode > 0 && !$cip) || (\_::$CONFIG->AccessMode < 0 && $cip)){
			if($showPage) VIEW(\_::$CONFIG->RestrictionViewName??"restriction",$_GET)??VIEW("401",$_GET);
			return false;
		}
	}
	return \_::$INFO->User->Access($access);
}
function getAccess($access = null):int{
	if(is_null($access)) return getValid(\_::$INFO->User,"Access",0);
	elseif(!is_null(\_::$INFO->User)) return \_::$INFO->User->Access($access);
	else return 0;
}

function SET($output = null){
	print $output;
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

function INCLUDING(string $filePath, array $variables = array(), bool $print = true){
	global $ASEQ, $BASE, $DIR, $BASE_DIR, $ROOT, $BASE_ROOT, $URL, $HOST, $PATH, $DIRECTION, $QUERY, $ANCHOR, $EMAIL;
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
function REQUIRING(string $filePath, array $variables = array(), bool $print = true){
	global $ASEQ, $BASE, $DIR, $BASE_DIR, $ROOT, $BASE_ROOT, $URL, $HOST, $PATH, $DIRECTION, $QUERY, $ANCHOR, $EMAIL;
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
function USING(string $dir, string|null $name = null, array $variables = array(), bool $print = true, string|null $format = ".php"){
	if(empty($name))
		if(isFormat($dir, $format)) return INCLUDING($dir, $variables, $print);
		else return INCLUDING($dir.$format, $variables, $print);
	elseif(isFormat($name, $format)) return INCLUDING($dir.$name, $variables, $print);
	else return INCLUDING($dir.$name.$format, $variables, $print);
}

function forceUSING(string $nodeDir, string $baseDir, string $name, array $variables = array(), bool $print = true){
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

/**
 * To interprete, the specified path
 * @param non-empty-string $name
 * @param array $variables
 * @param bool $print
 * @return mixed
 */
function RUN(string|null $name, array $variables = array(), bool $print = true){
	return forceUSING(\_::$DIR, \_::$BASE_DIR, $name, $variables, $print);
}
/**
 * To interprete, the specified ModelName
 * @param non-empty-string $Name
 * @param array $variables
 * @param bool $print
 * @return mixed
 */
function MODEL(string $name, array $variables = array(), bool $print = true){
	return forceUSING(\_::$MODEL_DIR, \_::$BASE_MODEL_DIR, $name, $variables, $print);
}
/**
 * To interprete, the specified LibraryName
 * @param non-empty-string $Name
 * @param array $variables
 * @param bool $print
 * @return mixed
 */
function LIBRARY(string $Name, array $variables = array(), bool $print = true){
	return forceUSING(\_::$LIBRARY_DIR, \_::$BASE_LIBRARY_DIR, $Name, $variables, $print);
}
/**
 * To interprete, the specified ComponentName
 * @param non-empty-string $Name
 * @param array $variables
 * @param bool $print
 * @return mixed
 */
function COMPONENT(string $Name, array $variables = array(), bool $print = true){
	return forceUSING(\_::$COMPONENT_DIR, \_::$BASE_COMPONENT_DIR, $Name, $variables, $print);
}
/**
 * To interprete, the specified TemplateName
 * @param non-empty-string $Name
 * @param array $variables
 * @param bool $print
 * @return mixed
 */
function MODULE(string $Name, array $variables = array(), bool $print = true){
	return forceUSING(\_::$MODULE_DIR, \_::$BASE_MODULE_DIR, $Name, $variables, $print);
}
/**
 * To interprete, the specified TemplateName
 * @param non-empty-string $Name
 * @param array $variables
 * @param bool $print
 * @return mixed
 */
function TEMPLATE(string $Name, array $variables = array(), bool $print = true){
	return forceUSING(\_::$TEMPLATE_DIR, \_::$BASE_TEMPLATE_DIR, $Name, $variables, $print);
}
/**
 * To interprete, the specified viewname
 * @param non-empty-lowercase-string $name
 * @param array $variables
 * @param bool $print
 * @return mixed
 */
function VIEW(string $name, array $variables = array(), bool $print = true){
	$output = executeCommands(forceUSING(\_::$VIEW_DIR, \_::$BASE_VIEW_DIR, $name, $variables, false));
	if($print) return SET(\_::$CONFIG->AllowReduceSize?ReduceSize($output):$output);
	else return $output;
}
/**
 * To interprete, the specified pagename
 * @param non-empty-lowercase-string $name
 * @param array $variables
 * @param bool $print
 * @return mixed
 */
function PAGE(string $name, array $variables = array(), bool $print = true){
	return forceUSING(\_::$PAGE_DIR, \_::$BASE_PAGE_DIR, $name, $variables, $print);
}
/**
 * To interprete, the specified regionname
 * @param non-empty-lowercase-string $name
 * @param array $variables
 * @param bool $print
 * @return mixed
 */
function REGION(string $name, array $variables = array(), bool $print = true){
	return forceUSING(\_::$REGION_DIR, \_::$BASE_REGION_DIR, $name, $variables, $print);
}
/**
 * To interprete, the specified partname
 * @param non-empty-lowercase-string $name
 * @param array $variables
 * @param bool $print
 * @return mixed
 */
function PART(string $name, array $variables = array(), bool $print = true){
	return forceUSING(\_::$PART_DIR, \_::$BASE_PART_DIR, $name, $variables, $print);
}

function __(string|null $text, bool $translate = true, bool $styling = true):string|null {
	if($styling && \_::$CONFIG->AllowTextAnalyzing) $text = \MiMFa\Library\Style::DoStrong($text);
	if($translate && \_::$CONFIG->AllowTranslate) $text = \MiMFa\Library\Translate::Get($text);
	return $text;
}


function code($html, &$dic = null, $startCode = "<", $endCode = ">", $pattern = "/\<\S+[\w\W]*\>/i")
{
	if(!is_array($dic)) $dic = array();
	return preg_replace_callback($pattern,function($a) use(&$dic,$startCode, $endCode){
        $key = $a[0];
		if(!array_key_exists($key,$dic)) return $dic[$key] = $startCode.count($dic).$endCode;
        return $dic[$key];
    },$html);
}
function decode($html, $dic)
{
	if(is_array($dic))
		foreach($dic as $k=>$v)
			$html = str_replace($v,$k,$html);
	return $html;
}

function startsWith(string|null $haystack, string|null $needle):bool {
	return substr_compare($haystack, $needle, 0, strlen($needle)) === 0;
}
function endsWith(string|null $haystack, string|null $needle):bool {
	return substr_compare($haystack, $needle, -strlen($needle)) === 0;
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

function forceFullUrl(string|null $path){
	return \MiMFa\Library\Local::GetFullUrl($path);
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

function getFullUrl(string|null $path = null):string|null{
	if($path === null) $path = getUrl();
	return \MiMFa\Library\Local::GetFullUrl($path);
}
function getUrl(string|null $path = null):string|null{
	if($path === null) $path = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"";
	return preg_replace("/^[\/\\\]/",rtrim(GetHost(),"/\\")."$1",$path);
}
function getHost(string|null $path = null):string|null{
	$pat = "/^\w+\:\/*[^\/]+/";
	if($path == null || !preg_match($pat,$path)) $path = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'];
	return PREG_Find($pat, $path);
}
function getPath(string|null $path = null):string|null{
	return PREG_Find("/(^[^\?#]*)/", $path??getUrl());
}
function getDirection(string|null $path = null):string|null{
	if($path == null) $path = getUrl();
	if(startsWith($path,\_::$BASE_DIR)) return substr($path, strlen(\_::$BASE_DIR));
	return PREG_Replace("/(^\w+:\/*[^\/]+)|((\?|#).+$)/","", $path);
}
function getRelative(string|null $path = null):string|null{
	if($path == null) $path = getUrl();
	if(startsWith($path,\_::$BASE_DIR)) return substr($path, strlen(\_::$BASE_DIR));
	return PREG_Replace("/^\w+:\/*[^\/]+/","", $path);
}
function getQuery(string|null $path = null):string|null{
	return PREG_Find("/((?<=\?)[^#]*($|#))/", $path??getUrl());
}
function getAnchor(string|null $path = null):string|null{
	return PREG_Find("/((?<=#)[^\?]*($|\?))/", $path??getUrl());
}
function getEmail(string|null $path = null, $mailName = "info"):string|null{
	return $mailName."@".PREG_replace("/\w+:\/{1,2}(www\.)?/","", getHost($path));
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

function isEmpty($text):bool{
	return !isset($text) || is_null($text) || trim($text."") == "" || trim($text."","'\"") == "";
}
function isValid($obj, string|null $item = null):bool{
	if($item === null) return isset($obj) && !is_null($obj) && (!is_string($obj) || !(trim($obj) == "" || trim($obj,"'\"") == ""));
	elseif(is_array($obj)) return isValid($obj) && isValid($item) && !empty($obj[$item]) && isValid($obj[$item]);
	else return isValid($obj) && isset($obj->$item) && isValid($obj->$item);
}
function getValid($obj, string|null $item = null, $defultValue = null){
	if(isValid($obj,$item))
        if($item === null) return $obj;
		else if(is_array($obj)) return $obj[$item];
		else return $obj->$item;
    else return $defultValue;
}
function doValid(callable $func, $obj, string|null $item = null, $defultValue = null){
    if(isValid($obj,$item))
        if($item === null) return $func($obj);
		else return $func($obj[$item]);
    else $defultValue;
}
function between(...$options){
	foreach ($options as $value)
        if(isValid($value)) return $value;
	return null;
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
function insertToString(string $mainstr,string $insertstr,int $index):string
{
    return substr($mainstr, 0, $index) . $insertstr . substr($mainstr, $index);
}
function deleteFromString(string $mainstr, int $index, int $length = 1):string
{
    return substr($mainstr, 0, $index) . substr($mainstr, $index + $length);
}

/**
 * Execute the Command Comments (Commands by the pattern <!---Name:Command---> <!---Name--->)
 * @param string|null $page The source document
 * @return string|null
 */
function executeCommands(string|null $page, string|null $name = null):string|null{
	if($page == null) return $page;
	if($name == null){
        //$page = executeCommands($page, "append");
        //$page = executeCommands($page, "prepend");
    } else {
		$name = strtolower($name);
		$patfull = "/<!-{3}$name:[\w\W]*-{3}>[\w\W]*<!-{3}$name-{3}>/i";
		$patcommand = "/(?<=<!-{3}$name:)[\w\W]*(?=-{3}>)/i";
		$matches = [];
		switch($name){
            case "append":
				$page = preg_replace_callback($patfull, function($m) use(&$matches){
					array_push($matches,$m[0]);
					return "";
				}, $page);
				foreach($matches as $m)
					$page = preg_replace("/".preg_find($patcommand,$m)."/i", "\1$m", $page);
			break;
            case "prepend":
				$page = preg_replace_callback($patfull, function($m) use(&$matches){
					array_push($matches,$m[0]);
					return "";
				}, $page);
				foreach($matches as $m)
					$page = preg_replace("/".preg_find($patcommand,$m)."/i", "$m\1", $page);
				break;
		}
		$pat = "/<!-{3}".$name."[\w\W]*-{3}>/i";
		$page = preg_replace($pat, "", $page);
    }
	return $page;
}

/**
 * Compress and reduce the size of document
 * @param string|null $page The source document
 * @return string|null
 */
function reduceSize(string|null $page):string|null{
	if($page == null) return $page;
	$ls = array();
	$pat ="/<\s*(style)[\s\S]*>[\s\S]*<\/\s*\1\s*>/ixU";
	//$pat ="/\<\s*((style)|(script))[\s\S]*\>[\s\S]*\<\\/\s*\\1\s*\>/ixU";
	$matches = null;
	if(preg_match_all($pat, $page, $matches)){
		foreach($matches[0] as $item)
			if(!in_array($item, $ls)) array_push($ls, preg_replace("/\s+/im", " ", $item));
		//echo count($ls);
		$page = preg_replace($pat, "", $page);
		$page = preg_replace("/<\/\s*head\s*>/im", implode(PHP_EOL, $ls).PHP_EOL."</head>", $page);
		//$page = preg_replace("/(?<!(\<\s*(script)[\s\S]*\>[\s\S]+))\s+(?!([\s\S]+\<\\/\s*\\2\s*\>))/ixU", " ", $page);
		return preg_replace("/<\s*\/\s*(style)\s*>\s*\<\s*\1\s*>/ixU", PHP_EOL, $page);
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

/**
 * Inser into an array
 * @param array      $array
 * @param int|string $position
 * @param mixed      $insert
 */
function array_insert(&$array, $position, $insert)
{
    if (is_int($position)) {
        array_splice($array, $position, 0, $insert);
    } else {
        $pos   = array_search($position, array_keys($array));
        $array = array_merge(
            array_slice($array, 0, $pos),
            $insert,
            array_slice($array, $pos)
        );
    }
    return $array;
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