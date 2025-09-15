<?php

/**
 * The Global Static Variables
 * It contains the most useful objects along developments
 * Also contains all the Global Static Variables and Functions You need to indicate and handle requests and responses
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Globals See the Documentation
 */
class _
{
	public static int $DynamicId = 0;
	/**
	 * The version of aseqbase framework
	 * Generation	.	Major	Minor	1:test|2:alpha|3:beta|4:release|5<=9:stable|0:base
	 * X			.	xx		xx		x
	 */
	public static float $Version = 4.00000;
	/**
	 * The default files extensions
	 * @example: ".php"
	 */
	public static string|null $Extension = ".php";



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
	 * A Path=>Fucntion array to apply the Function before using the Path
	 * @var mixed
	 */
	public static array $Prepends = array();
	/**
	 * A Path=>Fucntion array to apply the Function after using the Path
	 * @var mixed
	 */
	public static array $Appends = array();

	/**
	 * All sequences from aseq to base
	 * @example: [
	 *	'home/domain/aseq/' => 'https://aseq.domain.tld/',
	 *	'home/domain/1stseq/' => 'https://1stseq.domain.tld/',
	 *	'home/domain/2ndseq/' => 'https://2ndseq.domain.tld/',
	 *	'home/domain/3rdseq/' => 'https://3rdseq.domain.tld/',
	 *	'home/domain/base/' => 'https://base.domain.tld/'
	 *]
	 */
	public static array $Sequences;

	/**
	 * To access all the website configurations
	 */
	public static Configuration $Config;

	/**
	 * To access all the website information
	 */
	public static Information $Info;

	/**
	 * To access all back-end tools
	 */
	public static Back $Back;

	/**
	 * To access all front-end tools
	 */
	public static Front $Front;

	/**
	 * To access all addresses to a sequence of the website
	 */
	public static AddressBase $Aseq;
	/**
	 * To access all addresses to the base of the website,
	 * and the dinal sequence of the website
	 */
	public static AddressBase $Base;

	/**
	 * To access all basic directory names
	 */
	public static AddressBase $Address;
}

#region INITIALIZING

require_once(__DIR__ . DIRECTORY_SEPARATOR . "global" . DIRECTORY_SEPARATOR . "AddressBase.php");

\_::$Address = new AddressBase();

\_::$Sequences = [
	str_replace(["\\", "/"], DIRECTORY_SEPARATOR, $GLOBALS["DIR"] ?? "")
	=> str_replace(["\\", "/"], "/", $GLOBALS["ROOT"] ?? ""),
	...($GLOBALS["SEQUENCES"] ?? []),
	str_replace(["\\", "/"], DIRECTORY_SEPARATOR, __DIR__ . DIRECTORY_SEPARATOR ?? "")
	=> str_replace(["\\", "/"], "/", $GLOBALS["BASE_ROOT"] ?? "")
];

run("global/Base");
run("global/Types");

run("Address");

\_::$Aseq = new Address(
	$GLOBALS["ASEQBASE"],
	$GLOBALS["DIR"],
	getHost() . "/"//??$GLOBALS["ROOT"]
);

\_::$Base = new Address(
	$GLOBALS["BASE"],
	__DIR__ . DIRECTORY_SEPARATOR,
	$GLOBALS["BASE_ROOT"]
);

\_::$Url = getUrl();
\_::$Host = getHost();
\_::$Site = getSite();
\_::$Domain = getDomain();
\_::$Path = getPath();
\_::$Request = getRequest();
\_::$Direction = getDirection();
\_::$Page = getPage();
\_::$Query = getQuery();
\_::$Fragment = getFragment();

library("Local");
library("Convert");
library("Html");
library("Style");
library("Script");
library("Internal");

run("global/ConfigurationBase");
run("Configuration");
\_::$Config = new Configuration();

run("global/BackBase");
run("Back");
\_::$Back = new Back();

run("global/InformationBase");
run("Information");
\_::$Info = new Information();

run("global/FrontBase");
run("Front");
\_::$Front = new Front();

\MiMFa\Library\Local::CreateDirectory(\_::$Aseq->LogDirectory);
\MiMFa\Library\Local::CreateDirectory(\_::$Aseq->TempDirectory);
register_shutdown_function('cleanupTemp', false);

#endregion 


#region SENDING

/**
 * Send values to the client side
 * @param string $method The Method to send data
 * @param mixed $path The Url to send data
 * @param mixed $data Desired data
 * @return bool|string Its sent or received response
 */
function send($method = null, $path = null, ...$data)
{
	if (isEmpty($path))
		$path = getPath();
	if (isEmpty($method))
		$method = "POST";
	else
		$method = strtoupper($method);

	if (is_string($path) && isAbsoluteUrl($path)) {
		$ch = curl_init($path);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Data to be posted
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Set a timeout to avoid hanging indefinitely
		$response = curl_exec($ch);
		if (curl_errno($ch)) {
			$errorMessage = curl_error($ch);
			curl_close($ch);
			trigger_error("cURL Error: $errorMessage", E_USER_WARNING);
			return false;
		}
		curl_close($ch);
		return $response;
	}
	return false;
}
/**
 * Send values to the client side
 * @param mixed $path The Url to send GET data from that
 * @param mixed $data Additional data to send as query parameters
 * @return bool|string Its sent or received response
 */
