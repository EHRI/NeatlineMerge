<?php
$head = array('bodyclass' => 'primary',
              'title' => html_escape(__('Neatline Merge')),
              'content_class' => 'horizontal-nav');
echo head($head);
?>

<?php echo flash(); ?>
<?php echo $form; ?>
<?php echo foot(); ?>
