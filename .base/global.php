<?php

use MiMFa\Library\Convert;

/**
 * The Global Static Variables
 * It contains the most useful objects along developments
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Globals See the Documentation
 */
class _
{
	public static int $DynamicId = 0;
	/**
	 * The version of aseqbase framework
	 * Generation	.	Major	Minor	1:test|2:alpha|3:beta|4:release|5<=9:stable|0:base
	 * X			.	xx		xx		x
	 */
	public static float $Version = 2.00000;
	/**
	 * The default files extensions
	 * @example: ".php"
	 */
	public static string|null $Extension = ".php";

	/**
	 * A Path=>Fucntion array to apply the Function before using the Path
	 * @var mixed
	 */
	public static array $Prepends = array();
	/**
	 * A Path=>Fucntion array to apply the Function after using the Path
	 * @var mixed
	 */
	public static array $Appends = array();

	/**
	 * All sequences either $ASEQ and $BASE
	 * @example: [
	 *	'home/domain/aseq/' => 'https://aseq.domain.tld/',
	 *	'home/domain/1stseq/' => 'https://1stseq.domain.tld/',
	 *	'home/domain/2ndseq/' => 'https://2ndseq.domain.tld/',
	 *	'home/domain/3rdseq/' => 'https://3rdseq.domain.tld/',
	 *	'home/domain/base/' => 'https://base.domain.tld/'
	 *]
	 */
	public static array $Sequences;

	/**
	 * To access all the website configurations
	 */
	public static Configuration $Config;

	/**
	 * To access all the website information
	 */
	public static Information $Info;

	/**
	 * To access all back-end tools
	 */
	public static Back $Back;

	/**
	 * To access all front-end tools
	 */
	public static Front $Front;

	/**
	 * To access all addresses to a sequence of the website
	 */
	public static AddressBase $Aseq;
	/**
	 * To access all addresses to the base of the website,
	 * and the dinal sequence of the website
	 */
	public static AddressBase $Base;

	/**
	 * To access all basic directory names
	 */
	public static AddressBase $Address;
}

require_once(__DIR__ . "/global/AddressBase.php");

\_::$Address = new AddressBase();

\_::$Sequences = array_merge(
	[
		str_replace(["\\", "/"], DIRECTORY_SEPARATOR, $GLOBALS["DIR"] ?? "")
		=> str_replace(["\\", "/"], "/", $GLOBALS["ROOT"] ?? "")
	],
	$GLOBALS["SEQUENCES"],
	[
		str_replace(["\\", "/"], DIRECTORY_SEPARATOR, __DIR__ . DIRECTORY_SEPARATOR ?? "")
		=> str_replace(["\\", "/"], "/", $GLOBALS["BASE_ROOT"] ?? "")
	]
);

run("global/Base");
run("global/EnumBase");

run("Address");

\_::$Aseq = new Address(
	$GLOBALS["ASEQBASE"],
	$GLOBALS["DIR"],
	$GLOBALS["ROOT"]
);

\_::$Base = new Address(
	$GLOBALS["BASE"],
	__DIR__ . DIRECTORY_SEPARATOR,
	$GLOBALS["BASE_ROOT"]
);

unset($GLOBALS["ASEQ"]);
unset($GLOBALS["BASE"]);
unset($GLOBALS["ASEQBASE"]);
unset($GLOBALS["DIR"]);
unset($GLOBALS["ROOT"]);
unset($GLOBALS["BASE_DIR"]);
unset($GLOBALS["BASE_ROOT"]);
unset($GLOBALS["NEST"]);
unset($GLOBALS["SEQUENCES"]);

run("global/ReqBase");
run("Req");

run("global/ResBase");
run("Res");

library("Math");
library("Local");
library("Convert");
library("Html");
library("Style");
library("Script");

run("global/ConfigurationBase");
run("Configuration");
\_::$Config = new Configuration();

run("global/BackBase");
run("Back");
\_::$Back = new Back();

run("global/InformationBase");
run("Information");
\_::$Info = new Information();

run("global/FrontBase");
run("Front");
\_::$Front = new Front();

\MiMFa\Library\Local::CreateDirectory(\_::$Aseq->TempDirectory);
\MiMFa\Library\Local::CreateDirectory(\_::$Aseq->LogDirectory);


/**
 * To get Features of an object from an array
 * @param mixed $object
 */
function get($object, $data){
	if(!is_array($data))
	{
		if (is_array($object)) {
			if (isset($object[$data]))
				return $object[$data];
			$data = strtolower($data);
			foreach ($object as $k => $v)
				if ($data === strtolower($k))
					return $v;
		} else return $object->{$data} ??
					$object->{strtoproper($data)} ??
					$object->{strtolower($data)} ??
					$object->{strtoupper($data)} ?? null;
	} elseif(is_numeric(array_key_first($data))){
		$res = [];
		foreach ($data as $k)
			if(($val = get($object, $k)) !== null) $res[$k] = $val;
		return $res;
	} else {
		foreach ($data as $k=>$v)
			if(($val = get($object, $k)) === null) $res[$k] = $v;
			else $res[$k] = $val;
		return $res;
	}
}
/**
 * To get Features of an object from an array
 * Then unset that key of the $data
 * @param mixed $object
 */
function grab(&$object, $data){
	$res = null;
	if(!is_array($data)) {
		if (is_array($object)) {
			if (isset($object[$data])) {
				$res = $object[$data];
				unset($object[$data]);
			}
			else {
			$data = strtolower($data);
			foreach ($object as $k => $v)
				if ($data === strtolower($k)){
					$res = $v;
					unset($object[$k]);
					break;
				}
			}
		} else {
			$key = null;
			$res = $object->{$key = $data} ??
				$object->{$key = strtoproper($data)} ??
				$object->{$key = strtolower($data)} ??
				$object->{$key = strtoupper($data)} ?? 
				($key = null);
			if($key !== null) unset($object->$key);
		}
	} else {
		$res = [];
		$val = null;
		if(is_numeric(array_key_first($data))){
			foreach ($data as $k)
				if(($val = grab($object, $k)) !== null) $res[$k] = $val;
		}
		else 
			foreach ($data as $k=>$v)
				if(($val = grab($object, $k)) === null) $res[$k] = $v;
				else $res[$k] = $val;
	}
	return $res;
}

/**
 * To set Features of an object from an array or other object
 * @param mixed $object
 */
function set(&$object, $data){
	if(!is_array($data) || is_array($object)) try{ return $object = $data; } catch(Exception $ex) {}
	else foreach ($data as $k=>$v)
		if((findValid($object, $k, null, $key)) !== null)
			set($object->$key, $v);
	return $object;
}
/**
 * To set Features of an object from an array or other object
 * Then unset that key of the $data
 * @param mixed $object
 */
