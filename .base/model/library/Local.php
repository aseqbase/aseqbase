<?php
namespace MiMFa\Library;
/**
 * A simple library to work by the local files and folders
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#local See the Library Documentation
 */
class Local
{
	/**
	 * Get or Find a file, then get the external url
	 * @param string|null $address Probable file external url or path
	 * @return mixed
	 */
	public static function GetUrl($address)
	{
		if ((!isValid($address)) || isAbsoluteUrl($address))
			return $address;
		$address = self::GetRelativePath(str_replace(["/", "\\"], DIRECTORY_SEPARATOR, $address));
		$p = ltrim(str_replace(DIRECTORY_SEPARATOR, "/", $address), "/");
		$d = ltrim(preg_replace("/[\?#@].*$/", "", $address), DIRECTORY_SEPARATOR);
		foreach (\_::$Sequences as $dir => $root)
			if (file_exists($dir . $d))
				return $root . $p;
		$address = str_replace(DIRECTORY_SEPARATOR, "/", $address);
		if (!startsWith($address, "/")) {
			$dirs = explode("/", \Req::$Direction);
			$dirs = rtrim(implode("/", array_slice($dirs, 0, count($dirs) - 1)),"/");
			if (strlen($dirs) !== 0)
				$address = "$dirs/$address";
		}
		return $address;
	}
	/**
	 * Get the external absolute url
	 * @param string|null $url Probable file external url or path
	 * @return mixed
	 */
	public static function GetAbsoluteUrl($url)
	{
		if ((!isValid($url)) || isAbsoluteUrl($url))
			return $url;
		if (startsWith($url, "/")) 
			return \Req::$Host.$url;
		else {
			$dirs = explode("/", \Req::$Url);
			$dirs = rtrim(implode("/", array_slice($dirs, 0, count($dirs) - 1)), "/");
			return "$dirs/$url";
		}
	}
	/**
	 * Get the relative address from a url
	 * @example: "Category/mimfa/service/web.php?p=3&l=10#serp"
	 * @return string|null
	 */
	public static function GetRelativeUrl($url): string|null
	{
		if (!isValid($url)) return null;
		foreach (\_::$Sequences as $dir => $root)
			if (startsWith($url, $root))
				return substr($url, strlen($root));
		return PREG_Replace("/^\w+:\/*[^\/]+/", "", $url);
	}
	/**
	 * Get the external url pointed to catch status
	 * @param mixed $url The external url
	 * @return mixed
	 */
	public static function OptimizeUrl($url)
	{
		if (!\_::$Config->CachePeriod)
			return $url;
		if (strpos($url, "?") > 0)
			$url .= "&";
		else
			$url .= "?";
		return $url . "v=" . (\_::$Config->CachePeriod == "v" ? \_::$Version : date(\_::$Config->CachePeriod));
	}

	/**
	 * Get or Find a file, then return the internal path
	 * @param mixed $address Probable file internal path
	 * @return
	 */
	public static function GetPath($address)
	{
		if (!isValid($address)) return null;
		return
			ltrim(
				str_replace(
					["\\", "/"],
					DIRECTORY_SEPARATOR,
					preg_replace(
						"/(^\w+:\/*[^\/]+\/?)|([\?#@].*$)/",
						"",
						$address
					)
				),
				DIRECTORY_SEPARATOR
			);
	}
	/**
	 * Get absolute path from a relative one
	 * @param  $path The relative path
	 * @return string|null
	 */
	public static function GetAbsolutePath($path): string|null
	{
		if (!isValid($path)) return null;
		foreach (\_::$Sequences as $dir => $root)
			if (startsWith($path, $dir))
				return $path;
		return \_::$Aseq->Directory.ltrim($path, DIRECTORY_SEPARATOR);
	}
	/**
	 * Get the relative address from a path
	 * @param  $path The path
	 * @example: "Category/mimfa/service/web.php?p=3&l=10#serp"
	 * @return string|null
	 */
	public static function GetRelativePath($path): string|null
	{
		if (!isValid($path)) return null;
		foreach (\_::$Sequences as $dir => $root)
			if (startsWith($path, $dir))
				return substr($path, strlen($dir));
		return $path;
	}

	/**
	 * Create a new unique address
	 * @param string $dir Root directory, leave null for a temp directory
	 * @param string $format The full format of extension (like .html)
	 * @param int $random Pass 0 or false to get the name sequential from the number 1 to infinity
	 * @return string
	 */
	public static function CreatePath(string $fileName = "new", string $format = "", ?string $dir = null, bool $random = true): string
	{
		$dir = $dir ?? \_::$Aseq->TempDirectory;
		do
			$path = $dir . Convert::ToExcerpt(Convert::ToKey($fileName, true, '/[^A-Za-z0-9\_ \(\)]/'), 0, 50, "") . "-" . getId($random) . $format;
		while (file_exists($path));
		return $path;
	}

