<?php
/**
 * @desc html内容整理工具类
 */
class HtmlUtils {
  
  /**
   * @desc 分析DOM节点树并提取内容文本(默认根据文本密度算法提取)
   * @param string html文本
   * @param string 根据字符串查找
   */
  static public function htmlParseContent ($html, $find = '', $replace = '') {
    $retText = '';
    if (!$html) return $retText;
    
    //print_r($html);exit;
    
    //完全过滤
    $html = preg_replace(array(
      //<div class=="head">头部块
      "/<div[^\>]{0,}(id|class)=[\"|\']head[\"|\'][^\>]{0,}>.*<\/div>/Ui",
        
      //<span>标签
      "/<\/?span[^\>]{0,}>/Ui", 
        
      //html注释
      "/<\!--.*-->/Us",
      
      //无内容的空<div>标签
      "/<div[^>]+><\/div>/Ui",
      
      //内容中的内嵌广告<div>标签(自定义广告,如人民网)
      "/<div class=[\"|\']ad_hh[\"|\']>.*<\/div>/Ui",
         
      //内容中的内嵌广告(js广告,如百度)
      "/<div[^\>]{0,}><script[^\>]{0,}>.*<\/script>(<\/div>)?/Ui",
        
      //内容中的图片描述<div>标签
      "/<div[^\>]{0,}>（网络配图）<\/div>/Ui",
        
      //内容中的QQ IM链接图片(http://wpa.qq.com/和QQ安全防水墙的图标)
      "/<img[^\>]+src=[\'|\"]?(http:\/\/wpa.qq.com\/.*|static\/image\/common\/security\.png)[\'|\"]?[^\>]+>/Ui",
        
    ), "", $html);
    
    //echo $html;exit;
    
    //保留内容过滤
    $html = preg_replace(array(
      //过滤仅包含<img>子节点的<div>标签
      //"/(<div[^\>]{0,}>)+(<img[^\>]{0,}>)+(<\/div>)+/i",
      
      "/(<div[^\>]{0,}>)+((<br>)?<img[^\>]{0,}>(<br>)?[^\<]{0,})+(<br>)?(<\/div>)+/i",
        
      //过滤<a>,</a>标签(仅保留内容)
      "/<a[^\>]{0,}>(.*)<\/a>/Ui",
        
      //内容中的以</div><div>分隔段落, 替换为<p>(TODO: 需多测试)
      "/<\/div><div>/i",
    ), array(
      //"$2", 
      "$2",
      "$1",
      "</p><p>",
    ), $html);
    
    //过滤<p></p>节点中的<div>或其它无用标签
    /* $html = preg_replace_callback("/<p[^\>]{0,}>(.*)<\/p>/Ui", function($match) {
      return strip_tags($match[1], '<img>');
    }, $html); */
    
    //过滤并调用callback处理
    //过滤<td>节点中的无用标签(保留<img><br><p>)
    /* 
    $html = preg_replace_callback("/<td[^\>]{0,}>(.*)<\/td>/Ui", function($match) {
      $match[1] = strip_tags($match[1], '<img><br><p>');
      return "<td>{$match[1]}</td>";
    }, $html); 
    */
    
    //图片新闻类处理<embed>,<object>,<video>(TODO 目前不支持图片新闻,过滤掉)
    $html = preg_replace(array(
      "/<script[^\>]{0,}>.*<\/script>/Uis", 
      "/<\/?iframe.*>/Ui",
    ), array(
      "",
    ), $html);
    
    //视频类标签处理<embed>,<object>,<video>(TODO 目前不支持视频,过滤掉)
    $html = preg_replace(array(
      "/<embed\s+[^\>]{0,}>.*<\/embed>/Uis",
      "/<object\s+[^\>]{0,}>.*<\/object>/Uis",
      "/<video\s+[^\>]{0,}>.*<\/video>/Uis",
    ), array(
      "",
    ), $html);
    
    //echo $html;exit;
    
    //临时处理为[p],[br],[img]
    $html = preg_replace(array(
      "/<(\/?)p[^\>]{0,}>/Ui", "/<(\/?)br[^\>]{0,}>/Ui", "/<img([^\>]{0,})>/Ui", "/<(\/?)strong[^\>]{0,}>/Ui"
    ), array(
      "[$1p]", "[br]", "[img $1]", "[$1strong]"
    ), $html);
    
    //匹配DOM节点树
    preg_match_all("/(.*<\/?\w+[^\>]{0,}>)/Uis", $html, $matches);
    $domTree = $matches[1];
    
    //echo $html;exit;
    //print_r($domTree);exit;
    
    //根据字符串查找
    if ($find) {
      //TODO
      
    //文本密度算法
    } else {
      $lastTextLen = 0;
      $lastStringLen = 0;
      $lastString = '';
      $lastTextPercent = 0;
      $lastDomTreeId = 0;
      foreach ($domTree as $id => $string) {
        //过滤相关内容推荐 (上一篇, 下一篇)
        if(stripos($string, '上一篇：') !== FALSE || stripos($string, '下一篇：') !== FALSE) continue;
        
        //过滤相关版权文本到最底部的dom内容 (版权与免责声明, 版权声明)
        //@see 版权文本都在页面最底部,一定检测到便退出遍历dom树
        if(stripos($string, '版权与免责声明') !== FALSE || stripos($string, '版权声明') !== FALSE) break;
        
        //过滤</title>
        if (stripos($string, '</title>') !== FALSE) continue;
        
        //分析出文本内容
        $text = preg_replace("/[^\x{4e00}-\x{9fa5}]+/ui", '', $string);
        
        //计算中文文本字节数(+img标签字节数)
        $textLen = strlen($text);
        preg_match_all("/(\[img\s+[^\]]+\])/Ui", $string, $matches);
        if ($matches[1]) {
          $imgLen = mb_strlen(implode(',', $matches[1]), APP_DEFAULT_CHARACTER);
          $textLen += $imgLen;
          unset($matches);
        }
        
        //无文本+img图片内容则直接过滤
        if (!$textLen) continue;
        
        //计算文本字节数和整行字节数, 小于100则取占比最大值
        $stringLen = strlen($string);
        if ($textLen < 100 && $lastTextLen < 100) {
          //计算字符字节数和中文文本占比%
          $textPercent = round($textLen/$stringLen, 2);
          if ($textPercent > $lastTextPercent) {
            $lastTextLen = $textLen;
            $lastStringLen = $stringLen;
            $lastString = $string;
            $lastTextPercent = round($lastTextLen/$lastStringLen, 2);
            $lastDomTreeId = $id;
          }
          
        //反之
        //则与上一文本字节数比较, 取最大值
        } elseif ($textLen > $lastTextLen) {
          $lastTextLen = $textLen;
          $lastStringLen = $stringLen;
          $lastString = $string;
          $lastDomTreeId = $id;
        }
        
        //TODO debug
        //echo "{$stringLen} {$textLen} {$textPercent} {$string}\n";
      }
      
      if ($lastString) {
        if ($lastDomTreeId) {
          //上移一位DOM节点树(内容补充分析)
          $prevString = $domTree[$lastDomTreeId-1];
          
          //寻找是否有配图(仅图片)
          if (!trim(preg_replace("/(\[img\s+[^\]]+\])/Ui", "", strip_tags($prevString)))) {
            $lastString = strip_tags($prevString) . $lastString;

          //寻找是否有结尾的</div>(仅1个)隔断的内容, 且无任何多余<xxx>标签
          } else {
            $prevString = preg_replace("/<\/div>$/Ui", "", $prevString);
            preg_match('/<\/?[a-z]+[^\>]{0,}>/Ui', $prevString, $checkMatches);
            if (!count($checkMatches)) {
              $lastString = strip_tags($prevString) . $lastString;
            }
          }
        }
        
        //还原p,br,img
        $lastString = preg_replace(array(
          "/\[(\/?)p\]/Ui", "/\[br\]/Ui", "/\[img([^\]]{0,})\]/", "/\[(\/)?strong\]/"
        ), array(
          "<$1p>", "<br>", "<img$1>", "<$1strong>"
        ), $lastString);
        
        $retText = $lastString;
        unset($lastString);
      }
    }
    
    //echo $retText;exit;
    return $retText;
  }
  
