<?php

// Show some stuff before the form
?><h1>Hello, world!</h1>
<p>This is printed above the form</p>

<?php
// Show the rendered form
echo $this->getRenderedForm();

// Show some stuff after the form
?>
<p>This is printed below the form</p>
