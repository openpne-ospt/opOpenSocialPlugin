<?php
include_partial('application/renderProfileApplication', array('memberId' => $sf_request->getParameter('id',$sf_user->getMember()->getId())));
