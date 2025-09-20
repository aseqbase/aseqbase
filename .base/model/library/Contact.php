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
	public $Protocol = "http";
	public $Method = "POST";
	public $Header = "Content-type: application/x-www-form-urlencoded\r\n";

	public $Api = null;
	public $Identifier = null;
	public $UserName = null;
	public $Password = null;

	public $UserNameKey = "username";
	public $PasswordKey = "password";
	public $SenderKey = "from";
	public $ReceiverKey = "to";
	public $MessageKey = "text";
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
	public function Send($to, $message, $api = null, array $attributes = [])
	{
		if(is_array($to)) {
			$res= [];
			foreach ($to as $key => $value)
				$res[] = $this->Send($value, $message, $attributes);
			return $res;
		}
		$data = [
			$this->UserNameKey => $this->UserName,
			$this->PasswordKey => $this->Password,
			$this->ReceiverKey => $to,
			$this->SenderKey => $this->Identifier,
			$this->MessageKey => $message
		];
		array_merge($this->ToArray(), $data, $attributes);

		return file_get_contents(
			$this->MakeApi($api),
			false,
			stream_context_create([
				$this->Protocol => [
					'header' => $this->Header,
					'method' => $this->Method,
					'content' => http_build_query($data)
				]
			])
		);
	}

	/**
	 * Receive message
	 * @param string|array $from One or multiple sender(s)
	 */
	public function Receive($from, $api = null, array $attributes = [])
	{
		if(is_array($from)) {
			$res= [];
			foreach ($from as $key => $value)
				$res[] = $this->Receive($value, $attributes);
			return $res;
		}
		$data = [
			$this->UserNameKey => $this->UserName,
			$this->PasswordKey => $this->Password,
			$this->SenderKey => $from,
			$this->ReceiverKey => $this->Identifier
		];
		array_merge($this->ToArray(), $data, $attributes);

		return file_get_contents(
			$this->MakeApi($api),
			false,
			stream_context_create([
				$this->Protocol => [
					'header' => $this->Header,
					'method' => $this->Method,
					'content' => http_build_query($data)
				]
			])
		);
	}
	
	/**
	 * Get all messages
	 */
	public function GetMessages($count, $api = null, array $attributes = [])
	{
		$data = [
			$this->UserNameKey => $this->UserName,
			$this->PasswordKey => $this->Password,
			$this->CountKey => $count
		];
		array_merge($this->ToArray(), $data, $attributes);

		return file_get_contents(
			$this->MakeApi($api),
			false,
			stream_context_create([
				$this->Protocol => [
					'header' => $this->Header,
					'method' => $this->Method,
					'content' => http_build_query($data)
				]
			])
		);
	}

	public function MakeApi($api)
	{
		if(isRelativeUrl($api))
			return rtrim($this->Api, "/\\")."/".ltrim($api, "/\\");
		return $api??$this->Api;
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