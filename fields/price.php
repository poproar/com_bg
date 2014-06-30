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
class FOFFormFieldPrice extends FOFFormFieldText
{

	public function getRepeatable() {

		$html = parent::getRepeatable();

		if($this->item->subtotal != $this->item->total)
		{	
				$html .= '&nbsp;<a href="index.php?option=com_bg&amp;view=orderaddons&amp;bg_order_id='.$this->item->bg_order_id.'"><span class="icon-plus"></span></a>';
		}

		return $html;
		
	}
}
