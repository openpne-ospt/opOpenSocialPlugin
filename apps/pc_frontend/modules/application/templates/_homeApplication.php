<?php 
include_partial('application/renderProfileApplication', 
array(
  'memberId' => $sf_user->getMember()->getId(),
  'view' => 'home'
)
);
