<?php
namespace MiMFa\Library;

use DateTime;
use RecursiveDirectoryIterator;

/**
 * A simple library to work by the local files and folders
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#storage See the Library Documentation
 */
class Storage
{
	/**
	 * Get or Find a file, then get the external url
	 * @param string|null $path Probable file external url or path
	 * @return mixed
	 */
	public static function GetUrl($path)
	{
		if ((empty($path)) || isAbsoluteUrl($path))
			return $path;
		$path = self::GetRelativePath(str_replace(["/", "\\"], DIRECTORY_SEPARATOR, $path));
		$d = ltrim(preg_replace("/[\?#@].*$/", "", $path), DIRECTORY_SEPARATOR);
		$p = ltrim($path = normalizeUrl($path), "/");
		foreach (\_::$Sequence as $dir => $root)
			if (file_exists($dir . $d))
				return $root . $p;
		if (!startsWith($path, "/")) {
			$dirs = explode("/", \_::$Address->UrlRoute);
			$dirs = rtrim(implode("/", array_slice($dirs, 0, count($dirs) - 1)), "/");
			if (strlen($dirs) !== 0)
				$path = "$dirs/$path";
		}
		return $path;
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
			return \_::$Address->UrlOrigin . $url;
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
		$path = self::FindFile($path);
		if (!$path)
			return null;
		$mime = $mime ?? mime_content_type($path);
		return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
	}

	/**
	 * To normalize the internal path
	 * @param mixed $url Probable file internal path
	 */
	public static function GetPath($url)
	{
		if (empty($url))
			return null;
		return
			preg_replace(
				"/[\/\\\]+/",
				DIRECTORY_SEPARATOR,
				preg_replace(
					"/(^\w+:\/\/[^\/\\\]+\/?)|([\?#@].*$)/",
					"",
					$url
				)
			);
	}
	/**
	 * To get absolute path from a relative one
	 * @param  $path The relative address
	 * @return string|null
	 */
	public static function GetAbsolutePath($path): string|null
	{
		if (empty($path))
			return null;
		$path = preg_match("/^[a-z]+\:/i", $path) ? $path :
			(preg_match("/^[\/\\\]/i", $path) ? $GLOBALS["ROOT"] . ltrim($path, "\\\/") : (\_::$Address->Directory . $path));
		foreach (\_::$Sequence as $directory => $root)
			if (startsWith($path, $directory))
				return $path;
			elseif (startsWith($path, $root))
				return \_::$Address->GlobalDirectory . ltrim(self::GetPath(substr($path, strlen($root))), DIRECTORY_SEPARATOR);
		if (preg_match("/^[a-z]+\:/i", $path))
			return self::GetPath($path);
		return \_::$Address->GlobalDirectory . ltrim(self::GetPath($path), DIRECTORY_SEPARATOR);
	}
	/**
	 * To get the relative address from a path
	 * @param  $path The path "file://D:/MyWebsite/Category/mimfa/service/web.php?p=3&l=10#serp"
	 * @return string|null "/Category/mimfa/service/web.php?p=3&l=10#serp"
	 */
	public static function GetRelativePath($path): string|null
	{
		if (empty($path))
			return null;
		$path = preg_replace("/^file:[\/\\\]+/", "", $path);
		foreach (\_::$Sequence as $directory => $root)
			if (startsWith($path, $directory))
				return substr($path, strlen($directory));
			elseif (startsWith($path, $root))
				return self::GetPath(substr($path, strlen($root)));
		return $path;
	}

