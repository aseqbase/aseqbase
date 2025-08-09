<?php

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
	public static float $Version = 3.70000;
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
	 * All sequences from aseq to base
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

require_once(__DIR__ . DIRECTORY_SEPARATOR . "global" . DIRECTORY_SEPARATOR . "AddressBase.php");

\_::$Address = new AddressBase();

\_::$Sequences = [
	str_replace(["\\", "/"], DIRECTORY_SEPARATOR, $GLOBALS["DIR"] ?? "")
	=> str_replace(["\\", "/"], "/", $GLOBALS["ROOT"] ?? ""),
	...($GLOBALS["SEQUENCES"] ?? []),
	str_replace(["\\", "/"], DIRECTORY_SEPARATOR, __DIR__ . DIRECTORY_SEPARATOR ?? "")
	=> str_replace(["\\", "/"], "/", $GLOBALS["BASE_ROOT"] ?? "")
];

run("global/Base");
run("global/Types");

run("Address");

\_::$Aseq = new Address(
	$GLOBALS["ASEQBASE"],
	$GLOBALS["DIR"],
	getHost() . "/"//??$GLOBALS["ROOT"]
);

\_::$Base = new Address(
	$GLOBALS["BASE"],
	__DIR__ . DIRECTORY_SEPARATOR,
	$GLOBALS["BASE_ROOT"]
);

run("global/ReqBase");
run("Req");

run("global/ResBase");
run("Res");

library("Local");
library("Convert");
library("Html");
library("Style");
library("Script");
library("Internal");

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

\MiMFa\Library\Local::CreateDirectory(\_::$Aseq->LogDirectory);
\MiMFa\Library\Local::CreateDirectory(\_::$Aseq->TempDirectory);
register_shutdown_function('cleanupTemp', false);

/**
 * To check Features of an object from an array
 * @param mixed $object The source object
 * @param array $hierarchy A hierarchy of desired keys
 */
function has($object, ...$hierarchy)
{
	if(is_null($object)) return false;
	if (count($hierarchy) === 0)
		return $object !== null;
	$data = array_shift($hierarchy);
	if (is_null($data))
		return false;
	if (!is_array($data)) {
		if (is_array($object)) {
			if (isset($object[$data]))
				return has($object[$data], ...$hierarchy);
			$data = strtolower($data);
			foreach ($object as $k => $v)
				if ($data === strtolower($k))
					return has($v, ...$hierarchy);
		} else
			return has($object->{$data} ??
				$object->{strtoproper($data)} ??
				$object->{strtolower($data)} ??
				$object->{strtoupper($data)} ?? null, ...$hierarchy);
	} else {
		if (is_array($object)) {
			foreach ($data as $k => $v)
				if (is_numeric($k)) {
					if (($val = has($object, $v, ...$hierarchy)) !== null)
						return $val;
				} else
					return has($object, $k, $v, ...$hierarchy);
		} else {
			foreach ($data as $k => $v)
				if (is_numeric($k)) {
					if (($val = has($object, $v, ...$hierarchy)) !== null)
						return $val;
				} else
					return has($object, $k, $v, ...$hierarchy);
		}
		return false;
	}
}

/**
 * To get Features of an object from an array
 * @param mixed $object The source object
 * @param array $hierarchy A hierarchy of desired keys
 */
function get($object, ...$hierarchy)
{
	if (count($hierarchy) === 0)
		return $object;
	$data = array_shift($hierarchy);
	if (is_null($data))
		return null;
	if (!is_array($data)) {
		if (is_array($object)) {
			if (isset($object[$data]))
				return get($object[$data], ...$hierarchy);
			$data = strtolower($data);
			foreach ($object as $k => $v)
				if ($data === strtolower($k))
					return get($v, ...$hierarchy);
		} else
			return get(isset($object->$data)?$object->$data:(
				isset($object->{strtoproper($data)})?$object->$data:(
					isset($object->{strtolower($data)})?$object->$data:(
						isset($object->{strtoupper($data)})?$object->$data:null
					)
				)
			), ...$hierarchy);
	} else {
		$res = [];
		if (is_array($object)) {
			foreach ($data as $k => $v)
				if (is_numeric($k)) {
					if (($val = get($object, $v, ...$hierarchy)) !== null)
						$res[$k] = $val;
				} else
					$res[$k] = get($object, $k, $v, ...$hierarchy);
		} else {
			foreach ($data as $k => $v)
				if (is_numeric($k)) {
					if (($val = get($object, $v, ...$hierarchy)) !== null)
						$res[$k] = $val;
				} else
					$res[$k] = get($object, $k, $v, ...$hierarchy);
		}
		return $res;
	}
}
/**
 * To get Features of an object from an array
 * Then unset that key of the $data
 * @param mixed $object The source object
 * @param array $hierarchy A hierarchy of desired keys
 */
