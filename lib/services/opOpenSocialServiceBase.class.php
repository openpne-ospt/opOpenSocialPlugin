<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpensocialServiceBase
 *
 * @author Shogo Kawahara <kawahara@bucyou.net>
 */
class opOpenSocialServiceBase
{
  protected function getIdSet($user, GroupId $group, SecurityToken $token)
  {
    $ids = array();
    if ($user instanceof UserId)
    {
      $userId = $user->getUserId($token);
      if ($group == null)
      {
        return array($userId);
      }
      switch ($group->getType())
      {
        case 'friends':
          $ids = Doctrine::getTable('MemberRelationship')->getFriendMemberIds($userId);
          break;
        case 'self':
          $ids[] = $userId;
          break;
        case 'all':
        case 'groupId':
          throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
      }
    }
    elseif (is_array($user))
    {
      $ids = array();
      foreach ($user as $id)
      {
        $ids = array_merge($ids, $this->getIdSet($id, $group, $token));
      }
    }

    // block check
    if ($token->getViewerId())
    {
      $blockedIds = Doctrine::getTable('MemberRelationship')->getBlockedMemberIdsByTo($token->getViewerId());

      foreach ($ids as $k => $id)
      {
        if (isset($blockedIds[$id]))
        {
          unset($ids[$k]);
        }
      }
    }

    return $ids;
  }

  protected function fixStartIndex($startIndex = null)
  {
    if (!($startIndex !== false && is_numeric($startIndex) && $startIndex >= 0))
    {
      return 0;
    }

    return $startIndex;
  }

  protected function fixCount($count = null)
  {
    if (!($count !== false && is_numeric($count) && $count > 0))
    {
      return RequestItem::$DEFAULT_COUNT;
    }

    if ($count > sfConfig::get('op_opensocial_api_max_count', 100))
    {
      return sfConfig::get('op_opensocial_api_max_count', 100);
    }

    return $count;
  }
}

