<?php include_box('formAppSetting', __('アプリケーション設定: ').$appName, '', array(
  'form' => array($memberApplicationSettingForm,$applicationSettingForm),
  'url' => 'application/setting?mid='.$sf_request->getParameter('mid')
)) ?>
