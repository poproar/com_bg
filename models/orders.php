<?php // no direct access

defined( '_JEXEC' ) or die( 'Restricted access' ); 

class BgModelOrders extends FOFModel
{	
    public function buildQuery($overrideLimits = false)
    {
        $db = $this->getDbo();
        $query = parent::buildQuery($overrideLimits = false);
        $week = $this->getState('weekSearch', null);
       

        if($week == 'thisWeek')
        {
            $query->where('WEEKOFYEAR('. $db->qn('week'). ') = WEEKOFYEAR(CURDATE())');
        }
        elseif($week == 'lastWeek')
        {
            $query->where('WEEKOFYEAR('. $db->qn('week'). ') = (WEEKOFYEAR(CURDATE()) - 1)');
        }

        return $query;
    }

    public function snapshot()
    {

         $db = JFactory::getDbo();
         
        $query = $db->getQuery(true);
        $pre = $db->getQuery(true);

        // Insert columns.
        $columns = array('user_id', 'bg_size_id', 'bg_ratio_id', 'week', 'subtotal');


        $now = $query->currentTimestamp();
        // Insert values.
       
        $pre->insert($db->qn('#__bg_orders'))->columns($db->qn($columns));
        
        // Prepare the insert query.
        $query//->insert($db->qn('#__bg_orders'))->columns($db->qn($columns))
        ->select(array(
                        $db->q('') . ' AS id',
                        $db->qn('user_id'),
                        $db->qn('c.cb_size', 'bg_size_id'),
                        $db->qn('c.cb_ratio', 'bg_ratio_id'),
                        $db->qn('c.cb_next', 'week'),
                        $db->qn('price'),
                        $db->q('') . ' AS total',
                        $db->q('1') . ' AS enabled',
                        $db->q('') . ' AS ordering',
                        $db->q('') . ' AS cby',
                        $now .  ' AS con',
                        $db->q('') . ' AS mby',
                        $db->q('') . ' AS mon',
                        $db->q('') . ' AS lby',
                        $db->q('') . ' AS lon'
                        )
                )
                ->from($db->qn('#__comprofiler', 'c'))
                ->join('LEFT', $db->qn('#__bg_sizes', 's') . ' ON (' . $db->qn('s.bg_size_id') . ' = ' . $db->qn('c.cb_size') . ') ' )
                ->where('week(' . $db->qn('cb_next') . ', 3) = week(CURDATE(), 3)');
         
        $query = $pre . $query ;
         // Set the query using our newly populated query object and execute it.
        $db->setQuery($query);
        //dump($query, 'query');
        $db->execute();
               
        return true;
    }

    public function getAddons()
    {

        $db = JFactory::getDbo();
         
        $query = $db->getQuery(true);
        $idquery = $db->getQuery(true);
       
       // get user ids for current week
        $idquery->select($db->qn('user_id'))
            ->from($db->qn('#__bg_orders'))
            ->where('week(' . $db->qn('week') . ', 3) = week(CURDATE(), 3)');
        
        $query->select(array(
            $db->qn('o.bg_order_id'),
            $db->qn('s.title', 'item'), 
            $db->qn('a.qty'), 
            $db->qn('a.purchase_price')
            ))->from($db->qn('#__bg_addons', 'a'))
        ->join('LEFT', $db->qn('#__bg_orders', 'o') . ' ON (' . $db->qn('a.user_id') . ' = ' . $db->qn('o.user_id') . ') ' )
        ->join('LEFT', $db->qn('#__bg_stocks', 's') . ' ON (' . $db->qn('a.bg_stock_id') . ' = ' . $db->qn('s.bg_stock_id') . ') ' )
        ->where($db->qn('a.user_id') . "IN (" . $idquery . ")");
         
        // Set the query using our newly populated query object and execute it.
        $db->setQuery($query);
        
        $objects = $db->loadObjectList();
        
        foreach($objects as $object)
        {
            $object->line_total = $object->qty * $object->purchase_price;
            // dump($object->line_total, 'total');
            $db->insertObject('#__bg_lineitems', $object);
        }
        
        return true;
    }
    
    // this function should be moved to the addon model ?with conditions
    public function dropAddons()
    {

        $db = JFactory::getDbo();
         
        $query = $db->getQuery(true);
        $idquery = $db->getQuery(true);
        
        $idquery->select($db->qn('user_id'))
            ->from($db->qn('#__bg_orders'))
            ->where('week(' . $db->qn('week') . ', 3) = week(CURDATE(), 3)');
                            

        $query->delete($db->qn('#__bg_addons'));
        $query->where(array($db->qn('user_id') . "IN (" . $idquery . ")",
                            $db->qn('always') . ' <> 1'));
         
        $db->setQuery($query);
        
        $db->execute();
        
        return true;
    }
    
    public function retotal()
    {

        $db = JFactory::getDbo();
         
        $query = $db->getQuery(true);
        $idquery = $db->getQuery(true);
        
        $parts = $db->getQuery(true);
        $whole = $db->getQuery(true);
        $summed = $db->getQuery(true);
        $unioned = $db->getQuery(true);
        
       
       // get user ids for current week
        $idquery->select($db->qn('bg_order_id'))
            ->from($db->qn('#__bg_orders'))
            ->where('week(' . $db->qn('week') . ', 3) = week(CURDATE(), 3)');
            
        $parts->select(array($db->qn('bg_order_id'), $db->qn('line_total', 'subtotal')))->from($db->qn('#__bg_lineitems', 'parts'));
        // union
        $whole->select(array($db->qn('bg_order_id'), $db->qn('subtotal')))->from($db->qn('#__bg_orders', 'whole'))->where($db->qn('bg_order_id') . " IN (" . $idquery . ")");
        
        $parts->union($whole);
        // $unioned->select('*')->union(array($parts, $whole));
        
        
        $summed->select(array($db->qn('bg_order_id'), ' SUM('.$db->qn('subtotal').') AS sumz'))->from('('.$parts . ') AS ' . $db->qn('unioned'))->group($db->qn('bg_order_id'));
        
        
        
        $query->update($db->qn('#__bg_orders', 'o'))->join('LEFT', '('. $summed . ') AS ' . $db->qn('a') . 
        ' ON ' . $db->qn('o.bg_order_id') . ' = ' . $db->qn('a.bg_order_id'))->set($db->qn('o.total') . ' = ' . $db->qn('a.sumz'));
        
        // dump($query, 'hugass qry');
        $db->setQuery($query);
        
        $db->execute();
        
        return $query->dump() ; // true;
    }
    
    
}