	/**
	 * Find an exists directory, then get the internal path
	 * @param mixed $path Probable file internal path
	 * @return string|null
	 */
	public static function GetDirectory($path)
	{
		$path = self::GetPath($path);
		if (!$path || is_dir($path))
			return $path;
		if (startsWith($path, \_::$Aseq->Directory))
			$path = substr($path, strlen(\_::$Aseq->Directory));
		foreach (\_::$Sequences as $dir => $p)
			if (is_dir($dir . $path))
				return $dir . $path;
		return null;
	}
	public static function DirectoryExists($path): bool
	{
		return is_dir($path);
	}
	public static function CreateDirectory($destPath)
	{
		$dir = "";
		if (startsWith($destPath, \_::$Aseq->Directory))
			$destPath = substr($destPath, strlen($dir = \_::$Aseq->Directory));
		$dirs = explode(DIRECTORY_SEPARATOR, trim($destPath, DIRECTORY_SEPARATOR));
		foreach ($dirs as $d)
			if (!file_exists($dir .= $d)) {
				mkdir($dir, 0777, true);
				self::CreateFile(($dir .= DIRECTORY_SEPARATOR) . "index.html", "<!--Silence is the Best-->");
			} else
				$dir .= DIRECTORY_SEPARATOR;
		return $dir;
	}
	public static function DeleteDirectory($destPath)
	{
		$dir = trim($destPath, DIRECTORY_SEPARATOR);
		return unlink($dir);
	}
	public static function MoveDirectory($sourceDir, $directory, $recursive = true)
	{
		if (self::CopyDirectory($sourceDir, $directory, $recursive))
			return self::DeleteDirectory($sourceDir);
		return false;
	}
	public static function CopyDirectory($sourceDir, $directory, $recursive = true): bool
	{
		set_time_limit(24 * 60 * 60);
		$b = true;
		$sourcePaths = scandir($sourceDir);
		if ($recursive)
			foreach ($sourcePaths as $source) {
				$bn = basename($source);
				if (is_dir($source)) {
					self::CreateDirectory($directory . $bn);
					$b = self::CopyDirectory($source, $directory . $bn) && $b;
				} else
					$b = self::CopyFile($source, $directory . $bn) && $b;
			}
		return $b;
	}
	public static function CopyDirectories($sourceDirs, $directorys, $recursive = true): bool
	{
		set_time_limit(24 * 60 * 60);
		$b = true;
		foreach ($sourceDirs as $s_dir) foreach ($directorys as $d_dir)
				$b = self::CopyDirectory($s_dir, $d_dir, $recursive) && $b;
		return $b;
	}