function sendGet($path = null, ...$data)
{
	if (isEmpty($path))
		$path = getPath();
	if (is_string($path) && isAbsoluteUrl($path)) {
		$ch = curl_init();
		$queryParams = http_build_query($data);
		$urlWithParams = $path . (strpos($path, '?') === false ? '?' : '&') . $queryParams;
		curl_setopt($ch, CURLOPT_URL, $urlWithParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}
	return false;
}
/**
 * Send posted values to the client side
 * @param mixed $path The Url to send POST data to that
 * @param mixed $data Desired data to POST
 * @return bool|string Its sent or received response
 */
function sendPost($path = null, ...$data)
{
	if (isEmpty($path))
		$path = getPath();
	if (is_string($path) && isAbsoluteUrl($path)) {
		$ch = curl_init($path);
		curl_setopt($ch, CURLOPT_POST, true); // Use POST method
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Data to be posted
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}
	return false;
}
/**
 * Send putted values to the client side
 * @param mixed $path The Url to send PUT data to that
 * @param mixed $data Desired data to PUT
 * @return bool|string Its sent or received response
 */
function sendPut($path = null, ...$data)
{
	return send("put", $path, $data);
}
/**
 * Send patched values to the client side
 * @param mixed $path The Url to send PATCH data to that
 * @param mixed $data Desired data to PATCH
 * @return bool|string Its sent or received response
 */
function sendPatch($path = null, ...$data)
{
	return send("patch", $path, $data);
}
/**
 * Send file values to the client side
 * @param mixed $path The Url to send FILE data to that
 * @param mixed $data Desired data to FILE
 * @return bool|string Its sent or received response
 */
function sendFile($path = null, ...$data)
{
	if (isEmpty($path))
		$path = getPath();
	if (is_string($path) && isAbsoluteUrl($path)) {
		$ch = curl_init($path);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
		$fields = [];
		foreach ($data as $key => $value) {
			if (is_file($value)) {
				$fields[$key] = curl_file_create($value);
			} else {
				$fields[$key] = $value;
			}
		}
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}
	return false;
}
/**
 * Send delete values to the client side
 * @param mixed $path The Url to send DELETE data to that
 * @param mixed $data Desired data to DELETE
 * @return bool|string Its sent or received response
 */
function sendDelete($path = null, ...$data)
{
	return send("delete", $path, $data);
}
/**
 * Send stream values to the client side
 * @param mixed $path The Url to send STREAM data to that
 * @param mixed $data Desired data to STREAM
 * @return bool|string Its sent or received response
 */
function sendStream($path = null, ...$data)
{
	return send("stream", $path, $data);
}
/**
 * Send internal values to the client side
 * @param mixed $path The Url to send INTERNAL data to that
 * @param mixed $data Desired data to INTERNAL
 * @return bool|string Its sent or received response
 */
function sendInternal($path = null, ...$data)
{
	return send("internal", $path, $data);
}
/**
 * Send external values to the client side
 * @param mixed $path The Url to send EXTERNAL data to that
 * @param mixed $data Desired data to EXTERNAL
 * @return bool|string Its sent or received response
 */
function sendExternal($path = null, ...$data)
{
	return send("external", $path, $data);
}

#endregion


#region RECEIVING

/**
 * Receive requests from the client side then remove it
 * @param mixed $key The key of the received value
 * @param array|string|null $method The the received data source $_GET/$POST/$_FILES/... (by default it is $_REQUEST)
 * @return mixed The value
 */
function snapReceive($key = null, $default = null, array|string|null $method = null)
{
	$val = null;
	if (is_null($key)) {
		$val = [];
		foreach (receive($key, $default ?? [], $method) as $key => $value)
			$val[$key] = $value;
		if (is_string($method))
			switch (trim(strtolower($method))) {
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
		$val = receive($key, $default, $method);
		unset($_POST[$key]);
		unset($_GET[$key]);
		unset($_REQUEST[$key]);
		unset($_FILES[$key]);
	}
	return $val;
}

/**
 * Receive requests from the client side
 * @param mixed $key The key of the received value
 * @param array|string|null $method The the received data source $_GET/$POST/$_FILES/... (by default it is $_REQUEST)
 * @return mixed The value
 */
function receive($key = null, $default = null, array|string|null $method = null)
{
	if (is_null($method))
		$method = getMethodName();
	// if (isEmpty($_REQUEST)) parse_str(file_get_contents('php://input'), $method);
	// else $method = $_REQUEST;
	if (is_string($method))
		switch (trim(strtolower($method))) {
			case "file":
			case "files":
				$method = $_FILES;
			case "public":
			case "get":
				$method = $_GET;
				break;
			case "private":
			case "post":
				$method = $_POST;
				break;
			case "put":
			case "patch":
			case "delete":
				$res = file_get_contents('php://input');
				if (!isEmpty($res)) {
					if (isJson($res))
						$method = \MiMFa\Library\Convert::FromJson($res) ?? $method;
					else if (strpos($_SERVER['CONTENT_TYPE'], 'multipart/form-data') !== false)
						$method = \MiMFa\Library\Convert::FromFormData($res, $_FILES) ?? $method;
					else
						parse_str($res, $method);
				} else
					parse_str($res, $method);

				$_REQUEST = $method = is_array($method) ? $method : [$method];
				break;
			default:
				if (strtoupper($method) == getMethodName()) {
					if ($method = $_POST)
						break;
					$res = file_get_contents('php://input');
					if (!isEmpty($res)) {
						if (isJson($res))
							$method = \MiMFa\Library\Convert::FromJson($res) ?? $method;
						else if (strpos($_SERVER['CONTENT_TYPE'], 'multipart/form-data') !== false)
							$method = \MiMFa\Library\Convert::FromFormData($res, $_FILES) ?? $method;
						else
							parse_str($res, $method);
					} else
						parse_str($res, $method);

					$_REQUEST = $method = is_array($method) ? $method : [$method];
				}
				break;
		}
	if (count($method) == 1 && isset($method[0]))
		$method = decrypt($method[0]);
	if (is_null($key))
		return (count($method) > 0 ? $method : $default) ?? [];
	else
		return getValid($method, $key, $default);
}
/**
 * Received input from the client side
 * @param mixed $key The getted data key
 * @return mixed Received data
 */
function receiveGet($key = null, $default = null)
{
	if (is_string($key ?? ""))
		return receive($key, $default, "get");
	return $default;
}
/**
 * Received posted values from the client side
 * @param mixed $key The posted data key
 * @return mixed Received data
 */
function receivePost($key = null, $default = null)
{
	if (is_string($key ?? ""))
		return receive($key, $default, "post");
	return $default;
}
/**
 * Received putted values from the client side
 * @param mixed $key The putted data key
 * @return mixed Received data
 */
function receivePut($key = null, $default = null)
{
	if (is_string($key ?? ""))
		return receive($key, $default, "put");
	return $default;
}
/**
 * Received patched values from the client side
 * @param mixed $key The patched data key
 * @return mixed Received data
 */
function receivePatch($key = null, $default = null)
{
	if (is_string($key ?? ""))
		return receive($key, $default, "patch");
	return $default;
}
/**
 * Received file values from the client side
 * @param mixed $key The file data key
 * @return mixed Received data
 */
function receiveFile($key = null, $default = null)
{
	if (is_string($key ?? ""))
		return receive($key, $default, $_FILES);
	return $default;
}
/**
 * Received deleted values from the client side
 * @param mixed $key The deleted data key
 * @return mixed Received data
 */
function receiveDelete($key = null, $default = null)
{
	if (is_string($key ?? ""))
		return receive($key, $default, "delete");
	return $default;
}
/**
 * Received stream values from the client side
 * @param mixed $key The internal data key
 * @return mixed Received data
 */
function receiveStream($key = null, $default = null)
{
	if (is_string($key ?? ""))
		return receive($key, $default, "stream");
	return $default;
}
/**
 * Received internal values from the client side
 * @param mixed $key The internal data key
 * @return mixed Received data
 */
function receiveInternal($key = null, $default = null)
{
	if (is_string($key ?? ""))
		return receive($key, $default, "internal");
	return $default;
}
/**
 * Received external values from the client side
 * @param mixed $key The internal data key
 * @return mixed Received data
 */
function receiveExternal($key = null, $default = null)
{
	if (is_string($key ?? ""))
		return receive($key, $default, "external");
	return $default;
}

#endregion 


#region REQUESTING

/**
 * Interact with all specific parts of the client side
 * @param mixed $script The front JS codes
 * @param mixed $callback The call back handler
 * @example: request('$("body").html', function(selectedHtml)=>{ //do somework })
 */
function request($script = null, $callback = null)
{
	$callbackScript = "(data,err)=>document.querySelector('body').append(...((html)=>{el=document.createElement('qb');el.innerHTML=html;return el.childNodes;})(data??err))";
	$progressScript = "null";
	$timeout = 60000;
	$start = \MiMFa\Library\Internal::MakeStartScript(true);
	$end = \MiMFa\Library\Internal::MakeStartScript(true);
	$id = "S_" . getID(true);
	if (isStatic($callback))
		render(\MiMFa\Library\Html::Script("$start(" . $callbackScript . ")(" .
			\MiMFa\Library\Script::Convert($callback) . ",$script);document.getElementById('$id').remove();$end", null, ["id" => $id]));
	else
		render(\MiMFa\Library\Html::Script(
			$callback ? "$start" .
			'sendInternal(null,{"' . \MiMFa\Library\Internal::Set($callback) . '":JSON.stringify(' . $script . ")},'body',$callbackScript,$callbackScript,null,$progressScript,$timeout);document.getElementById('$id').remove();$end"
			: $script
			,
			null,
			["id" => $id]
		));
}
/**
 * Interact with all specific parts of the client side one by one
 * @param mixed $script The front JS codes
 * @param mixed $callback The call back handler
 * @example: iterateRequest("document.querySelectorAll('body input')", function(selectedItems)=>{ //do somework })
 */
function iterateRequest($script = null, $callback = null)
{
	$callbackScript = "(data,err)=>{el=document.createElement('qb');el.innerHTML=data??err;item.before(...el.childNodes);item.remove();}";
	$progressScript = "null";
	$timeout = 60000;
	$start = \MiMFa\Library\Internal::MakeStartScript(true);
	$end = \MiMFa\Library\Internal::MakeStartScript(true);
	$id = "S_" . getID(true);
	if (isStatic($callback))
		render(\MiMFa\Library\Html::Script("$start for(item of $script)(" . $callbackScript . ")(" .
			\MiMFa\Library\Script::Convert($callback) . ",item);document.getElementById('$id').remove();$end", null, ["id" => $id]));
	else
		render(\MiMFa\Library\Html::Script(
			$callback ? "$start" .
			"for(item of $script)sendInternal(null,{\"" . \MiMFa\Library\Internal::Set($callback) . '":item.outerHTML},' .
			"getQuery(item),$callbackScript,$callbackScript,null,$progressScript,$timeout);document.getElementById('$id').remove();$end"
			: $script
			,
			null,
			["id" => $id]
		));
}

/**
 * Have a dialog with the client side
 * @param mixed $script The front JS codes
 * @param mixed $callback The call back handler
 * @example: interact('$("body").html', function(selectedHtml)=>{ //do somework })
 * @return string|null The result of the client side
 */
function interact($script = null, $callback = null)
{
	$id = "Dialog_" . getId(true);
	request(
		"setMemo('$id', $script, 60000)",
		$callback
	);
	return grabMemo($id);
}
function alert($message = null, $callback = null)
{
	return injectScript(
		\MiMFa\Library\Script::Alert($message) . "??true",
		$callback
	);
}
function confirm($message = null, $callback = null)
{
	return interact(
		\MiMFa\Library\Script::Confirm($message),
		$callback
	);
}
function prompt($message = null, $callback = null, $default = null)
{
	return interact(
		\MiMFa\Library\Script::Prompt($message, $default),
		$callback
	);
}

#endregion 


#region RESPONSING

/**
 * To change the header in the client side
 * @param mixed $key The header key
 * @param mixed $value The header value
 * @return bool True if header is set, false otherwise
 */
function setHeader($key, $value)
{
	if (isValid($key) && !headers_sent()) {
		header("$key: $value");
		return true;
	}
	return false;
}
/**
 * To change the header content-type in the client side
 * @param mixed $value The header value
 * @return bool True if header is set, false otherwise
 */
function setContentType($value = null)
{
	if (!headers_sent()) {
		header("Content-Type: " . ($value ?? "text/html"));
		return true;
	}
	return false;
}
/**
 * To change the status of the results for the client side
 * @param mixed $status The header status
 * @return bool True if header is set, false otherwise
 */
function setStatus($status = null)
{
	if (isValid($status) && !headers_sent()) {
		header("HTTP/1.1 " . abs($status));
		return true;
	}
	return false;
}

/**
 * Print only this output on the client side, Clear before then end
 * @param mixed $output The data that is ready to print
 * @param mixed $status The header status
 */
function response($output = null, $status = null)
{
	if (ob_get_level()) ob_end_clean(); // Clean any remaining output buffers
	setStatus($status);
	if ($output)
		exit(\MiMFa\Library\Convert::ToString($output));
	else
		exit;
}
/**
 * Replace the output with all the document in the client side
 * @param mixed $output The data that is ready to print
 */
function replaceResponse($output = null)
{
	render(\MiMFa\Library\Html::Script(
		\MiMFa\Library\Internal::MakeScript(
			$output,
			null,
			"(data,err)=>{document.open();document.write(data??err);document.close();}"
		)
	));
}
/**
 * Print only this output on the client side then reload the page
 * @param mixed $output The data that is ready to print
 * @param mixed $status The header status
 * @param mixed $url The next url to show after rendering output
 */
function flipResponse($output = null, $status = null, $url = null)
{
	ob_clean();
	setStatus($status);
	exit(\MiMFa\Library\Convert::ToString($output) . "<script>window.location.assign(" . (isValid($url) ? "`" . \MiMFa\Library\Local::GetUrl($url) . "`" : "location.href") . ");</script>");
}

/**
 * Print only this JSON on the client side, Clear before then end
 * @param mixed $output The data that is ready to print
 * @param mixed $status The header status
 */
function responseJson($output = null, $status = null)
{
	if (ob_get_level())
		ob_end_clean(); // Clean any remaining output buffers
	setContentType("application/json");
	response(isJson($output) ? $output : \MiMFa\Library\Convert::ToJson($output), $status);
}
/**
 * Print only this XML on the client side, Clear before then end
 * @param mixed $output The data that is ready to print
 * @param mixed $status The header status
 */
function responseXml($output = null, $status = null)
{
	if (ob_get_level())
		ob_end_clean(); // Clean any remaining output buffers
	setContentType("application/xml");
	response(is_string($output) ? $output : \MiMFa\Library\Convert::ToXmlString($output), $status);
}
/**
 * Sends a file to the client side.
 * @param string $path The absolute or relative path to the file.
 * @param int|null $status The HTTP status code (e.g., 200, 404).
 * @param string|null $type The file content type (e.g., "application/pdf", "image/jpeg").
 * @param bool $attachment Whether to display the file inline in the browser or as an attachment.
 * @param string|null $name Optional filename to force download with a specific name.
 * @throws \Exception If the file path is invalid or the file cannot be read.
 */
function responseFile($path = null, $status = null, $type = null, bool $attachment = false, ?string $name = null)
{
	// Clear output buffer if active
	if (ob_get_level())
		ob_clean();

	$path = \MiMFa\Library\Local::GetFile($path);
	if ($path)
		setStatus($status);
	else {
		setStatus(404);
		exit;
	}
	if ($type)
		header("Content-Type: $type");
	else {
		// Attempt to guess content type if not provided
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$type = finfo_file($finfo, $path);
		finfo_close($finfo);
		header("Content-Type: " . $type);
	}
	// Sanitize the filename
	$n = explode("/", $path);
	$name = preg_replace('/[^\w\-.]/', '_', $name ?? end($n));
	$disposition = $attachment ? 'attachment' : 'inline';
	header("Content-Disposition: $disposition; filename=\"" . ($name ?? basename($path)) . '"');
	header('Content-Length: ' . filesize($path));
	//header("Etag: " . md5_file($path)); // Simple ETag (entity tag) response header is an identifier for a specific version of a resource. It lets caches be more efficient and save
	//Read and output the file
	readfile($path);
	exit;
}

/**
 * Render a message result output to the client side
 * @param mixed $output The data that is ready to print
 * @return mixed Printed data
 */
function responseMessage($output = null, $status = null)
{
	return response(\MiMFa\Library\Html::Result($output), $status);
}
/**
 * Render a success result output to the client side
 * @param mixed $output The data that is ready to print
 * @return mixed Printed data
 */
function responseSuccess($output = null, $status = 200)
{
	return response(\MiMFa\Library\Html::Success($output), $status);
}
/**
 * Render a warning result output to the client side
 * @param mixed $output The data that is ready to print
 * @return mixed Printed data
 */
function responseWarning($output = null, $status = 300)
{
	return response(\MiMFa\Library\Html::Warning($output), $status);
}
/**
 * Render an error result output to the client side
 * @param mixed $output The data that is ready to print
 * @return mixed Printed data
 */
function responseError($output = null, $status = 400)
{
	if (is_a($output, "Exception") || is_subclass_of($output, "Exception"))
		return response(\MiMFa\Library\Script::Error($output->getMessage()), $status);
	return response(\MiMFa\Library\Html::Error($output), $status);
}
/**
 * Execute console.log script
 * @param mixed $message
 * @return void
 */
function responseLog($message = null, $status = null)
{
	responseScript(\MiMFa\Library\Script::Log($message), $status);
}
/**
 * Execute console.log script
 * @param mixed $message
 * @return void
 */
function responseScript($message = null, $status = null)
{
	response(\MiMFa\Library\Html::Script($message), $status);
}
#endregion 


#region NAVIGATING

function locate($url = null)
{
	responseScript("window.history.replaceState(null, null, " . (empty($url) ? "location.href" : "`" . getFullUrl($url) . "`") . ");");
}
function relocate($url = null)
{
	responseScript("window.history.pushState(null, null, " . (empty($url) ? "location.href" : "`" . getFullUrl($url) . "`") . ");");
}
function go($url, $target = "_self")
{
	responseScript("window.open(" . (isValid($url) ? "'" . getFullUrl($url) . "'" : "location.href") . ", '$target');");
}
function open($url = null, $target = "_blank")
{
	response("<html><head><script>window.open(" . (isValid($url) ? "'" . getFullUrl($url) . "'" : "location.href") . ", '$target');</script></head></html>");
}
function load($url = null)
{
	responseScript("window.history.replaceState(null, null, " . (empty($url) ? "location.href" : "`" . getFullUrl($url) . "`") . ");window.location.reload();");
}
function reload()
{
	responseScript("window.location.reload();");
}
function share($urlOrText = null, $path = null)
{
	responseScript("window.open('sms://$path?body='+" . (isValid($urlOrText) ? "'" . __($urlOrText) . "'" : "location.href") . ", '_blank');");
}

#endregion 


#region RENDERING

/**
 * Echo output on the client side
 * @param mixed $output The data that is ready to print
 * @return mixed Printed data
 */
function render($output = null)
{
	echo $output = \MiMFa\Library\Convert::ToString($output);
	return $output;
}
/**
 * Render Scripts in the client side
 * @param mixed $output The data that is ready to print
 */
function renderScript($content, $source = null, ...$attributes)
{
	echo $output = \MiMFa\Library\Html::Script($content, $source, ...$attributes);
	return $output;
}
/**
 * Render Styles in the client side
 * @param mixed $output The data that is ready to print
 */
function renderStyle($content, $source = null, ...$attributes)
{
	echo $output = \MiMFa\Library\Html::Style($content, $source, ...$attributes);
	return $output;
}
/**
 * Render a message result output to the client side
 * @param mixed $output The data that is ready to print
 * @return mixed Printed data
 */
function renderMessage($output = null)
{
	echo $output = \MiMFa\Library\Html::Result($output);
	return $output;
}
/**
 * Render a success result output to the client side
 * @param mixed $output The data that is ready to print
 * @return mixed Printed data
 */
function renderSuccess($output = null)
{
	echo $output = \MiMFa\Library\Html::Success($output);
	return $output;
}
/**
 * Render a warning result output to the client side
 * @param mixed $output The data that is ready to print
 * @return mixed Printed data
 */
function renderWarning($output = null)
{
	echo $output = \MiMFa\Library\Html::Warning($output);
	return $output;
}
/**
 * Render an error result output to the client side
 * @param mixed $output The data that is ready to print
 * @return mixed Printed data
 */
function renderError($output = null)
{
	setStatus(400);
	if (is_a($output, "Exception") || is_subclass_of($output, "Exception"))
		return \MiMFa\Library\Html::Script(\MiMFa\Library\Script::Error($output->getMessage()));
	echo $output = \MiMFa\Library\Html::Error($output);
	return $output;
}
/**
 * Execute console.log script
 * @param mixed $message
 */
function renderLog($message = null)
{
	return renderScript(
		\MiMFa\Library\Script::Log($message)
	);
}


/**
 * Render Scripts in the client side
 * @param mixed $output The data that is ready to print
 */
function injectScript($content, $source = null, ...$attributes)
{
	render(\MiMFa\Library\Html::Script(
		\MiMFa\Library\Internal::MakeScript(
			\MiMFa\Library\Html::Script($content, $source, ...$attributes),
			null,
			"(data,err)=>$('head').append(data??err)"
			//"(data,err)=>document.querySelector('head').append(...((html)=>{el=document.createElement('qb');el.innerHTML=html;return el.childNodes;})(data??err))"
		)
	));
}
/**
 * Render Styles in the client side
 * @param mixed $output The data that is ready to print
 */
function injectStyle($content, $source = null, ...$attributes)
{
	render(\MiMFa\Library\Html::Script(
		\MiMFa\Library\Internal::MakeScript(
			\MiMFa\Library\Html::Style($content, $source, ...$attributes),
			null,
			"(data,err)=>document.querySelector('head').append(...((html)=>{el=document.createElement('qb');el.innerHTML=html;return el.childNodes;})(data??err))"
		)
	));
}
#endregion 


#region PROTECTION

/**
 * Check if the client has access to the page or assign them to other page, based on thair IP, Accessibility, Restriction and etc.
 * @param int|null $minaccess The minimum accessibility for the client, pass null to give the user access
 * @param bool $assign Assign clients to other page, if they have not enough access
 * @param bool $die Pass true to die the process if clients have not enough access, else pass false
 * @param int|string|null $die Die the process with this status if clients have not enough access
 * @return bool The client has accessibility bigger than $minaccess or not
 * @return int|mixed The user accessibility group
 */
function inspect($minaccess = 0, bool|string $assign = true, bool|string|int|null $exit = true): mixed
{
	if (isValid(\_::$Config->StatusMode)) {
		if ($assign) {
			if (is_string($assign))
				go($assign);
			else
				route(\_::$Config->StatusMode ?? \_::$Config->RestrictionRouteName, alternative: "403");
		}
		if ($exit !== false)
			exit($exit);
		return false;
	} elseif (isValid(\_::$Config->AccessMode)) {
		$ip = getClientIp();
		$cip = false;
		foreach (\_::$Config->AccessPatterns as $pat)
			if ($cip = preg_match($pat, $ip))
				break;
		if ((\_::$Config->AccessMode > 0 && !$cip) || (\_::$Config->AccessMode < 0 && $cip)) {
			if ($assign) {
				if (is_string($assign))
					go($assign);
				else
					route(\_::$Config->RestrictionRouteName, alternative: "401");
			}
			if ($exit !== false)
				exit($exit);
			return false;
		}
	}
	$b = auth($minaccess);
	if ($b !== false)
		return $b;
	if ($assign) {
		if (is_string($assign))
			go($assign);
		elseif (startsWith(\_::$Request, \MiMFa\Library\User::$HandlerPath))
			return true;
		else
			load(\MiMFa\Library\User::$InHandlerPath);
	}
	if ($exit !== false)
		exit($exit);
	return $b;
}
/**
 * Check if the user has access to the page or not
 * @param int|array|null $acceptableAccess The minimum accessibility for the user, pass null to give the user access
 * @return int|bool|null The user accessibility group
 */
function auth($minaccess = null): mixed
{
	if (!\_::$Back->User)
		return \MiMFa\Library\User::CheckAccess(null, $minaccess);
	else
		return \_::$Back->User->Access($minaccess);
}

#endregion


#region USING

/**
 * To include once a $path by the specific $data then return results or output
 * @param string $path
 * @param mixed $data
 * @param bool $print
 * @param mixed $default
 * @return mixed The results of including a path or the printed values if $print be false
 */
function including(string $path, mixed $data = [], bool $print = true, $default = null, bool $once = false)
{
	if (file_exists($path)) {
		if (!$print)
			ob_start();
		$result = [];
		if (endsWith($path, DIRECTORY_SEPARATOR)) {
			foreach (glob($path . "*" . \_::$Extension) as $file)
				if (!is_null($r = $once ? include_once $file : include $file))
					$result[] = $r;
		} else
			$result = $once ? include_once $path : include $path;
		if (!$print)
			return ob_get_clean();
		return $result;
	}
	if (is_callable($default) || $default instanceof \Closure)
		return ($default)($path, $data, $print);
	return $default;
}
/**
 * To require once a $path by the specific $data then return results or output
 * @param string $path
 * @param mixed $data
 * @param bool $print
 * @param mixed $default
 * @return mixed The results of requiring a path or the printed values if $print be false
 */
function requiring(string $path, mixed $data = [], bool $print = true, $default = null, bool $once = false)
{
	if (file_exists($path)) {
		if (!$print)
			ob_start();
		$result = [];
		if (endsWith($path, DIRECTORY_SEPARATOR)) {
			foreach (glob($path) as $file)
				if (!is_null($r = $once ? require_once $file : require $file))
					$result[] = $r;
		} else
			$result = $once ? require_once $path : require $path;
		if (!$print)
			return ob_get_clean();
		return $result;
	}
	if (is_callable($default) || $default instanceof \Closure)
		return ($default)($path, $data, $print);
	return $default;
}
/**
 * To seacrh and find the correct path of a file between all sequences
 * @param string|null $file The releative file path
 * @param mixed $extension The extention like ".php"
 * @param string|int $origin The start layer of the sequences (a zero started index)
 * @param int $depth How much layers it should iterate in searching
 * @return string|null The correct path of the file or null if its could not find
 */
function addressing(string|null $file = null, $extension = null, string|int $origin = 0, int $depth = 999999)
{
	$file = str_replace(["\\", "/"], DIRECTORY_SEPARATOR, $file ?? "");
	$extension = $extension ?? \_::$Extension;
	$file = preg_replace("/(?<!\\" . DIRECTORY_SEPARATOR . ")\\" . DIRECTORY_SEPARATOR . "$/", DIRECTORY_SEPARATOR . "index", $file);
	if (!endsWith($file, needles: $extension) && !endsWith($file, DIRECTORY_SEPARATOR))
		$file .= $extension;
	$path = null;
	//$toSeq = $depth < 0 ? (count(\_::$Sequences) + $depth) : ($origin + $depth);
	if (is_string($origin)) {
		take(\_::$Sequences, $origin, index: $origin);
		if (is_null($origin))
			$origin = 0;
	}
	$scount = count(\_::$Sequences);
	$origin = $origin < 0 ? ($scount + $origin) : min($scount, $origin);
	$toSeq = $depth < 0 ? ($scount + $depth) : min($scount, $origin + $depth);
	$seqInd = -1;
	$file = ltrim($file, DIRECTORY_SEPARATOR);
	foreach (\_::$Sequences as $dir => $host)
		if (++$seqInd < $origin)
			continue;
		elseif ($seqInd < $toSeq) {
			if (file_exists($path = $dir . $file))
				return $path;
		} else
			return null;
	return null;
}
/**
 * To seacrh in a specific directory in all sequences, to find a file with the name then including that
 * @param string|null $file The releative file path
 * @param mixed $extension The extention like ".php"
 * @param string|int $origin The start layer of the sequences (a zero started index)
 * @param int $depth How much layers it should iterate in searching
 * @return mixed The including results or null if its could not find
 */
function using(string|null $directory, string|null $name = null, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null, string|null $extension = null, bool $require = false, bool $once = false, &$used = null)
{
	try {
		renderPrepends($directory, $used = $name);
		if (
			$path =
			addressing("$directory$name", $extension, $origin, $depth) ??
			addressing($directory . ($used = $alternative), $extension, $origin, $depth)
		)
			return $require ?
				requiring(path: $path, data: $data, print: $print, default: $default, once: $once) :
				including(path: $path, data: $data, print: $print, default: $default, once: $once);
		else
			$used = null;
	} finally {
		renderAppends($directory, $name);
	}
}
/**
 * To grab a hierarchy of keys from the global $data object
 * @param array $hierarchy A hierarchy of desired keys
 */
function data(...$hierarchy)
{
	global $data;
	return grab($data, ...$hierarchy);
}

/**
 * Prepend something to any function or directory's files or actions
 * @param mixed $directory function name or directory
 * @param null|string $name file name
 * @param null|string|callable $value the action or content tou want to do
 */
function before($directory, string|null $name = null, null|string|callable $value = null)
{
	if (isValid($value)) {
		$directory = strtolower($directory ?? "");
		$name = strtolower($name ?? "");
		if (!isset(\_::$Prepends[$directory]))
			\_::$Prepends[$directory] = array();
		if (!isset(\_::$Prepends[$directory][$name]))
			\_::$Prepends[$directory][$name] = array();
		array_push(\_::$Prepends[$directory][$name], $value);
	}
}
/**
 * Append something to any function or directory's files or actions
 * @param mixed $directory function name or directory
 * @param null|string $name file name
 * @param null|string|callable $value the action or content tou want to do
 */
function after($directory, string|null $name = null, null|string|callable $value = null)
{
	if (isValid($value)) {
		$directory = strtolower($directory ?? "");
		$name = strtolower($name ?? "");
		if (!isset(\_::$Appends[$directory]))
			\_::$Appends[$directory] = array();
		if (!isset(\_::$Appends[$directory][$name]))
			\_::$Appends[$directory][$name] = array();
		array_push(\_::$Appends[$directory][$name], $value);
	}
}
function renderPrepends($directory, string|null $name = null)
{
	$directory = strtolower($directory ?? "");
	$name = strtolower($name ?? "");
	if (isset(\_::$Prepends[$directory][$name]))
		render(\_::$Prepends[$directory][$name]);
	elseif (isset(\_::$Prepends[$directory . $name]))
		render(\_::$Prepends[$directory . $name]);
}
function renderAppends($directory, string|null $name = null)
{
	$directory = strtolower($directory ?? "");
	$name = strtolower($name ?? "");
	if (isset(\_::$Appends[$directory][$name]))
		render(\_::$Appends[$directory][$name]);
	elseif (isset(\_::$Appends[$directory . $name]))
		render(\_::$Appends[$directory . $name]);
}

/**
 * To interprete, the specified file in all sequences
 * @param non-empty-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed
 */
function runAll(string|null $name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null, bool $require = false, bool $once = true)
{
	$depth = min($depth, count(\_::$Sequences)) - 1;
	$res = [];
	for (; $origin <= $depth; $depth--)
		$res[] = using(\_::$Address->Directory, $name, $data, $print, $depth, 1, $alternative, $default, require: $require, once: $once);
	return $res;
}
/**
 * To interprete, the specified path
 * @param non-empty-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed
 */
function run(string|null $name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null, bool $require = true, bool $once = true)
{
	return using(\_::$Address->Directory, $name, $data, $print, $origin, $depth, $alternative, $default, require: $require, once: $once);
}

/**
 * To interprete, the specified ModelName
 * @param non-empty-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed
 */
function model(string $name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->ModelDirectory, $name, $data, $print, $origin, $depth, $alternative, $default, once: true);
}
/**
 * To interprete, the specified LibraryName
 * @param non-empty-string $name
 * @param mixed $data
 * @param bool $print
 * @return string|null The complete name of selected library class or return null if it's not found
 */
function library(string $name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->LibraryDirectory, $name, $data, $print, $origin, $depth, $alternative, $default, once: true, used: $used) ? "\\MiMFa\\Template\\$used" : null;
}
/**
 * To interprete, the specified ComponentName
 * @param non-empty-string $name
 * @param mixed $data
 * @param bool $print
 * @return string|null The complete name of selected component class or return null if it's not found
 */