  /**
   * @desc html文本预处理(滤掉与正文无关的信息)
   * @param unknown $html
   */
  static public function htmlFormat ($html, $contentType = '') {
    if (!$html) return '';

    //编码转换(GBK转换为UTF8)
    if (stripos($contentType, 'utf-8') === FALSE && (preg_match('/content=[\"|\'][^\"]{0,}charset=(gbk|gb2312)[^\"]{0,}[\"|\']/i', $html) || preg_match('/<meta\s+charset=(\"|\')?(gbk|gb2312)(\"|\')?[^\"]{0,}>/i', $html)) ) {
      $html = Utils::gbkToUtf8($html);
      //$html = preg_replace('/(gbk|gb2312)/i', 'utf-8', $html);
      //echo $html;exit;
    }
    
    //GBK实体用utf-8编码,乱码识别(无任何中文字符)
    if (!preg_match("/[^\x{4e00}-\x{9fa5}]+/ui", $html)) {
      $html = Utils::gbkToUtf8($html);
    }

    //将Unicode编码转换成utf-8编码
    $html = self::unicodeDecode($html);

    //反转义(删除字符\r\n)
    $html = str_replace(array('\n', '\r', '\r\n'), "", $html);
    $html = stripslashes($html);
    
    //转义html字符
    /* $html = str_replace(array("&lsquo;", "&rsquo;", "&bdquo;", "&rsaquo;", "&ldquo;", "&rdquo;", "&sbquo;", "&hellip;", "&mdash;", "&ndash;", "&nbsp;", "&uarr;", "&larr;", "&darr;", "&rarr;", "&gt;", "&lt;", "&middot;", "&amp;", "&cap;", "&quot;", "&times;", "&divide;", "&lArr;", "&rArr;", "&uArr;", "&dArr;", "&trade;", "&deg;", "&bull;", "&agrave;", "&aacute;", "&igrave;", "&iacute;", "&egrave;", "&eacute;", "&oacute;", "&ograve;", "&ugrave;", "&uacute;", "&radic;", "&brvbar;", "&ge;", "&le;", "&epsilon;", "&not;", "&macr;", "&Pi;", "&pi;", "&omicron;", "&omega;", "&ang;", "&laquo;", "&ordm;", "&emsp;",
    ), array("‘", "’", "„", "›", "“", "”", "‚", "…", "—", "–", " ", "↑", "←", "↓", "→", ">", "<", "·", "&", "∩", '"', "×", "÷", "⇐", "⇒", "⇑", "⇓", "™", "°", "•", "à", "á", "ì", "í", "è", "é", "ó", "ò", "ù", "ú", "√", "¦", "≥", "≤", "ε", "¬", "¯", "Π", "π", "Ο", "ω", "∠", "«", "»", ""
    ), $html); */
    
    //过滤掉空格(&nbsp; 和 全角空格)
    $html = html_entity_decode($html, ENT_QUOTES, APP_DEFAULT_CHARACTER);
    $htmlentities = htmlentities($html, ENT_NOQUOTES, APP_DEFAULT_CHARACTER);
    if ($htmlentities) {
      $html = $htmlentities;
      $html = str_replace(array('&nbsp;', '　'), '', $html);
      $html = html_entity_decode($html, ENT_QUOTES, APP_DEFAULT_CHARACTER);
    }

    //过滤
    $html = preg_replace(array(
      "/<\!DOCTYPE[^\>]{0,}>/Ui",
      "/<\/?(html|head|body|form|center|tbody).*>/Ui",
      "/<meta\s+[^\>]{0,}>/Ui",
      "/<link\s+[^\>]{0,}>/Ui",
      "/<\/?i>/Ui",
      "/<\/?b>/Ui",
      "/<style[^\>]{0,}>(.*)<\/style>/Uis",
      "/<noscript[^\>]{0,}>.*<\/noscript>/Uis",
      "/<textarea[^\>]{0,}>.*<\/textarea>/Uis",
      "/<input\s+[^\>]{0,}>/Ui",
      "/<label[^\>]{0,}>.*<\/label>/Uis",
      "/<font[^\>]{0,}class=[\"|\']jammer[\"|\'][^\>]{0,}>.*<\/font>/Ui",
      "/<\/?font[^\>]{0,}>/Ui",
      "/(alt|title)=[\"|\']\W+[\"|\']/Ui",
      "/(onclick)=\"[^\"]+\"/Ui",
      "/<\w+[^\>]{0,}style=[\"|\'][^\"|^\']{0,}display\s{0,}:\s{0,}none[^\"|^\']{0,}[\"|\'][^\>]{0,}>.*<\/\w+>/Uis",
      "/\s{2,}/",
      "/(\r|\n|\t|\f|\v)/",
      "/<\/img>/i",
      "/\s?-\s?Powered by (phpwind|discuz)/Ui"//去掉title中“- Powered”语句
    ), "", $html);
    
    //过滤开头的<!--xxxxx-->(如东方网)
    $html = preg_replace("/^<\!--.*-->/Ui", "", $html);
        
    //TODO test
    //echo $html;exit;
    //preg_match_all("/href=[\"|\']?([^\"|^\'|^\=]{0,}\/b\d{4,}\/)[\"|\']?/Ui", $html, $matches);
    //var_dump($matches);exit;
    
    return $html;
  }
  
