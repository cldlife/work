<h2 class="fixmt">玩主API接口说明_<?php echo $this->version?></h2>

<h2>公共参数</h2>
<p>
    <h4>API请求地址：</h4>
    <?php echo WEB_QW_APP_API_DOMAIN;?>/<strong>[api_version][api_path]</strong>
</p>
<p><h4>HTTP请求方式：</h4>POST （测试期间支持GET）</p>
<p><h4>返回数据格式：</h4>JSON</p>
<p><h4>[api_version]：</h4><?php echo $this->version?></p>
<p><h4>密匙id [client_id]：</h4>1</p>
<p><h4>静态密匙key [client_secret]：</h4>API_QW_WanZhu_client_secret</p>
<p><h4>动态密匙签名key：</h4>API_QW_WanZhu_Sig_Key_t201609101734</p>
<p><h4>动态密匙签名算法：</h4>md5(签名key + client_id + api_version + t)</p>
<p><h4>上传token（用作file name）：</h4>API_QW_WanZhu_upload_key</p>
<!-- <p><h4>测试参数 s_uuid：</h4>MXwxfDE0Yjc4ZTUzNGJhOTBlOTFhOGI3YTYzNGIzMjc4NjBlXzQzMjI3ZGEzYmNmNzZlZjZlYmZmYTUzYWNmNTMzNDRm==</p> -->

<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
          <td class="bold">version</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>当前APP版本号, 如 <?php echo $this->version?></td>
        </tr>
        <tr>
            <td class="bold">client_id</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>密匙id</td>
        </tr>
        <tr>
            <td class="bold">client_secret</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>密匙key（动态密匙）</td>
        </tr>
        <tr>
          <td class="bold">t</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>内建时钟时间戳（用于动态密匙安全时间验证）</td>
        </tr>
        <tr>
          <td class="bold">network</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>当前手机网络状态，如：wifi，china_mobile_3G</td>
        </tr>
        <tr>
          <td class="bold">system</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>当前手机系统类型，如：iPhone 4S 7.1.0</td>
        </tr>
    </tbody>
</table>

<h2>返回码</h2>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        
        <tr>
            <td class="bold">code</td>
            <td align="center">int</td>
            <td>返回码数值:<br><br>等于0 - 成功<br>大于0 - 失败；等于403 - 则app切到登录界面</td>
        </tr>
        <tr>
            <td class="bold">description</td>
            <td align="center">string</td>
            <td>返回码描述信息</td>
        </tr>
        <tr>
            <td class="bold">apptip</td>
            <td align="center">string</td>
            <td>应用提示文案:<br><br>不为空 - 则弹框提示description<br>为空 - 则不提示</td>
        </tr>
        
    </tbody>
</table>

<h2>URL Scheme约定</h2>
<p><strong>[webview]：</strong>WanZhu://webview</p>
<p><strong>[参数分隔符]：</strong>?</p>
<p><strong>[json参数]：</strong>{"title":"\u6d4b\u8bd5\u9875","link":"http:\/\/m.wanzhuwenhua.com\/app\/webview\/testpage.html","hide_nav":1,"skin_color":"#cccccc"}</p>
<p><strong>[示例]：</strong>WanZhu://webview?{"title":"\u6d4b\u8bd5\u9875","link":"http:\/\/m.wanzhuwenhua.com\/app\/webview\/testpage.html","hide_nav":1,"skin_color":"#cccccc"}</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>参数描述</th>
        </tr>
        <tr>
            <td class="bold">title</td>
            <td align="center">string</td>
            <td>Nav导航栏标题</td>
        </tr>
        <tr>
            <td class="bold">link</td>
            <td align="center">string</td>
            <td>webview请求链接</td>
        </tr>
        <tr>
            <td class="bold">hide_nav</td>
            <td align="center">int</td>
            <td>是否显示隐藏Nav导航栏，0-否（默认显示），1-是（隐藏）</td>
        </tr>
        <tr>
            <td class="bold">skin_color</td>
            <td align="center">string</td>
            <td>皮肤颜色值16进制（设置Nav和webview背景颜色, 为空则默认）</td>
        </tr>
    </tbody>
