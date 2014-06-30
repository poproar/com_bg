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
class FOFFormFieldDuedate extends FOFFormFieldCalendar
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

		//dump("check");
		
		// Get some system objects.
		$config = JFactory::getConfig();
		// $user = JFactory::getUser();

		// // If a known filter is given use it.
		// switch (strtoupper((string) $this->element['filter']))
		// {
		// 	case 'SERVER_UTC':
		// 		// Convert a date to UTC based on the server timezone.
		// 		if ((int) $this->value)
		// 		{
		// 			// Get a date object based on the correct timezone.
		// 			$date = JFactory::getDate($this->value, 'UTC');
		// 			$date->setTimezone(new DateTimeZone($config->get('offset')));

		// 			// Transform the date string.
		// 			$this->value = $date->format('Y-m-d H:i:s', true, false);
		// 		}
		// 		break;

		// 	case 'USER_UTC':
		// 		// Convert a date to UTC based on the user timezone.
		// 		if ((int) $this->value)
		// 		{
		// 			// Get a date object based on the correct timezone.
		// 			$date = JFactory::getDate($this->value, 'UTC');
		// 			$date->setTimezone(new DateTimeZone($user->getParam('timezone', $config->get('offset'))));

		// 			// Transform the date string.
		// 			$this->value = $date->format('Y-m-d H:i:s', true, false);
		// 		}
		// 		break;
		// }

		if(!$this->value || $this->value == '0000-00-00')
		{
			$class = $this->element['class'] ? (string) $this->element['class'] : null;

			return '<span class="badge badge-important '.$class.'">NONE</span>'; 
		}

		jimport('joomla.utilities.date');
		$jDue = new JDate($this->value);
		$jNow = new JDate();

		$html = '';
		$daysLeft = date_diff($jDue, $jNow)->days;
		
		if($jDue < $jNow && $daysLeft > 0) {
			$class = 'todo-due-overdue';
		} elseif($daysLeft < 7) {
			$class = 'todo-due-enoughtime';
		} elseif($daysLeft < 14 ) {
			$class = 'todo-due-closing';
		} else {
			$class = 'todo-due-future';
		}

		if($jDue->format('w', true, true) != $this->item->cb_day){
			$html = '<span class="todo-due-overdue"><span class="icon-warning"></span></span> ';
		}

		$html .= '<span class="'.$class.'">'.$jDue->format($format, true, true). '</span>';  // or format JText::_('DATE_FORMAT_LC'), true, true
		
		return $html;
	}
}
