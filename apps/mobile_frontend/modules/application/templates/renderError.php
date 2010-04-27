<?php echo __('Error') ?>

<?php slot('op_mobile_footer_menu') ?>
<?php echo link_to(__('About App'), '@application_info?id='.$application->getId()) ?><br>
<?php echo link_to(__('My Apps'), '@my_application_list') ?>
<?php end_slot(); ?>