</table>

<h2>全局接口</h2>
<p>
    <h4><a name="g1.1" id="g1.1">》1.1、获取服务器当前时间戳：（用于APP内建时钟，同步时间）</a></h4>
    <br>
    <strong>[api_path]：</strong>/setting/generatesign.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/setting/generatesign');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">client_secret</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td style="color: red">仅此接口使用静态密匙key（来自公共参数）</td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">t</td>
            <td align="center">int</td>
            <td>服务器当前时间戳（秒）</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="g1.2" id="g1.2">》1.2、全局设置：启动APP时调用</a></h4>
    <br>
    <strong>[api_path]：</strong>/setting/global.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/setting/global');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">选填</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">update_setting</td>
            <td align="center">object</td>
            <td>版本更新设置：<br><br>version - 新版本号，如<?php echo $this->version?><br>updated_title - 提示框标题<br>updated_content - list，提示框内容列表<br>download_link - android:apk下载地址，iphone:默认空</td>
        </tr>
        <tr>
            <td class="bold">coins_list</td>
            <td align="center">list</td>
            <td>金币购买列表：<br><br>id - 选项id<br>coin - 金币数<br>fee - 金额，如6.00<br>desc - 购买描述</td>
        </tr>
        <tr>
            <td class="bold">friending_roses</td>
            <td align="center">list</td>
            <td>好友门槛设置列表：<br><br>id - 选项id<br>roses - 玫瑰数</td>
        </tr>
        <tr>
            <td class="bold">enabled_wxpay</td>
            <td align="center">int</td>
            <td>购买金币微信支付开关：0-关闭，1-开启（仅iOS有效，默认关闭开启iOS内购）</td>
        </tr>
        <tr>
            <td class="bold">apple_issandbox</td>
            <td align="center">int</td>
            <td>Apple内购沙盒开关：0-关闭，1-开启（仅iOS有效，默认关闭开启沙盒环境）</td>
        </tr>
        <tr>
            <td class="bold">howtoplay_url</td>
            <td align="center">string</td>
            <td>怎么玩H5链接</td>
        </tr>
        <tr>
            <td class="bold">invite_url</td>
            <td align="center">string</td>
            <td>邀请好友H5链接，其中“{{uid}}”需要替换成当前用户uid</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="g1.3" id="g1.3">》1.3、发送短信验证码</a></h4>
    <br>
    <strong>[api_path]：</strong>/message/sendsmscode.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/message/sendsmscode');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">type</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>验证码类型，1:注册，2:找回密码，3:绑定手机，4:更新绑定手机</td>
        </tr>
        <tr>
            <td class="bold">mobile</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>手机号（type等于4时，可空）</td>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">选填</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id（type等于3或4时，必须）</td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：</b></p>
默认

<p>
    <h4><a name="g1.4" id="g1.4">》1.4、意见反馈</a></h4>
    <br>
    <strong>[api_path]：</strong>/setting/feedback.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/setting/feedback');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>内容</td>
        </tr>
        <tr>
            <td class="bold">contact_info</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>联系方式（QQ/微信/手机号）</td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：</b></p>
默认

<p>
    <h4><a name="g1.5" id="g1.5">》1.5、举报</a></h4>
    <br>
    <strong>[api_path]：</strong>/setting/report.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/setting/report');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">report_id</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>举报id，1-帖子（爆照） 2-用户</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td align="center" class="red">必须</td>
            <td align="center">json</td>
            <td>举报内容（json对象）<br>
            <br>1、帖子
            <br>｛"tid": "6546454546789"｝
            <br>2、用户
            <br>｛"uid": "1234564564646"｝</td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：</b></p>
默认

