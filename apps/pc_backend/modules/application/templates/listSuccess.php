<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<h2><?php echo __('アプリケーション管理') ?></h2>
<form action="<?php echo url_for('application/list') ?>" method="post">
<?php echo $addform['application_url']->renderError() ?>
<?php echo $addform['application_url']->render() ?>
<input type="submit" value="追加" />
</form>

<?php echo pager_navigation($pager, 'application/list?page=%d') ?>
<ul>
<?php foreach ($pager->getResults() as $application) : ?>
<li><?php echo link_to($application->getTitle(),'application/info?id='.$application->getId()) ?></li>
<?php endforeach ?>
</ul>
<?php echo pager_navigation($pager, 'application/list?page=%d') ?>
