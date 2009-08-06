<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * ApplicationSearchForm
 *
 * @package    opOpenSocialPlugin
 * @subpackage form
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */

class ApplicationSearchForm extends sfForm
{
  static protected
    $sortOrderChoices = array(
      'created_at'      => '登録日時の昇順',
      'desc_created_at' => '登録日時の降順',
      'users'           => 'ユーザ数の昇順',
      'desc_users'      => 'ユーザ数の降順',
    );

  public function __construct($defaults = array(), $options = array(), $CSRFProtection = false)
  {
    parent::__construct($defaults, $options, $CSRFProtection);
  }

  public function configure()
  {
    $this->setWidgets(array(
      'keyword'    => new sfWidgetFormInput(),
      'order_by'   => new sfWidgetFormChoice(array('choices' => self::$sortOrderChoices))
    ));
    $this->setValidators(array(
      'keyword'    => new opValidatorSearchQueryString(array('required' => false)),
      'order_by'   => new sfValidatorChoice(array('choices' => array_keys(self::$sortOrderChoices)))
    ));
    $this->widgetSchema->setNameFormat('application[%s]');
  }

  public function getPager($page = 1, $size = 20)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    $query = Doctrine::getTable('Application')->createQuery('a')
      ->leftJoin('a.Translation t');
    $keywords = $this->getValue('keyword');
    if ($keywords)
    {
      if (!is_array($keywords))
      {
        $keywords = array($keywords);
      }
      foreach ($keywords as $keyword)
      {
        $query->addWhere('t.title LIKE ?', '%'.$keyword.'%');
      }
    }
    $pager = new sfDoctrinePager('Application', $size);
    $pager->setQuery($query);
    $pager->getPage($page);
    $pager->init();
    return $pager;
  }
}