<p>
    <h4><a name="g1.6" id="g1.6">》1.6、微信支付</a></h4>
    <br>
    <strong>[api_path]：</strong>/paying/wxpay.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/paying/wxpay');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">coin_id</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>金币购买列表选项id</td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">wxpayPre</td>
            <td align="center">object</td>
            <td>package - Sign=WXPay<br>partnerid - 微信支付商户号<br>prepayid - 微信支付交易会话ID<br>appid - 微信应用APPID<br>timestamp - 时间戳<br>noncestr - 随机字符串<br>sign - 签名</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="g1.7" id="g1.7">》1.7、Apple内购收据验证</a></h4>
    <br>
    <strong>[api_path]：</strong>/paying/appleinapp.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/paying/appleinapp');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">coin_id</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>金币购买列表选项id</td>
        </tr>
        <tr>
            <td class="bold">receipt_data</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>Apple内购收据</td>
        </tr>
        <tr>
            <td class="bold">issandbox</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>是否是沙盒环境（同/setting/global.json返回值一致）</td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：</b></p>
默认

<h2>账号接口</h2>
<p>
    <h4><a name="c2.1" id="c2.1">》2.1、手机登录</a></h4>
    <br>
    <strong>[api_path]：</strong>/user/login.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/user/login');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">mobile</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>手机号</td>
        </tr>
        <tr>
            <td class="bold">passwd</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>密码</td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">user_info</td>
            <td align="center">object</td>
            <td>nickname - 昵称<br>avatar - 头像url<br>gender - 性别, 男/女（默认空或保密）<br>birthday - 生日, 如19990909</td>
        </tr>
        <tr>
            <td class="bold">is_need_edit</td>
            <td align="center">int</td>
            <td>是否需要完善资料，0: 否，1: 是</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="c2.2" id="c2.2">》2.2、Weixin登录</a></h4>
    <br>
    <strong>[api_path]：</strong>/user/loginweixin.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/user/loginweixin');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">unionid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>weixin授权成功的unionid</td>
        </tr>
        <tr>
            <td class="bold">openid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>weixin授权成功的openid</td>
        </tr>
        <tr>
            <td class="bold">nickname</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>weixin授权成功的nickname</td>
        </tr>
        <tr>
            <td class="bold">gender</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>性别, 男/女（默认空或保密）</td>
        </tr>
        <tr>
            <td class="bold">avatar</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>头像, 用headimgurl（用132的尺寸）</td>
        </tr>
        <tr>
            <td class="bold">access_token</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>weixin授权成功的access_token</td>
        </tr>
        <tr>
            <td class="bold">expires_in</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>weixin授权成功的access_token的有效期，单位为秒</td>
        </tr>
        <tr>
            <td class="bold">refresh_token</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>weixin授权成功的刷新token</td>
        </tr>
        <tr>
            <td class="bold">location</td>
            <td align="center" class="red">选填</td>
            <td align="center">string</td>
            <td>用户所在地 province+city，如：北京 朝阳</td>
        </tr>
        <tr>
            <td class="bold">sign</td>
            <td align="center" class="red">选填</td>
            <td align="center">string</td>
            <td>weixin签名</td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">user_info</td>
            <td align="center">object</td>
            <td>nickname - 昵称<br>avatar - 头像url<br>gender - 性别, 男/女（默认空或保密）<br>birthday - 生日, 如19990909</td>
        </tr>
        <tr>
            <td class="bold">is_need_edit</td>
            <td align="center">int</td>
            <td>是否需要完善资料，0: 否，1: 是</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="c2.3" id="c2.3">》2.3、手机注册</a></h4>
    <br>
    <strong>[api_path]：</strong>/user/signup.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/user/signup');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">mobile</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>手机号</td>
        </tr>
        <tr>
            <td class="bold">passwd</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>密码</td>
        </tr>
        <tr>
            <td class="bold">mcode</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>手机短信验证码</td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">user_info</td>
            <td align="center">object</td>
            <td>nickname - 昵称<br>avatar - 头像url<br>gender - 性别, 男/女（默认空或保密）<br>birthday - 生日, 如19990909</td>
        </tr>
        <tr>
            <td class="bold">is_need_edit</td>
            <td align="center">int</td>
            <td>是否需要完善资料，0: 否，1: 是</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="c2.4" id="c2.4">》2.4、找回密码</a></h4>
    <br>
    <strong>[api_path]：</strong>/user/findpasswd.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/user/findpasswd');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">mobile</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>手机号</td>
        </tr>
        <tr>
            <td class="bold">passwd</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>新密码</td>
        </tr>
        <tr>
            <td class="bold">mcode</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>手机短信验证码</td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center">string</td>
            <td>用户找回密码后生成的会话id</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="c2.5" id="c2.5">》2.5、绑定手机&设置密码</a></h4>
    <br>
    <strong>[api_path]：</strong>/user/bindmobile.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/user/bindmobile');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">mobile</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>手机号</td>
        </tr>
        <tr>
            <td class="bold">mcode</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>手机短信验证码</td>
        </tr>
        <tr>
            <td class="bold">passwd</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>密码</td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center">string</td>
            <td>用户设置/修改密码后重新生成的会话id</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="c2.6" id="c2.6">》2.6、获取用户详细资料：</a></h4>
    <br>
    <strong>[api_path]：</strong>/user/profile.json
    <span class="btn_test" style="width: 51%"><a href="<?php echo $this->getApiUrl('/user/profile');?>" target="_blank">我的资料 》</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $this->getApiUrl('/user/profile');?>&uid=1" target="_blank">TA的资料 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">uid</td>
            <td align="center" class="red">选填</td>
            <td align="center">string</td>
            <td>用户uid（看TA的必须）</td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">user_info</td>
            <td align="center">object</td>
            <td>uid - 用户uid<br>nickname - 昵称<br>mobile - 手机号，如 130****2852<br>avatar - 小头像url<br>avatar_hd - 高清头像url<br>gender - 性别, 男/女（默认空或保密）<br>birthday - 生日, 如19990909<br>age - 年龄，如26<br>region - 地区信息<br> -- name - 地区名称<br> -- selected - list，已选择的地区列表<br> -- -- id - 地区id<br> -- -- name - 地区名称<br> -- -- has_subitem - 是否有子级<br>level - object，用户等级信息<br> -- num 等级值（显示对应Lv图标） <br> -- desc - 升级描述<br>status - object，用户状态信息<br> -- is_mine - 是否自己，0: 否，1: 是<br> -- is_binded_mobile - 是否绑定手机，0: 否，1: 是<br> -- is_setted_passwd - 是否设置密码，0: 否，1: 是<br> -- roses - 玫瑰数<br> -- points - 积分数<br> -- coins - 金币数<br> -- friending_roses - 加好友所需玫瑰数<br>friend_status - 好友状态，0: 非好友，1: 好友
            <br>permissions - object，用户权限控制<br> -- upgender，int 修改性别权限，0-无，1-有</td>
        </tr>
        <tr>
            <td class="bold">is_need_edit</td>
            <td align="center">int</td>
            <td>是否需要完善资料，0: 否，1: 是</td>
        </tr>
        <tr>
            <td class="bold">rc_user_token</td>
            <td align="center">string</td>
            <td>融云user token（用于连接融云服务器，定期更新）</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="c2.7" id="c2.7">》2.7、更新用户资料：</a></h4>
    <br>
    <strong>[api_path]：</strong>/user/editprofile.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/user/editprofile');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">new_profile</td>
            <td align="center" class="red">必须</td>
            <td align="center">json</td>
            <td>新的资料：（编辑某项，仅传某个值；如，编辑昵称，则只有nickname字段和值）<br><font color="red">更新用户头像：参数必须带上传token</font><br><br>nickname - 昵称<br>gender - 性别, 男/女（默认空或保密）<br>birthday - 生日, 如19990909<br>friending_rose_id - 好友门槛设置（选项id）<br>position - object，详细位置信息<br>-- name - 位置名称<br>-- address - 详细地址，如：浙江省 杭州市 西湖区 万塘路252号计量大厦<br>-- location - 经纬度，如：124.124587,34.054874</td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">user_info</td>
            <td align="center">object</td>
            <td>同上profile</td>
        </tr>
        <tr>
            <td class="bold">is_need_edit</td>
            <td align="center">int</td>
            <td>是否需要完善资料，0: 否，1: 是</td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
