<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opShindigBlobCrypter
 *
 * @package    opOpenSocialPlugin
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 * @see        BasicBlobCrypter
 */
class opShindigBlobCrypter extends BasicBlobCrypter
{
  /**
   * @see BasicBlobCrypter::warp()
   */
  public function wrap(Array $in)
  {
    $encoded = $this->serializeAndTimestamp($in);
    if ($this->allowPlaintextToken)
    {
      $cipherText = base64_encode($encoded);
    }
    else
    {
      $cipherText = opShindigCrypto::encrypt($this->cipherKey, $encoded);
    }
    $hmac = Crypto::hmacSha1($this->hmacKey, $cipherText);
    $b64 = base64_encode($cipherText . $hmac);
    return $b64;
  }

  /**
   * @see BasicBlobCrypter::unwrap();
   */
  public function unwrap($in, $maxAgeSec) {
    if ($this->allowPlaintextToken && count(explode(':', $in)) == 7)
    {
      $data = $this->parseToken($in);
      $out = array();
      $out['o'] = $data[0];
      $out['v'] = $data[1];
      $out['a'] = $data[2];
      $out['d'] = $data[3];
      $out['u'] = $data[4];
      $out['m'] = $data[5];
    }
    else
    {
      $bin = base64_decode($in);
      if (is_callable('mb_substr'))
      {
        $cipherText = mb_substr($bin, 0, - Crypto::$HMAC_SHA1_LEN, 'latin1');
        $hmac = mb_substr($bin, mb_strlen($cipherText, 'latin1'), Crypto::$HMAC_SHA1_LEN, 'latin1');
      }
      else
      {
        $cipherText = substr($bin, 0, - Crypto::$HMAC_SHA1_LEN);
        $hmac = substr($bin, strlen($cipherText));
      }
      Crypto::hmacSha1Verify($this->hmacKey, $cipherText, $hmac);
      $plain = base64_decode($cipherText);
      if ($this->allowPlaintextToken)
      {
        $plain = base64_decode($cipherText);
      }
      else
      {
        $plain = opShindigCrypto::decrypt($this->cipherKey, $cipherText);
      }
      $out = $this->deserialize($plain);
      $this->checkTimestamp($out, $maxAgeSec);
    }
    return $out;
  }
}
