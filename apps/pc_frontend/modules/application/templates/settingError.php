<?php include_box('ApplicationError',__('アプリケーション設定'), __('このアクセスは有効ではありません。')) ?>

<?php use_helper('Javascript') ?>
<?php op_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>
