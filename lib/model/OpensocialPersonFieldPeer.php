<?php

class OpensocialPersonFieldPeer extends BaseOpensocialPersonFieldPeer
{
  public static function initialize()
  {
    if(!self::doCount(new Criteria()))
    {
      $profiles = ProfilePeer::doSelect(new Criteria());
      foreach ($profiles as $profile)
      {
        $opensocial = new OpensocialPersonField();
        $opensocial->setProfile($profile);
        switch ($profile->getName())
        {
          case 'sex':
            $opensocial->setFieldName('GENDER');
            $opensocial->save();
            break;
          case 'birthday':
            $opensocial->setFieldName('DATE_OF_BIRTH');
            $opensocial->save();
            break;
          case 'self_intro':
            $opensocial->setFieldName('ABOUT_ME');
            $opensocial->save();
            break;
        }
      }
    }
  }
}
