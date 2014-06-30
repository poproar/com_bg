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
class FOFFormFieldChangedate extends FOFFormFieldCalendar
{
	/**
	 * Get the rendering of this field type for static display, e.g. in a single
	 * item view (typically a "read" task).
	 * 
	 * @since 2.0
	 */
	public function getRepeatable() {
		// Initialize some field attributes.
		$format = $this->element['format'] ? (string) $this->element['format'] : '%Y-%m-%d';
		
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';

		$useDate = $this->item->created_on > $this->item->modified_on ? $this->item->created_on : $this->item->modified_on;
		jimport('joomla.utilities.date');
		$jDue = new JDate($useDate);
		
		$html = '<span class="'.$class.'">'.$jDue->format( JText::_('DATE_FORMAT_LC'), true, true). '</span>';  // or format JText::_('DATE_FORMAT_LC'), true, true
		
		return $html;
	}
}