	/**
	 * Find an exists file, then get the internal path
	 * @param mixed $path Probable file internal path
	 * @return string|null
	 */
	public static function GetFile($path)
	{
		$path = self::GetPath($path);
		if (!$path || file_exists($path))
			return $path;
		if (startsWith($path, \_::$Aseq->Directory))
			$path = substr($path, strlen(\_::$Aseq->Directory));
		foreach (\_::$Sequences as $dir => $p)
			if (file_exists($dir . $path))
				return $dir . $path;
		return null;
	}
	public static function FileExists($path): bool
	{
		return !empty(self::GetFile($path));
	}
	public static function CreateFile($path, $content = null)
	{
		$res = fopen($path, "w");
		if ($content)
			fwrite($res, $content);
		fclose($res);
		return $res;
	}
	public static function DeleteFile($path)
	{
		$path = self::GetFile($path);
		return empty($path) || unlink($path);
	}
	public static function MoveFile($sourcePath, $destPath): bool
	{
		if (self::CopyFile($sourcePath, $destPath))
			return self::DeleteFile($sourcePath);
		return false;
	}
	public static function CopyFile($sourcePath, $destPath): bool
	{
		set_time_limit(24 * 60 * 60);
		$b = false;
		$sourcePath = self::GetFile($sourcePath);
		$destPath = self::GetPath($destPath);
		$s_file = fopen($sourcePath, "rb");
		if ($s_file) {
			$d_file = fopen($destPath, "wb");
			if ($d_file) {
				while (!feof($s_file)) {
					fwrite($d_file, fread($s_file, 1024 * 8), 1024 * 8);
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
	public static function CopyFiles($sourcePaths, $destPaths): bool
	{
		set_time_limit(24 * 60 * 60);
		$b = true;
		foreach ($sourcePaths as $s_path) foreach ($destPaths as $d_path)
				$b = self::CopyFile($s_path, $d_path) && $b;
		return $b;
	}

	public static function ReadText($path): null|string
	{
		$res = file_get_contents(self::GetFile($path));
		if ($res === false)
			return null;
		return $res;
	}
	public static function WriteText($path, string|null $text, bool $ifNeeds = false)
	{
		if($ifNeeds && (self::ReadText($path) === $text)) return null;
		return file_put_contents(self::GetFile($path) ?? self::GetPath($path), $text);
	}


	/**
	 * Get the fileobject by file key name
	 * @param mixed $inputName Posted file key name
	 * @return mixed
	 */
	public static function GetFileObject($inputName)
	{
		return \Req::ReceiveFile($inputName);
	}
	/**
	 * Check if the fileobject is not null or empty
	 * @param mixed $content Posted file key name or object
	 * @return mixed
	 */
	public static function IsFileObject($content)
	{
		if (is_string($content))
			$content = \Req::Receive($content);
		return get($content, "name") ? true : false;
	}

	/**
	 * Save (Upload from the client side) something to the local storage
	 * @param mixed $content A file object or posted file key name
	 * @param mixed $directory Leave null if you want to use \_::$Aseq->PublicDirectory as the destination
	 * @param mixed $minSize Minimum file size in byte
	 * @param mixed $maxSize Maximum file size in byte
	 * @param mixed $extensions Acceptable extentions for example ["jpg","jpeg","png","bmp","gif","ico"]
	 * @return string Return the uploaded file path, else return null
	 */
	public static function Store($content, $directory = null, $minSize = null, $maxSize = null, ?array $extensions = null, $deleteSource = true)
	{
		if (is_string($content))
			$content = self::GetFileObject($content);
		if (!get($content, "name"))
			throw new \SilentException("There is not any file!");
		$directory = $directory ?? \_::$Aseq->PublicDirectory;

		$fileType = strtolower(pathinfo($content["name"], PATHINFO_EXTENSION));
		$dir = self::CreateDirectory($directory);
		$fileName = strtolower(pathinfo($content["name"], PATHINFO_FILENAME)) . "_";

		// Allow certain file formats
		$allow = true;
		foreach ($extensions ?? \_::$Config->GetAcceptableFormats() as $ext)
			if ($allow = $fileType === $ext || "." . $fileType === $ext)
				break;
		$sourceFile = $content["tmp_name"];
		if (!$allow) {
			if ($deleteSource)
				unlink($sourceFile);
			throw new \SilentException("The file format is not acceptable!");
		}
		// Check file size
		$minSize = $minSize ?? \_::$Config->MinimumFileSize;
		$maxSize = $maxSize ?? \_::$Config->MaximumFileSize;
		if ($content["size"] < $minSize) {
			if ($deleteSource)
				unlink($sourceFile);
			throw new \SilentException("The file size is very small!");
		} elseif ($content["size"] > $maxSize) {
			if ($deleteSource)
				unlink($sourceFile);
			throw new \SilentException("The file size is very big!");
		}
		if (!$dir) {
			$dir = \_::$Aseq->TempDirectory;
			$t = preg_find("/^[\w-]+\b/", $content["type"] ?? "");
			if ($t)
				$dir .= $t . DIRECTORY_SEPARATOR;
		}
		$destFile = self::CreatePath($fileName, ".$fileType", $dir);
		if (is_uploaded_file($sourceFile) && move_uploaded_file($sourceFile, $destFile))
			return $destFile;
		if (rename($sourceFile, $destFile))
			return $destFile;
		if ($deleteSource)
			unlink($sourceFile);
		throw new \SilentException("Sorry, there was an error uploading your file.");
	}
	/**
	 * Save (Upload from the client side) file to the local storage
	 * @param mixed $content A file object or posted file key name
	 * @param mixed $directory Leave null if you want to use \_::$Aseq->PublicDirectory as the destination
	 * @param mixed $minSize Minimum file size in byte
	 * @param mixed $maxSize Maximum file size in byte
	 * @param mixed $extensions Acceptable extentions for example ["jpg","jpeg","png","bmp","gif","ico"]
	 * @return string Return the uploaded file path, else return null
	 */
	public static function StoreFile($content, $directory = null, $minSize = null, $maxSize = null, ?array $extensions = null)
	{
		return self::Store($content, $directory, $minSize, $maxSize, $extensions ?? \_::$Config->AcceptableFileFormats);
	}
	/**
	 * Save (Upload from the client side) image to the local storage
	 * @param mixed $content An image object or posted file key name
	 * @param mixed $directory Leave null if you want to use \_::$Aseq->PublicDirectory as the destination
	 * @param mixed $minSize Minimum image size in byte
	 * @param mixed $maxSize Maximum image size in byte
	 * @param mixed $extensions Acceptable image extentions (leave default for "jpg","jpeg","png","bmp","gif","ico" formats)
	 * @return string Return the uploaded image path, else return null
	 */
	public static function StoreImage($content, $directory = null, $minSize = null, $maxSize = null, ?array $extensions = null)
	{
		if (is_string($content))
			$content = self::GetFileObject($content);
		if (!get($content, "name"))
			throw new \SilentException("There is not any file!");

		// Check if image file is an actual image or fake image
		if (getimagesize($content["tmp_name"]) === false)
			throw new \SilentException("The image file is not an actual image!");
		return self::Store($content, $directory, $minSize, $maxSize, $extensions ?? \_::$Config->AcceptableImageFormats);
	}
	/**
	 * Save (Upload from the client side) audio to the local storage
	 * @param mixed $content A file object or posted file key name
	 * @param mixed $directory Leave null if you want to use \_::$Aseq->PublicDirectory as the destination
	 * @param mixed $minSize Minimum file size in byte
	 * @param mixed $maxSize Maximum file size in byte
	 * @param mixed $extensions Acceptable extentions for example ["jpg","jpeg","png","bmp","gif","ico"]
	 * @return string Return the uploaded file path, else return null
	 */
	public static function StoreAudio($content, $directory = null, $minSize = null, $maxSize = null, ?array $extensions = null)
	{
		return self::Store($content, $directory, $minSize, $maxSize, $extensions ?? \_::$Config->AcceptableAudioFormats);
	}
	/**
	 * Save (Upload from the client side) video to the local storage
	 * @param mixed $content A file object or posted file key name
	 * @param mixed $directory Leave null if you want to use \_::$Aseq->PublicDirectory as the destination
	 * @param mixed $minSize Minimum file size in byte
	 * @param mixed $maxSize Maximum file size in byte
	 * @param mixed $extensions Acceptable extentions for example ["jpg","jpeg","png","bmp","gif","ico"]
	 * @return string Return the uploaded file path, else return null
	 */
	public static function StoreVideo($content, $directory = null, $minSize = null, $maxSize = null, ?array $extensions = null)
	{
		return self::Store($content, $directory, $minSize, $maxSize, $extensions ?? \_::$Config->AcceptableVideoFormats);
	}
	/**
	 * Save (Upload from the client side) document to the local storage
	 * @param mixed $content A file object or posted file key name
	 * @param mixed $directory Leave null if you want to use \_::$Aseq->PublicDirectory as the destination
	 * @param mixed $minSize Minimum file size in byte
	 * @param mixed $maxSize Maximum file size in byte
	 * @param mixed $extensions Acceptable extentions for example ["jpg","jpeg","png","bmp","gif","ico"]
	 * @return string Return the uploaded file path, else return null
	 */
	public static function StoreDocument($content, $directory = null, $minSize = null, $maxSize = null, ?array $extensions = null)
	{
		return self::Store($content, $directory, $minSize, $maxSize, $extensions ?? \_::$Config->AcceptableDocumentFormats);
	}
	/**
	 * Load (Download from the client side) something from the local storage,
	 * Send somthing to download
	 * @param mixed $content
	 * @param string $name Optional filename to force download with a specific name.
	 * @param string $type The file content type (e.g., "application/pdf", "image/jpeg").
	 */
	public static function Load($content, $name = "Export.txt", $type = "text/plain")
	{
		// Sanitize the filename
		$name = preg_replace('/[^\w\-.]/', '_', $name);
		// Clear output buffer if active
		if (ob_get_level()) ob_clean();

		// ini_set('mbstring.internal_encoding', \_::$Config->Encoding);//deprecated
		// ini_set('mbstring.http_input', 'auto');//deprecated
		// ini_set('mbstring.http_output', \_::$Config->Encoding);//deprecated
		ini_set('mbstring.detect_order', 'auto');
		ini_set('default_charset', \_::$Config->Encoding);

		header("Content-Disposition: attachment; filename=\"$name\"");
		header("Content-Type: application/force-download");
		header("Content-Type: $type; charset=" . \_::$Config->Encoding);
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');

		\Res::Render("\xEF\xBB\xBF");
		\Res::End($content);
	}
	/**
	 * Load (Download from the client side) file from the local storage,
	 * Send somthing to download
	 * @param string $path The absolute or relative path to the file.
	 * @param string|null $type The file content type (e.g., "application/pdf", "image/jpeg").
	 * @param string|null $name Optional filename to force download with a specific name.
	 */
	public static function LoadFile($path, $name = null, $type = null){
		\Res::SetFile($path, null, $type, true, $name);
	}
}