<?php
namespace MiMFa\Library;

use DateTime;

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
		$d = ltrim(preg_replace("/[\?#@].*$/", "", $address), DIRECTORY_SEPARATOR);
		$p = ltrim($address = normalizeUrl($address), "/");
		foreach (\_::$Sequence as $dir => $root)
			if (file_exists($dir . $d))
				return $root . $p;
		if (!startsWith($address, "/")) {
			$dirs = explode("/", \_::$User->Direction);
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
			return \_::$User->Host . $url;
		else {
			$dirs = explode("/", \_::$User->Url);
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
				return substr($url, strlen($root) - 1);
		return PREG_Replace("/^\w+:\/*[^\/]+/", "", $url);
	}
	/**
	 * Get the external url pointed to catch status
	 * @param mixed $url The external url
	 * @return mixed
	 */
	public static function OptimizeUrl($url)
	{
		if (!\_::$Back->CachePeriod)
			return $url;
		if (strpos($url, "?") > 0)
			$url .= "&";
		else
			$url .= "?";
		return $url . "v=" . (\_::$Back->CachePeriod == "v" ? \_::$Version : date(\_::$Back->CachePeriod));
	}

	/**
	 * To convert a file to its Data URI
	 * @param mixed $path Probable file internal path
	 */
	public static function GetDataUri($path, $mime = null)
	{
		$path = self::GetFile($path);
		if (!$path)
			return null;
		$mime = $mime ?? mime_content_type($path);
		return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
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
			preg_replace(
				"/[\/\\\]+/",
				DIRECTORY_SEPARATOR,
				preg_replace(
					"/(^\w+:\/\/[^\/\\]+\/?)|([\?#@].*$)/",
					"",
					$path
				)
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
		$path = preg_match("/^[a-z]+\:/i", $path) ? $path :
			(preg_match("/^[\/\\\]/i", $path) ? $GLOBALS["ROOT"] . ltrim($path, "\\\/") : (\_::$Address->Address . $path));
		foreach (\_::$Sequence as $directory => $root)
			if (startsWith($path, $directory))
				return $path;
			elseif (startsWith($path, $root))
				return \_::$Address->Directory . ltrim(self::GetAddress(substr($path, strlen($root))), DIRECTORY_SEPARATOR);
		if (preg_match("/^[a-z]+\:/i", $path))
			return self::GetAddress($path);
		return \_::$Address->Directory . ltrim(self::GetAddress($path), DIRECTORY_SEPARATOR);
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
		$path = preg_replace("/^file:[\/\\\]+/", "", $path);
		foreach (\_::$Sequence as $directory => $root)
			if (startsWith($path, $directory))
				return substr($path, strlen($directory));
			elseif (startsWith($path, $root))
				return self::GetAddress(substr($path, strlen($root)));
		return $path;
	}

	/**
	 * Generate a new unique address
	 * @param string $directory Root directory, leave null for a temp directory
	 * @param string $format The full format of extension (like .html)
	 * @param int $random Pass 0 or false to get the name sequential from the number 1 to infinity
	 * @return string
	 */
	public static function GenerateAddress(string $fileName = "new", string $format = "", string|null $directory = null, bool $random = true): string
	{
		$directory = $directory ?: \_::$Address->TempAddress;
		if(endswith($fileName, $format))
			$fileName = substr($fileName, 0, strlen($fileName)-strlen($format));
		do
			$path = $directory . Convert::ToExcerpt(Convert::ToKey($fileName, true, '/[^A-Za-z0-9\_ \(\)]/'), 0, 50, "") . "-" . getId($random) . $format;
		while (file_exists($path));
		return $path;
	}
	/**
	 * Generate a new Organized Directory
	 * @param string|null $rootDirectory Root directory, leave null for a public directory
	 * @return string
	 */
	public static function GenerateOrganizedDirectory(string|null $rootDirectory = null): string
	{
		return self::CreateDirectory(($rootDirectory ?: \_::$Address->PublicAddress) . date("Y") . DIRECTORY_SEPARATOR . date("m") . DIRECTORY_SEPARATOR);
	}

	public static function SanitizeName(string $name): string
	{
		return preg_replace("/[^A-Z0-9._\- \(\)]/iu", '_', $name);
	}

	/**
	 * Find an exists file or directory, then get the internal path
	 * @param mixed $address Probable file or directory internal path
	 * @return string|null
	 */
	public static function Get($address)
	{
		$address = self::GetAddress($address);
		if (!$address)
			return null;
		return is_dir($address) ?
			self::GetDirectory($address) :
			self::GetFile($address);
	}
	public static function Exists($address): bool
	{
		$address = self::GetAddress($address);
		if (!$address)
			return false;
		return is_dir($address) ?
			self::DirectoryExists($address) :
			self::FileExists($address);
	}
	public static function Delete($address)
	{
		$address = self::GetAddress($address);
		if (!$address)
			return null;
		return is_dir($address) ?
			self::DeleteDirectory($address) :
			self::DeleteFile($address);
	}
	public static function Rename($address, $newName): bool
	{
		$address = self::GetAddress($address);
		if (!$address || empty($newName))
			return false;
		return is_dir($address) ?
			self::RenameDirectory($address, $newName) :
			self::RenameFile($address, $newName);
	}
	public static function Move($sourceAddress, $destAddress): bool
	{
		$sourceAddress = self::GetAddress($sourceAddress);
		if (!$sourceAddress)
			return false;
		return is_dir($sourceAddress) ?
			self::MoveDirectory($sourceAddress, $destAddress) :
			self::MoveFile($sourceAddress, $destAddress);
	}
	public static function Copy($sourceAddress, $destAddress): bool
	{
		$sourceAddress = self::GetAddress($sourceAddress);
		if (!$sourceAddress)
			return false;
		return is_dir($sourceAddress) ?
			self::CopyDirectory($sourceAddress, $destAddress) :
			self::CopyFile($sourceAddress, $destAddress);
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
		if (startsWith($path, \_::$Address->Address))
			$path = substr($path, strlen(\_::$Address->Address));
		foreach (\_::$Sequence as $directory => $p)
			if (is_dir($directory . $path))
				return $directory . $path;
		return null;
	}
	public static function DirectoryExists($path): bool
	{
		return is_dir($path);
	}
	/**
	 * To Create all Directories recursively
	 * @param mixed $directory
	 * @param int $permissions The permissions are 0777 by default, which means the widest possible access.
	 * For more information on permissions, read the details on the chmod() page
	 * @return string|null The last created directory
	 */
	public static function CreateDirectory($directory, $permissions = 0777)
	{
		$dir = "";
		$directory = self::GetAddress($directory);
		if (startsWith($directory, \_::$Address->Address))
			$directory = substr($directory, strlen($dir = \_::$Address->Address));
		elseif (startsWith($directory, DIRECTORY_SEPARATOR))
			$dir = DIRECTORY_SEPARATOR;
		$dirs = explode(DIRECTORY_SEPARATOR, trim($directory, DIRECTORY_SEPARATOR));
		foreach ($dirs as $d)
			if (!is_dir($dir .= $d)) {
				mkdir($dir, $permissions, true);
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
	public static function RenameDirectory(string $sourceDirectory, string $newName)
	{
		$dir = dirname(rtrim($sourceDirectory, "\\\/"));
		$newDirectory = $dir . DIRECTORY_SEPARATOR . self::SanitizeName($newName) . DIRECTORY_SEPARATOR;
		return self::MoveDirectory($sourceDirectory, $newDirectory) ? true : false;
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
				if (is_dir($source))
					$b = self::CopyDirectory($source, self::CreateDirectory($destDirectory . $bn)) && $b;
				else
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
	public static function GetDirectoryContents(string $directory): array
	{
		if (!is_dir($directory))
			return [];
		$items = [];
		foreach (scandir($directory) as $name) {
			if ($name === '.' || $name === '..')
				continue;
			if (is_dir($name = $directory . $name))
				$items[] = $directory . $name . DIRECTORY_SEPARATOR;
			else
				$items[] = $directory . $name;
		}
		return $items;
	}
	public static function GetDirectoryItems(string $directory): \Generator
	{
		$nd = rtrim($directory, "/\/");
		if (!is_dir($nd))
			return;
		foreach (scandir($nd) as $name) {
			if ($name === '.' || $name === '..')
				continue;
			$fullPath = $directory . $name;
			$stat = stat($fullPath);
			yield [
				"Name" => $name,
				"IsDirectory" => $is_dir = is_dir($fullPath),
				"Directory" => $directory,
				"Path" => $fullPath . ($is_dir ? DIRECTORY_SEPARATOR : null),
				"Size" => $stat['size'],
				"Id" => $stat['uid'],
				"MimeType" => $is_dir ? "Directory" : (function_exists("mime_content_type") ? mime_content_type($fullPath) : (strtoupper(preg_find("/(?<=\.)[a-z0-9]+$/", $name) ?? "") ?: "Unknown")),
				"CreateTime" => new DateTime(Date("Y-m-d H:i:s", $stat['ctime'] ?? 0)),
				"UpdateTime" => new DateTime(Date("Y-m-d H:i:s", $stat['mtime'] ?? 0))
			];
		}
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
		if (startsWith($path, \_::$Address->Address))
			$path = substr($path, strlen(\_::$Address->Address));
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
		//return file_put_contents($path, $content??"");
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
	public static function RenameFile(string $sourcePath, string $newName)
	{
		$dir = dirname($sourcePath);
		$newPath = $dir . DIRECTORY_SEPARATOR . self::SanitizeName($newName);
		return self::MoveFile($sourcePath, $newPath) ? true : false;
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

	/**
	 * To reads entire file into a string
	 * This function is similar to file(),
	 * except that this returns the file in a string,
	 * starting at the specified offset up to length bytes. On failure, this will return false.
	 * @param string|null $path The relative file or directory path
	 * @param int $depth How much layers it should iterate to find the correct address
	 * @param int $offset [optional] The offset where the reading starts.
	 * @param int|null $length [optional] Maximum length of data read. The default is to read until end of file is reached.
	 * @return string|false|null The function returns the read data or flse on failure or null if could not find the file.
	 */
	public static function GetFileContent(string|null $path = null, int $offset = 0, int|null $length = null)
	{
		return $path ? file_get_contents(self::GetFile($path), offset: $offset, length: $length) : null;
	}
	/**
	 * To write data into an exists file or the new file
	 * @param string $path The relative file path
	 * @param $data The data to write. Can be either a string, an array or a each other data types.
	 * @return string|false|null The function returns the number of bytes that were written to the file, or false on failure.
	 */
	public static function SetFileContent(string $path, $data = null, int $flags = 0)
	{
		return file_put_contents(self::GetAbsoluteAddress($path), Convert::ToString($data), flags: $flags);
	}


	/**
	 * Check if the fileobject is not null or empty
	 * @param mixed $object Posted file key name or object
	 * @return mixed
	 */
	public static function IsFileObject(&$object)
	{
		if (!$object)
			return false;
		if (is_string($object))
			$object = receiveFile($object);
		return isset($object["tmp_name"]) && isset($object["name"]) && isset($object["size"]) && $object["size"];
	}

	/**
	 * Save temporary (Upload from the client side) something to the local storage
	 * @param mixed $object A file object or posted file key name
	 * @param mixed $minSize Minimum file size in byte
	 * @param mixed $maxSize Maximum file size in byte
	 * @param mixed $extensions Acceptable extentions for example ["jpg","jpeg","png","bmp","gif","ico"]
	 * @return string Return the uploaded file path, else return null
	 */
	public static function Temp($object, $minSize = null, $maxSize = null, ?array $extensions = null)
	{
		return self::Store($object, \_::$Address->TempAddress, $minSize, $maxSize, $extensions, true);
	}
	/**
	 * Save (Upload from the client side) something to the local storage
	 * @param mixed $object A file object or posted file key name
	 * @param mixed $directory Leave null if you want to use \_::$Address->PublicAddress as the destination
	 * @param mixed $minSize Minimum file size in byte
	 * @param mixed $maxSize Maximum file size in byte
	 * @param mixed $extensions Acceptable extentions for example ["jpg","jpeg","png","bmp","gif","ico"]
	 * @return string Return the uploaded file path, else return null
	 */
	public static function Store($object, $directory = null, $minSize = null, $maxSize = null, ?array $extensions = null, $deleteSource = true)
	{
		$minSize = $minSize ?? \_::$Back->MinimumFileSize;
		$maxSize = $maxSize ?? \_::$Back->MaximumFileSize;

		if (!self::IsFileObject($object)) {
			if (!$object)
				return null;//throw new \SilentException("There is not any file!");
			// $objectsize = sizeof($object);
			// if ($objectsize < $minSize)
			// 	throw new \SilentException("The 'file size' is 'very small'!");
			// elseif ($objectsize > $maxSize)
			// 	throw new \SilentException("The 'file size' is 'very big'!");
			if (self::SetFileContent($path = self::GenerateAddress(directory: $directory, format: first($extensions) ?? ""), $object))
				return $path;
			return null;
		}

		$fileType = strtolower(pathinfo($object["name"], PATHINFO_EXTENSION));
		$fileName = strtolower(pathinfo($object["name"], PATHINFO_FILENAME)) . "_";

		// Allow certain file formats
		$allow = true;
		$dfileType = "." . $fileType;
		foreach (($extensions ?? \_::$Back->GetAcceptableFormats()) as $ext)
			if ($allow = ($fileType === $ext || $dfileType === $ext))
				break;
		$sourceFile = $object["tmp_name"];
		if (!$allow) {
			if ($deleteSource)
				self::DeleteFile($sourceFile);
			throw new \SilentException("The 'file format' is not 'acceptable'!");
		}
		// Check file size
		if ($object["size"] < $minSize) {
			if ($deleteSource)
				self::DeleteFile($sourceFile);
			throw new \SilentException("The 'file size' is 'very small'!");
		} elseif ($object["size"] > $maxSize) {
			if ($deleteSource)
				self::DeleteFile($sourceFile);
			throw new \SilentException("The 'file size' is 'very big'!");
		}

		if ($directory === false) {
			$directory = \_::$Address->TempAddress;
			$t = preg_find("/^[\w-]+\b/", $object["type"] ?? "");
			if ($t)
				$directory .= $t . DIRECTORY_SEPARATOR;
		} elseif (!$directory)
			$directory = self::GenerateOrganizedDirectory();
		else
			$directory = self::CreateDirectory($directory);

		$destFile = self::GenerateAddress($fileName, ".$fileType", $directory);
		if (is_uploaded_file($sourceFile) && move_uploaded_file($sourceFile, $destFile))
			return $destFile;
		if (rename($sourceFile, $destFile))
			return $destFile;
		return $destFile;
		if ($deleteSource)
			self::DeleteFile($sourceFile);
		throw new \SilentException("Sorry, there was an error on 'uploading your file'.");
	}
	/**
	 * Save (Upload from the client side) file to the local storage
	 * @param mixed $object A file object or posted file key name
	 * @param mixed $directory Leave null if you want to use \_::$Address->PublicAddress as the destination
	 * @param mixed $minSize Minimum file size in byte
	 * @param mixed $maxSize Maximum file size in byte
	 * @param mixed $extensions Acceptable extentions for example ["jpg","jpeg","png","bmp","gif","ico"]
	 * @return string Return the uploaded file path, else return null
	 */
	public static function StoreFile($object, $directory = null, $minSize = null, $maxSize = null, ?array $extensions = null)
	{
		return self::Store($object, $directory, $minSize, $maxSize, $extensions ?? \_::$Back->AcceptableFileFormats);
	}
	/**
	 * Save (Upload from the client side) image to the local storage
	 * @param mixed $object An image object or posted file key name
	 * @param mixed $directory Leave null if you want to use \_::$Address->PublicAddress as the destination
	 * @param mixed $minSize Minimum image size in byte
	 * @param mixed $maxSize Maximum image size in byte
	 * @param mixed $extensions Acceptable image extentions (leave default for "jpg","jpeg","png","bmp","gif","ico" formats)
	 * @return string Return the uploaded image path, else return null
	 */
	public static function StoreImage($object, $directory = null, $minSize = null, $maxSize = null, ?array $extensions = null)
	{
		if (!self::IsFileObject($object))
			return self::Store($object, $directory, $minSize, $maxSize, $extensions ?? \_::$Back->AcceptableImageFormats);
		// Check if image file is an actual image or fake image
		if ((($object["tmp_name"] ?? null) ? getimagesize($object["tmp_name"]) : true) === false)
			throw new \SilentException("The image file is not an actual image!");
		return self::Store($object, $directory, $minSize, $maxSize, $extensions ?? \_::$Back->AcceptableImageFormats);
	}
	/**
	 * Save (Upload from the client side) audio to the local storage
	 * @param mixed $object A file object or posted file key name
	 * @param mixed $directory Leave null if you want to use \_::$Address->PublicAddress as the destination
	 * @param mixed $minSize Minimum file size in byte
	 * @param mixed $maxSize Maximum file size in byte
	 * @param mixed $extensions Acceptable extentions for example ["jpg","jpeg","png","bmp","gif","ico"]
	 * @return string Return the uploaded file path, else return null
	 */
	public static function StoreAudio($object, $directory = null, $minSize = null, $maxSize = null, ?array $extensions = null)
	{
		return self::Store($object, $directory, $minSize, $maxSize, $extensions ?? \_::$Back->AcceptableAudioFormats);
	}
	/**
	 * Save (Upload from the client side) video to the local storage
	 * @param mixed $object A file object or posted file key name
	 * @param mixed $directory Leave null if you want to use \_::$Address->PublicAddress as the destination
	 * @param mixed $minSize Minimum file size in byte
	 * @param mixed $maxSize Maximum file size in byte
	 * @param mixed $extensions Acceptable extentions for example ["jpg","jpeg","png","bmp","gif","ico"]
	 * @return string Return the uploaded file path, else return null
	 */
	public static function StoreVideo($object, $directory = null, $minSize = null, $maxSize = null, ?array $extensions = null)
	{
		return self::Store($object, $directory, $minSize, $maxSize, $extensions ?? \_::$Back->AcceptableVideoFormats);
	}
	/**
	 * Save (Upload from the client side) document to the local storage
	 * @param mixed $object A file object or posted file key name
	 * @param mixed $directory Leave null if you want to use \_::$Address->PublicAddress as the destination
	 * @param mixed $minSize Minimum file size in byte
	 * @param mixed $maxSize Maximum file size in byte
	 * @param mixed $extensions Acceptable extentions for example ["jpg","jpeg","png","bmp","gif","ico"]
	 * @return string Return the uploaded file path, else return null
	 */
	public static function StoreDocument($object, $directory = null, $minSize = null, $maxSize = null, ?array $extensions = null)
	{
		return self::Store($object, $directory, $minSize, $maxSize, $extensions ?? \_::$Back->AcceptableDocumentFormats);
	}
	/**
	 * Load (Download from the client side) something from the local storage,
	 * Send somthing to download
	 * @param mixed $content The content of the loaded file
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

		// ini_set('mbstring.internal_encoding', \_::$Front->Encoding);//deprecated
		// ini_set('mbstring.http_input', 'auto');//deprecated
		// ini_set('mbstring.http_output', \_::$Front->Encoding);//deprecated
		ini_set('mbstring.detect_order', 'auto');
		ini_set('default_charset', \_::$Front->Encoding);

		header("Content-Disposition: attachment; filename=\"$name\"");
		header("Content-Type: application/force-download");
		header("Content-Type: $type; charset=" . \_::$Front->Encoding);
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