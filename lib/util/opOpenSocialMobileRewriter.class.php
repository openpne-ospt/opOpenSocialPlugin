<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpenSocialMobileRewriter
 *
 * @package    opOpenSocialPlugin
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
class opOpenSocialMobileRewriter
{
  protected
    $action = null,
    $rewriteUrlConfig = null;

 /**
  * constractor
  *
  * @param sfAction $action
  */
  public function __construct(sfAction $action)
  {
    $this->action = $action;
    $configcache = $action->getContext()->getConfiguration()->getConfigCache();
    $file = 'config/op_opensocial_mobile_rewrite_url.yml';
    $configcache->registerConfigHandler($file, 'sfSimpleYamlConfigHandler');
    $this->rewriteUrlConfig = include($configcache->checkConfig($file));
  }

  /**
   * rewrite
   *
   * @param string $body
   * @return string
   */
  public function rewrite($body)
  {
    $patterns = array();
    $replacements = array();

    $patterns[] = "/<\?xml(.*)encoding=[\"'].*[\"']/iU";
    $replacements[] = '<?xml${1}encoding="shift-jis"';

    $patterns[] = "/<meta(.*)content=[\"'](.*);\s*charset=(.*)(;.*)?[\"'](.*)>/iU";
    $replacements[] = '<meta${1}content="${2}; charset=shift-jis${4}"${5}>';

    $partials = array(
      $this->action->getPartial('global/partsPageTitle', array('title' => $this->action->application->getTitle())),
      $this->action->getPartial('application/renderFooter', array('application' => $this->action->application))
    );

    if ($this->action->getRequest()->getMobile()->isDoCoMo() && opConfig::get('font_size'))
    {
      $pattern_start_tag = '/(<td.*?>)/';
      $replacement_start_tag = '$1<font size="2">';
      $pattern_end_tag = '</td>';
      $replacement_end_tag = '</font></td>';
      $partials = preg_replace($pattern_start_tag, $replacement_start_tag, $partials);
      $partials = str_replace($pattern_end_tag, $replacement_end_tag, $partials);
      foreach ($partials as &$partial)
      {
        $partial = '<font size="2">'.$partial.'</font>';
      }
    }

    $patterns[] = "/<body.*>/iU";
    $replacements[] = '${0}'.$partials[0];

    $patterns[] = "/<\/body>/i";
    $replacements[] = $partials[1].'${0}';

    $urlPatterns = array(
      "/<(a)(.*)href=[\"']([a-zA-Z]+):([a-zA-Z]+)(?:\?(.*))?[\"'](.*)>/iU",
      "/<(form)(.*)action=[\"']([a-zA-Z]+):([a-zA-Z]+)(?:\?(.*))?[\"'](.*)>/iU"
    );
    $body = preg_replace_callback($urlPatterns, array($this, 'rewriteUrl'), $body);

    if ($this->action->getRequest()->getMobile()->isEZweb())
    {
      $body = preg_replace_callback('/\xEE[\xB1-\xB3\xB5-\xB6\xBD-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/',
        array($this, 'convertEZwebEmoji'), $body);
    }
    elseif ($this->action->getRequest()->getMobile()->isSoftBank())
    {
      $body = preg_replace_callback('/\xEE[\x80-\x81\x84-\x85\x88-\x89\x8C-\x8D\x90-\x91\x94][\x80-\xBF]/',
        array($this, 'convertSoftBankEmoji'), $body);
    }

    return OpenPNE_KtaiEmoji::convertEmoji(mb_convert_encoding(preg_replace($patterns, $replacements, $body), 'SJIS-win', 'UTF-8'));
  }

  protected function genUrl($uri)
  {
    $search = array('%aid%', '%mid%', '%uid%');
    $replace = array(
      $this->action->application->id,
      $this->action->memberApplication->id,
      $this->action->getUser()->getMemberId()
    );
    return $this->action->getController()->genUrl(str_replace($search, $replace, $uri));
  }

  protected function rewriteUrl($matches)
  {
    $patterns = $this->rewriteUrlConfig;

    foreach ($patterns as $name => $info)
    {
      if (in_array($matches[4], $info['allow_types']) && in_array($matches[1], $info['allow_tags']))
      {
        $uri = $info['uri'];
        if ($matches[5])
        {
          $uri .= false === strpos($uri, '?') ? '?' : '&';
          $uri .= $matches[5];
        }

        if ('a' === $matches[1])
        {
          if (isset($info['type_param_name']) && $info['type_param_name'])
          {
            $uri .= (false === strpos($uri, '?') ? '?' : '&');
            $uri .= $info['type_param_name'].'='.$matches[4];
          }
          $url = $this->genUrl($uri);
          return '<a'.$matches[2].'href="'.htmlspecialchars($url, ENT_QUOTES, 'UTF-8').'"'.$matches[6].'>';
        }
        else
        {
          $url = $this->genUrl($uri);
          $result = '<form'.$matches[2].'action="'.htmlspecialchars($url, ENT_QUOTES, 'UTF-8').'"'.$matches[6].'>';
          if (isset($info['type_param_name']) && $info['type_param_name'])
          {
            $result .= "\n".'<input type="hidden" name="'.$info['type_param_name'].'" value="'.$matches[4].'">';
          }
          return $result;
        }
      }
    }

    return $matches[0];
  }

  protected function convertEZwebEmoji($matches)
  {
    $unicode = mb_convert_encoding($matches[0], 'UCS2', 'UTF-8');
    return OpenPNE_KtaiEmoji::convertEZwebEmojiToOpenPNEFormat(pack('C*', ord($unicode[0]) + 0x7, ord($unicode[1])));
  }

  protected function convertSoftBankEmoji($matches)
  {
    $unicode = mb_convert_encoding($matches[0], 'UCS2', 'UTF-8');
    $unicode = array(ord($unicode[0]), ord($unicode[1]));
    $bias = array(0x19, 0x40);
    switch($unicode[0])
    {
      case 0xE1 :
        $bias[0] = 0x16;
        break;
      case 0xE2 :
        $bias[0] = 0x15;
        $bias[1] = 0xA0;
        break;
      case 0xE3 :
      case 0xE5 :
        $bias[0] = 0x16;
        $bias[1] = 0xA0;
        break;
      case 0xE4 :
        $bias[0] = 0x17;
        break;
    }

    if ($bias[1] == 0x40)
    {
      if ($unicode[1] >= 0x40)
      {
        $bias[1]++;
      }
    }

    return OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat(pack('C*', $unicode[0] + $bias[0], $unicode[1] + $bias[1]));
  }
}
