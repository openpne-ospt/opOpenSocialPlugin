<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<h2><?php echo __('アプリケーション詳細') ?></h2>
<table>
<tr><th colspan="2"><?php echo __('アプリケーション情報') ?></th></tr>
<tr><th><?php echo __('アプリケーション名') ?></th><td><?php echo $application->getTitle() ?></td></tr>
<tr><th><?php echo __('アプリケーションURL') ?></th><td><?php echo $application->getUrl() ?></td></tr>
<tr><th><?php echo __('タイトルURL') ?></th><td>
<?php if ($application->getTitleUrl()) : ?>
<?php echo link_to(null,$application->getTitleUrl(),array('target' => '_blank')) ?>
<?php endif ?>
</td></tr>
<tr><th><?php echo __('スクリーンショット') ?></th><td>
<?php if ($application->getScreenshot()) : ?>
<?php echo image_tag($application->getScreenshot(), array('alt' => $application->getTitle())) ?>
<?php endif ?>
</td></tr>
<tr><th><?php echo __('サムネイル') ?></th><td>
<?php if ($application->getThumbnail()) : ?>
<?php echo image_tag($application->getThumbnail(), array('alt' => $application->getTitle())) ?>
<?php endif ?>
</td></tr>
<tr><th><?php echo __('詳細') ?></th><td><?php echo $application->getDescription() ?></td></tr>
<tr><th><?php echo __('インストールしているメンバ数') ?></th><td><?php echo $application->getMembers()->count() ?></td></tr>
<tr><th><?php echo __('最終更新日') ?></th><td><?php echo $application->getUpdatedAt() ?></td></tr>
<tr><th colspan="2"><?php echo __('作成者情報') ?></th></tr>
<tr><th><?php echo __('作成者') ?></th><td><?php echo $application->getAuthorEmail() ? mail_to($application->getAuthorEmail(), $application->getAuthor(), array('encode' => true)) : $application->getAuthor() ?></td></tr>
<tr><th><?php echo __('所属') ?></th><td><?php echo $application->getAuthorAffiliation() ?></td></tr>
<tr><th><?php echo __('作者について') ?></th><td><?php echo $application->getAuthorAboutme() ?></td></tr>
<tr><th><?php echo __('写真') ?></th><td>
<?php if($application->getAuthorPhoto()) : ?>
<?php echo image_tag($application->getAuthorPhoto(), array('alt' => $application->getAuthor())) ?> 
<?php endif ?>
</td></tr>
<tr><th><?php echo __('リンク') ?></th><td>
<?php if ($application->getAuthorLink()) : ?>
<?php echo link_to(null,$application->getAuthorLink(),array('target' => '_blank')) ?>
<?php endif ?>
</td></tr>
<tr><th><?php echo __('引用') ?></th><td><?php echo $application->getAuthorQuote() ?></td></tr>
<tr><td colspan="2">
<?php echo link_to(__('削除'),'opOpenSocialPlugin/delete?id='.$sf_request->getParameter('id')) ?> 
<?php echo link_to(__('更新'),'opOpenSocialPlugin/update?id='.$sf_request->getParameter('id')) ?>
</td></tr>
</table>
