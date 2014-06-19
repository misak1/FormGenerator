<?php
require_once ('formGenerator.php');
startSession();
formHandler();
?>
<?php echo file_get_contents('header.html'); ?>

<!-- <?php echo __FILE__; ?> -->
<?php _formGenerator(); ?>
<!-- end.<?php echo __FILE__; ?> -->

<?php echo file_get_contents('footer.html'); ?>