function grab(&$object, ...$hierarchy)
{
	if (count($hierarchy) === 0)
		return $object;
	$data = array_shift($hierarchy);
	if (is_null($data))
		return null;
	$rem = count($hierarchy) === 0;
	$res = null;
	if (!is_array($data)) {
		if (is_array($object)) {
			if (isset($object[$data])) {
				$res = $object[$data];
				if ($rem)
					unset($object[$data]);
				else
					return grab($object[$data], ...$hierarchy);
			} else {
				$data = strtolower($data);
				foreach ($object as $k => $v)
					if ($data === strtolower($k)) {
						$res = $v;
						if ($rem)
							unset($object[$k]);
						else
							return grab($object[$k], ...$hierarchy);
						break;
					}
			}
		} else {
			$key = null;
			$res = isset($object->{$key = $data})?$object->$key:(
				isset($object->{$key = strtoproper($data)})?$object->$key:(
					isset($object->{$key = strtolower($data)})?$object->$key:(
						isset($object->{$key = strtoupper($data)})?$object->$key:($key = null)
					)
				)
			);
			if ($key !== null) {
				if ($rem)
					unset($object->$key);
				else
					return grab($object->$key, ...$hierarchy);
				if (!$object)
					unset($object);
			}
		}
	} else {
		$res = [];
		$val = null;
		if (is_array($object)) {
			foreach ($data as $k => $v)
				if (is_numeric($k)) {
					if (($val = grab($object, $v, ...$hierarchy)) !== null)
						$res[$k] = $val;
				} else
					$res[$k] = grab($object, $k, $v, ...$hierarchy);
		} else {
			foreach ($data as $k => $v)
				if (is_numeric($k)) {
					if (($val = grab($object, $v, ...$hierarchy)) !== null)
						$res[$k] = $val;
				} else
					$res[$k] = grab($object, $k, $v, ...$hierarchy);
		}
	}
	return $res;
}

/**
 * To set Features of an object from an array or other object
 * @param mixed $object The destination object
 */
function set(&$object, $data)
{
	if (!is_array($data) || !is_object($object))
		try {
			return $object = $data;
		} catch (Exception $ex) {
		} else {
		foreach ($data as $k => $v) {
			find($object, $k, $key, $index);
			if ($key)
				if (is_null($index))
					set($object->$key, $v);
				else
					set($object[$key], $v);
		}
	}
	return $object;
}
/**
 * To set Features of an object from an array or other object
 * Then unset that key of the $data
 * @param mixed $object The destination object
 */
function swap(&$object, &$data)
{
	if (!is_array($data) || !is_object($object))
		try {
			$object = $data;
			unset($data);
		} catch (Exception $ex) {
		} else {
		foreach ($data as $k => $v) {
			find($object, $k, $key, $index);
			if ($key) {
				if (is_null($index))
					swap($object->$key, $v);
				else
					swap($object[$key], $v);
				unset($data[$k]);
			}
		}
	}
	return $object;
}
function async($action, $callback = null, ...$args)
{
	$pid = 1;
	if (function_exists("pcntl_fork"))
		$pid = pcntl_fork(); // Create a child process
	$result = $action(...$args);
	if ($callback)
		$callback($result);
	if (!$pid)
		exit(0); // End the child process
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
function inspect($minaccess = 0, bool|string $assign = true, bool|string|int|null $exit = true): mixed
{
	if (isValid(\_::$Config->StatusMode)) {
		if ($assign) {
			if (is_string($assign))
				\Res::Go($assign);
			else
				route(\_::$Config->StatusMode ?? \_::$Config->RestrictionRouteName, alternative: "403");
		}
		if ($exit !== false)
			exit($exit);
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
					route(\_::$Config->RestrictionRouteName, alternative: "401");
			}
			if ($exit !== false)
				exit($exit);
			return false;
		}
	}
	$b = auth($minaccess);
	if ($b !== false)
		return $b;
	if ($assign) {
		if (is_string($assign))
			\Res::Go($assign);
		elseif (startsWith(\Req::$Request, \MiMFa\Library\User::$HandlerPath))
			return true;
		else
			\Res::Load(\MiMFa\Library\User::$InHandlerPath);
	}
	if ($exit !== false)
		exit($exit);
	return $b;
}
/**
 * Check if the user has access to the page or not
 * @param int|array|null $acceptableAccess The minimum accessibility for the user, pass null to give the user access
 * @return int|bool|null The user accessibility group
 */
function auth($minaccess = null): mixed
{
	if (!\_::$Back->User)
		return \MiMFa\Library\User::CheckAccess(null, $minaccess);
	else
		return \_::$Back->User->Access($minaccess);
}
/**
 * To include once a $path by the specific $data then return results or output
 * @param string $path
 * @param mixed $data
 * @param bool $print
 * @param mixed $default
 * @return mixed The results of including a path or the printed values if $print be false
 */
