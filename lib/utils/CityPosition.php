<?php
class CityPosition {

  public static function getCityNameByIP ($ip) {
    $cityName = '';
    $position = CityPosition::convertip($ip);
    $position = Utils::gbkToUtf8($position);
    preg_match("/省(.*?)市/", $position, $matches);
    if ($matches[1]) $cityName = $matches[1];
    return $cityName;
  }

  //根据地址$address获取info
  public static function getInfoByAddress ($address) {
    $addressInfo = array();
    $matches = array();
    
    //匹配 - 省.市.市
    preg_match("/(中国)?(.*省)?(.*?)市(.*?)市(.*?)(区|县|镇)(.*)?/", $address, $matches);
    if ($matches) {
      $addressInfo[0] = trim(str_replace('省', '', $matches[2]));
      $addressInfo[1] = trim($matches[3]);
      $addressInfo[2] = trim($matches[4]) . '市';
      $addressInfo[3] = trim($matches[5] . $matches[6]);
      $addressInfo[4] = trim($matches[7]);
      
    //匹配 - 省.市
    } else {
      preg_match("/(中国)?(.*省)?(.*?)(市|镇)(.*?)(区|县|镇)(.*)?/", $address, $matches);
      if ($matches) {
        $addressInfo[0] = trim(str_replace('省', '', $matches[2]));
        $addressInfo[1] = trim(str_replace('市', '', $matches[3] . $matches[4]));
        $addressInfo[2] = trim($matches[5] . $matches[6]);
        $addressInfo[3] = trim($matches[7]);
      }
    }
    
    return $addressInfo;
  }
  
  //根据地址$address获取 省-市-区
  public static function getPCDByAddress ($address) {
    $PCD = '';
    if ($address) {
      $addressInfo = self::getInfoByAddress($address);
      $P = $addressInfo[0] . " ";
      $C = $addressInfo[1] . " ";
      $D = $addressInfo[2];
      $PCD = $P . $C . $D;
    }
    
    return $PCD;
  }
  
