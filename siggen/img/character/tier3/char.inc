<?php

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

$char_ext = '.png';

if( !empty($sig_race) && !empty($sig_gender) && !empty($sig_classEn) )
{
	$im_user_file = $configData['image_dir'].$configData['char_dir'].$sig_race.'-'.$sig_gender.'-'.str_replace(' ','',$sig_classEn).$char_ext;

}	// Set default character image
else
{
	$im_user_file = $configData['image_dir'].$configData['char_dir'].$configData['charlogo_default_image'];
}