function including(string $path, mixed $data = [], bool $print = true, $default = null)
{
	if (file_exists($path)) {
		ob_start();
		$res = [];
		if (endsWith($path, DIRECTORY_SEPARATOR)) {
			foreach (glob($path . "*" . \_::$Extension) as $file)
				if (!is_null($r = include_once $file))
					$res[] = $r;
		} else
			$res = include_once $path;
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
/**
 * To require once a $path by the specific $data then return results or output
 * @param string $path
 * @param mixed $data
 * @param bool $print
 * @param mixed $default
 * @return mixed The results of requiring a path or the printed values if $print be false
 */
function requiring(string $path, mixed $data = [], bool $print = true, $default = null)
{
	if (file_exists($path)) {
		ob_start();
		$res = [];
		if (endsWith($path, DIRECTORY_SEPARATOR)) {
			foreach (glob($path) as $file)
				if (!is_null($r = require_once $file))
					$res[] = $r;
		} else
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
/**
 * To seacrh and find the correct path of a file between all sequences
 * @param string|null $file The releative file path
 * @param mixed $extension The extention like ".php"
 * @param string|int $origin The start layer of the sequences (a zero started index)
 * @param int $depth How much layers it should iterate in searching
 * @return string|null The correct path of the file or null if its could not find
 */
function addressing(string|null $file = null, $extension = null, string|int $origin = 0, int $depth = 999999)
{
	$file = str_replace(["\\", "/"], DIRECTORY_SEPARATOR, $file ?? "");
	$extension = $extension ?? \_::$Extension;
	$file = preg_replace("/(?<!\\" . DIRECTORY_SEPARATOR . ")\\" . DIRECTORY_SEPARATOR . "$/", DIRECTORY_SEPARATOR . "index", $file);
	if (!endsWith($file, needles: $extension) && !endsWith($file, DIRECTORY_SEPARATOR))
		$file .= $extension;
	$path = null;
	//$toSeq = $depth < 0 ? (count(\_::$Sequences) + $depth) : ($origin + $depth);
	if(is_string($origin)) {
		take(\_::$Sequences, $origin, index:$origin);
		if(is_null($origin)) $origin = 0;
	}
	$scount = count(\_::$Sequences);
	$origin = $origin < 0 ? ($scount + $origin) : min($scount, $origin);
	$toSeq = $depth < 0 ? ($scount + $depth) : min($scount, $origin + $depth);
	$seqInd = -1;
	$file = ltrim($file, DIRECTORY_SEPARATOR);
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
/**
 * To seacrh in a specific directory in all sequences, to find a file with the name then including that
 * @param string|null $file The releative file path
 * @param mixed $extension The extention like ".php"
 * @param string|int $origin The start layer of the sequences (a zero started index)
 * @param int $depth How much layers it should iterate in searching
 * @return mixed The including results or null if its could not find
 */
function using(string|null $directory, string|null $name = null, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null, string|null $extension = null, &$used = null)
{
	try {
		renderPrepends($directory, $used = $name);
		if (
			$path =
			addressing("$directory$name", $extension, $origin, $depth) ??
			addressing($directory . ($used = $alternative), $extension, $origin, $depth)
		)
			return including($path, $data, $print, $default);
		else
			$used = null;
	} finally {
		renderAppends($directory, $name);
	}
}
/**
 * To grab a hierarchy of keys from the global $data object
 * @param array $hierarchy A hierarchy of desired keys
 */
function data(...$hierarchy)
{
	global $data;
	return grab($data, ...$hierarchy);
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
function runAll(string|null $name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
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
function run(string|null $name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->Directory, $name, $data, $print, $origin, $depth, $alternative, $default);
}
/**
 * To interprete, the specified ModelName
 * @param non-empty-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed
 */
function model(string $name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->ModelDirectory, $name, $data, $print, $origin, $depth, $alternative, $default);
}
/**
 * To interprete, the specified LibraryName
 * @param non-empty-string $name
 * @param mixed $data
 * @param bool $print
 * @return string|null The complete name of selected library class or return null if it's not found
 */
function library(string $name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->LibraryDirectory, $name, $data, $print, $origin, $depth, $alternative, $default, used: $used) ? "\\MiMFa\\Template\\$used" : null;
}
/**
 * To interprete, the specified ComponentName
 * @param non-empty-string $name
 * @param mixed $data
 * @param bool $print
 * @return string|null The complete name of selected component class or return null if it's not found
 */
function component(string $name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->ComponentDirectory, $name, $data, $print, $origin, $depth, $alternative, $default, used: $used) ? "\\MiMFa\\Component\\$used" : null;
}
/**
 * To interprete, the specified TemplateName
 * @param non-empty-string $name
 * @param mixed $data
 * @param bool $print
 * @return string|null The complete name of selected module class or return null if it's not found
 */
function module(string $name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->ModuleDirectory, $name, $data, $print, $origin, $depth, $alternative, $default, used: $used) ? "\\MiMFa\\Module\\$used" : null;
}
/**
 * To interprete, the specified TemplateName
 * @param non-empty-string $name
 * @param mixed $data
 * @param bool $print
 * @return string|null The complete name of selected template class or return null if it's not found
 */
function template(string $name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->TemplateDirectory, $name, $data, $print, $origin, $depth, $alternative, $default, used: $used) ? "\\MiMFa\\Template\\$used" : null;
}
/**
 * To interprete, the specified viewname
 * @param non-empty-lowercase-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed The result of included view or the printed data
 */
function view(string|null $name = null, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->ViewDirectory, $name ?? \_::$Config->DefaultViewName, $data, $print, $origin, $depth, $alternative, $default);
}
/**
 * To interprete, the specified regionname
 * @param non-empty-lowercase-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed The result of included region or the printed data
 */
function region(string $name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->RegionDirectory, $name, $data, $print, $origin, $depth, $alternative, $default);
}
/**
 * To interprete, the specified pagename
 * @param non-empty-lowercase-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed The result of included page or the printed data
 */
function page(string $name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->PageDirectory, $name, $data, $print, $origin, $depth, $alternative, $default);
}
/**
 * To interprete, the specified partname
 * @param non-empty-lowercase-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed The result of included part or the printed data
 */
function part(string $name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->PartDirectory, $name, $data, $print, $origin, $depth, $alternative, $default);
}
/**
 * To interprete, the specified computionname
 * @param non-empty-lowercase-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed The result of the pointed computed codes or the printed data
 */
function compute(string $name, mixed $data = [], bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->ComputeDirectory, $name, $data, $print, $origin, $depth, $alternative, $default);
}
/**
 * To interprete, the specified routename
 * @param non-empty-lowercase-string $name
 * @param mixed $data
 * @param bool $print
 * @return mixed The result of the routers or the printed data
 */
