<?php 
defined('_JEXEC') or die;

JHtml::_('behavior.framework');
JHtml::_('behavior.modal');

// Show the rendered form
//echo $this->getRenderedForm(); 

$option =  $this->option . '&view=' . $this->view . '&tmpl=component&print=1&layout=';
?>
<div class="">
	<h3>Printing</h3>
	<p>Use the buttons above to print the documents. Below will be a preview of items on each document.</p>
	<p class="well">Legend</p>
</div>