<?php include_box('ApplicationListError', __('Error'), __('Failed in adding the application.')) ?>

<?php use_helper('Javascript') ?>
<?php op_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>