function route(string|null $name = null, mixed $data = null, bool $print = true, string|int $origin = 0, int $depth = 999999, string|null $alternative = null, $default = null)
{
	return using(\_::$Address->RouteDirectory, $name ?? \_::$Config->DefaultRouteName, $data, $print, $origin, $depth, $alternative, $default);
}
/**
 * To get the url of the selected asset
 * @param non-empty-string $directory
 * @param string|array|null $extensions An array of extensions or a string of the disired extension
 * @return string|null The complete path of selected asset or return null if it's not found
 */
function asset($directory, string|null $name = null, string|array|null $extensions = null, $optimize = false, string|int $origin = 0, int $depth = 999999, $default = null)
{
	$directory = preg_replace("/([\\\\\/]?asset[\\\\\/])|(^[\\\\\/]?)/", \_::$Address->AssetRoute, $directory ?? "");
	$i = 0;
	$extension = isset($extensions[$i++]) ? $extensions[$i++] : ($extensions ? $extensions : "");
	try {
		renderPrepends($directory, $name);
		do {
			if ($path = addressing("$directory$name", $extension, $origin, $depth))
				return getFullUrl($path, $optimize);
		} while ($extension = isset($extensions[$i]) ? $extensions[$i++] : null);
		return $default;
	} finally {
		renderAppends($directory, $name);
	}
}
/**
 * To get a Table from the DataBase
 * @param string $name The raw table name (Without any prefix)
 * @return \MiMFa\Library\DataTable The selected database's table
 */
function table(string $name, bool $prefix = true, string|int $origin = 0, int $depth = 999999, ?\MiMFa\Library\DataBase $source = null, $default = null)
{
	return new \MiMFa\Library\DataTable(
		$source ?? \_::$Back->DataBase,
		$name,
		$prefix
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
				process: function ($v, $k) {
					return \MiMFa\Library\Html::Link($v, \_::$Address->ContentRoute . strtolower($k));
				},
				keyWords: table("Content")->SelectPairs("`Name`", "`Title`", "ORDER BY LENGTH(`Title`) DESC"),
				both: true,
				caseSensitive: true
			);
		if (\_::$Config->AllowCategoryRefering)
			$value = \MiMFa\Library\Style::DoProcess(
				$value,
				process: function ($v, $k) {
					return \MiMFa\Library\Html::Link($v, \_::$Address->CategoryRoute . strtolower($k));
				},
				keyWords: table("Category")->SelectPairs("`Name`", "`Title`", "ORDER BY LENGTH(`Title`) DESC"),
				both: true,
				caseSensitive: true
			);
		if (\_::$Config->AllowTagRefering)
			$value = \MiMFa\Library\Style::DoProcess(
				$value,
				process: function ($v, $k) {
					return \MiMFa\Library\Html::Link($v, \_::$Address->TagRoute . strtolower($k));
				},
				keyWords: table("Tag")->SelectPairs("`Name`", "`Title`", "ORDER BY LENGTH(`Title`) DESC"),
				both: true,
				caseSensitive: true
			);
		if (\_::$Config->AllowUserRefering)
			$value = \MiMFa\Library\Style::DoProcess(
				$value,
				process: function ($v, $k) {
					return \MiMFa\Library\Html::Link($v, \_::$Address->UserRoute . strtolower($k));
				},
				keyWords: table("User")->SelectPairs("`Name`", "`Name`", "ORDER BY LENGTH(`Name`) DESC"),
				both: false,
				caseSensitive: true
			);
	}
	return $value;
}

/**
 * To take somthing by an exact key on a countable element
 * @param mixed $object The source object
 * @param $key The key sample to find
 * @param $index To get the index of the key (optional)
 * @return mixed
 */
function take($object, $key, int|null &$index = null, $default = null)
{
	$index = null;
	if (is_null($object) || is_null($key))
		return $object;
	if (is_array($object)) {
		$index = 0;
		foreach ($object as $k => $v){
			if ($key === $k) return $v;
			$index++;
		}
	}
	$index = null;
	return isset($object->$key)?$object->$key:$default;
}
/**
 * Find somthing by a callable function on a countable element
 * @param mixed $object The source object
 * @param $item The key sample to find
 * @param $key To get the correct spell of the key (optional)
 * @return mixed
 */
function find($object, $item, &$key = null, int|null &$index = null, $default = null)
{
	$index = $key = null;
	if (is_null($object) || is_null($item))
		return $object;
	if (is_array($object)) {
		$index = is_int($item)?$item:0;
		if (isset($object[$item]))
				return $object[$key = $item];
		$index = 0;
		$it = strtolower($item);
		foreach ($object as $k => $v){
			if ($it === strtolower($k)) {
				$key = $k;
				return $v;
			}
			$index++;
		}
	}
	$index = null;
	return
		isset($object->{$key = $item})?$object->$key:(
			isset($object->{$key = strtoproper($item)})?$object->$key:(
				isset($object->{$key = strtolower($item)})?$object->$key:(
					isset($object->{$key = strtoupper($item)})?$object->$key:(($key = null)??$default)
				)
			)
		);
}
/**
 * To seek for a result by a callable function on a countable element
 * @param mixed $object The source object
 * @param callable $by The filter $by($value, $key, $index)=> // return true if find and false when it is not find 
 * @return mixed
 */
