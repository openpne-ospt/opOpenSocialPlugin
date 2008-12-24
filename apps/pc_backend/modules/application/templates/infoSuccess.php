<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<h2><?php echo __('アプリケーション詳細') ?></h2>
<table>
<tr><th><?php echo __('アプリケーション名') ?></th><td><?php echo $application->getTitle() ?></td></tr>
<tr><th><?php echo __('アプリケーションURL') ?></th><td><?php echo $application->getUrl() ?></td></tr>
<tr><th><?php echo __('スクリーンショット') ?></th><td><?php 
if ($application->getScreenshot())
{
  echo image_tag($application->getScreenshot(), array('alt' => $application->getTitle()));
}
?></td></tr>
<tr><th><?php echo __('サムネイル') ?></th><td><?php
if ($application->getThumbnail())
{
  echo image_tag($application->getThumbnail(), array('alt' => $application->getTitle()));
}
?></td></td></tr>
<tr><th><?php echo __('作成者') ?></th><td><?php
if ($application->getAuthorEmail())
{
  echo mail_to($application->getAuthorEmail(), $application->getAuthor(), array('encode' => true));
}
else
{
  echo $application->getAuthor();
}
?></td></tr>
<tr><th><?php echo __('詳細') ?></th><td><?php echo $application->getDescription() ?></td></tr>
<tr><th><?php echo __('最終更新日') ?></th><td><?php echo $application->getUpdatedAt() ?></td></tr>
<tr><th><?php echo __('インストールしているユーザ') ?></th><td><?php echo $application->countInstalledMember() ?></td></tr>
<tr><td colspan="2">
<?php echo link_to(__('削除'),'application/deleteApplication?id='.$sf_request->getParameter('id')) ?> 
<?php echo link_to(__('更新'),'application/updateApplication?id='.$sf_request->getParameter('id')) ?>
</td></tr>
</table>
