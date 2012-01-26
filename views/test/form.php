<h2>Add an Item</h2>
<form class = "form-stacked" action = "?<?php echo $this -> name ?>/post/" method="post">
<label>Entry: </label> <br/>
<textarea id = "<?php echo $this -> name ?>-field" name = "<?php echo $this -> name ?>" type="text" rows="20" cols="50" >
</textarea> 
<input type = "submit" value = "Submit"/>
</form>
