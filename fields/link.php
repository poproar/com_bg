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

class FOFFormFieldLink extends FOFFormFieldText
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
        
        $html = parent::getRepeatable();
        
        if ($this->element['link_url'])
        {
            $link_url = $this->element['link_url'];
        }
        
        $icon = $this->element['icon'] ? 'icon-'.$this->element['icon']  : 'icon-edit';
        $replacements = array(
            '[USER:ID]'     => $this->item->user_id,
            '[ITEM:ID]'     => $this->item->bg_order_id,
        );

        foreach ($replacements as $key => $value)
        {
            $link_url = str_replace($key, $value, $link_url);
        }
        
        $html .= '&nbsp;<a href="'.$link_url.'"><span class="' . $icon . '"></span></a>';
        return $html;
    }
}