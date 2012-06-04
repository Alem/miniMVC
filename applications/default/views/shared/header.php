<?php if( isset( $data['config']['base_href'] ) ): ?>
<base href = '<?php echo $data['config']['base_href'] ?>'>
<?php endif; ?>

<meta charset="utf-8"/>
<title><?php echo $data['config']['site_name'] ?> - <?php echo ( isset( $data['config']['title'] ) ) ? $data['config']['title'] : $data['config']['site_tag']; ?> </title>

<?php if( isset( $data['config']['meta_description'] ) ): ?>
<meta name="description" content="<?php echo $data['config']['meta_description']?>"/>
<?php endif; ?>

<?php if( isset( $data['config']['meta_keywords'] ) ): ?>
<meta name="keywords" content="<?php echo $data['config']['meta_keywords'] ?>"/>
<?php endif; ?>

<meta name="author" content="">

<?php echo element::loadCSS( $data['config']['default_css'] ); ?>
<?php echo element::loadJS( $data['config']['default_javascript'] ); ?>
