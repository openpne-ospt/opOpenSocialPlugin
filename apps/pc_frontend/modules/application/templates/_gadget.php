<div class="dparts box" style="width:<?php echo $width ?>px;"><div class="parts">
<div id="gadgets-gadget-title-bar-<?php echo $mid ?>" class="partsHeading">
<p class="link"><?php 
if ($isViewer)
{
  if ($hasSettings)
  {
    echo link_to("Settings","application/setting?mid=".$mid);
  }
}
else
{
  echo "add this application";
}
?></p>
<h3 id="remote_iframe_<?php echo $mid ?>_title"><?php echo $title ?></h3>
</div>
<div class="block">
<iframe width="<?php echo ($width) ?>" scrolling="<?php
if ($scrolling)
{
  echo "on";
}
else
{
  echo "off";
}
?>" height="<?php echo ($height) ?>" frameborder="no" src="<? echo $iframe_url ?>" class="gadgets-gadget" name="remote_iframe_<?php echo $mid ?>" id="remote_iframe_<?php echo $mid ?>"></iframe>
</div>
</div></div>
