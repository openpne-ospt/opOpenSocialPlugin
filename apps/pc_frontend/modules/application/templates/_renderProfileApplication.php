<?php
$c = new Criteria();
$c->add(MemberApplicationPeer::MEMBER_ID, $memberId);
$c->add(MemberApplicationPeer::IS_DISP_HOME, 1);
if ($memberId != $sf_user->getMember()->getId())
{
  $c->add(MemberApplicationPeer::IS_DISP_OTHER, 1);
}
$c->addAscendingOrderByColumn(MemberApplicationPeer::SORT_ORDER);
$c->setLimit(SnsConfigPeer::get('home_application_limit', 3));
$member_apps = MemberApplicationPeer::doSelect($c);

foreach ($member_apps as $member_app)
{
  include_component('application','gadget',
    array(
      'view'       => 'home',
      'member_app' => $member_app
    )
  );
}