默认

<p>
    <h4><a name="c2.8" id="c2.8">》2.8、获取用户特权列表：</a></h4>
    <br>
    <strong>[api_path]：</strong>/user/privileges.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/user/privileges');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">list</td>
            <td align="center">list</td>
            <td>特权列表，<br>public_thread - int, 是否有喊话特权，0-无，1-有</td>
        </tr>
    </tbody>
</table>

<h2>游戏接口</h2>
<p>
    <h4><a name="g3.1" id="g3.1">》3.1、首页</a></h4>
    <br>
    <strong>[api_path]：</strong>/game/index.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/game/index');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">banners</td>
            <td align="center">list</td>
            <td>轮播图banner:<br><br>img - 图片<br>link - 链接地址或Url Scheme地址<br>type - 链接打开方式，0: Url Scheme直接解析打开，1: 内网webview打开，2: 外网webview打开
            <br>type = 0时，Url Scheme为：<br> - WanZhu://gongxiangequ - 贡献歌曲</td>
        </tr>
        <tr>
            <td class="bold">games</td>
            <td align="center">list</td>
            <td>游戏列表:<br><br>gid - 游戏id<br>name - 游戏名称<br>factor - 倍数因子<br>img - 游戏背景图</td>
        </tr>
        <tr>
            <td class="bold">recommend_users</td>
            <td align="center">list</td>
            <td>推荐用户列表:<br><br>uid - 用户uid<br>nickname - 昵称<br>avatar - 头像url<br>desc - 推荐描述</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="g3.2" id="g3.2">》3.2、推荐好友列表</a></h4>
    <br>
    <strong>[api_path]：</strong>/game/recommendusers.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/game/recommendusers');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">page</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>当前页数（默认为1）</td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">list</td>
            <td align="center">list</td>
            <td>推荐用户列表:<br><br>uid - 用户uid<br>nickname - 昵称<br>avatar - 头像url<br>level_num - 等级值（显示对应Lv图标）<br>friending_roses - 加好友所需玫瑰数<br>friend_status - 好友状态，0: 非好友，1: 好友</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="g3.3" id="g3.3">》3.3、游戏配置信息</a></h4>
    <br>
    <strong>[api_path]：</strong>/game/info.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/game/info');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">gid</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>游戏id</td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">info</td>
            <td align="center">object</td>
            <td>roomid - 游戏房间id<br>name - 游戏名称<br>players - 在线人数<br>factor - 倍数因子<br>quit_coins - 游戏中退出需扣的金币数<br>play_permission - 1-有权限玩，0-无权限玩<br>im_config - object，聊天组件配置<br> -- YuYin - 语音启用状态，0-关闭，1-开启</td>
        </tr>
    </tbody>
