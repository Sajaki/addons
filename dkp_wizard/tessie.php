<?
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

$itemArrayRow['item_texture'] = "Interface\\Icons\\Test_1";
print($itemArrayRow['item_texture']."<br><br>");
$itemArrayRow['item_texture'] = preg_replace("/Interface\\\\\\\\Icons\\\\\\\\/", '', $itemArrayRow['item_texture']);
$itemArrayRow['item_texture'] = preg_replace("/Interface\\\\Icons\\\\/", '', $itemArrayRow['item_texture']);
print($itemArrayRow['item_texture']."<br><br>");



?>
