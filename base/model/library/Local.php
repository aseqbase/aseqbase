<?php namespace MiMFa\Library;
class Local{
	public static function GetUrl($path){
		if(!isValid($path) || isAbsoluteUrl($path)) return $path;
		if(startsWith($path, "/")){
			$p = ltrim(GetDirection($path), "/\\");
			if(file_exists(\_::$DIR.$p)) return \_::$ROOT.$p;
			if(count(\_::$SEQUENCES) > 0){
				foreach(\_::$SEQUENCES as $dir=>$root)
					if(file_exists($dir.$p))
						return $root.$p;
			}
			if(file_exists(\_::$BASE_DIR.$p)) return \_::$BASE_ROOT.$p;
		} else {
			$p = rtrim(\_::$PATH, "/\\")."/".$path;
			if(file_exists(realpath($p))) return $p;
		}
		return $path;
	}
	public static function GetPath($path){
		if(!isValid($path) || IsAbsoluteUrl($path)) return $path;
		$p = ltrim(GetDirection($path), "/\\");
		if(file_exists(\_::$DIR.$p)) return \_::$DIR.$p;
		if(count(\_::$SEQUENCES) > 0){
			foreach(\_::$SEQUENCES as $dir=>$root)
				if(file_exists($dir.$p))
					return $root.$p;
		}
		if(file_exists(\_::$BASE_DIR.$p)) return \_::$BASE_DIR.$p;
		return $path;
	}

	public static function CreateDirectory($destPath){
		$dirs = explode("/",trim($destPath,"/"));
		$dir = rtrim(\_::$PUBLIC_DIR);
		foreach($dirs as $d){
			$dir .= "/".$d;
			if(!file_exists($dir)) mkdir($dir, 0777, true);
		}
		return $dir."/";
	}
	public static function DeleteDirectory($destPath){
		$dir = \_::$PUBLIC_DIR.trim($destPath,"/");
		return unlink($dir);
	}
	public static function GetDirectory($path){
		if(is_dir($path)) return $path;
		if(is_dir(\_::$DIR.$path)) return \_::$DIR.$path;
		$path = ltrim(GetDirection($path), "/\/");
		if(count(\_::$SEQUENCES) > 0){
			foreach(\_::$SEQUENCES as $aseq)
				if(is_dir($aseq.$path)) return $aseq.$path;
		}
		if(is_dir(\_::$BASE_DIR.$path)) return \_::$BASE_DIR.$path;
		return null;
	}

	public static function GetFile($path){
		if(file_exists($path)) return $path;
		if(file_exists(\_::$DIR.$path)) return \_::$DIR.$path;
		$path = ltrim(GetDirection($path), "/\/");
		if(count(\_::$SEQUENCES) > 0){
			foreach(\_::$SEQUENCES as $aseq)
				if(file_exists($aseq.$path)) return $aseq.$path;
		}
		if(file_exists(\_::$BASE_DIR.$path)) return \_::$BASE_DIR.$path;
		return null;
	}

	public static function GetFileObject($inputName){
		return $_FILES[$inputName];
	}
	public static function UploadFile($fileobject, $destdir, $minSize=10000, $maxSize=5000000, $extensions=[]){
		if(is_string($fileobject)) $fileobject = self::GetFileObject($fileobject);

		$obj = new \stdClass();
		$obj->status = false;
		$obj->result = null;
		$obj->error = [];

		$fileType = strtolower(pathinfo($fileobject["name"],PATHINFO_EXTENSION));
		$obj->result = trim($destdir,"/");
		$dir = self::CreateDirectory($obj->result);
		$fileName = explode(".",basename($fileobject["name"]))[0]."_".getId().".".$fileType;
		$filepath = $dir.$fileName;
		$obj->result .= "/".$fileName;

		if(is_null($fileobject) || empty($fileobject) || isEmpty($fileobject["name"])) $obj->error[] = "There is not any file!";

		// Allow certain file formats
		$allow = true;
		foreach($extensions as $ext) if($allow = $fileType === $ext) break;
		if(!$allow) $obj->error[] = "The file format is not acceptable!";

		// Check file size
		if($fileobject["size"]<$minSize) $obj->error[] = "The file size is very small!";
		elseif($fileobject["size"]>$maxSize) $obj->error[] = "The file size is very big!";

		// Check if file already exists
		if(file_exists($filepath)) $obj->error[] = "The file is already exists!";

		if(count($obj->error) > 0) $obj->error[] = "Sorry, your file was not uploaded.";
		elseif(!($obj->status=move_uploaded_file($fileobject["tmp_name"], $filepath)))
			 $obj->error[] = "Sorry, there was an error uploading your file.";
		return $obj;
	}
	public static function UploadImage($fileobject, $destdir, $minSize=10000, $maxSize=5000000,$extensions=["jpg","jpeg","png","bmp","gif","ico"]){
		if(is_string($fileobject)) $fileobject = self::GetFileObject($fileobject);

		$obj = new \stdClass();
		$obj->status = false;
		$obj->result = null;
		$obj->error = [];

		if(is_null($fileobject) || empty($fileobject) || isEmpty($fileobject["name"])) $obj->error[] = "There is not any file!";

		// Check if image file is an actual image or fake image
		if(getimagesize($fileobject["tmp_name"]) === false) $obj->error[] = "The image file is not an actual image!";
		return (count($obj->error)===0)? self::UploadFile($fileobject, $destdir,$minSize, $maxSize, $extensions) : $obj;
	}
}

?>