</table>

<p><h4><a>＝＝＝＝＝＝＝＝＝＝＝＝ 听歌曲猜歌名 ＝＝＝＝＝＝＝＝＝＝＝＝ </a></h4></p>

<p>
    <h4><a name="g3.4.1" id="g3.4.1">》3.4.1、同步游戏状态</a></h4>
    <br>
    <strong>[api_path]：</strong>/games/tgqcgm/sync.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/tgqcgm/sync');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">gid</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>游戏id</td>
        </tr>
        <tr>
            <td class="bold">status</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>当前在线状态（在线人数）</td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">status</td>
            <td align="center">int</td>
            <td>当前游戏状态，0-等待，1-可进入游戏</td>
        </tr>
        <tr>
            <td class="bold">waiting_sec</td>
            <td align="center">int</td>
            <td>等待倒计时（秒）</td>
        </tr>
        <tr>
            <td class="bold">tm_info</td>
            <td align="center">object</td>
            <td>当前游戏随机题目信息：<br><br>tm_id - 题目id<br>media - object，语音媒体信息<br> -- url - 下载url<br> -- duration - 时长<br>user_info - object， 贡献题目用户信息<br> -- uid - 用户id<br> -- nickname - 昵称<br> -- avatar - 头像<br> -- level_num - 等级值（显示对应Lv图标）<br>share_link - 分享链接<br>share_singer - 分享歌手昵称</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="g3.4.2" id="g3.4.2">》3.4.2、退出游戏</a></h4>
    <br>
    <strong>[api_path]：</strong>/games/tgqcgm/quit.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/tgqcgm/quit');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">tm_id</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>当前游戏题目id</td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：</b></p>
