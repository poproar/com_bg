<?php
dump($this, 'this');
// Show some stuff before the form
?><h1><?php echo $this->item->firstname . " " . $this->item->lastname; ?></h1>
<address>
	<?php echo $this->item->address . " " . $this->item->cb_street2; ?><br/>
	<?php echo $this->item->city . ", CA " . $this->item->zipcode; ?><br/>
	<?php echo $this->item->phone; ?>
</address>

<?php
// Show the rendered form
echo $this->getRenderedForm();

// Show some stuff after the form
?>
<small class="alert alert-warning"	>To edit the address and adjust last date delivery please use the community builder user manager.</small>
