<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include ( load::Page( 'shared', 'header') ); ?>

		<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<style type="text/css">
			/* Override some defaults */
			html, body {
				background-color: #eee;
			}
			body {
				padding-top: 40px; /* 40px to make the container go all the way to the bottom of the topbar */
			}
			.container > footer p {
				text-align: center; /* center align it with the container */
			}
			.container {
				width: 820px; /* downsize our container to make the content feel a bit tighter and more cohesive. NOTE: this removes two full columns from the grid, meaning you only go to 14 columns and not 16. */
			}

			/* The white background content wrapper */
			.content {
				background-color: #fff;
				padding: 20px;
				margin: 0 -20px; /* negative indent the amount of the padding to maintain the grid system */
				-webkit-border-radius: 0 0 6px 6px;
				-moz-border-radius: 0 0 6px 6px;
				border-radius: 0 0 6px 6px;
				-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
				-moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
				box-shadow: 0 1px 2px rgba(0,0,0,.15);
			}

			/* Page header tweaks */
			.page-header {
				background-color: #f5f5f5;
				padding: 65px 20px 10px;
				margin: -20px -20px 20px;
			}

			/* Give a quick and non-cross-browser friendly divider */
			.content .span4 {
				margin-left: 0;
				padding-left: 19px;
				border-left: 1px solid #eee;
			}

			.topbar .btn {
				border: 0;
			}

			.logo-small {
				max-width: 20px;
				max-height: 20px;
			}
				
		</style>

		<!-- Le fav and touch icons -->
		<link rel="shortcut icon" href="media/img/favicon.ico">
		<link rel="apple-touch-icon" href="images/apple-touch-icon.png">
		<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
	</head>
	<body>

		<?php include ( load::path( 'shared', 'nav') ); ?>

		<div class="container">

			<div class="content">
				<div class="page-header">
					<h1><?php echo SITE_NAME; ?> <small><?php echo SITE_TAG; ?></small></h1>
				</div>
				<div class="row">

					<div class="<?php echo(isset($this -> module('base/menu') -> menus['sidebar'])) ? 'span8' : 'span16' ?>">
						<?php require_once( $this -> loaded['view']['main']['path'] ); ?>
					</div>

					<?php if((isset($this -> module('base/menu') -> menus['sidebar']) )): ?>
					<div class="span2 well sidebar-nav">
						<ul class="nav nav-list">
						<li class="nav-header"> Navigation </li>
							<?php echo $this -> module('base/menu') -> display('sidebar') ?>
						</ul>
					</div><!--/.well -->
					<? endif;?>

				</div>
			</div>

			<footer>
			<p>(c) <a href='<?php echo COMPANY_WEBSITE; ?>'><?php echo COMPANY . ' - ' . date("Y"); ?></a></p>
			</footer>

		</div> <!-- /container -->
	</body>
<html>