  /**
   * @desc 过滤内容
   * @param string $content 处理的内容
   * @param string 需要过滤的内容
   */
  static public function filterContent ($content, $findContent = '') {
    if (!$content) return '';
    
    if ($findContent) {
      //转换成正则标准格式
      $regRules = Utils::getRegRule($findContent);
      
      //“*”代表不确定的内容, 需替换成正则 .*
      $regRules = str_replace("\*", ".*", $regRules);
      $content = preg_replace("/{$regRules}/Uis", "", $content);
    }
    
    return $content;
  }
  
  /**
   * @desc 去除多余内容
   * @param string $content 处理的内容
   */
  static public function convertOther ($content) {
    if (!$content) return '';

    //查找的字符串
    $aPat[0] = "/\[\w+=[^\]]+\]/Ui";
    $aPat[1] = "/\[\/?\w+\]/Ui";
    //$aPat[2] = "/\[([a-zA-Z0-9\:\/\.\_\-\\\?\&\;\=\,\#]+)\]/iss";
    //$aPat[3] = "/\[\]/";
    //$aPat[4] = "/\[font=[^\]]+\]/Ui";
   
    //discuz论坛附件相关文案过滤
    $aPat[5] = "/(点击文件名)?下载附件\s{0,}\(.*[KB|MB]\)/Ui";
    $aPat[6] = "/[^\r|^\n]+\.(jpg|png|jpeg|gif|bmp)\s{0,}\(.*(MB|KB).*下载次数.*\d+\)/Ui";
    $aPat[7] = "/((此帖|此贴|本主题|本帖|本贴).*(于|被) .*(编辑|加亮|移动|推荐|审核通过|限时精华|添加图章 荣登头条|添加图章 编辑采用|加入精华|删除回复|提升|置顶|设置高亮|限时高亮|解除限时高亮|解除限时置顶)|点击文件名下载附件|下载附件|保存到相册|(操作|到本区)\(\d+-\d+-\d+\))/U";
    $aPat[8] = "/(\d+\s{0,}分钟|\d+\s{0,}小时|半小时|昨天|前天|\d+\s{0,}天|\d{4}-\d{1,2}-\d{1,2}\s+\d{1,2}:\d{1,2}).*上传|\n+上传/U";
    $aPat[9] = "/<img\s+src=[\"|\'][^\>]{0,}(static\/image|statics\/images)\/.*[\"|\'][^\>]{0,}>/Ui";
    $aPat[10] = "/图片:.*\.(jpg|png|jpeg|gif|bmp)/Ui";
    $aPat[11] = "/幻灯播放|点击放大|下载次数.*\d+|登录\/注册可看大图/";
    $aPat[12] = "/该贴已经同步到.*微博|TencentArticl\.onload\(\);/U";
    
    //杭州网广告
    $aPat[13] = "/<img\s+src=[\"|\']http:\/\/bbs\.hangzhou\.com\.cn\/.*(\.gif|icon_logo\.png)[\"|\'][^\>]{0,}>/i";
    
    //钱报网广告
    $aPat[14] = "/更多内容请见钱报网（www\.qjwb\.com\.cn）|24小时新闻热线：96068/i";
    $aPat[15] = "/<img\s+src=[\"|\'](http:\/\/(upload|img)\.qjwb\.com\.cn\/.*\/(1390197516521\.jpg|baokaluru\.png|qqbaoliao\.png))[\"|\'][^\>]{0,}>/i";
    
    //大浙网广告
    $aPat[16] = "/正文已结束，您可以按alt\+4进行评论/i";
    $aPat[17] = "/<img\s+src=[\"|\']http:\/\/(img1|mat1)\.gtimg\.com\/.*(ajax-loadernone\.gif|\d+\.png|111654786\.jpg)[\"|\'][^\>]{0,}>/i";
    $aPat[18] = "/扫描下载腾讯新闻客户端\s+关注浙江页卡|加载中...|(前|上|下)一页(：)?|第\d+页|此新闻共有\d+页|再看一次进入图片中心|分享到\d+/";

    //余杭新闻网广告
    $aPat[19] = "/<img[^\>]+src=[\"|\']http:\/\/www\.eyh\.cn\/QQ图片20140320161649\.jpg[\"|\'][^\>]{0,}>/i";
    
    //平湖在线广告
    $aPat[20] = "/平湖在线微信二维码，扫一下轻松添加关注|\[(.*?)?\]/";
    $aPat[21] = "/<img[^\>]+src=[\"|\']http:\/\/bbs\.ph66\.com\/.*2287_197636_73b37921feea31f\.jpg.*[\"|\'][^\>]{0,}>/Ui";
    
    //平湖18楼广告
    $aPat[22] = "/扫描二维码关注18楼微信/";
    $aPat[23] = "/<img[^\>]+src=[\"|\']http:\/\/attach3\.18ph\.com\/.*150017bak1ewb22cx2eex7\.jpg[\"|\'][^\>]{0,}>/Ui";
    $aPat[24] = '/帖子标签:[^\n]+/';

    //嘉善生活网广告
    $aPat[25] = "/<img[^\>]+src=[\"|\']http:\/\/bbs\.jsr\.cc\/.*083151nhkmaazkwj1ew11p\.gif[\"|\'][^\>]{0,}>/Ui";
    
    //嘉善生活网广告
    $aPat[26] = "/<img[^\>]+src=[\"|\'].*qq_login\.gif[\"|\'][^\>]{0,}>/Ui";
    
    //东方热线-东论社区广告
    $aPat[27] = "/，推荐理由:/";
    
    //互联天地-论坛广告
    $aPat[28] = "/<img[^\>]+src=[\"|\'].*sina_login_btn\.png[\"|\'][^\>]{0,}>/Ui";
    $aPat[29] = '/登录后可欣赏大图，立即\s{0,}登录，也可以用QQ号或新浪微博账号直接登陆本站/';

    //新北仑-阿拉宁波网广告
    $aPat[30] = "/<img[^\>]+src=[\"|\'].*advert\/.*\.gif[\"|\'][^\>]{0,}>/i";

    //富阳新闻网广告
    $aPat[31] = '/更多要闻请点击\s+新闻频道/';
    
    //慈溪广告
    $aPat[32] = '/<img[^\>]+src=[\"|\'].*(img|images)\/.*\.gif[\"|\'][^\>]{0,}>/i';
    $aPat[33] = '/<img[^\>]+src=[\"|\']ad\/.*\.(gif|jpg)[\"|\'][^\>]{0,}>/i';

    //嘉兴在线广告
    $aPat[34] = '/\s{0,}<img[^\>]+src=[\"|\'].*images\/fetion\.png[\"|\'][^\>]{0,}>\s{0,}/i';
    $aPat[35] = "/嘉兴在线新闻网.*我要投稿|飞信报料有奖/Ui";
    
    $aPat[36] = '/<img[^\>]+src=[\"|\'].*logo\.gif[\"|\'][^\>]{0,}>/i';
    $aPat[37] = "/上江门五邑网知天下事.*我要爆料|<strong>相关报道<\/strong>：.*【<strong>详情<\/strong>】|新浪城市\|.*新浪内蒙古|<strong>芜湖在线企业专题推广.*<\/strong>|更多精彩资讯、优惠活动.*即可/Uis";
    
    //替换为
    $aRepl[0] = "";
    $content = preg_replace($aPat, $aRepl, $content);

    //TODO test
    //echo $content;
    //exit;
    return $content;
  }
  
