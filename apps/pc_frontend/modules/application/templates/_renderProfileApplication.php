<?php
$c = new Criteria();
$c->add(MemberApplicationPeer::MEMBER_ID, $memberId);
$c->setLimit(3);
$member_apps = MemberApplicationPeer::doSelect($c);

foreach ($member_apps as $member_app)
{
  include_component('application','gadget',
    array(
      'width'      => 480,
      'view'       => 'home',
      'member_app' => $member_app
    ));
  
}
