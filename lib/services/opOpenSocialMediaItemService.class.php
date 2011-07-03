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
  /**
   * get MediaItem from AlbumImage
   *
   * @param AlbumImage $albumImage
   * @return array
   */
  protected function getMediaItemFromAlbumImage(AlbumImage $albumImage)
  {
    $result['albumId']      = $albumImage->getAlbumId();
    $result['created']      = $albumImage->getCreatedAt();
    $result['description']  = opOpenSocialToolKit::convertEmojiForApi($albumImage->getDescription());
    $result['fileSize']     = $albumImage->getFilesize();
    $result['id']           = $albumImage->getId();
    $result['lastUpdated']  = $albumImage->getUpdatedAt();
    $result['thumbnailUrl'] = '';
    $result['title']        = $albumImage->getDescription();
    $result['type']         = 'IMAGE';
    $result['url']          = '';
    if ($albumImage->getFile())
    {
      sfContext::getInstance()->getConfiguration()->loadHelpers(array('Asset', 'sfImage'));
      $result['thumbnailUrl'] = sf_image_path($albumImage->getFile(), array('size' => '180x180'), true);
      $result['url'] = sf_image_path($albumImage->getFile(), array(), true);
    }

    return $result;
  }

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
        $results[] = $this->getMediaItemFromAlbumImage($object);
      }
    }

    $collection = new RestfulCollection($results, $first, $totalSize);
    $collection->setItemsPerPage($max);
    return $collection;
  }

  /**
   * createMediaItem
   *
   * @param UserId    $userId
   * @param GroupId   $groupId
   * @param array     $mediaItem
   * @param array     $data      An associative array that describes the uploaded file.
   * @param string    $token
   * @return the created media item.
   * @see   MediaItemService::createMediaItem()
   */
  public function createMediaItem($userId, $groupId, $mediaItem, $data, $token)
  {
    // check plugin
    if (!class_exists('Album'))
    {
      throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
    }

    // validate userId
    if (!is_object($userId))
    {
      $userId  = new UserId('userId', $userId);
      $groupId = new GroupId('self', null);
    }

    $memberIds = $this->getIdSet($userId, $groupId, $token);
    if ($groupId->getType() !== 'self' || count($memberIds) !== 1)
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }
    $memberId = $memberIds[0];

    // validate file
    if (!is_readable($data['tmp_name']))
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }

    $validatorFile = new opValidatorImageFile();
    $validatedFile = null;
    try
    {
      $validatedFile = $validatorFile->clean($data);
    }
    catch (sfValidatorError $e)
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }

    // validate mediaitem
    // check album id
    if (!isset($mediaItem['albumId']))
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }
    $album = Doctrine::getTable('Album')->find($mediaItem['albumId']);

    if (!$album)
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }

    if ($memberId != $album->getMemberId())
    {
      throw new SocialSpiException("Forbidden", ResponseError::$FORBIDDEN);
    }

    $file = new File();
    $file->setFromValidatedFile($validatedFile);

    // check description
    $description = '';
    if (isset($mediaItem['title']))
    {
      $stringValidator = new opValidatorString(array('required' => false, 'trim' => true));
      try
      {
        $description = $stringValidator->clean($mediaItem['title']);
      }
      catch (sfValidatorError $e)
      {
        throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
      }
    }
    $description = empty($description) ? $file->getName() : $description;

    $albumImage = new AlbumImage();
    $albumImage->setAlbum($album);
    $albumImage->setFile($file);
    $albumImage->setDescription($description);
    $albumImage->save();

    // save
    return $this->getMediaItemFromAlbumImage($albumImage);
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

