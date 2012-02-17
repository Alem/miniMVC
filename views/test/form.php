<div class = 'row' >
	<div class = 'span4' >
		<form class = "form-stacked" action = "?<?php echo $this -> name ?>/post/" method = "post">
			<label>ID: </label>  <input id = "id" name = "id" type="text"> <br/>
			<label>Entry: </label> <br/>
			<textarea id = "<?php echo $this -> name ?>-field" name = "<?php echo $this -> name ?>" type="text" rows="10" cols="50" ></textarea>
			<p>
			<input class = "Primary btn large btn-primary btn-large" type = "submit" value = "Submit"/>
			</p>
		</form>
	</div>
	<div class = 'span5' >
		<h1>Add</h1>
		<hr>
		<br/>
		<p>
		The content you enter here is added directly to the database.
		</p>
		<p>
		The data is parameterized and the columns are whitelisted to match valid table columns.
		</p>

	</div>
</div>
