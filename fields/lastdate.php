<?php
/**
 * @package    FrameworkOnFramework
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * Form Field class for the FOF framework
 * Shows the due date field, either as a calendar input or as a formatted due date field
 *
 * @since       2.0
 */
class FOFFormFieldLastdate extends FOFFormFieldCalendar
{
	/**
	 * Get the rendering of this field type for static display, e.g. in a single
	 * item view (typically a "read" task).
	 * 
	 * @since 2.0
	 */
	public function getRepeatable() {

		//if new do this 
		if(!$this->value || $this->value == '0000-00-00')
		{
			$class = $this->element['class'] ? (string) $this->element['class'] : null;

			return '<span class="badge badge-warning '.$class.'">NEW</span>'; 
		}

		return parent::getRepeatable();
	}
}
