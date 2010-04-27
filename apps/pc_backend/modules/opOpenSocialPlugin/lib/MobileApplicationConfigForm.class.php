<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * MobileApplicationConfigForm
 *
 * @package    opOpenSocialPlugin
 * @subpackage form
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
class MobileApplicationConfigForm extends BaseApplicationForm
{
  public function setup()
  {
    parent::setup();
    $this->useFields(array('is_mobile'));
    $this->widgetSchema->setLabel('is_mobile', 'Enable mobile App');
  }
}
