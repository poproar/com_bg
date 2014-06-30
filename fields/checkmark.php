<?php


// Protect from unauthorized access
defined('_JEXEC') or die();

class FOFFormFieldCheckmark extends FOFFormFieldText
{
	public function getRepeatable() {
		$html = '';
		// Initialize some field attributes.
		if($this->value)
		{
			$html = '<span class="icon-checkmark"></span> ';
		}
		return $html;
	}
}
