<?php include_box('formAppSetting', __('アプリケーション設定: ').$appName, '', array(
  'form' => $forms,
  'url' => 'application/setting?id='.$sf_request->getParameter('id')
)) ?>
