<?php
$app_info_list = array(
  __('アプリケーション名')  => $application->getTitleUrl() ? link_to($application->getTitle(), $application->getTitleUrl(), array('target' => '_blank')) : $application->getTitle(),
  __('スクリーンショット')  => $application->getScreenshot() ? image_tag($application->getScreenshot(), array('alt' => $application->getTitle())) : '',
  __('サムネイル')          => $application->getThumbnail() ? image_tag($application->getThumbnail(), array('alt' => $application->getTitle())) : '',
  __('詳細') => $application->getDescription(),
  __('インストールしているメンバ数') => $application->countInstalledMember(),
);
$author_info_list = array(
  __('作成者') => $application->getAuthorEmail() ? mail_to($application->getAuthorEmail(), $application->getAuthor(), array('encode' => true)) : $application->getAuthor(),
  __('所属')   => $application->getAuthorAffiliation(),
  __('作者について') => $application->getAuthorAboutme(),
  __('写真')   => $application->getAuthorPhoto() ? image_tag($application->getAuthorPhoto(), array('alt' => $application->getAuthor())) : '',
  __('リンク') => $application->getAuthorLink() ? link_to(null , $application->getAuthorLink(), array('target' => '_blank')) : '',
  __('引用')   => $application->getAuthorQuote(),
);
include_list_box('ApplicationInfoList', $app_info_list, array('title' => __('アプリケーション詳細')));
include_list_box('AuthorInfoList', $author_info_list, array('title' => __('作成者情報')));
echo link_to(__('このアプリを追加する'), 'application/add?id='.$application->getId());
?>

