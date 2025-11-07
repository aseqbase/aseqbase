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
		if ((empty($address)) || isAbsoluteUrl($address))
			return $address;
		$address = self::GetRelativeAddress(str_replace(["/", "\\"], DIRECTORY_SEPARATOR, $address));
		$p = ltrim(str_replace(DIRECTORY_SEPARATOR, "/", $address), "/");
		$d = ltrim(preg_replace("/[\?#@].*$/", "", $address), DIRECTORY_SEPARATOR);
		foreach (\_::$Sequence as $dir => $root)
			if (file_exists($dir . $d))
				return $root . $p;
		$address = str_replace(DIRECTORY_SEPARATOR, "/", $address);
		if (!startsWith($address, "/")) {
			$dirs = explode("/", \_::$Address->Direction);
			$dirs = rtrim(implode("/", array_slice($dirs, 0, count($dirs) - 1)), "/");
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
		if ((empty($url)) || isAbsoluteUrl($url))
			return $url;
		if (startsWith($url, "/"))
			return \_::$Address->Host . $url;
		else {
			$dirs = explode("/", \_::$Address->Url);
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
		if (empty($url))
			return null;
		foreach (\_::$Sequence as $dir => $root)
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
	 * To normalize the internal path
	 * @param mixed $path Probable file internal path
	 */
	public static function GetAddress($path)
	{
		if (empty($path))
			return null;
		return
			ltrim(
				preg_replace(
					"/[\/\\\]+/",
					DIRECTORY_SEPARATOR,
					preg_replace(
						"/(^\w+:?\/*[^\/\\\:]+\/?)|([\?#@].*$)/",
						"",
						$path
					)
				),
				DIRECTORY_SEPARATOR
			);
	}
	/**
	 * To get absolute path from a relative one
	 * @param  $path The relative path
	 * @return string|null
	 */
	public static function GetAbsoluteAddress($path): string|null
	{
		if (empty($path))
			return null;
		foreach (\_::$Sequence as $directory => $root)
			if (startsWith($path, $directory))
				return $path;
		return \_::$Router->Directory . ltrim(self::GetAddress($path), DIRECTORY_SEPARATOR);
	}
	/**
	 * To get the relative address from a path
	 * @param  $path The path "file://D:/MyWebsite/Category/mimfa/service/web.php?p=3&l=10#serp"
	 * @return string|null "/Category/mimfa/service/web.php?p=3&l=10#serp"
	 */
	public static function GetRelativeAddress($path): string|null
	{
		if (empty($path))
			return null;
		$path = preg_replace("/^file:[\/\\\]+/","",$path);
		foreach (\_::$Sequence as $directory => $root)
			if (startsWith($path, $directory))
				return substr($path, strlen($directory));
		return $path;
	}

	/**
	 * Create a new unique address
	 * @param string $directory Root directory, leave null for a temp directory
	 * @param string $format The full format of extension (like .html)
	 * @param int $random Pass 0 or false to get the name sequential from the number 1 to infinity
	 * @return string
	 */
	public static function CreateAddress(string $fileName = "new", string $format = "", ?string $directory = null, bool $random = true): string
	{
		$directory = $directory ?? \_::$Router->TempDirectory;
		do
			$path = $directory . Convert::ToExcerpt(Convert::ToKey($fileName, true, '/[^A-Za-z0-9\_ \(\)]/'), 0, 50, "") . "-" . getId($random) . $format;
		while (file_exists(filename: $path));
		return $path;
	}

	/**
	 * Find an exists directory, then get the internal path
	 * @param mixed $path Probable file internal path
	 * @return string|null
	 */
	public static function GetDirectory($path)
	{
		$path = self::GetAddress($path);
		if (empty($path))
			return null;
		if (is_dir($path))
			return $path;
		if (startsWith($path, \_::$Router->Directory))
			$path = substr($path, strlen(\_::$Router->Directory));
		foreach (\_::$Sequence as $directory => $p)
			if (is_dir($directory . $path))
				return $directory . $path;
		return null;
	}
	public static function DirectoryExists($path): bool
	{
		return is_dir($path);
	}
	public static function CreateDirectory($directory)
	{
		$dir = "";
		if (startsWith($directory, \_::$Router->Directory))
			$directory = substr($directory, strlen($dir = \_::$Router->Directory));
		$dirs = explode(DIRECTORY_SEPARATOR, trim($directory, DIRECTORY_SEPARATOR));
		foreach ($dirs as $d)
			if (!file_exists($dir .= $d)) {
				mkdir($dir, 0777, true);
				self::CreateFile(($dir .= DIRECTORY_SEPARATOR) . "index.html", "<!--Silence is the Best-->");
			} else
				$dir .= DIRECTORY_SEPARATOR;
		return $dir;
	}
	public static function DeleteDirectory($directory)
	{
		$directory = trim($directory, "/\\");
		$i = 0;
		if (empty($directory))
			return true;
		elseif (is_dir($directory)) {
			foreach (glob($directory . DIRECTORY_SEPARATOR . '*') as $file)
				if (is_file($file) && ++$i)
					unlink($file);
				elseif (is_dir($file))
					$i += self::DeleteDirectory($file);
			if (rmdir($directory))
				$i++;
		}
		return $i;
	}
	public static function MoveDirectory($sourceDirectory, $destDirectory, $recursive = true)
	{
		if (self::CopyDirectory($sourceDirectory, $destDirectory, $recursive))
			return self::DeleteDirectory($sourceDirectory);
		return false;
	}
	public static function CopyDirectory($sourceDirectory, $destDirectory, $recursive = true): bool
	{
		set_time_limit(24 * 60 * 60);
		$b = true;
		$sourcePaths = scandir($sourceDirectory);
		if ($recursive)
			foreach ($sourcePaths as $source) {
				$bn = basename($source);
				if (is_dir($source)) {
					self::CreateDirectory($destDirectory . $bn);
					$b = self::CopyDirectory($source, $destDirectory . $bn) && $b;
				} else
					$b = self::CopyFile($source, $destDirectory . $bn) && $b;
			}
		return $b;
	}
	public static function CopyDirectories($sourceDirectories, $destDirectories, $recursive = true): bool
	{
		set_time_limit(24 * 60 * 60);
		$b = true;
		foreach ($sourceDirectories as $s_dir)
			foreach ($destDirectories as $d_dir)
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
		$path = self::GetAddress($path);
		if (empty($path))
			return null;
		if (file_exists($path))
			return $path;
		if (startsWith($path, \_::$Router->Directory))
			$path = substr($path, strlen(\_::$Router->Directory));
		foreach (\_::$Sequence as $directory => $p)
			if (file_exists($directory . $path))
				return $directory . $path;
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
		$destPath = self::GetAddress($destPath);
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
		foreach ($sourcePaths as $s_path)
			foreach ($destPaths as $d_path)
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
		if ($ifNeeds && (self::ReadText($path) === $text))
			return null;
		return file_put_contents(self::GetFile($path) ?? self::GetAddress($path), $text);
	}


	/**
	 * Get the fileobject by file key name
	 * @param mixed $inputName Posted file key name
	 * @return mixed
	 */
	public static function GetFileObject($inputName)
	{
		return receiveFile($inputName);
	}
	/**
	 * Check if the fileobject is not null or empty
	 * @param mixed $content Posted file key name or object
	 * @return mixed
	 */
	public static function IsFileObject($content)
	{
		if (is_string($content))
			$content = getReceived($content);
		return get($content, "name") ? true : false;
	}

	/**
	 * Save (Upload from the client side) something to the local storage
	 * @param mixed $content A file object or posted file key name
	 * @param mixed $directory Leave null if you want to use \_::$Router->PublicDirectory as the destination
	 * @param mixed $minSize Minimum file size in byte
	 * @param mixed $maxSize Maximum file size in byte
	 * @param mixed $extensions Acceptable extentions for example ["jpg","jpeg","png","bmp","gif","ico"]
	 * @return string Return the uploaded file path, else return null
	 */
	public static function Store($content, $directory = null, $minSize = null, $maxSize = null, ?array $extensions = null, $deleteSource = true)
	{
		if (is_string($content))
			$content = self::GetFileObject($content);
		if (!$content)
			return null;
		if (!get($content, "name"))
			return null;//throw new \SilentException("There is not any file!");
		$directory = $directory ?? \_::$Router->PublicDirectory;

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
			$dir = \_::$Router->TempDirectory;
			$t = preg_find("/^[\w-]+\b/", $content["type"] ?? "");
			if ($t)
				$dir .= $t . DIRECTORY_SEPARATOR;
		}
		$destFile = self::CreateAddress($fileName, ".$fileType", $dir);
		if (is_uploaded_file($sourceFile) && move_uploaded_file($sourceFile, $destFile))
			return $destFile;
		if (rename($sourceFile, $destFile))
			return $destFile;
			return $destFile;
		if ($deleteSource)
			unlink($sourceFile);
		throw new \SilentException("Sorry, there was an error uploading your file.");
	}
	/**
	 * Save (Upload from the client side) file to the local storage
	 * @param mixed $content A file object or posted file key name
	 * @param mixed $directory Leave null if you want to use \_::$Router->PublicDirectory as the destination
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
	 * @param mixed $directory Leave null if you want to use \_::$Router->PublicDirectory as the destination
	 * @param mixed $minSize Minimum image size in byte
	 * @param mixed $maxSize Maximum image size in byte
	 * @param mixed $extensions Acceptable image extentions (leave default for "jpg","jpeg","png","bmp","gif","ico" formats)
	 * @return string Return the uploaded image path, else return null
	 */
	public static function StoreImage($content, $directory = null, $minSize = null, $maxSize = null, ?array $extensions = null)
	{
		if (is_string($content))
			$content = self::GetFileObject($content);
		if (!$content)
			return null;
		if (!get($content, "name"))
			return null;//throw new \SilentException("There is not any file!");
		// Check if image file is an actual image or fake image
		if (getimagesize($content["tmp_name"]) === false)
			throw new \SilentException("The image file is not an actual image!");
		return self::Store($content, $directory, $minSize, $maxSize, $extensions ?? \_::$Config->AcceptableImageFormats);
	}
	/**
	 * Save (Upload from the client side) audio to the local storage
	 * @param mixed $content A file object or posted file key name
	 * @param mixed $directory Leave null if you want to use \_::$Router->PublicDirectory as the destination
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
	 * @param mixed $directory Leave null if you want to use \_::$Router->PublicDirectory as the destination
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
	 * @param mixed $directory Leave null if you want to use \_::$Router->PublicDirectory as the destination
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
		if (ob_get_level())
			ob_clean();

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

		response("\xEF\xBB\xBF");
		deliver($content);
	}
	/**
	 * Load (Download from the client side) file from the local storage,
	 * Send somthing to download
	 * @param string $path The absolute or relative path to the file.
	 * @param string|null $type The file content type (e.g., "application/pdf", "image/jpeg").
	 * @param string|null $name Optional filename to force download with a specific name.
	 */
	public static function LoadFile($path, $name = null, $type = null)
	{
		deliverFile($path, null, $type, true, $name);
	}
}