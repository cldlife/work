<?php
/**
 * @desc Utils工具类
 */
class Utils {
  
  /**
   * @desc http转换成https
   * @param string $string
   */
  static public function urlToHttps ($string) {
    return str_replace('http://', 'https://', $string);
  } 
  
  /**
   * @desc 加密/解密算法
   * @param string $string
   * @param bool $decrypt ture-解密，false-加密
   * @param string $key 密匙
   * @param int $expiry 过期时间（秒）
   */
  static public function enDecrypt ($string, $decrypt = FALSE, $key = '', $expiry = 0) {
    //动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
    $ckeyLength = 4;
  
    //密匙
    $key = md5($key);
  
    //密匙a, 参与加解密
    $keya = md5(substr($key, 0, 16));
    //密匙b, 用来做数据完整性验证
    $keyb = md5(substr($key, 16, 16));
    //密匙c, 用于变化生成的密文
    $keyc = $ckeyLength ? ($decrypt ? substr($string, 0, $ckeyLength) : substr(md5(microtime()), -$ckeyLength)) : '';
  
    //参与运算的密匙
    $cryptkey = $keya . md5($keya . $keyc);
    $keyLength = strlen($cryptkey);
  
    //明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，
    //解密时通过这个密匙验证数据完整性
    //如果是解密的话，从第$ckeyLength位开始，因为密文前$ckeyLength位保存动态密匙，以保证解密正确
    $string = $decrypt ? base64_decode(substr($string, $ckeyLength)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $stringLength = strlen($string);
  
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
  
    //产生密匙簿
    for ($i = 0; $i <= 255; $i++) {
      $rndkey[$i] = ord($cryptkey[$i % $keyLength]);
    }
  
    //用固定的算法，打乱密匙簿，增加随机性，但不会增加密文的强度
    for ($j = $i = 0; $i < 256; $i++) {
      $j = ($j + $box[$i] + $rndkey[$i]) % 256;
      $tmp = $box[$i];
      $box[$i] = $box[$j];
      $box[$j] = $tmp;
    }
  
    //核心加解密部分
    for ($a = $j = $i = 0; $i < $stringLength; $i++) {
      $a = ($a + 1) % 256;
      $j = ($j + $box[$a]) % 256;
      $tmp = $box[$a];
      $box[$a] = $box[$j];
      $box[$j] = $tmp;
  
      //从密匙簿得出密匙进行异或，再转成字符
      $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
  
    if ($decrypt) {
      //验证数据有效性
      if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
        return substr($result, 26);
      } else {
        return '';
      }
    } else {
      //把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
      //因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
      return $keyc . str_replace('=', '', base64_encode($result));
    }
  }
  
  /**
   * 对提供的数据进行urlsafe的base64编码。
   * @param string $data 待编码的数据，一般为字符串
   * @return string 编码后的字符串
   */
  static public function base64_urlSafeEncode($data)
  {
    $find = array('+', '/');
    $replace = array('-', '_');
    return str_replace($find, $replace, base64_encode($data));
  }
  
  /**
   * 对提供的urlsafe的base64编码的数据进行解码
   * @param string $data 待解码的数据，一般为字符串
   * @return string 解码后的字符串
   */
  static public function base64_urlSafeDecode($str)
  {
    $find = array('-', '_');
    $replace = array('+', '/');
    return base64_decode(str_replace($find, $replace, $str));
  }
  
  /**
   * @desc 马甲账号手机号验证（2开头的马甲账号登录）
   * @param int $mobile
   * @return boolean
   */
  static public function checkVestUserMobile($mobile) {
    if ($mobile) {
      return preg_match("/[1|2][0-9]{10}$/", $mobile);
    }
    return FALSE;
  }
  
  /**
   * @desc 手机号验证 (中国)
   * @param int $mobile
   * @return boolean
   */
  static public function checkMobile($mobile) {
    if ($mobile) {
      return preg_match("/1[0-9]{10}$/", $mobile);
    }
    return FALSE;
  }
  
  /**
   * @desc 隐藏手机号中间4位
   * @param int $mobile
   * @return int
   */
  static public function hideMobileFourNumber($mobile) {
    if ($mobile) {
      return preg_replace("/([0-9]{3})([0-9]{4})([0-9]{4})/", "$1****$3", $mobile);
    }
    return 0;
  }

  /**
   * @desc 根据用户id生成目录算法
   * @param int $uid
   * @return path (0/0)
   */
  static public function getAvatarPath ($uid) {
    $path = '';
    if (is_numeric($uid) && $uid > 0) {
      $firstFactor = 2000 * 2000;
      $first = intval(ceil($uid / $firstFactor));
      $secondBase = $uid - $first * $firstFactor;
      $secondFactor = 2000;
      $second = abs(ceil($secondBase / $secondFactor));
      
      //$second = abs(ceil($uid / ($secondFactor * $first)));
      
      $path = $first . "/" . $second;
    }
    return $path;
  }
  
  /**
   * @desc Long类型id的生成实现类：server id（4位十进制数） + 随机数2位十进制数） + 时间戳（遗留13位十进制数）
   * @see 注：server id的限制是（0~9222）==> 由于long最大值的限制 (为0时, 随机生成)
   * @see 注：Long的最大值：9223372036854775807
   */
  static public function longIdGenerator ($serverId = 1) {
  	$maxServerId = 9222;
  	if (!$serverId) $serverId = mt_rand(1, $maxServerId);
  	if ($serverId > $maxServerId) $serverId = 1;
  	$randomId = mt_rand(0, 99);
  	list($usec, $sec) = explode(" ", microtime());
  	$currentMicroTime = $sec . intval(round($usec * 1000));
  	return $serverId . $randomId . $currentMicroTime;
  }
  
  /**
   * @desc 按时间顺序生成Long类型id
   * @see ymd（6位十进制数） + 秒数（5位十进制数） + 微妙数（6位十进制数），如: 16062276359326108
   */
  static public function longIdOnTimeGenerator () {
    list($mtUsec, $mtsec) = explode(" ", microtime());
    $usec = intval(round($mtUsec * 1000000));
    $sec = time() - strtotime(date('Ymd'));
    return date('ymd') . $sec . str_pad($usec, 6, 0);
  }
  
  /**
   * @desc 获取毫秒级别的时间戳 (14位十进制数)
   * @see 如: 14667494408246
   */
  static public function getMillisecond () {
    list($usec, $sec) = explode(" ", microtime());
    $usec = intval(round($usec * 10000));
    return $sec . str_pad($usec, 4, 0);
  }
  
  /**
   * @desc 获取随机小数($min-$max之间)
   */
  static public function getRandom ($min = 0, $max = 1) {
    return $min + mt_rand() / mt_getrandmax() * ($max - $min);
  }

  /**
   * @desc 获取文件名后缀
   */
  static public function getFileExt ($filename) {
    if (!$filename || strpos($filename, ".") === false) {
      return "";
    }
    $str = substr($filename, strrpos($filename, ".") + 1);
    return preg_replace("/\?.*/i", '', strtolower($str));
  }
  
  /**
   * @desc 获取文件名
   */
  static public function getFileName ($file) {
    $fileName = '';
    if ($file) {
      $expFile = explode('/', $file);
      $fileName = $expFile[count($expFile) - 1];
      $fileName = preg_replace("/\?.*$/Ui", "", $fileName);
    }
    return $fileName;
  }

  /**
   * 获得相差时间 (新闻时间精准到1天内)
   * @param datetime $startTime
   * @param datetime $toTime
   */
  static public function getDiffTime ($fromTime, $toTime = "") {
    $toTime = $toTime ? $toTime : time();
    $difftime = $toTime - $fromTime;
    if ($difftime <= 0) {
      $res = "刚刚";
    } elseif ($difftime > 0 && $difftime < 60) {
      $res = $difftime . "秒前";
    } else if ($difftime >= 60 && $difftime < 3600) {
      $minute = ceil($difftime / 60);
      $res = $minute . "分钟前";
    } else if ($difftime >= 3600 && $difftime < 24 * 3600) {
      $hour = ceil($difftime / 3600);
      $res = $hour . "小时前";
    } else if ($difftime >= 3600 * 24 && $difftime < date('t') * 24 * 3600) {
      $res = date("m-d H:i", $fromTime);
    } elseif ($difftime >= 3600 * 24 * date('t') && $difftime <  365 * date('t') * 24 * 3600) {
      $res = date("m-d H:i", $fromTime);
    } else {
      $res = date("Y-m-d H:i", $fromTime);
    }
    return $res;
  }
  
  /**
   * 剩余时间计算
   * @param int $startTime
   * @param int $toTime
   */
  static public function getRemainTime ($fromTime = 0 , $toTime = 0) {
    $fromTime = $fromTime ? $fromTime : time();
    $difftime = $toTime - $fromTime;
    if ($difftime <= 0) {
      $res = "已过期";
    } else if ($difftime > 0 && $difftime < 60) {
      $res = ($difftime - 1) . "秒";
    } else if ($difftime >= 60 && $difftime < 3600) {
      $minute = ceil($difftime / 60) - 1;
      $res = $minute . "分钟";
    } else if ($difftime >= 3600 && $difftime < 24 * 3600) {
      $hour = ceil($difftime / 3600) - 1;
      $res = $hour . "小时";
    } else if ($difftime >= 3600 * 24) {
      $day = ceil($difftime / (3600 * 24)) - 1;
      $res = $day . "天";
    }
    
    return $res;
  }

  /**
   * 获得json的键值
   * @param string $key
   * @param int $idx
   */
  static public function getJsonVal ($jsonstr, $key, $idx = 0) {
    if (!$jsonstr || !$key) {
      return "";
    }
    $reg = "/\"{$key}\"\:\"?([0-9a-zA-Z]*)\"?/i";
    $nums = preg_match_all($reg, $jsonstr, $matches);
    if ($nums > 0) {
      return (string) ($matches[1][$idx]);
    }
    return "";
  }

  /**
   * 对数组,按照制定的key排序
   * @param array $arrList
   * @param string $key
   * @param bool $small
   */
  static public function sortArrayByKey ($arrList, $key, $smallToBig = true) {
    if (is_array($arrList) == false || !$key) {
      return $arrList;
    }
    $i = 0;
    $arr = array();
    foreach ($arrList as $k => $item) {
      $arr[$i] = $item;
      $i++;
    }
    if ($smallToBig == true) { //从小到大
      $i = 0;
      $minval = 0; //获得第一个
      $minpos = 0;
      for ($i = 0; $i < count($arr); $i++) {
        $minval = $arr[$i][$key]; //得到最小
        $minpos = $i;
        for ($j = $i + 1; $j < count($arr); $j++) {
          if ($minval > $arr[$j][$key]) {
            $minval = $arr[$j][$key];
            $minpos = $j;
          }
        }
        //交换值
        if ($minpos > $i) {
          $tempItem = $arr[$i];
          $arr[$i] = $arr[$minpos];
          $arr[$minpos] = $tempItem;
        }
      }
    } else { //从大到小
      $i = 0;
      $maxval = 0; //获得第一个
      $maxpos = 0;
      for ($i = 0; $i < count($arr); $i++) {
        $maxval = $arr[$i][$key]; //得到最大
        $maxpos = $i;
        for ($j = $i + 1; $j < count($arr); $j++) {
          if ($maxval < $arr[$j][$key]) {
            $maxval = $arr[$j][$key];
            $maxpos = $j;
          }
        }
        //交换值
        if ($maxpos > $i) {
          $tempItem = $arr[$i];
          $arr[$i] = $arr[$maxpos];
          $arr[$maxpos] = $tempItem;
        }
      }
    }
    return $arr;
  }

  static public function prepareJSON ($input) {
    //This will convert ASCII/ISO-8859-1 to UTF-8.
    //Be careful with the third parameter (encoding detect list), because
    //if set wrong, some input encodings will get garbled (including UTF-8!)
    $imput = mb_convert_encoding($input, 'UTF-8', 'ASCII,UTF-8,ISO-8859-1');
    //Remove UTF-8 BOM if present, json_decode() does not like it.
    if (substr($input, 0, 3) == pack("CCC", 0xEF, 0xBB, 0xBF)) $input = substr($input, 3);
    return $input;
  }

  static public function thumbImage ($srcFile, $dstFile, $dstW, $dstH, $quality = 100) {
    if (!$dstW && !$dstH) {
      return FALSE;
    }
    
    //原图不存在
    if (!file_exists($srcFile)) {
      return FALSE;
    }
    
    //缩略图已存在
    if (file_exists($dstFile)) {
      $im =  imagecreatefromjpeg($dstFile);
      return $im;
    }
    
    //原图 im
    list($width, $height, $type) = @GetImageSize($srcFile);
    switch ($type) {
      case 1:
        $im = @ImageCreateFromGIF($srcFile);
        break;
      case 3:
        $im = @ImageCreateFromPNG($srcFile);
        break;
      case 2:
      default:
        $im = @imagecreatefromjpeg($srcFile);
        break;
    }
    
    //压缩方式(0-自动适应压缩, 1-按width等比压缩, 2-按height等比压缩, 3-正方图)
    $flag = 0;
    $dstWidth = $dstW;
    $dstHeight = $dstH;
    if ($dstW && !$dstH) {
      $flag = 1;
    } elseif (!$dstW && $dstH) {
      $flag = 2;
    } elseif ($dstW == $dstH) {
      $flag = 3;
    }
    switch ($flag) {
      case 1:
        if ($width <= $dstW) {
          $dstWidth = $width;
          $dstHeight = $height;
        } else {
          $dstHeight = ceil($height * ($dstW / $width));
        }
        break;
      case 2:
        if ($height <= $dstH) {
          $dstWidth = $width;
          $dstHeight = $height;
        } else {
          $dstWidth = ceil($width * ($dstH / $height));
        }
        break;
      case 3:
        if ($width < $height) {
          $dstHeight = ceil($height * ($dstW / $width));
        } else {
          $dstWidth = ceil($width * ($dstH / $height));
        }
        break;
    }
     
    if ($flag == 3) {
      //方图
      if (function_exists('ImageCreateTrueColor')) {
        $dstIm = ImageCreateTrueColor($dstW, $dstH);
      } else {
        $dstIm = ImageCreate($dstW, $dstH);
      }
      $white = @ImageColorAllocate($dstIm, 255, 255, 255);
      @ImageFilledRectAngle($dstIm, 0, 0, $dstW, $dstH, $white);
      $src_x_offset = ceil(($dstW - $dstWidth) / 2);
      $src_y_offset = ceil(($dstH - $dstHeight) / 2);
      @ImageCopyResampled($dstIm, $im, $src_x_offset, $src_y_offset, 0, 0, $dstWidth, $dstHeight, $width, $height);
    } else {
      if (function_exists('ImageCreateTrueColor')) {
        $dstIm = ImageCreateTrueColor($dstWidth, $dstHeight);
      } else {
        $dstIm = ImageCreate($dstWidth, $dstHeight);
      }
      @ImageCopyResampled($dstIm, $im, 0, 0, 0, 0, $dstWidth, $dstHeight, $width, $height);
    }
    
    //按质量比生成thumb图片
    $quality = intval($quality) ? intval($quality) : 100;
    switch ($type) {
      case 1:
        @imagejpeg($dstIm, $dstFile, $quality);
        break;
      case 3:
        @imagepng($dstIm, $dstFile);
        break;
      case 2:
      default:
        @imagejpeg($dstIm, $dstFile, $quality);
        break;
    }
    
    return $dstIm;
  }
  
  //写日志
  static public function log ($msg, $flag = '', $path = '') {
    if ($flag) $flag = $flag . '_';
    if ($path) {
      @mkdir($path, 0777, true);
    } else {
      $path = APP_LOG_DIR;
    }
    $msg = date(DATE_FORMAT) . " :: " . $msg . "\n";
    $logFile = $path . "/" . $flag . date("Ymd") . ".log";
    error_log($msg, 3, $logFile);
  }
  
  //写临时文件
  static public function tmpdata ($data, $flag = '', $path = '') {
    if ($flag) { 
      if ($path) {
        @mkdir($path, 0777, true);
        $path .= '/';
      } else {
        $path = APP_TMP_DIR;
      }
      $dataFile = $path . $flag . ".dat";
      error_log($data . "\n", 3, $dataFile);
    }
  }
  
  //读取临时文件
  static public function readTmpdata ($flag, $path = '') {
    if ($flag) {
      if ($path) {
        $path .= '/';
      } else {
        $path = APP_TMP_DIR;
      }
      $dataFile = $path . $flag . ".dat";
      if (file_exists($dataFile)) return file_get_contents($dataFile);
    }
  }
  
  //清除临时文件
  static public function cleanTmpdata ($flag, $path = '') {
    if ($flag) {
      if ($path) {
        $path .= '/';
      } else {
        $path = APP_TMP_DIR;
      }
      $dataFile = $path . $flag . ".dat";
      if (file_exists($dataFile)) return unlink($dataFile);
    }
  }
  
  //读取存储数据文件
  static public function readStorageData ($fileName) {
    if ($fileName) {
      $dataFile = APP_STORAGE_DIR . '/data/' . $fileName . ".dat";
      if (file_exists($dataFile)) return file_get_contents($dataFile);
    }
  }
  
  //写存储数据文件
  static public function writeStorageData ($fileName, $data) {
    if ($fileName) {
      $dataFile = APP_STORAGE_DIR . '/data/' . $fileName . ".dat";
      return file_put_contents($dataFile, $data);
    }
  }

  /**
   * @desc 编码转换
   */
  static public function gbkToUtf8 ($param) {
    if (is_object($param) || is_array($param)) {
      foreach ($param as $k => $v) {
        $value[$k] = self::gbkToUtf8($v);
      }
      return $value;
    }
    return iconv('GBK', 'UTF-8//IGNORE', $param);
  }

  static public function utf8ToGbk ($param) {
    if (is_object($param) || is_array($param)) {
      foreach ($param as $k => $v) {
        $value[$k] = self::utf8ToGbk($v);
      }
      return $value;
    }
    return iconv('UTF-8', 'GBK//IGNORE', $param);
  }
  
  /**
   * @desc 城市选择下拉菜单控件
   */
  static public function citySelection ($vaules = array(), $activeDistrict = true, $UIClass = '') {
    $districtSelectHtml = $districtExcuteJs = '';
    $selectClass = $UIClass ? " class='{$UIClass}'" : '';
    if ($activeDistrict) {
      $districtSelectHtml =  "<select name='district_id' id='district_id'{$selectClass}><option value='0'>请选择</option></select>"; 
      $districtExcuteJs = <<<EOT
      $('#city_id').bind('change', function() {
        var cityId = $(this).find("option:selected").val();
      
        $.getJSON('/util/Regions', {id:cityId}, function(response) {
          var cities = [];
          if (response.code == 1) {
      	  	cities.push('<option value="0">请选择</option>');
            $.each(response.data, function(i, item) {
      				cities.push('<option value="'+item.id+'" '+("{$vaules[2]}" == item.id ? "selected='selected'" : "")+'>'+item.name+'</option>');
            })
            $('#district_id').html(cities.join(""));
          }
        })
      })
EOT;
    }
    
    $selectHtml = <<<EOT
    <select name="province_id" id="province_id"{$selectClass}><option value="0">请选择</option></select>
    <select name="city_id" id="city_id"{$selectClass}><option value="0">请选择</option></select>
    {$districtSelectHtml}
EOT;
    $excuteJs = <<<EOT
    <script type="text/javascript">
    $.getJSON('/util/provinces', function(response) {
      var provinces = [];
      if (response.code == 1) {
    		provinces.push('<option value="0">请选择</option>');
        $.each(response.data, function(i, item) {
    	  	provinces.push('<option value="'+item.id+'" '+("{$vaules[0]}" == item.id ? "selected='selected'" : "")+' >'+item.name+'</option>');
        })
        $('#province_id').html(provinces.join(""));
        if ('{$vaules[0]}') $('#province_id').change();
      }
    })
    
    $('#province_id').bind('change', function() {
      var provinceId = $(this).find("option:selected").val();
    
      $.getJSON('/util/Regions', {id:provinceId}, function(response) {
        var cities = [];
        if (response.code == 1) {
    	  	cities.push('<option value="0">请选择</option>');
          $.each(response.data, function(i, item) {
    				cities.push('<option value="'+item.id+'" '+("{$vaules[1]}" == item.id ? "selected='selected'" : "")+'>'+item.name+'</option>');
          })
          $('#city_id').html(cities.join(""));
          if ('{$vaules[1]}' && '{$activeDistrict}') $('#city_id').change();
        }
      })
    })
    
    {$districtExcuteJs}
    </script>
EOT;
    echo $selectHtml . $excuteJs;
  }
  
   /**
   * @desc 生日选择下拉菜单控件
   */
  static public function birthSelection ($vaules = array(), $UIClass = '') {
    $yearOptions = $monthOptions = $dayOptions = '<option value="0">请选择</option>';
    for ($i=date('Y');$i>=1900;$i--) {
      $yearOptions .= "<option value='{$i}' ".($i == $vaules[0] ? 'selected="selected"' : '').">{$i}</option>";
    }
    for ($i=1;$i<=12;$i++) {
      $monthOptions .= "<option value='{$i}' ".($i == $vaules[1] ? 'selected="selected"' : '').">".($i < 10 ? '0' . $i : $i)."</option>";
    }
    for ($i=1;$i<=date('t');$i++) {
      $dayOptions .= "<option value='{$i}' ".($i == $vaules[2] ? 'selected="selected"' : '').">".($i < 10 ? '0' . $i : $i)."</option>";
    }
    
    echo <<<EOT
    <select name="birth_year" id="birth_year" class="{$UIClass}">{$yearOptions}</select>
    <select name="birth_month" id="birth_month" class="{$UIClass}">{$monthOptions}</select>
    <select name="birth_day" id="birth_day" class="{$UIClass}">{$dayOptions}</select>
EOT;
  }
  
  //转义字符串
  static function replaceSlashes ($string) {
    return str_replace(array('/', '.', '?'), array('\/', '\.', '\?'), $string);
  }
  
  //截取字串
  static function cutstr($string, $length, $dot = '', $charset = 'utf-8') {
    $defaultCharset = 'utf-8';
    $charset = strtolower($charset) == $defaultCharset ? $defaultCharset : strtolower($charset);
    
    $string = strip_tags($string);
    if (strlen($string) < $length) {
      return $string;
    }
      
  	$strcut = '';
  	$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>' ), $string);
  	if ($charset == $defaultCharset) {
      $n = $tn = $noc = 0;
      while($n < strlen($string)) {
      	$t = ord($string[$n]);
      	if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
          $tn = 1;
          $n ++;
          $noc ++;
      	} elseif (194 <= $t && $t <= 223) {
          $tn = 2;
          $n += 2;
          $noc += 2;
      	} elseif (224 <= $t && $t < 239) {
          $tn = 3;
          $n += 3;
          $noc += 2;
      	} elseif (240 <= $t && $t <= 247) {
          $tn = 4;
          $n += 4;
          $noc += 2;
      	} elseif (248 <= $t && $t <= 251) {
          $tn = 5;
          $n += 5;
          $noc += 2;
      	} elseif ($t == 252 || $t == 253) {
          $tn = 6;
          $n += 6;
          $noc += 2;
      	} else {
          $n ++;
      	}
      	
      	if ($noc >= $length) break;
      }
      
      if ($noc > $length) $n -= $tn;
      $strcut = substr($string, 0, $n);
  	} else {
      for($i = 0; $i < $length - strlen($dot) - 1; $i ++) {
      	$strcut .= ord ($string[$i]) > 127 ? $string[$i] . $string[++ $i] : $string[$i];
      }
  	}
  
  	$strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
  	return $strcut . (mb_strlen($strcut, $charset) > $length/2 ? $dot : '');//TODO
  }
  
