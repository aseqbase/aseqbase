<?php

use MiMFa\Library\Convert;
use MiMFa\Library\DataBase;
use MiMFa\Library\DataTable;
use MiMFa\Library\Html;
use MiMFa\Library\Internal;
use MiMFa\Library\Local;
use MiMFa\Library\Script;
use MiMFa\Library\Style;
use MiMFa\Module\Modal;

/**
 * All the Global Static Variables and Functions You need to indicate and handle requests and responses
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Globals See the Documentation
 */

#region INITIALIZING
require_once(__DIR__ . DIRECTORY_SEPARATOR . "_.php");
require_once(__DIR__ . DIRECTORY_SEPARATOR . "global" . DIRECTORY_SEPARATOR . "Address.php");
\_::$Address = new Address();
\_::$Address->Initial();

\_::$Sequence = [
	str_replace(["\\", "/"], DIRECTORY_SEPARATOR, $GLOBALS["DIR"] ?? "")
	=> str_replace(["\\", "/"], "/", $GLOBALS["ROOT"] ?? ""),
	...($GLOBALS["SEQUENCES"] ?? []),
	str_replace(["\\", "/"], DIRECTORY_SEPARATOR, __DIR__ . DIRECTORY_SEPARATOR ?? "")
	=> str_replace(["\\", "/"], "/", $GLOBALS["BASE_ROOT"] ?? "")
];

run("global/Base");
run("global/Types");

run("global/RouterBase");
run("Router");
\_::$Router = new Router(
	$GLOBALS["ASEQBASE"],
	$GLOBALS["DIR"],
	getHost() . "/"//??$GLOBALS["ROOT"]
);

library("Local");
library("Convert");
library("Html");
library("Style");
library("Script");
library("Internal");

run("global/ConfigBase");
run("Config");
\_::$Config = new Config();

run("global/BackBase");
run("Back");
\_::$Back = new Back();

run("global/FrontBase");
run("Front");
\_::$Front = new Front();

run("global/UserBase");
run("User");
\_::$User = new User();

run("global/InfoBase");
run("Info");
\_::$Info = new Info();

Local::CreateDirectory(\_::$Router->LogDirectory);
Local::CreateDirectory(\_::$Router->TempDirectory);
register_shutdown_function('cleanupTemp', false);

component("Component");
template("Template");
module("Module");

function initialize(string|null|int $status = null, $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null, bool $require = false, bool $once = true)
{
	responseStatus($status);
	runSequence("initialize", $data, $print, $origin, $depth, $alternative, $default, $require, $once);
}
function finalize(string|null|int $status = null, $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null, bool $require = false, bool $once = true)
{
	runSequence("finalize", $data, $print, $origin, $depth, $alternative, $default, $require, $once);
	if (is_null($status))
		exit;
	else
		exit($status);
}
#endregion 


#region SENDING

/**
 * Send values to the client side
 * @param string $method The Method to send data
 * @param mixed $url The Url to send data
 * @param mixed $data Desired data
 * @return bool|string Its sent or received response
 */
function send($method = null, $url = null, mixed $data = [], array|null $options = null, array|null $headers = null, null|bool $secure = null, int $timeout = 60, $async = false)
{
	if (isEmpty($url))
		$url = getPath();
	if (isEmpty($method))
		$method = "POST";
	else
		$method = strtoupper($method);
	switch ($method) {
		case 'POST':
			return sendPost($url, $data, $options, $headers, $secure, $timeout);
		case 'GET':
			return sendGet($url, $data, $options, $headers, $secure, $timeout);
		case 'FILE':
			return sendFile($url, $data, $options, $headers, $secure, $timeout);
	}
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
	curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($data) ? http_build_query($data) : $data); // Data to be posted
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
	curl_setopt($curl, CURLOPT_TIMEOUT, $timeout); // Set a timeout to avoid hanging indefinitely
	if (!is_null($secure)) {
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $secure);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $secure);
	}
	if (!is_null($headers))
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	if (!is_null($options))
		curl_setopt_array($curl, $options);
	$response = curl_exec($curl);
	if (curl_errno($curl)) {
		$errorMessage = curl_error($curl);
		curl_close($curl);
		trigger_error("cURL Error: $errorMessage", E_USER_WARNING);
		return false;
	}
	curl_close($curl);
	return $response;
}
/**
 * Send values to the client side
 * @param mixed $url The Url to send GET data from that
 * @param mixed $data Additional data to send as query parameters
 * @return bool|string Its sent or received response
 */
function sendGet($url = null, mixed $data = [], array|null $options = null, array|null $headers = null, null|bool $secure = null, int $timeout = 60, $async = false)
{
	if (isEmpty($url))
		$url = getPath();
	$curl = curl_init();
	$queryParams = http_build_query($data);
	$urlWithParams = $url . (strpos($url, '?') === false ? '?' : '&') . $queryParams;
	curl_setopt($curl, CURLOPT_URL, $urlWithParams);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_TIMEOUT, $timeout); // Set a timeout to avoid hanging indefinitely
	if (!is_null($secure)) {
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $secure);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $secure);
	}
	if (!is_null($headers))
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	if (!is_null($options))
		curl_setopt_array($curl, $options);
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}
/**
 * Send posted values to the client side
 * @param mixed $url The Url to send POST data to that
 * @param mixed $data Desired data to POST
 * @return bool|string Its sent or received response
 */
function sendPost($url = null, mixed $data = [], array|null $options = null, array|null $headers = null, null|bool $secure = null, int $timeout = 60, $async = false)
{
	if (isEmpty($url))
		$url = getPath();
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_POST, true); // Use POST method
	curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($data) ? http_build_query($data) : $data); // Data to be posted
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
	curl_setopt($curl, CURLOPT_TIMEOUT, $timeout); // Set a timeout to avoid hanging indefinitely
	if (!is_null($secure)) {
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $secure);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $secure);
	}
	if (!is_null($headers))
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	if (!is_null($options))
		curl_setopt_array($curl, $options);
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}
/**
 * Send putted values to the client side
 * @param mixed $url The Url to send PUT data to that
 * @param mixed $data Desired data
 * @return bool|string Its sent or received response
 */
function sendPut($url = null, mixed $data = [], array|null $options = null, array|null $headers = null, null|bool $secure = null, int $timeout = 60, $async = false)
{
	return send("put", $url, $data, $options, $headers, $secure, $timeout, $async);
}
/**
 * Send patched values to the client side
 * @param mixed $url The Url to send PATCH data to that
 * @param mixed $data Desired data
 * @return bool|string Its sent or received response
 */
function sendPatch($url = null, mixed $data = [], array|null $options = null, array|null $headers = null, null|bool $secure = null, int $timeout = 60, $async = false)
{
	return send("patch", $url, $data, $options, $headers, $secure, $timeout, $async);
}
/**
 * Send file values to the client side
 * @param mixed $url The Url to send FILE data to that
 * @param mixed $data Desired data to FILE
 * @return bool|string Its sent or received response
 */
function sendFile($url = null, mixed $data = [], array|null $options = null, array|null $headers = null, null|bool $secure = null, int $timeout = 60, $async = false)
{
	if (isEmpty($url))
		$url = getPath();
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
	curl_setopt($curl, CURLOPT_TIMEOUT, $timeout); // Set a timeout to avoid hanging indefinitely
	if (!is_null($secure)) {
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $secure);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $secure);
	}
	$fields = [];
	if (is_iterable($data))
		foreach ($data as $key => $value) {
			if (is_file($value)) {
				$fields[$key] = curl_file_create($value);
			} else {
				$fields[$key] = $value;
			}
		} else
		$fields = $data;
	curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
	if (!is_null($headers))
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	if (!is_null($options))
		curl_setopt_array($curl, $options);
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}
/**
 * Send delete values to the client side
 * @param mixed $url The Url to send DELETE data to that
 * @param mixed $data Desired data to DELETE
 * @return bool|string Its sent or received response
 */
