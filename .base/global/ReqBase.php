<?php

use MiMFa\Library\Internal;

/**
 * The Global Static Variables and Functions
 * You need to indicate and handle requests
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/ReqBase See the Documentation
 */
abstract class ReqBase
{
	/**
	 * Full part of the current url
	 * @example: "https://www.mimfa.net:5056/Category/mimfa/service/web.php?p=3&l=10#serp"
	 * @var string|null
	 */
	public static string|null $Url = null;
	/**
	 * The path part of the current url
	 * @example: "https://www.mimfa.net:5056/Category/mimfa/service/web.php"
	 * @var string|null
	 */
	public static string|null $Path = null;
	/**
	 * The host part of the current url
	 * @example: "https://www.mimfa.net:5056"
	 * @var string|null
	 */
	public static string|null $Host = null;
	/**
	 * The site name part of the current url
	 * @example: "www.mimfa.net"
	 * @var string|null
	 */
	public static string|null $Site = null;
	/**
	 * The domain name part of the current url
	 * @example: "mimfa.net"
	 * @var string|null
	 */
	public static string|null $Domain = null;
	/**
	 * The request part of the current url
	 * @example: "/Category/mimfa/service/web.php?p=3&l=10#serp"
	 * @var string|null
	 */
	public static string|null $Request = null;
	/**
	 * The direction part of the current url from the root
	 * @example: "Category/mimfa/service/web.php"
	 * @var string|null
	 */
	public static string|null $Direction = null;
	/**
	 * The last part of the current direction url
	 * @example: "web.php"
	 * @var string|null
	 */
	public static string|null $Page = null;
	/**
	 * The query part of the current url
	 * @example: "p=3&l=10"
	 * @var string|null
	 */
	public static string|null $Query = null;
	/**
	 * The fragment or anchor part of the current url
	 * @example: "serp"
	 * @var string|null
	 */
	public static string|null $Fragment = null;