function component(string $name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->ComponentDirectory, $name, $data, $print, $origin, $depth, $alternative, $default, once: true, used: $used) ? "\\MiMFa\\Component\\$used" : null;
}
/**
 * To interprete, the specified TemplateName
 * @param non-empty-string $name
 * @param mixed $data
 * @param bool $print
 * @return string|null The complete name of selected module class or return null if it's not found
 */
function module(string $name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->ModuleDirectory, $name, $data, $print, $origin, $depth, $alternative, $default, once: true, used: $used) ? "\\MiMFa\\Module\\$used" : null;
}
/**
 * To interprete, the specified TemplateName
 * @param non-empty-string $name
 * @param mixed $data
 * @param bool $print
 * @return string|null The complete name of selected template class or return null if it's not found
 */
function template(string $name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->TemplateDirectory, $name, $data, $print, $origin, $depth, $alternative, $default, once: true, used: $used) ? "\\MiMFa\\Template\\$used" : null;
}

/**
 * To interprete, the specified viewname
 * @param non-empty-lowercase-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed The result of included view or the printed data
 */
function view(string|null $name = null, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->ViewDirectory, $name ?? \_::$Config->DefaultViewName, $data, $print, $origin, $depth, $alternative, $default, once: true);
}

/**
 * To interprete, the specified regionname
 * @param non-empty-lowercase-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed The result of included region or the printed data
 */
