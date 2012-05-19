<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<?php if ( defined('DEFAULT_LOGO_PATH') ): ?>
			<img class="logo-small brand" src="<?php echo DEFAULT_LOGO_PATH?>"/>
			<?php endif; ?>
			<a class="brand" href="<?php echo DEFAULT_CONTROLLER?>"><?php echo SITE_NAME; ?></a>
			<div class="nav-collapse">
				<ul class="nav">
					<?php echo $this -> module('base/menu') -> display('nav') ?>
				</ul>
				<?php if ( $data -> logged_in ): ?>
				<p class="navbar-text pull-right">
				Logged in as <a href="user"><?php echo  $data -> username ?></a>
				</p>
				<?php endif; ?>
			</div><!--/.nav-collapse -->
		</div>
	</div>

	<?php if ( isset( $this -> module('base/menu') -> menus['sec_nav'] ) ): ?>
	<div class="navbar-inner"  >
		<div class="container-fluid sec-nav" >
			<div class="nav-collapse">
				<ul class="nav">
					<?php echo $this -> module('base/menu') -> display('sec_nav') ?>
				</ul>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>

