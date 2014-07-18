<?php
/**
 *  @package AdminTools
 *  @copyright Copyright (c)2010-2013 Nicholas K. Dionysopoulos
 *  @license GNU General Public License version 3, or later
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

class BgToolbar extends FOFToolbar
{

	public function onBrowse()
	{
		parent::onBrowse();
		JToolBarHelper::divider();
		JToolBarHelper::spacer('100%');
		
		
		$this->addListsBtn();
		$this->addLabelsBtn();
		JToolbarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_bg&view=profiles');
	}
	
	public function onProfilesBrowse()
	{
	//custom($task = '', $icon = '', $iconOver = '', $alt = '', $listSelect = true)
		$subtitle_key = 'Customers';//'COM_BG_TITLE_PROFILES';//.strtoupper($this->input->getCmd('view','printings'));
		JToolBarHelper::title(JText::_('COM_BG').' &ndash; '.JText::_($subtitle_key), 'users');
		
		//$this->onBrowse();
		
		// Add toolbar buttons
//		if($this->perms->delete) {
//			JToolBarHelper::deleteList();
//		}
		if($this->perms->edit) {
			JToolBarHelper::editList();
			JToolBarHelper::archiveList('archive', 'Pick Up Only');
//			JToolBarHelper::publishList();
     		JToolBarHelper::unpublishList('unpublish', 'Missing');
			JToolBarHelper::trash('trash', 'Close');
		}
//		if($this->perms->create) {
//			JToolBarHelper::addNew();
//		}

		JToolBarHelper::divider();
		JToolBarHelper::spacer('100%');
		
		$this->addListsBtn();
		$this->addLabelsBtn();
      
//      JToolBarHelper::preferences('com_bg', '500');
		//JToolBarHelper::custom('copy', 'copy.png', 'copy_f2.png', 'JLIB_HTML_BATCH_COPY', false);
		//JToolBarHelper::custom('printings.browse', 'copy.png', 'copy_f2.png', 'Labels', false);
		
		
		$this->renderSubmenu();
	}
	
	public function onPrintingsBrowse()
	{
	//custom($task = '', $icon = '', $iconOver = '', $alt = '', $listSelect = true)
		$subtitle_key = 'Things to Print';//'COM_BG_TITLE_PROFILES';//.strtoupper($this->input->getCmd('view','printings'));
		JToolBarHelper::title(JText::_('COM_BG').' &ndash; '.JText::_($subtitle_key), 'print');

		JToolBarHelper::divider();
		$this->addListsBtn();
		$this->addLabelsBtn();
		JToolbarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_bg&view=profiles');
		
	}
	
	protected function addLabelsBtn()
    {
		$options['class']   = 'copy';
        $options['a.class']  = '';
        $options['a.href']  = 'index.php?option=com_bg&view=printings&tmpl=component&print=1&layout=labels';
		$options['a.target']  = '_blank';
        $options['text']    = JText::_('Labels');

        $this->addCustomBtn('print-labels', $options);
	}
	
	protected function addListsBtn()
    {
		$deloptions['class']   = 'list';
        $deloptions['a.class']  = 'btn-primary';
        $deloptions['a.href']  = 'index.php?option=com_bg&view=printings&tmpl=component&print=1&layout=deliverlist&page=';
		$deloptions['a.target']  = '_blank';
        $deloptions['text']    = JText::_('Delivery List');

        $this->addCustomBtn('print-deliverlist', $deloptions);
		
		$packoptions['class']   = 'cart';
        $packoptions['a.class']  = 'btn-inverse';
        $packoptions['a.href']  = 'index.php?option=com_bg&view=printings&tmpl=component&print=1&layout=packlist&page=';
		$packoptions['a.target']  = '_blank';
        $packoptions['text']    = JText::_('Packing List');

        $this->addCustomBtn('print-packlist', $packoptions);
	}
	
	protected function addCustomBtn($id, $options = array())
    {
        $options = (array) $options;
		if (version_compare(JVERSION, '3.0', 'ge'))
		{
			$a_class = 'btn btn-small';
		}
		else
		{
			$a_class = 'toolbar';
		}
        $href	 = '';
        $task	 = '';
        $text    = '';
        $rel	 = '';
        $target  = '';
        $other   = '';

        if(isset($options['a.class']))	$a_class .= ' ' . $options['a.class'];
        if(isset($options['a.href']))	$href     = $options['a.href'];
        if(isset($options['a.task']))	$task     = $options['a.task'];
        if(isset($options['a.target']))	$target   = $options['a.target'];
        if(isset($options['a.other']))	$other    = $options['a.other'];
        if(isset($options['text']))		$text	  = $options['text'];
        if(isset($options['class']))
        {
            $class = $options['class'];
        }
        else
        {
            $class = 'default';
        }

        if(isset($options['modal']))
        {
            JHTML::_('behavior.modal');
            $a_class .= ' modal';
            $rel	  = "'handler':'iframe'";
            if(is_array($options['modal']))
            {
                if(isset($options['modal']['size']['x']) && isset($options['modal']['size']['y']))
                {
                    $rel .= ", 'size' : {'x' : ".$options['modal']['size']['x'].", 'y' : ".$options['modal']['size']['y']."}";
                }
            }
        }

        $html = '<a id="'.$id.'" class="'.$a_class.'" alt="'.$text.'"';

        if($rel)	$html .= ' rel="{'.$rel.'}"';
        if($href)	$html .= ' href="'.$href.'"';
        if($task)	$html .= " onclick=\"javascript:submitbutton('".$task."')\"";
        if($target) $html .= ' target="'.$target.'"';
        if($other)  $html .= ' '.$other;
        $html .= ' >';

		$html .= '<span class="icon icon-'.$class.'" title="'.$text.'" > </span>';


		$html .= ' ' . $text;

		$html .= '</a>';

        $bar = JToolBar::getInstance();
        $bar->appendButton('Custom', $html, $id);
    }
}