function swap(&$object, &$data){
	if(!is_array($data) || is_array($object))
		try{ 
			$object = $data;
			unset($data);
		} catch(Exception $ex) {}
	else foreach ($data as $k=>$v)
		if((findValid($object, $k, null, $key)) !== null) {
			set($object->$key, $v);
			unset($data[$k]);
		}
	return $object;
}

/**
 * Check if the client has access to the page or assign them to other page, based on thair IP, Accessibility, Restriction and etc.
 * @param int|null $minaccess The minimum accessibility for the client, pass null to give the user access
 * @param bool $assign Assign clients to other page, if they have not enough access
 * @param bool $die Pass true to die the process if clients have not enough access, else pass false
 * @param int|string|null $die Die the process with this status if clients have not enough access
 * @return bool The client has accessibility bigger than $minaccess or not
 * @return int|mixed The user accessibility group
 */
function inspect($minaccess = 0, bool|string $assign = true, bool|string|int|null $die = true): mixed
{
	if (isValid(\_::$Config->StatusMode)) {
		if ($assign) {
			if (is_string($assign))
				\Res::Go($assign);
			else
				route(\_::$Config->StatusMode??\_::$Config->RestrictionRouteName, alternative:"403");
		}
		if ($die !== false)
			die($die);
		return false;
	} elseif (isValid(\_::$Config->AccessMode)) {
		$ip = getClientIp();
		$cip = false;
		foreach (\_::$Config->AccessPatterns as $pat)
			if ($cip = preg_match($pat, $ip))
				break;
		if ((\_::$Config->AccessMode > 0 && !$cip) || (\_::$Config->AccessMode < 0 && $cip)) {
			if ($assign) {
				if (is_string($assign))
					\Res::Go($assign);
				else
					route(\_::$Config->RestrictionRouteName, alternative:"401");
			}
			if ($die !== false)
				die($die);
			return false;
		}
	}
	$b = auth($minaccess);
	if ($b !== false)
		return $b;
	if ($assign) {
		if (is_string($assign))
			\Res::Go($assign);
		else
			\Res::Go(\MiMFa\Library\User::$InHandlerPath);
	}
	if ($die !== false)
		die($die);
	return $b;
}
/**
 * Check if the user has access to the page or not
 * @param int|array|null $acceptableAccess The minimum accessibility for the user, pass null to give the user access
 * @return int|bool|null The user accessibility group
 */
function auth($minaccess = null): mixed
{
	if (is_null(\_::$Info) || is_null(\_::$Back->User))
		return \MiMFa\Library\User::CheckAccess(null, $minaccess);
	else
		return \_::$Back->User->Access($minaccess);
}

function including(string $path, mixed $data = [], bool $print = true, $default = null)
{
	if (file_exists($path)) {
		ob_start();
		// if (count($data) > 0)
		// 	extract($data);
		$res = include_once $path;
		$output = ob_get_clean();
		if ($print) echo $output;
		else return $output;
		return $res;
	}
	if (is_callable($default) || $default instanceof \Closure)
		return ($default)($path, $data, $print);
	return $default;
}
function requiring(string $path, mixed $data = [], bool $print = true, $default = null)
{
	if (file_exists($path)) {
		ob_start();
		// if (count($data) > 0)
		// 	extract($data);
		$res = require_once $path;
		$output = ob_get_clean();
		if ($print)
			echo $output;
		else
			return $output;
		return $res;
	}
	if (is_callable($default) || $default instanceof \Closure)
		return ($default)($path, $data, $print);
	return $default;
}

function addressing(string|null $file = null, $extension = null, int $origin = 0, int $depth = 99)
{
	$file = str_replace(["\\", "/"], DIRECTORY_SEPARATOR, $file ?? "");
	$extension = $extension ?? \_::$Extension;
	if (!endsWith($file, $extension)) $file .= $extension;
	$path = null;
	$toSeq = $depth < 0 ? (count(\_::$Sequences) + $depth) : ($origin + $depth);
	$seqInd = -1;
	foreach (\_::$Sequences as $dir => $host)
		if (++$seqInd < $origin)
			continue;
		elseif ($seqInd < $toSeq) {
			if (file_exists($path = $dir . $file))
				return $path;
		} else
			return null;
	return null;
}

function using(string|null $directory, string|null $name = null, mixed $data = [], bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null, string|null $extension = ".php")
{
	try {
		renderPrepends($directory, $name);
		if($path = 
			addressing("$directory$name", $extension, $origin, $depth) ?? 
			addressing("$directory$alternative", $extension, $origin, $depth)
		) return including($path, $data, $print, $default);
	} finally {
		renderAppends($directory, $name);
	}
}

/**
 * Prepend something to any function or directory's files or actions
 * @param mixed $directory function name or directory
 * @param null|string $name file name
 * @param null|string|callable $value the action or content tou want to do
 */
function before($directory, string|null $name = null, null|string|callable $value = null)
{
	if (isValid($value)) {
		$directory = strtolower($directory ?? "");
		$name = strtolower($name ?? "");
		if (!isset(\_::$Prepends[$directory]))
			\_::$Prepends[$directory] = array();
		if (!isset(\_::$Prepends[$directory][$name]))
			\_::$Prepends[$directory][$name] = array();
		array_push(\_::$Prepends[$directory][$name], $value);
	}
}
/**
 * Append something to any function or directory's files or actions
 * @param mixed $directory function name or directory
 * @param null|string $name file name
 * @param null|string|callable $value the action or content tou want to do
 */
function after($directory, string|null $name = null, null|string|callable $value = null)
{
	if (isValid($value)) {
		$directory = strtolower($directory ?? "");
		$name = strtolower($name ?? "");
		if (!isset(\_::$Appends[$directory]))
			\_::$Appends[$directory] = array();
		if (!isset(\_::$Appends[$directory][$name]))
			\_::$Appends[$directory][$name] = array();
		array_push(\_::$Appends[$directory][$name], $value);
	}
}
function renderPrepends($directory, string|null $name = null)
{
	$directory = strtolower($directory ?? "");
	$name = strtolower($name ?? "");
	if (isset(\_::$Prepends[$directory][$name]))
		\Res::Render(\_::$Prepends[$directory][$name]);
	elseif (isset(\_::$Prepends[$directory . $name]))
		\Res::Render(\_::$Prepends[$directory . $name]);
}
function renderAppends($directory, string|null $name = null)
{
	$directory = strtolower($directory ?? "");
	$name = strtolower($name ?? "");
	if (isset(\_::$Appends[$directory][$name]))
		\Res::Render(\_::$Appends[$directory][$name]);
	elseif (isset(\_::$Appends[$directory . $name]))
		\Res::Render(\_::$Appends[$directory . $name]);
}

/**
 * To interprete, the specified file in all sequences
 * @param non-empty-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed
 */
