<?php
	/**
	 * The Global Static Class
	 * It contains the most useful objects along developments
	 *@copyright All rights are reserved for MiMFa Development Group
	 *@author Mohammad Fathi
	 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
	 *@link https://github.com/aseqbase/aseqbase/wiki/Globals See the Documentation
	 */
	class _ {
		public static string $ID = "0";
		public static int $DYNAMIC_ID = 0;

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
		 * Example: "https://www.mimfa.net:5056/Category/mimfa/service/web.php?p=3&l=10#serp"
		 * @var string|null
		 */
		public static string|null $URL = null;
		/**
		 * The path part of the current url
		 * Example: "https://www.mimfa.net:5056/Category/mimfa/service/web.php"
		 * @var string|null
		 */
		public static string|null $PATH = null;
		/**
         * The host part of the current url
         * Example: "https://www.mimfa.net:5056"
         * @var string|null
         */
		public static string|null $HOST = null;
		/**
         * The site name part of the current url
         * Example: "www.mimfa.net"
         * @var string|null
         */
		public static string|null $SITE = null;
		/**
         * The domain name part of the current url
         * Example: "mimfa.net"
         * @var string|null
         */
		public static string|null $DOMAIN = null;
		/**
		 * The request part of the current url
		 * Example: "/Category/mimfa/service/web.php?p=3&l=10#serp"
		 * @var string|null
		 */
		public static string|null $REQUEST = null;
		/**
		 * The direction part of the current url from the root
		 * Example: "Category/mimfa/service/web.php"
		 * @var string|null
		 */
		public static string|null $DIRECTION = null;
		/**
         * The last part of the current direction url
         * Example: "web.php"
         * @var string|null
         */
		public static string|null $PAGE = null;
		/**
		 * The query part of the current url
		 * Example: "p=3&l=10"
		 * @var string|null
		 */
		public static string|null $QUERY = null;
		/**
		 * The fragment or anchor part of the current url
		 * Example: "serp"
		 * @var string|null
		 */
		public static string|null $FRAGMENT = null;
		/**
		 * The default email account
		 * Example: "do-not-reply@mimfa.net"
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

	_::$ID = getId(true)."";
	_::$DYNAMIC_ID = getId(false);

	_::$URL = getUrl();
	_::$HOST = getHost();
	_::$SITE = getSite();
	_::$DOMAIN = getDomain();
	_::$PATH = getPath();
	_::$REQUEST = getRequest();
	_::$DIRECTION = getDirection();
	_::$PAGE = getPage();
	_::$QUERY = getQuery();
	_::$FRAGMENT = getFragment();
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

	LIBRARY("Math");
	LIBRARY("Local");
	LIBRARY("DataBase");
	LIBRARY("Session");
	LIBRARY("Style");
	LIBRARY("Translate");
	LIBRARY("Convert");
	LIBRARY("Contact");
	LIBRARY("User");
	LIBRARY("HTML");
	LIBRARY("Script");

	RUN("global/ConfigurationBase");
	RUN("Configuration");
	_::$CONFIG = new Configuration();
	ini_set('display_errors', \_::$CONFIG->DisplayError);
	ini_set('display_startup_errors', \_::$CONFIG->DisplayStartupError);
	error_reporting(\_::$CONFIG->ReportError);
    \MiMFa\Library\Session::Start();
    if(\_::$CONFIG->AllowTranslate){
        \MIMFa\Library\Translate::$Language = \_::$CONFIG->DefaultLanguage;
        \MIMFa\Library\Translate::$Direction = \_::$CONFIG->DefaultDirection;
        \MIMFa\Library\Translate::$Encoding = \_::$CONFIG->Encoding;
        \MIMFa\Library\Translate::$AutoUpdate = \_::$CONFIG->AutoUpdateLanguage;
        \MIMFa\Library\Translate::Initialize();
    }

	RUN("global/InformationBase");
	RUN("Information");
	_::$INFO = new Information();
	_::$INFO->User = new \MiMFa\Library\User();

	RUN("global/TemplateBase");
	RUN("Template");
	_::$TEMPLATE = new Template();

	\MiMFa\Library\Local::CreateDirectory(\_::$TMP_DIR);
	\MiMFa\Library\Local::CreateDirectory(\_::$LOG_DIR);

	/**
	 * Check if the client has access to the page or assign them to other page, based on thair IP, Accessibility, Restriction and etc.
	 * @param int|null $minaccess The minimum accessibility for the client, pass null to give the user access
	 * @param bool $assign Assign clients to other page, if they have not enough access
	 * @param bool $die Pass true to die the process if clients have not enough access, else pass false
	 * @param int|string|null $die Die the process with this status if clients have not enough access
	 * @return bool The client has accessibility bigger than $minaccess or not
	 * @return int|mixed The user accessibility group
	 */
	function ACCESS($minaccess = 0, bool|string $assign = true, bool|string|int|null $die = true):mixed{
		if(isValid(\_::$CONFIG->StatusMode)) {
			if($assign){
				if(is_string($assign)) go($assign);
				else VIEW(\_::$CONFIG->StatusMode,variables:$_REQUEST)??VIEW(\_::$CONFIG->RestrictionViewName??"restriction",variables:$_REQUEST);
            }
			if($die !== false) die($die);
			return false;
        }
		elseif(isValid(\_::$CONFIG->AccessMode)) {
			$ip = getClientIP();
			$cip = false;
			foreach(\_::$CONFIG->AccessPatterns as $pat) if($cip = preg_match($pat, $ip)) break;
			if((\_::$CONFIG->AccessMode > 0 && !$cip) || (\_::$CONFIG->AccessMode < 0 && $cip)){
				if($assign){
                    if(is_string($assign)) go($assign);
                    else VIEW(\_::$CONFIG->RestrictionViewName??"restriction",variables:$_REQUEST)??VIEW("401",variables:$_REQUEST);
                }
				if($die !== false) die($die);
				return false;
			}
		}
		$b = getAccess($minaccess);
		if($b !== false) return $b;
		if($assign){
            if(is_string($assign)) go($assign);
            else go(\MiMFa\Library\User::$InHandlerPath);
        }
        if($die !== false) die($die);
		return $b;
	}
	/**
	 * Check if the user has access to the page or not
     * @param int|array|null $acceptableAccess The minimum accessibility for the user, pass null to give the user access
	 * @return int|bool|null The user accessibility group
	 */
	function getAccess($minaccess = null):mixed{
		if(is_null(\_::$INFO) || is_null(\_::$INFO->User)) return \MiMFa\Library\User::CheckAccess(null, $minaccess);
		else return \_::$INFO->User->Access($minaccess);
	}

	/**
     * Print only this output on the client side then reload the page
     * @param mixed $value The data that is ready to print
     * @return mixed Printed data
     */
	function FLIP($value = null, $url = null){
        ob_clean();
		flush();
		die($value."<script>window.location.assign(".(isValid($url)?"`".\MiMFa\Library\Local::GetUrl($url)."`":"location.href").");</script>");
	}
	/**
     * Print only this output on the client side
     * @param mixed $value The data that is ready to print
     * @return mixed Printed data
     */
	function SEND($value = null){
        ob_clean();
		flush();
		die($value."");
	}
	/**
     * Receive requests from the client side
	 * @param mixed $key The key of the received value
	 * @param array|string|null $source The the received data source $_GET/$POST/$_FILES (by default it is $_REQUEST)
     * @return mixed The value
     */
	function RECEIVE($key = null, array|string|null $source = null, $default = null){
		if(is_null($source)) $source = $_REQUEST;
		if(is_string($source))
			switch (trim(strtolower($source)))
            {
                case "public":
                case "get":
					$source = $_GET;
					break;
                case "private":
                case "post":
					$source = $_POST;
					break;
                case "file":
                case "files":
					$source = $_FILES;
					break;
            	default:
					$source = $_REQUEST;
					break;
            }
		if(is_null($key)) return count($source)>0?$source:$default;
		else return getValid($source, $key, $default);
	}
	/**
     * Receive requests from the client side then remove it
	 * @param mixed $key The key of the received value
	 * @param array|string|null $source The the received data source $_GET/$POST/$_FILES (by default it is $_REQUEST)
     * @return mixed The value
     */
	function GRAB($key = null, array|string|null $source = null, $default = null){
		$val = RECEIVE($key, $source, $default);
		if(is_null($key)){
            if(is_string($source))
                switch (trim(strtolower($source)))
                {
                    case "public":
                    case "get":
                        $_GET = [];
                        break;
                    case "private":
                    case "post":
                        $_POST = [];
                        break;
                    case "file":
                    case "files":
                        $_FILES = [];
                        break;
                    default:
                        $_REQUEST = [];
                        break;
                }
        } else {
            unset($_POST[$key]);
            unset($_GET[$key]);
            unset($_REQUEST[$key]);
            unset($_FILES[$key]);
        }
		return $val;
	}

	/**
     * Print output on the client side
     * @param mixed $output The data that is ready to print
     * @return mixed Printed data
     */
	function SET($output = null){
		print $output;
		return $output;
	}
	/**
	 * Received input from the client side
	 * @param mixed $input The data that is received to print
	 * @return mixed Received data
	 */
	function GET($input = null){
		if(is_string($input) && isAbsoluteUrl($input)){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $input);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			return curl_exec($ch);
		}
		return $input;
	}

	function INCLUDING(string $filePath, bool $print = true, array $variables = []){
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
	function REQUIRING(string $filePath, bool $print = true, array $variables = []){
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

	function USING(string $dir, string|null $name = null, bool $print = true, array $variables = [], string|null $extension = ".php"){
		$extension = $extension??\_::$EXTENSION;
		try{ applyPrepends($dir, $name);
			if(empty($name))
				if(isFormat($dir, $extension)) return INCLUDING($dir, $print, $variables);
				else return INCLUDING($dir.$extension, $print, $variables);
			elseif(isFormat($name, $extension)) return INCLUDING($dir.$name, $print, $variables);
			else return INCLUDING($dir.$name.$extension, $print, $variables);
        } finally{ applyAppends($dir, $name); }
	}
	function forceUSING(string $nodeDir, string $baseDir, string $name, bool $print = true, array $variables = [], int $fromSeq = 0, int $count = 999999999){
		$seq = null;
		$seqInd = 0;
		$c = count(\_::$SEQUENCES);
		$toSeq = $count < 0 ? ($c + 1 + $count) : ($fromSeq + $count);
		if($seqInd >= $fromSeq && $seqInd <= $toSeq && (($seq = USING($nodeDir,$name, $print, $variables)) !== null)) return $seq;
		$seqInd++;
		if($fromSeq <= $c) {
			$dir = substr($nodeDir, strlen(\_::$DIR));
			foreach(\_::$SEQUENCES as $aseq=>$root) {
				if($seqInd > $toSeq) return null;
				if($seqInd >= $fromSeq && (($seq = USING($aseq.$dir, $name, $print, $variables)) !== null))
					return $seq;
                $seqInd++;
            }
		} else $seqInd += $c;
		if($seqInd >= $fromSeq && $seqInd <= $toSeq && (($seq = USING($baseDir, $name, $print, $variables)) !== null)) return $seq;
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
	function RUN(string|null $name, bool $print = true, string|null $dir = null, array $variables = [], int $fromSeq = 0, int $count = 999999999){
		try{ applyPrepends("RUN", $name);
			return forceUSING($dir??\_::$DIR, \_::$BASE_DIR, $name, $print, $variables, $fromSeq, $count);
        } finally{ applyAppends("RUN", $name); }
	}
	/**
	 * To interprete, the specified ModelName
	 * @param non-empty-string $Name
	 * @param array $variables
	 * @param bool $print
	 * @return mixed
	 */
	function MODEL(string $name, bool $print = true, string|null $dir = null, array $variables = [], int $fromSeq = 0, int $count = 999999999){
		try{ applyPrepends("MODEL", $name);
			return forceUSING($dir??\_::$MODEL_DIR, \_::$BASE_MODEL_DIR, $name, $print, $variables, $fromSeq, $count);
        } finally{ applyAppends("MODEL", $name); }
	}
	/**
	 * To interprete, the specified LibraryName
	 * @param non-empty-string $Name
	 * @param array $variables
	 * @param bool $print
	 * @return mixed
	 */
	function LIBRARY(string $Name, bool $print = true, string|null $dir = null, array $variables = [], int $fromSeq = 0, int $count = 999999999){
		try{ applyPrepends("LIBRARY", $Name);
			return forceUSING($dir??\_::$LIBRARY_DIR, \_::$BASE_LIBRARY_DIR, $Name, $print, $variables, $fromSeq, $count);
        } finally{ applyAppends("LIBRARY", $Name); }
	}
	/**
	 * To interprete, the specified ComponentName
	 * @param non-empty-string $Name
	 * @param array $variables
	 * @param bool $print
	 * @return mixed
	 */
	function COMPONENT(string $Name, bool $print = true, string|null $dir = null, array $variables = [], int $fromSeq = 0, int $count = 999999999){
		try{ applyPrepends("COMPONENT", $Name);
			return forceUSING($dir??\_::$COMPONENT_DIR, \_::$BASE_COMPONENT_DIR, $Name, $print, $variables, $fromSeq, $count);
        } finally{ applyAppends("COMPONENT", $Name); }
	}
	/**
	 * To interprete, the specified TemplateName
	 * @param non-empty-string $Name
	 * @param array $variables
	 * @param bool $print
	 * @return mixed
	 */
	function MODULE(string $Name, bool $print = true, string|null $dir = null, array $variables = [], int $fromSeq = 0, int $count = 999999999){
		try{ applyPrepends("MODULE", $Name);
			return forceUSING($dir??\_::$MODULE_DIR, \_::$BASE_MODULE_DIR, $Name, $print, $variables, $fromSeq, $count);
        } finally{ applyAppends("MODULE", $Name); }
	}
	/**
	 * To interprete, the specified TemplateName
	 * @param non-empty-string $Name
	 * @param array $variables
	 * @param bool $print
	 * @return mixed
	 */
	function TEMPLATE(string $Name, bool $print = true, string|null $dir = null, array $variables = [], int $fromSeq = 0, int $count = 999999999){
		try{ applyPrepends("TEMPLATE", $Name);
			return forceUSING($dir??\_::$TEMPLATE_DIR, \_::$BASE_TEMPLATE_DIR, $Name, $print, $variables, $fromSeq, $count);
        } finally{ applyAppends("TEMPLATE", $Name); }
	}
	/**
	 * To interprete, the specified viewname
	 * @param non-empty-lowercase-string $name
	 * @param array $variables
	 * @param bool $print
	 * @return mixed
	 */
	function VIEW(string $name, bool $print = true, string|null $dir = null, array $variables = [], int $fromSeq = 0, int $count = 999999999){
		try{ applyPrepends("VIEW", $name);
			$output = executeCommands(forceUSING($dir??\_::$VIEW_DIR, \_::$BASE_VIEW_DIR, $name, false, $variables, $fromSeq, $count));
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
	function PAGE(string $name, bool $print = true, string|null $dir = null, array $variables = [], int $fromSeq = 0, int $count = 999999999){
		try{ applyPrepends("PAGE", $name);
			return forceUSING($dir??\_::$PAGE_DIR, \_::$BASE_PAGE_DIR, $name, $print, $variables, $fromSeq, $count);
        } finally{ applyAppends("PAGE", $name); }
	}
	/**
	 * To interprete, the specified regionname
	 * @param non-empty-lowercase-string $name
	 * @param array $variables
	 * @param bool $print
	 * @return mixed
	 */
	function REGION(string $name, bool $print = true, string|null $dir = null, array $variables = [], int $fromSeq = 0, int $count = 999999999){
		try{ applyPrepends("REGION", $name);
			return forceUSING($dir??\_::$REGION_DIR, \_::$BASE_REGION_DIR, $name, $print, $variables, $fromSeq, $count);
        } finally{ applyAppends("REGION", $name); }
	}
	/**
	 * To interprete, the specified partname
	 * @param non-empty-lowercase-string $name
	 * @param array $variables
	 * @param bool $print
	 * @return mixed
	 */
	function PART(string $name, bool $print = true, string|null $dir = null, array $variables = [], int $fromSeq = 0, int $count = 999999999){
		try{ applyPrepends("PART", $name);
			return forceUSING($dir??\_::$PART_DIR, \_::$BASE_PART_DIR, $name, $print, $variables, $fromSeq, $count);
        } finally{ applyAppends("PART", $name); }
	}

	/**
	 * Process and convert to string everythings
	 * @param mixed $textOrObject The target object tot do process
	 * @param bool $translation Do translation
	 * @param bool $styling Do style and strongify the keywords
	 * @return string|null
	 */
	function __(mixed $textOrObject, bool $translation = true, bool $styling = true) :string|null {
		$textOrObject = \MiMFa\Library\Convert::ToString($textOrObject);
		if($translation && \_::$CONFIG->AllowTranslate) $textOrObject = \MiMFa\Library\Translate::Get($textOrObject);
		if($styling && \_::$CONFIG->AllowTextAnalyzing) $textOrObject = \MiMFa\Library\Style::DoStrong($textOrObject);
		return $textOrObject;
	}

	function go($url){
        echo "<html><head><script>window.location.assign(".(isValid($url)?"'".\MiMFa\Library\Local::GetUrl($url)."'":"location.href").");</script></head></html>";
    }
	function reload(){
        load(\_::$URL);
    }
	function load($url = null){
        echo "<script>window.location.assign(".(isValid($url)?"`".\MiMFa\Library\Local::GetUrl($url)."`":"location.href").");</script>";
    }
	function open($url = null, $target = "_blank"){
        echo "<script>window.open(".(isValid($url)?"'".\MiMFa\Library\Local::GetUrl($url)."'":"location.href").", '$target');</script>";
    }
	function share($urlOrText = null, $path = null){
        echo "<script>window.open('sms://$path?body='+".(isValid($urlOrText)?"'".__($urlOrText, styling:false)."'":"location.href").", '_blank');</script>";
    }
	function alert($message){
        echo "<script>alert(`".__($message, styling:false)."`);</script>";
    }

	/**
     * Do a loop action by a callable function on a countable element
     * @param mixed $array
     * @param callable $action The loop action $action($key, $value, $index)
 * @return array
     */
	function loop($array, callable $action, $nullValues = false)
	{
		return iterator_to_array(iteration($array, $action, $nullValues));
	}
	/**
     * Do a loop action by a callable function on a countable element
     * @param mixed $array
     * @param callable $action The loop action $action($key, $value, $index)
     * @return iterable
     */
	function iteration($array, callable $action, $nullValues = false)
	{
		$i = 0;
		if(!is_iterable($array)){
            if(($res = $action($i, $array, $i)) !== null || $nullValues)
				yield $res;
        }
		else foreach ($array as $key=>$value)
            if(($res = $action($key, $value, $i++)) !== null || $nullValues)
				yield $res;
    }
	/**
	 * Returns the value of the first array element.
	 * @param array|object|iterable|Generator|null $array
	 * @return mixed
	 */
	function first($array, $default = null){
		if(is_array($array)) return count($array)>0?$array[array_key_first($array)]:$default;
		if(is_iterable($array)) {
			foreach ($array as $value) return $value;
            return $default;
        }
		$res = reset($array);
		if($res === false) return $default;
        return $res;
    }
	/**
     * Returns the value of the last array element.
     * @param array|object|iterable|Generator|null $array
     * @return mixed
     */
	function last($array, $default = null){
		if(is_array($array)) return count($array)>0?$array[array_key_last($array)]:$default;
		if(is_iterable($array)) {
			foreach ($array as $value) $default = $value;
            return $default;
        }
		$res = end($array);
		if($res === false) return $default;
        return $res;
    }

	function code($html, &$dic = null, $startCode = "<", $endCode = ">", $pattern = "/(\<\S+[\w\W]*\>)|(([\"'])\S+[\w\W]*\\3)|(\d*\.?\d+)/iU")
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

	function startsWith(string|null $haystack, string|null ...$needles):bool {
		foreach ($needles as $needle)
            if(!is_null($needle) && substr_compare($haystack, $needle, 0, strlen($needle)) === 0)
				return $needle||true;
		return false;
	}
	function endsWith(string|null $haystack, string|null ...$needles):bool {
		foreach ($needles as $needle)
            if(!is_null($needle) && substr_compare($haystack, $needle, -strlen($needle)) === 0) return $needle||true;
		return false;
	}

	function getId($random = false):int
	{
		if(!$random) return ++\_::$DYNAMIC_ID;
		list($usec, $sec) = explode(" ", microtime());
		return (int)($usec*10000000+$sec);
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

	/**
	* Get the full part of a url pointed to catch status
	* Example: "https://www.mimfa.net:5046/Category/mimfa/service/web.php?p=3&l=10#serp"
	* @return string|null
	*/
	function getFullUrl(string|null $path = null, bool $optimize = true):string|null{
		if($path === null) $path = getUrl();
		return \MiMFa\Library\Local::GetFullUrl($path, $optimize);
	}
	/**
     * Get the full part of a url
     * Example: "https://www.mimfa.net:5046/Category/mimfa/service/web.php?p=3&l=10#serp"
     * @return string|null
     */
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
		return preg_replace("/^([\/\\\])/",rtrim(GetHost(),"/\\")."$1",$path);
	}
	/**
	* Get the host part of a url
	* Example: "https://www.mimfa.net:5046"
	* @return string|null
	*/
	function getHost(string|null $path = null):string|null{
		$pat = "/^\w+\:\/*[^\/]+/";
		if($path == null || !preg_match($pat,$path)) $path = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'];
		return PREG_Find($pat, $path);
	}
	/**
	* Get the site name part of a url
	* Example: "www.mimfa.net"
	* @return string|null
	*/
	function getSite(string|null $path = null):string|null{
		return PREG_replace("/(^\w+:\/*)|(\:\d+$)/","", getHost($path));
	}
	/**
	* Get the domain name part of a url
	* Example: "mimfa.net"
	* @return string|null
	*/
	function getDomain(string|null $path = null):string|null{
		return PREG_replace("/(^\w+:\/*(www\.)?)|(\:\d+$)/","", getHost($path));
	}
	/**
	* Get the path part of a url
	* Example: "https://www.mimfa.net/Category/mimfa/service/web.php"
	* @return string|null
	*/
	function getPath(string|null $path = null):string|null{
		return PREG_Find("/(^[^\?#]*)/", $path??getUrl());
	}
	/**
	* Get the request part of a url
	* Example: "/Category/mimfa/service/web.php?p=3&l=10#serp"
	* @return string|null
	*/
	function getRequest(string|null $path = null):string|null{
		if($path == null) $path = getUrl();
		if(startsWith($path,\_::$BASE_DIR)) $path = substr($path, strlen(\_::$BASE_DIR));
		return PREG_Replace("/(^\w+:\/*[^\/]+)/","", $path);
	}
	/**
	* Get the relative address from a url
	* Example: "Category/mimfa/service/web.php?p=3&l=10#serp"
	* @return string|null
	*/
	function getRelative(string|null $path = null):string|null{
		if($path == null) $path = getUrl();
		if(startsWith($path,\_::$BASE_DIR)) return substr($path, strlen(\_::$BASE_DIR));
		return PREG_Replace("/^\w+:\/*[^\/]+/","", $path);
	}
	/**
     * Get the direction part of a url from the root
     * Example: "Category/mimfa/service/web.php"
     * @return string|null
     */
	function getDirection(string|null $path = null):string|null{
		if($path == null) $path = getUrl();//ltrim($_SERVER["REQUEST_URI"],"\\\/");
		if(startsWith($path,\_::$BASE_DIR)) $path = substr($path, strlen(\_::$BASE_DIR));
		return PREG_Replace("/(^\w+:\/*[^\/]+\/)|([\?#].+$)/","", $path);
	}
	/**
     * Get the last part of a direction url
     * Example: "web.php"
     * @return string|null
     */
	function getPage(string|null $path = null):string|null{
		return last(explode("/", getDirection($path)));
	}
	/**
	* Get the query part of a url
	* Example: "p=3&l=10"
	* @return string|null
	*/
	function getQuery(string|null $path = null):string|null{
		return PREG_Find("/((?<=\?)[^#]*($|#))/", $path??getUrl());
	}
	/**
	* Get the fragment or anchor part of a url
	* Example: "serp"
	* @return string|null
	*/
	function getFragment(string|null $path = null):string|null{
		return PREG_Find("/((?<=#)[^\?]*($|\?))/", $path??getUrl());
	}
	/**
	* Create an email account
	* Example: "do-not-reply@mimfa.net"
	* @return string|null
	*/
	function getEmail(string|null $path = null, $mailName = "do-not-reply"):string|null{
		return $mailName."@".getDomain($path);
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
		$arr = is_array($source)? $source : explode("\n", $source);
		$f = false;
		$res = "";
		foreach($arr as $i => $line) {
			$line = trim($line);
			if(strpos($line,$key)===0){
				$res = trim(substr($line, strlen($key)));
				$f = $ismultiline;
				if(!$f) break;
			} elseif($f) {
				if(strpos($line,"	")===0) $res .= PHP_EOL."\t".trim($line);
				else break;
			}
		}
		return trim($res);
	}

	function isEmpty($obj):bool{
		return !isset($obj) || is_null($obj) || (is_string($obj) && (trim($obj.""," \n\r\t\v\f'\"") === "")) || (is_array($obj) && count($obj) === 0);
	}
	function isValid($obj, string|null $item = null):bool{
		if($item === null) return isset($obj) && !is_null($obj) && (!is_string($obj) || !(trim($obj) == "" || trim($obj,"'\"") == ""));
		elseif(is_array($obj)) return isValid($obj) && isValid($item) && isset($obj[$item]) && isValid($obj[$item]);
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
	function getBetween($obj, ...$items){
		foreach ($items as $value)
			if(($value = getValid($obj, $value, null)) !== null) return $value;
		return null;
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
     * Check file format by thats extension
     * @param null|string $path
     * @param array<string> $formats
     * @return string|bool
     */
	function isFormat(string|null $path, string|array ...$formats){
		$p = getPath(strtolower($path));
		foreach ($formats as $format)
			if(is_countable($format)){
                foreach ($format as $forma)
					if($forma = isFormat($p, $forma)) return $forma;
			} elseif(endsWith($p, strtolower($format))) return $format;
		return false;
    }

	/**
     * Check if the string is a relative or absolute file URL
     * @param null|string $url The url string
     * @return bool
     */
	function isFile(string|null $url, string ...$formats):bool{
		if(count($formats) == 0) array_push($formats, \_::$CONFIG->AcceptableFileFormats, \_::$CONFIG->AcceptableDocumentFormats, \_::$CONFIG->AcceptableImageFormats, \_::$CONFIG->AcceptableAudioFormats, \_::$CONFIG->AcceptableVideoFormats);
		return isUrl($url) && isFormat($url, $formats);
	}
	/**
	 * Check if the string is a relative or absolute URL
	 * @param null|string $url The url string
	 * @return bool
	 */
	function isUrl(string|null $url):bool{
		return (!empty($url)) && preg_match("/^([A-z0-9\-]+\:)?([\/\?\#]([^:\/\{\}\|\^\[\]\"\`\r\n\t\f]*)|(\:\d))+$/",$url);
	}
	/**
	 * Check if the string is only a relative URL
	 * @param null|string $url The url string
	 * @return bool
	 */
	function isRelativeUrl(string|null $url):bool{
		return (!empty($url)) && preg_match("/^([\/\?\#]([^:\/\{\}\|\^\[\]\"\`\r\n\t\f]*)|(\:\d))+$/",$url);
	}
	/**
	 * Check if the string is only an absolute URL
     * @param null|string $url The url string
     * @return bool
     */
	function isAbsoluteUrl(string|null $url):bool{
		return (!empty($url)) && preg_match("/^[A-z0-9\-]+\:\/*([\/\?\#][^\/\{\}\|\^\[\]\"\`\r\n\t\f]*)+$/",$url);
	}
	/**
     * Check if the string is script or not
	 * @param null|string $script The url string
     * @return bool
     */
	function isScript(string|null $script):bool{
		return (!empty($script))
			&& !preg_match("/^[A-z0-9\-\.\_]+\@([A-z0-9\-\_]+\.[A-z0-9\-\_]+)+$/",$script)
			&& !preg_match("/^[A-z0-9\-]+\:\/*([\/\?\#][^\/\{\}\|\^\[\]\"\`\r\n\t\f]*)+$/",$script)
			&& preg_match("/[\{\}\|\^\[\]\"\`\;\r\n\t\f]|((^\s*[\w\$][\w\d\$\_\.]+\s*\([\s\S]*\)\s*)+;?\s*$)/",$script);
	}
	/**
     * Check if the string is a relative or absolute URL
     * @param null|string $url The url string
     * @return bool
     */
	function isEmail(string|null $email):bool{
		return (!empty($url)) && preg_match("/^[A-z0-9\-\.\_]+\@([A-z0-9\-\_]+\.[A-z0-9\-\_]+)+$/",$url);
	}

	/**
     * Check if the string is a suitable name for a class or id or name field
     * @param null|string $text The url string
     * @return bool
     */
	function isIdentifier(string|null $text):bool{
		return (!empty($text)) && preg_match("/^[A-z_\$][A-z0-9_\-\$]*$/",$text);
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
		preg_match_all($pattern, $text, $matches);
		return isset($matches[0][0])?$matches[0][0]:$def;
	}
	/**
	 * Regular Expression Find all matches by pattern
	 * @param mixed $pattern
	 * @param string|null $text
	 * @return array|null
	 */
	function preg_find_all($pattern, string|null $text):array|null{
		preg_match_all($pattern, $text, $matches);
		return $matches[0];
	}

	/**
	 * Insert into an array
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
	/**
     * Find something from an array by a callable function
     * @param array      $array
     * @param callable $searching function($key, $val){ return true; }
     * @param int|string|null $array_find_key
     */
	function array_find_key($array, callable $searching)
	{
		return array_key_first(array_filter($array, function($k,$v)use($searching){ return $searching($v, $k);}, ARRAY_FILTER_USE_BOTH));
	}
	/**
     * Find everythings are match from an array by a callable function
     * @param array      $array
     * @param callable $searching function($key, $val){ return true; }
     * @param array $array_find_keys
     */
	function array_find_keys($array, callable $searching)
	{
		return array_filter($array, function($k,$v)use($searching){ return $searching($v, $k);}, ARRAY_FILTER_USE_BOTH);
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
		echo "<br>SITE: "._::$SITE;
		echo "<br>PATH: "._::$PATH;
		echo "<br>REQUEST: "._::$REQUEST;
		echo "<br>DIRECTION: "._::$DIRECTION;
		echo "<br>QUERY: "._::$QUERY;
		echo "<br>FRAGMENT: "._::$FRAGMENT;
		echo "<br>EMAIL: "._::$EMAIL;
	}
	function test_Access($func,$res=null){
		$r = null;
		if(ACCESS(0, false, false)){
			if($r = $func()) echo "<b>TRUE: ".($r??$res)."</b><br>";
			else echo "FALSE: ".$res."<br>";
		}
	}
	//End Test Region
?>