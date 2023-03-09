<?php namespace MiMFa\Library;
require_once "Translate.php";

class Transport
{

	public static $Items = array();

	public static function SetINPUTedItems(){
		$json = file_get_contents('php://input');
		self::$Items = json_decode($json);
	}
	public static function SetGETedItems(){
		self::$Items = json_decode(json_encode($_GET));
	}
	public static function SetPOSTedItems(){
		self::$Items = json_decode(json_encode($_POST));
	}
	public static function SetItems($items){
		self::$Items = $items;
	}

	public static function GetItem($name,$dflt = null){
		return ((self::IsItem($name))? self::Normalize(self::$Items->$name) : $dflt);
	}
	public static function IsItem($name){
		return isset(self::$Items->$name);
	}
 
	public static function GetPOSTedItem($name,$dflt = null){
		return (isset($_POST[$name])? self::Normalize($_POST[$name]) : $dflt);
	}
	public static function GetGETedItem($name,$dflt = null){
		return (isset($_GET[$name])? self::Normalize($_GET[$name]) : $dflt);
	}

	public static function Normalize($value){
		return str_replace('"',"",str_replace("'","",$value));
	}

	public static function Success($message,$translate = true){
		if($translate) $message = Translate::Get($message);
		self::PushMessage("Success",$message);
		return "<DIV CLASS='success'>$message</DIV>";
	}

	public static function Error($message,$translate = true){
		if($translate) $message = Translate::Get($message);
		self::PushMessage("Error",$message);
		return "<DIV CLASS='error'>$message</DIV>";
	}
	
	public static function Message($message,$translate = true){
		if($translate) $message = Translate::Get($message);
		self::PushMessage("Message",$message);
		return "<DIV CLASS='message'>$message</DIV>";
	}


//Send Part

	public static function SendData($data,$functionName="Result",$message = ""){
		self::PushMessage("Data",$message);
		$result= "{\"type\":\"0\",\"message\":\"$message\",\"function\":\"$functionName\",\"data\":".json_encode($data)."}";
		self::Send($result);
	}

	public static function SendText($message,$num = 1,$translate = true){
		if($translate) $message = Translate::Get($message);
		self::PushMessage("Text",$message);
		$result= "{\"type\":\"${num}\",\"message\":\"".$message."\"}";
		self::Send($result);
	}

	public static function SendMessage($message,$num = 1,$translate = true){
		self::$ResultNum = $num;
		if($translate) $message = Translate::Get($message);
		$result= "{\"type\":\"${num}\",\"message\":\"".self::Message($message,$translate)."\"}";
		self::Send($result);
	}

	public static function SendSuccess($message,$num = 1,$translate = true){
		self::$ResultNum = $num;
		$result= "{\"type\":\"${num}\",\"message\":\"".self::Success($message,$translate)."\"}";
		self::Send($result);
	}

	public static function SendError($message,$num = -1,$translate = true){
		self::$ResultNum = $num;
		$result= "{\"type\":\"${num}\",\"message\":\"".self::Error($message,$translate)."\"}";
		self::Send($result);
	}

	public static function SendException($ex,$num = -1,$translate = true){
		self::SendError($ex->getMessage(),$num);
	}

	public static function SendSuccesses($num = 1,$translate = true,$splitor="<br>"){
		self::$ResultNum = $num;
		$message = self::GetMessages("Success",$translate,$splitor);
		self::Send("{\"type\":\"${num}\",\"message\":\"".self::Success($message,false)."\"}");
	}

	public static function SendErrors($num = -1,$translate = true,$splitor="<br>"){
		self::$ResultNum = $num;
		$message = self::GetMessages("Error",$translate,$splitor);
		self::Send("{\"type\":\"${num}\",\"message\":\"".self::Error($message,false)."\"}");
	}

	public static function SendMessages($num = 1,$translate = true,$splitor="<br>"){
		self::$ResultNum = $num;
		$message = self::GetMessages("Message",$translate,$splitor);
		self::Send("{\"type\":\"${num}\",\"message\":\"".self::Message($message,false)."\"}");
	}