function seek($object, callable $by, &$key = null, int|null &$index = null, $default = null)
{
	if (!is_null($object)) {
		$index = 0;
		if (!is_iterable($object)) {
			if ($by($object, null, $index))
				return $object;
		} else
			foreach ($object as $key => $value)
				if ($by($value, $key, $index++))
					return $value;
	}
	$index = null;
	return $default;
}
/**
 * To filter and return all succeed results by a callable function on a countable element
 * @param mixed $object The source object
 * @param callable $by The filter $by($value, $key, $index)=> // return true if find and false when it is not find 
 * @return mixed
 */
function filter(&$object, callable $by, $default = null)
{
	if (!is_null($object)) {
		$results = [];
		$index = 0;
		if (!is_iterable($object)) {
			if ($by($object, null, $index))
				$results = $object;
		} else
			foreach ($object as $key => $value)
				if ($by($value, $key, $index++)){
					$results[$key] = $value;
					unset($object[$key]);
				}
	}
	return $results??$default;
}
/**
 * To search and return all succeed results by a callable function on a countable element
 * @param mixed $object The source object
 * @param callable $by The filter $by($value, $key, $index)=> // return true if find and false when it is not find 
 * @return mixed
 */
function search($object, callable $by)
{
	if (!is_null($object)) {
		$index = 0;
		if (!is_iterable($object)) {
			if ($by($object, null, $index))
				yield $object;
		} else
			foreach ($object as $key => $value)
				if ($by($value, $key, $index++))
					yield $value;
	}
}

/**
 * Do a loop action by a callable function on a countable element
 * @param mixed $array An array or an intiger to iterate
 * @param callable $action The loop action $action($value, $key, $index)
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
 * @param mixed $array An array or an intiger to iterate
 * @param callable $action The loop action $action($value, $key, $index)
 */
