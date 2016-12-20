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

<h2>游戏接口</h2>
<p>
    <h4><a name="g1.1" id="g1.1">》1.1、首页</a></h4>
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
            <td class="bold">header</td>
            <td align="center">object</td>
            <td>头部装饰图片:<br><br>bg_url - 背景图，为空则不显示<br>avatar_decorated_url - 头像装饰图片，为空则不显示</td>
        </tr>
        <tr>
            <td class="bold">banners</td>
            <td align="center">list</td>
            <td>轮播图banner:<br><br>img - 昵称<br>link - 链接地址或Url Scheme地址（根据协议跳转；webview统一带上Header头信息）
            <br>链接地址：<br> - WanZhu://gongxiangequ - 贡献歌曲
            <br>Url Scheme地址：<br> - http://www.wanzhuyouxi.com</td>
        </tr>
        <tr>
            <td class="bold">games</td>
            <td align="center">list</td>
            <td>游戏列表:<br><br>gid - 游戏id<br>name - 游戏名称<br>players - 在线人数<br>img - 游戏背景图<br>link - 链接地址或Url Scheme地址<br>skin_color - 皮肤16进制颜色，如#cccccc（Nav和backgroud颜色设置）</td>
        </tr>
        <tr>
            <td class="bold">recommend_users</td>
            <td align="center">list</td>
            <td>推荐用户列表:<br><br>uid - 用户uid<br>nickname - 昵称<br>avatar - 头像url<br>desc - 推荐描述</td>
        </tr>
    </tbody>
</table>

