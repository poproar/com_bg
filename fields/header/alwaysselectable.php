<?php
defined('_JEXEC') or die();

class FOFFormHeaderAlwaysselectable extends FOFFormHeaderFieldselectable
{
	protected function getOptions()
	{
		$options[] = JHTML::_('select.option', 0, JText::_('This time'));
		$options[] = JHTML::_('select.option', 1, JText::_('Always'));

		return $options;
	}
}