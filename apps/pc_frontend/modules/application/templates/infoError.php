<?php include_box('ApplicationInfoError', __('アプリケーション情報'), __('存在しないアプリケーションです。')) ?>

<?php use_helper('Javascript') ?>
<?php op_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>
