<?php 
defined('_JEXEC') or die;

JHtml::_('behavior.framework');
JHtml::_('behavior.modal');

// Show the rendered form
//echo $this->getRenderedForm(); 

$option =  $this->option . '&view=' . $this->view . '&tmpl=component&print=1&layout=';
?>
<div class="form-actions">
	<a class="btn btn-large btn-primary" href="index.php?option=<?php echo $option ?>deliverlist&page=" target="_blank">
		<i class="icon-list"></i>&ensp;
		<span><?php echo JText::_('Delivery List') ?></span></a>
	<a class="btn btn-large" href="index.php?option=<?php echo $option ?>packlist&page=" target="_blank">
		<i class="icon-cart"></i>&ensp;
		<span><?php echo JText::_('Packing List') ?></span></a>
	<a class="btn btn-large btn-inverse" href="index.php?option=com_bg&task=printings.outputLabels"  target="_blank">
		<i class="icon-printer"></i>&ensp;
		<span><?php echo JText::_('Labels') ?></span></a>
	<a class="btn btn-large btn-danger" href="index.php?option=<?php echo $option ?>labels&page="  target="_blank">
		<i class="icon-warning"></i>&ensp;
		<span><?php echo JText::_('Create Orders') ?></span></a>
</div>