function runAll(string|null $name, mixed $data = [], bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
{
	$depth = min($depth, count(\_::$Sequences)) - 1;
	$res = [];
	for (; $origin <= $depth; $depth--)
		$res[] = using(\_::$Address->Directory, $name, $data, $print, $depth, 1, $alternative, $default);
	return $res;
}
/**
 * To interprete, the specified path
 * @param non-empty-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed
 */
function run(string|null $name, mixed $data = [], bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->Directory, $name, $data, $print, $origin, $depth, $alternative, $default);
}
/**
 * To interprete, the specified ModelName
 * @param non-empty-string $Name
 * @param mixed $data
 * @param bool $print
 * @return mixed
 */
function model(string $name, mixed $data = [], bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->ModelDirectory, $name, $data, $print, $origin, $depth, $alternative, $default);
}
/**
 * To interprete, the specified LibraryName
 * @param non-empty-string $Name
 * @param mixed $data
 * @param bool $print
 * @return mixed
 */
function library(string $Name, mixed $data = [], bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->LibraryDirectory, $Name, $data, $print, $origin, $depth, $alternative, $default);
}
/**
 * To interprete, the specified ComponentName
 * @param non-empty-string $Name
 * @param mixed $data
 * @param bool $print
 * @return mixed
 */
function component(string $Name, mixed $data = [], bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->ComponentDirectory, $Name, $data, $print, $origin, $depth, $alternative, $default);
}
/**
 * To interprete, the specified TemplateName
 * @param non-empty-string $Name
 * @param mixed $data
 * @param bool $print
 * @return mixed
 */
function module(string $Name, mixed $data = [], bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->ModuleDirectory, $Name, $data, $print, $origin, $depth, $alternative, $default);
}
/**
 * To interprete, the specified TemplateName
 * @param non-empty-string $Name
 * @param mixed $data
 * @param bool $print
 * @return mixed
 */
function template(string $Name, mixed $data = [], bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->TemplateDirectory, $Name, $data, $print, $origin, $depth, $alternative, $default);
}
/**
 * To interprete, the specified viewname
 * @param non-empty-lowercase-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed
 */
function view(string|null $name, mixed $data = [], bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->ViewDirectory, $name ?? \_::$Config->DefaultViewName, $data, $print, $origin, $depth, $alternative, $default);
}
/**
 * To interprete, the specified pagename
 * @param non-empty-lowercase-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed
 */
function page(string $name, mixed $data = [], bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->PageDirectory, $name, $data, $print, $origin, $depth, $alternative, $default);
}
/**
 * To interprete, the specified regionname
 * @param non-empty-lowercase-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed
 */
function region(string $name, mixed $data = [], bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->RegionDirectory, $name, $data, $print, $origin, $depth, $alternative, $default);
}
/**
 * To interprete, the specified partname
 * @param non-empty-lowercase-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed
 */
function part(string $name, mixed $data = [], bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->PartDirectory, $name, $data, $print, $origin, $depth, $alternative, $default);
}
/**
 * To interprete, the specified logicname
 * @param non-empty-lowercase-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed
 */
function logic(string $name, mixed $data = [], bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->LogicDirectory, $name, $data, $print, $origin, $depth, $alternative, $default);
}
/**
 * To interprete, the specified routename
 * @param non-empty-lowercase-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed
 */
function route(string|null $name, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->RouteDirectory, $name ?? \_::$Config->DefaultRouteName, $data??\_::$Back->Router, $print, $origin, $depth, $alternative, $default);
}
/**
 * To get a Table from the DataBase
 * @param string $name The raw table name (Without any prefix)
 * @return \MiMFa\Library\DataTable
 */
function table(string $name, bool $prefix = true, int $origin = 0, int $depth = 99, \MiMFa\Library\DataBase $source = null, $default = null)
{
	return new \MiMFa\Library\DataTable(
		$source ?? \_::$Back->DataBase,
		$prefix ? 
		(\_::$Config->DataBasePrefix . (\_::$Config->DataBaseAddNameToPrefix? preg_replace("/\W/i", "_", \_::$Aseq->Name ?? "qb") . "_":"") . $name) 
		: $name
	);
}

/**
 * Convert to string and process everythings
 * @param mixed $value The target object tot do process
 * @param bool $translating Do translation
 * @param bool $styling Do style and strongify the keywords
 * @param bool $refering Refering tags and categories to their links
 * @return string|null
 */
function __(mixed $value, bool $translating = true, bool $styling = true, bool|null $refering = null): string|null
{
	$value = MiMFa\Library\Convert::ToString($value);
	if ($translating && \_::$Config->AllowTranslate)
		$value = \_::$Back->Translate->Get($value);
	if ($styling)
		$value = MiMFa\Library\Style::DoStyle(
			$value,
			\_::$Info->KeyWords
		);
	if ($refering ?? $styling) {
		if (\_::$Config->AllowContentRefering)
			$value = \MiMFa\Library\Style::DoProcess(
				$value,
				process: function ($k, $v, $i) {
					return \MiMFa\Library\Html::Link($v, \_::$Address->ContentPath . strtolower($k));
				},
				keyWords: table("Content")->DoSelectPairs("`Name`", "`Title`", "ORDER BY LENGTH(`Title`) DESC"),
				both: true,
				caseSensitive: true
			);
		if (\_::$Config->AllowCategoryRefering)
			$value = \MiMFa\Library\Style::DoProcess(
				$value,
				process: function ($k, $v, $i) {
					return \MiMFa\Library\Html::Link($v, \_::$Address->CategoryPath . strtolower($k));
				},
				keyWords: table("Category")->DoSelectPairs("`Name`", "`Title`", "ORDER BY LENGTH(`Title`) DESC"),
				both: true,
				caseSensitive: true
			);
		if (\_::$Config->AllowTagRefering)
			$value = \MiMFa\Library\Style::DoProcess(
				$value,
				process: function ($k, $v, $i) {
					return \MiMFa\Library\Html::Link($v, \_::$Address->TagPath . strtolower($k));
				},
				keyWords: table("Tag")->DoSelectPairs("`Name`", "`Title`", "ORDER BY LENGTH(`Title`) DESC"),
				both: true,
				caseSensitive: true
			);
		if (\_::$Config->AllowUserRefering)
			$value = \MiMFa\Library\Style::DoProcess(
				$value,
				process: function ($k, $v, $i) {
					return \MiMFa\Library\Html::Link($v, \_::$Address->UserPath . strtolower($k));
				},
				keyWords: table("User")->DoSelectPairs("`Name`", "`Name`", "ORDER BY LENGTH(`Name`) DESC"),
				both: false,
				caseSensitive: true
			);
	}
	return $value;
}


