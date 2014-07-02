<?php
// Protect from unauthorized access
defined('_JEXEC') or die;

class BgControllerOrders extends FOFController

{
    public function execute($task)
    {
        if(!in_array($task, array('insertDummy'))) {
            // $task = 'browse';
        }
        parent::execute($task);
    }

    public function insertDummy()
    {
        // get the model for a customer?
        $model = $this->getThisModel();
        $model->snapshot();
        $model->getAddons();
        $model->dropAddons();
        
        $query = $model->retotal();

        $url = '';
        $returnurl = $this->input->get('returnurl', '', 'base64');
        if(!empty($returnurl)) {
            $url = base64_decode($returnurl);
        }
        if(empty($url)) {
            $url = JURI::base().'index.php?option=com_bg&view=orders';
        }
        $this->setRedirect($url, JText::_($query));


    }
    
}