function sendDelete($url = null, mixed $data = [], array|null $options = null, array|null $headers = null, null|bool $secure = null, int $timeout = 60, $async = false)
{
	return send("delete", $url, $data, $options, $headers, $secure, $timeout, $async);
}
/**
 * Send stream values to the client side
 * @param mixed $url The Url to send STREAM data to that
 * @param mixed $data Desired data to STREAM
 * @return bool|string Its sent or received response
 */
function sendStream($url = null, mixed $data = [], array|null $options = null, array|null $headers = null, null|bool $secure = null, int $timeout = 60, $async = false)
{
	return send("stream", $url, $data, $options, $headers, $secure, $timeout, $async);
}
/**
 * Send internal values to the client side
 * @param mixed $url The Url to send INTERNAL data to that
 * @param mixed $data Desired data to INTERNAL
 * @return bool|string Its sent or received response
 */
function sendInternal($url = null, mixed $data = [], array|null $options = null, array|null $headers = null, null|bool $secure = null, int $timeout = 60, $async = false)
{
	return send("internal", $url, $data, $options, $headers, $secure, $timeout, $async);
}
/**
 * Send external values to the client side
 * @param mixed $url The Url to send EXTERNAL data to that
 * @param mixed $data Desired data to EXTERNAL
 * @return bool|string Its sent or received response
 */
function sendExternal($url = null, mixed $data = [], array|null $options = null, array|null $headers = null, null|bool $secure = null, int $timeout = 60, $async = false)
{
	return send("external", $url, $data, $options, $headers, $secure, $timeout, $async);
}

#endregion


#region RECEIVING

/**
 * Receive requests from the client side
 * @param array|string|null $method The the received data source $_GET/$POST/$_FILES/... (by default it is $_REQUEST)
 * @return mixed The value
 */
