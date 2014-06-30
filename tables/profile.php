<?php
/**
 * @package bg
 * @copyright Copyright (c)2012 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

class BgTableProfile extends FOFTable
{
	function __construct( $table, $key, &$db, $config )
	{
		$table = '#__comprofiler';
		$key = 'id';

		$this->setColumnAlias('enabled', 'cb_status');
		$this->setColumnAlias('ordering', 'cb_ordering');

		parent::__construct($table, $key, $db, $config);

	}
}
