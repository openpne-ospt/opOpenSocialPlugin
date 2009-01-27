<?php include_box('ApplicationRemoveError', __('アプリケーション削除'), __('アプリケーションの削除に失敗しました。')) ?>

<?php use_helper('Javascript') ?>
<?php op_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>
