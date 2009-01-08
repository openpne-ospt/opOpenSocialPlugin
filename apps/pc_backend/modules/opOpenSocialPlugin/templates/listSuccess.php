<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<h2><?php echo __('アプリケーション管理') ?></h2>
<form action="<?php echo url_for('opOpenSocialPlugin/list') ?>" method="post">
<?php echo $addform->renderGlobalErrors() ?>
<?php echo $addform['application_url']->renderError() ?>
<?php echo $addform['application_url']->render() ?>
<?php echo $addform->renderHiddenFields() ?>
<input type="submit" value="追加" />
</form>

<?php echo pager_navigation($pager, 'opOpenSocialPlugin/list?page=%d') ?>
<ul>
<?php foreach ($pager->getResults() as $application) : ?>
<li><?php echo link_to($application->getTitle(),'opOpenSocialPlugin/info?id='.$application->getId()) ?></li>
<?php endforeach ?>
</ul>
<?php echo pager_navigation($pager, 'opOpenSocialPlugin/list?page=%d') ?>
