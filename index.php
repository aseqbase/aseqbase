<?php /* MiMFa aseqbase	http://aseqbase.ir */
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//ini_set('display_startup_errors', E_ALL);

/*
 * Change the value, to the current subdomains sequence (like [my-subdomain-name])
 * or if this file is in the root address, leave null for that
 */
// $relativePath = __DIR__;//str_replace(str_replace(["\\","/"], DIRECTORY_SEPARATOR, $_SERVER['DOCUMENT_ROOT']), "", subject: __DIR__);
// $dirs = preg_split("/[\/\\\]/", trim($relativePath, "/\\"));
$GLOBALS["ASEQ"] = null;//end($dirs);//join(".", $dirs);/* Change it to null if the file is in the root directory */
$GLOBALS["BASE"] = ".aseq";/* Change it to the base directory if deferents */

/*
    An array to apply your custom changes in \_::$Sequences
	newdirectory, newaseq;// Add new directory to the \_::$Sequences
    directory, newaseq;// Update directory in the \_::$Sequences
    directory, null;// Remove thw directory from the \_::$Sequences
*/
$GLOBALS["SEQUENCES_PATCH"] = array();

require_once(__DIR__.DIRECTORY_SEPARATOR."initialize.php");
require_once($GLOBALS["BASE_DIR"]."index.php");
?>