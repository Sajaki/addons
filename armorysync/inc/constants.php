<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Contants and defines file for ArmorySync
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 2.6.0
 * @package    ArmorySync
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

define('ARMORYSYNC_STARTTIME', isset($_POST['ARMORYSYNC_STARTTIME']) ? $_POST['ARMORYSYNC_STARTTIME']: format_microtime());
define('ARMORYSYNC_CACHE', ROSTER_CACHEDIR );
define('ARMORYSYNC_VERSION','1.0');

define('ARMORYSYNC_REQUIRED_ROSTER_VERSION','2.1.9.2400');
