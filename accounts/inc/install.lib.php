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
 * @subpackage Plugin Install Library
 */
if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

class pluginInstall
{
	var $sql=array();	// install sql
	var $errors=array();	// errors
	var $messages=array();	// messages
	var $tables=array();	// $table=>boolean, true to restore, false to drop on rollback.
	var $addata;

	var $plugin_id;

	/**
	 * Add a query to be installed.
	 *
	 * @param string $query
	 *		The query to add
	 */
	function add_query($query)
	{
		$this->sql[] = $query;
	}

	/**
	 * Have a table backed up for rollback
	 *
	 * @param string $table
	 *		Table name
	 */
	function add_backup($table)
	{
		global $roster;

		$this->sql[] = 'CREATE' . ( $roster->config['use_temp_tables'] ? ' TEMPORARY ' : ' ' ). 'TABLE `backup_' . $table . '` LIKE `' . $table . '`';
		$this->sql[] = 'INSERT INTO `backup_' . $table . '` SELECT * FROM `' . $table . '`';
		$this->tables[$table] = true; // Restore backup on rollback
	}

	/**
	 * Have a table be dropped on rollback
	 *
	 * @param string $table
	 *		Table name
	 */
	function add_drop($table)
	{
		$this->tables[$table] = false; // Remove copy on rollback
	}

	/**
	 * Drops if exists then creates a new table with the correct charset
	 *
	 * @param string $name
	 * @param string $query
	 */
	function create_table( $name , $query )
	{
		$this->sql[] = 'DROP TABLE IF EXISTS `' . $name . '`;';
		$this->sql[] = 'CREATE TABLE `' . $name . '` (' . $query . ') ENGINE=MyISAM DEFAULT CHARSET=utf8;';
		$this->add_drop($name);
	}

	/**
	 * Drops a table, if it exists
	 *
	 * @param string $name
	 */
	function drop_table($name)
	{
		$this->sql[] = 'DROP TABLE IF EXISTS `' . $name . '`;';
	}

	/**
	 * Add config sql to roster_plugin_config
	 *
	 * @param string $sql
	 *		SQL string to add to the roster_plugin_config table
	 */
	function add_config($sql)
	{
		global $roster;

		$this->sql[] = "INSERT INTO `" . $roster->db->table('plugin_config', 'accounts') . "` VALUES ('" . $this->addata['plugin_id'] . "',$sql);";
	}

	/**
	 * Update a config setting
	 *
	 * @param int $id
	 *		Config ID to update
	 * @param string $sql
	 *		Set string
	 */
	function update_config($id, $sql)
	{
		global $roster;

		$this->sql[] = "UPDATE `" . $roster->db->table('plugin_config', 'accounts') . "` SET " . $sql . " WHERE `plugin_id` = '" . $this->addata['plugin_id'] . "' AND `id` = '" . $id . "';";
	}

	/**
	 * Delete a config setting
	 *
	 * @param int $id
	 *		Config ID to delete
	 */
	function remove_config($id)
	{
		global $roster;

		$this->sql[] = "DELETE FROM `" . $roster->db->table('plugin_config', 'accounts') . "` WHERE `plugin_id` = '" . $this->addata['plugin_id'] . "' AND `id` = '" . $id . "';";
	}

	/**
	 * Removes the all the config settings for an plugin
	 */
	function remove_all_config()
	{
		global $roster;

		$this->sql[] = "DELETE FROM `" . $roster->db->table('plugin_config', 'accounts') . "` WHERE `plugin_id` = '" . $this->addata['plugin_id'] . "';";
	}

	/**
	 * Add a front page menu button
	 *
	 * @param string $title
	 *		Localization key for the button title
	 * @param string $scope
	 *		Scope to link to
	 * @param string $url
	 *		URL parameters for the plugin function
	 * @param string $icon
	 * 		Icon for display
	 */
	function add_menu_button($title, $scope='util', $url='', $icon='')
	{
		global $roster;

		if( empty($icon) )
		{
			$icon = $this->addata['icon'];
		}

		$this->sql[] = "INSERT INTO `" . $roster->db->table('menu_button', 'accounts') . "` VALUES (NULL,'" . $this->addata['plugin_id'] . "','" . $title . "','" . $scope . "','" . $url . "','" . $icon . "');";
		$this->sql[] = "UPDATE `" . $roster->db->table('menu', 'accounts') . "` SET `config` = CONCAT(`config`,':','b',LAST_INSERT_ID()) WHERE `section` = '" . $scope . "' LIMIT 1;";
	}

	/**
	 * Modify a front page menu button
	 *
	 * @param string $title
	 *		Localization key for the button title
	 * @param string $scope
	 *		Scope to link to
	 * @param string $url
	 *		URL parameters for the plugin function
	 * @param string $icon
	 * 		Icon for display
	 */
	function update_menu_button($title, $scope='util', $url='', $icon='')
	{
		global $roster;

		if( empty($icon) )
		{
			$icon = $this->addata['icon'];
		}

		$this->sql[] = "UPDATE `" . $roster->db->table('menu_button', 'accounts') . "` SET `scope` = '" . $scope . "', `url`='" . $url . "', `icon`='" . $icon . "' WHERE `plugin_id`='" . $this->addata['plugin_id'] . "' AND `title`='" . $title . "';";
	}

