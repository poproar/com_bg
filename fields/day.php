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
class FOFFormFieldDay extends FOFFormFieldCalendar
{
	public $days = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
	/**
	 * Get the rendering of this field type for static display, e.g. in a single
	 * item view (typically a "read" task).
	 * 
	 * @since 2.0
	 */
	public function getRepeatable() {	
		// Initialize some field attributes.

		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';

		jimport('joomla.utilities.date');
		$jNext = new JDate($this->item->next_date);
		//dump($jNext->format('w', true, true), 'nx');
		if($jNext->format('w', true, true) != $this->value){
			$html = '<span class="todo-due-overdue"><span class="icon-warning"></span></span>';
		}

		$html .= $this->days[$this->value];
		
		return $html;
	}
}