function receive(array|string|null $method = null)
{
	if (is_null($method))
		$method = getMethodName();
	// if (isEmpty($_REQUEST)) parse_str(file_get_contents('php://input'), $method);
	// else $method = $_REQUEST;
	if (is_string($method))
		switch ($method = trim(strtolower($method))) {
			case "file":
			case "files":
				$method = $_FILES;
			case "public":
			case "get":
				$method = $_GET;
				break;
			case "private":
			case "post":
			case "put":
			case "patch":
			case "delete":
				if ($method === "post" && $_POST) {
					$method = $_POST;
					break;
				}
				$res = file_get_contents('php://input');
				if (!isEmpty($res)) {
					if (isJson($res))
						$method = Convert::FromJson($res) ?? $method;
					else if (strpos($_SERVER['CONTENT_TYPE'] ?? "", 'multipart/form-data') !== false)
						$method = Convert::FromFormData($res, $_FILES) ?? $method;
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
							$method = Convert::FromJson($res) ?? $method;
						else if (strpos($_SERVER['CONTENT_TYPE'] ?? "", 'multipart/form-data') !== false)
							$method = Convert::FromFormData($res, $_FILES) ?? $method;
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
	return $method;
}
/**
 * Received input from the client side
 * @param mixed $key The getted data key
 * @return mixed Received data
 */
function receiveGet($key = null, $default = null)
{
	return getReceived($key, $default, "get");
}
/**
 * Received posted values from the client side
 * @param mixed $key The posted data key
 * @return mixed Received data
 */
function receivePost($key = null, $default = null)
{
	return getReceived($key, $default, "post");
}
/**
 * Received putted values from the client side
 * @param mixed $key The putted data key
 * @return mixed Received data
 */
function receivePut($key = null, $default = null)
{
	return getReceived($key, $default, "put");
}
/**
 * Received patched values from the client side
 * @param mixed $key The patched data key
 * @return mixed Received data
 */
function receivePatch($key = null, $default = null)
{
	return getReceived($key, $default, "patch");
}
/**
 * Received file values from the client side
 * @param mixed $key The file data key
 * @return mixed Received data
 */
function receiveFile($key = null, $default = null)
{
	return getReceived($key, $default, $_FILES);
}
/**
 * Received deleted values from the client side
 * @param mixed $key The deleted data key
 * @return mixed Received data
 */
function receiveDelete($key = null, $default = null)
{
	return getReceived($key, $default, "delete");
}
/**
 * Received stream values from the client side
 * @param mixed $key The internal data key
 * @return mixed Received data
 */
function receiveStream($key = null, $default = null)
{
	return getReceived($key, $default, "stream");
}
/**
 * Received internal values from the client side
 * @param mixed $key The internal data key
 * @return mixed Received data
 */
function receiveInternal($key = null, $default = null)
{
	return getReceived($key, $default, "internal");
}
/**
 * Received external values from the client side
 * @param mixed $key The internal data key
 * @return mixed Received data
 */
function receiveExternal($key = null, $default = null)
{
	return getReceived($key, $default, "external");
}

/**
 * Receive a parameter from the client side
 * @param mixed $key The key of the received value
 * @param array|string|null $method The the received data source $_GET/$POST/$_FILES/... (by default it is $_REQUEST)
 * @return mixed The value
 */
function getReceived($key = null, $default = null, array|string|null $method = null)
{
	if (is_null($key))
		return \_::Cache($method, fn() => receive($method)) ?? $default;
	else
		return getValid(\_::Cache($method, fn() => receive($method)), $key, $default);
}
/**
 * Receive a parameter from the client side then remove it
 * @param mixed $key The key of the received value
 * @param array|string|null $method The the received data source $_GET/$POST/$_FILES/... (by default it is $_REQUEST)
 * @return mixed The value
 */
function popReceived($key = null, $default = null, array|string|null $method = null)
{
	$val = null;
	if (is_null($key)) {
		$val = getReceived($key, $default, $method);
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
		$val = getReceived($key, $default, $method);
		unset($_POST[$key]);
		unset($_GET[$key]);
		unset($_REQUEST[$key]);
		unset($_FILES[$key]);
	}
	return $val;
}

#endregion 


#region REQUESTING

/**
 * Request something from parts of the client side
 * @param mixed $intent The front JS codes to collect requested thing from the client side 
 * @param mixed $callback The call back handler
 * @example: request('$("body").html', function(selectedHtml)=>{ //do somework })
 */
function request($intent = null, $callback = null)
{
	return beforeUsing(\_::$Address->Directory, "finalize", function () use ($intent, $callback) {
		$callbackScript = "(data,err)=>document.querySelector('body').after(...((html)=>{el=document.createElement('qb');el.innerHTML=html;return el.childNodes;})(data??err))";
		$progressScript = "null";
		$timeout = 60000;
		$start = Internal::MakeStartScript(true);
		$end = Internal::MakeEndScript(true);
		$id = "S_" . getID(true);
		$intent = is_string($intent) ? $intent : Script::Convert($intent);
		if (isStatic($callback))
			response(Html::Script("$start(" . $callbackScript . ")(" .
				Script::Convert($callback) . ",$intent);document.getElementById('$id').remove();$end", null, ["id" => $id]));
		else
			response(Html::Script(
				$callback ? $start .
				'sendInternal(null,{"' . Internal::Set($callback) . '":JSON.stringify(' . $intent . ")}, null,$callbackScript,$callbackScript, null,$progressScript,$timeout);document.getElementById('$id').remove();$end"
				: $intent,
				null,
				["id" => $id]
			));
	});
}

/**
 * Have a dialog with the client side
 * @param mixed $intent The front JS codes
 * @param mixed $callback The call back handler
 * @example: interact('$("body").html', function(selectedHtml)=>{ //do somework })
 * @return string|null The result of the client side
 */
function interact($intent = null, $callback = null)
{
	$id = "Dialog_" . getId(true);
	request(
		"setMemo('$id', $intent, 60000)",
		$callback
	);
	return popMemo($id);
}
function alert($message = null, $callback = null)
{
	return interact(
		Script::Alert($message) . "??true",
		$callback
	);
}
function confirm($message = null, $callback = null)
{
	return interact(
		Script::Confirm($message),
		$callback
	);
}
function prompt($message = null, $callback = null, $default = null)
{
	return interact(
		Script::Prompt($message, $default),
		$callback
	);
}

#endregion 


#region RESPONSING

/**
 * Echo the output on the client side
 * @param mixed $content The data that is ready to print
 * @param mixed $status The header status
 */
function response($content = null, $status = null)
{
	responseStatus($status);
	echo $content = Convert::ToString($content);
	return $content;
}
/**
 * Echo scripts to the client side
 */
function script($content, $source = null, ...$attributes)
{
	echo $content = Html::Script($content, $source, ...$attributes);
	return $content;
}
/**
 * Echo styles to the client side
 */
function style($content, $source = null, ...$attributes)
{
	echo $content = Html::Style($content, $source, ...$attributes);
	return $content;
}

/**
 * Show a modal output in the client side
 * @param mixed $content The data that is ready to print
 * @return mixed Printed data
 */
function modal($content = null)
{
	return response(Html::Modal($content));
}

/**
 * Show a message result output to the client side
 * @param mixed $message The data that is ready to print
 * @return mixed Printed data
 */
function message($message = null)
{
	if (is_a($message, "Exception") || is_subclass_of($message, "Exception"))
		return script(Script::Log($message->getMessage(), "message"));
	echo $message = Html::Result($message);
	return $message;
}
/**
 * Show a success result output to the client side
 * @param mixed $message The data that is ready to print
 * @return mixed Printed data
 */
function success($message = null)
{
	if (is_a($message, "Exception") || is_subclass_of($message, "Exception"))
		return script(Script::Log($message->getMessage(), "success"));
	echo $message = Html::Success($message);
	return $message;
}
/**
 * Show a warning result output to the client side
 * @param mixed $message The data that is ready to print
 * @return mixed Printed data
 */
function warning($message = null)
{
	if (is_a($message, "Exception") || is_subclass_of($message, "Exception"))
		return script(Script::Log($message->getMessage(), "warn"));
	echo $message = Html::Warning($message);
	return $message;
}
/**
 * Show an error result output to the client side
 * @param mixed $message The data that is ready to print
 * @return mixed Printed data
 */
function error($message = null)
{
	responseStatus(400);
	if (is_a($message, "Exception") || is_subclass_of($message, "Exception"))
		return script(Script::Log($message->getMessage(), "error"));
	echo $message = Html::Error($message);
	return $message;
}
/**
 * Show message on the console
 * @param mixed $message
 */
function report($message = null, $type = "log")
{
	return script(Script::Log($message, $type));
}

/**
 * Convert markdown supported data and echo them on the client side
 * @param mixed $content The data that is ready to print
 * @param array|null $replacements A key=>value array of all parts to their replacement matchs
 * @param mixed $status The header status
 * @return mixed Printed data
 */
function render($content = null, $replacements = null, $status = null)
{
	responseStatus($status);
	echo $content = Html::Convert($replacements ? decode($content, $replacements) : $content);
	return $content;
}

/**
 * To change the header in the client side
 * @param mixed $key The header key
 * @param mixed $value The header value
 * @return bool True if header is set, false otherwise
 */
function responseHeader($key, $value)
{
	if ($key && !headers_sent()) {
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
function responseType($value = null)
{
	if (!headers_sent()) {
		header("Content-Type: " . ($value ?? "text/html"));
		return true;
	}
	return false;
}
/**
 * To change the status of the results for the client side
 * @param int|null $status The header status
 * @return bool True if header is set, false otherwise
 */
function responseStatus($status = null)
{
	if ($status && !headers_sent()) {
		header("HTTP/1.1 " . abs($status));
		return true;
	}
	return false;
}
/**
 * Print this content on the client side then reload the page
 * @param mixed $content The data that is ready to print
 * @param mixed $url The next url to show after rendering output,
 * @param int $delay A milliseconds number to drop a delay in breaking page
 * if leave null it will try to find the next or previous request into the getted url,
 * or will reload the page otherwise
 * @param $forward Send the target url or send true to go forward, false to go to the previous page, and null otherwise
 */
function responseBreaker($content = null, $forward = null, $delay = 0)
{
	if ($forward === false)
		$forward = getBackPath();
	elseif ($forward === true)
		$forward = getForePath();
	$forward = $forward ?? receiveGet("Next") ?? receiveGet("Previous");
	$script = "window.location.assign(" . (isValid($forward) ? Script::Convert(Local::GetUrl($forward)) : "location.href") . ")";
	echo ($content = Convert::ToString($content)) .
		Html::Script($delay ? "setTimeout(()=>$script, $delay);" : "$script;");
	return $content;
}

/**
 * Replace the output with all the document in the client side
 * @param mixed $output The data that is ready to print
 * @param string $target The url to show without updating the page
 */
function entireResponse($output = null, $target = null)
{
	response(Html::Script(
		Internal::MakeScript(
			$output,
			null,
			"(data,err)=>{"
			($output ? "document.open();document.write(data??err);document.close();" : "") .
			($target ? "window.history.pushState(null, null, `" . getFullUrl($target) . "`);" : "") .
			"}"
		)
	));
}
/**
 * Clean (erase) the output buffer and turn off output buffering
 */
function eraseResponse()
{
	if (ob_get_level())
		return ob_end_clean(); // Clean any remaining output buffers
	return null;
}
#endregion 


#region DELIVERING
/**
 * Print only this output on the client side, Clear before then end
 * @param mixed $output The data that is ready to print
 * @param mixed $status The header status
 */
function deliver($output = null, $status = null)
{
	eraseResponse(); // Clean any remaining output buffers
	responseStatus($status);
	if ($output) {
		echo Convert::ToString($output);
		finalize();
	} else
		finalize();
}
/**
 * Response scripts to the client side
 * @param mixed $output The script that is ready to send as the response
 * @return void
 */
function deliverScript($output = null, $status = null)
{
	deliver(Html::Script($output), $status);
}
/**
 * Print only this JSON on the client side, Clear before then end
 * @param mixed $output The data that is ready to print
 * @param mixed $status The header status
 */
function deliverJson($output = null, $status = null)
{
	eraseResponse(); // Clean any remaining output buffers
	responseType("application/json");
	deliver(isJson($output) ? $output : Convert::ToJson($output), $status);
}
/**
 * Print only this XML on the client side, Clear before then end
 * @param mixed $output The data that is ready to print
 * @param mixed $status The header status
 */
function deliverXml($output = null, $status = null)
{
	eraseResponse(); // Clean any remaining output buffers
	responseType("application/xml");
	deliver(is_string($output) ? $output : Convert::ToXmlString($output), $status);
}
/**
 * Sends a file to the client side.
 * @param string $output The absolute or relative path to the file.
 * @param int|null $status The HTTP status code (e.g., 200, 404).
 * @param string|null $type The file content type (e.g., "application/pdf", "image/jpeg").
 * @param bool $attachment Whether to display the file inline in the browser or as an attachment.
 * @param string|null $name Optional filename to force download with a specific name.
 * @throws \Exception If the file path is invalid or the file cannot be read.
 */
function deliverFile($output = null, $status = null, $type = null, bool $attachment = false, ?string $name = null)
{
	eraseResponse(); // Clean any remaining output buffers

	$output = Local::GetFile($output);
	if ($output)
		responseStatus($status);
	else {
		responseStatus(404);
		finalize();
	}
	if ($type)
		header("Content-Type: $type");
	else {
		// Attempt to guess content type if not provided
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$type = finfo_file($finfo, $output);
		finfo_close($finfo);
		header("Content-Type: " . $type);
	}
	// Sanitize the filename
	$n = explode("/", $output);
	$name = preg_replace('/[^\w\-.]/', '_', $name ?? end($n));
	$disposition = $attachment ? 'attachment' : 'inline';
	header("Content-Disposition: $disposition; filename=\"" . ($name ?? basename($output)) . '"');
	header('Content-Length: ' . filesize($output));
	//header("Etag: " . md5_file($output)); // Simple ETag (entity tag) response header is an identifier for a specific version of a resource. It lets caches be more efficient and save
	//Read and output the file
	readfile($output);
	finalize();
}

/**
 * To deliver a modal output in the client side
 * @param mixed $content The data that is ready to print
 * @return mixed Printed data
 */
function deliverModal($content = null)
{
	return deliver(Html::Modal($content));
}
/**
 * To deliver a message result output to the client side
 * @param mixed $message The data that is ready to print
 * @return mixed Printed data
 */
function deliverMessage($message = null)
{
	if (is_a($message, "Exception") || is_subclass_of($message, "Exception"))
		return deliverScript(Script::Log($message->getMessage(), "message"), 400);
	return deliver(Html::Result($message));
}
/**
 * To deliver a success result output to the client side
 * @param mixed $message The data that is ready to print
 * @return mixed Printed data
 */
function deliverSuccess($message = null)
{
	if (is_a($message, "Exception") || is_subclass_of($message, "Exception"))
		return deliverScript(Script::Log($message->getMessage(), "success"), 400);
	return deliver(Html::Success($message));
}
/**
 * To deliver a warning result output to the client side
 * @param mixed $message The data that is ready to print
 * @return mixed Printed data
 */
function deliverWarning($message = null)
{
	if (is_a($message, "Exception") || is_subclass_of($message, "Exception"))
		return deliverScript(Script::Log($message->getMessage(), "warn"), 400);
	return deliver(Html::Warning($message));
}
/**
 * To deliver an error result output to the client side
 * @param mixed $message The data that is ready to print
 * @return mixed Printed data
 */
function deliverError($message = null)
{
	if (is_a($message, "Exception") || is_subclass_of($message, "Exception"))
		return deliverScript(Script::Log($message->getMessage(), "error"), 400);
	return deliver(Html::Error($message), 400);
}
/**
 * To deliver message on the console
 * @param mixed $message
 */
function deliverReport($message = null)
{
	return deliverScript(Script::Log($message));
}

/**
 * Print only this output on the client side then reload the page
 * @param mixed $output The data that is ready to print
 * @param mixed $status The header status
 * @param $forward Send the target url or send true to go forward, false to go to the previous page, and null otherwise
 * @param int $delay A milliseconds number to drop a delay in breaking page
 * if leave null it will try to find the next or previous request into the getted url,
 * or will reload the page otherwise
 */
function deliverBreaker($output = null, $status = null, $forward = null, $delay = 0)
{
	eraseResponse(); // Clean any remaining output buffers
	responseStatus($status);
	responseBreaker($output, $forward, $delay);
	finalize();
}
#endregion 


#region NAVIGATING

/**
 * Change the location address of the window
 * @param string $url The url to show for the current page without updating the page
 * @return void
 */
function locate($url = null)
{
	deliverScript("window.history.replaceState(null, null, " . (empty($url) ? "location.href" : "`" . getFullUrl($url) . "`") . ");");
}
/**
 * Push the new location address a the url of the window
 * @param string $url The url to show for the current page without updating the page
 * @return void
 */
function relocate($url = null)
{
	deliverScript("window.history.pushState(null, null, " . (empty($url) ? "location.href" : "`" . getFullUrl($url) . "`") . ");");
}
/**
 * Load the new address
 * @param mixed $url
 * @param bool|string $target Put true to visit in "_blank" target, false for "_self" or put the name of target otherwise
 * @return void
 */
function load($url = null, $target = null)
{
	if (is_null($target))
		deliverScript("window.location.href = " . (empty($url) ? "location.href" : "`" . getFullUrl($url) . "`") . ";");
	else
		deliverScript("window.open(" . (isValid($url) ? "'" . getFullUrl($url) . "'" : "location.href") . ", '" . ($target === true ? "_blank" : ($target === false ? "_self" : $target)) . "');");
}
/**
 * Reload the current location
 * @return void
 */
function reload()
{
	deliverScript("window.location.reload();");
}
/**
 * Open a message by a sharable app 
 * @param mixed $output The url or text to share
 * @param mixed $with The path or phone number to share
 * @return void
 */
function share($output = null, $with = null)
{
	deliverScript("window.open('sms://$with?body='+" . (isValid($output) ? "'" . __($output) . "'" : "location.href") . ", '_blank');");
}

#endregion 


#region PROTECTING

/**
 * Check if the client has access to the page or assign them to other page, based on thair IP, Accessibility, Restriction and etc.
 * @param int|null $minaccess The minimum accessibility for the client, pass null to give the user access
 * @param bool $assign Assign clients to other page, if they have not enough access
 * @param bool|int|string|null $exit Pass true to die the process if clients have not enough access, else pass false
 * Die the process with this status if clients have not enough access
 * @return bool|int|null The client has accessibility bigger than $minaccess or not
 * user accessibility group
 */
function inspect($minaccess = 0, bool|string $assign = true, bool|string|int|null $exit = true)
{
	if (isValid(\_::$Router->StatusMode)) {
		if ($assign) {
			if (is_string($assign))
				load($assign);
			else
				route(\_::$Router->StatusMode ?? \_::$Router->RestrictionRouteName, alternative: "403");
		}
		if ($exit !== false)
			finalize($exit);
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
					load($assign);
				else
					route(\_::$Router->RestrictionRouteName, alternative: "401");
			}
			if ($exit !== false)
				finalize($exit);
			return false;
		}
	}
	$b = \_::$User->GetAccess($minaccess);
	if ($b)
		return $b;
	if ($assign) {
		if (is_string($assign))
			load($assign);
		elseif (
			startsWith(\_::$Address->Request, \_::$User->InHandlerPath) ||
			startsWith(\_::$Address->Request, \_::$User->UpHandlerPath) ||
			startsWith(\_::$Address->Request, \_::$User->RecoverHandlerPath) ||
			startsWith(\_::$Address->Request, \_::$User->ActiveHandlerPath)
		)
			return true;
		else
			load(\_::$User->InHandlerPath);
	}
	if ($exit !== false)
		finalize($exit);
	return $b;
}

function setTimer($timeout = 60000, $key = null)
{
	if ($timeout < 1)
		return false;
	return setSecret(getClientCode("Timer_" . ($key ?? \_::$Address->Direction)), time() + max(1, $timeout / 1000));
}
function getTimer($key = null)
{
	$key = getClientCode("Timer_" . ($key ?? \_::$Address->Direction));
	if (hasSecret($key)) {
		$remains = getSecret($key) - time();
		if ($remains <= 0)
			popSecret($key);
		return $remains * 1000;
	}
	return null;
}
function popTimer($key = null)
{
	$remains = getTimer($key);
	popSecret(getClientCode("Timer_" . ($key ?? \_::$Address->Direction)));
	return $remains;
}
function hasTimer($key = null)
{
	return getTimer($key) > 0;
}

function encode($plain, &$replacements = [], $wrapStart = "<", $wrapEnd = ">", $pattern = '/("\S+[^"]*")|(\'\S+[^\']*\')|(<\S+[\w\W]*[^\\\\]>)|(\d*\.?\d+)/iU', $correctorPattern = null, $correctorReplacement = "")
{
	if (!is_array($replacements))
		$replacements = array();
	$c = count($replacements);
	return $correctorPattern ? preg_replace_callback($pattern, function ($a) use (&$replacements, &$c, $wrapStart, $wrapEnd, $correctorPattern, $correctorReplacement) {
		$val = preg_replace($correctorPattern, $correctorReplacement, $a[0]);
		$key = array_search($val, $replacements);
		if (!$key)
			$replacements[$key = $wrapStart . $c++ . $wrapEnd] = $val;
		return $key;
	}, $plain) :
		preg_replace_callback($pattern, function ($a) use (&$replacements, &$c, $wrapStart, $wrapEnd, $correctorPattern, $correctorReplacement) {
			$val = $a[0];
			$key = array_search($val, $replacements);
			if (!$key)
				$replacements[$key = $wrapStart . $c++ . $wrapEnd] = $val;
			return $key;
		}, $plain);
}
function decode($cipher, ?array $replacements)
{
	if (is_array($replacements))
		foreach (array_reverse($replacements) as $k => $v)
			$cipher = str_replace($k, $v, $cipher);
	return $cipher;
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
	return \_::$Back->Cryptograph->Encrypt($plain, $key ?? \_::$Back->SoftKey, true);
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
	return \_::$Back->Cryptograph->Decrypt($cipher, $key ?? \_::$Back->SoftKey, true);
}

#endregion


#region USING

/**
 * To search and find the correct path of a file between all sequences
 * @param string|null $path The relative file or directory path
 * @param false|null|string|array $extension The extention like ".jpg" (leave null for default value ".php")
 * @param string|int $origin The start layer of the sequences (a zero started index)
 * @param int $depth How much layers it should iterate in searching
 * @param string $address [optional] To get the filal found address.
 * @return string|null The correct path of the file or null if its could not find
 */
function address(string|null $path = null, $extension = false, string|int $origin = 0, int $depth = 999999)
{
	$path = str_replace(["\\", "/"], DIRECTORY_SEPARATOR, $path ?? "");
	$extension = $extension === false ? "" : $extension ?? \_::$Extension;
	$path = preg_replace("/(?<!\\" . DIRECTORY_SEPARATOR . ")\\" . DIRECTORY_SEPARATOR . "$/", DIRECTORY_SEPARATOR . "index", $path);
	if (!endsWith($path, $extension) && !endsWith($path, DIRECTORY_SEPARATOR))
		$path .= $extension;
	$address = null;
	//$toSeq = $depth < 0 ? (count(\_::$Sequence) + $depth) : ($origin + $depth);
	if (is_string($origin)) {
		$key = null;
		$index = 0;
		foreach (\_::$Sequence as $k) {
			if ($origin === $k) {
				$key = $origin = $index;
				break;
			}
			$index++;
		}
		if (is_null($key))
			$origin = 0;
	}
	$scount = count(\_::$Sequence);
	$origin = $origin < 0 ? ($scount + $origin) : min($scount, $origin);
	$toSeq = $depth < 0 ? ($scount + $depth) : min($scount, $origin + $depth);
	$seqInd = -1;
	$path = ltrim($path, DIRECTORY_SEPARATOR);
	foreach (\_::$Sequence as $dir => $host)
		if (++$seqInd < $origin)
			continue;
		elseif ($seqInd < $toSeq) {
			if (file_exists($address = $dir . $path))
				return $address;
		} else
			return null;
	return null;
}
/**
 * To reads entire file into a string by a relative path between all sequences
 * This function is similar to file(),
 * except that this returns the file in a string,
 * starting at the specified offset up to length bytes. On failure, this will return false.
 * @param string|null $path The relative file or directory path
 * @param false|null|string|array $extension The extention like ".jpg" (leave null for default value ".php")
 * @param string|int $origin The start layer of the sequences (a zero started index)
 * @param int $depth How much layers it should iterate to find the correct address
 * @param int $offset [optional] The offset where the reading starts.
 * @param int|null $length [optional] Maximum length of data read. The default is to read until end of file is reached.
 * @param string $address [optional] To get the filal found or maked address.
 * @return string|false|null The function returns the read data or flse on failure or null if could not find the file.
 */
function open(string|null $path = null, $extension = false, string|int $origin = 0, int $depth = 999999, int $offset = 0, int|null $length = null, &$address = null)
{
	$address = address($path, $extension, $origin, $depth);
	return $address ? file_get_contents($address, offset: $offset, length: $length) : null;
}
/**
 * To write data into an exists file or by a relative path or the new file by the exact path
 * @param $data The data to write. Can be either a string, an array or a each other data types.
 * @param string|null $path The relative file or directory path
 * @param false|null|string|array $extension The extention like ".jpg" (leave null for default value ".php")
 * @param string|int $origin The start layer of the sequences (a zero started index)
 * @param int $depth How much layers it should iterate to find the correct address
 * @return string|false|null The function returns the number of bytes that were written to the file, or false on failure.
 */
function save($data, string|null $path = null, $extension = false, string|int $origin = 0, int $depth = 999999, int $flags = 0, &$address = null)
{
	$address = address($path, $extension, $origin, $depth);
	return file_put_contents($address = $address ?: ($path ? Local::GetAbsoluteAddress(DIRECTORY_SEPARATOR . $path) : Local::CreateAddress()), Convert::ToString($data), flags: $flags);
}

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
			foreach (glob($path . "*" . \_::$Extension) as $path)
				if (!is_null($r = $once ? include_once $path : include $path))
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
 * To search in a specific directory in all sequences, to find a file with the name then including that
 * @param string|null $directory The relative file directory
 * @param string|null $name The file name
 * @param false|null|string|array $extension The extention like ".jpg" (leave null for default value ".php")
 * @param string|int $origin The start layer of the sequences (a zero started index)
 * @param int $depth How much layers it should iterate in searching
 * @return mixed The including results or null if its could not find
 */
function using(string|null $directory, string|null $name = null, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null, string|null $extension = null, bool $require = false, bool $once = false, &$used = null)
{
	try {
		usingBefores($directory, $used = $name);
		if (
			$path =
			address("$directory$name", $extension, $origin, $depth) ??
			address($directory . ($used = $alternative), $extension, $origin, $depth)
		)
			return $require ?
				requiring(path: $path, data: $data, print: $print, default: $default, once: $once) :
				including(path: $path, data: $data, print: $print, default: $default, once: $once);
		else
			$used = null;
	} finally {
		usingAfters($directory, $name);
	}
}
/**
 * Render or do something before using any function or directory's files or actions
 * @param mixed $directory function name or directory
 * @param null|string $name file name
 * @param null|string|callable $action the output content or action you want to do
 */
function beforeUsing($directory, string|null $name = null, null|string|callable $action = null)
{
	if (isValid($action)) {
		$directory = strtolower($directory ?? "");
		$name = strtolower($name ?? "");
		if (!isset(\_::$BeforeActions[$directory]))
			\_::$BeforeActions[$directory] = array();
		if (!isset(\_::$BeforeActions[$directory][$name]))
			\_::$BeforeActions[$directory][$name] = array();
		array_push(\_::$BeforeActions[$directory][$name], $action);
	}
}
/**
 * Render or do something after using any function or directory's files or actions
 * @param mixed $directory function name or directory
 * @param null|string $name file name
 * @param null|string|callable $action the output content or action you want to do
 */
function afterUsing($directory, string|null $name = null, null|string|callable $action = null)
{
	if (isValid($action)) {
		$directory = strtolower($directory ?? "");
		$name = strtolower($name ?? "");
		if (!isset(\_::$AfterActions[$directory]))
			\_::$AfterActions[$directory] = array();
		if (!isset(\_::$AfterActions[$directory][$name]))
			\_::$AfterActions[$directory][$name] = array();
		array_push(\_::$AfterActions[$directory][$name], $action);
	}
}
function usingBefores($directory, string|null $name = null)
{
	$directory = strtolower($directory ?? "");
	$name = strtolower($name ?? "");
	if (isset(\_::$BeforeActions[$directory][$name]))
		response(\_::$BeforeActions[$directory][$name]);
	elseif (isset(\_::$BeforeActions[$directory . $name]))
		response(\_::$BeforeActions[$directory . $name]);
}
function usingAfters($directory, string|null $name = null)
{
	$directory = strtolower($directory ?? "");
	$name = strtolower($name ?? "");
	if (isset(\_::$AfterActions[$directory][$name]))
		response(\_::$AfterActions[$directory][$name]);
	elseif (isset(\_::$AfterActions[$directory . $name]))
		response(\_::$AfterActions[$directory . $name]);
}

/**
 * To grab a hierarchy of keys from the global $data object
 * @param array $hierarchy A hierarchy of desired keys
 */
function data(...$hierarchy)
{
	global $data;
	return pop($data, ...$hierarchy);
}

/**
 * To interprete, the specified file in all sequences
 * @param non-empty-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed
 */
function runSequence($name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null, bool $require = false, bool $once = true)
{
	$depth = min($depth, count(\_::$Sequence)) - 1;
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
function run($name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null, bool $require = true, bool $once = true)
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
function model($name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
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
function library($name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
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
function component($name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
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
function module($name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
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
function template($name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
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
function view($name = null, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->ViewDirectory, $name ?? \_::$Front->DefaultViewName, $data, $print, $origin, $depth, $alternative, $default, once: true);
}

/**
 * To interprete, the specified regionname
 * @param non-empty-lowercase-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed The result of included region or the printed data
 */
function region($name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
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
function page($name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
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
function part($name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
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
function compute($name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->ComputeDirectory, $name, $data, $print, $origin, $depth, $alternative, $default);
}

/**
 * To interprete, the specified routename
 * @param non-empty-lowercase-string|null $name
 * @param mixed $data
 * @param bool $print
 * @return mixed The result of the routers or the printed data
 */
function route($name = null, mixed $data = null, bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->RouteDirectory, $name ?? \_::$Router->DefaultRouteName, $data, $print, $origin, $depth, $alternative, $default);
}

/**
 * To get the url of the selected asset
 * @param non-empty-string $directory The relative file directory
 * @param string|null $name The asset file name
 * @param string|array|null $extensions An array of extensions or a string of the disired extension
 * @return string|null The complete path of selected asset or return null if it's not found
 */
function asset($directory, string|null $name = null, string|array|null $extensions = null, $optimize = false, string|int $origin = 0, int $depth = 999999, $default = null)
{
	$directory = preg_replace("/([\\\\\/]?asset[\\\\\/])|(^[\\\\\/]?)/", \_::$Address->AssetRoot, $directory ?? "");
	$i = 0;
	if (!is_array($extensions))
		$extensions = [$extensions ?? ""];
	$extension = isset($extensions[$i]) ? $extensions[$i++] : "";
	try {
		usingBefores($directory, $name);
		do {
			if ($path = address("$directory$name", $extension, $origin, $depth))
				return getFullUrl($path, $optimize);
		} while ($extension = isset($extensions[$i]) ? $extensions[$i++] : null);
		return $default;
	} finally {
		usingAfters($directory, $name);
	}
}

/**
 * To get a Table from the DataBase
 * @param string $name The raw table name (Without any prefix)
 * @param bool $prefix Add table prefix to the name or not (default is true)
 * @return DataTable The selected database's table
 */
function table(string $name, bool $prefix = true, string|int $origin = 0, int $depth = 999999, ?DataBase $source = null, $default = null)
{
	return new DataTable(
		$source ?? \_::$Back->DataBase,
		$name,
		$prefix ? \_::$Back->DataBasePrefix : null
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
 * To get some parts of an $object addressed by the $hierarchy
 * @param mixed $object The source object
 * @param array $hierarchy A hierarchy of desired keys
 * @return mixed The disired value
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
 * To get some parts of an $object addressed by the $hierarchy, then unset them from the $object
 * @param mixed $object The source object
 * @param array $hierarchy A hierarchy of desired keys
 * @return mixed The disired value
 */
function pop(&$object, ...$hierarchy)
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
					return pop($object[$data], ...$hierarchy);
			} else {
				$data = strtolower($data);
				foreach ($object as $k => $v)
					if ($data === strtolower($k)) {
						$res = $v;
						if ($rem)
							unset($object[$k]);
						else
							return pop($object[$k], ...$hierarchy);
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
					return pop($object->$key, ...$hierarchy);
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
					if (($val = pop($object, $v, ...$hierarchy)) !== null)
						$res[$k] = $val;
				} else
					$res[$k] = pop($object, $k, $v, ...$hierarchy);
		} else {
			foreach ($data as $k => $v)
				if (is_numeric($k)) {
					if (($val = pop($object, $v, ...$hierarchy)) !== null)
						$res[$k] = $val;
				} else
					$res[$k] = pop($object, $k, $v, ...$hierarchy);
		}
	}
	return $res;
}
/**
 * To set some parts of an $object addressed by the $hierarchy
 * @param mixed $object The destination object
 * @param array $hierarchy A hierarchy of desired keys and values
 * @return mixed The old values that are sat
 */
function set(&$object, $hierarchy)
{
	if (!is_array($hierarchy) || !is_object($object))
		try {
			$m = $object;
			$object = $hierarchy;
			return $m;
		} catch (Exception $ex) {
		} else {
		$sat = [];
		foreach ($hierarchy as $k => $v) {
			take($object, $k, $key, $index);
			if ($key)
				if (is_null($index))
					$sat[$key] = set($object->$key, $v);
				else
					$sat[$key] = set($object[$key], $v);
		}
		return $sat;
	}
	return null;
}
/**
 * To set some parts of an $object addressed by the $hierarchy, then unset them from the $hierarchy
 * @param mixed $object The destination object
 * @param array $hierarchy A hierarchy of desired keys and values
 * @return mixed The old values that are sat
 */
function pod(&$object, &$hierarchy)
{
	if (!is_array($hierarchy) || !is_object($object))
		try {
			$m = $object;
			$object = $hierarchy;
			unset($hierarchy);
			return $m;
		} catch (Exception $ex) {
		} else {
		$sat = [];
		foreach ($hierarchy as $k => $v) {
			take($object, $k, $key, $index);
			if ($key) {
				if (is_null($index))
					$sat[$key] = pod($object->$key, $v);
				else
					$sat[$key] = pod($object[$key], $v);
				unset($hierarchy[$k]);
			}
		}
		return $sat;
	}
	return null;
}


/**
 * To seek for a correct value by a case insensitive value on a countable element
 * @param mixed $object The source object
 * @param $value The value sample to find
 * @param $key To get the correct spell of the key (optional)
 * @return mixed
 */
function find($object, mixed $value, &$key = null, int|null &$index = null, $default = null, $caseSensitive = false)
{
	$index = $key = null;
	if (is_null($object) || is_null($value))
		return $value == $object ? $object : $default;
	if (is_array($object)) {
		$index = 0;
		foreach ($object as $k => $v) {
			if ($value === $v) {
				$key = $k;
				return $v;
			}
			$index++;
		}
		if (!$caseSensitive && is_string($value)) {
			$index = 0;
			$it = strtolower($value ?? "");
			foreach ($object as $k => $v) {
				if (is_string($v) && $it === strtolower($v ?? "")) {
					$key = $k;
					return $v;
				}
				$index++;
			}
		}
	}
	$index = null;
	return $default;
}
/**
 * To seek for a result by a key on a countable element
 * @param mixed $object The source object
 * @param $sampler The key sample to find or The filter $by($value, $key, $index)=> // return true if find and false when it is not find 
 * @param $key To get the correct spell of the key (optional)
 * @param $index To get the index of the key (optional)
 * @return mixed
 */
function take($object, callable|string|int|null $sampler, &$key = null, int|null &$index = null, $default = null)
{
	$index = $key = null;
	if (is_callable($sampler)) {
		if (!is_null($object)) {
			$index = 0;
			if (!is_iterable($object)) {
				if ($sampler($object, null, $index))
					return $object;
			} else
				foreach ($object as $key => $value)
					if ($sampler($value, $key, $index++))
						return $value;
		}
		$index = null;
		return $default;
	}
	if (is_null($object) || is_null($sampler))
		return $object;
	if (is_array($object)) {
		$index = is_int($sampler) ? $sampler : 0;
		if (isset($object[$sampler]))
			return $object[$key = $sampler];
		$index = 0;
		$it = strtolower($sampler);
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
		isset($object->{$key = $sampler}) ? $object->$key : (
			isset($object->{$key = strtoproper($sampler)}) ? $object->$key : (
				isset($object->{$key = strtolower($sampler)}) ? $object->$key : (
					isset($object->{$key = strtoupper($sampler)}) ? $object->$key : (($key = null) ?? $default)
				)
			)
		);
}
/**
 * To search and return all succeed results by a callable function on a countable element
 * @param mixed $object The source object
 * @param callable $by The filter $by($value, $key, $index)=> // return true if find and false when it is not find 
 * @return mixed
 */
function search($object, callable $by, $default = null)
{
	if (is_null($object))
		return $object;
	if (isStatic($object))
		return $by($object, null, null) ? $object : $default;
	$res = [];
	$index = 0;
	if (!is_iterable($object)) {
		if ($by($object, null, $index))
			return $object;
	} else
		foreach ($object as $key => $value)
			if ($by($value, $key, $index++))
				$res[$key] = $value;
	return $res ?: $default;
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
 * @return Generator
 */
function seek($object, callable $by)
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
function popValid(&$object, string|null $item = null, $defultValue = null, &$key = null)
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
function popBetween(&$object, ...$items)
{
	foreach ($items as $value)
		if (($value = popValid($object, $value, null)) !== null)
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

#endregion 


#region IDENTIFICATING

function getId($random = false): int
{
	if (!$random)
		return ++\_::$DynamicId;
	list($usec, $sec) = explode(" ", microtime());
	return (int) ($usec * 10000000 + $sec);
}

/**
 * Get the full part of the referrer url
 * @example: "https://www.mimfa.net:5046/Category/mimfa/service/web.php?p=3&l=10#serp&v=3.21"
 */
function getBackPath()
{
	return $_SERVER['HTTP_REFERER']??null ?: receiveGet("BackPath")??receiveGet("Previous");
}
/**
 * Get the full part of the referrer url
 * @example: "https://www.mimfa.net:5046/Category/mimfa/service/web.php?p=3&l=10#serp&v=3.21"
 */
function getForePath()
{
	return receiveGet("ForePath")??receiveGet("Next");
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
		return Local::OptimizeUrl(Local::GetUrl($path));
	return Local::GetUrl($path);
}
/**
 * Get the full part of a url
 * @example: "/Category/mimfa/service/web.php?p=3&l=10#serp" => "https://www.mimfa.net:5046/Category/mimfa/service/web.php?p=3&l=10#serp"
 * @return string|null
 */
function getUrl(string|null $path = null): string|null
{
	if ($path === null)
		$path = getHost() . ($_SERVER["REQUEST_URI"]??null ?: (($_SERVER["PHP_SELF"] ?? null) . ($_SERVER['QUERY_STRING']??null ? "?" . $_SERVER['QUERY_STRING'] : "")));
	return preg_replace("/^([\/\\\])/", rtrim(getHost(), "/\\") . "$1", $path);
}
/**
 * Get the host part of a url
 * @example: "https://www.mimfa.net:5046/Category/mimfa/service/web.php?p=3&l=10#serp" => "https://www.mimfa.net:5046"
 * @return string|null
 */
function getHost(string|null $path = null): string|null
{
	$pat = "/^\w+\:\/*[^\/]+/";
	if ($path == null || !preg_match($pat, $path))
		$path = ((empty($https = $_SERVER['HTTPS'] ?? null) || $https == 'off' || ($_SERVER['SERVER_PORT'] ?? null) != 443) ? "http" : "https") . "://" . ($_SERVER["HTTP_HOST"] ?? null);
	return preg_Find($pat, $path) ?? "";
}
/**
 * Get the site name part of a url
 * @example: "https://www.mimfa.net:5046/Category/mimfa/service/web.php?p=3&l=10#serp" => "www.mimfa.net"
 * @return string|null
 */
function getSite(string|null $path = null): string|null
{
	return preg_replace("/(^\w+:\/*)|(\:\d+$)/", "", getHost($path));
}
/**
 * Get the domain name part of a url
 * @example: "https://www.mimfa.net:5046/Category/mimfa/service/web.php?p=3&l=10#serp" => "mimfa.net"
 * @return string|null
 */
function getDomain(string|null $path = null): string|null
{
	return preg_replace("/(^\w+:\/*(www\.)?)|(\:\d+$)/", "", getHost($path));
}
/**
 * Get the path part of a url
 * @example: "https://www.mimfa.net:5046/Category/mimfa/service/web.php?p=3&l=10#serp" => "https://www.mimfa.net/Category/mimfa/service/web.php"
 * @return string|null
 */
function getPath(string|null $path = null): string|null
{
	return preg_Find("/(^[^\?#]*)/", $path ?? getUrl());
}
/**
 * Get the request part of a url
 * @example: "https://www.mimfa.net:5046/Category/mimfa/service/web.php?p=3&l=10#serp" => "/Category/mimfa/service/web.php?p=3&l=10#serp"
 * @return string|null
 */
function getRequest(string|null $path = null): string|null
{
	if ($path == null)
		$path = getUrl();
	return preg_Replace("/(^\w+:\/*[^\/]+)/", "", $path);
}
/**
 * Get the direction part of a url from the root
 * @example: "https://www.mimfa.net:5046/Category/mimfa/service/web.php?p=3&l=10#serp" => "Category/mimfa/service/web.php"
 * @return string|null
 */
function getDirection(string|null $path = null): string|null
{
	if ($path == null)
		$path = getUrl();//ltrim($_SERVER["REQUEST_URI"],"\\\/");
	return preg_Replace("/(^\w+:\/*[^\/]+\/)|([\?#].+$)/", "", $path);
}
/**
 * Get the last part of a direction url
 * @example: "https://www.mimfa.net:5046/Category/mimfa/service/web.php?p=3&l=10#serp" => "web.php"
 * @return string|null
 */
function getPage(string|null $path = null): string|null
{
	return last(preg_split("/[\\\\\/]/i", getDirection($path)));
}
/**
 * Get the query part of a url
 * @example: "https://www.mimfa.net:5046/Category/mimfa/service/web.php?p=3&l=10#serp" => "p=3&l=10"
 * @return string|null
 */
function getQuery(string|null $path = null): string|null
{
	return preg_Find("/((?<=\?)[^#]*(?=$|#))/", $path ?? getUrl());
}
/**
 * Get the fragment or anchor part of a url
 * @example: "https://www.mimfa.net:5046/Category/mimfa/service/web.php?p=3&l=10#serp" => "serp"
 * @return string|null
 */
function getFragment(string|null $path = null): string|null
{
	return preg_Find("/((?<=#)[^\/\?#\\\]*(?=$|[\/\?#\\\]))/", $path ?? getUrl());
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
	switch (strtoupper($method ?? $_SERVER['HTTP_X_CUSTOM_METHOD'] ?? $_SERVER['REQUEST_METHOD'] ?? "")) {
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
function getClientCode($key = null): string|null
{
	return md5($key . (getClientIp() ?? $_SERVER['HTTP_USER_AGENT'] ?? null));
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
	$i = 0;
	if ($full) {
		$i += cleanup(\_::$Address->TempDirectory);
		$i += cleanup(\_::$Router->TempDirectory);
	} else
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
		foreach (glob($directory . '*') as $file)
			if (is_file($file) && ++$i)
				unlink($file);
			elseif (is_dir($file)) {
				$i += cleanup($file);
				rmdir($file);
			}
	} else {
		$i += cleanup(\_::$Address->TempDirectory);
		$i += cleanup(\_::$Router->TempDirectory);
		$i += cleanup(\_::$Address->LogDirectory);
		$i += cleanup(\_::$Router->LogDirectory);
		clearSecrets();
		\_::$Back->Session->Clear();
	}
	return $i;
}

/**
 * This will store on the clients computer; do not use for sensitive information.
 * @param string $key A special key
 * @param string $value The target value to store, This value is stored on the clients computer; do not store sensitive information.
 * @param int $expires A miliseconds delay to expire
 * @param string $path 
 * @param bool $secure
 * @return bool
 */
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
function popMemo($key)
{
	$val = getMemo($key);
	$key = urlencode($key);
	unset($_COOKIE[$key]);
	setcookie($key, "", 0, "/", "", true, true);
	return $val;
}
function hasMemo($key)
{
	return !is_null(getMemo($key));
}
function clearMemos()
{
	foreach ($_COOKIE as $key => $val) {
		unset($_COOKIE[$key]);
		return setcookie($key, "", 0, "/", "", true, true);
	}
}

/**
 * Set somthing as a secure session variable available to the current script.
 * This will store on the server side; Able to use for sensitive information.
 * @param string $key A special key
 * @param mixed $value The target value to store.
 * @param mixed $expires A miliseconds delay to expire
 */
function setSecret($key, $value, $expires = 0, $path = "/", $secure = false)
{
	$isObject = !isStatic($value);
	if($isObject) $value = Convert::ToJson($value);
	return $_SESSION[$key] = [
		'value' => $secure ? encrypt($value) : $value,
		'expires' => ($expires > 0 ? (int) (microtime(true)) + ($expires / 1000) : 0),
		'path' => $path,
		'convert' => $isObject,
		'secure' => $secure
	];
}
/**
 * To get the secure session if its timeout is not expired
 * @param string $key The special key
 */
function getSecret($key)
{
	if (!isset($_SESSION[$key]))
		return null;
	$item = $_SESSION[$key];
	if ($item['expires'] > 0 && $item['expires'] < (int) microtime(true)) {
		unset($_SESSION[$key]);
		return null;
	}
	if (str_starts_with(\_::$Address->Request ?? "/", $item['path'])){
		$value = $item['secure'] ? decrypt($item['value']) : $item['value'];
		return ($item['convert']??null)?Convert::FromJson($value):$value;
	} else
		return null;
}
/**
 * To get and forget a session
 * @param string $key The special key
 */
function popSecret($key)
{
	$val = getSecret($key);
	unset($_SESSION[$key]);
	return $val;
}
/**
 * To check if the secure session is exists and not expired
 * @param string $key The special key
 * @return bool
 */
function hasSecret($key)
{
	if (!isset($_SESSION[$key]))
		return false;
	$item = $_SESSION[$key];
	if ($item['expires'] > 0 && $item['expires'] < (int) microtime(true)) {
		popSecret($key);
		return false;
	}
	if (str_starts_with(\_::$Address->Request ?? "/", $item['path']))
		return true;
	else
		return false;
}
/**
 * To forget all secure sessions
 */
function clearSecrets()
{
	$_SESSION = [];
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
function isAseq(string|null $directory): bool
{
	return !Local::FileExists($directory . "global/ConfigBase.php")
		&& Local::FileExists($directory . "global.php")
		&& Local::FileExists($directory . "Info.php")
		&& Local::FileExists($directory . "initialize.php");
}
function isBase(string|null $directory): bool
{
	return Local::FileExists($directory . "Config.php")
		&& Local::FileExists($directory . "global/ConfigBase.php")
		&& Local::FileExists($directory . "Info.php")
		&& Local::FileExists($directory . "global/InfoBase.php")
		&& Local::FileExists($directory . "Front.php")
		&& Local::FileExists($directory . "global/FrontBase.php")
		&& Local::FileExists($directory . "global.php")
		&& Local::FileExists($directory . "initialize.php");
}
function isInAseq(string|null $filePath): bool
{
	$filePath = preg_replace("/^\\\\/", \_::$Router->Directory, str_replace(\_::$Router->Directory, "", trim($filePath ?? getUrl())));
	if (isFormat($filePath, \_::$Extension))
		return file_exists($filePath);
	return is_dir($filePath) || file_exists($filePath . \_::$Extension);
}
function isInBase(string|null $filePath): bool
{
	$filePath = __DIR__ . DIRECTORY_SEPARATOR . preg_replace("/^\\\\/", "", str_replace(\_::$Address->Directory, "", trim($filePath ?? getUrl())));
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
function putToString(string $string, string $patch, int $index): string
{
	return substr($string, 0, $index) . $patch . substr($string, $index);
}
function popFromString(string $string, int $index, int $length = 1): string
{
	return substr($string, 0, $index) . substr($string, $index + $length);
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
		finalize(0); // End the child process
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
	$value = MiMFa\Library\Convert::ToString(
		is_array($value) ? join(" ", loop($value, fn($v) => __($v, $translating, $styling, $referring))) : $value
	);
	if ($translating && \_::$Back->AllowTranslate)
		$value = \_::$Back->Translate->Get($value);
	if ($styling)
		$value = MiMFa\Library\Style::DoStyle(
			$value,
			\_::$Info->KeyWords
		);
	if ($referring ?? $styling) {
		if (\_::$Config->AllowContentReferring)
			$value = Style::DoProcess(
				$value,
				process: function ($v, $k) {
					return Html::Link($v, \_::$Address->ContentRoot . strtolower($k));
				},
				keyWords: table("Content")->SelectPairs("`Name`", "`Title`", "ORDER BY LENGTH(`Title`) DESC"),
				both: true,
				caseSensitive: true
			);
		if (\_::$Config->AllowCategoryReferring)
			$value = Style::DoProcess(
				$value,
				process: function ($v, $k) {
					return Html::Link($v, \_::$Address->CategoryRoot . strtolower($k));
				},
				keyWords: table("Category")->SelectPairs("`Name`", "`Title`", "ORDER BY LENGTH(`Title`) DESC"),
				both: true,
				caseSensitive: true
			);
		if (\_::$Config->AllowTagReferring)
			$value = Style::DoProcess(
				$value,
				process: function ($v, $k) {
					return Html::Link($v, \_::$Address->TagRoot . strtolower($k));
				},
				keyWords: table("Tag")->SelectPairs("`Name`", "`Title`", "ORDER BY LENGTH(`Title`) DESC"),
				both: true,
				caseSensitive: true
			);
		if (\_::$Config->AllowUserReferring)
			$value = Style::DoProcess(
				$value,
				process: function ($v, $k) {
					return Html::Link($v, \_::$Address->UserRoot . strtolower($k));
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

function startsWith(string|null $haystack, ...$needles)
{
	if ($haystack)
		foreach ($needles as $needle)
			if (!is_null($needle) && substr_compare($haystack, $needle, 0, strlen($needle)) === 0)
				return $needle ?: true;
	return false;
}
function endsWith(string|null $haystack, ...$needles)
{
	if ($haystack)
		foreach ($needles as $needle)
			if (!is_null($needle) && substr_compare($haystack, $needle, -strlen($needle)) === 0)
				return $needle ?: true;
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
// /**
//  * @test
//  */
// function test_server()
// {
// 	foreach ($_SERVER as $k => $v) echo "<br>" . "$k: " . $v;
// }
// /**
//  * @test
//  */
// function test_address($directory = null, string $name = "Configuration")
// {
// 	echo addressing($directory ?? \_::$Address->Directory, $name);
// 	echo "<br>ASEQ: " . \_::$Router->Name;
// 	echo "<br>ASEQ->Path: " . \_::$Address->Path;
// 	echo "<br>ASEQ->Dir: " . \_::$Router->Directory;
// 	echo "<br>OTHER ASEQ: <br>";
// 	var_dump(\_::$Router);
// 	echo "<br>BASE: " . \_::$Address->Name;
// 	echo "<br>BASE->Path: " . \_::$Address->Path;
// 	echo "<br>BASE->Dir: " . \_::$Address->Directory;
// 	echo "<br>OTHER BASE: <br>";
// 	var_dump(\_::$Address);
// 	echo "<br><br>ADDRESSES: <br>";
// 	var_dump(\_::$Router);
// }
// function test_url()
// {
// 	echo "<br>URL: " . \_::$Address->Url;
// 	echo "<br>HOST: " . \_::$Address->Host;
// 	echo "<br>SITE: " . \_::$Address->Site;
// 	echo "<br>PATH: " . \_::$Address->Path;
// 	echo "<br>REQUEST: " . \_::$Address->Request;
// 	echo "<br>DIRECTION: " . \_::$Address->Direction;
// 	echo "<br>QUERY: " . \_::$Address->Query;
// 	echo "<br>FRAGMENT: " . \_::$Address->Fragment;
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