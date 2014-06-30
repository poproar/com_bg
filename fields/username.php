<?php
/**
 * @package    FrameworkOnFramework
 * @subpackage form
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// Protect from unauthorized access
defined('_JEXEC') or die;

/**
 * Form Field class for the FOF framework
 * A user selection box / display field
 *
 * @package  FrameworkOnFramework
 * @since    2.0
 */
class FOFFormFieldUsername extends FOFFormFieldModel
{
	/**
	 * Get the rendering of this field type for a repeatable (grid) display,
	 * e.g. in a view listing many item (typically a "browse" task)
	 *
	 * @since 2.0
	 *
	 * @return  string  The field HTML
	 */
	public function getRepeatable()
	{

		$string = parent::getRepeatable();

		$pattern = "/<span ?.*>(.\d*)<\/span>/";

    	preg_match($pattern, $string, $match);

    	$user_id = (int) $match[1];

		// Get the user record
		$user = JFactory::getUser($user_id)->name;

		$html = str_replace($match, $user, $string);

		return $html;

	}
}
