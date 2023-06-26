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
		 * Generation	.	Major	Minor	1:test|2:alpha|3:beta|4:release|5<=9:stable|0:base
		 * X		.	xx	xx	x
		 * @var float
		 */
		public static float $VERSION = 0.21003;

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
		 * The request part of the current url
		 * Example: "/Category/mimfa/service/web"
		 * @var string|null
		 */
		public static string|null $REQUEST = null;
		/**
		 * The direction part of the current url from the root
		 * Example: "Category/mimfa/service/web"
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
		 * Example: "serp"
		 * @var string|null
		 */
		public static string|null $ANCHOR = null;
		/**
		 * The default email acount
		 * Example: "info@mimfa.net"
		 * @var string|null
		 */
		public static string|null $EMAIL = null;
		/**
		 * The default files extensions
		 * Example: ".php"
		 * @var string|null
		 */
		public static string $EXTENSION = ".php";

		public static ConfigurationBase|null $CONFIG = null;
		public static InformationBase|null $INFO = null;
		public static TemplateBase|null $TEMPLATE = null;
		
		public static $PREPENDS = array();
		public static $APPENDS = array();
	
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

	_::$URL = getUrl();
	_::$HOST = getHost();
	_::$PATH = getPath();
	_::$REQUEST = getRequest();
	_::$DIRECTION = getDirection();
	_::$QUERY = getQuery();
	_::$ANCHOR = getAnchor();
	_::$EMAIL = getEmail();

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

	RUN("global/Base");
	RUN("global/EnumBase");

	LIBRARY("Local");
	LIBRARY("DataBase");
	LIBRARY("Session");
	LIBRARY("Style");
	LIBRARY("Translate");
	LIBRARY("Convert");
	LIBRARY("Contact");
	LIBRARY("User");

	RUN("global/ConfigurationBase");
	RUN("Configuration");
	_::$CONFIG = new Configuration();
	ini_set('display_errors', \_::$CONFIG->DisplayError);
	ini_set('display_startup_errors', \_::$CONFIG->DisplayStartupError);
	error_reporting(\_::$CONFIG->ReportError);

	RUN("global/InformationBase");
	RUN("Information");
	_::$INFO = new Information();
	_::$INFO->User = new \MiMFa\Library\User();

	RUN("global/TemplateBase");
	RUN("Template");
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
		if(is_null($access) && !is_null(\_::$INFO->User)) return \_::$INFO->User->Access($access);
		else return \_::$CONFIG->GuestAccess;
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
		global $ASEQ, $BASE, $DIR, $BASE_DIR, $ROOT, $BASE_ROOT;
		if(file_exists($filePath)){
			//if(count($variables) > 0) $filePath = rtrim($filePath,"/")."?". http_build_query($variables);
			//if(count($variables) > 0) extract($variables);
			//foreach($variables as $k=>$v) $_POST[$k] = $_REQUEST[$k] = $v;
			ob_start();
			include_once $filePath;
			$output = ob_get_clean();
			if ($print) return SET($output)??true;
			return $output??true;
		}
		return null;
	}
	function REQUIRING(string $filePath, array $variables = array(), bool $print = true){
		global $ASEQ, $BASE, $DIR, $BASE_DIR, $ROOT, $BASE_ROOT;
		if(file_exists($filePath)){
			//if(count($variables) > 0) extract($variables);
			//foreach($variables as $k=>$v) $_POST[$k] = $_REQUEST[$k] = $v;
			ob_start();
			require_once $filePath;
			$output = ob_get_clean();
			if ($print) return SET($output)??true;
			return $output??true;
		}
		return null;
	}
	
	function USING(string $dir, string|null $name = null, array $variables = array(), bool $print = true, string|null $extension = ".php"){
		$extension = $extension??\_::$EXTENSION;
		try{ applyPrepends($dir, $name);
			if(empty($name))
				if(isFormat($dir, $extension)) return INCLUDING($dir, $variables, $print);
				else return INCLUDING($dir.$extension, $variables, $print);
			elseif(isFormat($name, $extension)) return INCLUDING($dir.$name, $variables, $print);
			else return INCLUDING($dir.$name.$extension, $variables, $print);
        } finally{ applyAppends($dir, $name); }
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
	 * Prepend something to any function or directory's files or actions
	 * @param mixed function name or directory 
	 * @param null|string file name 
	 * @param null|string|callable the action or content tou want to do 
	 */
	function prepend($toCase, string|null $name = null, null|string|callable $value = null){
        if(isValid($value)){
			$toCase = strtoupper($toCase??"");
			$name = strtoupper($name??"");
            if(!isset(\_::$PREPENDS[$toCase])) \_::$PREPENDS[$toCase] = array();
            if(!isset(\_::$PREPENDS[$toCase][$name])) \_::$PREPENDS[$toCase][$name] = array();
            array_push(\_::$PREPENDS[$toCase][$name], $value);
        }
    }
	/**
	 * Append something to any function or directory's files or actions
	 * @param mixed function name or directory 
	 * @param null|string file name 
	 * @param null|string|callable the action or content tou want to do 
	 */
	function append($toCase, string|null $name = null, null|string|callable $value = null){
        if(isValid($value)){
			$toCase = strtoupper($toCase??"");
			$name = strtoupper($name??"");
            if(!isset(\_::$APPENDS[$toCase])) \_::$APPENDS[$toCase] = array();
            if(!isset(\_::$APPENDS[$toCase][$name])) \_::$APPENDS[$toCase][$name] = array();
            array_push(\_::$APPENDS[$toCase][$name], $value);
        }
    }
	function applyPrepends($toCase, string|null $name = null){
		$toCase = strtoupper($toCase??"");
		$name = strtoupper($name??"");
        if(!isset(\_::$PREPENDS[$toCase][$name])) return;
        $value = \_::$PREPENDS[$toCase][$name];
		if(is_string($value))
			echo $value;
        else return ($value)();
    }
	function applyAppends($toCase, string|null $name = null){
		$toCase = strtoupper($toCase??"");
		$name = strtoupper($name??"");
        if(!isset(\_::$APPENDS[$toCase][$name])) return;
        $value = \_::$APPENDS[$toCase][$name];
		if(is_string($value))
			echo $value;
        else return ($value)();
    }

	/**
	 * To interprete, the specified path
	 * @param non-empty-string $name
	 * @param array $variables
	 * @param bool $print
	 * @return mixed
	 */
	function RUN(string|null $name, array $variables = array(), bool $print = true){
		try{ applyPrepends("RUN", $name);
			return forceUSING(\_::$DIR, \_::$BASE_DIR, $name, $variables, $print);
        } finally{ applyAppends("RUN", $name); }
	}
	/**
	 * To interprete, the specified ModelName
	 * @param non-empty-string $Name
	 * @param array $variables
	 * @param bool $print
	 * @return mixed
	 */
	function MODEL(string $name, array $variables = array(), bool $print = true){
		try{ applyPrepends("MODEL", $name);
			return forceUSING(\_::$MODEL_DIR, \_::$BASE_MODEL_DIR, $name, $variables, $print);
        } finally{ applyAppends("MODEL", $name); }
	}
	/**
	 * To interprete, the specified LibraryName
	 * @param non-empty-string $Name
	 * @param array $variables
	 * @param bool $print
	 * @return mixed
	 */
	function LIBRARY(string $Name, array $variables = array(), bool $print = true){
		try{ applyPrepends("LIBRARY", $Name);
			return forceUSING(\_::$LIBRARY_DIR, \_::$BASE_LIBRARY_DIR, $Name, $variables, $print);
        } finally{ applyAppends("LIBRARY", $Name); }
	}
	/**
	 * To interprete, the specified ComponentName
	 * @param non-empty-string $Name
	 * @param array $variables
	 * @param bool $print
	 * @return mixed
	 */
	function COMPONENT(string $Name, array $variables = array(), bool $print = true){
		try{ applyPrepends("COMPONENT", $Name);
			return forceUSING(\_::$COMPONENT_DIR, \_::$BASE_COMPONENT_DIR, $Name, $variables, $print);
        } finally{ applyAppends("COMPONENT", $Name); }
	}
	/**
	 * To interprete, the specified TemplateName
	 * @param non-empty-string $Name
	 * @param array $variables
	 * @param bool $print
	 * @return mixed
	 */
	function MODULE(string $Name, array $variables = array(), bool $print = true){
		try{ applyPrepends("MODULE", $Name);
			return forceUSING(\_::$MODULE_DIR, \_::$BASE_MODULE_DIR, $Name, $variables, $print);
        } finally{ applyAppends("MODULE", $Name); }
	}
	/**
	 * To interprete, the specified TemplateName
	 * @param non-empty-string $Name
	 * @param array $variables
	 * @param bool $print
	 * @return mixed
	 */
	function TEMPLATE(string $Name, array $variables = array(), bool $print = true){
		try{ applyPrepends("TEMPLATE", $Name);
			return forceUSING(\_::$TEMPLATE_DIR, \_::$BASE_TEMPLATE_DIR, $Name, $variables, $print);
        } finally{ applyAppends("TEMPLATE", $Name); }
	}
	/**
	 * To interprete, the specified viewname
	 * @param non-empty-lowercase-string $name
	 * @param array $variables
	 * @param bool $print
	 * @return mixed
	 */
	function VIEW(string $name, array $variables = array(), bool $print = true){
		try{ applyPrepends("VIEW", $name);
			$output = executeCommands(forceUSING(\_::$VIEW_DIR, \_::$BASE_VIEW_DIR, $name, $variables, false));
			if($print) return SET(\_::$CONFIG->AllowReduceSize?ReduceSize($output):$output);
			else return \_::$CONFIG->AllowReduceSize?ReduceSize($output):$output;
        } finally{ applyAppends("VIEW", $name); }
	}
	/**
	 * To interprete, the specified pagename
	 * @param non-empty-lowercase-string $name
	 * @param array $variables
	 * @param bool $print
	 * @return mixed
	 */
	function PAGE(string $name, array $variables = array(), bool $print = true){
		try{ applyPrepends("PAGE", $name);
			return forceUSING(\_::$PAGE_DIR, \_::$BASE_PAGE_DIR, $name, $variables, $print);
        } finally{ applyAppends("PAGE", $name); }
	}
	/**
	 * To interprete, the specified regionname
	 * @param non-empty-lowercase-string $name
	 * @param array $variables
	 * @param bool $print
	 * @return mixed
	 */
	function REGION(string $name, array $variables = array(), bool $print = true){
		try{ applyPrepends("REGION", $name);
			return forceUSING(\_::$REGION_DIR, \_::$BASE_REGION_DIR, $name, $variables, $print);
        } finally{ applyAppends("REGION", $name); }
	}
	/**
	 * To interprete, the specified partname
	 * @param non-empty-lowercase-string $name
	 * @param array $variables
	 * @param bool $print
	 * @return mixed
	 */
	function PART(string $name, array $variables = array(), bool $print = true){
		try{ applyPrepends("PART", $name);
			return forceUSING(\_::$PART_DIR, \_::$BASE_PART_DIR, $name, $variables, $print);
        } finally{ applyAppends("PART", $name); }
	}

	function __(string|null $text, bool $translate = true, bool $styling = true):string|null {
		if($styling && \_::$CONFIG->AllowTextAnalyzing) $text = \MiMFa\Library\Style::DoStrong($text);
		if($translate && \_::$CONFIG->AllowTranslate) $text = \MiMFa\Library\Translate::Get($text);
		return $text;
	}
	
	function go($url){
        echo "<script>load('$url');</script>";
    }
	function load($url = null){
        echo "<script>load(".(isValid($url)?"'$url'":"null").");</script>";
    }
	function open($url = null, $target = "_blank"){
        echo "<script>open(".(isValid($url)?"'$url'":"null").", '$target');</script>";
    }
	function share($url = null, $path = null){
        echo "<script>share(".(isValid($url)?"'$url'":"null").", ".(isValid($path)?"'$path'":"null").");</script>";
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
	
	function encrypt($plain){
		if(is_null($plain)) return null;
		if(empty($plain)) return $plain;
		return \MiMFa\Library\HashCrypt::Encrypt($plain,\_::$CONFIG->SecretKey, true);
    }
	function decrypt($cipher){
		if(is_null($cipher)) return null;
		if(empty($cipher)) return $cipher;
		return \MiMFa\Library\HashCrypt::Decrypt($cipher,\_::$CONFIG->SecretKey, true);
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

	function forceFullUrl(string|null $path, bool $optimize = true){
		return \MiMFa\Library\Local::GetFullUrl($path, $optimize);
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

	function getFullUrl(string|null $path = null, bool $optimize = true):string|null{
		if($path === null) $path = getUrl();
		return \MiMFa\Library\Local::GetFullUrl($path, $optimize);
	}
	function getUrl(string|null $path = null):string|null{
		if($path === null)
			$path = //$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] or
				(
					(
						getValid($_SERVER,'SCRIPT_URI')??
						(((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https":"http").
						"://".$_SERVER["HTTP_HOST"].(getValid($_SERVER,'PHP_SELF')?? $_SERVER["REQUEST_URI"])
					).($_SERVER['QUERY_STRING']?"?".$_SERVER['QUERY_STRING']:"")
				);
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
	function getRequest(string|null $path = null):string|null{
		if($path == null) $path = getUrl();
		if(startsWith($path,\_::$BASE_DIR)) $path = substr($path, strlen(\_::$BASE_DIR));
		return PREG_Replace("/(^\w+:\/*[^\/]+)|([\?#].+$)/","", $path);
	}
	function getDirection(string|null $path = null):string|null{
		if($path == null) $path = getUrl();//ltrim($_SERVER["REQUEST_URI"],"\\\/");
		if(startsWith($path,\_::$BASE_DIR)) $path = substr($path, strlen(\_::$BASE_DIR));
		return PREG_Replace("/(^\w+:\/{2}[^\/]+\/)|([\?#].+$)/","", $path);
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
	
	function changeMemo($key, $val){
		if($val=="!" || is_null($val)) {
			popMemo($key);
			return null;
        }
		else setMemo($key,$val);
		return $val;
	}
	function popMemo($key){
		$val = getMemo($key);
		forgetMemo($key);
		return $val;
	}
	function setMemo($key, $val){
		if($val == null) return false;
		return setcookie($key, $val, 0,"/");
	}
	function getMemo($key){
		if(isset($_COOKIE[$key])) return $_COOKIE[$key];
		else return null;
	}
	function hasMemo($key){
		return !is_null(getMemo($key));
	}
	function forgetMemo($key){
		unset($_COOKIE[$key]);
		return setcookie($key, "", 0,"/");
	}
	function flushMemos($key){
		foreach($_COOKIE as $key => $val){
            unset($_COOKIE[$key]);
            return setcookie($key, "", 0,"/");
        }
	}

	function changeSession($key, $val){
		if($val=="!" || is_null($val)) {
			popSession($key);
			return null;
        }
		else setSession($key,$val);
		return $val;
	}
	function popSession($key){
		$val = getSession($key);
		forgetSession($key);
		return $val;
	}
	function setSession($key, $val){
		return $_SESSION[$key] = $val;
	}
	function getSession($key){
		return getValid($_SESSION,$key);
	}
	function hasSession($key){
		return isValid($_SESSION,$key);
	}
	function forgetSession($key){
		unset($_SESSION[$key]);
	}
	function flushSessions($key){
		foreach($_SESSION as $key => $val)
			unset($_SESSION[$key]);
	}

	function getClientIP($ver = null):string|null{
		foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
			if (array_key_exists($key, $_SERVER) === true){
				foreach (explode(',', $_SERVER[$key]) as $ip){
					$ip = trim($ip); // just to be safe
					if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
						if($ver == 6) return gethostbyaddr($ip);
						return $ip;
					}
				}
			}
		}
		return null;
	}

	function getValue(string $source, string|null $key = null, bool $ismultiline = true){
		if($key == null)
			if(is_string($source))
				return $source;
			else return ($source)();
		else return fetchValue($source,$key,$ismultiline);
	}
	function fetchValue(string|null $source, string|null $key, bool $ismultiline = true){
		$source = getValue($source);
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
	function isInASEQ(string|null $filePath):bool{
		$filePath = preg_replace("/^\\\\/",\_::$DIR,str_replace(\_::$DIR,"",trim($filePath??getUrl())));
		if(isFormat($filePath, \_::$EXTENSION)) return file_exists($filePath);
		return is_dir($filePath) || file_exists($filePath.\_::$EXTENSION);
	}
	function isInBASE(string|null $filePath):bool{
		$filePath = \_::$BASE_DIR.preg_replace("/^\\\\/","",str_replace(\_::$BASE_DIR,"",trim($filePath??getUrl())));
		if(isFormat($filePath, \_::$EXTENSION)) return file_exists($filePath);
		return is_dir($filePath) || file_exists($filePath.\_::$EXTENSION);
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
	function test_Details(){
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
	function test_Address(){
		echo "<br>URL: "._::$URL;
		echo "<br>HOST: "._::$HOST;
		echo "<br>PATH: "._::$PATH;
		echo "<br>REQUEST: "._::$REQUEST;
		echo "<br>DIRECTION: "._::$DIRECTION;
		echo "<br>QUERY: "._::$QUERY;
		echo "<br>ANCHOR: "._::$ANCHOR;
		echo "<br>EMAIL: "._::$EMAIL;
	}
	function test_Access($func,$res=null){
		$r = null;
		if(ACCESS(0, false)){
			if($r = $func()) echo "<b>TRUE: ".($r??$res)."</b><br>";
			else echo "FALSE: ".$res."<br>";
		}
	}
	//End Test Region
?>