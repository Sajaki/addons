<?php
/** 
 * Dev.PKComp.net WoWRoster Addon
 * 
 * LICENSE: Licensed under the Creative Commons 
 *          "Attribution-NonCommercial-ShareAlike 2.5" license 
 * 
 * @copyright  2005-2007 Pretty Kitty Development 
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5" 
 * @link       http://dev.pkcomp.net 
 * @package    Accounts 
 * @subpackage User Admin
 */
if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

include_once ($addon['inc_dir'] . 'admin.lib.php');

$admin_update = new userAdmin;

if ($admin_update->usePerms == 1)
{
	$admin_update->accessPage($_SERVER['PHP_SELF'], $_SERVER['QUERY_STRING'], $admin_update->adminGroup); // check page permissions
}

if (isset($_POST['Submit']))
{
	if ($_POST['Submit'] == $roster->locale->act['config_submit_button'])
	{
		$conf_str = (isset($_POST['confMail'])) ? $_POST['confMail'] : ""; // the checkbox value to send a confirmation mail 
		$admin_update->updateUserByAdmin($_POST['user'], $_POST['level'], $_POST['uid'], $_POST['userPass'], $_POST['userEMail'], $_POST['activation'], $conf_str);
		$admin_update->getUserData($_POST['uname'], 'uname'); // this is needed to get the modified data after update
	}
	elseif ($_POST['Submit'] == $roster->locale->act['search'])
	{
		if ($_POST['userEMail'] == '')
		{
			$admin_update->getUserData($_POST['uname'], 'uname');
		}
		elseif ($_POST['uname'] == '')
		{
			$admin_update->getUserData($_POST['userEMail'], 'email');
		}
	}
}
elseif (isset($_GET['uid']) && intval($_GET['uid']) > 0)
{
	$admin_update->getUserData($_GET['uid'], 'uid');
} 

$error = $admin_update->message; // error message

echo '<!-- Begin Admin Form -->';
echo border('sgold','start', $roster->locale->act['accounts_user_admin']);
echo '<table class="bodyline" cellspacing="0" cellpadding="0" width="100%">';
echo     '<tr>';
echo     '<form name="userAdmin" method="post" action="' . makelink() . '">';

if ($admin_update->userFound == true)
{     
	echo         '<td class="membersRow1">';
    echo             '<span style="float:left">' . $roster->locale->act['account_uname'] . ':</span>';
    echo             '<span style="float:right"><input type="text" name="user" class="wowinput128" value="' . $admin_update->uname . '"></span>';
	echo         '</td>';
	echo     '</tr>';
	echo     '<tr>';
	echo         '<td class="membersRow2">';
    echo             '<span style="float:left">' . $roster->locale->act['new_pass'] . ':</span>';
    echo             '<span style="float:right"><input type="password" name="userPass" class="wowinput128"></span>';
	echo         '</td>';
	echo     '</tr>';
	echo     '<tr>';
	echo         '<td class="membersRow1">';
    echo             '<span style="float:left">' . $roster->locale->act['account_email'] . '</span>';
    echo             '<span style="float:right"><input type="text" name="userEMail" class="wowinput128" value="' . $admin_update->oldUserEMail . '"></span>';
	echo         '</td>';
	echo     '</tr>';
	echo     '<tr>';
	echo         '<td class="membersRow2">';
    echo             '<span style="float:left">' . $roster->locale->act['user_group'] . '</span>';
    echo             '<span style="float:right">' . $admin_update->accessLevelMenu($admin_update->userGroupID) . '</span>';
	echo         '</td>';
	echo     '</tr>';
	echo	 '<tr>';
	echo		 '<td class="membersRow1">';
	echo	 	 	 $admin_update->activationSwitch();
	echo 		 '</td>';
	echo 	 '</tr>';
	echo     '<tr>';
	echo         '<td class="membersRow2">';
    echo             '<span style="float:left">' . $roster->locale->act['conf_mail'] . ':</span>';
    echo             '<span style="float:right"><input type="radio" name="confMail" class="checkBox" value="yes"></span>';
	echo         '</td>';
	echo     '</tr>';
	echo     '<tr>';
	echo         '<td class="membersHeader">';
	echo			 '<input type="hidden" name="uid" value="' . $admin_update->uid . '">';
    echo			 '<input type="hidden" name="uname" value="' . $admin_update->uname . '">';
    echo             '<span style="float:right"><input type="submit" name="Submit" value="' . $roster->locale->act['config_submit_button'] . '"></span>';
	echo         '</td>';
	echo     '</tr>';
}
else
{
	echo         '<td class="membersRow1">';
	echo			 '<span style="float:left">' . $roster->locale->act['account_uname'] . ':</span>';
    echo             '<span style="float:right"><input type="text" name="uname" class="wowinput128"></span>';
	echo         '</td>';
	echo     '</tr>';
	echo     '<tr>';
	echo         '<td class="membersRow2">';
    echo             '<span style="float:left">' . $roster->locale->act['account_email'] . '</span>';
    echo             '<span style="float:right"><input type="text" name="userEMail" class="wowinput192"></span>';
	echo         '</td>';
	echo     '</tr>';
	echo     '<tr>';
	echo         '<td class="membersHeader">';
    echo             '<span style="float:right"><input type="submit" name="Submit" value="' . $roster->locale->act['search'] . '"></span>';
	echo         '</td>';
	echo     '</tr>';
}

echo     '</form>';
echo '</table>';
echo border('sgold','end');
echo '<p><b>' . (isset($error)) ? $error : "&nbsp;" . '</b></p>';
echo '<p>&nbsp;</p>';
echo '<p><a href="' . makelink('util-accounts-index') . '">' . $roster->locale->act['account_main_page'] . '</a></p><br />';
echo '<!-- End Admin Form -->';