function region(string $name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->RegionDirectory, $name, $data, $print, $origin, $depth, $alternative, $default);
}
/**
 * To interprete, the specified pagename
 * @param non-empty-lowercase-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed The result of included page or the printed data
 */
function page(string $name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->PageDirectory, $name, $data, $print, $origin, $depth, $alternative, $default);
}
/**
 * To interprete, the specified partname
 * @param non-empty-lowercase-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed The result of included part or the printed data
 */
function part(string $name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->PartDirectory, $name, $data, $print, $origin, $depth, $alternative, $default);
}
/**
 * To interprete, the specified computionname
 * @param non-empty-lowercase-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed The result of the pointed computed codes or the printed data
 */
function compute(string $name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->ComputeDirectory, $name, $data, $print, $origin, $depth, $alternative, $default);
}

/**
 * To interprete, the specified routename
 * @param non-empty-lowercase-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed The result of the routers or the printed data
 */
function route(string|null $name = null, mixed $data = null, bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->RouteDirectory, $name ?? \_::$Config->DefaultRouteName, $data, $print, $origin, $depth, $alternative, $default);
}

/**
 * To get the url of the selected asset
 * @param non-empty-string $directory
 * @param string|array|null $extensions An array of extensions or a string of the disired extension
 * @return string|null The complete path of selected asset or return null if it's not found
 */
function asset($directory, string|null $name = null, string|array|null $extensions = null, $optimize = false, string|int $origin = 0, int $depth = 999999, $default = null)
{
	$directory = preg_replace("/([\\\\\/]?asset[\\\\\/])|(^[\\\\\/]?)/", \_::$Address->AssetRoute, $directory ?? "");
	$i = 0;
	if (!is_array($extensions))
		$extensions = [$extensions ?? ""];
	$extension = isset($extensions[$i]) ? $extensions[$i++] : "";
	try {
		renderPrepends($directory, $name);
		do {
			if ($path = addressing("$directory$name", $extension, $origin, $depth))
				return getFullUrl($path, $optimize);
		} while ($extension = isset($extensions[$i]) ? $extensions[$i++] : null);
		return $default;
	} finally {
		renderAppends($directory, $name);
	}
}

/**
 * To get a Table from the DataBase
 * @param string $name The raw table name (Without any prefix)
 * @return \MiMFa\Library\DataTable The selected database's table
 */
function table(string $name, bool $prefix = true, string|int $origin = 0, int $depth = 999999, ?\MiMFa\Library\DataBase $source = null, $default = null)
{
	return new \MiMFa\Library\DataTable(
		$source ?? \_::$Back->DataBase,
		$name,
		$prefix
	);
}

#endregion 


#region ACCESSING

/**
 * To check Features of an object from an array
 * @param mixed $object The source object
 * @param array $hierarchy A hierarchy of desired keys
 */
function has($object, ...$hierarchy)
{
	if (is_null($object))
		return false;
	if (count($hierarchy) === 0)
		return $object !== null;
	$data = array_shift($hierarchy);
	if (is_null($data))
		return false;
	if (!is_array($data)) {
		if (is_array($object)) {
			if (isset($object[$data]))
				return has($object[$data], ...$hierarchy);
			$data = strtolower($data);
			foreach ($object as $k => $v)
				if ($data === strtolower($k))
					return has($v, ...$hierarchy);
		} else
			return has($object->{$data} ??
				$object->{strtoproper($data)} ??
				$object->{strtolower($data)} ??
				$object->{strtoupper($data)} ?? null, ...$hierarchy);
	} else {
		if (is_array($object)) {
			foreach ($data as $k => $v)
				if (is_numeric($k)) {
					if (($val = has($object, $v, ...$hierarchy)) !== null)
						return $val;
				} else
					return has($object, $k, $v, ...$hierarchy);
		} else {
			foreach ($data as $k => $v)
				if (is_numeric($k)) {
					if (($val = has($object, $v, ...$hierarchy)) !== null)
						return $val;
				} else
					return has($object, $k, $v, ...$hierarchy);
		}
		return false;
	}
}

/**
 * To get Features of an object from an array
 * @param mixed $object The source object
 * @param array $hierarchy A hierarchy of desired keys
 */
function get($object, ...$hierarchy)
{
	if (count($hierarchy) === 0)
		return $object;
	$data = array_shift($hierarchy);
	if (is_null($data))
		return null;
	if (!is_array($data)) {
		if (is_array($object)) {
			if (isset($object[$data]))
				return get($object[$data], ...$hierarchy);
			$data = strtolower($data);
			foreach ($object as $k => $v)
				if ($data === strtolower($k))
					return get($v, ...$hierarchy);
		} else
			return get(isset($object->$data) ? $object->$data : (
				isset($object->{strtoproper($data)}) ? $object->$data : (
					isset($object->{strtolower($data)}) ? $object->$data : (
						isset($object->{strtoupper($data)}) ? $object->$data : null
					)
				)
			), ...$hierarchy);
	} else {
		$res = [];
		if (is_array($object)) {
			foreach ($data as $k => $v)
				if (is_numeric($k)) {
					if (($val = get($object, $v, ...$hierarchy)) !== null)
						$res[$k] = $val;
				} else
					$res[$k] = get($object, $k, $v, ...$hierarchy);
		} else {
			foreach ($data as $k => $v)
				if (is_numeric($k)) {
					if (($val = get($object, $v, ...$hierarchy)) !== null)
						$res[$k] = $val;
				} else
					$res[$k] = get($object, $k, $v, ...$hierarchy);
		}
		return $res;
	}
}
/**
 * To get Features of an object from an array
 * Then unset that key of the $data
 * @param mixed $object The source object
 * @param array $hierarchy A hierarchy of desired keys
 */
