<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<h2><?php echo __('アプリケーション詳細') ?></h2>
<table>
<tr><th>アプリケーション名</th><td><?php echo $application->getTitle() ?></td></tr>
<tr><th>アプリケーションURL</th><td><?php echo $application->getUrl() ?></td></tr>
</table>