默认

<p>
    <h4><a name="g3.4.3" id="g3.4.3">》3.4.3、送玫瑰/砸鸡蛋</a></h4>
    <br>
    <strong>[api_path]：</strong>/games/tgqcgm/vote.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/tgqcgm/vote');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">tm_id</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>当前游戏题目id</td>
        </tr>
        <tr>
            <td class="bold">type</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>1-送玫瑰，2-砸鸡蛋</td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：</b></p>
默认

<p>
    <h4><a name="g3.4.4" id="g3.4.4">》3.4.4、抢答</a></h4>
    <br>
    <strong>[api_path]：</strong>/games/tgqcgm/answer.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/tgqcgm/answer');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">tm_id</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>当前游戏题目id</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>答案内容</td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">result</td>
            <td align="center">int</td>
            <td>0-错，1-对</td>
        </tr>
        <tr>
            <td class="bold">desc</td>
            <td align="center">string</td>
            <td>描述</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="g3.4.5" id="g3.4.5">》3.4.5、公布答案</a></h4>
    <br>
    <strong>[api_path]：</strong>/games/tgqcgm/result.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/tgqcgm/result');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">tm_id</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>当前游戏题目id</td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">info</td>
            <td align="center">object</td>
            <td>答案信息：<br><br>media_name - 歌曲名称<br>media_singer - 原唱歌手<br>correct_percent - 答对比例（%）<br>desc - 结果描述<br></td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="g3.4.6" id="g3.4.6">》3.4.6、贡献题目 </a></h4>
    <br>
    <strong>[api_path]：</strong>/games/tgqcgm/uploadtm.json <font color="red">（需验证上传token）</font>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/tgqcgm/uploadtm');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">media_name</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>歌曲名称</td>
        </tr>
        <tr>
            <td class="bold">media_singer</td>
            <td align="center" class="red">选填</td>
            <td align="center">int</td>
            <td>歌曲原唱歌手</td>
        </tr>
        <tr>
            <td class="bold">media_duration</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>歌曲时长</td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">tm_id</td>
            <td align="center">int</td>
            <td>题目id</td>
        </tr>
        <tr>
            <td class="bold">share_link</td>
            <td align="center">string</td>
            <td>分享链接</td>
        </tr>
    </tbody>
</table>

