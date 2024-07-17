<?php namespace MiMFa\Library;
/**
 * A simple library to work by the local files and folders
*@copyright All rights are reserved for MiMFa Development Group
*@author Mohammad Fathi
*@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#local See the Library Documentation
*/
class Local{
    /**
     * Get or Find a file, then get the external url pointed to catch status
     * @param mixed $path Probable file external url or path
     * @return mixed
     */
	public static function GetFullUrl($path, $optimize = true){
		if($optimize) return self::OptimizeUrl(self::GetUrl($path));
		return self::GetUrl($path);
    }
    /**
     * Get or Find a file, then get the external url
     * @param mixed $path Probable file external url or path
     * @return mixed
     */
	public static function GetUrl($path){
		if((!isValid($path)) || isAbsoluteUrl($path)) return $path;
		if(startsWith($path, \_::$DIR)) return \_::$ROOT.substr($path, strlen(\_::$DIR));
		if(startsWith($path, \_::$BASE_DIR)) return \_::$BASE_ROOT.substr($path, strlen(\_::$BASE_DIR));
		if(!startsWith($path, "/")){
			$dirs = explode("/", \_::$DIRECTION);
			$dirs = implode("/", array_slice($dirs, 0, count($dirs) - 1));
			if(strlen($dirs) !== 0) $path = "$dirs/$path";
        }
		$p = ltrim(getRelative($path), "/\\");
		if(file_exists(\_::$DIR.$p)) return \_::$ROOT.$p;
		if(count(\_::$SEQUENCES) > 0){
			foreach(\_::$SEQUENCES as $dir=>$root)
				if(file_exists($dir.$p))
					return $root.$p;
		}
		if(file_exists(\_::$BASE_DIR.$p)) return \_::$BASE_ROOT.$p;
		return $path;
	}
	/**
	 * Get or Find a file, then get the internal path
	 * @param mixed $path Probable file internal path
	 * @return mixed
	 */
	public static function GetPath($path){
		if(!isValid($path)) return $path;
		elseif(startsWith($path, \_::$ROOT)) $path = \_::$DIR.substr($path,strlen(\_::$ROOT));
		elseif(startsWith($path, \_::$BASE_ROOT)) $path = \_::$BASE_DIR.substr($path,strlen(\_::$BASE_ROOT));
		else{
			$p = ltrim(getRelative($path), "/\\");
            if(file_exists(\_::$DIR.$p)) return realpath(\_::$DIR.$p);
            if(count(\_::$SEQUENCES) > 0){
                foreach(\_::$SEQUENCES as $dir=>$root)
                    if(file_exists($dir.$p))
                        return realpath($root.$p);
            }
            if(file_exists(\_::$BASE_DIR.$p)) return realpath(\_::$BASE_DIR.$p);
        }
		return realpath($path);
	}
    /**
     * Get the external url pointed to catch status
     * @param mixed $path The external url or path
     * @return mixed
     */
	public static function OptimizeUrl($path){
		if(\_::$CONFIG->AllowCache) return $path;
		if(strpos($path,"?")>0) $path .= "&";
		else $path .= "?";
        return $path."v=".date(\_::$CONFIG->CachePeriod);
    }
    /**
     * Create a new unique address
     * @param string $dir Root directory
     * @param string $format The full format of extension (like .html)
     * @param int $random Pass 0 or false to get the name sequential from the number 1 to infinity
     * @return string
     */
    public static function NewUniquePath(string $fileName = "new", string $format = "", string $dir = null, bool $random = true):string{
		$dir =  $dir??\_::$PUBLIC_DIR;
		do $path = $dir.Convert::ToExcerpt(Convert::ToKey($fileName, true,'/[^A-Za-z0-9\_ \(\)]/'),0,50,"")."-".getId($random).$format;
        while(file_exists($path));
        return $path;
    }

