<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpensocialAlbumService
 *
 * @author Shogo Kawahara <kawahara@bucyou.net>
 */
class opOpenSocialAlbumService extends opOpenSocialServiceBase implements AlbumService
{
  public function getAlbums($userId, $groupId, $albumIds, $collectionOptions, $fields, $token)
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
    $albumIds = array_unique($albumIds);

    $objects = array();
    $totalSize = 0;

    if (count($memberIds))
    {
      $query = Doctrine::getTable('Album')->createQuery()
        ->whereIn('member_id', $memberIds);

      Doctrine::getTable('Album')->addPublicFlagQuery($query, AlbumTable::PUBLIC_FLAG_SNS);

      $totalSize = $query->count();

      $query->orderBy('id');
      if (count($albumIds))
      {
        $query->andWhereIn('id', $albumIds);
      }

      $query->offset($first);
      $query->limit($max);

      $objects = $query->execute();
    }
    $results = array();
    foreach ($objects as $object)
    {
      $result = array();
      $result['id']             = $object->getId();
      $result['title']          = opOpenSocialToolKit::convertEmojiForApi($object->getTitle());
      $result['description']    = opOpenSocialToolKit::convertEmojiForApi($object->getBody());
      $result['mediaItemCount'] = 0;
      if ($object->getAlbumImages())
      {
        $result['mediaItemCount'] = count($object->getAlbumImages());
      }
      $result['ownerId'] = $object->getMemberId();
      $result['thumbnailUrl'] = '';
      if ($object->getFileId())
      {
        sfContext::getInstance()->getConfiguration()->loadHelpers(array('Asset', 'sfImage'));
        $result['thumbnailUrl'] = sf_image_path($object->getFile(), array('size' => '180x180'), true);
      }
      $result['mediaType'] = 'IMAGE';
      $results[] = $result;
    }

    $collection = new RestfulCollection($results, $first, $totalSize);
    $collection->setItemsPerPage($max);
    return $collection;
  }

  public function createAlbum($userId, $groupId, $album, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function updateAlbum($userId, $groupId, $album, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function deleteAlbum($userId, $groupId, $albumId, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }
}

