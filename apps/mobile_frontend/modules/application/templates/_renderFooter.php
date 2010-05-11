<hr color="<?php echo $op_color["core_color_11"] ?>">
<?php echo link_to(__('About App'), '@application_info?id='.$application->getId()) ?><br>
<?php echo link_to(__('My Apps'), '@my_application_list') ?>

<table width="100%">
<tbody><tr><td align="center" bgcolor="<?php echo $op_color["core_color_2"] ?>">
<font color="<?php echo $op_color["core_color_18"] ?>"><a href="<?php echo url_for('@homepage') ?>" accesskey="0"><font color="<?php echo $op_color["core_color_18"] ?>">0.<?php echo __('home') ?></font></a> / <a href="#top" accesskey="2"><font color="<?php echo $op_color["core_color_18"] ?>">2. <?php echo __('top') ?></font></a> / <a href="#bottom" accesskey="8"><font color="<?php echo $op_color["core_color_18"] ?>">8. <?php echo __('bottom') ?></font></a></font><br>
</td></tr></tbody></table>
