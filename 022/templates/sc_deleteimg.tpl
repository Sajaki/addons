<?php
/**
 * Project: SigGen - Signature and Avatar Generator for WoWRoster
 * File: /templates/sc_deleteimg.tpl
 *
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Legal Information:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 *
 * Full License:
 *  license.txt (Included within this library)
 *
 * You should have recieved a FULL copy of this license in license.txt
 * along with this library, if you did not and you are unable to find
 * and agree to the license you may not use this library.
 *
 * For questions, comments, information and documentation please visit
 * the official website at cpframework.org
 *
 * @link http://www.wowroster.net
 * @license http://creativecommons.org/licenses/by-nc-sa/2.5/
 * @author Joshua Clark
 * @version $Id:$
 * @copyright 2005-2007 Joshua Clark
 * @package SigGen
 * @filesource
 *
 */

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}
?>

<?php
	// Get regular image files
	$userFilesArr = $functions->listFiles( SIGGEN_DIR.$configData['image_dir'].$configData['user_dir'],array('png','gif','jpeg','jpg') );

?>
<!-- Begin Image Delete Box -->
<?php
if( $allow_upload )
{
?>
  <form method="post" action="<?php print $script_filename; ?>" enctype="multipart/form-data" name="image_delete" onsubmit="submitonce(this)">
<?php print border('sgray','start','<div style="width:187px;"><img src="'.$roster_conf['img_url'].'blue-question-mark.gif" style="float:right;" alt="" />'.$functions->createTip( 'Images are currently located in:<br />\n&quot;'.str_replace('\\','/',SIGGEN_DIR.$configData['image_dir'].$configData['user_dir']).'&quot;','Delete User Images' ).'</div>'); ?>
    <table width="198" class="sc_table" cellspacing="0" cellpadding="2">
      <tr>
        <td class="sc_row_right1" align="center">Character Image:
          <?php print $functions->createOptionList( $userFilesArr,$name_test,'image_name',2 ); ?></td>
      </tr>
      <tr>
        <td class="sc_row_right2" align="center">
          <input type="hidden" name="sc_op" value="delete_image" />
          <input type="submit" value="Delete Image" name="delete_image" /></td>
      </tr>
    </table>
<?php print border('sgray','end'); ?>
  </form>
<?php
}
else
{
	print border('sred','start','Delete DISABLED' );
	print border('sred','end');
}
?>
<!-- End Image Delete Box -->
