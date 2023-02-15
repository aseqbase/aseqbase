<?php namespace MiMFa\Library;
class Contact{
	public static function SendEmail($from,$to,$subject,$message,$reply=null,$cc=null)
	{
		try{
			$header = "From: $from\r\n";
			if(!is_null($reply)) $header .="Reply-To: $reply\r\n";
			if(!is_null($cc))	$header .="CC: $cc\r\n";
			
			return mail($to,
			$subject,
			$message,
			$header);
		} catch (Exception $ex){
			return false;
		}
		return false;
	}
	
	public static function SendHTMLEmail($from,$to,$subject,$message,$reply=null,$cc=null)
	{
		try{
			$header = "From: $from\r\n";
			if(!is_null($reply)) $header .="Reply-To: $reply\r\n";
			if(!is_null($cc))	$header .="CC: $cc\r\n";
			$header .="MIME-Version: 1.0\r\n"
				. "Content-Type: text/html; charset=UTF-8\r\n";
			
			return mail($to,
			$subject,
			$message,
			$header);
		} catch (Exception $ex){
			return false;
		}
		return false;
	}
}
?>