/**
 * Do a loop action by a callable function on a countable element
 * @param mixed $array
 * @param callable $action The loop action $action($key, $value, $index)
 * @return array
 */
function loop($array, callable $action, $nullValues = false)
{
	if (is_null($array))
		return [];
	return iterator_to_array(iteration($array, $action, $nullValues));
}
/**
 * Do a loop action by a callable function on a countable element
 * @param mixed $array
 * @param callable $action The loop action $action($key, $value, $index)
 * @return iterable
 */
function iteration($array, callable $action, $nullValues = false)
{
	if (!is_null($array)) {
		$i = 0;
		if (!is_iterable($array)) {
			if (($res = $action($i, $array, $i)) !== null || $nullValues)
				yield $res;
		} else
			foreach ($array as $key => $value)
				if (($res = $action($key, $value, $i++)) !== null || $nullValues)
					yield $res;
	}
}
/**
 * Returns the value of the first array element.
 * @param array|object|iterable|Generator|null $array
 * @return mixed
 */
function first($array, $default = null)
{
	if (is_null($array))
		return $default;
	if (is_array($array))
		return count($array) > 0 ? $array[array_key_first($array)] : $default;
	if (is_iterable($array)) {
		foreach ($array as $value)
			return $value;
		return $default;
	}
	$res = reset($array);
	if ($res === false)
		return $default;
	return $res;
}
/**
 * Returns the value of the last array element.
 * @param array|object|iterable|Generator|null $array
 * @return mixed
 */
function last($array, $default = null)
{
	if (is_null($array))
		return $default;
	if (is_array($array))
		return count($array) > 0 ? $array[array_key_last($array)] : $default;
	if (is_iterable($array)) {
		foreach ($array as $value)
			$default = $value;
		return $default;
	}
	$res = end($array);
	if ($res === false)
		return $default;
	return $res;
}

function code($html, &$dic = null, $startCode = "<", $endCode = ">", $pattern = '/((["\'])\S+[\w\W]*\2)|(\<\S+[\w\W]*[^\\\\]\>)|(\d*\.?\d+)/iU')
{
	if (!is_array($dic))
		$dic = array();
	$c = count($dic);
	return preg_replace_callback($pattern, function ($a) use (&$dic, &$c, $startCode, $endCode) {
		$key = $a[0];
		if (array_key_exists($key, $dic))
			return $dic[$key];
		return $dic[$key] = $startCode . $c++ . $endCode;
	}, $html);
}
function decode($html, $dic)
{
	if (is_array($dic))
		foreach ($dic as $k => $v)
			$html = str_replace($v, $k, $html);
	return $html;
}

function encrypt($plain)
{
	if (is_null($plain))
		return null;
	if (empty($plain))
		return $plain;
	return \_::$Back->Cryptograph->Encrypt($plain, \_::$Config->SecretKey, true);
}
function decrypt($cipher)
{
	if (is_null($cipher))
		return null;
	if (empty($cipher))
		return $cipher;
	return \_::$Back->Cryptograph->Decrypt($cipher, \_::$Config->SecretKey, true);
}

function startsWith(string|null $haystack, string|null ...$needles): bool
{
	foreach ($needles as $needle)
		if (!is_null($needle) && substr_compare($haystack, $needle, 0, strlen($needle)) === 0)
			return $needle || true;
	return false;
}
function endsWith(string|null $haystack, string|null ...$needles): bool
{
	foreach ($needles as $needle)
		if (!is_null($needle) && substr_compare($haystack, $needle, -strlen($needle)) === 0)
			return $needle || true;
	return false;
}

function getId($random = false): int
{
	if (!$random)
		return ++\_::$DynamicId;
	list($usec, $sec) = explode(" ", microtime());
	return (int) ($usec * 10000000 + $sec);
}

function getDomainUrl(): string|null
{
	// server protocol
	$protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';
	// domain name
	$domain = $_SERVER['SERVER_NAME'];

	// server port
	$port = $_SERVER['SERVER_PORT'];
	$disp_port = ($protocol == 'http' && $port == 80 || $protocol == 'https' && $port == 443) ? '' : ":$port";

	// put em all together to get the complete base URL
	return $protocol . "://" . $domain . $disp_port;
}

function forceFullUrl(string|null $path, bool $optimize = true)
{
	return \MiMFa\Library\Local::GetFullUrl($path, $optimize);
}
function forceUrl(string|null $path)
{
	return \MiMFa\Library\Local::GetUrl($path);
}
function forceUrls($items): array
{
	$c = count($items);
	if ($c > 0)
		if (is_array($items[0])) {
			for ($i = 0; $i < $c; $i++) {
				if (isset($items[$i]["Source"]))
					$items[$i]["Source"] = \MiMFa\Library\Local::GetUrl($items[$i]["Source"]);
				if (isset($items[$i]["Image"]))
					$items[$i]["Image"] = \MiMFa\Library\Local::GetUrl($items[$i]["Image"]);
				if (isset($items[$i]["Url"]))
					$items[$i]["Url"] = \MiMFa\Library\Local::GetUrl($items[$i]["Url"]);
			}
		} else
			for ($i = 0; $i < $c; $i++)
				$items[$i] = \MiMFa\Library\Local::GetUrl($items[$i]);
	return $items;
}
function forcePath(string|null $path): string|null
{
	return \MiMFa\Library\Local::GetPath($path);
}

/**
 * Get the full part of a url pointed to catch status
 * @example: "https://www.mimfa.net:5046/Category/mimfa/service/web.php?p=3&l=10#serp"
 * @return string|null
 */
function getFullUrl(string|null $path = null, bool $optimize = true): string|null
{
	if ($path === null)
		$path = getUrl();
	return \MiMFa\Library\Local::GetFullUrl($path, $optimize);
}
/**
 * Get the full part of a url
 * @example: "https://www.mimfa.net:5046/Category/mimfa/service/web.php?p=3&l=10#serp"
 * @return string|null
 */
