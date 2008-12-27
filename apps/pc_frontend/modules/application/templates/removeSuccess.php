<?php include_box('ApplicationRemoveConfirm',__('アプリケーション削除確認'),
  __('本当にこのアプリケーションを削除しますか？').'<br />'.
  '<form action="'.url_for('application/remove?id='.$sf_request->getParameter('id')).'" method="post">
  <input type="submit" value="'.__('はい').'" />
  </form>'
);
