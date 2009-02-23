<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Open Social Person Field Config Form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class OpenSocialPersonFieldConfigForm extends sfForm
{
  protected static $choices = array(
    ''             => '-',            
    'aboutMe'      => 'ABOUT_ME',
    'activities'   => 'ACTIVITIES',
    //'addressed'    => 'ADDRESSES',
    'age'          => 'AGE',
    //'bodyType'     => 'BODY_TYPE',
    'books'        => 'BOOKS',
    'cars'         => 'CARS',
    'children'     => 'CHILDREN',
    //'currentLocation' => 'CURRENT_LOCATION',
    'dateOfBirth'  => 'DATE_OF_BIRTH',
    //'drinker'      => 'DRINKER'
    'emails'       => 'EMAILS',
    'ethnicity'    => 'ETHNICITY',
    'fashion'      => 'FASHION',
    'food'         => 'FOOD',
    'gender'       => 'GENDER',
    'happiestWhen' => 'HAPPIEST_WHEN',
    'heroes'       => 'HEROES',
    'humor'        => 'HUMOR',
    'interests'    => 'INTERESTS',
    'jobInterests' => 'JOB_INTERESTS',
    //'jobs'       => 'JOBS',
    'languagesSpoken'   => 'LANGUAGES_SPOKEN',
    'livingArrangement' => 'LIVING_ARRANGEMENT',
    'lookingFor'   => 'LOOKING_FOR',
    'movies'       => 'MOVIES',
    'music'        => 'MUSIC',
    'pets'         => 'PETS',
    'phoneNumbers' => 'PHONE_NUMBERS',
    'politicalViews' => 'POLITICAL_VIEWS',
    'profileSong'  => 'PROFILE_SONG',
    'profileVideo' => 'PROFILE_VIDEO',
    'quotes'       => 'QUOTES',
    'relationshipStatus' => 'RELATIONSHIP_STATUS',
    'religion'     => 'RELIGION',
    'romance'      => 'ROMANCE',
    'scaredOf'     => 'SCARED_OF',
    //'schools'      => 'SCHOOLS',
    'sexualOrientation' => 'SEXUAL_ORIENTATION',
    //'smoker'       => 'SMOKER',
    'status'       => 'STATUS',
    'tags'         => 'TAGS',
    'timeZone'     => 'TIME_ZONE',
    'turnOffs'     => 'TURN_OFFS',
    'turnOns'      => 'TURN_ONS',
    'tvShows'      => 'TV_SHOWS',
    'urls'         => 'URLS'
  );
  public function configure()
  {
    $profiles = ProfilePeer::doSelect(new Criteria());
    $params = array('choices' => self::$choices);
    $option = array('choices' => array_keys(self::$choices), 'required' => false);
    foreach ($profiles as $profile)
    {
      $osPersonField = $profile->getOpensocialPersonField();
      $this->setWidget($profile->getName(), new sfWidgetFormSelect($params));
      $this->setValidator($profile->getName(), new sfValidatorChoice($option));
      if ($osPersonField)
      {
        $this->setDefault($profile->getName(), $osPersonField->getFieldName());
      }
    }
    $this->widgetSchema->setNameFormat('opensocial_person_field_config[%s]');
  }

  public function save()
  {
    foreach ($this->getValues() as $key => $value)
    {
      $profile = ProfilePeer::retrieveByName($key);
      if (!$profile)
      {
        continue;
      }

      $osPersonField = $profile->getOpensocialPersonField();
      if (!$osPersonField)
      {
        $osPersonField = new OpensocialPersonField();
        $osPersonField->setProfile($profile);
      }
      $osPersonField->setFieldName($value);
      $osPersonField->save();
    }

    //prod
    $opOpenSocialContainerConfig = new opOpenSocialContainerConfig(false);
    $opOpenSocialContainerConfig->generateAndSave(true);

    //dev
    $opOpenSocialContainerConfig = new opOpenSocialContainerConfig(true);
    $opOpenSocialContainerConfig->generateAndSave(true);
  }
}

