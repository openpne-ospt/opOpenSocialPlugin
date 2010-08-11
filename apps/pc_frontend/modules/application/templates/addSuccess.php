<?php slot('box_body') ?>
<div class="applicationInfoBox">
<div class="applicationThumbnail">
<?php if ($application->getThumbnail()) : ?>
<?php echo image_tag($application->getThumbnail(), array('alt' => $application->getTitle())) ?>
<?php else : ?>
<?php echo image_tag('no_image.gif', array('size' => '76x76')) ?>
<?php endif; ?>
</div>
<div class="info">
<?php echo __('Do you wish to install this Application?') ?><br>
<?php echo __('The Application might use your profile and your friends information.') ?>
</div>
</div>
<div style="clear: both;">&nbsp;</div>
<?php end_slot() ?>

<?php op_include_parts('yesNo', 'AddApplicationBox', array(
  'title'      => __('Add Application: %0%', array('%0%' => $application->getTitle())),
  'body'       => new sfOutputEscaperSafe(get_slot('box_body')),
  'yes_form'   => new sfForm(),
  'yes_method' => 'post',
  'no_url'     => url_for('application/gallery'),
)) ?>
