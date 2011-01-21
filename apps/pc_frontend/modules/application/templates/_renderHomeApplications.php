<?php
use_helper('OpenSocial');
$maxNum = (int)$gadget->getConfig('num');
$i = 0;
?>

<?php foreach ($memberApplications as $memberApplication): ?>
<?php if ($i >= $maxNum) break; ?>
<?php if ($memberApplication->getApplicationSetting('is_view_home')): ?>
<?php include_component('application', 'gadget', array(
  'view' => 'home',
  'memberApplication' => $memberApplication,
  'titleLinkTo' => '@application_render?id='.$memberApplication->getApplicationId(),
)) ?>
<?php $i++ ?>
<?php endif; ?>
<?php endforeach; ?>
