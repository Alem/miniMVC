<h1>Settings </h1>
<hr>
<br/>

<div class = 'row' >
	<div class = 'span8' >
		<p>
		 	Tweak your user settings here.
		</p>
	</div>
</div>

<br/>
<br/>

<div class = 'row' >

	<div class = 'well span3' >
		<h3> Change Password </h3>
		<br/>
		<form class = 'form' action ='user/change/password' method = 'post' >
			<label> Current Password: <?php echo $this->helper->input ('password_old' , 'password', false ) ?> </label>
			<label> New Password: <?php echo $this->helper->input ('password_new' , 'password', false ) ?> </label>
			<label> Repeat Password: <?php echo $this->helper->input ('password_repeat' , 'password', false ) ?> </label>
			<br/>
			<input class = 'btn-primary btn-large' value ='Change' type ='submit' />
		 </form>
	</div>

	<div class = 'well span3' >
		<h3> Change Email </h3>
		<p>
			<span class='label label-info'> Current Email</span>  <?php echo $data['email'] ?>
		</p>
		<form class = 'form' action ='user/change/email' method = 'post' >
			<label> New Email: <?php echo $this->helper->input ('email_new' , 'text', false ) ?> </label>
			<br/>
			<input class = 'btn-info btn-large' value ='Change' type ='submit' />
		 </form>
	</div>
</div>
