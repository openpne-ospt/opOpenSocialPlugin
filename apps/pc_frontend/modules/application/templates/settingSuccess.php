<?php include_box('formAppSetting', 'Application Settings for '.$appName, '', array(
  'form' => array($memberApplicationSettingForm,$applicationSettingForm),
  'url' => 'application/setting?mid='.$sf_request->getParameter('mid')
)) ?>
