<?php
/**
 * 趣米接口类
 */
final class Qumi {

  //APP ID
  const QUMI_APP_ID = '7167d41a652fc292';

  //APP SECRET
  const QUMI_APP_SECRET = '4d5aafa11f28fc28';

  //QUMI CALLBACK URL
  const QUMI_CALLBACK_URL = 'http://thirdapp.shihuo.me/qumi/callback.html';

  /**
   * @desc 获取趣米积分墙
   * @param int uid
   * @param string nickname (optional)
   * @param string appid (optional)
   * @param string appsecret (optional)
   * @return string wall url
   */
  public function getPointsWallUrl ($uid, $nickname = '', $appid = '', $appsecret = '') {
    $appId = $appid ? $appid : self::QUMI_APP_ID;
    $appSecret = $appsecret ? $appsecret : self::QUMI_APP_SECRET;
    //构建打开微墙需要的字符串
    $str = "app_id={$appid}&openid={$uid}&wx=true&nickname={$nickname}"; 
    $cryptedStr = self::AesEncrypt($str, $appSecret);
    //经过测试会自动缓存,建议您这边设置此参数清空缓存
    $flushCache = time();
    return "http://wx.mob.qumi.com/wallsimple/webwall/adListwx?r=" . urlencode($cryptedStr) . "&appcode={$appId}&appSecretkey={$appSecret}&fc={$flushCache}";
  }

  /**
   * This was AES-128 / CBC / NoPadding encrypted.
   * @param string text to be crypted
   * @param string $key
   * @return base64_encode string
   */
  private static function AesEncrypt ($plaintext, $key = null) {
    $plaintext = trim($plaintext);
    if ($plaintext && extension_loaded('mcrypt')) {
      $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
      $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
      $key = mb_substr($key, 0, mcrypt_enc_get_key_size($module));
      $iv = '0102030405060708';
      /* Intialize encryption */
      mcrypt_generic_init($module, $key, $iv);
      /* Encrypt data */
      $encrypted = mcrypt_generic($module, $plaintext);
      /* Terminate encryption handler */
      mcrypt_generic_deinit($module);
      mcrypt_module_close($module);
      return base64_encode($encrypted);
    }
    return '';
  }

  /**
   * This was AES-128 / CBC / NoPadding decrypted.
   * @param string encrypted str
   * @param string key(optional)
   * @return string
   */
  private static function AesDecrypt ($encrypted, $key = null) {
    if ($encrypted && extension_loaded('mcrypt')) {
      $ciphertext_dec = base64_decode($encrypted);
      $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
      $key = mb_substr($key, 0, mcrypt_enc_get_key_size($module));
      //$iv = substr(md5($key),0,mcrypt_enc_get_iv_size($module));
      $iv = '0102030405060708';
      /* Initialize encryption module for decryption */
      mcrypt_generic_init($module, $key, $iv);
      /* Decrypt encrypted string */
      $decrypted = mdecrypt_generic($module, $ciphertext_dec);
      /* Terminate decryption handle and close module */
      mcrypt_generic_deinit($module);
      mcrypt_module_close($module);
      return rtrim($decrypted, "\0");
    }
    return '';
  }
}