	/**
	 * Receive requests from the client side
	 * @param mixed $key The key of the received value
	 * @param array|string|null $source The the received data source $_GET/$POST/$_FILES/... (by default it is $_REQUEST)
	 * @return mixed The value
	 */
	public static function Receive($key = null, array|string|null $source = null, $default = null)
	{
		if (is_null($source)) $source = getMethodName();
		// if (isEmpty($_REQUEST))
		// 	parse_str(file_get_contents('php://input'), $source);
		// else $source = $_REQUEST;
		if (is_string($source))
			switch (trim(strtolower($source))) {
				case "file":
				case "files":
					$source = $_FILES;
				case "public":
				case "get":
					$source = $_GET;
					break;
				case "private":
				case "post":
					$source = $_POST;
					break;
				case "put":
				case "patch":
				case "delete":
					$res = file_get_contents('php://input');
					if (!isEmpty($res)) {
						if (isJson($res))
							$source = \MiMFa\Library\Convert::FromJson($res) ?? $source;
						else if (strpos($_SERVER['CONTENT_TYPE'], 'multipart/form-data') !== false)
							$source = \MiMFa\Library\Convert::FromFormData($res, $_FILES) ?? $source;
						else
							parse_str($res, $source);
					}
					$_REQUEST = $source = is_array($source)? $source:[$source];
					break;
				default:
					if(strtoupper($source) == getMethodName()) {
						if($source = $_POST) break;
						$res = file_get_contents('php://input');
						if (!isEmpty($res)) {
							if (isJson($res))
								$source = \MiMFa\Library\Convert::FromJson($res) ?? $source;
							else if (strpos($_SERVER['CONTENT_TYPE'], 'multipart/form-data') !== false)
								$source = \MiMFa\Library\Convert::FromFormData($res, $_FILES) ?? $source;
							else
								parse_str($res, $source);
						}
						$_REQUEST = $source = is_array($source)? $source:[$source];
					}
					break;
			}
		if (is_null($key))
			return (count($source) > 0 ? $source : $default) ?? [];
		else
			return getValid($source, $key, $default);
	}
	/**
	 * Receive requests from the client side then remove it
	 * @param mixed $key The key of the received value
	 * @param array|string|null $source The the received data source $_GET/$POST/$_FILES/... (by default it is $_REQUEST)
	 * @return mixed The value
	 */
	public static function Grab($key = null, array|string|null $source = null, $default = null)
	{
		$val = null;
		if (is_null($key)) {
			$val = [];
			foreach (self::Receive($key, $source, $default ?? []) as $key => $value)
				$val[$key] = $value;
			if (is_string($source))
				switch (trim(strtolower($source))) {
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
			$val = self::Receive($key, $source, $default);
			unset($_POST[$key]);
			unset($_GET[$key]);
			unset($_REQUEST[$key]);
			unset($_FILES[$key]);
		}
		return $val;
	}
	
	/**
	 * Received input from the client side
	 * @param mixed $key The getted data key
	 * @return mixed Received data
	 */
	public static function ReceiveGet($key = null, $default = null)
	{
		if (is_string($key ?? ""))
			return self::Receive($key, "get", $default);
		return $default;
	}
	/**
	 * Received posted values from the client side
	 * @param mixed $key The posted data key
	 * @return mixed Received data
	 */
	public static function ReceivePost($key = null, $default = null)
	{
		if (is_string($key ?? ""))
			return self::Receive($key, "post", $default);
		return $default;
	}
	/**
	 * Received putted values from the client side
	 * @param mixed $key The putted data key
	 * @return mixed Received data
	 */
	public static function ReceivePut($key = null, $default = null)
	{
		if (is_string($key ?? ""))
			return self::Receive($key, "put", $default);
		return $default;
	}
	/**
	 * Received patched values from the client side
	 * @param mixed $key The patched data key
	 * @return mixed Received data
	 */
	public static function ReceivePatch($key = null, $default = null)
	{
		if (is_string($key ?? ""))
			return self::Receive($key, "patch", $default);
		return $default;
	}
	/**
	 * Received file values from the client side
	 * @param mixed $key The file data key
	 * @return mixed Received data
	 */
	public static function ReceiveFile($key = null, $default = null)
	{
		if (is_string($key ?? ""))
			return self::Receive($key, $_FILES, $default);
		return $default;
	}
	/**
	 * Received deleted values from the client side
	 * @param mixed $key The deleted data key
	 * @return mixed Received data
	 */
	public static function ReceiveDelete($key = null, $default = null)
	{
		if (is_string($key ?? ""))
			return self::Receive($key, "delete", $default);
		return $default;
	}
	/**
	 * Received stream values from the client side
	 * @param mixed $key The internal data key
	 * @return mixed Received data
	 */
	public static function ReceiveStream($key = null, $default = null)
	{
		if (is_string($key ?? ""))
			return self::Receive($key, "stream", $default);
		return $default;
	}
	/**
	 * Received internal values from the client side
	 * @param mixed $key The internal data key
	 * @return mixed Received data
	 */
	public static function ReceiveInternal($key = null, $default = null)
	{
		if (is_string($key ?? ""))
			return self::Receive($key, "internal", $default);
		return $default;
	}
	/**
	 * Received external values from the client side
	 * @param mixed $key The internal data key
	 * @return mixed Received data
	 */
	public static function ReceiveExternal($key = null, $default = null)
	{
		if (is_string($key ?? ""))
			return self::Receive($key, "external", $default);
		return $default;
	}
}

\ReqBase::$Url = getUrl();
\ReqBase::$Host = getHost();
\ReqBase::$Site = getSite();
\ReqBase::$Domain = getDomain();
\ReqBase::$Path = getPath();
\ReqBase::$Request = getRequest();
\ReqBase::$Direction = getDirection();
\ReqBase::$Page = getPage();
\ReqBase::$Query = getQuery();
\ReqBase::$Fragment = getFragment();
?>