	/**
	 * Remove a front page menu button
	 *
	 * @param string $title
	 *		Localization key for the button title.
	 */
	function remove_menu_button($title)
	{
		global $roster;

		$this->sql[] = 'DELETE FROM `'.$roster->db->table('menu_button', 'accounts').'` WHERE `plugin_id`="'.$this->addata['plugin_id'].'" AND `title`="'.$title.'";';
	}

	/**
	 * Removes the all the menu buttons for an plugin
	 */
	function remove_all_menu_button()
	{
		global $roster;

		$this->sql[] = "DELETE FROM `" . $roster->db->table('menu_button', 'accounts') . "` WHERE `plugin_id` = '" . $this->addata['plugin_id'] . "';";
	}

	/**
	 * Adds a new menu pane below all the default Roster panes
	 *
	 * @param string $name
	 * 		Name of the pane, this will be prefixed with the plugin's basename
	 */
	function add_menu_pane($name)
	{
		global $roster;

		$this->sql[] = "INSERT INTO `" . $roster->db->table('menu', 'accounts') . "` VALUES (NULL, '" . $roster->db->escape($name) . "', '');";
	}

	/**
	 * Removes a menu pane
	 * Roster panes cannot be deleted
	 *
	 * @param string $name
	 */
	function remove_menu_pane($name)
	{
		global $roster;

		if( !in_array($name,array('util','realm','guild','char')) )
		{
			$this->sql[] = "DELETE FROM `" . $roster->db->table('menu', 'accounts') . "` WHERE `section` = '" . $roster->db->escape($name) . "' LIMIT 1;";
		}
		else
		{
			$this->seterrors('You cannot remove a Roster made menu section!');
		}
	}

	/**
	 * Do the actual installation.
	 *
	 * @return int
	 *		0 on success
	 *		1 on failure but successful rollback
	 *		2 on failed rollback
	 */
	function install()
	{
		global $roster;

		$retval = 0;
		foreach ($this->sql as $id => $query)
		{
			if (!$roster->db->query($query))
			{
				$this->seterrors('Install error in query '.$id.'. MySQL said: <br/>'.$roster->db->error().'<br />The query was: <br />'.$query);
				$retval = 1;
				break;
			}
		}
		if ($retval)
		{
			foreach ($this->tables as $table => $backup)
			{
				$query = 'DROP TABLE IF EXISTS `'.$table.'`';
				if ($result = $roster->db->query($query))
				{
					$roster->db->free_result($result);
				}
				else
				{
					$this->seterrors('Rollback error while dropping '.$table.'. MySQL said: '.$roster->db->error());
					$retval = 2;
				}
				if ($backup)
				{
					$query = 'CREATE TABLE `'.$table.'` LIKE `backup_'.$table.'`';
					if ($result = $roster->db->query($query))
					{
						$roster->db->free_result($result);
					}
					else
					{
						$this->seterrors('Rollback error while recreating '.$table.'. MySQL said: '.$roster->db->error());
						$retval = 2;
					}
					$query = 'INSERT INTO `'.$table.'` SELECT * FROM `backup_'.$table.'`';

					if ($result = $roster->db->query($query))
					{
						$roster->db->free_result($result);
					}
					else
					{
						$this->seterrors('Rollback error while reinserting data in '.$table.'. MySQL said: '.$roster->db->error());
						$retval = 2;
					}
				}
			}
		}
		if( !$roster->config['use_temp_tables'] )
		{
			foreach( $this->tables as $table => $backup )
			{
				if( $backup )
				{
					$query = 'DROP TABLE `backup_' . $table . '`;';
					if( !$roster->db->query($query) )
					{
						$this->seterrors( 'Cleanup error while dropping temporary table backup_' . $table . '. MySQL said: ' . $roster->db->error());
					}
				}
			}
		}
		return $retval;
	}

	/**
	 * Return full table name from base table name for the current plugin and config profile.
	 *
	 * @param string $table base table name
	 * @param boolean $backup true to prepend backup (for temporary tables)
	 */
	function table($table, $backup=false)
	{
		global $roster;

		return (($backup) ? 'backup_' : '').$roster->db->table($table, $this->addata['basename']);
	}

	/**
	 * Set Error Message
	 *
	 * @param string $error
	 */
	function seterrors($error)
	{
		$this->errors[] = $error;
	}

	/**
	 * Return errors
	 *
	 * @return string errors
	 */
	function geterrors()
	{
		return implode("<br />\n",$this->errors);
	}

	/**
	 * Set Message
	 *
	 * @param string $message
	 */
	function setmessages($message)
	{
		$this->messages[] = $message;
	}

	/**
	 * Return messages
	 *
	 * @return string messages
	 */
	function getmessages()
	{
		return implode("<br />\n",$this->messages);
	}

	/**
	 * Return SQL
	 *
	 * @return string SQL
	 */
	function getsql()
	{
		return implode("<br />\n",$this->sql);
	}
}

$installer = new pluginInstall;