function grab(&$object, ...$hierarchy)
{
	if (count($hierarchy) === 0)
		return $object;
	$data = array_shift($hierarchy);
	if (is_null($data))
		return null;
	$rem = count($hierarchy) === 0;
	$res = null;
	if (!is_array($data)) {
		if (is_array($object)) {
			if (isset($object[$data])) {
				$res = $object[$data];
				if ($rem)
					unset($object[$data]);
				else
					return grab($object[$data], ...$hierarchy);
			} else {
				$data = strtolower($data);
				foreach ($object as $k => $v)
					if ($data === strtolower($k)) {
						$res = $v;
						if ($rem)
							unset($object[$k]);
						else
							return grab($object[$k], ...$hierarchy);
						break;
					}
			}
		} else {
			$key = null;
			$res = isset($object->{$key = $data}) ? $object->$key : (
				isset($object->{$key = strtoproper($data)}) ? $object->$key : (
					isset($object->{$key = strtolower($data)}) ? $object->$key : (
						isset($object->{$key = strtoupper($data)}) ? $object->$key : ($key = null)
					)
				)
			);
			if ($key !== null) {
				if ($rem)
					unset($object->$key);
				else
					return grab($object->$key, ...$hierarchy);
				if (!$object)
					unset($object);
			}
		}
	} else {
		$res = [];
		$val = null;
		if (is_array($object)) {
			foreach ($data as $k => $v)
				if (is_numeric($k)) {
					if (($val = grab($object, $v, ...$hierarchy)) !== null)
						$res[$k] = $val;
				} else
					$res[$k] = grab($object, $k, $v, ...$hierarchy);
		} else {
			foreach ($data as $k => $v)
				if (is_numeric($k)) {
					if (($val = grab($object, $v, ...$hierarchy)) !== null)
						$res[$k] = $val;
				} else
					$res[$k] = grab($object, $k, $v, ...$hierarchy);
		}
	}
	return $res;
}

/**
 * To set Features of an object from an array or other object
 * @param mixed $object The destination object
 */
function set(&$object, $data)
{
	if (!is_array($data) || !is_object($object))
		try {
			return $object = $data;
		} catch (Exception $ex) {
		} else {
		foreach ($data as $k => $v) {
			find($object, $k, $key, $index);
			if ($key)
				if (is_null($index))
					set($object->$key, $v);
				else
					set($object[$key], $v);
		}
	}
	return $object;
}
/**
 * To set Features of an object from an array or other object
 * Then unset that key of the $data
 * @param mixed $object The destination object
 */
function swap(&$object, &$data)
{
	if (!is_array($data) || !is_object($object))
		try {
			$object = $data;
			unset($data);
		} catch (Exception $ex) {
		} else {
		foreach ($data as $k => $v) {
			find($object, $k, $key, $index);
			if ($key) {
				if (is_null($index))
					swap($object->$key, $v);
				else
					swap($object[$key], $v);
				unset($data[$k]);
			}
		}
	}
	return $object;
}


/**
 * To take somthing by an exact key on a countable element
 * @param mixed $object The source object
 * @param $key The key sample to find
 * @param $index To get the index of the key (optional)
 * @return mixed
 */
function take($object, $key, int|null &$index = null, $default = null)
{
	$index = null;
	if (is_null($object) || is_null($key))
		return $object;
	if (is_array($object)) {
		$index = 0;
		foreach ($object as $k => $v) {
			if ($key === $k)
				return $v;
			$index++;
		}
	}
	$index = null;
	return isset($object->$key) ? $object->$key : $default;
}
/**
 * Find somthing by an index on a countable element
 * @param mixed $object The source object
 * @param string|int|null $item The key sample to find
 * @param $key To get the correct spell of the key (optional)
 * @return mixed
 */
function find($object, $item, &$key = null, int|null &$index = null, $default = null)
{
	$index = $key = null;
	if (is_null($object) || is_null($item))
		return $object;
	if (is_array($object)) {
		$index = is_int($item) ? $item : 0;
		if (isset($object[$item]))
			return $object[$key = $item];
		$index = 0;
		$it = strtolower($item);
		foreach ($object as $k => $v) {
			if ($it === strtolower($k)) {
				$key = $k;
				return $v;
			}
			$index++;
		}
	}
	$index = null;
	return
		isset($object->{$key = $item}) ? $object->$key : (
			isset($object->{$key = strtoproper($item)}) ? $object->$key : (
				isset($object->{$key = strtolower($item)}) ? $object->$key : (
					isset($object->{$key = strtoupper($item)}) ? $object->$key : (($key = null) ?? $default)
				)
			)
		);
}
/**
 * To seek for a result by a callable function on a countable element
 * @param mixed $object The source object
 * @param callable $by The filter $by($value, $key, $index)=> // return true if find and false when it is not find 
 * @return mixed
 */
function seek($object, callable $by, &$key = null, int|null &$index = null, $default = null)
{
	if (!is_null($object)) {
		$index = 0;
		if (!is_iterable($object)) {
			if ($by($object, null, $index))
				return $object;
		} else
			foreach ($object as $key => $value)
				if ($by($value, $key, $index++))
					return $value;
	}
	$index = null;
	return $default;
}
/**
 * To filter and return all succeed results by a callable function on a countable element
 * @param mixed $object The source object
 * @param callable $by The filter $by($value, $key, $index)=> // return true if find and false when it is not find 
 * @return mixed
 */
function filter(&$object, callable $by, $default = null)
{
	if (!is_null($object)) {
		$results = [];
		$index = 0;
		if (!is_iterable($object)) {
			if ($by($object, null, $index))
				$results = $object;
		} else
			foreach ($object as $key => $value)
				if ($by($value, $key, $index++)) {
					$results[$key] = $value;
					unset($object[$key]);
				}
	}
	return $results ?? $default;
}
/**
 * To search and return all succeed results by a callable function on a countable element
 * @param mixed $object The source object
 * @param callable $by The filter $by($value, $key, $index)=> // return true if find and false when it is not find 
 * @return mixed
 */
function search($object, callable $by)
{
	if (!is_null($object)) {
		$index = 0;
		if (!is_iterable($object)) {
			if ($by($object, null, $index))
				yield $object;
		} else
			foreach ($object as $key => $value)
				if ($by($value, $key, $index++))
					yield $value;
	}
}

/**
 * Do a loop action by a callable function on a countable element
 * @param mixed $array An array or an intiger to iterate
 * @param callable $action The loop action $action($value, $key, $index)
 * @return array
 */
function loop($array, callable $action, $nullValues = false, $pair = false)
{
	if (is_null($array))
		return [];
	if ($pair) {
		$items = [];
		foreach (iteration($array, $action, $nullValues) as $kvps)
			foreach ($kvps as $key => $value)
				$items[$key] = $value;
		return $items;
	} else
		return iterator_to_array(iteration($array, $action, $nullValues));
}
/**
 * Do a loop action by a callable function on a countable element
 * @param mixed $array An array or an intiger to iterate
 * @param callable $action The loop action $action($value, $key, $index)
 */
function iteration($array, callable $action, $nullValues = false)
{
	if (!is_null($array)) {
		$i = 0;
		if (!is_iterable($array)) {
			if (is_int($array))
				for (; $i < $array; $i++)
					if (($res = $action($array, null, $i)) !== null || $nullValues)
						yield $res;
					elseif (($res = $action($array, null, $i)) !== null || $nullValues)
						yield $res;
		} else
			foreach ($array as $key => $value)
				if (($res = $action($value, $key, $i++)) !== null || $nullValues)
					yield $res;
	}
}
/**
 * Returns the value of the first array element.
 * @param array|object|iterable|Generator|null $array
 * @return mixed
 */
function first($array, $default = null)
{
	if (is_null($array))
		return $default;
	if (is_array($array))
		return count($array) > 0 ? $array[array_key_first($array)] : $default;
	if (is_iterable($array)) {
		foreach ($array as $value)
			return $value;
		return $default;
	}
	$res = reset($array);
	if ($res === false)
		return $default;
	return $res;
}
/**
 * Returns the value of the last array element.
 * @param array|object|iterable|Generator|null $array
 * @return mixed
 */
function last($array, $default = null)
{
	if (is_null($array))
		return $default;
	if (is_array($array))
		return count($array) > 0 ? $array[array_key_last($array)] : $default;
	if (is_iterable($array)) {
		foreach ($array as $value)
			$default = $value;
		return $default;
	}
	$res = end($array);
	if ($res === false)
		return $default;
	return $res;
}

#endregion 


#region CRYPTION

function code($html, &$dic = null, $startCode = "<", $endCode = ">", $pattern = '/((["\'])\S+[\w\W]*\2)|(\<\S+[\w\W]*[^\\\\]\>)|(\d*\.?\d+)/iU')
{
	if (!is_array($dic))
		$dic = array();
	$c = count($dic);
	return preg_replace_callback($pattern, function ($a) use (&$dic, &$c, $startCode, $endCode) {
		$key = $a[0];
		if (array_key_exists($key, $dic))
			return $dic[$key];
		return $dic[$key] = $startCode . $c++ . $endCode;
	}, $html);
}
function decode($html, $dic)
{
	if (is_array($dic))
		foreach (array_reverse($dic) as $k => $v)
			$html = str_replace($v, $k, $html);
	return $html;
}
/**
 * Encrypt plain by the key or the website secret key
 * @param mixed $plain The plain text
 * @param mixed $key Leave null to use default soft key
 */
function encrypt($plain, $key = null)
{
	if (is_null($plain))
		return null;
	if (empty($plain))
		return $plain;
	return \_::$Back->Cryptograph->Encrypt($plain, $key ?? \_::$Config->SoftKey, true);
}
/**
 * Decrypt cipher by the key or the website secret key
 * @param mixed $cipher The cipher text
 * @param mixed $key Leave null to use default soft key
 */
function decrypt($cipher, $key = null)
{
	if (is_null($cipher))
		return null;
	if (empty($cipher))
		return $cipher;
	return \_::$Back->Cryptograph->Decrypt($cipher, $key ?? \_::$Config->SoftKey, true);
}

#endregion 


#region IDENTIFICATION

function getId($random = false): int
{
	if (!$random)
		return ++\_::$DynamicId;
	list($usec, $sec) = explode(" ", microtime());
	return (int) ($usec * 10000000 + $sec);
}

/**
 * Get the full part of a url pointed to cache status
 * @example: "/Category/mimfa/service/web.php?p=3&l=10#serp" => "https://www.mimfa.net:5046/Category/mimfa/service/web.php?p=3&l=10#serp&v=3.21"
 * @return string|null
 */
