<?php
/**
 * The Global Static Variables
 * It contains the most useful objects along developments
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Kernel See the Documentation
 */
class _
{
	public static int $DynamicId = 0;
	/**
	 * The version of aseqbase framework
	 * Generation	.	Major	Minor	1:test|2:alpha|3:beta|4:release|5<=9:stable|0:base
	 * X			.	xx		xx		x
	 */
	public static float $Version = 7.99900;
	/**
	 * The default files extensions
	 * @example
     * ".php"
	 */
	public static string|null $Extension = ".php";

	/**
	 * All sequence from aseq to base
	 * @example
	 * [
	 *	'home/domain/aseq/' => 'https://aseq.domain.tld/',
	 *	'home/domain/1stseq/' => 'https://1stseq.domain.tld/',
	 *	'home/domain/2ndseq/' => 'https://2ndseq.domain.tld/',
	 *	'home/domain/3rdseq/' => 'https://3rdseq.domain.tld/',
	 *	'home/domain/base/' => 'https://base.domain.tld/'
	 * ]
	 */
	public static array $Sequence;
	public static $Address;

	/**
	 * To access all back-end tools
	 */
	public static $Back;

	/**
	 * To access all addresses to a sequence of the website
	 * and an array of all method=>patterns=>handler view names to handle all type request virtual paths
	 */
	public static $Router;

	/**
	 * To access all front-end tools
	 */
	public static $Front;

	/**
	 * To access the user service
	 */
	public static $User;
	
	/**
	 * A Directory=>Name=>Fucntion array to apply the Function before using the Path
	 * @var mixed
	 */
	public static array $BeforeActions = array();
	/**
	 * A Directory=>Name=>Fucntion array to apply the Function after using the Path
	 * @var mixed
	 */
	public static array $AfterActions = array();
	
	/**
	 * To check if the results are rendered on the client side or not
	 * @return bool
	 */
	public static function IsRendered(){
		return headers_sent();
	} 

	/**
	 * The storage of all cached data
	 * @var array
	 */
	public static array $Caches = [];
	/**
	 * Get the cached data or cache the data by execute the $generator
	 * @param mixed $key
	 * @param callable|null $generator Send null to clear cache
	 */
	public static function Cache($key, callable|null $generator = null){
		$key=isStatic($key)?($key?:0):gettype($key);
		if(isset(self::$Caches[$key])) return self::$Caches[$key];
		elseif(!$generator) unset(self::$Caches[$key]);
		else return self::$Caches[$key] = $generator($key);
	}
}