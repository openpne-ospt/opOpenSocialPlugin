<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';
include dirname(__FILE__).'/../../bootstrap/database.php';
sfContext::createInstance($configuration);

$t = new lime_test(10, new lime_output_color());

$conn = Doctrine::getTable('Application')->getConnection();
$conn->beginTransaction();

$class = new opOpensocialMediaItemService();

$userId  = new UserId('userId', 1);
$groupId = new GroupId('self', null);

$wrong_groupId1 = new GroupId('friends', null);
$wrong_groupId2 = new GroupId('all', null);
$wrong_groupId3 = new GroupId('groupId', 1);

$mediaItem = new MediaItem('image/jpeg', 'IMAGE', '');
$mediaItem->setAlbumId(1);
$mediaItem = (array)$mediaItem;

// wrong MediaItem 1 - wrong album id
$wrong_mediaItem1 = new MediaItem('image/jpeg', 'IMAGE', '');
$wrong_mediaItem1->setAlbumId(999);
$wrong_mediaItem1 = (array)$wrong_mediaItem1;

// wrong MediaItem 2 - wrong album id
$wrong_mediaItem2 = new MediaItem('image/jpeg', 'IMAGE', '');
$wrong_mediaItem2->setAlbumId(2);
$wrong_mediaItem2 = (array)$wrong_mediaItem2;

$file = array(
  'tmp_name' => dirname(__FILE__).'/../../images/OpenPNE.jpg',
  'type'     => 'image/jpeg',
  'size'     => 8327,
  'name'     => 'OpenPNE.jpg'
);

// wrong file 1 - filesize is too big.
$wrong_file1 = array(
  'tmp_name' => dirname(__FILE__).'/../../images/OpenPNE_h.jpg',
  'type'     => 'image/jpeg',
  'size'     => 14267,
  'name'     => 'OpenPNE_h.jpg'
);

// wrong file 2 - tmp file is not exists.
$wrong_file2 = array(
  'tmp_name' => dirname(__FILE__).'/../../images/xxx.jpg',
  'type'     => 'image/jpeg',
  'size'     => 0,
  'name'     => 'xxx.jpg'
);

// wrong file 3 - invalid filetype.
$wrong_file3 = array(
  'tmp_name' => dirname(__FILE__).'/../../images/OpenPNE.bmp',
  'type'     => 'image/bmp',
  'size'     => 1534,
  'name'     => 'OpenPNE.bmp'
);

$token = opShindigSecurityToken::createFromValues(1, 1, 1, '', '', 1, '');

$result = $class->createMediaItem($userId, $groupId, $mediaItem, $file, $token);
$t->isa_ok($result, 'array', '->createMediaItem() returns an instance of MediaItem.');

$msg = '->createMediaItem() saves an image to database';
if (is_array($result) && isset($result['id']))
{
  $albumImage = Doctrine::getTable('AlbumImage')->find($result['id']);
  if ($albumImage)
  {
    $t->pass($msg);
  }
  else
  {
    $t->fail($msg);
  }
}
else
{
  $t->fail($msg);
}

$msg = '->createMediaItem() throws a SocialSpiException because of the wrong MediaItem (Album is not exists).';
try
{
  $class->createMediaItem($userId, $groupId, $wrong_mediaItem1, $file, $token);
  $t->fail($msg);
}
catch (SocialSpiException $e)
{
  $t->pass($msg);
}
catch (Exception $e)
{
  $t->fail($msg);
}

$msg = '->createMediaItem() throws a SocialSpiException because of the wrong MediaItem (User have not the permission of Album).';
try
{
  $class->createMediaItem($userId, $groupId, $wrong_mediaItem2, $file, $token);
  $t->fail($msg);
}
catch (SocialSpiException $e)
{
  $t->is($e->getMessage(), 'Forbidden', $msg);
}
catch (Exception $e)
{
  $t->fail($msg);
}

$msg = '->createMediaItem() throws a SocialSpiException because of the wrong file (filesize is too big).';
try
{
  $class->createMediaItem($userId, $groupId, $mediaItem, $wrong_file1, $token);
  $t->fail($msg);
}
catch (SocialSpiException $e)
{
  $t->pass($msg);
}
catch (Exception $e)
{
  $t->fail($msg);
}

$msg = '->createMediaItem() throws a SocialSpiException because of the wrong file (file is not exists).';
try
{
  $class->createMediaItem($userId, $groupId, $mediaItem, $wrong_file2, $token);
  $t->fail($msg);
}
catch (SocialSpiException $e)
{
  $t->pass($msg);
}
catch (Exception $e)
{
  $t->fail($msg);
}

$msg = '->createMediaItem() throws a SocialSpiException because of the wrong file (wrong file type).';
try
{
  $class->createMediaItem($userId, $groupId, $mediaItem, $wrong_file3, $token);
  $t->fail($msg);
}
catch (SocialSpiException $e)
{
  $t->is($e->getMessage(), 'Bad Request', $msg);
}
catch (Exception $e)
{
  $t->fail($msg);
}

$msg = '->createMediaItem() throws a SocialSpiException because of the wrong groupId (friends).';
try
{
  $class->createMediaItem($userId, $wrong_groupId1, $mediaItem, $file, $token);
  $t->fail($msg);
}
catch (SocialSpiException $e)
{
  $t->is($e->getMessage(), 'Bad Request', $msg);
}
catch (Exception $e)
{
  $t->fail($msg);
}

$msg = '->createMediaItem() throws a SocialSpiException because of the wrong groupId (all).';
try
{
  $class->createMediaItem($userId, $wrong_groupId2, $mediaItem, $file, $token);
  $t->fail($msg);
}
catch (SocialSpiException $e)
{
  $t->is($e->getMessage(), 'Not implemented', $msg);
}
catch (Exception $e)
{
  $t->fail($msg);
}

$msg = '->createMediaItem() throws a SocialSpiException because of the wrong groupId (groupId).';
try
{
  $class->createMediaItem($userId, $wrong_groupId3, $mediaItem, $file, $token);
  $t->fail($msg);
}
catch (SocialSpiException $e)
{
  $t->is($e->getMessage(), 'Not implemented', $msg);
}
catch (Exception $e)
{
  $t->fail($msg);
}

$conn->rollback();
