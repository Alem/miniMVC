<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a data['config']-target=".nav-collapse" data['config']-toggle="collapse" class="btn btn-navbar">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<?php if ( isset( $data['config']['default_logo_path'] ) ): ?>
			<img class="logo-small brand" src="<?php echo $data['config']['default_logo_path']?>"/>
			<?php endif; ?>
			<a class="brand" href="" ><?php echo $data['config']['site_name']; ?></a>
			<div class="nav-collapse">
				<ul class="nav">
					<?php echo $this->module('base/menu')->display('nav') ?>
				</ul>
				<?php if ( !empty( $data['session']['logged_in'] ) ): ?>
				<p class="navbar-text pull-right">
				Logged in as <a href="user"><?php echo  $data['session']['username'] ?></a>
				</p>
				<?php endif; ?>
			</div><!--/.nav-collapse -->
		</div>
	</div>

	<?php if ( isset( $this->module('base/menu')->menus['sec_nav'] ) ): ?>
	<div class="navbar-inner"  >
		<div class="container-fluid sec-nav" >
			<div class="nav-collapse">
				<ul class="nav">
					<?php echo $this->module('base/menu')->display('sec_nav') ?>
				</ul>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>

