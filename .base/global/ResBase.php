<?php
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
	 * Echo output on the client side
	 * @param mixed $output The data that is ready to print
	 * @param mixed $status The header status
	 * @return mixed Printed data
	 */
	public static function Set($output = null, $status = null)
	{
		ob_clean();
		self::Status($status);
		echo $output = \MiMFa\Library\Convert::ToString($output);
		flush();
		ob_start();
		return $output;
	}
	/**
	 * Read a file from the client side
	 * @param mixed $path The data that is ready to print
	 * @param mixed $status The header status
	 */
	public static function SetFile($path = null, $status = null)
	{
		header('Content-Type: application/pdf');
		header('Content-Disposition: inline; filename="' . basename($path) . '"');
		header('Content-Length: ' . filesize($path));
		self::Status($status);
		// Read and output the file
		return readfile($path);
	}
	/**
	 * Print only this output on the client side
	 * @param mixed $output The data that is ready to print
	 * @param mixed $status The header status
	 */
	public static function Send($output = null, $status = null)
	{
		ob_clean();
		if (self::Status($status))
			flush();
		exit(\MiMFa\Library\Convert::ToString($output));
	}
	/**
	 * Send a file to the client side
	 * @param mixed $path The data that is ready to print
	 * @param mixed $status The header status
	 */
	public static function SendFile($path = null, $status = null)
	{
		if($res = self::SetFile($path, $status)) exit;
		else $res;
	}
	/**
	 * Print only this output on the client side then reload the page
	 * @param mixed $output The data that is ready to print
	 * @param mixed $status The header status
	 */
	public static function Flip($output = null, $status = null, $url = null)
	{
		ob_clean();
		if (self::Status($status))
			flush();
		exit(\MiMFa\Library\Convert::ToString($output) . "<script>window.location.assign(" . (isValid($url) ? "`" . \MiMFa\Library\Local::GetUrl($url) . "`" : "location.href") . ");</script>");
	}
	/**
	 * Print only this output on the client side
	 * @param mixed $output The data that is ready to print
	 * @param mixed $status The header status
	 */
	public static function End($output = null, $status = null)
	{
		if (self::Status($status))
			flush();
		if ($output)
			exit(\MiMFa\Library\Convert::ToString($output));
		else
			exit();
	}

	/**
	 * Send values to the client side
	 * @param mixed $path The Url to send GET data from that
	 * @param mixed $data Additional data to send as query parameters
	 * @return bool|string Is sent or received response
	 */
	public static function Get($path = null, ...$data)
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
	 * @return bool|string Is sent or received response
	 */
	public static function Post($path = null, ...$data)
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
	 * @return bool|string Is sent or received response
	 */
	public static function Put($path = null, ...$data)
	{
		if (isEmpty($path))
			$path = getPath();
		if (is_string($path) && isAbsoluteUrl($path)) {
			$ch = curl_init($path);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Data to be posted
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
			$response = curl_exec($ch);
			curl_close($ch);
			return $response;
		}
		return false;
	}
	/**
	 * Send patched values to the client side
	 * @param mixed $path The Url to send PATCH data to that
	 * @param mixed $data Desired data to PATCH
	 * @return bool|string Is sent or received response
	 */
	public static function Patch($path = null, ...$data)
	{
		if (isEmpty($path))
			$path = getPath();
		if (is_string($path) && isAbsoluteUrl($path)) {
			$ch = curl_init($path);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Data to be posted
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
			$response = curl_exec($ch);
			curl_close($ch);
			return $response;
		}
		return false;
	}
	/**
	 * Send file values to the client side
	 * @param mixed $path The Url to send FILE data to that
	 * @param mixed $data Desired data to FILE
	 * @return bool|string Is sent or received response
	 */
	public static function File($path = null, ...$data)
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
	 * @return bool|string Is sent or received response
	 */
	public static function Delete($path = null, ...$data)
	{
		if (isEmpty($path))
			$path = getPath();
		if (is_string($path) && isAbsoluteUrl($path)) {
			$ch = curl_init($path);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Data to be posted
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
			$response = curl_exec($ch);
			curl_close($ch);
			return $response;
		}
		return false;
	}
	/**
	 * Send internal values to the client side
	 * @param mixed $path The Url to send INTERNAL data to that
	 * @param mixed $data Desired data to INTERNAL
	 * @return bool|string Is sent or received response
	 */
	public static function Internal($path = null, ...$data)
	{
		if (isEmpty($path))
			$path = getPath();
		if (is_string($path) && isAbsoluteUrl($path)) {
			$ch = curl_init($path);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "INTERNAL");
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Data to be posted
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
			$response = curl_exec($ch);
			curl_close($ch);
			return $response;
		}
		return false;
	}

	public static function Go($url)
	{
		self::End("<html><head><script>window.location.assign(" . (isValid($url) ? "'" . \MiMFa\Library\Local::GetUrl($url) . "'" : "location.href") . ");</script></head></html>");
	}
	public static function Reload()
	{
		self::End(\MiMFa\Library\Html::Script("window.location.assign(location.href);"));
	}
	public static function Load($url = null)
	{
		self::End(\MiMFa\Library\Html::Script("window.location.assign(" . (empty($url) ? "location.href" : "`" . forceUrl($url) . "`") . ");"));
	}
	public static function Open($url = null, $target = "_blank")
	{
		self::End(\MiMFa\Library\Html::Script("window.open(" . (isValid($url) ? "'" . forceUrl($url) . "'" : "location.href") . ", '$target');"));
	}
	public static function Share($urlOrText = null, $path = null)
	{
		self::Render(\MiMFa\Library\Html::Script("window.open('sms://$path?body='+" . (isValid($urlOrText) ? "'" . __($urlOrText, styling: false) . "'" : "location.href") . ", '_blank');"));
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
		echo $output = \MiMFa\Library\Html::Error($output);
		return $output;
	}
	/**
	 * Render Scripts in the client side
	 * @param mixed $output The data that is ready to print
	 * @return mixed Printed data
	 */
	public static function Script($output = null)
	{
		echo $output = \MiMFa\Library\Html::Script($output);
		return $output;
	}
	/**
	 * Render Styles in the client side
	 * @param mixed $output The data that is ready to print
	 * @return mixed Printed data
	 */
	public static function Style($output = null)
	{
		echo $output = \MiMFa\Library\Html::Style($output);
		return $output;
	}

	/**
	 * Enclosure codes
	 * @param mixed $codes The data that is ready to print
	 * @return mixed Printed data
	 */
	public static function EnclosureScript($codes = null)
	{
		return "<script>$codes</script>";
	}
	/**
	 * Enclosure dialog codes
	 * @param mixed $codes The data that is ready to print
	 * @return mixed Printed data
	 */
	public static function EnclosureDialogScript($codes = null, $name = null)
	{
		return self::EnclosureScript("sendInternal(null,{" . ($name ?? "response") . ":$codes});");
	}
	public static function Alert($message = null)
	{
		self::Render(
			self::EnclosureScript(
				\MiMFa\Library\Script::Alert($message)
			)
		);
	}
	public static function Confirm($message = null)
	{
		$res = \Req::Confirm(null);
		if ($res === null)
			self::End(self::EnclosureDialogScript(
				\MiMFa\Library\Script::Confirm($message),
				"confirmed"
			));
		else
			return $res;
		return null;
	}
	public static function Prompt($message = null, $default = null)
	{
		$res = \Req::Confirm(null);
		if ($res === null)
			self::End(self::EnclosureDialogScript(
				\MiMFa\Library\Script::Prompt($message, $default),
				"prompted"
			));
		else
			return $res;
		return null;
	}
}
?>