  /**
   * @desc html代码格式处理(转换成纯文本，仅有\s, \n)
   * @param string $httpUrl 完整的http URL地址(用于补全img url)
   * @param string $content 处理的内容
   * @param string $allowableTags 允许的标签
   */
  static public function convertHtmlToText ($httpUrl, $content, $allowableTags = '<img><br><strong>') {
    if (!$content) return '';
    
    //过滤<script>所有内容
    $content = preg_replace(array(
      "/<script[^\>]{0,}>.*<\/script>/Ui",
      "/<div\s+class=[\"|\']attach_nopermission attach_tips[\"|\']>.*<\/span><\/div>/Ui",//过滤论坛附件登录信息
      '/<div\s+class=[\"|\']tag_div[\"|\']>.*<\/div>/Ui',//过滤论坛标签信息
    ), array(
      "",
    ), $content);

    //保留换行符<br>
    $content = preg_replace(array(
      '/<\/div>/i', '/<\/p>/i', "/<br[^\>]{0,}>/Ui", "/<\/td><\/tr>/i", "/<\/td><td>/i"
    ), array(
      '</div><br>', '</p><br>', "<br>", "</p><br>", " "
    ), $content);

    //echo $content;exit;
    
    //去除html标签(保留$allowableTags)
    $content = strip_tags($content, $allowableTags);
    $content = preg_replace(array("/<br>\s{0,}/i", "/\s{0,}<br>/i"), "<br>", $content);
    
    //去除多余空格
    $content = preg_replace("/\s{2,}/Ui", "", $content);
    
    //把所有换行符<br>替换成\n
    $content = preg_replace("/(<br>)+/i", "\n", $content);
    
    //补全img标签的http连接
    if (stripos($allowableTags, '<img>') !== FALSE) {
       if ($httpUrl) $content = self::completeImgHttpUrl($httpUrl, $content);
       $content = preg_replace("/<img[^\>]{0,}(src|zoomfile|file)=[\"|\'](.*?)[\"|\'][^\>]{0,}>/i", "\n<img src=\"$2\">\n", $content);
    }
    
    //去除多余内容
    $content = self::convertOther($content);
    
    //去掉第1行的\n,并合并多个为1个\n
    $content = preg_replace(array("/^\n+/", "/\n+/"), array("", "\n"), $content);
    
    return trim($content);
  }
  
