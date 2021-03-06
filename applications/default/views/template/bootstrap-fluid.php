<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include ( $this->load()->path( 'shared', 'header.php' ) ); ?>

		<style type="text/css">
			body {
				padding-top: 75px;
				padding-bottom: 40px;
			}
			.sidebar-nav {
				padding: 9px 0;
			}
			.sec-nav{
				 background-color: rgba(0, 0, 0, 0.45);
			}
			.logo-small {
				max-width: 20px;
				max-height: 20px;
			}

		</style>

		<!-- Le fav and touch icons -->
		<link href="" rel="shortcut icon">
		<link href="" rel="apple-touch-icon">
		<link href="" sizes="72x72" rel="apple-touch-icon">
		<link href="" sizes="114x114" rel="apple-touch-icon">
	</head>

	<body>

		<?php include ( $this->load()->path( 'shared', 'nav.php' ) ); ?>

		<div class="container-fluid">
			<div class="row-fluid">
				<?php if((isset($this->module('base/Menu')->menus['sidebar']) )): ?>
				<div class="span2">
					<br/>
					<div class="well sidebar-nav">
						<ul class="nav nav-list">
						<li class="nav-header"> Navigation </li>
							<?php echo $this->module('base/Menu')->display('sidebar') ?>
						</ul>
					</div><!--/.well -->
				</div><!--/span-->
				<? endif;?>
				<div class="<?php echo ((isset($this->module('base/Menu')->menus['sidebar']) )) ? 'span9' : 'span12' ?>">
					<div class="hero-unit">

						<?php if ( isset( $this->module('base/Menu')->menus['breadcrumb'] ) ): ?>
						<ul class="breadcrumb">
							<?php echo $this->module('base/Menu')->display('breadcrumb') ?>
						</ul>
						<? endif;?>

						<br/>
						<?php $this->renderLoaded( $data ); ?>
					</div><!--/row-->
				</div><!--/span-->
			</div><!--/row-->

			<hr>
			<footer>
			<p>(c) <a href='<?php echo $data['config']['company_website']; ?>'><?php echo $data['config']['company'] . ' - ' . date("Y"); ?></a></p>
			</footer>

		</div><!--/.fluid-container-->
	</body>
</html>
