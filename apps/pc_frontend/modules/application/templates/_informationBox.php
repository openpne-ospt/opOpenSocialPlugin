<div id="<?php echo $id ?>" class="dparts box<?php
if ($isOwner)
{
  echo " sortable";
}
?>"><div class="parts">

<div class="partsHeading">
<h3>
<?php if ($mid) : ?>
<?php echo link_to($application->getTitle(), 'application/canvas?id='.$mid) ?>
<?php else : ?>
<?php echo $application->getTitle() ?>
<?php endif ?>
</h3>
</div>

<div class="body">
<div class="app_thumbnail">
<?php if ($application->getThumbnail()) : ?>
<?php echo image_tag($application->getThumbnail(), array('alt' => $application->getTitle())) ?>
<?php endif; ?>
</div>

<div class="app_info">
<div class="app_description">
<?php echo $application->getDescription() ?>
</div>

<?php if ($application->getAuthor()) : ?>
<div class="app_author">
<?php if ($application->getAuthorEmail()) : ?>
<?php echo __('Author') ?>: <?php echo mail_to($application->getAuthorEmail() , $application->getAuthor(), array('encode' => true)) ?>
<?php else : ?>
<?php echo __('Author') ?>: <?php echo $application->getAuthor() ?>
<?php endif; ?>
</div>
<?php endif; ?>
</div>
</div>

<div class="app_option">
<ul>
<?php if($isOwner) : ?>
<li><?php echo link_to_app_setting(__('設定'), $mid); ?></li>
<li><?php echo link_to(__('削除'), 'application/remove?id='.$mid); ?></li>
<?php else : ?>
<?php echo link_to(__('このアプリを追加する'), 'application/add?id='.$aid) ?>
<?php endif ?>
<?php echo link_to(__('詳細'), 'application/info?id='.$aid) ?>
</div>

<div style="clear:both;">&nbsp;</div>

</div></div>
