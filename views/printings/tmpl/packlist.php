<?php 
defined('_JEXEC') or die;

JHtml::_('behavior.framework');
JHtml::_('behavior.modal');

$dow = array(3 => "Wed", 4 => "Thu", 5 => "Fri");
$size = array(3 => "Family", 2 => "Studio", 1 => "Original", 0 => "Pick UP");
//echo $this->query;
// Show the rendered form
//echo $this->getRenderedForm(); 
?>

<table class="table table-striped table-bordered table-condensed" >

<?php 	$i = -1; $day = 0 ;
		foreach ($this->items as $item => $customer) : ?>
	<?php $sizindex = $customer['bg_size_id'] ? $customer['bg_size_id'] : 0 ; ?>
		<?php if ($i < 0) : ?>
			
			<caption><?php echo $dow[$customer['cb_day']] . ' ' . $customer['cb_shift'] . ' | ' . $customer['cb_next']; ?></caption>

		<?php $i++; endif; ?>


		<?php if ($i == 0)
		{
			$day = $customer['cb_day'];
			$shift = $customer['cb_shift'];
		}
		
		// set up a page break if day shift changes
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
	<td width="25%">
		<span class="small"><small><b><?php echo $customer['firstname'] . ' ' . $customer['lastname']; ?></b></small></span>
		<span class="small pull-right">&nbsp;&nbsp;<?php echo $size[$sizindex]; ?>:<?php echo  $customer['bg_ratio_id']; ?></span>
	</td>
	<td>
		<span class="small" style="line-height: 50%;"><small><?php echo $customer['addons']; ?><small></span>
	</td>
	<td width="1%">
		<span class="text-center"><?php echo $customer['cb_numdelivered']; ?></span>
	</td>
</tr>

		<?php endforeach; ?>
		</table>
