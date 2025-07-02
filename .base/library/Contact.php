<?php
namespace MiMFa\Library;
/**
 * A simple library to send email and comunications
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#contact See the Library Documentation
 */
class Contact
{
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

			$tos = is_array($to)?$to:preg_split("/[,; ><\[\](){}&#\|!~\'\"`\*=^%\$\s]+/", Convert::ToString($to));
			$i = 0;
			foreach ($tos as $t)
				if (mail($t, $subject, $message.PHP_EOL.Convert::ToString($attaches), $header))
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
			$tos = is_array($to)?$to:preg_split("/[,; ><\[\](){}&#\|!~\'\"`\*=^%\$\s]+/", Convert::ToString($to));
			$i = 0;
			foreach ($tos as $t)
				if (mail($t, $subject, $message.Html::$NewLine.Convert::ToString($attaches), $header))
					$i++;
			return $i;
		} catch (\Exception $ex) {
			$exception = $ex;
			return false;
		}
	}
}