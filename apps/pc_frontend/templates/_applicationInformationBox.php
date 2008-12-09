<div id="<?php echo $id ?>" class="dparts box<?php
if ($is_owner)
{
  echo " sortable";
}
?>"><div class="parts">

<div class="partsHeading">
<h3>
<?php if ($mid) : ?>
<?php echo link_to($title, 'application/canvas?mid='.$mid) ?>
<?php else : ?>
<?php echo $title ?>
<?php endif ?>
</h3>
</div>

<div class="body">
<div class="app_thumbnail" style="overflow: hidden;float:left;width:120px;height:60px;">
<?php if (!empty($thumbnail)) : ?>
<img src="<?php echo $thumbnail ?>" alt="<?php echo $title ?>" />
<?php endif; ?>
</div>

<div class="app_info" style="width:60%;float:left;padding:0px 5px;">
<div class="app_description">
<?php echo $description ?>
</div>

<?php if (!empty($author)) : ?>
<div class="app_author">
<?php if (!empty($author_email)) : ?>
<?php echo __('Author') ?>: <?php echo mail_to($author_email, $author, array('encode' => true)) ?>
<?php else : ?>
<?php echo __('Author') ?>: <?php echo $author ?>
<?php endif; ?>
</div>
<?php endif; ?>
</div>
</div>

<div class="app_option" style="float:right;padding-right:5px;">
<ul>
<?php if($is_owner) : ?>
<li><?php echo link_to(__('Settings'), 'application/setting?mid='.$mid); ?></li>
<li><?php echo link_to(__('Remove'), 'application/remove?mid='.$mid); ?></li>
<?php else : ?>
<?php echo link_to(__('Add this application'), 'application/add?id='.$aid) ?>
<?php endif ?>
</div>

<div style="clear:both">&nbsp;</div>

</div></div>
