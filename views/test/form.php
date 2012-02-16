<div class = 'row' >
	<div class = 'span5' >
		<form class = "form-stacked" action = "?<?php echo $this -> name ?>/post/" method = "post">
			<p>
			<input class = "Primary btn large" type = "submit" value = "Submit"/>
			</p>
			<label>ID: </label>  <input id = "id" name = "id" type="text"> <br/>
			<label>Entry: </label> <br/>
			<textarea id = "<?php echo $this -> name ?>-field" name = "<?php echo $this -> name ?>" type="text" rows="20" cols="50" >
			</textarea>
		</form>
	</div>
	<div class = 'span5' >
		<h2>Add an Item</h2>
		<br/>
		<p>
		The content you enter here is added directly to the database.
		</p>
		<p>
		The data is parameterized and the columns are whitelisted to match valid table columns.
		</p>

	</div>
</div>
