<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpensocialMediaItemService
 *
 * @author Shogo Kawahara <kawahara@bucyou.net>
 */
class opOpenSocialMediaItemService extends opOpenSocialServiceBase implements MediaItemService
{
  public function getMediaItems($userId, $groupId, $albumId, $mediaItemIds, $collectionOptions, $fields, $token)
  {
    if (!class_exists('Album'))
    {
      throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
    }

    $first = $this->fixStartIndex($collectionOptions->getStartIndex());
    $max   = $this->fixCount($collectionOptions->getCount());

    if (!is_object($userId))
    {
      $userId  = new UserId('userId', $userId);
      $groupId = new GroupId('self', 'all');
    }

    $memberIds = $this->getIdSet($userId, $groupId, $token);
    if ($groupId->getType() !== 'self' || count($memberIds) !== 1)
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }
    $memberId = $memberIds[0];

    $albumObject = Doctrine::getTable('Album')->find($albumId);
    if (!$albumObject)
    {
      throw new SocialSpiException("Album Not Found", ResponseError::$BAD_REQUEST);
    }
    if ($albumObject->getMemberId() != $memberId &&
      !($albumObject->getPublicFlag() === AlbumTable::PUBLIC_FLAG_SNS ||
      $albumObject->getPublicFlag() === AlbumTable::PUBLIC_FLAG_OPEN))
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }

    $totalSize = 0;
    $query = Doctrine::getTable('AlbumImage')->createQuery()
      ->where('album_id = ?', $albumObject->getId());
    $totalSize = $query->count();

    $query->offset($first);
    $query->limit($max);

    $objects = $query->execute();

    $results = array();

    // block check
    $isBlock = false;
    if ($token->getViewerId())
    {
      $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($memberId, $token->getViewerId());
      if ($relation && $relation->getIsAccessBlock())
      {
        $isBlock = true;
      }
    }

    if (!$isBlock)
    {
      foreach ($objects as $object)
      {
        $result['albumId']      = $object->getAlbumId();
        $result['created']      = $object->getCreatedAt();
        $result['description']  = opOpenSocialToolKit::convertEmojiForApi($object->getDescription());
        $result['fileSize']     = $object->getFilesize();
        $result['id']           = $object->getId();
        $result['lastUpdated']  = $object->getUpdatedAt();
        $result['thumbnailUrl'] = '';
        $result['title']        = $object->getDescription();
        $result['type']         = 'IMAGE';
        $result['url']          = '';
        if ($object->getFile())
        {
          sfContext::getInstance()->getConfiguration()->loadHelpers(array('Asset', 'sfImage'));
          $result['thumbnailUrl'] = sf_image_path($object->getFile(), array('size' => '180x180'), true);
          $result['url'] = sf_image_path($object->getFile(), array(), true);
        }
        $results[] = $result;
      }
    }

    $collection = new RestfulCollection($results, $first, $totalSize);
    $collection->setItemsPerPage($max);
    return $collection;
  }

  public function createMediaItem($userId, $groupId, $mediaItem, $data, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function updateMediaItem($userId, $groupId, $mediaItem, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function deleteMediaItems($userId, $groupId, $albumId, $mediaItemIds, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }
}

