<?php
use_helper('OpenSocial');
op_include_application_setting();
?>

<?php foreach ($memberApplications as $memberApplication): ?>
<?php if ($memberApplication->getApplicationSetting('is_view_home')): ?>
<?php include_component('application', 'gadget', array('view' => 'home', 'memberApplication' => $memberApplication)) ?>
<?php endif; ?>
<?php endforeach; ?>
