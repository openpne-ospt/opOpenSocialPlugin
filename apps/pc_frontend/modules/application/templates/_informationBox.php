<div id="<?php echo $id ?>" class="dparts box applicationInfoBox<?php
if ($isOwner)
{
  echo " sortable";
}
?>"><div class="parts">

<div class="partsHeading">
<h3>
<?php if ($memberApplication) : ?>
<?php echo link_to($application->getTitle(), '@application_render?id='.$application->id.
(!$isOwner ? '&member_id='.$memberApplication->getMemberId() : '')) ?>
<?php else : ?>
<?php echo $application->getTitle() ?>
<?php endif ?>
</h3>
</div>

<div class="body">
<div class="applicationThumbnail">
<?php if ($application->getThumbnail()) : ?>
<?php echo image_tag($application->getThumbnail(), array('alt' => $application->getTitle())) ?>
<?php else: ?>
<?php echo image_tag('no_image.gif', array('size' => '76x76')) ?>
<?php endif; ?>
</div>

<div class="info">
<div class="description">
<?php echo $application->getDescription() ?>
</div>

<?php if ($application->getAuthor()) : ?>
<div class="author">
<?php if ($application->getAuthorEmail()) : ?>
<?php echo __('Author') ?>: <?php echo mail_to($application->getAuthorEmail() , $application->getAuthor(), array('encode' => true)) ?>
<?php else : ?>
<?php echo __('Author') ?>: <?php echo $application->getAuthor() ?>
<?php endif; ?>
</div>
<?php endif; ?>
</div>

<div class="operation">
<ul>
<?php if ($application->isActive()): ?>
<?php if($memberApplication && $isOwner) : ?>
<li><?php echo link_to_app_setting(__('Settings'), $memberApplication->id); ?></li>
<li><?php echo link_to(__('Remove'), '@application_remove?id='.$memberApplication->id); ?></li>
<?php else : ?>
<?php if ($application->getIsPc()): ?>
<li><?php echo link_to(__('Add this App'), '@application_add?id='.$application->getId()) ?></li>
<?php endif; ?>
<?php endif ?>
<?php else: ?>
<?php echo __('This is waiting to approval by the SNS administrator.') ?>
<?php endif; ?>
<li><?php echo link_to(__('Information'), '@application_info?id='.$application->getId()) ?></li>
</div>
</div>

<div style="clear:both;">&nbsp;</div>

</div></div>
