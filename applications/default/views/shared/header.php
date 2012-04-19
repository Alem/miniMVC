<?php if( defined('BASE_HREF') ): ?>
<base href = '<?php echo BASE_HREF ?>'>
<?php endif; ?>

<meta charset="utf-8"/>
<title><?php echo SITE_NAME ?> - <?php echo ( isset( $model -> title ) ) ? $model -> title : SITE_TAG; ?> </title> 

<?php if( defined('META_DESCRIPTION') ): ?>
<meta name="description" content="<?php echo META_DESCRIPTION?>"/>
<?php endif; ?>

<?php if( defined('META_KEYWORDS') ): ?>
<meta name="keywords" content="<?php echo META_KEYWORDS ?>"/>
<?php endif; ?>

<meta name="author" content="">

<?php echo element::loadCSS(); ?>
<?php echo element::loadJS(); ?>
<?php if( $this -> module('base/analytics') )  echo $this -> module('base/analytics') -> track(); ?>