  /**
   * @desc 解析内容中的 img
   */
  static public function convertImgList ($content) {
    $imgList = array();
    if ($content) {
      preg_match_all("/<img[^\>]{0,}src=[\"|\'](.*?)[\"|\'][^\>]{0,}>/i", $content, $matches);
      if ($matches && $matches[1]) {
        $imgList = $matches[1];
      }
    }

    //去掉重复url
    $imgList = array_unique($imgList);
    return $imgList;
  }
  
  /**
   * @desc 替换内容中的img以支持lazyload图片加载
   */
  static public function convertImgWithLazyload ($content) {
    return preg_replace("/<img[^\>]{0,}src=[\"|\'](.*?)[\"|\'][^\>]{0,}>/i", "<img class='lazy-img' src='http://f.shiyi11.com/ui/img/dot1.gif' data-original='$1'>", $content);
  }
  
  /**
   * @see 需先调用convertHtmlToText
   * @desc 纯文本代码格式处理(转换成html，仅有<br>, &nbsp;)
   * @param string $content 处理的内容
   * @param string $allowableTags 允许的标签
   */
  static public function convertTextToHtml ($content) {
    if (!$content) return '';
    //$content = str_replace(" ", "", $content);//过滤中文空格
    //$content = preg_replace("/\n{2,}/", "\n<br>\n", $content);
  	$content = preg_replace("/\n/", "</p><p>", '<p>' .$content . '</p>');
  	$content = preg_replace(array("/<p>\s{0,}<p>/i", "/<\/p>\s{0,}<\/p>/i"), array('<p>', '</p>'), $content);
  	$content = preg_replace("/<p>\s{0,}<\/p>/i", "<p></p>", $content);
  	return $content;
  }
  