	public static function GetDirectory($path){
		if(is_dir($path)) return $path;
		if(is_dir(\_::$DIR.$path)) return \_::$DIR.$path;
		$path = ltrim(getRelative($path), "/\/");
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
	public static function CreateDirectory($destPath){
		$dirs = explode("/",trim($destPath,"/"));
		$dir = "";
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
		$dir = trim($destPath,"/");
		return unlink($dir);
	}
	public static function MoveDirectory($sourceDir, $destDir, $recursive = true){
		if(self::CopyDirectory($sourceDir, $destDir, $recursive))
			return self::DeleteDirectory($sourceDir);
		return false;
    }
	public static function CopyDirectory($sourceDir, $destDir, $recursive = true):bool{
		set_time_limit (24 * 60 * 60);
		$b = true;
		$sourcePaths = scandir($sourceDir);
        if($recursive)
			foreach ($sourcePaths as $source)
            {
                $bn = basename($source);
                if(is_dir($source)){
                    self::CreateDirectory($destDir . $bn);
                    $b = self::CopyDirectory($source, $destDir . $bn) && $b;
                } else $b = self::CopyFile($source, $destDir . $bn) && $b;
            }
		return $b;
    }
	public static function CopyDirectories($sourceDirs, $destDirs, $recursive = true):bool{
		set_time_limit (24 * 60 * 60);
		$b = true;
        foreach ($sourceDirs as $s_dir)
            foreach ($destDirs as $d_dir)
                $b = self::CopyDirectory($s_dir, $d_dir, $recursive) && $b;
		return $b;
    }


	public static function GetFile($path){
		if(file_exists($path)) return $path;
		if(file_exists(\_::$DIR.$path)) return \_::$DIR.$path;
		$path = ltrim(getRelative($path), "/\/");
		if(count(\_::$SEQUENCES) > 0){
			foreach(\_::$SEQUENCES as $aseq)
				if(file_exists($aseq.$path)) return $aseq.$path;
		}
		if(file_exists(\_::$BASE_DIR.$path)) return \_::$BASE_DIR.$path;
		return null;
	}
	public static function FileExists($path):bool{
		return file_exists(self::GetPath($path));
	}
	public static function CreateFile($path){
		return fopen(self::GetPath($path),"w");
	}
	public static function DeleteFile($path){
		$path = self::GetPath($path);
		return (!file_exists($path)) || unlink($path);
	}
	public static function MoveFile($sourcePath, $destPath):bool{
		if(self::CopyFile($sourcePath, $destPath))
			return self::DeleteFile($sourcePath);
		return false;
    }
	public static function CopyFile($sourcePath, $destPath):bool{
		set_time_limit (24 * 60 * 60);
		$b = false;
		$sourcePath = self::GetPath($sourcePath);
		$destPath = self::GetPath($destPath);
        $s_file = fopen ($sourcePath, "rb");
        if ($s_file) {
            $d_file = fopen ($destPath, "wb");
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
	public static function CopyFiles($sourcePaths, $destPaths):bool{
		set_time_limit (24 * 60 * 60);
		$b = true;
        foreach ($sourcePaths as $s_path)
            foreach ($destPaths as $d_path)
                $b = self::CopyFile($s_path, $d_path) && $b;
		return $b;
    }

	public static function ReadText($path):null|string{
		$res = file_get_contents(self::GetPath($path));
        if($res === false) return null;
		return $res;
	}
	public static function WriteText($path, string|null $text){
		return file_put_contents(self::GetPath($path),$text);
	}


    public static function GetForceImage($path="/file/image/image.png", array $extensions = null){
		$filepath = self::GetPath(preg_replace("/\.[^\.\\/\\\]+$/","",$path));
		foreach ($extensions??\_::$CONFIG->AcceptableImageFormats as $format)
            if(file_exists(self::GetUrl("$filepath$format"))) return self::GetUrl("$filepath$format");
        return $filepath;
	}
	/**
     * Get the fileobject by file key name
     * @param mixed $inputName Posted file key name
	 * @return mixed
	 */
	public static function GetFileObject($inputName){
		return $_FILES[$inputName];
	}
	/**
     * Check if the fileobject is not null or empty
     * @param mixed $fileObject Posted file key name or object
	 * @return mixed
	 */
	public static function IsFileObject($fileObject){
		if(is_string($fileObject)) $fileObject = $_FILES[$fileObject];
		return !isEmpty($fileObject) && !isEmpty($fileObject["name"]);
	}

	/**
	 * Upload File
	 * @param mixed $fileObject A file object or posted file key name
     * @param mixed $destDir Leave null if you want to use PUBLIC_DIR as the destination
	 * @param mixed $minSize Minimum file size in byte
     * @param mixed $maxSize Maximum file size in byte
     * @param mixed $extensions Acceptable extentions for example ["jpg","jpeg","png","bmp","gif","ico"]
	 * @return string Return the uploaded file url, else return null
	 */
	public static function Upload($fileObject, $destDir=null, $minSize=null, $maxSize=null, array $extensions=null){
		if(is_string($fileObject)) $fileObject = self::GetFileObject($fileObject);
		if(is_null($fileObject) || empty($fileObject) || isEmpty($fileObject["name"])) throw new \Exception("There is not any file!");
		if(!isValid($destDir)) $destDir = \_::$PUBLIC_DIR;

		$fileType = strtolower(pathinfo($fileObject["name"], PATHINFO_EXTENSION));
		$dir = self::CreateDirectory(trim($destDir,"/"));
		$fileName = strtolower(pathinfo($fileObject["name"], PATHINFO_FILENAME))."_";

		// Allow certain file formats
		$allow = true;
		foreach($extensions??\_::$CONFIG->GetAcceptableFormats() as $ext) if($allow = $fileType === $ext || ".".$fileType === $ext) break;
		if(!$allow) throw new \Exception("The file format is not acceptable!");

		// Check file size
		$minSize = $minSize??\_::$CONFIG->MinimumFileSize;
		$maxSize = $maxSize??\_::$CONFIG->MaximumFileSize;
		if($fileObject["size"]<$minSize) throw new \Exception("The file size is very small!");
		elseif($fileObject["size"]>$maxSize) throw new \Exception("The file size is very big!");

		$filepath = self::NewUniquePath($fileName,".$fileType", $dir);
		if(!move_uploaded_file($fileObject["tmp_name"], $filepath))
            throw new \Exception("Sorry, there was an error uploading your file.");
        return self::GetUrl($filepath);
	}
	/**
	 * Upload File
	 * @param mixed $fileObject A file object or posted file key name
     * @param mixed $destDir Leave null if you want to use PUBLIC_DIR as the destination
	 * @param mixed $minSize Minimum file size in byte
     * @param mixed $maxSize Maximum file size in byte
     * @param mixed $extensions Acceptable extentions for example ["jpg","jpeg","png","bmp","gif","ico"]
	 * @return string Return the uploaded file url, else return null
	 */
	public static function UploadFile($fileObject, $destDir=null, $minSize=null, $maxSize=null, array $extensions=null){
		if(is_string($fileObject)) $fileObject = self::GetFileObject($fileObject);
		if(is_null($fileObject) || empty($fileObject) || isEmpty($fileObject["name"])) throw new \Exception("There is not any file!");

		return self::Upload($fileObject, $destDir,$minSize, $maxSize, $extensions??\_::$CONFIG->AcceptableFileFormats);
	}
	/**
     * Upload Image
     * @param mixed $fileObject An image object or posted file key name
     * @param mixed $destDir Leave null if you want to use PUBLIC_DIR as the destination
     * @param mixed $minSize Minimum image size in byte
     * @param mixed $maxSize Maximum image size in byte
     * @param mixed $extensions Acceptable image extentions (leave default for "jpg","jpeg","png","bmp","gif","ico" formats)
     * @return string Return the uploaded image url, else return null
     */
	public static function UploadImage($fileObject, $destDir=null, $minSize=null, $maxSize=null,array $extensions=null){
		if(is_string($fileObject)) $fileObject = self::GetFileObject($fileObject);
		if(is_null($fileObject) || empty($fileObject) || isEmpty($fileObject["name"])) throw new \Exception("There is not any file!");

		// Check if image file is an actual image or fake image
		if(getimagesize($fileObject["tmp_name"]) === false) throw new \Exception("The image file is not an actual image!");
		return self::Upload($fileObject, $destDir, $minSize, $maxSize, $extensions??\_::$CONFIG->AcceptableImageFormats);
	}
	/**
	 * Upload audio
	 * @param mixed $fileObject A file object or posted file key name
     * @param mixed $destDir Leave null if you want to use PUBLIC_DIR as the destination
	 * @param mixed $minSize Minimum file size in byte
     * @param mixed $maxSize Maximum file size in byte
     * @param mixed $extensions Acceptable extentions for example ["jpg","jpeg","png","bmp","gif","ico"]
	 * @return string Return the uploaded file url, else return null
	 */
	public static function UploadAudio($fileObject, $destDir=null, $minSize=null, $maxSize=null, array $extensions=null){
		if(is_string($fileObject)) $fileObject = self::GetFileObject($fileObject);
		if(is_null($fileObject) || empty($fileObject) || isEmpty($fileObject["name"])) throw new \Exception("There is not any file!");

		return self::Upload($fileObject, $destDir,$minSize, $maxSize, $extensions??\_::$CONFIG->AcceptableAudioFormats);
	}
	/**
	 * Upload video
	 * @param mixed $fileObject A file object or posted file key name
     * @param mixed $destDir Leave null if you want to use PUBLIC_DIR as the destination
	 * @param mixed $minSize Minimum file size in byte
     * @param mixed $maxSize Maximum file size in byte
     * @param mixed $extensions Acceptable extentions for example ["jpg","jpeg","png","bmp","gif","ico"]
	 * @return string Return the uploaded file url, else return null
	 */
	public static function UploadVideo($fileObject, $destDir=null, $minSize=null, $maxSize=null, array $extensions=null){
		if(is_string($fileObject)) $fileObject = self::GetFileObject($fileObject);
		if(is_null($fileObject) || empty($fileObject) || isEmpty($fileObject["name"])) throw new \Exception("There is not any file!");

		return self::Upload($fileObject, $destDir,$minSize, $maxSize, $extensions??\_::$CONFIG->AcceptableVideoFormats);
	}
	/**
	 * Upload document
	 * @param mixed $fileObject A file object or posted file key name
     * @param mixed $destDir Leave null if you want to use PUBLIC_DIR as the destination
	 * @param mixed $minSize Minimum file size in byte
     * @param mixed $maxSize Maximum file size in byte
     * @param mixed $extensions Acceptable extentions for example ["jpg","jpeg","png","bmp","gif","ico"]
	 * @return string Return the uploaded file url, else return null
	 */
	public static function UploadDocument($fileObject, $destDir=null, $minSize=null, $maxSize=null, array $extensions=null){
		if(is_string($fileObject)) $fileObject = self::GetFileObject($fileObject);
		if(is_null($fileObject) || empty($fileObject) || isEmpty($fileObject["name"])) throw new \Exception("There is not any file!");

		return self::Upload($fileObject, $destDir,$minSize, $maxSize, $extensions??\_::$CONFIG->AcceptableDocumentFormats);
	}
	/**
	 * Send somthing to download
	 * @param mixed $content
	 * @param mixed $fileName
	 * @param mixed $contentType
	 */
	public static function Download($content, $fileName = "Export.txt",  $contentType = "text/plain"){
        ob_clean();
        flush();

        ini_set('mbstring.internal_encoding', \_::$CONFIG->Encoding);
        ini_set('mbstring.http_input','auto');
        ini_set('mbstring.http_output', \_::$CONFIG->Encoding);
        ini_set('mbstring.detect_order','auto');
        ini_set('default_charset', \_::$CONFIG->Encoding);

        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header("Content-Type: application/force-download");
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header("Content-Type: $contentType; charset=".\_::$CONFIG->Encoding);

        SEND("\xEF\xBB\xBF".$content);
    }
}

?>
