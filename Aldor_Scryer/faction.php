<?php
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
 * $Id: membersRep.php 19 2006-12-27 06:25:26Z zanix $
 *
 ******************************/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

	$lang = $roster_conf['roster_lang'];

	$query = "SELECT DISTINCT m.member_id, m.name member, p.server, r.faction, r.name fct_name, r.curr_rep, r.max_rep, r.standing ".
		"FROM `".ROSTER_REPUTATIONTABLE."` r, ".ROSTER_MEMBERSTABLE." m, ".ROSTER_PLAYERSTABLE." p, `".ROSTER_SKILLSTABLE."` s ".
		"WHERE r.member_id = m.member_id ".
		"AND p.member_id = m.member_id ".
		"AND s.member_id = m.member_id ".
		"AND r.name='".addslashes($AS_faction)."' ";

	if( (isset($_REQUEST['Professionsfilter'])) && (($_REQUEST['Professionsfilter']) != 'All') )
		$query .= " AND s.skill_name='".$_REQUEST['Professionsfilter']."'";

	$query .= "ORDER BY max_rep desc, r.standing DESC, curr_rep DESC";


	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error', basename(__FILE__),__LINE__,$query);

	$ab = array();
	$striping_counter = 1;
	$rep = array();

	while($row = $wowdb->fetch_array($result))
	{
		$category = $row['faction'];
		$faction = $row['fct_name'];

		$query = "SELECT s.skill_name, s.skill_level ".
			"FROM `".ROSTER_SKILLSTABLE."` s ".
			"WHERE s.member_id = ".$row['member_id']."  ".
			"AND s.skill_type='".$wordings[$lang]['professions']."'";
		//echo $query."<br>";

		$result2 = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error', basename(__FILE__),__LINE__,$query);
		
		$cell_value = '';
		while($row2 = $wowdb->fetch_array($result2))
		{
			$toolTip = str_replace(':','/',$row2['skill_level']);
			$toolTiph = $row2['skill_name'];
			$skill_image = 'Interface/Icons/'.$wordings[$lang]['ts_iconArray'][$row2['skill_name']];

			$cell_value .= "<img class=\"membersRowimg\" width=\"".$roster_conf['index_iconsize']."\" height=\"".$roster_conf['index_iconsize']."\" src=\"".$roster_conf['interface_url'].$skill_image.'.'.$roster_conf['img_suffix']."\" alt=\"\" ".makeOverlib($toolTip,$toolTiph,'',2,'',',RIGHT,WRAP')." />\n";
		}

		// Increment counter so rows are colored alternately
		++$striping_counter;

		$rep[$row['standing']] .=('<tr class="membersRow'. (($striping_counter % 2) +1) ."\">\n");
		$rep[$row['standing']] .=('<td class="membersRow'. (($striping_counter % 2) +1) .'"><a href="char.php?name='.$row['member'].'&amp;server='.$row['server'].'">'.$row['member'].'</a></td>');
		$rep[$row['standing']] .=('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['standing'].'</td>');
		$rep[$row['standing']] .=('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['curr_rep'].' / '.$row['max_rep'].'</td>');
		$rep[$row['standing']] .=('<td class="membersRowRight'. (($striping_counter % 2) +1) .'">'.$cell_value.'</td>');
		$rep[$row['standing']] .=('</tr>');
	}

	$wowdb->free_result($result);

	$borderTop = border('syellow', 'start', $category.' - '.$faction);
	$tableHeader = '<table width="100%" cellspacing="0" class="bodyline">';
	$tableHeaderRow = '	<tr>
		<th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['rep_name'].'</th>
		<th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['rep_status'].'</th>
		<th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['rep_value'].' / '.$wordings[$roster_conf['roster_lang']]['rep_max'].'</th>
		<th class="membersHeaderRight"> '.$wordings[$roster_conf['roster_lang']]['Professions'].'</th>
		</tr>';
	$borderBottom = border('syellow', 'end');
	$tableFooter = '</table>';

	$content .=($borderTop);
	$content .=($tableHeader);
	$content .=($tableHeaderRow);

	$content .=($rep[$wordings[$roster_conf['roster_lang']]['exalted']]);
	$content .=($rep[$wordings[$roster_conf['roster_lang']]['revered']]);
	$content .=($rep[$wordings[$roster_conf['roster_lang']]['honored']]);
	$content .=($rep[$wordings[$roster_conf['roster_lang']]['friendly']]);
/*	$content .=($rep[$wordings[$roster_conf['roster_lang']]['neutral']]);
	$content .=($rep[$wordings[$roster_conf['roster_lang']]['unfriendly']]);
	$content .=($rep[$wordings[$roster_conf['roster_lang']]['hostile']]);
	$content .=($rep[$wordings[$roster_conf['roster_lang']]['hated']]);*/

	$content .=($tableFooter);
	$content .= $borderBottom;
