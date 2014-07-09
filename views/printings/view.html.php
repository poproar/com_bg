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

		require_once JPATH_ADMINISTRATOR . '/components/com_bg/libraries/fpdf/fpdf.php';
		
		$pdf = new FPDF('P', 'mm', 'letter');
    	//FPDF::_putcatalog();
    	$pdf->_out('/ViewerPreferences <</PrintScaling /None>>');
    	$pdf->SetFont('Arial');
    	$pdf->SetMargins(0,0); 
    	$pdf->SetAutoPageBreak(false); 
    	$_COUNTX = -1;
    	$_COUNTY = 0;

    	$_Margin_Left = 1.762;            // Left margin of labels
    	$_Margin_Top = 11;// 10.7;            // Top margin of labels
    	$_X_Space = 3.175;                // Horizontal space between 2 labels
    	$_Y_Space = 0;                // Vertical space between 2 labels
    	$_X_Number = 2;                // Number of labels horizontally
    	$_Y_Number = 5;                // Number of labels vertically
    	$_Width = 102;//101.6;                // Width of label
    	$_Height = 51;//50.8;                // Height of label


    	$_Font_Size = 12;
    	$h = array(6=>2, 7=>2.5, 8=>3, 9=>4, 10=>5, 11=>6, 12=>7, 13=>8, 14=>9, 15=>10);
    	if (!isset($h[$_Font_Size]))
    	        $pdf->Error('Invalid font size: '.$_Font_Size);
    	$_Line_Height = $h[$_Font_Size];            // Line height
    	$_Padding = 4;                // Padding
    	$pdf->SetFontSize($_Font_Size);
    	$pdf->SetFillColor(230,230,230);

 		$pdf->AddPage();
		//while($label = $db->fetch($result)){
 		foreach ($array as $label) {
    	$_COUNTX++;
    	if ($_COUNTX == $_X_Number) {
    	        // Row full, we start a new one
    	        $_COUNTX=0;
    	        $_COUNTY++;
    	        if ($_COUNTY == $_Y_Number) {
    	            // End of page reached, we start a new one
    	            $_COUNTY=0;
    	            $pdf->AddPage();
    	        }
		}

        $_PosX = $_Margin_Left + $_COUNTX*($_Width+$_X_Space) + $_Padding;
        $_PosY = $_Margin_Top + $_COUNTY*($_Height+$_Y_Space) + $_Padding;

        // draw box
        //$pdf->SetXY($_PosX, $_PosY);
        //$pdf->Cell($_Width - $_Padding, $_Height - $_Padding ,' ',  'LRTB',                   0,'L');

        if (!empty($label['new']) && $label['new'] == 'new'){
        	$label['name'] .= ' *';
        }

        //Start Labels
        $pdf->SetXY($_PosX, $_PosY);
        $pdf->SetFont('','', 10);
        //$pdf->MultiCell($_Width - $_Padding, $_Line_Height, $text, 0, 'L');
        $pdf->Cell( 4*($_Width - $_Padding)/5, $_Line_Height, $label['name'], '', 0,'L');

        $pdf->SetFontSize(2*$_Font_Size/3);
        $pdf->Cell( ($_Width - $_Padding)/5, $_Line_Height, $label['dayshift'], '', 0,'R');
        $pdf->Ln();

        $pdf->SetX($_PosX);
        $pdf->SetFont('','', 12);
        
        if (!empty($label['apt'])){
        	$label['address'] .= ' '. $label['apt'];
        }

        $pdf->Cell($_Width - $_Padding, 4, $label['address'], '',  0, 'R');
        $pdf->Ln(6);

        if (!empty($label['substitutions'])){
            $pdf->SetX($_PosX);
            $pdf->SetFont('','', 8);
            $pdf->MultiCell($_Width - $_Padding - 5, 5, 'Sub: ' . $label['substitutions'], '', 'L', true);
            $pdf->Ln();
        }

        if (!empty($label['addons'])){
            $pdf->SetXY($_PosX, $_PosY + 25 );
            $pdf->SetFont('','B', 10);
            $pdf->MultiCell($_Width - $_Padding - 20, 5, 'Add: '. $label['addons'], 'TB', 'L', false );
        }

        $pdf->SetXY($_PosX + $_Width - $_Padding-15, $_PosY + 30 );
        $pdf->SetFont('','', 10);
        $pdf->Cell($_Width - $_Padding - 85, 5, $label['size'], '',  0, 'C');
        $pdf->Ln();
        $pdf->SetFont('','', 18);
        $pdf->SetX($_PosX + $_Width - $_Padding-15);
        $pdf->Cell($_Width - $_Padding-85, 10, $label['ratio'], 'LRTB',  0, 'C');
		}// ; need semi colon if using while

		// Print labels

		return $pdf->Output();
	}
}