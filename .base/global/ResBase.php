<?php

use MiMFa\Library\Convert;

/**
 * The Global Static Variables and Functions
 * You need to create and send responses
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/ResBase See the Documentation
 */
abstract class ResBase
{
	/**
	 * To change the header in the client side
	 * @param mixed $key The header key
	 * @param mixed $value The header value
	 * @return bool True if header is set, false otherwise
	 */
	public static function Header($key, $value)
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
	public static function Type($value = null)
	{
		if (!headers_sent()) {
			header("Content-Type: ".($value??"text/html"));
			return true;
		}
		return false;
	}
	/**
	 * To change the status of the results for the client side
	 * @param mixed $status The header status
	 * @return bool True if header is set, false otherwise
	 */
	public static function Status($status = null)
	{
		if (isValid($status) && !headers_sent()) {
			header("HTTP/1.1 " . abs($status));
			return true;
		}
		return false;
	}

	/**
	 * Echo output on the client side
	 * @param mixed $output The data that is ready to print
	 * @return mixed Printed data
	 */
	public static function Render($output = null)
	{
		echo $output = \MiMFa\Library\Convert::ToString($output);
		return $output;
	}
	/**
	 * Print only this output on the client side then reload the page
	 * @param mixed $output The data that is ready to print
	 * @param mixed $status The header status
	 */
	public static function Flip($output = null, $status = null, $url = null)
	{
		ob_clean();
		self::Status($status);
		exit(\MiMFa\Library\Convert::ToString($output) . "<script>window.location.assign(" . (isValid($url) ? "`" . \MiMFa\Library\Local::GetUrl($url) . "`" : "location.href") . ");</script>");
	}
	/**
	 * Print only this output on the client side
	 * @param mixed $output The data that is ready to print
	 * @param mixed $status The header status
	 */
	public static function End($output = null, $status = null)
	{
		self::Status($status);
		if ($output)
			exit(\MiMFa\Library\Convert::ToString($output));
		else
			exit;
	}
	/**
	 * Print only this output on the client side, Clear before then end
	 * @param mixed $output The data that is ready to print
	 * @param mixed $status The header status
	 */
	public static function Set($output = null, $status = null)
	{
		if (ob_get_level())
			ob_end_clean(); // Clean any remaining output buffers
		self::End($output, $status);
	}
	/**
	 * Print only this JSON on the client side, Clear before then end
	 * @param mixed $output The data that is ready to print
	 * @param mixed $status The header status
	 */
	public static function SetJson($output = null, $status = null)
	{
		if (ob_get_level())
			ob_end_clean(); // Clean any remaining output buffers
		self::Type("application/json");
		self::End(isJson($output)?$output:Convert::ToJson($output), $status);
	}
	/**
	 * Print only this XML on the client side, Clear before then end
	 * @param mixed $output The data that is ready to print
	 * @param mixed $status The header status
	 */
	public static function SetXml($output = null, $status = null)
	{
		if (ob_get_level())
			ob_end_clean(); // Clean any remaining output buffers
		self::Type("application/xml");
		self::End(is_string($output)?$output:Convert::ToXmlString($output), $status);
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
	public static function SetFile($path = null, $status = null, $type = null, bool $attachment = false, ?string $name = null)
	{
		// Clear output buffer if active
		if (ob_get_level()) ob_clean();
		
		$path = \MiMFa\Library\Local::GetFile($path);
		if ($path)
			self::Status($status);
		else {
			self::Status(404);
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
		$name = preg_replace('/[^\w\-.]/', '_', $name??end($n));
		$disposition = $attachment ? 'attachment' : 'inline';
		header("Content-Disposition: $disposition; filename=\"" . ($name ?? basename($path)) . '"');
		header('Content-Length: ' . filesize($path));
		//header("Etag: " . md5_file($path)); // Simple ETag (entity tag) response header is an identifier for a specific version of a resource. It lets caches be more efficient and save
		//Read and output the file
		readfile($path);
		exit;
	}
	/**
	 * Replace the output with all the document in the client side
	 * @param mixed $output The data that is ready to print
	 */
	public static function Reset($output = null)
	{
		\Res::Render(\MiMFa\Library\Html::Script(
			\MiMFa\Library\Internal::MakeScript(
				$output,
				null,
				"(data,err)=>{document.open();document.write(data??err);document.close();}"
			)
		));
	}

	/**
	 * Send values to the client side
	 * @param string $method The Method to send data
	 * @param mixed $path The Url to send data
	 * @param mixed $data Desired data
	 * @return bool|string Its sent or received response
	 */
	public static function Send($method = null, $path = null, ...$data)
	{
		if (isEmpty($path))
			$path = getPath();
		if (isEmpty($method))
			$method = "POST";
		else $method = strtoupper($method);

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
	public static function SendGet($path = null, ...$data)
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
	public static function SendPost($path = null, ...$data)
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
	public static function SendPut($path = null, ...$data)
	{
		return self::Send("PUT",$path,$data);
	}
	/**
	 * Send patched values to the client side
	 * @param mixed $path The Url to send PATCH data to that
	 * @param mixed $data Desired data to PATCH
	 * @return bool|string Its sent or received response
	 */
	public static function SendPatch($path = null, ...$data)
	{
		return self::Send("PATCH",$path,$data);
	}
	/**
	 * Send file values to the client side
	 * @param mixed $path The Url to send FILE data to that
	 * @param mixed $data Desired data to FILE
	 * @return bool|string Its sent or received response
	 */
	public static function SendFile($path = null, ...$data)
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
	public static function SendDelete($path = null, ...$data)
	{
		return self::Send("DELETE",$path,$data);
	}
	/**
	 * Send stream values to the client side
	 * @param mixed $path The Url to send STREAM data to that
	 * @param mixed $data Desired data to STREAM
	 * @return bool|string Its sent or received response
	 */
	public static function SendStream($path = null, ...$data)
	{
		return self::Send("STREAM",$path,$data);
	}
	/**
	 * Send internal values to the client side
	 * @param mixed $path The Url to send INTERNAL data to that
	 * @param mixed $data Desired data to INTERNAL
	 * @return bool|string Its sent or received response
	 */
	public static function SendInternal($path = null, ...$data)
	{
		return self::Send("INTERNAL",$path,$data);
	}
	/**
	 * Send external values to the client side
	 * @param mixed $path The Url to send EXTERNAL data to that
	 * @param mixed $data Desired data to EXTERNAL
	 * @return bool|string Its sent or received response
	 */
	public static function SendExternal($path = null, ...$data)
	{
		return self::Send("EXTERNAL",$path,$data);
	}

	public static function Locate($url = null)
	{
		self::End(\MiMFa\Library\Html::Script("window.history.replaceState(null, null, " . (empty($url) ? "location.href" : "`" . getFullUrl($url) . "`") . ");"));
	}
	public static function Relocate($url = null)
	{
		self::End(\MiMFa\Library\Html::Script("window.history.pushState(null, null, " . (empty($url) ? "location.href" : "`" . getFullUrl($url) . "`") . ");"));
	}
	public static function Go($url, $target = "_self")
	{
		self::End(\MiMFa\Library\Html::Script("window.open(" . (isValid($url) ? "'" . getFullUrl($url) . "'" : "location.href") . ", '$target');"));
	}
	public static function Open($url = null, $target = "_blank")
	{
		self::End("<html><head><script>window.open(" . (isValid($url) ? "'" . getFullUrl($url) . "'" : "location.href") . ", '$target');</script></head></html>");
	}
	public static function Load($url = null)
	{
		self::End(\MiMFa\Library\Html::Script("window.history.replaceState(null, null, " . (empty($url) ? "location.href" : "`" . getFullUrl($url) . "`") . ");window.location.reload();"));
	}
	public static function Reload()
	{
		self::End(\MiMFa\Library\Html::Script("window.location.reload();"));
	}
	public static function Share($urlOrText = null, $path = null)
	{
		self::Render(\MiMFa\Library\Html::Script("window.open('sms://$path?body='+" . (isValid($urlOrText) ? "'" . __($urlOrText, styling: false) . "'" : "location.href") . ", '_blank');"));
	}

	/**
	 * Interact with all specific parts of the client side
	 * @param mixed $script The front JS codes
	 * @param mixed $callback The call back handler
	 * @example: Interact('$("body").html', function(selectedHtml)=>{ //do somework })
	 */
	public static function Interact($script = null, $callback = null)
	{
        $callbackScript = "(data,err)=>document.querySelector('body').append(...((html)=>{el=document.createElement('qb');el.innerHTML=html;return el.childNodes;})(data??err))";
        $progressScript = "null";
		$timeout = 60000;
		$start = \MiMFa\Library\Internal::MakeStartScript(true);
		$end = \MiMFa\Library\Internal::MakeStartScript(true);
		$id = "S_".getID(true);
		if(isStatic($callback)) \Res::Render(\MiMFa\Library\Html::Script("$start(".$callbackScript.")(".
				\MiMFa\Library\Script::Convert($callback) . ",$script);document.getElementById('$id').remove();$end",null, ["id"=>$id]));
		else \Res::Render(\MiMFa\Library\Html::Script(
				$callback ? "$start".
					'sendInternal(null,{"' . \MiMFa\Library\Internal::Set($callback) . '":JSON.stringify('. $script . ")},'body',$callbackScript,$callbackScript,null,$progressScript,$timeout);document.getElementById('$id').remove();$end"
				: $script
		,null, ["id"=>$id]));
	}
	/**
	 * Have a dialog with the client side
	 * @param mixed $script The front JS codes
	 * @param mixed $callback The call back handler
	 * @example: Dialog('$("body").html', function(selectedHtml)=>{ //do somework })
	 * @return string|null The result of the client side
	 */
	public static function Dialog($script = null, $callback = null)
	{
		$id = "Dialog_".getId(true);
		self::Interact(
			"setMemo('$id', $script, 60000)",
			$callback
		);
		return grabMemo($id);
	}
	/**
	 * Interact with all specific parts of the client side one by one
	 * @param mixed $script The front JS codes
	 * @param mixed $callback The call back handler
	 * @example: Get("body", function(selectedHtml)=>{ //do somework })
	 */
	public static function Iterate($script = null, $callback = null)
	{
        $callbackScript = "(data,err)=>{el=document.createElement('qb');el.innerHTML=data??err;item.before(...el.childNodes);item.remove();}";
        $progressScript = "null";
		$timeout = 60000;
		$start = \MiMFa\Library\Internal::MakeStartScript(true);
		$end = \MiMFa\Library\Internal::MakeStartScript(true);
		$id = "S_".getID(true);
		if(isStatic($callback)) \Res::Render(\MiMFa\Library\Html::Script("$start for(item of $script)(".$callbackScript.")(".
				\MiMFa\Library\Script::Convert($callback) . ",item);document.getElementById('$id').remove();$end", null, ["id"=>$id]));
		else \Res::Render(\MiMFa\Library\Html::Script(
				$callback ? "$start".
					"for(item of $script)sendInternal(null,{\"" . \MiMFa\Library\Internal::Set($callback) . '":item.outerHTML},'.
						"getQuery(item),$callbackScript,$callbackScript,null,$progressScript,$timeout);document.getElementById('$id').remove();$end"
				: $script
		,null, ["id"=>$id]));
	}

	public static function Alert($message = null, $callback = null)
	{
		self::Interact(
			\MiMFa\Library\Script::Alert($message)."??true",
			$callback
		);
	}
	public static function Confirm($message = null, $callback = null)
	{
		return self::Dialog(
			\MiMFa\Library\Script::Confirm($message),
			$callback
		);
	}
	public static function Prompt($message = null, $callback = null, $default = null)
	{
		return self::Dialog(
			\MiMFa\Library\Script::Prompt($message, $default),
			$callback
		);
	}
	/**
	 * Execute console.log script
	 * @param mixed $message
	 * @return void
	 */
	public static function Log($message = null)
	{
		self::Script(
			\MiMFa\Library\Script::Log($message)
		);
	}
	/**
	 * Render a message result output to the client side
	 * @param mixed $output The data that is ready to print
	 * @return mixed Printed data
	 */
	public static function Message($output = null)
	{
		echo $output = \MiMFa\Library\Html::Result($output);
		return $output;
	}
	/**
	 * Render a success result output to the client side
	 * @param mixed $output The data that is ready to print
	 * @return mixed Printed data
	 */
	public static function Success($output = null)
	{
		echo $output = \MiMFa\Library\Html::Success($output);
		return $output;
	}
	/**
	 * Render a warning result output to the client side
	 * @param mixed $output The data that is ready to print
	 * @return mixed Printed data
	 */
	public static function Warning($output = null)
	{
		echo $output = \MiMFa\Library\Html::Warning($output);
		return $output;
	}
	/**
	 * Render an error result output to the client side
	 * @param mixed $output The data that is ready to print
	 * @return mixed Printed data
	 */
	public static function Error($output = null)
	{
		self::Status(400);
        if (is_a($output, "Exception") || is_subclass_of($output, "Exception"))
            return \MiMFa\Library\Html::Script(\MiMFa\Library\Script::Error($output->getMessage()));
		echo $output = \MiMFa\Library\Html::Error($output);
		return $output;
	}

	/**
	 * Render Scripts in the client side
	 * @param mixed $output The data that is ready to print
	 */
	public static function Script($content, $source = null, ...$attributes)
	{
		\Res::Render(\MiMFa\Library\Html::Script(
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
	public static function Style($content, $source = null, ...$attributes)
	{
		\Res::Render(\MiMFa\Library\Html::Script(
			\MiMFa\Library\Internal::MakeScript(
				\MiMFa\Library\Html::Style($content, $source, ...$attributes),
				null,
				"(data,err)=>document.querySelector('head').append(...((html)=>{el=document.createElement('qb');el.innerHTML=html;return el.childNodes;})(data??err))"
			)
		));
	}
}