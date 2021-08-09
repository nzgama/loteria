<?php

// -----------------------------------------------------------------------
// DEFINE SEPARATOR ALIASES
// -----------------------------------------------------------------------
define("URL_SEPARATOR", '/');

define("DS", DIRECTORY_SEPARATOR);

// -----------------------------------------------------------------------
// DEFINE PATHS
// -----------------------------------------------------------------------
/* How should I define the SITE_ROOT ??? 
   this is very important, by security */
if ( !defined('SITE_ROOT') ) {
	$INC_PATH = dirname(__FILE__);
	$i = strrpos($INC_PATH, DS);
	if ( $i !== FALSE ) {
		/* === By Yoel.- 2020.05.26 ===
		 * My understanding:
		 *
		 * SITE_ROOT is one level above of the folder "includes", 
		 * this way if INC_PATH = /localhost/includes, then
		 * then the SITE_ROOT is deducted as /localhost ... as it 
		 * should be. 
		 * Also, this script is SUPPOSED to be always into the 
		 * folder localhost/includes */
		define( "SITE_ROOT", substr($INC_PATH, 0, $i) );

		// === debug (DON'T keep it) ===
		//echo SITE_ROOT;
	}
	else
		;
}

defined("INC_ROOT")? null: define("INC_ROOT", realpath(dirname(__FILE__)));
define("LIB_PATH_INC", INC_ROOT.DS);

/* TimeZone */
date_default_timezone_set("America/Argentina/Buenos_Aires");    // UTC-6

require_once(LIB_PATH_INC.'config.php');
require_once(LIB_PATH_INC.'functions.php');
require_once(LIB_PATH_INC.'session.php');
require_once(LIB_PATH_INC.'upload.php');
require_once(LIB_PATH_INC.'database.php');
require_once(LIB_PATH_INC.'sql.php');