  //获取带有参数的URL
  static public function getUrlWithParams ($url, $params = array()) {
    $paramsString = '';
    if ($params) {
      $s = stripos($url, '?') !== FALSE ? '&' : '?';
      foreach ($params as $k => $param) {
        if ($paramsString) {
          $paramsString .= "&{$k}={$param}";
        } else {
          $paramsString .= "{$k}={$param}";
        }
      }
    }
    
    return $url . $s . $paramsString;
  }
  
  //手机浏览器判断
  static public function isFromMobile () {
    $regex_match="/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|";
    $regex_match.="htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|";
    $regex_match.="blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";
    $regex_match.="symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|";
    $regex_match.="jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220";
    $regex_match.=")/i";
    $isMobile = (isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE']) or preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT'])));
    return $isMobile;
  }

  //判断是否iPhone
  static public function isFromIphone () {
    $regex_match="/(iphone)/i";
    return (isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE']) or preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT'])));
  }

  //判断是否android
  static public function isFromAndroid () {
    $regex_match="/(android)/i";
    return (isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE']) or preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT'])));
  }

  static public function isFromPad () {
    $regex_match="/(ipad)/i";
    $isMobile = (isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE']) or preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT'])));
    return $isMobile;
  }

  //手机weixin浏览器判断
  static public function isFromWeixin () {
    $regex_match="/(MicroMessenger)/i";
    $isFromWeixin = (isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE']) or preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT'])));
    return $isFromWeixin;
  }

  //手机weibo浏览器判断
  static public function isFromWeibo () {
    $regex_match="/(Weibo)/i";
    $isFromWeibo = (isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE']) or preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT'])));
    return $isFromWeibo;
  }

  //手机QQ浏览器判断
  static public function isFromQQ () {
    $regex_match="/(QQ)|(Qzone)/i";
    $isFromQQ = (isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE']) or preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT'])));
    return $isFromQQ;
  }

  //ShiHuo APP 浏览器判断
  static public function isFromShihuo () {
    //获取头信息
    $headers = HttpClient::getAllHeaders();
    $fromapp = $headers['fromapp'];

    //验证是否来自app
    $isFromShihuo = FALSE;
    if (stripos($fromapp, 'ShiHuo') !== FALSE) $isFromShihuo = TRUE;
    return $isFromShihuo;
  }
  
  //WanZhu APP 浏览器判断
  static public function isFromWanZhu () {
    //获取头信息
    $headers = HttpClient::getAllHeaders();
    $fromapp = $headers['fromapp'];
  
    //验证是否来自app
    $isFromWanZhu = FALSE;
    if (stripos($fromapp, 'WanZhu') !== FALSE) $isFromWanZhu = TRUE;
    return $isFromWanZhu;
  }
  
  /**
   * @desc gzip解压
   * @example
   * 页面gzip压缩检测(31139则数据已进行压缩, 并进行解压操作)
   * $unpackInfo = @unpack("C2chars", $content);
   * $unpackCode = intval($unpackInfo['chars1'].$unpackInfo['chars2']);
   * if ($unpackCode == 31139) $content = gzdecode($content);
   */
  static public function gzdecode ($data) {    
    if (function_exists('gzdecode')) gzdecode($data);
    
    $flags = ord(substr($data, 3, 1));      
    $headerlen = 10;      
    $extralen = 0;      
    $filenamelen = 0;      
    if ($flags & 4) {      
      $extralen = unpack('v' ,substr($data, 10, 2));      
      $extralen = $extralen[1];      
      $headerlen += 2 + $extralen;      
    }
    
    if ($flags & 8) // Filename      
      $headerlen = strpos($data, chr(0), $headerlen) + 1;
      
    if ($flags & 16) // Comment
      $headerlen = strpos($data, chr(0), $headerlen) + 1;
      
    if ($flags & 2) // CRC at end of file
      $headerlen += 2; 
      
    $unpacked = @gzinflate(substr($data, $headerlen));      
    if ($unpacked === FALSE)      
      $unpacked = $data;
      
    return $unpacked;      
  }
  
  //正则格式化
  static public function getRegRule ($string) {
    if (!$string) return $string;
    
    return str_replace(array(
      '|', '/', '.', '?', '*', '[', ']', "'", '"', '(', ')' 
    ), array(
      "\|", "\/", "\.", "\?", "\*", "\[", "\]", '\'', '\"', '\(', '\)'
    ), $string);
  }
  
  //解析json数据以支持js-josn格式
  static public function parseJsonStringForJs ($jsonString) {
    return str_replace(array(
      '"', "'"
    ), array(
      '\"',"\'"
    ), $jsonString);
  }
  
  //解码js-json数据
  static public function jsJsonStringDecode ($jsJsonString) {
    $res = array();
    
    //解析基本格式
    $repString = str_replace(array('{', '}', '"', "'", ' '), '', $jsJsonString);
    $repString = str_replace('http://', 'http//', $repString);
    $expString = explode(",", $repString);
    if ($expString) {
      foreach ($expString as $kv) {
        if (!$kv) continue;
        
        //解析k=>v对
        $expKv = explode(":", $kv);
        if (!$expKv[0] || !$expKv[1]) continue;
        $res[$expKv[0]] = str_replace('http//', 'http://', $expKv[1]);
      }
    }
    
    return $res;
  }
  
  /**
   * @desc 动态生成CSRF安全密匙
   * (TODO 过期时间未设置)
   */
  const SIG_KEY = 'QW_ShiHuo_Sig_Key_v1.0.0';
  static public function generateCSRFSecret ($key) {
    return md5($key . self::SIG_KEY) ;
  }
  
  /**
   * @desc 字符串安全过滤(防止SQL注入)
   */
  static public function filterString ($string) {
    if ($string) {
      $string = trim(strip_tags($string));
      $string = str_replace(array("'", '"', "\\"), '', $string);
    }
    return $string;
  }
  
  /**
   * @desc 从list中获取第1个和最后一个 created_time（时间戳）
   */
  static public function getFirstAndEndTime (Array $list) {
    $times = array();
    $times['start_time'] = 0;
    $times['end_time'] = 0;
    if ($list) {
      $listCount = count($list);
      $times['start_time'] = $list[0]['created_time'];
      $times['end_time'] = $list[$listCount-1]['created_time'];
    }
    return $times;
  }
  
  /**
   * @desc 指定$arrays中的key，去重
   */
  static public function uniqueArraysByKey (Array $arrays, $key) {
    $list = array();
    if ($arrays && $key) {
      $tmpKeys = array();
      foreach ($arrays as $item) {
        if (!in_array($item[$key], $tmpKeys)) {
          $tmpKeys[] = $item[$key];
          $list[] = $item;
        }
      }
    }
    return $list;
  }
  
  /**
   * @desc 计算本月之前所有的天数
   * @param int $startTime 本月之前截止的时间戳
   */
  static public function calcDaysBeforeThisMonth ($startTime = 0) {
    $days = 0;
    if ($startTime) {
      $perYearMonths = 12;
  
      //当前年月，和本月之前截止的年月日
      $curYear = date('Y');
      $curMonth = date('m');
      $curDay = date('d');
      $beforeEndYear = date('Y', $startTime);
      $beforeEndMonth = date('m', $startTime);
      $beforeEndDay = date('d', $startTime);
  
      for ($y = $beforeEndYear; $y <= $curYear; $y ++) {
        $isEqualYear = $y == $curYear ? TRUE : FALSE;
        $startMonth = intval($isEqualYear ? $beforeEndMonth : 1);
        $endMonth = intval($isEqualYear ? $curMonth - 1 : $perYearMonths + 1);
        
        for ($m = $startMonth; $m <= $endMonth; $m ++) {
          $days += date('t', mktime(0, 0, 0, $m, 1, $y));
        }
        if ($isEqualYear) break;
      }
      if ($days >= $beforeEndDay) $days = $days - $beforeEndDay;
    }
    return $days;
  }
  
  /**
   * @desc 验证Url是否是本地Url(非http://或https://开头)
   */
  static public function isLocalUrl ($url) {
    return stripos($url, 'http://') === FALSE && stripos($url, 'https://') === FALSE;
  }
  
  /**
   * @desc 获取主域名
   */
  static public function getHostDomainName () {
    $domain = $_SERVER['SERVER_NAME'];
    $domainExp = explode('.', $domain);
    $domainExpLen = count($domainExp);
    $domain = $domainExp[$domainExpLen - 2] . '.' . $domainExp[$domainExpLen - 1];
    return $domain;
  }
  
  /**
   * @desc 根据日期获取年龄
   */
  static public function getAgeFromDate ($date) {
    $age = 0;
    if ($date) {
      $ageTime = time() - strtotime($date);
      $age = ceil($ageTime/86400/365);
    }
    return $age;
  }
  
  /**
   * @desc Thing帖子链接
   */
  static public function getThingThreadLink ($tid) {
    return WEB_QW_APP_M_DOMAIN . "/t/{$tid}.html";
  }
}
