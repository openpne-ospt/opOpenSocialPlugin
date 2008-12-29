<?php
if (!empty($memberApplication))
{
  include_component('application', 'gadget',
    array(
      'view'       => 'home',
      'member_app' => $memberApplication
    )
  );
}
else
{
  include_box('HomeApplicationError', __('エラー'), __('アプリケーションが正常にロードできませんでした。'));
}