	/**
	 * Generate a new unique directory or file path
	 * @param string $destDirectory Root directory, leave null for a temp directory
	 * @param string $prefix The file or directory's name or prefix
	 * @param string $suffix The full format of extension (like .html)
	 * @param int $random Pass 0 or false to get the name sequential from the number 1 to infinity
	 * @return string
	 */
	public static function GenerateUniquePath(string|null $destDirectory = null, string $prefix = "new", string $suffix = "", bool $random = false): string
	{
		$directory = $destDirectory ?: \_::$Address->TempDirectory;
		if (endswith($prefix, $suffix))
			$prefix = substr($prefix, 0, strlen($prefix) - strlen($suffix));
		$name = null;
		$prefix = Convert::ToExcerpt(self::SanitizeName($prefix), 0, 50, "");
		do {
			$path = $directory . $prefix . $name . $suffix;
			$name = "-" . getId($random);
		} while (file_exists($path));
		return $path;
	}
	/**
	 * To generate then create a new Unique Directory
	 * @param string|null $destDirectory Root directory, leave null for a public directory
	 * @return string
	 */
	public static function CreateUniqueDirectory(string|null $destDirectory = null, string $prefix = "", string $suffix = "", $random = false): string
	{
		return self::CreateDirectory(self::GenerateUniquePath($destDirectory, $prefix, $suffix.DIRECTORY_SEPARATOR, $random));
	}
	/**
	 * Generate a new Organized Directory or File path
	 * @param string|null $destDirectory Root directory, leave null for a public directory
	 * @return string
	 */
	public static function GenerateOrganizedPath(string|null $destDirectory = null, string $prefix = "", string $suffix = ""): string
	{
		return self::CreateDirectory(($destDirectory ?: \_::$Address->PublicDirectory) . date("Y") . DIRECTORY_SEPARATOR . $prefix . date("m") . $suffix);
	}
	/**
	 * To generate then create a new Organized Directory
	 * @param string|null $destDirectory Root directory, leave null for a public directory
	 * @return string
	 */
	public static function CreateOrganizedDirectory(string|null $destDirectory = null, string $prefix = "", string $suffix = ""): string
	{
		return self::CreateDirectory(self::GenerateOrganizedPath($destDirectory, $prefix, $suffix.DIRECTORY_SEPARATOR));
	}

	public static function SanitizeName(string $name): string
	{
		return preg_replace("/[^A-Za-z0-9._\- \(\)]/iu", '_', $name);
	}

	/**
	 * Find an exists file or directory, then get the internal path
	 * @param mixed $path Probable file or directory internal path
	 * @return string|null
	 */
	public static function Find($path)
	{
		$path = self::GetPath($path);
		if (!$path)
			return null;
		return is_dir(rtrim($path, "/\\")) ?
			self::FindDirectory($path) :
			self::FindFile($path);
	}
	public static function Delete($path)
	{
		$path = self::GetPath($path);
		if (!$path)
			return null;
		return is_dir(rtrim($path, "/\\")) ?
			self::DeleteDirectory($path) :
			self::DeleteFile($path);
	}
	public static function Rename($path, $newName): bool
	{
		$path = self::GetPath($path);
		if (!$path || empty($newName))
			return false;
		return is_dir(rtrim($path, "/\\")) ?
			self::RenameDirectory($path, $newName) :
			self::RenameFile($path, $newName);
	}
	public static function Move($sourceAddress, $destAddress): bool
	{
		$sourceAddress = self::GetPath($sourceAddress);
		if (!$sourceAddress)
			return false;
		return is_dir(rtrim($sourceAddress, "/\\")) ?
			self::MoveDirectory($sourceAddress, $destAddress) :
			self::MoveFile($sourceAddress, $destAddress);
	}
	public static function Copy($sourceAddress, $destAddress): bool
	{
		$sourceAddress = self::GetPath($sourceAddress);
		if (!$sourceAddress)
			return false;
		return is_dir(rtrim($sourceAddress, "/\\")) ?
			self::CopyDirectory($sourceAddress, $destAddress) :
			self::CopyFile($sourceAddress, $destAddress);
	}