  private static function convertip ($ip) {
    $position = '';
    if (preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip)) {
      $iparray = explode('.', $ip);
      if ($iparray[0] == 10 || $iparray[0] == 127 || ($iparray[0] == 192 && $iparray[1] == 168) || ($iparray[0] == 172 && ($iparray[1] >= 16 && $iparray[1] <= 31))) {
        $position = '- LAN';
      } elseif ($iparray[0] > 255 || $iparray[1] > 255 || $iparray[2] > 255 || $iparray[3] > 255) {
        $position = '- Invalid IP Address';
      } else {
        $ipDataFile = dirname(__FILE__) . '/data/ip.dat';
        if (@file_exists($ipDataFile)) {
          $position = CityPosition::convertip_full($ip, $ipDataFile);
        }
      }
    }
    return $position;
  }

  private static function convertip_full ($ip, $ipDataFile) {
    if (!$fd = @fopen($ipDataFile, 'rb')) {
      return '- Invalid IP data file';
    }
    $ip = explode('.', $ip);
    $ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];
    if (!($DataBegin = fread($fd, 4)) || !($DataEnd = fread($fd, 4)))
      return;
    @$ipbegin = implode('', unpack('L', $DataBegin));
    if ($ipbegin < 0)
      $ipbegin += pow(2, 32);
    @$ipend = implode('', unpack('L', $DataEnd));
    if ($ipend < 0)
      $ipend += pow(2, 32);
    $ipAllNum = ($ipend - $ipbegin) / 7 + 1;
    $BeginNum = $ip2num = $ip1num = 0;
    $ipAddr1 = $ipAddr2 = '';
    $EndNum = $ipAllNum;
    while ($ip1num > $ipNum || $ip2num < $ipNum) {
      $Middle = intval(($EndNum + $BeginNum) / 2);
      fseek($fd, $ipbegin + 7 * $Middle);
      $ipData1 = fread($fd, 4);
      if (strlen($ipData1) < 4) {
        fclose($fd);
        return '- System Error';
      }
      $ip1num = implode('', unpack('L', $ipData1));
      if ($ip1num < 0)
        $ip1num += pow(2, 32);
      if ($ip1num > $ipNum) {
        $EndNum = $Middle;
        continue;
      }
      $DataSeek = fread($fd, 3);
      if (strlen($DataSeek) < 3) {
        fclose($fd);
        return '- System Error';
      }
      $DataSeek = implode('', unpack('L', $DataSeek . chr(0)));
      fseek($fd, $DataSeek);
      $ipData2 = fread($fd, 4);
      if (strlen($ipData2) < 4) {
        fclose($fd);
        return '- System Error';
      }
      $ip2num = implode('', unpack('L', $ipData2));
      if ($ip2num < 0)
        $ip2num += pow(2, 32);
      if ($ip2num < $ipNum) {
        if ($Middle == $BeginNum) {
          fclose($fd);
          return '- Unknown';
        }
        $BeginNum = $Middle;
      }
    }
    $ipFlag = fread($fd, 1);
    if ($ipFlag == chr(1)) {
      $ipSeek = fread($fd, 3);
      if (strlen($ipSeek) < 3) {
        fclose($fd);
        return '- System Error';
      }
      $ipSeek = implode('', unpack('L', $ipSeek . chr(0)));
      fseek($fd, $ipSeek);
      $ipFlag = fread($fd, 1);
    }
    if ($ipFlag == chr(2)) {
      $AddrSeek = fread($fd, 3);
      if (strlen($AddrSeek) < 3) {
        fclose($fd);
        return '- System Error';
      }
      $ipFlag = fread($fd, 1);
      if ($ipFlag == chr(2)) {
        $AddrSeek2 = fread($fd, 3);
        if (strlen($AddrSeek2) < 3) {
          fclose($fd);
          return '- System Error';
        }
        $AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
        fseek($fd, $AddrSeek2);
      } else {
        fseek($fd, -1, SEEK_CUR);
      }
      while (($char = fread($fd, 1)) != chr(0))
        $ipAddr2 .= $char;
      $AddrSeek = implode('', unpack('L', $AddrSeek . chr(0)));
      fseek($fd, $AddrSeek);
      while (($char = fread($fd, 1)) != chr(0))
        $ipAddr1 .= $char;
    } else {
      fseek($fd, -1, SEEK_CUR);
      while (($char = fread($fd, 1)) != chr(0))
        $ipAddr1 .= $char;
      $ipFlag = fread($fd, 1);
      if ($ipFlag == chr(2)) {
        $AddrSeek2 = fread($fd, 3);
        if (strlen($AddrSeek2) < 3) {
          fclose($fd);
          return '- System Error';
        }
        $AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
        fseek($fd, $AddrSeek2);
      } else {
        fseek($fd, -1, SEEK_CUR);
      }
      while (($char = fread($fd, 1)) != chr(0))
        $ipAddr2 .= $char;
    }
    fclose($fd);
    if (preg_match('/http/i', $ipAddr2)) {
      $ipAddr2 = '';
    }
    $ipaddr = "$ipAddr1 $ipAddr2";
    $ipaddr = preg_replace('/CZ88\.NET/is', '', $ipaddr);
    $ipaddr = preg_replace('/^\s*/is', '', $ipaddr);
    $ipaddr = preg_replace('/\s*$/is', '', $ipaddr);
    if (preg_match('/http/i', $ipaddr) || $ipaddr == '') {
      $ipaddr = '- Unknown';
    }
    return '- ' . $ipaddr;
  }
  
  /** 
   * @desc 根据两点间的经纬度计算距离 (以 m 为单位)
   * @param float $lat 纬度值 
   * @param float $lng 经度值 
   */
  public function getDistanceBetweenPoints ($lng1, $lat1, $lng2, $lat2) {
    /*
    Convert these degrees to radians
    to work with the formula
    */
    $lng1 = ($lng1 * pi() ) / 180;
    $lat1 = ($lat1 * pi() ) / 180;
    $lng2 = ($lng2 * pi() ) / 180;
    $lat2 = ($lat2 * pi() ) / 180;
    
    /*
     Using the
     Haversine formula
     http://en.wikipedia.org/wiki/Haversine_formula
     calculate the distance
     */
    $calcLongitude = $lng2 - $lng1; 
    $calcLatitude = $lat2 - $lat1; 
    $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2); 
    $stepTwo = 2 * asin(min(1, sqrt($stepOne))); 
    $calculatedDistance = EARTH_RADIUS * $stepTwo; 
    
    return round($calculatedDistance); 
  }
  
  /**
   * @desc 计算某个经纬度的周围某段距离的正方形的四个点
   * @param lng float 经度
   * @param lat float 纬度
   * @param distance float 该点所在圆的半径，该圆与此正方形内切，默认值为1000m
   * @return array 正方形的四个点的经纬度坐标
   */
  function getSquarePoints ($lng, $lat, $distance = 1000){
    $dlng =  2 * asin(sin($distance / (2 * EARTH_RADIUS)) / cos(deg2rad($lat)));
    $dlng = rad2deg($dlng);
  
    $dlat = $distance / EARTH_RADIUS;
    $dlat = rad2deg($dlat);
  
    return array(
      'left_top' => array('lng' => $lng - $dlng, 'lat' => $lat + $dlat),
      'right_top' => array('lng' => $lng + $dlng, 'lat' => $lat + $dlat),
      'left_bottom' => array('lng' => $lng - $dlng, 'lat' => $lat - $dlat),
      'right_bottom' => array('lng' => $lng + $dlng, 'lat' => $lat - $dlat)
    );
  }
  
  /**
   * @desc 获取多边形的外包四边形的点
   */
  function getPointsFromPolygon ($polygonPoints) {
    $neLng = '';
    $neLat = '';
    $swLng = '';
    $swLat = '';
    $polygonPointsExp = explode(',', $polygonPoints);
    if ($polygonPointsExp) {
      foreach ($polygonPointsExp as $i => $point) {
        if ($i % 2 == 0) {
          //最大lng
          if ($point > $neLng) $neLng = $point;
          //最小lng
          if (!$swLng || $point < $swLng) $swLng = $point;
        } else {
          //最大lat
          if ($point > $neLat) $neLat = $point;
          //最小lat
          if (!$swLat || $point < $swLat) $swLat = $point;
        }
      }
    }
    
    return array(
  	  'neLng' => $neLng,
      'neLat' => $neLat,
      'swLng' => $swLng,
      'swLat' => $swLat,
    );
  }
  
  /**
   * @desc 验证$lng, $lat是否在多边形内
   * 利用射线法，计算射线与多边形各边的交点，如果是偶数，则点在多边形外，否则在多边形内。
   * 还会考虑一些特殊情况，如点在多边形顶点上，点在多边形边上等特殊情况。
   */
  function checkPointInPolygon ($polygonPoints, $pLng, $pLat) {
    if (!$polygonPoints || !$pLng || !$pLat) return FALSE;
    
    $polygonPointsExp = explode(',', $polygonPoints);
    if ($polygonPointsExp) {
      $lngs = array();
      $lats = array();
      foreach ($polygonPointsExp as $i => $point) {
        if ($i % 2 == 0) {
          $lngs[] = $point;
        } else {
          $lats[] = $point;
        }
      }
      
      //所以点坐标
      $points = array();
      foreach ($lngs as $j => $lng) {
        $points[] = array(
          'lng' => $lng,
          'lat' => $lats[$j],
        );
      }
      $pointCount = count($points);
      
      //计算交点数
      $intersectCount = 0;
      $precision = 2e-10; //浮点类型计算时候与0比较时候的容差
      
      //初始化顶点$p1, $p2
      $p1 = $points[0];
      $p2 = 0;
      for ($i = 1; $i <= $pointCount; $i++) {
        //在顶点上
        if ($pLng == $p1['lng'] && $pLat == $p1['lat']) return TRUE;
        
        //初始化相临顶点
        $p2 = $points[$i % $pointCount];
        
        //无交点直接跳过
        if ($pLat < min($p1['lat'], $p2['lat']) || $pLat > max($p1['lat'], $p2['lat'])) {
          $p1 = $p2;
          continue; //next ray left point
        }
        
        //ray is crossing over by the algorithm (common part of)
        if ($pLat > min($p1['lat'], $p2['lat']) && $pLat < max($p1['lat'], $p2['lat'])) {
          if ($pLng <= max($p1['lng'], $p2['lng'])) { //x is before of ray 
            if ($p1['lat'] == $p2['lat'] && $pLng >= min($p1['lng'], $p2['lng'])) { //overlies on a horizontal ray
              return TRUE;
            }
          
            if ($p1['lng'] == $p2['lng']) { //ray is vertical
              if ($p1['lng'] == $pLng) { //overlies on a vertical ray
                return TRUE;
              } else { //before ray
                ++ $intersectCount;
              }
            } else { //cross point on the left side  
              $xinters = ($pLat - $p1['lat']) * ($p2['lng'] - $p1['lng']) / ($p2['lat'] - $p1['lat']) + $p1['lng'];//cross point of lng
              if (abs($pLng - $xinters) < $precision) { //overlies on a ray
                return TRUE;
              }
          
              if ($pLng < $xinters) {//before ray
                ++ $intersectCount;
              }
            }
          }
          
        //special case when ray is crossing through the vertex
        } else {
          if ($pLat == $p2['lat'] && $pLng <= $p2['lng']){//p crossing over p2
            $p3 = $points[($i+1) % $pointCount]; //next vertex
            if ($pLat >= min($p1['lat'], $p3['lat']) && $pLat <= max($p1['lat'], $p3['lat'])){//$pLat lies between $p1['lat'] & $p3['lat']
              ++ $intersectCount;
            } else {
              $intersectCount += 2;
            }
          }
        }
        
        $p1 = $p2;//next ray left point
      }
      
      //偶数在多边形外, 奇数在多边形内
      if ($intersectCount % 2 == 0){
        return FALSE;
      } else {
        return TRUE;
      }
    }
    
    return FALSE; 
  }
}