  /**
   * @desc 解析http Path路径
   * @param string $httpUrl 完整的http URL地址
   * @param bool $domain
   */
  static public function gethttpPath ($httpUrl, $absolutePath = FALSE) {
    $httpPath = '';
    if ($httpUrl) {
      $expHttpUrl = explode('/', $httpUrl);
      if ($expHttpUrl) {
        if ($absolutePath == TRUE) {
          //返回domain
          $httpPath = $expHttpUrl[0] . '//' . $expHttpUrl[2];
        } else {
          //返回path
          $lastExp = '';
          if (count($expHttpUrl) < 4) {
            $httpUrl .= "/";
          } else {
            $lastExp = $expHttpUrl[count($expHttpUrl) - 1];
          }
          $httpPath = $lastExp ? str_replace($lastExp, '', $httpUrl) : $httpUrl;
        }
      }
    }

    return $httpPath;
  }
  
  /**
   * @desc 补全img标签的http链接
   * @param string $httpUrl 完整的http URL地址
   * @param string $content 处理的内容
   */
  static public function completeImgHttpUrl ($httpUrl, $content) {
    if (!$httpUrl || !$content) return '';
    
    //补全src
    $newSrcList = array();
    preg_match_all("/<img[^\>]{0,}(src|zoomfile|file)=[\"|\'](.*?)[\"|\'][^\>]{0,}>/i", $content, $matches);
    $srcList = $matches[2];
    if ($srcList) {
      //去重
      $srcList = array_unique($srcList);
      foreach ($srcList as $src) {
        if (stripos($src, 'http://') === FALSE) {
          $findSrcList[] = $src;
          $replaceSrcList[] = self::completeHttpUrl($httpUrl, $src);
        }
      }
      if ($findSrcList && $replaceSrcList) $content = str_replace($findSrcList, $replaceSrcList, $content);
    }
    
  	return $content;
  }
  
