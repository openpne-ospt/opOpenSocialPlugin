<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<h2><?php echo __('アプリケーション管理') ?></h2>
<table>
<?php $searchForm->renderFormTag(url_for('opOpenSocialPlugin/list'), array('method' => 'get')) ?>
<?php echo $searchForm ?>
<td colspan="2"><input type="submit" value="検索"/></td>
</table>

<?php if ($pager->getNbResults()): ?>
<?php slot('pager') ?>
<?php echo pager_navigation($pager, 'opOpenSocialPlugin/list?page=%d', array('use_current_query_string' => true)) ?>
<?php end_slot(); ?>
<?php include_slot('pager') ?>
<ul>
<?php foreach ($pager->getResults() as $application): ?>
<li><?php echo link_to($application->getTitle(),'opOpenSocialPlugin/info?id='.$application->getId()) ?></li>
<?php endforeach; ?>
</ul>
<?php include_slot('pager') ?>
<?php else: ?>
アプリケーションがありません。<?php echo link_to('こちら', 'opOpenSocialPlugin/add') ?>からアプリケーションを追加できます。
<?php endif; ?>
