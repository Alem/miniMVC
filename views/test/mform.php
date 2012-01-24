<h2>Add an Item</h2>
<form action = "?<?php echo $this -> name ?>/post/" method = "post">
<p>
<label>ID: </label>  <input id = "id" name = "id" type="text"> <br/>
</p>
<p>
<label>Entry: </label> <br/>
<textarea id = "<?php echo $this -> name ?>-field" name = "<?php echo $this -> name ?>" type="text" rows="20" cols="50" >
</textarea>
</p>
<input type = "submit" value = "Submit"/>
</form>