  /**
   * @desc 补全http链接
   * @param string $httpUrl 完整的http URL地址
   * @param string $url
   */
  static public function completeHttpUrl ($httpUrl, $url) {
    if (!$url) return '';
  
    //检测http
    if (stripos($url, 'http://') === FALSE && stripos($url, 'https://') === FALSE) {

      //检测绝对、相对路径(TRUE为绝对路径)
      $absolutePath = FALSE;
      if (preg_match('/^\/.*/', $url)) $absolutePath = TRUE;
      $httpPath = self::gethttpPath($httpUrl, $absolutePath);
      $url = $httpPath . $url;
    }
    
    return trim($url);
  }
  
  /**
   * @desc 换图片分段输出内容集合
   * @param string $content 处理的内容
   */
  static public function convertContentToArray ($content) {
    $ret = array();

    if (mb_strlen($content, APP_DEFAULT_CHARACTER) < 4) {
      $content = $content . "    ";
    }
    
    //匹配出所有图片, 标识分段符
    $content = preg_replace("/<img[^\>]{0,}src=[\"|\'](.*?)[\"|\'][^\>]{0,}>/i", '<%@IMG%>IMG:$1<%@IMG%>', $content);
    
    //根据分段符, 取出内容
    $contentArray = explode('<%@IMG%>', $content);
    
    $maxLength = 1000;
    foreach ($contentArray as $item) {
      //过滤中英文空格
      $item = str_replace(array(" ", "　", " "), "", $item);
      
      //过滤为空的段
      $trimItem = trim($item);
      if (!$trimItem) continue;
      
      //内容按1000个字分段(考虑到: 1、内容太多加载卡;2、IOS的label标签有容量问题;)
      $itemLength = mb_strlen($trimItem, APP_DEFAULT_CHARACTER);
      if ($itemLength <= $maxLength){
        
        //图片URL处理
        if (stripos($trimItem, 'IMG') !== FALSE) {
          $trimItem = str_replace('?', '%3F', $trimItem);
        }
        
        $ret[] = $trimItem;
      } else {
        $num = intval(ceil($itemLength/$maxLength));
        for ($i = 0; $i < $num; $i++) {
          $secondItem = mb_substr($trimItem, $i * $maxLength, $maxLength, APP_DEFAULT_CHARACTER);
          $trimSecondItem = trim($secondItem);
          $ret[] = $trimSecondItem;
        }
      }
    }
    
    return $ret;
  }
  
  /**
   * @desc 将Unicode编码转换成utf-8编码
   * @param string $content 处理的内容
   */
  static public function unicodeDecode ($content) {
    preg_match_all('/(\\\u([\w]{4}))/i', $content, $matches);
    if (!empty($matches)) {
      $founds = array();
      $replaces = array();
      for ($j = 0; $j < count($matches[0]); $j++) {
        $str = $matches[0][$j];
        if (strpos($str, '\\u') === 0) {
          $codeA = base_convert(substr($str, 2, 2), 16, 10);
          $codeB = base_convert(substr($str, 4), 16, 10);
          $utf8Str = chr($codeA).chr($codeB);
          $utf8Str = iconv('UCS-2', 'UTF-8', $utf8Str);
          $founds[] = $str;
          $replaces[] = $utf8Str;
        }
      }
  
      $content = str_replace($founds, $replaces, $content);
    }
  
    return $content;
  }
}