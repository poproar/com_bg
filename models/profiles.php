<?php
/**
 * @package bg
 * @copyright Copyright (c)2010-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

class BgModelProfiles extends FOFModel
{
	public function buildQuery($overrideLimits = false)
	{
		$db = $this->getDbo();
		$query = parent::buildQuery($overrideLimits = false);
        	//$this->setState('filter_order', 'bg_day_id');

        	$query->where($db->qn('acceptedterms').'  = 1'); // new versus active? featured?


        	$week = $this->getState('weekSearch', null);

        	// this doesnt work on sat or sunday  so....
       
        	// something bigger is not working
        	if($week == 'thisWeek')
        	{
        	    $query->where('WEEKOFYEAR('. $db->qn('cb_next'). ') = WEEKOFYEAR(CURDATE())');
        	}
        	elseif($week == 'nextWeek')
        	{
        	    $query->where('WEEKOFYEAR('. $db->qn('cb_next'). ') = (WEEKOFYEAR(CURDATE()) + 1) ');
        	}	
        	elseif($week == 'lastWeek')
        	{
        	    $query->where('WEEKOFYEAR('. $db->qn('cb_last'). ') = (WEEKOFYEAR(CURDATE()) - 1)');
        	}

        	if($this->getState('isNew', null))
        	{
        	    $query->where($db->qn('cb_last'). ' is null OR ' .  $db->qn('cb_last') . '= ' .$db->q('0000-00-00') . ' OR ' .  $db->qn('cb_status') . ' = 1');
        	}
		
		return $query;
		
	}
}
