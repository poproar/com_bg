	<?php 
defined('_JEXEC') or die;

JHtml::_('behavior.framework');
JHtml::_('behavior.modal');

$dow = array(3 => "Wed", 4 => "Thu", 5 => "Fri");
// Show the rendered form
//echo $this->getRenderedForm(); 
?>

<table class="table table-striped table-bordered table-condensed" >

<?php 	$i = -1; $day = 0 ;
		foreach ($this->items as $item => $customer) : ?>

		<?php if ($i < 0) : ?>
			
			<caption><?php echo $dow[$customer['cb_day']] . ' ' . $customer['cb_shift'] . ' | ' . $customer['cb_next']; ?></caption>

		<?php $i++; endif; ?>


		<?php if ($i == 0)
		{
			$day = $customer['cb_day'];
			$shift = $customer['cb_shift'];
		}
		if ($day != $customer['cb_day'] || $shift != $customer['cb_shift']) :
		
			$i = 0; 
			$day = $customer['cb_day'];
			$shift = $customer['cb_shift']; ?> 
			</table>
			<table class="table table-striped table-bordered table-condensed" style="page-break-before: always" >
				<caption><?php echo $dow[$customer['cb_day']] . ' ' . $customer['cb_shift'] . ' | ' . $customer['cb_next']; ?></caption>

		<?php endif; ?>
<tr>
<td width="1%"><?php echo ++$i; ?></td>
	<td width="40%">
		<span class="small nowrap"><b><?php echo $customer['address']; if(!empty($customer['cb_street2'])) {echo ' ' . $customer['cb_street2'];}?></b></span>
		<span class="small pull-right"><i><small><?php echo $customer['firstname'] . ' ' . $customer['lastname']; ?></small></i>&nbsp;:&nbsp;<?php echo  $customer['phone']; ?></span>
	</td>
	<td>
		<span class="small" style="line-height: 50%;"><small><?php echo $customer['cb_instruct']; ?><small></span>
	</td>
	<td width="1%">
		<span class="text-center"><?php echo $customer['cb_day']; ?></span>
	</td>
</tr>

		<?php endforeach; ?>
		</table>