function iteration($array, callable $action, $nullValues = false)
{
	if (!is_null($array)) {
		$i = 0;
		if (!is_iterable($array)) {
			if(is_int($array))
				for (; $i < $array; $i++)
					if (($res = $action($array, null, $i)) !== null || $nullValues)
						yield $res;
			elseif (($res = $action($array, null, $i)) !== null || $nullValues)
				yield $res;
		} else
			foreach ($array as $key => $value)
				if (($res = $action($value, $key, $i++)) !== null || $nullValues)
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
		foreach (array_reverse($dic) as $k => $v)
			$html = str_replace($v, $k, $html);
	return $html;
}
/**
 * Encrypt plain by the key or the website secret key
 * @param mixed $plain The plain text
 * @param mixed $key Leave null to use default soft key
 */
function encrypt($plain, $key = null)
{
	if (is_null($plain))
		return null;
	if (empty($plain))
		return $plain;
	return \_::$Back->Cryptograph->Encrypt($plain, $key ?? \_::$Config->SoftKey, true);
}
/**
 * Decrypt cipher by the key or the website secret key
 * @param mixed $cipher The cipher text
 * @param mixed $key Leave null to use default soft key
 */
function decrypt($cipher, $key = null)
{
	if (is_null($cipher))
		return null;
	if (empty($cipher))
		return $cipher;
	return \_::$Back->Cryptograph->Decrypt($cipher, $key ?? \_::$Config->SoftKey, true);
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

/**
 * Get the full part of a url pointed to cache status
 * @example: "/Category/mimfa/service/web.php?p=3&l=10#serp" => "https://www.mimfa.net:5046/Category/mimfa/service/web.php?p=3&l=10#serp&v=3.21"
 * @return string|null
 */
function getFullUrl(string|null $path = null, bool $optimize = true): string|null
{
	if ($path === null)
		$path = getUrl();
	if ($optimize)
		return \MiMFa\Library\Local::OptimizeUrl(\MiMFa\Library\Local::GetUrl($path));
	return \MiMFa\Library\Local::GetUrl($path);
}
/**
 * Get the full part of a url
 * @example: "/Category/mimfa/service/web.php?p=3&l=10#serp" => "https://www.mimfa.net:5046/Category/mimfa/service/web.php?p=3&l=10#serp"
 * @return string|null
 */
function getUrl(string|null $path = null): string|null
{
	if ($path === null)
		$path = (
			takeValid($_SERVER, 'SCRIPT_URI') ??
			(((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http") .
			"://" . $_SERVER["HTTP_HOST"] . takeBetween($_SERVER, "REQUEST_URI", "PHP_SELF")
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
	return PREG_Replace("/(^\w+:\/*[^\/]+)/", "", $path);
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

/**
 * Get the request method name =>
 * GET:1,
 * POST:2,
 * PUT:3,
 * FILE:4,
 * PATCH:5,
 * DELETE:6,
 * STREAM:7,
 * INTERNAL:8,
 * EXTERNAL:9,
 * OTHER:0
 * @param string|int|null $method
 * @return int|string
 */
function getMethodName(string|int|null $method = null)
{
	switch (strtoupper($method ?? "")) {
		case 1:
		case "PUBLIC":
		case "GET":
			return "GET";
		case 2:
		case "PRIVATE":
		case "POST":
			return "POST";
		case 3:
		case "PUT":
			return "PUT";
		case 4:
		case "FILES":
		case "FILE":
			return "POST";
		case 5:
		case "PATCH":
			return "PATCH";
		case 6:
		case "DELETE":
		case "DEL":
			return "DELETE";
		case 7:
		case "STREAM":
			return "STREAM";
		case 8:
		case "INTER":
		case "INTERNAL":
			return "INTERNAL";
		case 9:
		case "EXTER":
		case "EXTERNAL":
			return "EXTERNAL";
		default:
			return strtoupper($method ?? $_SERVER['HTTP_X_CUSTOM_METHOD'] ?? $_SERVER['REQUEST_METHOD'] ?? "OTHER");
	}
}
/**
 * Get the request method index =>
 * All:0
 * GET:1,
 * POST:2,
 * PUT:3,
 * FILE:4,
 * PATCH:5,
 * DELETE:6,
 * STREAM:7,
 * INTERNAL:8,
 * EXTERNAL:9,
 * OTHER:10
 * @param string|int|null $method
 * @return int|string
 */
function getMethodIndex(string|int|null $method = null)
{
	switch (strtoupper($method ?? $_SERVER['HTTP_X_CUSTOM_METHOD'] ?? $_SERVER['REQUEST_METHOD'])) {
		case 1:
		case "PUBLIC":
		case "GET":
			return 1;
		case 2:
		case "PRIVATE":
		case "POST":
			return 2;
		case 3:
		case "PUT":
			return 3;
		case 4:
		case "FILES":
		case "FILE":
			return 4;
		case 5:
		case "PATCH":
			return 5;
		case 6:
		case "DELETE":
		case "DEL":
			return 6;
		case 7:
		case "STREAM":
			return 7;
		case 8:
		case "INTER":
		case "INTERNAL":
			return 8;
		case 9:
		case "EXTER":
		case "EXTERNAL":
			return 9;
		default:
			return 10;
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

/**
 * To cleanup all Temporary files, or received files in this request
 * @param mixed $full True to cleanup all Temporary files, false to cleanup only received files in this request
 */
function cleanupTemp($full = true)
{
	if ($full)
		return cleanup(\_::$Address->TempDirectory);
	$i = 0;
	foreach ($_FILES as $file)
		if (isset($file["tmp_name"]) && is_file($file["tmp_name"]) && ++$i)
			unlink($file["tmp_name"]);
	return $i;
}
/**
 * Iterate through the files of the directory and delete them
 * @param mixed $directory
 */
function cleanup($directory = null)
{
	$i = 0;
	if ($directory) {
		foreach (glob("$directory*") as $file)
			if (is_file($file) && ++$i)
				unlink($file);
	} else {
		$i += cleanup(\_::$Address->TempDirectory);
		$i += cleanup(\_::$Aseq->TempDirectory);
		$i += cleanup(\_::$Base->TempDirectory);
		$i += cleanup(\_::$Address->LogDirectory);
		$i += cleanup(\_::$Aseq->LogDirectory);
		$i += cleanup(\_::$Base->LogDirectory);
		flushSessions();
		\_::$Back->Session->Flush();
	}
	return $i;
}

function grabMemo($key)
{
	$val = getMemo($key);
	forgetMemo($key);
	return $val;
}
function setMemo($key, $value, $expires = 0, $path = "/")
{
	if ($value == null)
		return false;
	return setcookie(urlencode($key), urlencode($value), ceil($expires / 1000), $path, "", true, true);
}
function getMemo($key)
{
	if (isset($_COOKIE[$key]))
		return urldecode($_COOKIE[$key]);
	else
		return null;
}
function hasMemo($key)
{
	return !is_null(getMemo($key));
}
function forgetMemo($key)
{
	unset($_COOKIE[urlencode($key)]);
	return setcookie(urlencode($key), "", 0, "/", "", true, true);
}
function flushMemos()
{
	foreach ($_COOKIE as $key => $val) {
		unset($_COOKIE[$key]);
		return setcookie($key, "", 0, "/", "", true, true);
	}
}

function grabSession($key)
{
	$val = getSession($key);
	forgetSession($key);
	return $val;
}
function setSession($key, $value)
{
	return $_SESSION[$key] = $value;
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
function flushSessions()
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
function getClientCode(): string|null
{
	return md5(getClientIp() ?? $_SERVER['HTTP_USER_AGENT']);
}

/**
 * @deprecated
 */
function fetchValue(string|null $source, string|null $key, bool $ismultiline = true)
{
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

function isEmpty($object): bool
{
	return !isset($object) || is_null($object) || (is_string($object) && (trim($object . "", " \n\r\t\v\f'\"") === "")) || (is_countable($object) && count($object) === 0);
	//return $object?(is_string($object) && (trim($object . "", " \n\r\t\v\f'\"") === "")):true;
}
function isValid($object, string|null $item = null): bool
{
	if ($item === null)
		return isset($object) && !is_null($object) && (!is_string($object) || !(trim($object) == "" || trim($object, "'\"") == ""));
	//return $object?true:false;
	else
		return isValid($object) && isValid($item) && ((isset($object[$item]) && isValid($object[$item])) || (isset($object->$item) && isValid($object->$item)));
}
function takeValid($object, string|null $item = null, $defultValue = null)
{
	if (isValid($object, $item)) {
		if ($item === null)
			return $object;
		if (isset($object[$item]))
			return $object[$item];
		return $object->$item;
	} else
		return $defultValue;
}
function getValid($object, string|null $item = null, $defultValue = null, &$key = null)
{
	if ($object === null || $item === null)
		return isValid($object) ? $object : $defultValue;
	if (is_array($object)) {
		if (isset($object[$item]))
			return isValid($object[$key = $item]) ? $object[$item] : $defultValue;
		$item = strtolower($item);
		foreach ($object as $k => $v)
			if ($item === strtolower($k)) {
				$key = $k;
				return isValid($v) ? $v : $defultValue;
			}
	}
	$res =
		$object->{$key = $item} ??
		$object->{$key = strtoproper($item)} ??
		$object->{$key = strtolower($item)} ??
		$object->{$key = strtoupper($item)} ??
		($key = null);
	return isValid($res) ? $res : $defultValue;
}
function grabValid(&$object, string|null $item = null, $defultValue = null, &$key = null)
{
	if ($object === null || $item === null)
		return isValid($object) ? $object : $defultValue;
	if (is_array($object)) {
		if (isset($object[$item])) {
			$res = $object[$key = $item] ?? $defultValue;
			unset($object[$item]);
			return isValid($res) ? $res : $defultValue;
		}
		$item = strtolower($item);
		foreach ($object as $k => $v)
			if ($item === strtolower($k)) {
				$key = $k;
				unset($object[$k]);
				return isValid($v) ? $v : $defultValue;
			}
	}
	$res =
		$object->{$key = $item} ??
		$object->{$key = strtoproper($item)} ??
		$object->{$key = strtolower($item)} ??
		$object->{$key = strtoupper($item)} ??
		($key = null) ?? $defultValue;
	if ($key !== null)
		unset($object->$key);
	return isValid($res) ? $res : $defultValue;
}
function doValid(callable $func, $object, string|null $item = null, $defultValue = null)
{
	return isValid($object, $item) ? $func(getValid($object, $item)) : $defultValue;
}
function takeBetween($object, ...$items)
{
	foreach ($items as $value)
		if (($value = getValid($object, $value, null)) !== null)
			return $value;
	return null;
}
function getBetween($object, ...$items)
{
	foreach ($items as $value)
		if (($value = getValid($object, $value, null)) !== null)
			return $value;
	return null;
}
function grabBetween(&$object, ...$items)
{
	foreach ($items as $value)
		if (($value = grabValid($object, $value, null)) !== null)
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
	return isUrl($url) && isFormat(getPath($url), $formats);
}
/**
 * Check if the string is a relative or absolute URL
 * @param null|string $url The url string
 * @return bool
 */
function isUrl(string|null $url): bool
{
	return (!empty($url)) && preg_match("/^([A-z0-9\-]+\:)?([\/\?\#]([^:\/\{\}\|\^\[\]\"\`\r\n\t\f]*)|(\:\d+))+$/", $url);
}
/**
 * Check if the string is only a relative URL
 * @param null|string $url The url string
 * @return bool
 */
function isRelativeUrl(string|null $url): bool
{
	return (!empty($url)) && preg_match("/^([\/\?\#]([^:\/\{\}\|\^\[\]\"\`\r\n\t\f]*)|(\:\d+))+$/", $url);
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
function isScript(mixed $script): bool
{
	return !is_string($script) ||
		((!empty($script))
			&& !preg_match("/^[A-z0-9\-\.\_]+\@([A-z0-9\-\_]+\.[A-z0-9\-\_]+)+$/", $script)
			&& !preg_match("/^[A-z0-9\-]+\:\/*([\/\?\#][^\/\{\}\|\^\[\]\"\`\r\n\t\f]*)+$/", $script)
			&& preg_match("/[\{\}\|\^\[\]\"\`\;\r\n\t\f]|((^\s*[\w\$][\w\d\$\_\.]+\s*\([\s\S]*\)\s*)+;?\s*$)/", $script));
}
/**
 * To check if the string is a JSON or not
 * @param null|string $json The json string
 */
function isJson($json)
{
	if (isEmpty($json))
		return null;
	if (!is_string($json)) return false;
	return preg_match("/^\s*[\{|\[][\s\S]*[\}\]]\s*$/", $json)>0;
}
/**
 * Check if the string is a relative or absolute URL
 * @param null|string $url The url string
 * @return bool
 */
function isEmail(string|null $email): bool
{
	return (!empty($email)) && preg_match("/^[A-z0-9\-\.\_]+\@([A-z0-9\-\_]+\.[A-z0-9\-\_]+)+$/", $email);
}
/**
 * Check if the string is a regex pattern or not
 * @param null|string $text The text string
 * @return bool
 */
function isPattern(string $text): bool
{
	return preg_match("/^\/[\s\S]+\/[gimsxU]{0,6}$/", $text);
}


/**
 * Check if the string is a suitable name for a class or id or name field
 * @param null|string $text The url string
 * @return bool
 */
function isIdentifier(string|null $text): bool
{
	return (!empty($text)) && preg_match("/^[a-z_\$][a-z0-9_\-\$]*$/i", $text);
}

/**
 * Check if the value is a static type like string or number or other static types
 * @param null|string $value Desired value
 * @return bool
 */
function isStatic($value): bool
{
	return is_string($value) || is_numeric($value) || is_bool($value) || is_null($value);
}

/**
 * Remove all changeable command signs from a url (such as ../ or /./.)
 * Change all backslashes to the slash
 * @param string $path The source path
 * @return array|string|null
 */
function normalizeUrl(string $path): string|null
{
	return str_replace("\\", "/", preg_replace("/([\/\\\]\.+)|(\.+[\/\\\])/", "", $path));
}
/**
 * Remove all changeable command signs from a path (such as ../ or /./.)
 * Change all slashes/backslashes to the DIRECTORY_SEPARATOR
 * @param string $path The source path
 * @return array|string|null
 */
function normalizePath(string $path): string|null
{
	return str_replace(["\\", "/"], DIRECTORY_SEPARATOR, preg_replace("/([\/\\\]\.+)|(\.+[\/\\\])/", "", $path));
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
 * @deprecated
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
 * Make a string to  ProperCase
 * @param string $string — The input string.
 * @return string — the ProperCased string.
 */
function strToProper($string)
{
	if (empty($string))
		return $string;
	return preg_replace_callback("/\b[a-z]/", fn($v) => strtoupper($v[0]), $string);
}
/**
 * Make a string to camleCase
 * @param string $string — The input string.
 * @return string — the camelCased string.
 */
function strToCamel($string)
{
	if (empty($string))
		return $string;
	$string =  preg_replace_callback("/(?<=[^A-Za-z])[a-z]/", fn($v) => strtoupper($v[0]), $string);
	return strtolower(substr($string,0,1)).substr($string,1);
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
 * @param callable $searching function($val, $key){ return true; }
 * @param array $array_find_keys
 */
function array_find_keys($array, callable $searching)
{
	return array_filter($array, function ($v, $k) use ($searching) {
		return $searching($v, $k);
	}, ARRAY_FILTER_USE_BOTH);
}

// //Test Region
// function test_server()
// {
// 	foreach ($_SERVER as $k => $v)
// 		echo "<br>" . "$k: " . $v;
// 	// echo "<br>"."PHP_SELF: ".$_SERVER['PHP_SELF'];
// 	// echo "<br>"."GATEWAY_INTERFACE: ".$_SERVER['GATEWAY_INTERFACE'];
// 	// echo "<br>"."SERVER_ADDR: ".$_SERVER['SERVER_ADDR'];
// 	// echo "<br>"."SERVER_NAME: ".$_SERVER['SERVER_NAME'];
// 	// echo "<br>"."SERVER_SOFTWARE: ".$_SERVER['SERVER_SOFTWARE'];
// 	// echo "<br>"."SERVER_PROTOCOL: ".$_SERVER['SERVER_PROTOCOL'];
// 	// echo "<br>"."REQUEST_METHOD: ".$_SERVER['REQUEST_METHOD'];
// 	// echo "<br>"."REQUEST_TIME: ".$_SERVER['REQUEST_TIME'];
// 	// echo "<br>"."QUERY_STRING: ".$_SERVER['QUERY_STRING'];
// 	// echo "<br>"."HTTP_ACCEPT: ".$_SERVER['HTTP_ACCEPT'];
// 	// echo "<br>"."HTTP_ACCEPT_CHARSET: ".$_SERVER['HTTP_ACCEPT_CHARSET'];
// 	// echo "<br>"."HTTP_HOST: ".$_SERVER['HTTP_HOST'];
// 	// echo "<br>"."HTTP_REFERER: ".$_SERVER['HTTP_REFERER'];
// 	// echo "<br>"."HTTPS: ".$_SERVER['HTTPS'];
// 	// echo "<br>"."REMOTE_ADDR: ".$_SERVER['REMOTE_ADDR'];
// 	// echo "<br>"."REMOTE_HOST: ".$_SERVER['REMOTE_HOST'];
// 	// echo "<br>"."REMOTE_PORT: ".$_SERVER['REMOTE_PORT'];
// 	// echo "<br>"."SCRIPT_FILENAME: ".$_SERVER['SCRIPT_FILENAME'];
// 	// echo "<br>"."SERVER_ADMIN: ".$_SERVER['SERVER_ADMIN'];
// 	// echo "<br>"."SERVER_PORT: ".$_SERVER['SERVER_PORT'];
// 	// echo "<br>"."SERVER_SIGNATURE: ".$_SERVER['SERVER_SIGNATURE'];
// 	// echo "<br>"."PATH_TRANSLATED: ".$_SERVER['PATH_TRANSLATED'];
// 	// echo "<br>"."SCRIPT_NAME: ".$_SERVER['SCRIPT_NAME'];
// 	// echo "<br>"."SCRIPT_URI: ".$_SERVER['SCRIPT_URI'];
// }
// function test_address($directory = null, string $name = "Configuration")
// {
// 	echo addressing($directory ?? \_::$Address->Directory, $name);
// 	echo "<br>ASEQ: " . \_::$Aseq->Name;
// 	echo "<br>ASEQ->Path: " . \_::$Aseq->Path;
// 	echo "<br>ASEQ->Dir: " . \_::$Aseq->Directory;
// 	echo "<br>OTHER ASEQ: <br>";
// 	var_dump(\_::$Aseq);
// 	echo "<br>BASE: " . \_::$Base->Name;
// 	echo "<br>BASE->Path: " . \_::$Base->Path;
// 	echo "<br>BASE->Dir: " . \_::$Base->Directory;
// 	echo "<br>OTHER BASE: <br>";
// 	var_dump(\_::$Base);
// 	echo "<br><br>ADDRESSES: <br>";
// 	var_dump(\_::$Address);
// }
// function test_url()
// {
// 	echo "<br>URL: " . \Req::$Url;
// 	echo "<br>HOST: " . \Req::$Host;
// 	echo "<br>SITE: " . \Req::$Site;
// 	echo "<br>PATH: " . \Req::$Path;
// 	echo "<br>REQUEST: " . \Req::$Request;
// 	echo "<br>DIRECTION: " . \Req::$Direction;
// 	echo "<br>QUERY: " . \Req::$Query;
// 	echo "<br>FRAGMENT: " . \Req::$Fragment;
// }
// function test_access($func, $res = null)
// {
// 	$r = null;
// 	if (inspect(0, false, false)) {
// 		if ($r = $func())
// 			echo "<b>TRUE: " . ($r ?? $res) . "</b><br>";
// 		else
// 			echo "FALSE: " . $res . "<br>";
// 	}
// }
// //End Test Region
?>