	/**
	 * Find an exists directory, then get the internal path
	 * @param mixed $path Probable file internal path
	 * @return string|null
	 */
	public static function FindDirectory($directory)
	{
		$directory = self::GetPath(rtrim($directory, "\\\/"));
		if (empty($directory))
			return null;
		if (is_dir($directory))
			return $directory . DIRECTORY_SEPARATOR;
		if (startsWith($directory, \_::$Address->Directory))
			$directory = substr($directory, strlen(\_::$Address->Directory));
		foreach (\_::$Sequence as $dir => $p)
			if (is_dir($dir . $directory))
				return $dir . $directory . DIRECTORY_SEPARATOR;
		return null;
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
		$directory = self::GetPath($directory);
		if (startsWith($directory, \_::$Address->Directory))
			$directory = substr($directory, strlen($dir = \_::$Address->Directory));
		elseif (startsWith($directory, DIRECTORY_SEPARATOR))
			$dir = DIRECTORY_SEPARATOR;
		$dirs = explode(DIRECTORY_SEPARATOR, trim($directory, DIRECTORY_SEPARATOR));
		foreach ($dirs as $d)
			if (is_dir($dir .= $d))
				$dir .= DIRECTORY_SEPARATOR;
			else {
				mkdir($dir, $permissions, true);
				self::CreateFile(($dir .= DIRECTORY_SEPARATOR) . "index.html", "<!--Silence is the Best-->");
			}
		return $dir;
	}
	public static function DeleteDirectory($directory)
	{
		$directory = rtrim($directory, "/\\");
		$i = 0;
		if (empty($directory))
			return true;
		elseif (is_dir(filename: $directory)) {
			foreach (scandir($directory, SCANDIR_SORT_NONE) as $item) {
				if ($item === '.' || $item === '..')
					continue;
				$path = $directory . DIRECTORY_SEPARATOR . $item;
				if (is_file($path) && ++$i)
					unlink($path);
				elseif (is_dir($path))
					$i += self::DeleteDirectory($path);
			}
			if (rmdir($directory))
				$i++;
		}
		return $i;
	}
	public static function RenameDirectory(string $directory, string $newName)
	{
		$newDirectory = Storage::GenerateUniquePath(dirname($directory = rtrim($directory, "\\\/")) . DIRECTORY_SEPARATOR, $newName);
		return rename($directory, $newDirectory);
	}
	public static function MoveDirectory($sourceDirectory, $destDirectory)
	{
		$sourceDirectory = rtrim($sourceDirectory, "\\\/");
		$destDirectory = rtrim($destDirectory, "\\\/");
		self::CreateDirectory(dirname($destDirectory));
		return rename($sourceDirectory, $destDirectory);
	}
	public static function CopyDirectory($sourceDirectory, $destDirectory): bool
	{
		$sourceDirectory = rtrim($sourceDirectory, "\\\/");
		$destDirectory = rtrim($destDirectory, "\\\/");
		self::CreateDirectory(dirname($destDirectory));
		if (!is_dir($sourceDirectory))
			return false;
		if (!is_dir($destDirectory))
			mkdir($destDirectory);
		foreach (scandir($sourceDirectory, SCANDIR_SORT_NONE) as $item) {
			if ($item === '.' || $item === '..')
				continue;
			$sourceItem = $sourceDirectory . DIRECTORY_SEPARATOR . $item;
			$destItem = $destDirectory . DIRECTORY_SEPARATOR . $item;
			if (is_dir($sourceItem)) {
				if (!self::CopyDirectory($sourceItem, $destItem))
					return false;
			} elseif (!copy($sourceItem, $destItem))
				return false;
		}
		return true;
	}
	public static function CopyDirectories($sourceDirectories, $destDirectories): bool
	{
		set_time_limit(24 * 60 * 60);
		$b = true;
		foreach ($sourceDirectories as $s_dir)
			foreach ($destDirectories as $d_dir)
				$b = self::CopyDirectory($s_dir, $d_dir) && $b;
		return $b;
	}
	public static function GetDirectoryContents(string $directory): array
	{
		if (!is_dir($directory))
			return [];
		$items = [];
		$files = new \DirectoryIterator($directory);
		foreach ($files as $file) {
			$name = $file->getFilename();
			if ($name === '.' || $name === '..')
				continue;
			if ($file->isDir())
				$items[] = $file->getPathname() . DIRECTORY_SEPARATOR;
			else
				$items[] = $file->getPathname();
		}
		return $items;
	}
	public static function GetDirectoryItems(string $directory): \Generator
	{
		$nd = rtrim($directory, "/\/");
		if (!is_dir($nd))
			return;
		$files = new \DirectoryIterator($directory);
		foreach ($files as $file) {
			$name = $file->getFilename();
			if ($name === '.' || $name === '..')
				continue;
			$fullPath = $file->getPathname();
			yield [
				"Name" => $name,
				"IsDirectory" => $is_dir = $file->isDir(),
				"Directory" => $directory,
				"Path" => $fullPath . ($is_dir ? DIRECTORY_SEPARATOR : null),
				"Size" => $file->getSize(),
				"MimeType" => $is_dir ? "Directory" : (function_exists("mime_content_type") ? mime_content_type($fullPath) : (strtoupper(preg_find("/(?<=\.)[a-z0-9]+$/", $name) ?? "") ?: "Unknown")),
				"CreateTime" => new DateTime(Date(\_::$Front->DateTimeFormat ?? "Y-m-d H:i:s", $file->getCTime() ?? 0)),
				"UpdateTime" => new DateTime(Date(\_::$Front->DateTimeFormat ?? "Y-m-d H:i:s", $file->getMTime() ?? 0)),
				"AccessTime" => new DateTime(Date(\_::$Front->DateTimeFormat ?? "Y-m-d H:i:s", $file->getATime() ?? 0))
			];
		}
	}