<h2>社区接口</h2>
<p>
    <h4><a name="t4.1" id="t4.1">》4.1、首页帖子列表（喊话/爆照）</a></h4>
    <br>
    <strong>[api_path]：</strong>/thing/threadlist.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/thing/threadlist');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">category</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>帖子类型：1-喊话（默认），2-爆照</td>
        </tr>
        <tr>
            <td class="bold">page</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>当前页数（默认为1）</td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">list</td>
            <td align="center">list</td>
            <td>帖子列表:<br><br>is_mine - 是否自己，0: 否，1: 是<br>has_sent_rose - 是否有送过玫瑰，0: 否，1: 是<br><br>thread_info - object, 帖子信息<br>{<br> tid - 帖子id<br> category - 帖子类型：1-喊话（默认），2-爆照<br> content - 内容<br> images - object, 图片信息<br> -- s_url - 小图url<br> -- b_url - 大图url<br> roses - 玫瑰数<br> ctime - 发布时间戳<br> share_link - 分享链接<br> extend_url - 扩展帖子链接，空则不处理，非空则webview内网打开url(全屏无Nav)<br><br> user_info - object, 用户信息<br> -- uid - 用户id<br> -- nickname - 昵称<br> -- avatar - 头像url<br> -- level_num - 等级值（显示对应Lv图标）<br> -- desc - 描述<br>}<br><br>posts - list, 回复列表<br> -- is_mine - 是否自己，0: 否，1: 是<br> -- pid - 回复id<br> -- content - 回复内容<br> -- user_info - object, 用户信息<br> -- -- uid - 用户id<br> -- -- nickname - 昵称<br> -- replied_user_info - object, 被回复用户信息<br> -- -- uid - 用户id<br> -- -- nickname - 昵称</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="t4.2" id="t4.2">》4.2、帖子详情（喊话/爆照）</a></h4>
    <br>
    <strong>[api_path]：</strong>/thing/threaddetail.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/thing/threaddetail');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">tid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>帖子id</td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">is_mine</td>
            <td align="center">int</td>
            <td>是否自己，0: 否，1: 是</td>
        </tr>
        <tr>
            <td class="bold">has_sent_rose</td>
            <td align="center">int</td>
            <td>是否有送过玫瑰，0: 否，1: 是</td>
        </tr>
        <tr>
            <td class="bold">thread_info</td>
            <td align="center">object</td>
            <td>帖子信息，见/thing/threadlist，thread_info对象数据结构</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="t4.3" id="t4.3">》4.3、帖子回复列表（喊话/爆照）</a></h4>
    <br>
    <strong>[api_path]：</strong>/thing/threadpostlist.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/thing/threadpostlist');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">tid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>帖子id</td>
        </tr>
        <tr>
            <td class="bold">page</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>当前页数（默认为1）</td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">list</td>
            <td align="center">list</td>
            <td>回复列表：<br><br>is_mine - 是否自己，0: 否，1: 是<br>pid - 回复id<br>content - 回复内容<br>user_info - object, 用户信息<br>-- uid - 用户id<br>-- nickname - 昵称<br>replied_user_info - object, 被回复用户信息<br>-- uid - 用户id<br>-- nickname - 昵称</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="t4.4" id="t4.4">》4.4、上传帖子附件（图片）</a></h4>
    <br>
    <strong>[api_path]：</strong>/thing/uploadattach.json <font color="red">（需验证上传token）</font>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/thing/uploadattach');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">order_id</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>顺序id</td>
        </tr>
        <tr>
            <td class="bold">t_key</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>t_key为时间戳（秒，10位）<font color="red">（同一个帖子的多张图片上传，t_key值一致）</font></td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">info</td>
            <td align="center">object</td>
            <td>附件图片信息:<br><br>order:aid，顺序id:附件id</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="t4.5" id="t4.5">》4.5、发布帖子（喊话/爆照）</a></h4>
    <br>
    <strong>[api_path]：</strong>/thing/publishthread.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/thing/publishthread');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">category</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>帖子类型：1-喊话，2-爆照</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>帖子内容</td>
        </tr>
        <tr>
            <td class="bold">t_key</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td><font color="red">同附件上传的t_key值一致</font></td>
        </tr>
        <tr>
            <td class="bold">aids</td>
            <td align="center" class="red">必须</td>
            <td align="center">json</td>
            <td>上传成功返回的多个info，拼装成一个json对象：
            <br>{"1": "461435153504278"}</td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">tid</td>
            <td align="center">string</td>
            <td>帖子id</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="t4.6" id="t4.6">》4.6、回复帖子</a></h4>
    <br>
    <strong>[api_path]：</strong>/thing/postthread.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/thing/postthread');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">tid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>帖子id</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>帖子内容</td>
        </tr>
        <tr>
            <td class="bold">replied_uid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>被回复的用户uid（引用回复必须）</td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">pid</td>
            <td align="center">string</td>
            <td>回复id</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="t4.7" id="t4.7">》4.7、点赞帖子（并给楼主送玫瑰）</a></h4>
    <br>
    <strong>[api_path]：</strong>/thing/votethread.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/thing/votethread');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">tid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>帖子id</td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">votes</td>
            <td align="center">int</td>
            <td>点赞总数</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="t4.8" id="t4.8">》4.8、webview上传图片</a></h4>
    <br>
    <strong>[api_path]：</strong>/thing/webviewupload.json <font color="red">（需验证上传token）</font>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/thing/webviewupload');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">t_key</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>t_key为时间戳（秒，10位）<font color="red">（同一个帖子的多张图片上传，t_key值一致）</font></td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">info</td>
            <td align="center">json</td>
            <td>图片信息:<br><br>{"code":1,"fileInfo":{"aid":"1231482068079302","file_type":"image\/png","file_uri":"\/up\/161218\/","file_name":"0934_1231482068079302_949180fb328d80416fa9f406c622fd47","width":51,"height":50}}</td>
        </tr>
    </tbody>
