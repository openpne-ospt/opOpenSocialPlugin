<div id="<?php echo $id ?>" class="dparts box"><div class="parts">

<div class="partsHeading">
<h3><?php echo link_to($title, 'application/canvas?mid='.$mid) ?></h3>
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
Author: <?php echo mail_to($author_email, $author, array('encode' => true)) ?>
<?php else : ?>
Author: <?php echo $author ?>
<?php endif; ?>
</div>
<?php endif; ?>
</div>
</div>

<div class="app_option" style="float:right;padding-right:5px;">
Sample
</div>

<div style="clear:both">&nbsp;</div>

</div></div>