function getFullUrl(string|null $path = null, bool $optimize = true): string|null
{
	if ($path === null)
		$path = getUrl();
	if ($optimize)
		return \MiMFa\Library\Local::OptimizeUrl(\MiMFa\Library\Local::GetUrl($path));
	return \MiMFa\Library\Local::GetUrl($path);
}
/**
 * Get the full part of a url
 * @example: "/Category/mimfa/service/web.php?p=3&l=10#serp" => "https://www.mimfa.net:5046/Category/mimfa/service/web.php?p=3&l=10#serp"
 * @return string|null
 */
function getUrl(string|null $path = null): string|null
{
	if ($path === null)
		$path = (
			takeValid($_SERVER, 'SCRIPT_URI') ??
			(((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http") .
			"://" . $_SERVER["HTTP_HOST"] . takeBetween($_SERVER, "REQUEST_URI", "PHP_SELF")
		);//.($_SERVER['QUERY_STRING']?"?".$_SERVER['QUERY_STRING']:"");
	return preg_replace("/^([\/\\\])/", rtrim(getHost(), "/\\") . "$1", $path);
}
/**
 * Get the host part of a url
 * @example: "https://www.mimfa.net:5046"
 * @return string|null
 */
function getHost(string|null $path = null): string|null
{
	$pat = "/^\w+\:\/*[^\/]+/";
	if ($path == null || !preg_match($pat, $path))
		$path = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'];
	return PREG_Find($pat, $path);
}
/**
 * Get the site name part of a url
 * @example: "www.mimfa.net"
 * @return string|null
 */
function getSite(string|null $path = null): string|null
{
	return PREG_replace("/(^\w+:\/*)|(\:\d+$)/", "", getHost($path));
}
/**
 * Get the domain name part of a url
 * @example: "mimfa.net"
 * @return string|null
 */
function getDomain(string|null $path = null): string|null
{
	return PREG_replace("/(^\w+:\/*(www\.)?)|(\:\d+$)/", "", getHost($path));
}
/**
 * Get the path part of a url
 * @example: "https://www.mimfa.net/Category/mimfa/service/web.php"
 * @return string|null
 */
function getPath(string|null $path = null): string|null
{
	return PREG_Find("/(^[^\?#]*)/", $path ?? getUrl());
}
/**
 * Get the request part of a url
 * @example: "/Category/mimfa/service/web.php?p=3&l=10#serp"
 * @return string|null
 */
function getRequest(string|null $path = null): string|null
{
	if ($path == null)
		$path = getUrl();
	return PREG_Replace("/(^\w+:\/*[^\/]+)/", "", $path);
}
/**
 * Get the direction part of a url from the root
 * @example: "Category/mimfa/service/web.php"
 * @return string|null
 */
function getDirection(string|null $path = null): string|null
{
	if ($path == null)
		$path = getUrl();//ltrim($_SERVER["REQUEST_URI"],"\\\/");
	return PREG_Replace("/(^\w+:\/*[^\/]+\/)|([\?#].+$)/", "", $path);
}
/**
 * Get the last part of a direction url
 * @example: "web.php"
 * @return string|null
 */
function getPage(string|null $path = null): string|null
{
	return last(preg_split("/[\\\\\/]/i", getDirection($path)));
}
/**
 * Get the query part of a url
 * @example: "p=3&l=10"
 * @return string|null
 */
function getQuery(string|null $path = null): string|null
{
	return PREG_Find("/((?<=\?)[^#]*($|#))/", $path ?? getUrl());
}
/**
 * Get the fragment or anchor part of a url
 * @example: "serp"
 * @return string|null
 */
function getFragment(string|null $path = null): string|null
{
	return PREG_Find("/((?<=#)[^\?]*($|\?))/", $path ?? getUrl());
}

/**
 * Get the request method name =>
 * GET:1,
 * POST:2,
 * PUT:3,
 * FILE:4,
 * PATCH:5,
 * DELETE:6,
 * STREAM:7,
 * INTERNAL:8,
 * EXTERNAL:9,
 * OTHER:0
 * @param string|int|null $method
 * @return int|string
 */
function getMethodName(string|int|null $method = null)
{
	switch (strtoupper($method ?? "")) {
		case 1:
		case "PUBLIC":
		case "GET":
			return "GET";
		case 2:
		case "PRIVATE":
		case "POST":
			return "POST";
		case 3:
		case "PUT":
			return "PUT";
		case 4:
		case "FILES":
		case "FILE":
			return "POST";
		case 5:
		case "PATCH":
			return "PATCH";
		case 6:
		case "DELETE":
		case "DEL":
			return "DELETE";
		case 7:
		case "STREAM":
			return "STREAM";
		case 8:
		case "INTER":
		case "INTERNAL":
			return "INTERNAL";
		case 9:
		case "EXTER":
		case "EXTERNAL":
			return "EXTERNAL";
		default:
			return strtoupper($method ?? $_SERVER['HTTP_X_CUSTOM_METHOD'] ?? $_SERVER['REQUEST_METHOD'] ?? "OTHER");
	}
}
/**
 * Get the request method index =>
 * All:0
 * GET:1,
 * POST:2,
 * PUT:3,
 * FILE:4,
 * PATCH:5,
 * DELETE:6,
 * STREAM:7,
 * INTERNAL:8,
 * EXTERNAL:9,
 * OTHER:10
 * @param string|int|null $method
 * @return int|string
 */
function getMethodIndex(string|int|null $method = null)
{
	switch (strtoupper($method ?? $_SERVER['HTTP_X_CUSTOM_METHOD'] ?? $_SERVER['REQUEST_METHOD'])) {
		case 1:
		case "PUBLIC":
		case "GET":
			return 1;
		case 2:
		case "PRIVATE":
		case "POST":
			return 2;
		case 3:
		case "PUT":
			return 3;
		case 4:
		case "FILES":
		case "FILE":
			return 4;
		case 5:
		case "PATCH":
			return 5;
		case 6:
		case "DELETE":
		case "DEL":
			return 6;
		case 7:
		case "STREAM":
			return 7;
		case 8:
		case "INTER":
		case "INTERNAL":
			return 8;
		case 9:
		case "EXTER":
		case "EXTERNAL":
			return 9;
		default:
			return 10;
	}
}

function getClientIp($version = null): string|null
{
	foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
		if (array_key_exists($key, $_SERVER) === true) {
			foreach (explode(',', $_SERVER[$key]) as $ip)
				$ip = trim($ip); // just to be safe
			if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false)
				return $version == 6 ? gethostbyaddr($ip) : $ip;
		}
	}
	return null;
}
function getClientCode(): string|null
{
	return md5(getClientIp() ?? $_SERVER['HTTP_USER_AGENT']);
}

/**
 * Create an email account
 * @example: "do-not-reply@mimfa.net"
 * @return string|null
 */
function createEmail($name = "do-not-reply", string|null $path = null): string|null
{
	return $name . "@" . getDomain($path);
}

#endregion 


#region STORAGING

/**
 * To cleanup all Temporary files, or received files in this request
 * @param mixed $full True to cleanup all Temporary files, false to cleanup only received files in this request
 */
function cleanupTemp($full = true)
{
	if ($full)
		return cleanup(\_::$Address->TempDirectory);
	$i = 0;
	foreach ($_FILES as $file)
		if (isset($file["tmp_name"]) && is_file($file["tmp_name"]) && ++$i)
			unlink($file["tmp_name"]);
	return $i;
}
/**
 * Iterate through the files of the directory and delete them
 * @param mixed $directory
 */
function cleanup($directory = null)
{
	$i = 0;
	if ($directory) {
		foreach (glob("$directory*") as $file)
			if (is_file($file) && ++$i)
				unlink($file);
	} else {
		$i += cleanup(\_::$Address->TempDirectory);
		$i += cleanup(\_::$Aseq->TempDirectory);
		$i += cleanup(\_::$Base->TempDirectory);
		$i += cleanup(\_::$Address->LogDirectory);
		$i += cleanup(\_::$Aseq->LogDirectory);
		$i += cleanup(\_::$Base->LogDirectory);
		flushSessions();
		\_::$Back->Session->Flush();
	}
	return $i;
}

function grabMemo($key)
{
	$val = getMemo($key);
	forgetMemo($key);
	return $val;
}
function setMemo($key, $value, $expires = 0, $path = "/", $secure = false)
{
	if ($value == null)
		return false;
	return setcookie(urlencode($key), urlencode($value), ceil($expires / 1000), $path, "", $secure, $secure);
}
function getMemo($key)
{
	if (isset($_COOKIE[$key]))
		return urldecode($_COOKIE[$key]);
	else
		return null;
}
function hasMemo($key)
{
	return !is_null(getMemo($key));
}
function forgetMemo($key)
{
	unset($_COOKIE[urlencode($key)]);
	return setcookie(urlencode($key), "", 0, "/", "", true, true);
}
function flushMemos()
{
	foreach ($_COOKIE as $key => $val) {
		unset($_COOKIE[$key]);
		return setcookie($key, "", 0, "/", "", true, true);
	}
}

function grabSession($key)
{
	$val = getSession($key);
	forgetSession($key);
	return $val;
}
function setSession($key, $value)
{
	return $_SESSION[$key] = $value;
}
function getSession($key)
{
	return get($_SESSION, $key);
}
function hasSession($key)
{
	return isValid($_SESSION, $key);
}
function forgetSession($key)
{
	unset($_SESSION[$key]);
}
function flushSessions()
{
	foreach ($_SESSION as $key => $val)
		unset($_SESSION[$key]);
}

/**
 * Compress and reduce the size of document
 * @param string|null $page The source document
 * @return string|null
 */
function reduceSize(string|null $page): string|null
{
	if ($page == null)
		return $page;
	$ls = array();
	$pat = "/<\s*(style)[\s\S]*>[\s\S]*<\/\s*\1\s*>/ixU";
	//$pat ="/\<\s*((style)|(script))[\s\S]*\>[\s\S]*\<\\/\s*\\1\s*\>/ixU";
	$matches = null;
	if (preg_match_all($pat, $page, $matches)) {
		foreach ($matches[0] as $item)
			if (!in_array($item, $ls))
				array_push($ls, preg_replace("/\s+/im", " ", $item));
		//echo count($ls);
		$page = preg_replace($pat, "", $page);
		$page = preg_replace("/<\/\s*head\s*>/im", implode(PHP_EOL, $ls) . PHP_EOL . "</head>", $page);
		//$page = preg_replace("/(?<!(\<\s*(script)[\s\S]*\>[\s\S]+))\s+(?!([\s\S]+\<\\/\s*\\2\s*\>))/ixU", " ", $page);
		return preg_replace("/<\s*\/\s*(style)\s*>\s*\<\s*\1\s*>/ixU", PHP_EOL, $page);
	}
	return $page;
}

/**
 * @deprecated
 */
function fetchValue(string|null $source, string|null $key, bool $ismultiline = true)
{
	$arr = is_array($source) ? $source : explode("\n", $source);
	$f = false;
	$res = "";
	foreach ($arr as $i => $line) {
		$line = trim($line);
		if (strpos($line, $key) === 0) {
			$res = trim(substr($line, strlen($key)));
			$f = $ismultiline;
			if (!$f)
				break;
		} elseif ($f) {
			if (strpos($line, "	") === 0)
				$res .= PHP_EOL . "\t" . trim($line);
			else
				break;
		}
	}
	return trim($res);
}

#endregion 


#region CHECKING

function isEmpty($object): bool
{
	return !isset($object) || is_null($object) || (is_string($object) && (trim($object . "", " \n\r\t\v\f'\"") === "")) || (is_countable($object) && count($object) === 0);
	//return $object?(is_string($object) && (trim($object . "", " \n\r\t\v\f'\"") === "")):true;
}
function isValid($object, string|null $item = null): bool
{
	if ($item === null)
		return isset($object) && !is_null($object) && (!is_string($object) || !(trim($object) == "" || trim($object, "'\"") == ""));
	//return $object?true:false;
	else
		return isValid($object) && isValid($item) && ((isset($object[$item]) && isValid($object[$item])) || (isset($object->$item) && isValid($object->$item)));
}
function takeValid($object, string|null $item = null, $defultValue = null)
{
	if (isValid($object, $item)) {
		if ($item === null)
			return $object;
		if (isset($object[$item]))
			return $object[$item];
		return $object->$item;
	} else
		return $defultValue;
}
function getValid($object, string|null $item = null, $defultValue = null, &$key = null)
{
	if ($object === null || $item === null)
		return isValid($object) ? $object : $defultValue;
	if (is_array($object)) {
		if (isset($object[$item]))
			return isValid($object[$key = $item]) ? $object[$item] : $defultValue;
		$item = strtolower($item);
		foreach ($object as $k => $v)
			if ($item === strtolower($k)) {
				$key = $k;
				return isValid($v) ? $v : $defultValue;
			}
	}
	$res =
		$object->{$key = $item} ??
		$object->{$key = strtoproper($item)} ??
		$object->{$key = strtolower($item)} ??
		$object->{$key = strtoupper($item)} ??
		($key = null);
	return isValid($res) ? $res : $defultValue;
}
function grabValid(&$object, string|null $item = null, $defultValue = null, &$key = null)
{
	if ($object === null || $item === null)
		return isValid($object) ? $object : $defultValue;
	if (is_array($object)) {
		if (isset($object[$item])) {
			$res = $object[$key = $item] ?? $defultValue;
			unset($object[$item]);
			return isValid($res) ? $res : $defultValue;
		}
		$item = strtolower($item);
		foreach ($object as $k => $v)
			if ($item === strtolower($k)) {
				$key = $k;
				unset($object[$k]);
				return isValid($v) ? $v : $defultValue;
			}
	}
	$res =
		$object->{$key = $item} ??
		$object->{$key = strtoproper($item)} ??
		$object->{$key = strtolower($item)} ??
		$object->{$key = strtoupper($item)} ??
		($key = null) ?? $defultValue;
	if ($key !== null)
		unset($object->$key);
	return isValid($res) ? $res : $defultValue;
}
function doValid(callable $func, $object, string|null $item = null, $defultValue = null)
{
	return isValid($object, $item) ? $func(getValid($object, $item)) : $defultValue;
}
function takeBetween($object, ...$items)
{
	foreach ($items as $value)
		if (($value = getValid($object, $value, null)) !== null)
			return $value;
	return null;
}
function getBetween($object, ...$items)
{
	foreach ($items as $value)
		if (($value = getValid($object, $value, null)) !== null)
			return $value;
	return null;
}
function grabBetween(&$object, ...$items)
{
	foreach ($items as $value)
		if (($value = grabValid($object, $value, null)) !== null)
			return $value;
	return null;
}
function between(...$options)
{
	foreach ($options as $value)
		if (isValid($value))
			return $value;
	return null;
}

function isASEQ(string|null $directory): bool
{
	return !\MiMFa\Library\Local::FileExists($directory . "global/ConfigurationBase.php")
		&& \MiMFa\Library\Local::FileExists($directory . "global.php")
		&& \MiMFa\Library\Local::FileExists($directory . "Information.php")
		&& \MiMFa\Library\Local::FileExists($directory . "initialize.php");
}
function isBASE(string|null $directory): bool
{
	return \MiMFa\Library\Local::FileExists($directory . "Configuration.php")
		&& \MiMFa\Library\Local::FileExists($directory . "global/ConfigurationBase.php")
		&& \MiMFa\Library\Local::FileExists($directory . "Information.php")
		&& \MiMFa\Library\Local::FileExists($directory . "global/InformationBase.php")
		&& \MiMFa\Library\Local::FileExists($directory . "Front.php")
		&& \MiMFa\Library\Local::FileExists($directory . "global/FrontBase.php")
		&& \MiMFa\Library\Local::FileExists($directory . "global.php")
		&& \MiMFa\Library\Local::FileExists($directory . "initialize.php");
}
function isInASEQ(string|null $filePath): bool
{
	$filePath = preg_replace("/^\\\\/", \_::$Aseq->Directory, str_replace(\_::$Aseq->Directory, "", trim($filePath ?? getUrl())));
	if (isFormat($filePath, \_::$Extension))
		return file_exists($filePath);
	return is_dir($filePath) || file_exists($filePath . \_::$Extension);
}
function isInBASE(string|null $filePath): bool
{
	$filePath = \_::$Base->Directory . preg_replace("/^\\\\/", "", str_replace(\_::$Base->Directory, "", trim($filePath ?? getUrl())));
	if (isFormat($filePath, \_::$Extension))
		return file_exists($filePath);
	return is_dir($filePath) || file_exists($filePath . \_::$Extension);
}

/**
 * Check file format by thats extension
 * @param null|string $path
 * @param array<string> $formats
 * @return string|bool
 */
function isFormat(string|null $path, string|array ...$formats)
{
	$p = getPath(strtolower($path));
	foreach ($formats as $format)
		if (is_array($format)) {
			foreach ($format as $forma)
				if ($forma = isFormat($p, $forma))
					return $forma;
		} elseif (endsWith($p, strtolower($format)))
			return $format;
	return false;
}

/**
 * Check if the string is a relative or absolute file URL
 * @param null|string $url The url string
 * @return bool
 */
function isFile(string|null $url, string ...$formats): bool
{
	if (count($formats) == 0)
		array_push($formats, \_::$Config->AcceptableFileFormats, \_::$Config->AcceptableDocumentFormats, \_::$Config->AcceptableImageFormats, \_::$Config->AcceptableAudioFormats, \_::$Config->AcceptableVideoFormats);
	return isUrl($url) && isFormat(getPath($url), $formats);
}
/**
 * Check if the string is a relative or absolute URL
 * @param null|string $url The url string
 * @return bool
 */
function isUrl(string|null $url): bool
{
	return (!empty($url)) && preg_match("/^([A-z0-9\-]+\:)?([\/\?\#]([^:\/\{\}\|\^\[\]\"\`\r\n\t\f]*)|(\:\d+))+$/", $url);
}
/**
 * Check if the string is only a relative URL
 * @param null|string $url The url string
 * @return bool
 */
function isRelativeUrl(string|null $url): bool
{
	return (!empty($url)) && preg_match("/^([\/\?\#]([^:\/\{\}\|\^\[\]\"\`\r\n\t\f]*)|(\:\d+))+$/", $url);
}
/**
 * Check if the string is only an absolute URL
 * @param null|string $url The url string
 * @return bool
 */
function isAbsoluteUrl(string|null $url): bool
{
	return (!empty($url)) && preg_match("/^[A-z0-9\-]+\:\/*([\/\?\#][^\/\{\}\|\^\[\]\"\`\r\n\t\f]*)+$/", $url);
}
/**
 * Check if the string is script or not
 * @param null|string $script The url string
 * @return bool
 */
function isScript(mixed $script): bool
{
	return !is_string($script) ||
		((!empty($script))
			&& !preg_match("/^[A-z0-9\-\.\_]+\@([A-z0-9\-\_]+\.[A-z0-9\-\_]+)+$/", $script)
			&& !preg_match("/^[A-z0-9\-]+\:\/*([\/\?\#][^\/\{\}\|\^\[\]\"\`\r\n\t\f]*)+$/", $script)
			&& preg_match("/[\{\}\|\^\[\]\"\`\;\r\n\t\f]|((^\s*[\w\$][\w\d\$\_\.]+\s*\([\s\S]*\)\s*)+;?\s*$)/", $script));
}
/**
 * To check if the string is a JSON or not
 * @param null|string $json The json string
 */
function isJson($json)
{
	if (isEmpty($json))
		return null;
	if (!is_string($json))
		return false;
	return preg_match("/^\s*[\{|\[][\s\S]*[\}\]]\s*$/", $json) > 0;
}
/**
 * Check if the string is a relative or absolute URL
 * @param null|string $url The url string
 * @return bool
 */
function isEmail(string|null $email): bool
{
	return (!empty($email)) && preg_match("/^[A-z0-9\-\.\_]+\@([A-z0-9\-\_]+\.[A-z0-9\-\_]+)+$/", $email);
}
/**
 * Check if the string is a regex pattern or not
 * @param null|string $text The text string
 * @return bool
 */
function isPattern(string $text): bool
{
	return preg_match("/^\/[\s\S]+\/[gimsxU]{0,6}$/", $text);
}

/**
 * Check if the string is a suitable name for a class or id or name field
 * @param null|string $text The url string
 * @return bool
 */
function isIdentifier(string|null $text): bool
{
	return (!empty($text)) && preg_match("/^[a-z_\$][a-z0-9_\-\$]*$/i", $text);
}

/**
 * Check if the value is a static type like string or number or other static types
 * @param null|string $value Desired value
 * @return bool
 */
function isStatic($value): bool
{
	return is_string($value) || is_numeric($value) || is_bool($value) || is_null($value);
}

#endregion 


#region MANIPULATING
/**
 * Remove all changeable command signs from a url (such as ../ or /./.)
 * Change all backslashes to the slash
 * @param string $path The source path
 * @return array|string|null
 */
function normalizeUrl(string $path): string|null
{
	return str_replace("\\", "/", preg_replace("/([\/\\\]\.+)|(\.+[\/\\\])/", "", $path));
}
/**
 * Remove all changeable command signs from a path (such as ../ or /./.)
 * Change all slashes/backslashes to the DIRECTORY_SEPARATOR
 * @param string $path The source path
 * @return array|string|null
 */
function normalizePath(string $path): string|null
{
	return str_replace(["\\", "/"], DIRECTORY_SEPARATOR, preg_replace("/([\/\\\]\.+)|(\.+[\/\\\])/", "", $path));
}

/**
 * Create a random string|null with a custom length
 * @param int $length Custom length of destination string|null
 * @param string|null $chars Allowable characters
 * @return string|null
 */
function randomString(int $length = 10, string|null $chars = '_0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'): string|null
{
	return substr(str_shuffle(str_repeat($chars, ceil($length / strlen($chars)))), 1, $length);
}
function insertToString(string $mainstr, string $insertstr, int $index): string
{
	return substr($mainstr, 0, $index) . $insertstr . substr($mainstr, $index);
}
function deleteFromString(string $mainstr, int $index, int $length = 1): string
{
	return substr($mainstr, 0, $index) . substr($mainstr, $index + $length);
}

/**
 * Execute the Command Comments (Commands by the pattern <!---name:Command---> <!---name--->)
 * @param string|null $page The source document
 * @deprecated
 * @return string|null
 */
function executeCommands(string|null $page, string|null $name = null): string|null
{
	if ($page == null)
		return $page;
	if ($name == null) {
		//$page = executeCommands($page, "Append");
		//$page = executeCommands($page, "Prepend");
	} else {
		$name = strtolower($name);
		$patfull = "/<!-{3}$name:[\w\W]*-{3}>[\w\W]*<!-{3}$name-{3}>/i";
		$patcommand = "/(?<=<!-{3}$name:)[\w\W]*(?=-{3}>)/i";
		$matches = [];
		switch ($name) {
			case "append":
				$page = preg_replace_callback($patfull, function ($m) use (&$matches) {
					array_push($matches, $m[0]);
					return "";
				}, $page);
				foreach ($matches as $m)
					$page = preg_replace(DIRECTORY_SEPARATOR . preg_find($patcommand, $m) . "/i", "\1$m", $page);
				break;
			case "prepend":
				$page = preg_replace_callback($patfull, function ($m) use (&$matches) {
					array_push($matches, $m[0]);
					return "";
				}, $page);
				foreach ($matches as $m)
					$page = preg_replace(DIRECTORY_SEPARATOR . preg_find($patcommand, $m) . "/i", "$m\1", $page);
				break;
		}
		$pat = "/<!-{3}" . $name . "[\w\W]*-{3}>/i";
		$page = preg_replace($pat, "", $page);
	}
	return $page;
}

#endregion 


#region PROCESSING

function async($action, $callback = null, ...$args)
{
	$pid = 1;
	if (function_exists("pcntl_fork"))
		$pid = pcntl_fork(); // Create a child process
	$result = $action(...$args);
	if ($callback)
		$callback($result);
	if (!$pid)
		exit(0); // End the child process
}


/**
 * Convert to string and process everythings
 * @param mixed $value The target object tot do process
 * @param bool $translating Do translation
 * @param bool $styling Do style and strongify the keywords
 * @param bool $referring Referring tags and categories to their links
 * @return string|null
 */
function __(mixed $value, bool $translating = true, bool $styling = false, bool|null $referring = null): string|null
{
	$value = MiMFa\Library\Convert::ToString($value);
	if ($translating && \_::$Config->AllowTranslate)
		$value = \_::$Back->Translate->Get($value);
	if ($styling)
		$value = MiMFa\Library\Style::DoStyle(
			$value,
			\_::$Info->KeyWords
		);
	if ($referring ?? $styling) {
		if (\_::$Config->AllowContentReferring)
			$value = \MiMFa\Library\Style::DoProcess(
				$value,
				process: function ($v, $k) {
					return \MiMFa\Library\Html::Link($v, \_::$Address->ContentRoute . strtolower($k));
				},
				keyWords: table("Content")->SelectPairs("`Name`", "`Title`", "ORDER BY LENGTH(`Title`) DESC"),
				both: true,
				caseSensitive: true
			);
		if (\_::$Config->AllowCategoryReferring)
			$value = \MiMFa\Library\Style::DoProcess(
				$value,
				process: function ($v, $k) {
					return \MiMFa\Library\Html::Link($v, \_::$Address->CategoryRoute . strtolower($k));
				},
				keyWords: table("Category")->SelectPairs("`Name`", "`Title`", "ORDER BY LENGTH(`Title`) DESC"),
				both: true,
				caseSensitive: true
			);
		if (\_::$Config->AllowTagReferring)
			$value = \MiMFa\Library\Style::DoProcess(
				$value,
				process: function ($v, $k) {
					return \MiMFa\Library\Html::Link($v, \_::$Address->TagRoute . strtolower($k));
				},
				keyWords: table("Tag")->SelectPairs("`Name`", "`Title`", "ORDER BY LENGTH(`Title`) DESC"),
				both: true,
				caseSensitive: true
			);
		if (\_::$Config->AllowUserReferring)
			$value = \MiMFa\Library\Style::DoProcess(
				$value,
				process: function ($v, $k) {
					return \MiMFa\Library\Html::Link($v, \_::$Address->UserRoute . strtolower($k));
				},
				keyWords: table("User")->SelectPairs("`Name`", "`Name`", "ORDER BY LENGTH(`Name`) DESC"),
				both: false,
				caseSensitive: true
			);
	}
	return $value;
}

#endregion


#region COMPLETION

function startsWith(string|null $haystack, string|null ...$needles): bool
{
	foreach ($needles as $needle)
		if (!is_null($needle) && substr_compare($haystack, $needle, 0, strlen($needle)) === 0)
			return $needle || true;
	return false;
}
function endsWith(string|null $haystack, string|null ...$needles): bool
{
	foreach ($needles as $needle)
		if (!is_null($needle) && substr_compare($haystack, $needle, -strlen($needle)) === 0)
			return $needle || true;
	return false;
}

/**
 * Make a string to  ProperCase
 * @param string $string — The input string.
 * @return string — the ProperCased string.
 */
function strToProper($string)
{
	if (empty($string))
		return $string;
	return preg_replace_callback("/\b[a-z]/", fn($v) => strtoupper($v[0]), $string);
}
/**
 * Make a string to camleCase
 * @param string $string — The input string.
 * @return string — the camelCased string.
 */
function strToCamel($string)
{
	if (empty($string))
		return $string;
	$string = preg_replace_callback("/(?<=[^A-Za-z])[a-z]/", fn($v) => strtoupper($v[0]), $string);
	return strtolower(substr($string, 0, 1)) . substr($string, 1);
}

/**
 * Regular Expression Find first match by pattern
 * @param mixed $pattern
 * @param string|null $text
 * @param string|null $def
 * @return mixed
 */
function preg_find($pattern, string|null $text, string|null $def = null): string|null
{
	preg_match_all($pattern, $text, $matches);
	return isset($matches[0][0]) ? $matches[0][0] : $def;
}
/**
 * Regular Expression Find all matches by pattern
 * @param mixed $pattern
 * @param string|null $text
 * @return array|null
 */
function preg_find_all($pattern, string|null $text): array|null
{
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
		$pos = array_search($position, array_keys($array));
		$array = array_merge(
			array_slice($array, 0, $pos),
			$insert,
			array_slice($array, $pos)
		);
	}
	return $array;
}
/**
 * Find everythings are match from an array by a callable function
 * @param array      $array
 * @param callable $searching function($val, $key){ return true; }
 * @param array $array_find_keys
 */
