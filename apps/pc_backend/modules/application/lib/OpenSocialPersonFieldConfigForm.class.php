<?php
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
    ''           => '-',            
    'ABOUT_ME'   => 'ABOUT_ME',
    'ACTIVITIES' => 'ACTIVITIES',
    //'ADDRESSES'  => 'ADDRESSES',
    'AGE'        => 'AGE',
    //'BODY_TYPE'  => 'BODY_TYPE',
    'BOOKS'      => 'BOOKS',
    'CARS'       => 'CARS',
    'CHILDREN'   => 'CHILDREN',
    //'CURRENT_LOCATION' => 'CURRENT_LOCATION',
    'DATE_OF_BIRTH' => 'DATE_OF_BIRTH',
    //'DRINKER'    => 'DRINKER'
    'EMAILS'     => 'EMAILS',
    'ETHNICITY'  => 'ETHNICITY',
    'FASHION'    => 'FASHION',
    'FOOD'       => 'FOOD',
    'GENDER'     => 'GENDER',
    'HAPPIEST_WHEN' => 'HAPPIEST_WHEN',
    'HEROES'     => 'HEROES',
    'HUMOR'      => 'HUMOR',
    'INTERESTS'  => 'INTERESTS',
    'JOB_INTERESTS' => 'JOB_INTERESTS',
    //'JOBS'       => 'JOBS',
    'LANGUAGES_SPOKEN' => 'LANGUAGES_SPOKEN',
    'LIVING_ARRANGEMENT' => 'LIVING_ARRANGEMENT',
    'LOOKING_FOR'=> 'LOOKING_FOR',
    'MOVIES'     => 'MOVIES',
    'MUSIC'      => 'MUSIC',
    //'NAME'     => 'NAME',
    'PETS'       => 'PETS',
    'PHONE_NUMBERS' => 'PHONE_NUMBERS',
    'POLITICAL_VIEWS' => 'POLITICAL_VIEWS',
    'PROFILE_SONG'    => 'PROFILE_SONG',
    'PROFILE_VIDEO'   => 'PROFILE_VIDEO',
    'QUOTES'     => 'QUOTES',
    'RELATIONSHIP_STATUS' => 'RELATIONSHIP_STATUS',
    'RELIGION'   => 'RELIGION',
    'ROMANCE'    => 'ROMANCE',
    'SCARED_OF'  => 'SCARED_OF',
    //'SCHOOLS'    => 'SCHOOLS',
    'SEXUAL_ORIENTATION' => 'SEXUAL_ORIENTATION',
    //'SMOKER'     => 'SMOKER',
    'STATUS'     => 'STATUS',
    'TAGS'       => 'TAGS',
    'TIME_ZONE'  => 'TIME_ZONE',
    'TURN_OFFS'  => 'TURN_OFFS',
    'TURN_ONS'   => 'TURN_ONS',
    'TV_SHOWS'   => 'TV_SHOWS',
    'URLS'       => 'URLS'

  );
  public function configure()
  {
    OpensocialPersonFieldPeer::initialize();
    $profiles = ProfilePeer::doSelect(new Criteria());
    $params = array('choices' => self::$choices);
    $option = array('choices' => self::$choices, 'required' => true);
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
  }

  public function save()
  {
  }
}

