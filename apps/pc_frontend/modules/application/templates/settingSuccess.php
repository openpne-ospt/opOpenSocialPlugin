<?php include_box('formAppSetting', 'Application Settings for '.$appName, '', array(
  'form' => array($appsettingForm),
  'url' => 'application/setting?mid='.$sf_request->getParameter('mid')
)) ?>
