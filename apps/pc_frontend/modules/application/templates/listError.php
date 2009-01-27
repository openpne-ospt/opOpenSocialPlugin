<?php include_box('ApplicationListError', __('アプリケーション追加'), __('アプリケーションの追加に失敗しました。')) ?>

<?php use_helper('Javascript') ?>
<?php op_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>