function array_find_keys($array, callable $searching)
{
	return array_filter($array, function ($v, $k) use ($searching) {
		return $searching($v, $k);
	}, ARRAY_FILTER_USE_BOTH);
}

#endregion


#region TESTING

// function test_server()
// {
// 	foreach ($_SERVER as $k => $v)
// 		echo "<br>" . "$k: " . $v;
// 	// echo "<br>"."PHP_SELF: ".$_SERVER['PHP_SELF'];
// 	// echo "<br>"."GATEWAY_INTERFACE: ".$_SERVER['GATEWAY_INTERFACE'];
// 	// echo "<br>"."SERVER_ADDR: ".$_SERVER['SERVER_ADDR'];
// 	// echo "<br>"."SERVER_NAME: ".$_SERVER['SERVER_NAME'];
// 	// echo "<br>"."SERVER_SOFTWARE: ".$_SERVER['SERVER_SOFTWARE'];
// 	// echo "<br>"."SERVER_PROTOCOL: ".$_SERVER['SERVER_PROTOCOL'];
// 	// echo "<br>"."REQUEST_METHOD: ".$_SERVER['REQUEST_METHOD'];
// 	// echo "<br>"."REQUEST_TIME: ".$_SERVER['REQUEST_TIME'];
// 	// echo "<br>"."QUERY_STRING: ".$_SERVER['QUERY_STRING'];
// 	// echo "<br>"."HTTP_ACCEPT: ".$_SERVER['HTTP_ACCEPT'];
// 	// echo "<br>"."HTTP_ACCEPT_CHARSET: ".$_SERVER['HTTP_ACCEPT_CHARSET'];
// 	// echo "<br>"."HTTP_HOST: ".$_SERVER['HTTP_HOST'];
// 	// echo "<br>"."HTTP_REFERER: ".$_SERVER['HTTP_REFERER'];
// 	// echo "<br>"."HTTPS: ".$_SERVER['HTTPS'];
// 	// echo "<br>"."REMOTE_ADDR: ".$_SERVER['REMOTE_ADDR'];
// 	// echo "<br>"."REMOTE_HOST: ".$_SERVER['REMOTE_HOST'];
// 	// echo "<br>"."REMOTE_PORT: ".$_SERVER['REMOTE_PORT'];
// 	// echo "<br>"."SCRIPT_FILENAME: ".$_SERVER['SCRIPT_FILENAME'];
// 	// echo "<br>"."SERVER_ADMIN: ".$_SERVER['SERVER_ADMIN'];
// 	// echo "<br>"."SERVER_PORT: ".$_SERVER['SERVER_PORT'];
// 	// echo "<br>"."SERVER_SIGNATURE: ".$_SERVER['SERVER_SIGNATURE'];
// 	// echo "<br>"."PATH_TRANSLATED: ".$_SERVER['PATH_TRANSLATED'];
// 	// echo "<br>"."SCRIPT_NAME: ".$_SERVER['SCRIPT_NAME'];
// 	// echo "<br>"."SCRIPT_URI: ".$_SERVER['SCRIPT_URI'];
// }
// function test_address($directory = null, string $name = "Configuration")
// {
// 	echo addressing($directory ?? \_::$Address->Directory, $name);
// 	echo "<br>ASEQ: " . \_::$Aseq->Name;
// 	echo "<br>ASEQ->Path: " . \_::$Aseq->Path;
// 	echo "<br>ASEQ->Dir: " . \_::$Aseq->Directory;
// 	echo "<br>OTHER ASEQ: <br>";
// 	var_dump(\_::$Aseq);
// 	echo "<br>BASE: " . \_::$Base->Name;
// 	echo "<br>BASE->Path: " . \_::$Base->Path;
// 	echo "<br>BASE->Dir: " . \_::$Base->Directory;
// 	echo "<br>OTHER BASE: <br>";
// 	var_dump(\_::$Base);
// 	echo "<br><br>ADDRESSES: <br>";
// 	var_dump(\_::$Address);
// }
// function test_url()
// {
// 	echo "<br>URL: " . \_::$Url;
// 	echo "<br>HOST: " . \_::$Host;
// 	echo "<br>SITE: " . \_::$Site;
// 	echo "<br>PATH: " . \_::$Path;
// 	echo "<br>REQUEST: " . \_::$Request;
// 	echo "<br>DIRECTION: " . \_::$Direction;
// 	echo "<br>QUERY: " . \_::$Query;
// 	echo "<br>FRAGMENT: " . \_::$Fragment;
// }
// function test_access($func, $res = null)
// {
// 	$r = null;
// 	if (inspect(0, false, false)) {
// 		if ($r = $func())
// 			echo "<b>TRUE: " . ($r ?? $res) . "</b><br>";
// 		else
// 			echo "FALSE: " . $res . "<br>";
// 	}
// }

#endregion 