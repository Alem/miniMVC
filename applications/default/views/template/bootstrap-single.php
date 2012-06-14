<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include ( $this->load()->path( 'shared', 'header') ); ?>

		<style type="text/css">
			body {
				padding-top: 75px;
				padding-bottom: 40px;
			}
			.container{
				width: 1180px;
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
		<?php include ( $this->load()->path( 'shared', 'nav') ); ?>

		<div class="container">
			<div class="hero-unit">

				<?php if ( isset( $this->module('base/menu')->menus['breadcrumb'] ) ): ?>
				<ul class="breadcrumb">
					<?php echo $this->module('base/menu')->display('breadcrumb') ?>
				</ul>
				<br/>
				<?php endif; ?>

				<?php $this->renderLoaded( $data ); ?>
			</div><!--/row-->

			<hr>
			<footer>
			<p>(c) <a href='<?php echo $data['config']['company_website']; ?>'><?php echo $data['config']['company'] . ' - ' . date("Y"); ?></a></p>
			</footer>

		</div><!--/.container-->

	</body>
</html>