	public static function SendAll($num = 1,$translate = true,$splitor="<br>"){
		self::$ResultNum = $num;
		$message = "";
		foreach(self::$Messages as $key=>$val)
			if(strpos($key,"Success-")==0) $message .= self::Success($val,$translate).$splitor;
			elseif(strpos($key,"Error-")==0) $message .= self::Error($val,$translate).$splitor;
			else $message .= self::Message($val,$translate).$splitor;
		self::Send("{\"type\":\"${num}\",\"message\":\"${message}\"}");
	}
	
	public static function Send($result){
		self::Action($result);
	}


//Show Part

	public static function ShowText($message,$num=0,$translate = true){
		if($translate) $message = Translate::Get($message);
		self::PushMessage("Text",$message);
		$result= "{\"type\":\"${num}\",\"message\":\"".$message."\"}";
		self::Show($result);
	}
	
	public static function ShowMessage($message,$num=0,$translate = true){
		self::$ResultNum = $num;
		self::Show(self::Message($message,$translate),$num);
	}

	public static function ShowSuccess($message,$num = 1,$translate = true){
		self::$ResultNum = $num;
		self::Show(self::Success($message,$translate),$num);
	}

	public static function ShowError($message,$num = -1,$translate = true){
		self::$ResultNum = $num;
		self::Show(self::Error($message,$translate),$num);
	}

	public static function ShowException($ex,$num = -1,$translate = true){
		self::ShowError($ex->getMessage(),$num,$translate);
	}

	public static function ShowSuccesses($num = 1,$translate = true,$splitor="<br>"){
		self::$ResultNum = $num;
		$message = "";
		$message = self::GetMessages("Success",$translate,$splitor);
		self::Show(self::Success($message,false),$num);
	}

	public static function ShowErrors($num = -1,$translate = true,$splitor="<br>"){
		self::$ResultNum = $num;
		$message = self::GetMessages("Error",$translate,$splitor);
		self::Show(self::Error($message,false),$num);
	}

	public static function ShowMessages($num = 1,$translate = true,$splitor="<br>"){
		self::$ResultNum = $num;
		$message = self::GetMessages("Message",$translate,$splitor);
		self::Show($message,$num);
	}

	public static function ShowAll($num = 1,$translate = true,$splitor="<br>"){
		self::$ResultNum = $num;
		$message = "";
		if($translate) foreach(self::$Messages as $key=>$val) $message .= Translate::Get($val).$splitor;
		else foreach(self::$Messages as $key=>$val) $message .= $val.$splitor;
		self::Show($message,$num);
	}
	
	public static function Show($result,$num = 0){
		if((self::$ResultNum =$num) == 0){
			$result = "<style>
			.success{
				font-size: 2em;
				text-align: center;
				color: #1a1;
				width: 60%;
				padding: 10%;
				margin: 10%;
				border: 2px solid #1a1;
			}
			.error{
				font-size: 2em;
				text-align: center;
				color: #f11;
				width: 60%;
				padding: 10%;
				margin: 10%;
				border: 2px solid #f11;
			}
			</style>".$result;
			self::Action($result);
		}
		else self::ShowURL("/view?message=".$result);
	}

	public static function ShowURL($url)
	{
		ob_start(); // ensures anything dumped out will be caught

		// clear out the output buffer
		while (ob_get_status()) 
			ob_end_clean();

		// no redirect
		header( "Location: $url" );
		self::Action(null);
	}

	public static $Messages = array();
	public static $Run = true;
	public static $ResultNum = 0;

	public static function Suspend() { self::$Run = false;}
	public static function Resume() { self::$Run = true;}
		
	public static function GetMessages($kind = "Message",$translate = true,$splitor="<br>")
	{
		$message = "";
		if($translate) foreach(self::$Messages as $key=>$val) if(strpos($key,$kind."-")==0) $message .= Translate::Get($val).$splitor;
		else foreach(self::$Messages as $key=>$val) if(strpos($key,$kind."-")==0) $message .= $val.$splitor;
		return $message;
	}
	public static function ClearMessages()
	{
		self::$Messages = array();
	}
	public static function PushMessage($key,$val)
	{
		self::$Messages[$key."-".count(self::$Messages)] = $val; 
	}
	public static function Action($object=null)
	{
		if(self::$Run) if($object==null) exit(); else exit($object);
	}

}
?>