<?php
namespace MiMFa\Library;

use ArrayObject;
/**
 * A simple library to send email or messages (with an Api) and comunications
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#contact See the Library Documentation
 */
class Contact extends ArrayObject
{
	public $Api = null;
	public $Method = "POST";
	public $Secure = false;
	public $Timeout = 60;
	public array|null $Header = ["Content-type: application/x-www-form-urlencoded"];

	public $Identifier = null;
	public $UserName = null;
	public $Password = null;

	public $UserNameKey = "username";
	public $PasswordKey = "password";
	public $FromKey = "from";
	public $ToKey = "to";
	public $TextKey = "text";
	public $CountKey = "count";

	/**
	 * Summary of __construct
	 * @param mixed $identifier
	 * @param mixed $api
	 * @param mixed $userName
	 * @param mixed $password
	 */
	public function __construct($identifier = null, $api = null, $userName = null, $password = null)
	{
		$this->Identifier = $identifier;
		$this->Api = $api;
		$this->UserName = $userName;
		$this->Password = $password;
	}

	/**
	 * Send aaa message
	 * @param string|array $to One or multiple recipient(s)
	 * @param mixed $message
	 */
	public function Send($to, $message, $path = null, array $attributes = [])
	{
		if (is_array($to)) {
			$res = [];
			foreach ($to as $key => $value)
				$res[] = $this->Send($value, $message, $attributes);
			return $res;
		}

		return send(
			$this->Method,
			$this->MakeApi($path),
			array_merge($this->ToArray(), [
				$this->UserNameKey => $this->UserName,
				$this->PasswordKey => $this->Password,
				$this->ToKey => $to,
				$this->FromKey => $this->Identifier,
				$this->TextKey => $message
			], $attributes),
			headers: $this->Header,
			secure: $this->Secure,
			timeout: $this->Timeout
		);
	}

	/**
	 * Receive message
	 * @param string|array $from One or multiple sender(s)
	 */
	public function Receive($from, $path = null, array $attributes = [])
	{
		if (is_array($from)) {
			$res = [];
			foreach ($from as $key => $value)
				$res[] = $this->Receive($value, $attributes);
			return $res;
		}

		return send(
			$this->Method,
			$this->MakeApi($path),
			array_merge($this->ToArray(), [
				$this->UserNameKey => $this->UserName,
				$this->PasswordKey => $this->Password,
				$this->FromKey => $from,
				$this->ToKey => $this->Identifier
			], $attributes),
			headers: $this->Header,
			secure: $this->Secure,
			timeout: $this->Timeout
		);
	}

	/**
	 * Get all messages
	 */
	public function GetMessages($count, $path = null, array $attributes = [])
	{
		return send(
			$this->Method,
			$this->MakeApi($path),
			array_merge($this->ToArray(), [
				$this->UserNameKey => $this->UserName,
				$this->PasswordKey => $this->Password,
				$this->CountKey => $count
			], $attributes),
			headers: $this->Header,
			secure: $this->Secure,
			timeout: $this->Timeout
		);
	}

	public function MakeApi($path)
	{
		if (isRelativeUrl($path))
			return rtrim($this->Api, "/\\") . "/" . ltrim($path, "/\\");
		return $path ?? $this->Api;
	}

	public function ToArray()
	{
		$arr = [];
		foreach ($this as $key => $value)
			$arr[$key] = $value;
		return $arr;
	}

	/**
	 * Send email(s)
	 * @param string $from
	 * @param string|array $to One or multiple email recipient(s)
	 * @param mixed $subject
	 * @param mixed $message
	 * @param mixed $reply
	 * @param mixed $cc
	 * @param mixed $attaches
	 * @param mixed $exception
	 */
	public static function SendEmail($from, $to, $subject, $message, $reply = null, $cc = null, $attaches = null, &$exception = null)
	{
		try {
			$header = "From: $from\r\n";
			if (!is_null($reply))
				$header .= "Reply-To: $reply\r\n";
			if (!is_null($cc))
				$header .= "CC: $cc\r\n";

			$tos = is_array($to) ? $to : preg_split("/[,; ><\[\](){}&#\|!~\'\"`\*=^%\$\s]+/", Convert::ToString($to));
			$i = 0;
			foreach ($tos as $t)
				if (mail($t, $subject, $message . PHP_EOL . Convert::ToString($attaches), $header))
					$i++;
			return $i;
		} catch (\Exception $ex) {
			$exception = $ex;
			return false;
		}
	}
	/**
	 * Send html type email(s)
	 * @param string $from
	 * @param string|array $to One or multiple email recipient(s)
	 * @param mixed $subject
	 * @param mixed $message
	 * @param mixed $reply
	 * @param mixed $cc
	 * @param mixed $attaches
	 * @param mixed $exception
	 */
	public static function SendHtmlEmail($from, $to, $subject, $message, $reply = null, $cc = null, $attaches = null, &$exception = null)
	{
		try {
			$header = "From: $from\r\n";
			if (!is_null($reply))
				$header .= "Reply-To: $reply\r\n";
			if (!is_null($cc))
				$header .= "CC: $cc\r\n";
			$header .= "MIME-Version: 1.0\r\n"
				. "Content-Type: text/html; charset=UTF-8\r\n";

			$message = Html::Convert($message);
			$tos = is_array($to) ? $to : preg_split("/[,; ><\[\](){}&#\|!~\'\"`\*=^%\$\s]+/", Convert::ToString($to));
			$i = 0;
			foreach ($tos as $t)
				if (mail($t, $subject, $message . Html::$Break . Convert::ToString($attaches), $header))
					$i++;
			return $i;
		} catch (\Exception $ex) {
			$exception = $ex;
			return false;
		}
	}
}