	/**
	 * Find an exists file, then get the internal path
	 * @param mixed $path Probable file internal path
	 * @return string|null
	 */
	public static function FindFile($path)
	{
		$path = self::GetPath($path);
		if (empty($path))
			return null;
		if (file_exists($path))
			return $path;
		if (startsWith($path, \_::$Address->Directory))
			$path = substr($path, strlen(\_::$Address->Directory));
		foreach (\_::$Sequence as $directory => $p)
			if (file_exists($directory . $path))
				return $directory . $path;
		return null;
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
		$path = self::FindFile($path);
		return empty($path) || unlink($path);
	}
	public static function RenameFile(string $path, string $newName)
	{
		$extension = preg_find("/(\.[^.]*)$/u", $newName) ?? "";
		$newName = $extension ? substr($newName, 0, -strlen($extension)) : $newName;
		$newPath = Storage::GenerateUniquePath(dirname($path) . DIRECTORY_SEPARATOR, $newName, $extension);
		return rename($path, $newPath);
	}
	public static function MoveFile($sourcePath, $destPath): bool
	{
		if (self::CopyFile($sourcePath, $destPath))
			return self::DeleteFile($sourcePath);
		return false;
	}
	public static function CopyFile($sourcePath, $destPath): bool
	{
		$sourcePath = self::FindFile($sourcePath);
		$destPath = self::GetPath($destPath);
		return copy($sourcePath, $destPath);
	}
	public static function CopyFiles($sourcePaths, $destPaths): bool
	{
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
		return $path ? file_get_contents(self::FindFile($path), offset: $offset, length: $length) : null;
	}
	/**
	 * To write data into an exists file or the new file
	 * @param string $path The relative file path
	 * @param $data The data to write. Can be either a string, an array or a each other data types.
	 * @return string|false|null The function returns the number of bytes that were written to the file, or false on failure.
	 */
	public static function SetFileContent(string $path, $data = null, int $flags = 0)
	{
		return file_put_contents(self::GetAbsolutePath($path), Convert::ToString($data), flags: $flags);
	}
	/**
	 * To prepend data into an exists file or the new file
	 * @param string $path The relative file path
	 * @param $data The data to write. Can be either a string, an array or a each other data types.
	 * @return string|false|null The function returns the number of bytes that were written to the file, or false on failure.
	 */
	public static function PrependFileContent(string $path, $data = null)
	{
		return file_put_contents($path = self::GetAbsolutePath($path), Convert::ToString($data).(file_exists($path)?file_get_contents($path):""));
	}
	/**
	 * To append data into an exists file or the new file
	 * @param string $path The relative file path
	 * @param $data The data to write. Can be either a string, an array or a each other data types.
	 * @return string|false|null The function returns the number of bytes that were written to the file, or false on failure.
	 */
	public static function AppendFileContent(string $path, $data = null)
	{
		return file_put_contents(self::GetAbsolutePath($path), Convert::ToString($data), flags: FILE_APPEND);
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
		return self::Store($object, \_::$Address->TempDirectory, $minSize, $maxSize, $extensions, true);
	}
	/**
	 * Save (Upload from the client side) something to the local storage
	 * @param mixed $object A file object or posted file key name
	 * @param mixed $directory Leave null if you want to use \_::$Address->PublicDirectory as the destination
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
			if (self::SetFileContent($path = self::GenerateUniquePath($directory, "new", first($extensions) ?? ""), $object))
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
			throw new \SilentException("The 'file size' is 'smaller than' " . Convert::ToCompactNumber($minSize) . "B!");
		} elseif ($object["size"] > $maxSize) {
			if ($deleteSource)
				self::DeleteFile($sourceFile);
			throw new \SilentException("The 'file size' is 'bigger than' " . Convert::ToCompactNumber($maxSize) . "B!");
		}

		if ($directory === false) {
			$directory = \_::$Address->TempDirectory;
			$t = preg_find("/^[\w-]+\b/", $object["type"] ?? "");
			if ($t)
				$directory .= $t . DIRECTORY_SEPARATOR;
		} elseif (!$directory)
			$directory = self::CreateOrganizedDirectory();
		else
			$directory = self::CreateDirectory($directory);

		$destFile = self::GenerateUniquePath($directory, $fileName, ".$fileType");
		if (is_uploaded_file($sourceFile) && move_uploaded_file($sourceFile, $destFile))
			return $destFile;
		if (file_exists($sourceFile) && rename($sourceFile, $destFile))
			return $destFile;
		return $destFile;
		if ($deleteSource)
			self::DeleteFile($sourceFile);
		throw new \SilentException("Sorry, there was an error on 'uploading your file'.");
	}
	/**
	 * Save (Upload from the client side) file to the local storage
	 * @param mixed $object A file object or posted file key name
	 * @param mixed $directory Leave null if you want to use \_::$Address->PublicDirectory as the destination
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
	 * @param mixed $directory Leave null if you want to use \_::$Address->PublicDirectory as the destination
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
	 * @param mixed $directory Leave null if you want to use \_::$Address->PublicDirectory as the destination
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
	 * @param mixed $directory Leave null if you want to use \_::$Address->PublicDirectory as the destination
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
	 * @param mixed $directory Leave null if you want to use \_::$Address->PublicDirectory as the destination
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
	 * Compress files and folders paths syncronusly into a zip file
	 * @param string $destPath The output zip file path
	 * @param array $paths The input files and folders paths
	 * @return array The list of files added to the zip
	 */
	public static function Compress(string $destPath, ...$paths)
	{
		return iterator_to_array(self::CompressIterator($destPath, ...$paths));
	}
	/**
	 * Compress files and folders paths asyncronusly into a zip file
	 * @param string $destPath The output zip file path
	 * @param array $paths The input files and folders paths
	 * @return \Generator<mixed, string, mixed, void> The list of files added to the zip
	 */
	public static function CompressIterator(string $destPath, ...$paths)
	{
		// Simple in-app zip using PHP's ZipArchive
		$destPath = Storage::GetAbsolutePath($destPath);
		$zipDir = dirname($destPath);
		if (!is_dir($zipDir))
			Storage::CreateDirectory($zipDir);

		$zip = new \ZipArchive();
		$zip->open($destPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

		$parent = self::ParentDirectory(...$paths);
		foreach ($paths as $p) {
			$full = rtrim(Storage::GetAbsolutePath($p), DIRECTORY_SEPARATOR);
			$root = rtrim(str_replace($parent, "", $p), DIRECTORY_SEPARATOR);
			if (is_dir($full)) {
				$files = new \RecursiveIteratorIterator(
					new \RecursiveDirectoryIterator($full),
					\RecursiveIteratorIterator::SELF_FIRST
				);
				foreach ($files as $file) {
					$name = $file->getFilename();
					if ($name === '.' || $name === '..')
						continue;
					$path = $file->getPathname();
					if ($file->isDir())
						$zip->addEmptyDir(str_replace($full, $root, $path));
					else
						$zip->addFile($path, str_replace($full, $root, $path));
					yield $path;
				}
			} else {
				$zip->addFile($full, $root);
				yield $full;
			}
		}

		$zip->close();
	}
	/**
	 * Decompress a zip file syncronusly into files and folders
	 * @param string $sourcePath The source zip file path
	 * @param mixed $destDirectory The destination directory path
	 * @return array The list of extracted files
	 */
	public static function Decompress(string $sourcePath, $destDirectory = null)
	{
		return iterator_to_array(self::DecompressIterator($sourcePath, $destDirectory));
	}
	/**
	 * Decompress a zip file asyncronusly into files and folders
	 * @param string $sourcePath The source zip file path
	 * @param mixed $destDirectory The destination directory path
	 * @return \Generator<mixed, string, mixed, void> The list of extracted files
	 */
	public static function DecompressIterator(string $sourcePath, $destDirectory = null)
	{
		$zip = new \ZipArchive();
		$sourcePath = Storage::GetAbsolutePath($sourcePath);
		if ($zip->open($sourcePath) === TRUE) {
			$extractPath = $destDirectory ? Storage::GetAbsolutePath($destDirectory) : Storage::GetAbsolutePath(dirname($sourcePath));
			if (!is_dir($extractPath))
				$extractPath = Storage::CreateDirectory($extractPath);
			$zip->extractTo($extractPath);
			for ($i = 0; $i < $zip->numFiles; $i++) {
				$stat = $zip->statIndex($i);
				yield $extractPath . DIRECTORY_SEPARATOR . $stat['name'];
			}
			$zip->close();
		}
	}


	public static function ParentDirectory(...$paths)
	{
		if ($paths) {
			$sep = str_contains(first($paths), "/") ? "/" : "\\";
			$sepPat = "/" . preg_quote($sep) . "/";
			$l = count($paths);
			for ($i = 0; $i < $l; $i++)
				$paths[$i] = preg_split($sepPat, rtrim($paths[$i], $sep));
			$parts = [];
			while (true) {
				$part = null;
				for ($i = 0; $i < $l; $i++) {
					if (count($paths[$i]) <= 1)
						return join($sep, $parts) . $sep;
					$p = array_shift($paths[$i]);
					if ($part === null)
						$part = $p;
					elseif ($part !== $p)
						return join($sep, $parts) . $sep;
				}
				$parts[] = $part;
			}
		}
		return null;
	}
}