function getUrl(string|null $path = null): string|null
{
	if ($path === null)
		$path = (
			getValid($_SERVER, 'SCRIPT_URI') ??
			(((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http") .
			"://" . $_SERVER["HTTP_HOST"] . getBetween($_SERVER, "REQUEST_URI", "PHP_SELF")
		);//.($_SERVER['QUERY_STRING']?"?".$_SERVER['QUERY_STRING']:"");
	return preg_replace("/^([\/\\\])/", rtrim(GetHost(), "/\\") . "$1", $path);
}
/**
 * Get the host part of a url
 * @example: "https://www.mimfa.net:5046"
 * @return string|null
 */
function getHost(string|null $path = null): string|null
{
	$pat = "/^\w+\:\/*[^\/]+/";
	if ($path == null || !preg_match($pat, $path))
		$path = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'];
	return PREG_Find($pat, $path);
}
/**
 * Get the site name part of a url
 * @example: "www.mimfa.net"
 * @return string|null
 */
function getSite(string|null $path = null): string|null
{
	return PREG_replace("/(^\w+:\/*)|(\:\d+$)/", "", getHost($path));
}
/**
 * Get the domain name part of a url
 * @example: "mimfa.net"
 * @return string|null
 */
function getDomain(string|null $path = null): string|null
{
	return PREG_replace("/(^\w+:\/*(www\.)?)|(\:\d+$)/", "", getHost($path));
}
/**
 * Get the path part of a url
 * @example: "https://www.mimfa.net/Category/mimfa/service/web.php"
 * @return string|null
 */
function getPath(string|null $path = null): string|null
{
	return PREG_Find("/(^[^\?#]*)/", $path ?? getUrl());
}
/**
 * Get the request part of a url
 * @example: "/Category/mimfa/service/web.php?p=3&l=10#serp"
 * @return string|null
 */
function getRequest(string|null $path = null): string|null
{
	if ($path == null)
		$path = getUrl();
	if (startsWith($path, \_::$Base->Directory))
		$path = substr($path, strlen(\_::$Base->Directory));
	return PREG_Replace("/(^\w+:\/*[^\/]+)/", "", $path);
}
/**
 * Get the relative address from a url
 * @example: "Category/mimfa/service/web.php?p=3&l=10#serp"
 * @return string|null
 */
function getRelative(string|null $path = null): string|null
{
	if ($path == null)
		$path = getUrl();
	if (startsWith($path, \_::$Base->Directory))
		return substr($path, strlen(\_::$Base->Directory));
	return PREG_Replace("/^\w+:\/*[^\/]+/", "", $path);
}
/**
 * Get the direction part of a url from the root
 * @example: "Category/mimfa/service/web.php"
 * @return string|null
 */
function getDirection(string|null $path = null): string|null
{
	if ($path == null)
		$path = getUrl();//ltrim($_SERVER["REQUEST_URI"],"\\\/");
	if (startsWith($path, \_::$Base->Directory))
		$path = substr($path, strlen(\_::$Base->Directory));
	return PREG_Replace("/(^\w+:\/*[^\/]+\/)|([\?#].+$)/", "", $path);
}
/**
 * Get the last part of a direction url
 * @example: "web.php"
 * @return string|null
 */
function getPage(string|null $path = null): string|null
{
	return last(preg_split("/[\\\\\/]/i", getDirection($path)));
}
/**
 * Get the query part of a url
 * @example: "p=3&l=10"
 * @return string|null
 */
function getQuery(string|null $path = null): string|null
{
	return PREG_Find("/((?<=\?)[^#]*($|#))/", $path ?? getUrl());
}
/**
 * Get the fragment or anchor part of a url
 * @example: "serp"
 * @return string|null
 */
function getFragment(string|null $path = null): string|null
{
	return PREG_Find("/((?<=#)[^\?]*($|\?))/", $path ?? getUrl());
}

function  getMethodName(string|int|null $method = null)
{
	switch (strtoupper($method ?? "")) {
		case 1:
		case "GET":
			return "GET";
		case 2:
		case "SET":
		case "POST":
		case "ADD":
			return "POST";
		case 3:
		case "EDIT":
		case "MODIFY":
		case "UPDATE":
		case "PUT":
			return "PUT";
		case 4:
		case "BIN":
		case "BINARY":
		case "FILES":
		case "FILE":
			return "POST";
		case 5:
		case "PATCH":
		case "VALUE":
		case "VAL":
			return "PATCH";
		case 6:
		case "REMOVE":
		case "DELETE":
		case "DEL":
			return "DELETE";
		default:
			return $method ?? $_SERVER['REQUEST_METHOD'];
	}
}
function  getMethodIndex(string|int|null $method = null)
{
	switch (strtoupper($method ?? $_SERVER['REQUEST_METHOD'])) {
		case 1:
		case "GET":
			return 1;
		case 2:
		case "SET":
		case "ADD":
		case "POST":
			return 2;
		case 3:
		case "EDIT":
		case "MODIFY":
		case "UPDATE":
		case "PUT":
			return 3;
		case 4:
		case "BIN":
		case "BINARY":
		case "FILES":
		case "FILE":
			return 4;
		case 5:
		case "PATCH":
		case "VALUE":
		case "VAL":
			return 5;
		case 6:
		case "REMOVE":
		case "DELETE":
		case "DEL":
			return 6;
		default:
			return 0;
	}
}

/**
 * Create an email account
 * @example: "do-not-reply@mimfa.net"
 * @return string|null
 */
function createEmail($name = "do-not-reply", string|null $path = null): string|null
{
	return $name . "@" . getDomain($path);
}

function changeMemo($key, $val)
{
	if ($val == "!" || is_null($val)) {
		popMemo($key);
		return null;
	} else
		setMemo($key, $val);
	return $val;
}
function popMemo($key)
{
	$val = getMemo($key);
	forgetMemo($key);
	return $val;
}
function setMemo($key, $val)
{
	if ($val == null)
		return false;
	return setcookie($key, $val, 0, "/");
}
function getMemo($key)
{
	if (isset($_COOKIE[$key]))
		return $_COOKIE[$key];
	else
		return null;
}
function hasMemo($key)
{
	return !is_null(getMemo($key));
}
function forgetMemo($key)
{
	unset($_COOKIE[$key]);
	return setcookie($key, "", 0, "/");
}
function flushMemos($key)
{
	foreach ($_COOKIE as $key => $val) {
		unset($_COOKIE[$key]);
		return setcookie($key, "", 0, "/");
	}
}

function changeSession($key, $val)
{
	if ($val == "!" || is_null($val)) {
		popSession($key);
		return null;
	} else
		setSession($key, $val);
	return $val;
}
function popSession($key)
{
	$val = getSession($key);
	forgetSession($key);
	return $val;
}
function setSession($key, $val)
{
	return $_SESSION[$key] = $val;
}
function getSession($key)
{
	return get($_SESSION, $key);
}
function hasSession($key)
{
	return isValid($_SESSION, $key);
}
function forgetSession($key)
{
	unset($_SESSION[$key]);
}
function flushSessions($key)
{
	foreach ($_SESSION as $key => $val)
		unset($_SESSION[$key]);
}

function getClientIp($version = null): string|null
{
	foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
		if (array_key_exists($key, $_SERVER) === true) {
			foreach (explode(',', $_SERVER[$key]) as $ip)
				$ip = trim($ip); // just to be safe
			if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false)
				return $version == 6 ? gethostbyaddr($ip) : $ip;
		}
	}
	return null;
}

function getValue(string $source, string|null $key = null, bool $ismultiline = true)
{
	if ($key == null)
		return is_string($source) ? $source : ($source)();
	else
		return fetchValue($source, $key, $ismultiline);
}
function fetchValue(string|null $source, string|null $key, bool $ismultiline = true)
{
	$source = getValue($source);
	$arr = is_array($source) ? $source : explode("\n", $source);
	$f = false;
	$res = "";
	foreach ($arr as $i => $line) {
		$line = trim($line);
		if (strpos($line, $key) === 0) {
			$res = trim(substr($line, strlen($key)));
			$f = $ismultiline;
			if (!$f)
				break;
		} elseif ($f) {
			if (strpos($line, "	") === 0)
				$res .= PHP_EOL . "\t" . trim($line);
			else
				break;
		}
	}
	return trim($res);
}

function isEmpty($obj): bool
{
	return !isset($obj) || is_null($obj) || (is_string($obj) && (trim($obj . "", " \n\r\t\v\f'\"") === "")) || (is_array($obj) && count($obj) === 0);
	//return $obj?(is_string($obj) && (trim($obj . "", " \n\r\t\v\f'\"") === "")):true;
}
function isValid($obj, string|null $item = null): bool
{
	if ($item === null)
		return isset($obj) && !is_null($obj) && (!is_string($obj) || !(trim($obj) == "" || trim($obj, "'\"") == ""));
	//return $obj?true:false;
	elseif (is_array($obj))
		return isValid($obj) && isValid($item) && isset($obj[$item]) && isValid($obj[$item]);
	else
		return isValid($obj) && isset($obj->$item) && isValid($obj->$item);
}
function getValid($obj, string|null $item = null, $defultValue = null)
{
	if (isValid($obj, $item))
		if ($item === null)
			return $obj;
		elseif (is_array($obj))
			return $obj[$item];
		else
			return $obj->$item;
	else
		return $defultValue;
}
function findValid($obj, string|null $item = null, $defultValue = null, &$key = null)
{
	if ($item == null)
		return isValid($obj) ? $obj : $defultValue;
	elseif (is_array($obj)) {
		if (isset($obj[$item]))
			return isValid($obj[$item]) ? $obj[$item] : $defultValue;
		$item = strtolower($item);
		foreach ($obj as $k => $v)
			if ($item === strtolower($k)) {
				$key = $k;
				return isValid($v) ? $v : $defultValue;
			}
	} else {
		$res =
			($obj->{$key = $item} ??
				$obj->{$key = strtoproper($item)} ??
				$obj->{$key = strtolower($item)} ??
				$obj->{$key = strtoupper($item)} ??
				($key = null));
		return isValid($res) ? $res : $defultValue;
	}
	return $defultValue;
}
function grabValid(&$obj, string|null $item = null, $defultValue = null)
{
	if (isValid($obj, $item))
		if ($item === null)
			return $obj;
		elseif (is_array($obj)) {
			$res = $obj[$item];
			unset($obj[$item]);
			return $res;
		} else {
			$res = $obj->$item;
			unset($obj->$item);
			return $res;
		} else
		return $defultValue;
}
function grabFindValid(&$obj, string|null $item = null, $defultValue = null, &$key = null)
{
	if ($item == null)
		return isValid($obj) ? $obj : $defultValue;
	elseif (is_array($obj)) {
		if (isset($obj[$item])) {
			$res = $obj[$item] ?? $defultValue;
			unset($obj[$item]);
			return isValid($res) ? $res : $defultValue;
		}
		$item = strtolower($item);
		foreach ($obj as $k => $v)
			if ($item === strtolower($k)) {
				$key = $k;
				unset($obj[$k]);
				return isValid($v) ? $v : $defultValue;
			}
	} else {
		$res =
			$obj->{$key = $item} ??
			$obj->{$key = strtoproper($item)} ??
			$obj->{$key = strtolower($item)} ??
			$obj->{$key = strtoupper($item)} ??
			($key = null) ?? $defultValue;
		if ($key !== null)
			unset($obj->$key);
		return isValid($res) ? $res : $defultValue;
	}
	return $defultValue;
}
function doValid(callable $func, $obj, string|null $item = null, $defultValue = null)
{
	return isValid($obj, $item) ? $func(getValid($obj, $item)) : $defultValue;
}
function getBetween($obj, ...$items)
{
	foreach ($items as $value)
		if (($value = getValid($obj, $value, null)) !== null)
			return $value;
	return null;
}
function findBetween($obj, ...$items)
{
	foreach ($items as $value)
		if (($value = findValid($obj, $value, null)) !== null)
			return $value;
	return null;
}
function grabBetween(&$obj, ...$items)
{
	foreach ($items as $value)
		if (($value = grabValid($obj, $value, null)) !== null)
			return $value;
	return null;
}
function between(...$options)
{
	foreach ($options as $value)
		if (isValid($value))
			return $value;
	return null;
}

function isASEQ(string|null $directory): bool
{
	return !\MiMFa\Library\Local::FileExists($directory . "global/ConfigurationBase.php")
		&& \MiMFa\Library\Local::FileExists($directory . "global.php")
		&& \MiMFa\Library\Local::FileExists($directory . "Information.php")
		&& \MiMFa\Library\Local::FileExists($directory . "initialize.php");
}
function isBASE(string|null $directory): bool
{
	return \MiMFa\Library\Local::FileExists($directory . "Configuration.php")
		&& \MiMFa\Library\Local::FileExists($directory . "global/ConfigurationBase.php")
		&& \MiMFa\Library\Local::FileExists($directory . "Information.php")
		&& \MiMFa\Library\Local::FileExists($directory . "global/InformationBase.php")
		&& \MiMFa\Library\Local::FileExists($directory . "Front.php")
		&& \MiMFa\Library\Local::FileExists($directory . "global/FrontBase.php")
		&& \MiMFa\Library\Local::FileExists($directory . "global.php")
		&& \MiMFa\Library\Local::FileExists($directory . "initialize.php");
}
function isInASEQ(string|null $filePath): bool
{
	$filePath = preg_replace("/^\\\\/", \_::$Aseq->Directory, str_replace(\_::$Aseq->Directory, "", trim($filePath ?? getUrl())));
	if (isFormat($filePath, \_::$Extension))
		return file_exists($filePath);
	return is_dir($filePath) || file_exists($filePath . \_::$Extension);
}
function isInBASE(string|null $filePath): bool
{
	$filePath = \_::$Base->Directory . preg_replace("/^\\\\/", "", str_replace(\_::$Base->Directory, "", trim($filePath ?? getUrl())));
	if (isFormat($filePath, \_::$Extension))
		return file_exists($filePath);
	return is_dir($filePath) || file_exists($filePath . \_::$Extension);
}

/**
 * Check file format by thats extension
 * @param null|string $path
 * @param array<string> $formats
 * @return string|bool
 */
function isFormat(string|null $path, string|array ...$formats)
{
	$p = getPath(strtolower($path));
	foreach ($formats as $format)
		if (is_array($format)) {
			foreach ($format as $forma)
				if ($forma = isFormat($p, $forma))
					return $forma;
		} elseif (endsWith($p, strtolower($format)))
			return $format;
	return false;
}

/**
 * Check if the string is a relative or absolute file URL
 * @param null|string $url The url string
 * @return bool
 */
function isFile(string|null $url, string ...$formats): bool
{
	if (count($formats) == 0)
		array_push($formats, \_::$Config->AcceptableFileFormats, \_::$Config->AcceptableDocumentFormats, \_::$Config->AcceptableImageFormats, \_::$Config->AcceptableAudioFormats, \_::$Config->AcceptableVideoFormats);
	return isUrl($url) && isFormat($url, $formats);
}
/**
 * Check if the string is a relative or absolute URL
 * @param null|string $url The url string
 * @return bool
 */
function isUrl(string|null $url): bool
{
	return (!empty($url)) && preg_match("/^([A-z0-9\-]+\:)?([\/\?\#]([^:\/\{\}\|\^\[\]\"\`\r\n\t\f]*)|(\:\d))+$/", $url);
}
/**
 * Check if the string is only a relative URL
 * @param null|string $url The url string
 * @return bool
 */
function isRelativeUrl(string|null $url): bool
{
	return (!empty($url)) && preg_match("/^([\/\?\#]([^:\/\{\}\|\^\[\]\"\`\r\n\t\f]*)|(\:\d))+$/", $url);
}
/**
 * Check if the string is only an absolute URL
 * @param null|string $url The url string
 * @return bool
 */
function isAbsoluteUrl(string|null $url): bool
{
	return (!empty($url)) && preg_match("/^[A-z0-9\-]+\:\/*([\/\?\#][^\/\{\}\|\^\[\]\"\`\r\n\t\f]*)+$/", $url);
}
/**
 * Check if the string is script or not
 * @param null|string $script The url string
 * @return bool
 */
function isScript(string|null $script): bool
{
	return (!empty($script))
		&& !preg_match("/^[A-z0-9\-\.\_]+\@([A-z0-9\-\_]+\.[A-z0-9\-\_]+)+$/", $script)
		&& !preg_match("/^[A-z0-9\-]+\:\/*([\/\?\#][^\/\{\}\|\^\[\]\"\`\r\n\t\f]*)+$/", $script)
		&& preg_match("/[\{\}\|\^\[\]\"\`\;\r\n\t\f]|((^\s*[\w\$][\w\d\$\_\.]+\s*\([\s\S]*\)\s*)+;?\s*$)/", $script);
}
/**
 * To check if the string is a JSON or not
 * @param null|string $json The json string
 * @return bool
 */
function isJson($json)
{
	if (isEmpty($json))
		return 0;
	return preg_match("/^\s*[\{|\[][\s\S]*[\}\]]\s*$/", $json);
}
/**
 * Check if the string is a relative or absolute URL
 * @param null|string $url The url string
 * @return bool
 */
function isEmail(string|null $email): bool
{
	return (!empty($url)) && preg_match("/^[A-z0-9\-\.\_]+\@([A-z0-9\-\_]+\.[A-z0-9\-\_]+)+$/", $url);
}

/**
 * Check if the string is a suitable name for a class or id or name field
 * @param null|string $text The url string
 * @return bool
 */
function isIdentifier(string|null $text): bool
{
	return (!empty($text)) && preg_match("/^[A-z_\$][A-z0-9_\-\$]*$/", $text);
}

/**
 * Remove all changeable command signs from a path (such as ../ or /./.)
 * Change all slashes/backslashes to the DIRECTORY_SEPARATOR
 * @param string|null $path The source path
 * @return array|string|null
 */
function normalizePath(string|null $path): string|null
{
	return str_replace(["\\", "/"], DIRECTORY_SEPARATOR, preg_replace("/([\/\\\]\.+)|(\.+[\/\\\])/", "", $path ?? getUrl()));
}

/**
 * Create a random string|null with a custom length
 * @param int $length Custom length of destination string|null
 * @param string|null $chars Allowable characters
 * @return string|null
 */
function randomString(int $length = 10, string|null $chars = '_0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'): string|null
{
	return substr(str_shuffle(str_repeat($chars, ceil($length / strlen($chars)))), 1, $length);
}
function insertToString(string $mainstr, string $insertstr, int $index): string
{
	return substr($mainstr, 0, $index) . $insertstr . substr($mainstr, $index);
}
function deleteFromString(string $mainstr, int $index, int $length = 1): string
{
	return substr($mainstr, 0, $index) . substr($mainstr, $index + $length);
}

/**
 * Execute the Command Comments (Commands by the pattern <!---name:Command---> <!---name--->)
 * @param string|null $page The source document
 * @return string|null
 */
function executeCommands(string|null $page, string|null $name = null): string|null
{
	if ($page == null)
		return $page;
	if ($name == null) {
		//$page = executeCommands($page, "Append");
		//$page = executeCommands($page, "Prepend");
	} else {
		$name = strtolower($name);
		$patfull = "/<!-{3}$name:[\w\W]*-{3}>[\w\W]*<!-{3}$name-{3}>/i";
		$patcommand = "/(?<=<!-{3}$name:)[\w\W]*(?=-{3}>)/i";
		$matches = [];
		switch ($name) {
			case "append":
				$page = preg_replace_callback($patfull, function ($m) use (&$matches) {
					array_push($matches, $m[0]);
					return "";
				}, $page);
				foreach ($matches as $m)
					$page = preg_replace(DIRECTORY_SEPARATOR . preg_find($patcommand, $m) . "/i", "\1$m", $page);
				break;
			case "prepend":
				$page = preg_replace_callback($patfull, function ($m) use (&$matches) {
					array_push($matches, $m[0]);
					return "";
				}, $page);
				foreach ($matches as $m)
					$page = preg_replace(DIRECTORY_SEPARATOR . preg_find($patcommand, $m) . "/i", "$m\1", $page);
				break;
		}
		$pat = "/<!-{3}" . $name . "[\w\W]*-{3}>/i";
		$page = preg_replace($pat, "", $page);
	}
	return $page;
}
/**
 * Make a string to proper case
 * @param string $string — The input string.
 * @return string — the propercased string.
 */
function strToProper($string)
{
	if (empty($string))
		return $string;
	return preg_replace_callback("/\b[a-z]/", fn($v) => strtoupper($v[0]), $string);
}

/**
 * Compress and reduce the size of document
 * @param string|null $page The source document
 * @return string|null
 */
function reduceSize(string|null $page): string|null
{
	if ($page == null)
		return $page;
	$ls = array();
	$pat = "/<\s*(style)[\s\S]*>[\s\S]*<\/\s*\1\s*>/ixU";
	//$pat ="/\<\s*((style)|(script))[\s\S]*\>[\s\S]*\<\\/\s*\\1\s*\>/ixU";
	$matches = null;
	if (preg_match_all($pat, $page, $matches)) {
		foreach ($matches[0] as $item)
			if (!in_array($item, $ls))
				array_push($ls, preg_replace("/\s+/im", " ", $item));
		//echo count($ls);
		$page = preg_replace($pat, "", $page);
		$page = preg_replace("/<\/\s*head\s*>/im", implode(PHP_EOL, $ls) . PHP_EOL . "</head>", $page);
		//$page = preg_replace("/(?<!(\<\s*(script)[\s\S]*\>[\s\S]+))\s+(?!([\s\S]+\<\\/\s*\\2\s*\>))/ixU", " ", $page);
		return preg_replace("/<\s*\/\s*(style)\s*>\s*\<\s*\1\s*>/ixU", PHP_EOL, $page);
	}
	return $page;
}

/**
 * Regular Expression Find first match by pattern
 * @param mixed $pattern
 * @param string|null $text
 * @param string|null $def
 * @return mixed
 */
function preg_find($pattern, string|null $text, string|null $def = null): string|null
{
	preg_match_all($pattern, $text, $matches);
	return isset($matches[0][0]) ? $matches[0][0] : $def;
}
/**
 * Regular Expression Find all matches by pattern
 * @param mixed $pattern
 * @param string|null $text
 * @return array|null
 */
function preg_find_all($pattern, string|null $text): array|null
{
	preg_match_all($pattern, $text, $matches);
	return $matches[0];
}

/**
 * Insert into an array
 * @param array      $array
 * @param int|string $position
 * @param mixed      $insert
 */
function array_insert(&$array, $position, $insert)
{
	if (is_int($position)) {
		array_splice($array, $position, 0, $insert);
	} else {
		$pos = array_search($position, array_keys($array));
		$array = array_merge(
			array_slice($array, 0, $pos),
			$insert,
			array_slice($array, $pos)
		);
	}
	return $array;
}
/**
 * Find everythings are match from an array by a callable function
 * @param array      $array
 * @param callable $searching function($key, $val){ return true; }
 * @param array $array_find_keys
 */
function array_find_keys($array, callable $searching)
{
	return array_filter($array, function ($k, $v) use ($searching) {
		return $searching($v, $k);
	}, ARRAY_FILTER_USE_BOTH);
}

//Test Region
function test_server()
{
	foreach ($_SERVER as $k => $v)
		echo "<br>" . "$k: " . $v;
	// echo "<br>"."PHP_SELF: ".$_SERVER['PHP_SELF'];
	// echo "<br>"."GATEWAY_INTERFACE: ".$_SERVER['GATEWAY_INTERFACE'];
	// echo "<br>"."SERVER_ADDR: ".$_SERVER['SERVER_ADDR'];
	// echo "<br>"."SERVER_NAME: ".$_SERVER['SERVER_NAME'];
	// echo "<br>"."SERVER_SOFTWARE: ".$_SERVER['SERVER_SOFTWARE'];
	// echo "<br>"."SERVER_PROTOCOL: ".$_SERVER['SERVER_PROTOCOL'];
	// echo "<br>"."REQUEST_METHOD: ".$_SERVER['REQUEST_METHOD'];
	// echo "<br>"."REQUEST_TIME: ".$_SERVER['REQUEST_TIME'];
	// echo "<br>"."QUERY_STRING: ".$_SERVER['QUERY_STRING'];
	// echo "<br>"."HTTP_ACCEPT: ".$_SERVER['HTTP_ACCEPT'];
	// echo "<br>"."HTTP_ACCEPT_CHARSET: ".$_SERVER['HTTP_ACCEPT_CHARSET'];
	// echo "<br>"."HTTP_HOST: ".$_SERVER['HTTP_HOST'];
	// echo "<br>"."HTTP_REFERER: ".$_SERVER['HTTP_REFERER'];
	// echo "<br>"."HTTPS: ".$_SERVER['HTTPS'];
	// echo "<br>"."REMOTE_ADDR: ".$_SERVER['REMOTE_ADDR'];
	// echo "<br>"."REMOTE_HOST: ".$_SERVER['REMOTE_HOST'];
	// echo "<br>"."REMOTE_PORT: ".$_SERVER['REMOTE_PORT'];
	// echo "<br>"."SCRIPT_FILENAME: ".$_SERVER['SCRIPT_FILENAME'];
	// echo "<br>"."SERVER_ADMIN: ".$_SERVER['SERVER_ADMIN'];
	// echo "<br>"."SERVER_PORT: ".$_SERVER['SERVER_PORT'];
	// echo "<br>"."SERVER_SIGNATURE: ".$_SERVER['SERVER_SIGNATURE'];
	// echo "<br>"."PATH_TRANSLATED: ".$_SERVER['PATH_TRANSLATED'];
	// echo "<br>"."SCRIPT_NAME: ".$_SERVER['SCRIPT_NAME'];
	// echo "<br>"."SCRIPT_URI: ".$_SERVER['SCRIPT_URI'];
}
function test_address($directory = null, string $name = "Configuration")
{
	echo addressing($directory ?? \_::$Address->Directory, $name);
	echo "<br>ASEQ: " . \_::$Aseq->Name;
	echo "<br>ASEQ->Path: " . \_::$Aseq->Path;
	echo "<br>ASEQ->Dir: " . \_::$Aseq->Directory;
	echo "<br>OTHER ASEQ: <br>";
	var_dump(\_::$Aseq);
	echo "<br>BASE: " . \_::$Base->Name;
	echo "<br>BASE->Path: " . \_::$Base->Path;
	echo "<br>BASE->Dir: " . \_::$Base->Directory;
	echo "<br>OTHER BASE: <br>";
	var_dump(\_::$Base);
	echo "<br><br>ADDRESSES: <br>";
	var_dump(\_::$Address);
}
function test_url()
{
	echo "<br>URL: " . \Req::$Url;
	echo "<br>HOST: " . \Req::$Host;
	echo "<br>SITE: " . \Req::$Site;
	echo "<br>PATH: " . \Req::$Path;
	echo "<br>REQUEST: " . \Req::$Request;
	echo "<br>DIRECTION: " . \Req::$Direction;
	echo "<br>QUERY: " . \Req::$Query;
	echo "<br>FRAGMENT: " . \Req::$Fragment;
}
function test_access($func, $res = null)
{
	$r = null;
	if (inspect(0, false, false)) {
		if ($r = $func())
			echo "<b>TRUE: " . ($r ?? $res) . "</b><br>";
		else
			echo "FALSE: " . $res . "<br>";
	}
}
//End Test Region
?>