</table>


<h2>我的接口</h2>
<p>
    <h4><a name="um5.1" id="um5.1">》5.1、我（TA）的主页</a> </h4>
    <br>
    <strong>[api_path]：</strong>/umine/timeline.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/umine/timeline');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">uid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户uid</td>
        </tr>
        <tr>
            <td class="bold">page</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>当前页数（默认为1）</td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">list</td>
            <td align="center">list</td>
            <td>动态列表，见/thing/threadlist，thread_info对象数据结构</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="um5.2" id="um5.2">》5.2、添加/解除好友</a> </h4>
    <br>
    <strong>[api_path]：</strong>/umine/friending.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/umine/friending');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">uid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>对方用户uid</td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">friend_status</td>
            <td align="center">int</td>
            <td>好友状态，0: 非好友，1: 好友</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="um5.3" id="um5.3">》5.3、拉黑好友（仅是好友时调用）</a> </h4>
    <br>
    <strong>[api_path]：</strong>/umine/blackfriend.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/umine/blackfriend');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">uid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>对方用户uid</td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：</b></p>
默认

<p>
    <h4><a name="um5.4" id="um5.4">》5.4、好友列表</a> </h4>
    <br>
    <strong>[api_path]：</strong>/umine/friends.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/umine/friends');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">page</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>当前页数（默认为1）</td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">list</td>
            <td align="center">list</td>
            <td>好友列表:<br><br>uid - 用户uid<br>nickname - 昵称<br>avatar - 头像url<br>level_num - 等级值（显示对应Lv图标）</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="um5.5" id="um5.5">》5.5、删除我的帖子</a> </h4>
    <br>
    <strong>[api_path]：</strong>/umine/delthread.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/umine/delthread');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">tid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>帖子id</td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">list</td>
            <td align="center">list</td>
            <td>好友列表:<br><br>uid - 用户uid<br>nickname - 昵称<br>avatar - 头像url<br>level_num - 等级值（显示对应Lv图标）</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="um5.6" id="um5.6">》5.6、删除我的回复</a> </h4>
    <br>
    <strong>[api_path]：</strong>/umine/delthreadpost.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/umine/delthreadpost');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">tid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>帖子id</td>
        </tr>
        <tr>
            <td class="bold">pid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>帖子回复id</td>
        </tr>
    </tbody>
</table>

<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">replies</td>
            <td align="center">int</td>
            <td>回复数</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="um5.7" id="um5.7">》5.7、屏蔽用户帖子（屏蔽后用户所有帖子不可见）</a> </h4>
    <br>
    <strong>[api_path]：</strong>/umine/disappearuser.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/umine/disappearuser');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">uid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>对方用户uid</td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：</b></p>
默认