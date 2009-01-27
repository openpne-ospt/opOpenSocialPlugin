<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<p>アプリケーションの追加に失敗しました。</p>

<?php use_helper('Javascript') ?>
<?php op_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>
