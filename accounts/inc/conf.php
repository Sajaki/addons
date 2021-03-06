<?php 
/** 
 * Dev.PKComp.net Accounts Addon
 * 
 * LICENSE: Licensed under the Creative Commons 
 *          "Attribution-NonCommercial-ShareAlike 2.5" license 
 * 
 * @copyright  2005-2007 Pretty Kitty Development 
 * @author	   mdeshane
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5" 
 * @link       http://dev.pkcomp.net 
 * @package    Accounts 
 * @subpackage Conf File
 */ 
 
if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

if( !isset($addon))
{
	$addon = getaddon('accounts');
}

if( !isset($accounts))
{
	include_once( $addon['inc_dir'] . 'accounts.lib.php' );
	$accounts = new accounts;

	if( !isset($accounts->plugin))
	{
		include_once( $addon['inc_dir'] . 'plugin.lib.php');
		$accounts->plugin = new accountsPlugin;
	}

	if( !isset($accounts->session))
	{
		include_once( $addon['inc_dir'] . 'session.lib.php');
		$accounts->session = new accountsSession;
	}

	if( !isset($accounts->form))
	{
		include_once( $addon['inc_dir'] . 'form.lib.php');
		$accounts->form = new accountsForm;
	}

	if( !isset($accounts->admin))
	{
		include_once( $addon['inc_dir'] . 'admin.lib.php');
		$accounts->admin = new accountsAdmin;
	}

	if( !isset($accounts->page))
	{
		include_once( $addon['inc_dir'] . 'page.lib.php');
		$accounts->page = new accountsPage;
	}

	if( !isset($accounts->user))
	{
		include_once( $addon['inc_dir'] . 'user.lib.php');
		$accounts->user = new accountsUser;
	}

	if( !isset($accounts->profile))
	{
		include_once( $addon['inc_dir'] . 'profile.lib.php');
		$accounts->profile = new accountsProfile;
	}

	if( !isset($accounts->messaging))
	{
		include_once( $addon['inc_dir'] . 'messaging.lib.php');
		$accounts->messaging = new accountsMessaging;
	}

}
?>