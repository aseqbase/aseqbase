<?php namespace MiMFa\Library;
/**
 * A simple library to work by the local files and folders
*@copyright All rights are reserved for MiMFa Development Group
*@author Mohammad Fathi
*@see https://aseqbase.ir, https://github.com/mimfa/aseqbase
 *@link https://github.com/mimfa/aseqbase/wiki/Libraries#local See the Library Documentation
*/
class Local{
    /**
     * Get or Find a file, then get the external url pointed to catch status
     * @param mixed $path Probable file external url or path
     * @return mixed
     */
	public static function GetFullUrl($path){
		return self::CatchOptimizeUrl(self::GetUrl($path));
    }
    /**
     * Get or Find a file, then get the external url
     * @param mixed $path Probable file external url or path
     * @return mixed
     */
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
	/**
	 * Get or Find a file, then get the internal path
	 * @param mixed $path Probable file internal path
	 * @return mixed
	 */
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
    /**
     * Get the external url pointed to catch status
     * @param mixed $path The external url or path
     * @return mixed
     */
	public static function CatchOptimizeUrl($path){
		if(\_::$CONFIG->AllowCache) return $path;
		if(strpos($path,"?")>0) $path .= "&";
		else $path .= "?";
        return $path."v=".date(\_::$CONFIG->CachePeriod);
    }
    public static function CreateNewAddress(string $dir, string $format = "", int $length = 10):string{
        $id = time();
		$path = $dir.$id.$format;
		while(self::FileExists($path)) $path = $dir.$id."_".randomString(3).$format;
        return $path;
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
	public static function DirectoryExists($path):bool{
		return is_dir($path);
	}
	public static function CreateDirectory($destPath):string|null{
		$dirs = explode("/",trim($destPath,"/"));
		$dir = rtrim(\_::$PUBLIC_DIR);
		foreach($dirs as $d){
			$dir .= "/".$d;
			if(!file_exists($dir)){
                mkdir($dir, 0777, true);
				self::CreateFile($dir."/index.html");
            }
		}
		return $dir."/";
	}
	public static function DeleteDirectory($destPath){
		$dir = \_::$PUBLIC_DIR.trim($destPath,"/");
		return unlink($dir);
	}
	public static function MoveDirectory($source_dir, $dest_dir, $recursive = true){
		if(self::CopyDirectory($source_dir, $dest_dir, $recursive))
			return self::DeleteDirectory($source_dir);
		return false;
    }
	public static function CopyDirectory($source_dir, $dest_dir, $recursive = true):bool{
		set_time_limit (24 * 60 * 60);
		$b = true;
		$source_paths = scandir($source_dir);
        if($recursive)
			foreach ($source_paths as $source)
            {
                $bn = basename($source);
                if(is_dir($source)){
                    self::CreateDirectory($dest_dir . $bn);
                    $b = self::CopyDirectory($source, $dest_dir . $bn) && $b;
                } else $b = self::CopyFile($source, $dest_dir . $bn) && $b;
            }
		return $b;
    }
	public static function CopyDirectories($source_dirs, $dest_dirs, $recursive = true):bool{
		set_time_limit (24 * 60 * 60);
		$b = true;
        foreach ($source_dirs as $s_dir)
            foreach ($dest_dirs as $d_dir)
                $b = self::CopyDirectory($s_dir, $d_dir, $recursive) && $b;
		return $b;
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
	public static function FileExists($path):bool{
		return file_exists($path);
	}
	public static function CreateFile($path){
		return fopen($path,"w");
	}
	public static function DeleteFile($path){
		return unlink($path);
	}
	public static function MoveFile($source_path, $dest_path):bool{
		if(self::CopyFile($source_path, $dest_path))
			return self::DeleteFile($source_path);
		return false;
    }
	public static function CopyFile($source_path, $dest_path):bool{
		set_time_limit (24 * 60 * 60);
		$b = false;
        $s_file = fopen ($source_path, "rb");
        if ($s_file) {
            $d_file = fopen ($dest_path, "wb");
            if ($d_file)
            {
                while(!feof($s_file)) {
                    fwrite($d_file, fread($s_file, 1024 * 8 ), 1024 * 8 );
                }
                $b = true;
            }
        }

        if ($s_file) {
            fclose($s_file);
        }
        if ($d_file) {
            fclose($d_file);
        }

		return $b;
    }
	public static function CopyFiles($source_paths, $dest_paths):bool{
		set_time_limit (24 * 60 * 60);
		$b = true;
        foreach ($source_paths as $s_path)
            foreach ($dest_paths as $d_path)
                $b = self::CopyFile($s_path, $d_path) && $b;
		return $b;
    }

	public static function ReadText($path):string|null{
		return file_get_contents($path);
	}
	public static function WriteText($path, string|null $text){
		return fwrite($path,$text);
	}


    public static function GetForceImage($filePath="/file/image/image.png", $probableFormat = [".png",".jpg",".gif",".bmp",".jiff",".ico",".svg"]){
		$path = preg_replace("/\.[^\.\\/\\\]+$/","",$filePath);
		foreach ($probableFormat as $format)
            if(file_exists(self::GetUrl("$path$format"))) return self::GetUrl("$path$format");
        return $filePath;
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
