<?php if( isset( $data['base_href'] ) ): ?>
<base href = '<?php echo $data['base_href'] ?>'>
<?php endif; ?>

<meta charset="utf-8"/>
<title><?php echo $data['site_name'] ?> - <?php echo ( isset( $data['title'] ) ) ? $data['title'] : $data['site_tag']; ?> </title> 

<?php if( isset( $data['meta_description'] ) ): ?>
<meta name="description" content="<?php echo $data['meta_description']?>"/>
<?php endif; ?>

<?php if( isset( $data['meta_keywords'] ) ): ?>
<meta name="keywords" content="<?php echo $data['meta_keywords'] ?>"/>
<?php endif; ?>

<meta name="author" content="">

<?php echo element::loadCSS( $data['default_css'] ); ?>
<?php echo element::loadJS( $data['default_javascript'] ); ?>
