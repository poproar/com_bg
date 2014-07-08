<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
 
class BgViewPrinting extends FOFViewHtml
{
	protected function onBrowse($tpl = null)
	{
		$tpl = $this->getLayout();
		if ($tpl == 'packlist')
		{
			$this->items = $this->getPacklist();
		}
		else if ($tpl == 'deliverlist')
		{
			$this->items = $this->getDeliverlist();
		}
		else if ($tpl == 'labels')
		{
			$this->items = $this->getLabels();
		}		
		else
		{
			$this->items = array();//$this->getState();
		}
	}

	protected function getPacklist()
	{
		$db = JFactory::getDbo();
  
        $query = $db->getQuery(true);

        $query->select($db->qn(array('firstname', 'lastname', 'bg_size_id', 'bg_ratio_id', 'cb_numdelivered', 'cb_status', 'cb_day', 'cb_shift', 'cb_next')))
		->select('GROUP_CONCAT(CONCAT(li.qty, " ", li.item)) AS addons')
    			->from($db->qn('#__bg_orders', 'o'))
				->join('LEFT', $db->qn('#__comprofiler', 'cb') . ' ON (' . $db->qn('cb.user_id') . ' = ' . $db->qn('o.user_id') . ')' )
				->join('LEFT', $db->qn('#__bg_lineitems', 'li') . ' ON (' . $db->qn('li.bg_order_id') . ' = ' . $db->qn('o.bg_order_id') . ')' )
    			->where('yearweek('.$db->qn('cb_next') . ') ')
    			->order(array('cb_day ASC', 'cb_shift', 'cb_ordering ASC'))
				->group($db->qn('o.bg_order_id'));
		               
					   //$this->query =$query->dump(); 
        $db->setQuery($query);
        $array = $db->loadAssocList();

	    return $array;
	
	}

	protected function getDeliverlist()
	{
		$db = JFactory::getDbo();
  
        $query = $db->getQuery(true);

        $query->select($db->qn(array('firstname', 'lastname', 'address', 'cb_street2', 'phone', 'cb_instruct', 'cb_day', 'cb_shift', 'cb_next')))
    			->from($db->qn('#__comprofiler'))
    			->where('yearweek('.$db->qn('cb_next') . ') = yearweek(curdate())')
    			->order('cb_ordering ASC');
		               
        $db->setQuery($query);
        $array = $db->loadAssocList();

	    return $array;
	}
	
	protected function getLabels()
	{
	require_once JPATH_ADMINISTRATOR . '/components/com_bg/views/printings/fpdf.php';
		$db = JFactory::getDbo();
  
        $query = $db->getQuery(true);

        $query->select($db->qn(array('address', 'cb_street2', 'o.bg_size_id', 'o.bg_ratio_id', 'cb_instruct', 'cb_last'), array('address', 'apt', 'size', 'ratio', 'substitutions', 'new')))
		->select('CONCAT(cb.firstname, " ", cb.lastname) AS name')
		->select('CONCAT(cb.cb_day, " ", cb.cb_shift) AS dayshift')
		->select('GROUP_CONCAT(CONCAT(li.qty, " ", li.item)) AS addons')
    			->from($db->qn('#__bg_orders', 'o'))
				->join('LEFT', $db->qn('#__comprofiler', 'cb') . ' ON (' . $db->qn('cb.user_id') . ' = ' . $db->qn('o.user_id') . ')' )
				->join('LEFT', $db->qn('#__bg_lineitems', 'li') . ' ON (' . $db->qn('li.bg_order_id') . ' = ' . $db->qn('o.bg_order_id') . ')' )
    			->where(array('yearweek('.$db->qn('cb_next') . ') ', $db->qn('o.bg_size_id').' > 0'))
    			->order(array('cb_day ASC', 'cb_shift', 'o.bg_size_id', 'o.bg_ratio_id DESC', 'cb_ordering ASC'))
				->group($db->qn('o.bg_order_id'));
		               
        $db->setQuery($query);
        $array = $db->loadAssocList();

	    return $array;
	}
}
// tmpl=component&print=1&layout=default&page=